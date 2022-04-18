<?php
 /**
  *------
  * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
  * HadriansWall implementation : © <Your name here> <Your email address here>
  * 
  * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
  * See http://en.boardgamearena.com/#!doc/Studio for more information.
  * -----
  * 
  * hadrianswall.game.php
  *
  * This is the main file for your game logic.
  *
  * In this PHP file, you are going to defines the rules of the game.
  *
  */


require_once( APP_GAMEMODULE_PATH.'module/table/table.game.php' );


class HadriansWall extends Table
{
    const GAME_ROUND = 'game_round';

	function __construct( )
	{
        parent::__construct();
        
        self::initGameStateLabels( array( 
            self::GAME_ROUND => 10,
        ) );        

        $this->player_cards=self::getNew('module.common.deck');
        $this->player_cards->init('player_cards');
        $this->fate_cards=self::getNew('module.common.deck');
        $this->fate_cards->init('fate_cards');
	}
	
    protected function getGameName( )
    {
        return "hadrianswall";
    }	

    /*
        setupNewGame 
    */

    protected function setupNewGame( $players, $options = array() )
    {    
        // Standard Game Setup
        self::debug( "PHP - setupNewGame" ); 
        $gameinfos = self::getGameinfos();
        $default_colors = $gameinfos['player_colors'];
        $player_sql = "INSERT INTO player (player_id, player_color, player_canal, player_name, player_avatar) VALUES ";
        $values = [];


        // Initilize the game board and cards for the game
        $board_sql = "INSERT INTO board (`round`,player_id) VALUES ";
        $board_values = [];

        foreach( $players as $player_id => $player )
        {
            $color = array_shift( $default_colors );
            $values[] = "('".$player_id."','$color','".$player['player_canal']."','".addslashes( $player['player_name'] )."','".addslashes( $player['player_avatar'] )."')";

            $board_values[] = "(0,'".$player_id."')";

            $player_cards=[];
            for($index=0; $index<12; $index++) {
                $player_cards[] = ['type'=>'player_card_'.($index+1),'type_arg'=>$player_id,'nbr'=>1];
            }
            $this->player_cards->createCards($player_cards,$player_id."_deck");   
            $this->player_cards->shuffle($player_id."_deck"); 
        }
        $player_sql .= implode( $values, ',' );
        self::DbQuery( $player_sql );
        $board_sql .= implode( $board_values, ',' );        
        self::DbQuery( $board_sql );

        $fate_cards=[];
        for($index=0; $index<48; $index++) {
            $fate_cards[] = ['type'=>'fate_card_'.($index+1),'type_arg'=>$index,'nbr'=>1];
        }
        $this->fate_cards->createCards($fate_cards,'fate_deck');
        $this->fate_cards->shuffle('fate_deck');

        //self::reattributeColorsBasedOnPreferences( $players, $gameinfos['player_colors'] );
        self::reloadPlayersBasicInfos();
        
        $this->setGameStateInitialValue(self::GAME_ROUND,0);

        // Activate first player (required)
        $this->activeNextPlayer();
    }

    /*
        getAllDatas: 
    */
    protected function getAllDatas()
    {
        $result = array();
    
        $current_player_id = self::getCurrentPlayerId();    // !! We must only return informations visible by this player !!
    
        // Get information about players
        // Note: you can retrieve some extra field you added for "player" table in "dbmodel.sql" if you need it.
        $player_sql = "SELECT player_id id, player_color color, player_score score FROM player ";
        $result['players'] = self::getCollectionFromDb( $player_sql );
  
        $board_sql = "SELECT * FROM board WHERE player_id = $current_player_id";
        $result['board'] = self::getCollectionFromDb( $board_sql );

        $score_sql = "SELECT renown, piety, valour, discipline, disdain, player_id id FROM board";
        $result['scores'] = self::getCollectionFromDb( $score_sql );

        // $display_round = max([0,$current_round-1]);
        // $opsql = "SELECT player_id, renown, piety, valour, discipline, disdain FROM board WHERE `round`=$display_round";
        // $result['score_boards'] = self::getCollectionFromDb( $opsql );

        $current_round = $this->getGameStateValue(self::GAME_ROUND);
        $result['round'] = $current_round;
        $result['attack_potential'] = [1,2,2,3,3,4];
        $result['difficulty'] = 'easy';
        $result['attacks'] = [1,2,3,4,6,8];

        $result['resources'] = $this->getResources();

        return $result;
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////
    //    Standard Game Functions
    ////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////

    function getGameProgression()
    {
        $current_round = $this->getGameStateValue(self::GAME_ROUND);
        return max([1,($current_round-1)/6*100]);
    }

    private function isPlayerActive($playerId){
        return array_search($playerId, $this->gamestate->getActivePlayerList()) !== false;
    }


    ////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////
    //    Database Wrappers
    ////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////

    // Board

    function getBoard() {
        self::debug("--->getBoard");
        $current_player_id = self::getCurrentPlayerId();
        $board_sql = "SELECT * FROM board WHERE player_id = $current_player_id";
        $board = self::getCollectionFromDb( $board_sql );
        $results = $board[$current_player_id];
        return $results;
    }

    // Resources

    function getResources() {
        self::debug("--->getResources");
        $current_player_id = self::getCurrentPlayerId();
        $resource_sql = "SELECT player_id, `civilians`, `servants`, `soldiers`, `builders`, `bricks`, `special` FROM player WHERE player_id=$current_player_id";
        self::debug($resource_sql);
        $result = self::getCollectionFromDb($resource_sql)[$current_player_id];
        return $result;
    }

    function setResources($resources) {
        self::debug("--->setResources");
        $current_player_id = self::getCurrentPlayerId();
        $resource_sql = "UPDATE player SET ";

        $rarray = ['civilians', 'servants', 'soldiers', 'builders', 'bricks', 'special'];
        $updates=[];
        foreach($rarray as $resource) {
            if(array_key_exists($resource, $resources)) {
                $updates[]=("$resource=".$resources[$resource]."");
            }
        }

        $resource_sql.=implode( $updates, ',' );
        $resource_sql.=" WHERE player_id=$current_player_id";

        self::debug($resource_sql);

        self::DbQuery($resource_sql);        
        $result = self::getResources();
        return $result;
    }

    function adjResources($resources) {
        self::debug("--->adjResources");
        $current_player_id = self::getCurrentPlayerId();
        $resource_sql = "UPDATE player SET ";

        $rarray = ['civilians', 'servants', 'soldiers', 'builders', 'bricks', 'special'];
        $updates=[];
        foreach($rarray as $resource) {
            if(array_key_exists($resource, $resources)) {
                $updates[]=("$resource=$resource+".$resources[$resource]."");
            }
        }

        $resource_sql.=implode( $updates, ',' );
        $resource_sql.=" WHERE player_id=$current_player_id";

        self::debug($resource_sql);

        self::DbQuery($resource_sql);        
        $result = self::getResources();
        return $result;
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////
    //    Gameplay Functions
    ////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////

    function doCheckNextBox( $section ) {
        // todo - make sure resources and prereqs are met
        // todo - reduce resources as needed

        $current_player_id = self::getCurrentPlayerId();

        $board_sql = "UPDATE board SET $section = $section + 1 WHERE player_id=$current_player_id";
        self::DbQuery( $board_sql );

        $board_sql = "SELECT * FROM board WHERE player_id = $current_player_id";
        $board = self::getCollectionFromDb( $board_sql );

        $this->notifyPlayer( $current_player_id, "sheetsUpdated", "", [
            "board"=>$board[$current_player_id],
            "valid_moves"=>$this->getValidMoves()
        ]);
    }

    function checkNextBox( $section ) {
        $this->checkAction('checkNextBox');
        $current_player_id = self::getCurrentPlayerId();

        $this->doCheckNextBox($section);
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////
    //    User triggered Actions
    ////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////

    function acceptFateResources() {
        $this->checkAction('acceptFateResources');
        $current_player_id = self::getCurrentPlayerId();

        $round = $this->getGameStateValue(self::GAME_ROUND);
        $round_sql = "SELECT `round`, `fate_resource_card` FROM rounds WHERE `round`=".$round;
        $round_info = self::getCollectionFromDB($round_sql);
        $card = $round_info[$round]['fate_resource_card'];

        $soldiers  = $this->fate_card_data[$card]['soldiers'];
        $builders  = $this->fate_card_data[$card]['builders'];
        $servants  = $this->fate_card_data[$card]['servants'];
        $civilians = $this->fate_card_data[$card]['civilians'];
        $bricks    = $this->fate_card_data[$card]['bricks'];

        $this->setResources([
            'soldiers'=>$soldiers,
            'builders'=>$builders,
            'servants'=>$servants,
            'civilians'=>$civilians,
            'bricks'=>$bricks,
        ]);

        $this->notifyPlayer( $current_player_id, "resourcesUpdated", "", [
            'resources'=>$this->getResources(),
            'change'=>[
                'card'=>$card,
                'soldiers'=>$this->fate_card_data[$card]['soldiers'],
                'builders'=>$this->fate_card_data[$card]['builders'],
                'servants'=>$this->fate_card_data[$card]['servants'],
                'civilians'=>$this->fate_card_data[$card]['civilians'],
                'bricks'=>$this->fate_card_data[$card]['bricks'],
            ]
        ]);

        $this->gamestate->nextPrivateState($current_player_id, 'acceptProducedResources');
    }

    function acceptProducedResources() {
        $this->checkAction('acceptProducedResources');
        $current_player_id = self::getCurrentPlayerId();

        $resources = $this->adjResources(['bricks'=>1]);
        
        $this->notifyPlayer( $current_player_id, "resourcesUpdated", "", [
            'resources'=>$this->getResources(),
            'change'=>[
                    'bricks'=>1
                ]         
        ]);

        $hasGeneratedResources = false;
        if($hasGeneratedResources) {
            $this->gamestate->nextPrivateState($current_player_id, 'chooseGeneratedAttributes');
        } else {
            $this->gamestate->nextPrivateState($current_player_id, 'chooseGoalCard');
        }
        
    }

    function chooseAttribute($attribute) {
        $this->checkAction('chooseAttribute');
        $current_player_id = self::getCurrentPlayerId();

        self::debug("attribute = $attribute");
        if($attribute=="none") {
            self::debug("No attribute chosen");
        } else {
            $this->doCheckNextBox($attribute);
        }

        $this->gamestate->nextPrivateState($current_player_id, 'chooseGoalCard');
    }

    function chooseGoalCard($goal_card) {
        $this->checkAction('chooseGoalCard');
        $current_player_id = self::getCurrentPlayerId();
        $round = $this->getGameStateValue(self::GAME_ROUND);

        $hand = $this->player_cards->getCardsInLocation($current_player_id."_hand");
        self::debug(print_r($hand,true));

        $goal_card_id = -1;
        $resource_card = 'unknown';
        $resource_card_id = -1;
        
        foreach($hand as $card_id => $hand_card) {
            self::debug("hand_card ".$hand_card['type']."  (".$hand_card['id'].")");
            if($hand_card['type']!=$goal_card) {
                $resource_card = $hand_card['type'];
                $resource_card_id = $hand_card['id'];
            } else {
                $goal_card_id = $hand_card['id'];
            }
        }

        self::debug("picked card ".$goal_card."  (".$goal_card_id.")");
        self::debug("resource card ".$resource_card."  (".$resource_card_id.")");

        $resource_card_data = $this->player_card_data[$resource_card];
        $this->adjResources($resource_card_data);

        $this->notifyPlayer( $current_player_id, "resourcesUpdated", "", [
            'resources'=>$this->getResources(),
            'change'=>$resource_card_data
        ]);

        $this->player_cards->moveCard($goal_card_id,$current_player_id."_goals",$round);
        $this->player_cards->moveCard($resource_card_id,$current_player_id."_discard");

        $goals = $this->player_cards->getCardsInLocation($current_player_id."_goals",null,'location_arg');
        $goal_cards = [];
        foreach($goals as $id => $card ) {
            $goal_cards[] = $card['type'];
        }

        // notify goal board changed
        $this->notifyPlayer( $current_player_id, "goalsUpdated", "", [
            'goals'=>$goal_cards
        ]);

        $this->gamestate->nextPrivateState($current_player_id, 'useResources');
    }

    function endTurn() {
        $this->checkAction('endTurn');
        $current_player_id = self::getCurrentPlayerId();

        self::debug("end turn");

        $this->gamestate->setPlayerNonMultiactive($current_player_id, 'endOfRound');
    }

    function acceptAttackResults() {
        $this->checkAction('acceptAttackResults');
        $current_player_id = self::getCurrentPlayerId();

        self::debug("acceptAttackResults");

        $this->gamestate->setPlayerNonMultiactive($current_player_id, 'checkEndGame');
    }    


      ///////   ////   /////    //// 
        //     //  //  //  //  //  //
        //     //  //  //  //  //  //
        //      ////   ////     //// 
    ////////////////////////////////////  
    function undoCheck() {
        $this->checkAction('undoCheck');
        $current_player_id = self::getCurrentPlayerId();

        self::debug("TODO: undo last check");

        //$this->gamestate->nextPrivateState($current_player_id, ' ');
    }

      ///////   ////   /////    //// 
        //     //  //  //  //  //  //
        //     //  //  //  //  //  //
        //      ////   ////     //// 
    ////////////////////////////////////  
    function restartRound() {
        $this->checkAction('restartRound');
        $current_player_id = self::getCurrentPlayerId();

        self::debug("TODO: restart round");

        //$this->gamestate->nextPrivateState($current_player_id, ' ');
    }
    
      ///////   ////   /////    //// 
        //     //  //  //  //  //  //
        //     //  //  //  //  //  //
        //      ////   ////     //// 
    ////////////////////////////////////  
    function useFavor() {
        $this->checkAction('useFavor');

        self::debug("TODO: useFavor");
    }

      ///////   ////   /////    //// 
        //     //  //  //  //  //  //
        //     //  //  //  //  //  //
        //      ////   ////     //// 
    ////////////////////////////////////  
    function doneUsingFavor() {
        $this->checkAction('doneUsingFavor');

        self::debug("TODO: doneUsingFavor");
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////
    //    Game State Functions
    ////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////
    function stGameSetup() {
        self::debug( "----> stGameSetup" ); 
    }

    function stPrepareRound() {
        self::debug( "----> stPrepareRound" ); 

        $this->incGameStateValue(self::GAME_ROUND,1);
        $round = $this->getGameStateValue(self::GAME_ROUND);

        $card = $this->fate_cards->pickCardForLocation('fate_deck','fate_discard');

        self::debug("picked ".$card['type']);
        $round_sql = "INSERT INTO `rounds` (`round`,`fate_resource_card`) VALUES (".$round.",'".$card['type']."')";
        self::DbQuery( $round_sql );
      
        // draw player cards for each player
        $player_cards=[];
        $players = self::loadPlayersBasicInfos();
        foreach($players as $player_id => $player) {
            $player_cards[]=$this->player_cards->pickCardsForLocation(2,$player_id."_deck",$player_id."_hand");
        }

        $this->notifyAllPlayers( "newRound", clienttranslate("Round ".$round." starts with ".$card['type']), [
            "round" => $round,
            "fate_resource_card" => $card['type'],
            "player_cards" => $player_cards
        ]);

        $this->gamestate->nextState('playerTurn');
    }

    function argFateResources() {
        $round = $this->getGameStateValue(self::GAME_ROUND);
        $round_sql = "SELECT `round`, `fate_resource_card` FROM rounds WHERE `round`=".$round;
        $round_info = self::getCollectionFromDB($round_sql);
        $card = $round_info[$round]['fate_resource_card'];

        return [
            'round'=>$this->getGameStateValue(self::GAME_ROUND),
            'card'=>$card,
            'soldiers'=>$this->fate_card_data[$card]['soldiers'],
            'builders'=>$this->fate_card_data[$card]['builders'],
            'servants'=>$this->fate_card_data[$card]['servants'],
            'civilians'=>$this->fate_card_data[$card]['civilians'],
            'bricks'=>$this->fate_card_data[$card]['bricks'],
        ];
    }

    function argProducedResources() {
        $current_player_id = self::getCurrentPlayerId();
        $round = 0;//$this->getGameStateValue(self::GAME_ROUND);
        $board_sql = "SELECT player_id, resource_production, hotel, workshop FROM board WHERE `round`=$round AND player_id=$current_player_id";
        $board = self::getCollectionFromDB($board_sql)[$current_player_id];

        $bricks = 1+$board['resource_production'];
        $civilians = $board['hotel'];
        $builders = $board['workshop'];

        return ['bricks'=>$bricks,'civilians'=>$civilians,'builders'=>$builders];
    }

    function argChooseGoalCard() {
        $current_player_id = self::getCurrentPlayerId();
        $hand = $this->player_cards->getCardsInLocation($current_player_id."_hand");
        $cards=[];
        foreach($hand as $card) {
            $card_data = $this->player_card_data[$card['type']];
            $cards[]=[
                'card'=>$card['type'],
                'name'=>$card_data['name'],
                'soldiers'=>$card_data['soldiers'],
                'builders'=>$card_data['builders'],
                'servants'=>$card_data['servants'],
                'civilians'=>$card_data['civilians'],
                'bricks'=>$card_data['bricks'],
                ];
        }

        return $cards;
    }

    function getValidMoves() {
        self::debug( "----> getValidMoves" ); 
        $valid_moves = [];
        $costs = [];
        $section_data = $this->section_data;
        $board = $this->getBoard();
        $resources = $this->getResources();

        // make it easier to compare needs with haves
        $resources['civilian'] = $resources['civilians'];
        $resources['servant'] = $resources['servants'];
        $resources['soldier'] = $resources['soldiers'];
        $resources['builder'] = $resources['builders'];
        $resources['brick'] = $resources['bricks'];

        foreach($section_data as $id=>$data) {
            $index = $board[$id];
            $section = $data[0]['id'];

            // check to see if it's locked
            if(array_key_exists('lockedBy',$data[$index])) {
                $locked = explode(",",$data[$index]['lockedBy']);
                $locked_section = $locked[0];
                $required_level = $locked[1];
                // self::debug("$section is locked by $locked_section at $required_level");
                if($board[$locked_section]<$required_level) {
                    continue;
                }
            }

            if(array_key_exists('cost',$data[$index])) {
                $cost_lists = explode(",",$data[$index]['cost']);
                $altCost_lists = [];
                if(array_key_exists('altCost',$data[$index])) {
                    $altCost_lists = explode(",",$data[$index]['altCost']);
                }
                                
                $needed =[];
                $alt_needed =[];
                foreach($cost_lists as $resource) {
                    if(array_key_exists($resource,$needed)){
                        $needed[$resource]++;
                    } else {
                        $needed[$resource]=1;
                    }
                }
                foreach($altCost_lists as $resource) {
                    if(array_key_exists($resource,$alt_needed)){
                        $alt_needed[$resource]++;
                    } else {
                        $alt_needed[$resource]=1;
                    }
                }

                $costs[] = ['cost'=>$needed,'altCost'=>$alt_needed]; // DEBUG
                
                // self::debug($id." has a cost of ".print_r($needed,true));
                
                $valid = false;
                foreach($needed as $resource=>$amount) {
                    $valid = true;
                    // self::debug("Checking $resource is at least $amount in ".print_r($resources,true));
                    if(!array_key_exists($resource,$resources) || $resources[$resource]<$amount) {
                        self::debug("Missing resource");
                        $valid = false;
                    }
                }
        
                if(!$valid) {
                    foreach($alt_needed as $resource=>$amount) {
                        $valid = true;
                        // self::debug("Checking alt $resource is at least $amount in ".print_r($resources,true));
                        if(!array_key_exists($resource,$resources) || $resources[$resource]<$amount) {
                            // self::debug("Still missing resource");
                            $valid = false;
                        }
                    }
                }

                if($valid) {
                    $valid_moves[]=$data[$index]['id'];
                }
            } 
        }

        // $results = [
        //     'section_data'=>$section_data,
        //     'resources'=>$resources,
        //     'board'=>$board,
        //     'costs'=>$costs,
        //     'valid_moves'=>$valid_moves
        // ];
        
        return $valid_moves;
    }


    function argUseResources() {
        self::debug( "----> argUseResources" ); 
        $results=[];

        $results = $this->section_data;

        $results = $this->getValidMoves();



        return $results;
    }

    function stAcceptPictAttack() {
        self::debug( "----> stAcceptPictAttack" ); 

        $this->gamestate->setAllPlayersMultiactive();
        $this->gamestate->initializePrivateStateForAllActivePlayers();
    }

    function stPlayerTurn() {
        self::debug( "----> stPlayerTurn" ); 

        $this->gamestate->setAllPlayersMultiactive();
        $this->gamestate->initializePrivateStateForAllActivePlayers();
    }


      ///////   ////   /////    //// 
        //     //  //  //  //  //  //
        //     //  //  //  //  //  //
        //      ////   ////     //// 
    ////////////////////////////////////  
    function stChooseGeneratedAttributes() {
        self::debug( "----> stChooseGeneratedAttributes" ); 

        $current_player_id = self::getCurrentPlayerId();
        self::debug("current_player_id = ".$current_player_id);        
    }

      ///////   ////   /////    //// 
        //     //  //  //  //  //  //
        //     //  //  //  //  //  //
        //      ////   ////     //// 
    ////////////////////////////////////  
    function argChooseGeneratedAttributes()
    {
        return [
            //'renown','piety','valour','discipline'
        ];
    }

      ///////   ////   /////    //// 
        //     //  //  //  //  //  //
        //     //  //  //  //  //  //
        //      ////   ////     //// 
    ////////////////////////////////////  
    function stEndOfRound() {
        self::debug( "----> stEndOfRound" ); 

        // TODO: draw pict attack

        $this->gamestate->nextState('acceptPictAttack');
    }

      ///////   ////   /////    //// 
        //     //  //  //  //  //  //
        //     //  //  //  //  //  //
        //      ////   ////     //// 
    ////////////////////////////////////  
    function stCheckFavor() {
        self::debug( "----> stCheckFavor" ); 
        $current_player_id = self::getCurrentPlayerId();

        $this->gamestate->nextPrivateState($current_player_id,'useFavor');
    }

      ///////   ////   /////    //// 
        //     //  //  //  //  //  //
        //     //  //  //  //  //  //
        //      ////   ////     //// 
    ////////////////////////////////////  
    function stCheckGameEnd() {
        self::debug( "----> stCheckGameEnd" ); 

        $this->gamestate->nextState('nextRound');
    }


    ////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////
    //    Zombie
    ////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////

    function zombieTurn( $state, $active_player )
    {
    	$statename = $state['name'];
    	
        if ($state['type'] === "activeplayer") {
            switch ($statename) {
                default:
                    $this->gamestate->nextState( "zombiePass" );
                	break;
            }

            return;
        }

        if ($state['type'] === "multipleactiveplayer") {
            // Make sure player is in a non blocking status for role turn
            $this->gamestate->setPlayerNonMultiactive( $active_player, '' );
            
            return;
        }

        throw new feException( "Zombie mode not supported at this game state: ".$statename );
    }
    
    ////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////
    //    DB Upgrades
    ////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    function upgradeTableDb( $from_version )
    {

    }    
}
