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
    const GAME_DIFFICULTY = 'game_difficulty';
    const PASS_RESOURCE = 'pass_resource';

	function __construct( )
	{
        parent::__construct();
        
        self::initGameStateLabels( array( 
            self::GAME_ROUND => 10,
            self::GAME_DIFFICULTY => 100, // from options
            self::PASS_RESOURCE => 101,   // from options
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
        //self::debug( "PHP - setupNewGame" ); 
        $gameinfos = self::getGameinfos();
        $default_colors = $gameinfos['player_colors'];
        $player_sql = "INSERT INTO player (player_id, player_color, player_canal, player_name, player_avatar) VALUES ";
        $values = [];


        // Initilize the game board and cards for the game
        $board_sql = "INSERT INTO board (`round`,player_id) VALUES ";
        $board_values = [];

        foreach( $players as $player_id => $player )
        {
            self::DbQuery("INSERT INTO resources (player_id) VALUES ($player_id)");

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
  
        //$board_sql = "SELECT * FROM board WHERE player_id = $current_player_id";
        //$result['board'] = self::getCollectionFromDb( $board_sql );

        $result['board'] = $this->getBoard();


        //$this->scorePathCards(null,$result['board'][$current_player_id]); // DEBUG


        $score_sql = "SELECT renown, piety, valour, discipline, disdain, approve, player_id id FROM board";
        $result['scores'] = self::getCollectionFromDb( $score_sql );

        $result['score_column'] = $this->getScoreColumn();

        // $display_round = max([0,$current_round-1]);
        // $opsql = "SELECT player_id, renown, piety, valour, discipline, disdain FROM board WHERE `round`=$display_round";
        // $result['score_boards'] = self::getCollectionFromDb( $opsql );

        $paths = $this->player_cards->getCardsInLocation($current_player_id."_paths",null,'location_arg');
        $path_cards = [];
        foreach($paths as $id => $card ) {
            $path_cards[] = $card['type'];
        }
        $result['paths'] = $path_cards;

        $current_round = $this->getGameStateValue(self::GAME_ROUND);
        $result['round'] = $current_round;
        $difficulty = $this->getGameStateValue(self::GAME_DIFFICULTY);
        $difficulty_label = [1=>"Easy",2=>"Normal",3=>"Hard"][$difficulty];
        $result['difficulty'] = $difficulty_label;

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

    function updateScore() {
        $current_player_id = self::getCurrentPlayerId();
        $score_column = $this->getScoreColumn();
        $score = $score_column['total'];
        
        self::DbQuery("UPDATE player SET player_score=$score WHERE player_id=$current_player_id");

        return $score_column;
    }

    function getScoreColumn() {
        $current_player_id = self::getCurrentPlayerId();
        $score_sql = "SELECT renown, piety, valour, discipline, disdain, approve, player_id id FROM board WHERE player_id=$current_player_id";
        $attr = self::getObjectFromDb( $score_sql );
        $attr_score = $attr['renown']+$attr['piety']+$attr['valour']+$attr['discipline'];
        $path_score = $this->scorePathCards();
        $disdain_score = -([0,1,3,5,7,9,12,15,18,22,22,22,22,22,22,22][$attr['disdain']-$attr['approve']]);

        return [
            'renown'=>$attr['renown'],
            'piety'=>$attr['piety'],
            'valour'=>$attr['valour'],
            'discipline'=>$attr['discipline'],
            'path'=>$path_score,
            'disdain'=>$disdain_score,
            'total'=>($attr_score+$path_score+$disdain_score)
        ];
    }

    // Board

    function getBoard() {
        //self::debug("--->getBoard");
        $current_player_id = self::getCurrentPlayerId();
        $board_sql = "SELECT * FROM board WHERE player_id = $current_player_id";
        $board = self::getCollectionFromDb( $board_sql );
        $current_player_board = $board[$current_player_id];

        foreach($current_player_board as $field=>$value) {
            if(substr($field,-7)=="_rounds") {
                self::debug("$field => $value");
                $current_player_board[$field]=explode(",",$value);
            }
        }

        $results = $current_player_board;
        return $results;
    }

    // Resources

    function getResources() {
        //self::debug("--->getResources");
        $current_player_id = self::getCurrentPlayerId();
        $resource_sql = "SELECT player_id, civilians, servants, soldiers, builders, bricks, special FROM resources WHERE player_id=$current_player_id";
        $result = self::getCollectionFromDb($resource_sql)[$current_player_id];
        $result['special'] = explode(',',$result['special']);

        //self::debug("Special: ".print_r($result['special'],true));
        if($result['special'][0]!="") {
                $result['civilians'] = -$result['civilians'];
                $result['servants'] = -$result['servants'];
                $result['soldiers'] = -$result['soldiers'];
                $result['builders'] = -$result['builders'];
                $result['bricks'] = -$result['bricks'];
        }
        return $result;
    }

    function setResources($resources) {
        //self::debug("--->setResources");
        $current_player_id = self::getCurrentPlayerId();
        $resource_sql = "UPDATE resources SET ";

        $rarray = ['civilians', 'servants', 'soldiers', 'builders', 'bricks'];
        $updates=[];
        foreach($rarray as $resource) {
            if(array_key_exists($resource, $resources)) {
                $updates[]=("$resource=".$resources[$resource]."");
            }
        }

        $resource_sql.=implode( $updates, ',' );
        $resource_sql.=" WHERE player_id=$current_player_id";

        //self::debug($resource_sql);

        self::DbQuery($resource_sql);        
        $result = self::getResources();
        return $result;
    }

    function adjResources($resources) {
        //self::debug("--->adjResources ".print_r($resources,true));
        $current_player_id = self::getCurrentPlayerId();
        $resource_sql = "UPDATE resources SET ";

        $rarray = ['civilians', 'servants', 'soldiers', 'builders', 'bricks'];
        $updates=[];
        foreach($rarray as $resource) {
            if(array_key_exists($resource, $resources)) {
                $updates[]=("$resource=$resource+".$resources[$resource]."");
            }
        }

        if(count($updates)>0) {
            $resource_sql.=implode( $updates, ',' );
            $resource_sql.=" WHERE player_id=$current_player_id";
    
            //self::debug($resource_sql);
    
            self::DbQuery($resource_sql);        
        } else {
            //self::debug("NO UPDATES");
        }

        $result = self::getResources();
        return $result;
    }

    function clearResources() {
        $this->setResources([
            'civilians'=>0, 
            'servants'=>0, 
            'soldiers'=>0, 
            'builders'=>0, 
            'bricks'=>0,
            'special'=>""
        ]);
    }

    function addSpecial($resource) { // always adds to the beginning
        //self::debug("--->addSpecial $resource");
        $current_player_id = self::getCurrentPlayerId();
        $resource_sql = "UPDATE resources SET special = IF(LENGTH(special)>0,CONCAT(special,',$resource'),'$resource') WHERE player_id=$current_player_id";
        self::DbQuery($resource_sql);
    }

    function delSpecial($resource) { // must/assumed be the first resource in the special array
        //self::debug("--->delSpecial $resource");
        $current_player_id = self::getCurrentPlayerId();
        $length = strlen($resource)+1;
        // TODO allow this to not have to be the first element in the special array
        
        $resource_sql = "UPDATE resources SET special = RIGHT(special,LENGTH(special)-$length) WHERE player_id=$current_player_id";
        self::DbQuery($resource_sql);
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////
    //    Path Cards
    ////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////

    function scoreArchitect($board=null) {
        // Constructed Landmarks: 1,2,3
        $score = 0;
        if($board['archway']>0) $score++;
        if($board['monolith']>0) $score++;
        if($board['column']>0) $score++;
        if($board['statue']>0) $score++;   
        $score = min($score,3);
        return $score;
    }

    function scoreAristocrat($board=null) {
        // Final Disdain: 4,2,0
        $score = 0;
        $disdain = $board['disdain']-$board['approve'];
        if($disdain==0) $score++;
        if($disdain<=2) $score++;
        if($disdain<=4) $score++;
        return $score;
    }

    function scoreDefender($board=null) {
        // Completed Wall Sections: 1,2,3
        $score = 0;
        if($board['wall']>=7) $score++;
        if($board['wall']>=14) $score++;
        if($board['wall']>=21) $score++;
        return $score;
    }

    function scoreEngineer($board=null) {
        // Large Buildings: 2,4,6
        $count = 0;
        if($board['granary']>=2) $count++;
        if($board['hotel']>=3) $count++;
        if($board['workshop']>=3) $count++;
        if($board['road']>=3) $count++;
        if($board['precinct']>=3) $count++;
        if($board['gardens']>=2) $count++;
        if($board['temple']>=3) $count++;

        $score=0;
        if($count>=6) $score++;
        if($count>=4) $score++;
        if($count>=2) $score++;
        return $score;
    }

    function scoreFighter($board=null) {
        // Completed Cohorts: 1,2,3
        $score = 0;
        if($board['left_cohort']>=6) $score++;
        if($board['center_cohort']>=6) $score++;
        if($board['right_cohort']>=6) $score++;
        return $score;
    }

    function scoreForager($board=null) {
        // Resource Production: 3,6,9
        $score = 0;
        if($board['production']>=2) $score++;
        if($board['production']>=5) $score++;
        if($board['production']>=8) $score++;
        return $score;
    }

    function scoreMerchant($board=null) {
        // Collected Goods: 4,6,8
        $score = 100;
        // TODO
        return $score;
    }

    function scorePlanner($board=null) {
        // Completed Citizen Tracks: 2,4,5
        $count = 0;
        if($board['traders']>=9) $count++;
        if($board['performers']>=9) $count++;
        if($board['priests']>=9) $count++;
        if($board['apparitores']>=9) $count++;
        if($board['patricians']>=9) $count++;

        $score=0;
        if($count>=5) $score++;
        if($count>=4) $score++;
        if($count>=2) $score++;
        return $score;
    }

    function scorePontiff($board=null) {
        // Filled Temples: 1,2,3
        $score = 100;
        // TODO
        return $score;
    }

    function scoreRanger($board=null) {
        // Completed Scout Columns: 1,3,5
        $score = 100;
        // TODO
        return $score;
    }

    function scoreTrainer($board=null) {
        // Total Gladiator Strength: 4,8,12
        $score = 100;
        // TODO
        return $score;
    }

    function scoreVanguard($board=null) {
        // Completed Wall Guard Sections
        $score = 0;
        if($board['wall_guard']>=6) $score++;
        if($board['wall_guard']>=12) $score++;
        if($board['wall_guard']>=18) $score++;
        return $score;
    }

    function scorePathCards($paths=null,$board=null) {
        $score = 0;

        if($paths==null) {
            $current_player_id = self::getCurrentPlayerId();
            $path_cards = $this->player_cards->getCardsInLocation($current_player_id."_paths",null,'location_arg');
            $paths = [];
            foreach($path_cards as $id => $card ) {
                $type = $card['type'];
                $name = $this->player_card_data[$card['type']]['name'];
                self::debug("Path: ".$type." is ".$name);
                $paths[] = $name;
            }            

            self::debug("Paths: ".print_r($paths,true));

            // $paths=[
            //     'Architect','Aristocrat','Defender','Engineer','Fighter','Forager',
            //     'Merchant','Planner','Pontiff','Ranger','Trainer','Vanguard'];
        }
        if($board==null) {
            $board=$this->getBoard();
        }

        foreach($paths as $path) {
            switch($path) {
                case 'Architect': $score+=$this->scoreArchitect($board); break;
                case 'Aristocrat': $score+=$this->scoreAristocrat($board); break;
                case 'Defender': $score+=$this->scoreDefender($board); break;
                case 'Engineer': $score+=$this->scoreEngineer($board); break;
                case 'Fighter': $score+=$this->scoreFighter($board); break;
                case 'Forager': $score+=$this->scoreForager($board); break;
                case 'Merchant': $score+=$this->scoreMerchant($board); break;
                case 'Planner': $score+=$this->scorePlanner($board); break;
                case 'Pontiff': $score+=$this->scorePontiff($board); break;
                case 'Ranger': $score+=$this->scoreRanger($board); break;
                case 'Trainer': $score+=$this->scoreTrainer($board); break;
                case 'Vanguard': $score+=$this->scoreVanguard($board); break;
            }
        }

        self::debug("Path Cards Score: [".$score."]");
        return $score;
    }


    ////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////
    //    Gameplay Functions
    ////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////

    function applyRewards( $rewards ) {
        $adjResources = ['civilians'=>0, 'servants'=>0, 'soldiers'=>0, 'builders'=>0, 'bricks'=>0];
        foreach($rewards as $reward) {
            if(array_key_exists($reward,$adjResources)){
                $adjResources[$reward]++;
            } else {
                $this->addSpecial($reward);
            }           
        }
        $this->adjResources($adjResources);
    }

    function doCheckNextBox( $section, $spend=null, $reward=null, $resources=null) {
        $current_player_id = self::getCurrentPlayerId();
        $round = $this->getGameStateValue(self::GAME_ROUND);
        $boxData = $this->isBoxValid($section,null,$resources,null);
        //self::debug(print_r($boxData,true));

        if($boxData['valid']) {
            $board_sql = "UPDATE board SET `$section` = `$section` + 1 WHERE player_id=$current_player_id";
            self::DbQuery( $board_sql );

            $score_column = $this->updateScore();
    
            if($resources==null) {
                $resources = $this->getResources();
            }
    
            // $this->payFor($section);
            if($spend==null) {
                $cost = $boxData['cost'];
                $altCost = $boxData['altCost'];

                // TODO
                // if cost and alt are both basic or workers and spend is null, then error
                foreach([$altCost,$cost] as $c) {
                    $adjResources = [];
                    foreach($c as $resource=>$amount) {
                        if(array_key_exists($resource,$resources)) {
                            // basic resource
                            if($resources[$resource]<$amount) {
                                $adjResources = [];
                                break;
                            } else {
                                $adjResources[$resource]=-$amount;
                            }
                        } else {
                            $this->delSpecial($resource);
                        }
                    }
    
                    if(count($adjResources)>0){
                        $this->adjResources($adjResources);
                        break;
                    }
                }
            } else {
                // TODO: validate the spend cost is valid

                $this->adjResources([$spend=>-1]);
            }

            if(array_key_exists('reward',$boxData)) {
                //self::debug("Should reward ".print_r($boxData['reward'],true));
                $this->applyRewards($boxData['reward']);
            }

            // check if we need to record the round number            
            if(array_key_exists('roundNumberEntry',$boxData)) {
                $rounds_section = $boxData['roundNumberEntry']['section'];
                //$oldRounds = $boxData['roundNumberEntry']['current_rounds'];
                //$oldRounds = implode(",",$oldRounds);

                $newRounds = $boxData['roundNumberEntry']['current_rounds'];
                $newRounds = implode(",",$newRounds);
                if(strlen($newRounds)>0) {
                    $newRounds = $newRounds.",";
                } 
                $newRounds = $newRounds."$round";
                
                //self::debug("UPDATING ROUNDS for $rounds_section with index $index and current rounds of $oldRounds setting to $newRounds  [");
               
                $board_sql = "UPDATE board SET `$rounds_section` = '".$newRounds."' WHERE player_id=$current_player_id";
                self::DbQuery( $board_sql );
            }

            // check to see if we should also check the next cell            
            if(array_key_exists('reward',$boxData) && count($boxData['reward'])>0 && $boxData['reward'][0]=='continue') {
                //self::debug("rewards: ".implode(",",$boxData['reward'])."  ");
                //self::debug("continue checking next box after ".$boxData['id']);
                $this->doCheckNextBox($section,null,null,['special'=>['continue']]);
            } else {
                $this->notifyPlayer( $current_player_id, "sheetsUpdated", "", [
                    "board"=>$this->getBoard(),
                    "valid_moves"=>$this->getValidMoves(),
                    "resources"=>$this->getResources(),
                    "score_column"=>$score_column,
                    "boxData"=>$boxData
                ]);    
            }        
        }
    }

    function checkNextBox( $section, $spend = null, $reward = null ) {
        $this->checkAction('checkNextBox');
        $current_player_id = self::getCurrentPlayerId();

        $this->doCheckNextBox($section,$spend,$reward);
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

        $this->adjResources([
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

        $produced = $this->argProducedResources();        
        $this->adjResources($produced);
        
        $this->notifyPlayer( $current_player_id, "resourcesUpdated", "", [
            'resources'=>$this->getResources(),
            'change'=>$produced
        ]);

        $hasGeneratedResources = false;
        if($hasGeneratedResources) {
            $this->gamestate->nextPrivateState($current_player_id, 'chooseGeneratedAttributes');
        } else {
            $this->gamestate->nextPrivateState($current_player_id, 'choosePathCard');
        }
        
    }

    function chooseAttribute($attribute) {
        $this->checkAction('chooseAttribute');
        $current_player_id = self::getCurrentPlayerId();

        //self::debug("attribute = $attribute");
        if($attribute=="none") {
            //self::debug("No attribute chosen");
        } else {
            $this->doCheckNextBox($attribute);
        }

        $this->gamestate->nextPrivateState($current_player_id, 'choosePathCard');
    }

    function choosePathCard($path_card) {
        $this->checkAction('choosePathCard');
        $current_player_id = self::getCurrentPlayerId();
        $round = $this->getGameStateValue(self::GAME_ROUND);

        $hand = $this->player_cards->getCardsInLocation($current_player_id."_hand");
        //self::debug(print_r($hand,true));

        $path_card_id = -1;
        $resource_card = 'unknown';
        $resource_card_id = -1;
        
        foreach($hand as $card_id => $hand_card) {
            //self::debug("hand_card ".$hand_card['type']."  (".$hand_card['id'].")");
            if($hand_card['type']!=$path_card) {
                $resource_card = $hand_card['type'];
                $resource_card_id = $hand_card['id'];
            } else {
                $path_card_id = $hand_card['id'];
            }
        }

        //self::debug("picked card ".$path_card."  (".$path_card_id.")");
        //self::debug("resource card ".$resource_card."  (".$resource_card_id.")");

        $resource_card_data = $this->player_card_data[$resource_card];
        $this->adjResources($resource_card_data);

        $this->notifyPlayer( $current_player_id, "resourcesUpdated", "", [
            'resources'=>$this->getResources(),
            'change'=>$resource_card_data
        ]);

        $this->player_cards->moveCard($path_card_id,$current_player_id."_paths",$round);
        $this->player_cards->moveCard($resource_card_id,$current_player_id."_discard");

        $paths = $this->player_cards->getCardsInLocation($current_player_id."_paths",null,'location_arg');
        $path_cards = [];
        foreach($paths as $id => $card ) {
            $path_cards[] = $card['type'];
        }

        // notify path board changed
        $this->notifyPlayer( $current_player_id, "pathsUpdated", "", [
            'paths'=>$path_cards
        ]);

        $this->gamestate->nextPrivateState($current_player_id, 'useResources');
    }

    function endTurn() {
        //self::debug("end turn");
        $this->checkAction('endTurn');
        $current_player_id = self::getCurrentPlayerId();

        $this->clearResources();

        $this->gamestate->setPlayerNonMultiactive($current_player_id, 'endOfRound');
    }

    function applyCohorts() {
        self::debug("applyCohorts");
        $this->checkAction('applyCohorts');
        $current_player_id = self::getCurrentPlayerId();
        $round = $this->getGameStateValue(self::GAME_ROUND);
        $max_valour = $this->player_board_data[$round]['attackPotential'];

        $cohorts_sql = "SELECT player_id, left_cohort `left`, center_cohort center, right_cohort `right` FROM board WHERE player_id=$current_player_id";
        $cohorts = self::getCollectionFromDB($cohorts_sql)[$current_player_id];

        $attacks_sql = "SELECT player_id, fate_attacks_left `left`, fate_attacks_center center, fate_attacks_right `right` FROM attacks WHERE player_id=$current_player_id AND `round`=$round";
        $org_attacks = self::getCollectionFromDB($attacks_sql)[$current_player_id];
        $attacks=$org_attacks;

        // how many blocked
        $blocked = 0;
        $disdain = 0;
        foreach(['left','center','right'] as $pos) {
            if($cohorts[$pos]>0 && $attacks[$pos]>0) {
                $blocked += min($cohorts[$pos],$attacks[$pos]);
                $attacks[$pos] -= $cohorts[$pos];
                if($attacks[$pos]<0) {
                    $attacks[$pos]=0;
                }
            }
            $disdain += $attacks[$pos];
        }
        $valour = min($max_valour,$blocked);

        $rewards = [];
        for($v=0;$v<$valour;$v++) {
            $rewards[]="valour";
        }
        for($d=0;$d<$disdain;$d++) {
            $rewards[]="disdain";
        }
        $rewards = implode(",",$rewards);

        //$this->clearResources();
        $this->addSpecial($rewards);

        $this->notifyPlayer( $current_player_id, "resourcesUpdated", "", [
            'resources'=>$this->getResources(),
            'max_valour'=>$max_valour,
            'cohorts'=>$cohorts,
            'attacks'=>$org_attacks,
            'disdain'=>$disdain,
            'blocked'=>$blocked,
            'valour'=>$valour
        ]);

        //TODO: compare cohorts for this player and return only the cards that are left to deal with
        //TODO: if favor applies, offer it here

        $this->gamestate->nextPrivateState($current_player_id, 'gainValourAndDisdain');
    }

    function acceptAttackResults() {
        $this->checkAction('acceptAttackResults');
        $current_player_id = self::getCurrentPlayerId();

        //self::debug("acceptAttackResults");


        // TODO: don't allow a player to move past this stage until all valour and disdain has been used


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

        //self::debug("TODO: undo last check");

        //$this->gamestate->nextPrivateState($current_player_id, ' ');
    }

      ///////   ////   /////    //// 
        //     //  //  //  //  //  //
        //     //  //  //  //  //  //
        //      ////   ////     //// 
    ////////////////////////////////////  
    function restartRound() {
        self::debug("restart round");

        $this->checkAction('restartRound');
        $current_player_id = self::getCurrentPlayerId();



        //self::debug("TODO: restart round");

        //$this->gamestate->nextPrivateState($current_player_id, ' ');
    }
    
      ///////   ////   /////    //// 
        //     //  //  //  //  //  //
        //     //  //  //  //  //  //
        //      ////   ////     //// 
    ////////////////////////////////////  
    function applyFavor() {
        $this->checkAction('applyFavor');

        //self::debug("TODO: applyFavor");
    }

      ///////   ////   /////    //// 
        //     //  //  //  //  //  //
        //     //  //  //  //  //  //
        //      ////   ////     //// 
    ////////////////////////////////////  
    function doneApplyingFavor() {
        $this->checkAction('doneApplyingFavor');

        //self::debug("TODO: doneApplyingFavor");
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////
    //    Game State Functions
    ////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////
    function stGameSetup() {
        //self::debug( "----> stGameSetup" ); 
    }

    function stPrepareRound() {
        //self::debug( "----> stPrepareRound" ); 

        $this->incGameStateValue(self::GAME_ROUND,1);
        $round = $this->getGameStateValue(self::GAME_ROUND);

        $card = $this->fate_cards->pickCardForLocation('fate_deck','fate_discard');

        //self::debug("picked ".$card['type']);
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
        $board_sql = "SELECT player_id, production, hotel, workshop FROM board WHERE `round`=$round AND player_id=$current_player_id";
        $board = self::getCollectionFromDB($board_sql)[$current_player_id];

        $bricks = 1+$board['production'];
        $civilians = $board['hotel']/2;
        $builders = $board['workshop']/2;

        return ['bricks'=>$bricks,'civilians'=>$civilians,'builders'=>$builders];
    }

    function argChoosePathCard() {
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

    function isBoxValid($section,$index=null,$resources=null,$board=null) {
        $valid = true;
        $cost = [];
        $altCost = [];
        $reward = [];
        $message = "valid";
        $roundNumberEntry = null;

        if($board==null) {
            $board = $this->getBoard();
        }
        if($index==null) {
            $index = $board[$section];
        }
        if($resources==null) {
            $resources = $this->getResources();
        }
        $data = $this->section_data[$section];

        if($index>=count($data)) {
            return ['valid'=>false,'message'=>"Track Full"];
        }

        if(array_key_exists('cost',$data[$index])) {
            $cost = $data[$index]['cost'];
        }
        //self::debug("Cost: ".implode(array_keys($data[$index]['cost']))."         [");

        if(array_key_exists('altCost',$data[$index])) {
            $altCost = $data[$index]['altCost'];
        }
        if(array_key_exists('reward',$data[$index])) {
            //self::debug("Rewards: ".print_r(implode(",",$data[$index]['reward']),true)."         [");
            $reward = $data[$index]['reward'];
        }
    
        if($valid && array_key_exists('lockedBy',$data[$index])) {
            $locked = $data[$index]['lockedBy'];
            foreach($locked as $locked_section=>$required_level){
                if($locked_section=='peryear') {
                    $round = $this->getGameStateValue(self::GAME_ROUND);
                    $current_rounds = $board[$section."_rounds"];
                    $message = "Per year cell";
                    $roundNumberEntry = ['section'=>$section."_rounds",'current_rounds'=>$current_rounds];

                    // if the current round is already in the array the required_level number of times, then it's not valid
                    foreach($current_rounds as $r) {
                        if($r==$round) {
                            $required_level--;
                        }
                    }
                    if($required_level==0) {
                        $valid = false;
                    }
                } else if($board[$locked_section]<$required_level) {
                    //self::debug("LOCKED BY $locked_section");
                    $message = "Cell is locked.";
                    $valid = false; // this section is locked
                }
            }
        }

        if($valid) {
            $costs=[$cost];
            if(array_key_exists('altCost',$data[$index])) {
                $costs[]=$altCost;
            } 

            $message = "Hmm, you shouldn't see this.";
            $valid = false;
            foreach($costs as $list) {
                if($valid) { break; } // we were already able to validate in a prior loop
                $message = "valid";
                $valid = true;
                foreach($list as $resource=>$amount) {
                    //self::debug("<<<<<<<<< Checking for $amount $resource >>>>>>>>");
                    // check for basic resources 
                    if(array_key_exists($resource,$resources)) {
                        if($resources[$resource]<$amount) {
                            $message = "Missing $resource";
                            //self::debug($message."         [");
                            $valid = false;
                        }
                    // check for worker which can be satisfied by any meeple
                    } else if($resource=='worker') {
                        $workers = $resources['soldiers']+
                                   $resources['builders']+
                                   $resources['servants']+
                                   $resources['civilians'];
                        // self::debug("<<<<<<<<< Worker count = $workers >>>>>>>>");
                        if($workers<$amount) {
                            $message = "Missing workers";
                            //self::debug($message."         [");
                            $valid = false;
                        }
                    } else {
                        $message = "Missing speical $resource";
                        //self::debug($message."         [");
                        $valid = false;
                        // TODO: Check the special array (like cohort,renown,piety,valour,discipline, etc.)
                        $first = true;
                        foreach($resources['special'] as $special) {
                            if($first && $resource==$special) {
                                $message = "Special valid";
                                //self::debug($message."         [");
                                $valid = true;
                            }
                            $first = false;
                        }                       
                    }
                }
            }
        }

        $results = [
            'id'=>$section."_".$index,
            'section'=>$section,
            'index'=>$index,
            'valid'=>$valid,
            'cost'=>$cost,
            'altCost'=>$altCost,
            'reward'=>$reward,
            'message'=>$message,
        ];
        if($roundNumberEntry == null) {
        } else {
            $results['roundNumberEntry'] = $roundNumberEntry;
        }

        return $results;
    }

    function isBasicResource($resource) {
        $r = explode(",",$resource)[0];
        if($r=='soldiers') return true;
        if($r=='builders') return true;
        if($r=='servants') return true;
        if($r=='civilians') return true;
        if($r=='bricks') return true;
        return false;
    }

    function getValidMoves() {
        //self::debug( "----> getValidMoves" ); 
        $valid_moves = [];
        $costs = [];
        $section_data = $this->section_data;
        $board = $this->getBoard();
        $resources = $this->getResources();
        $invalid_moves = [];

        foreach($section_data as $id=>$data) {
            self::debug("CHECKING $id ".print_r($board[$id],true)."         [");
            $index = $board[$id];
            $boxData = $this->isBoxValid($id,$index,$resources,$board);
            if($boxData['valid']) {
                $d=$data[$index];

                $valid_move=[];
                $valid_move['id']=$d['id'];                

                // if alt_cost is a basic resource
                if(array_key_exists('altCost',$d) && $this->isBasicResource(array_key_first($d['altCost']))) {
                    if($this->isBasicResource(array_key_first($d['cost']))) {
                        $valid_move['spend_choice']=[array_key_first($d['cost']),array_key_first($d['altCost'])];
                    }
                }
                // if the cost is 'worker'

                $valid_moves[]=$valid_move;

                // self::debug("++++++++ Added valid move for ".$data[$index]['id']."         [");
                // if(array_key_exists('reward',$data[$index])) {
                //     self::debug("-- -- --rewards = ".implode(",",$data[$index]['reward'])."         [");
                // }
                if(array_key_exists('reward',$data[$index]) && $data[$index]['reward'][0]=='continue') {
                    $valid_move=[];
                    $valid_move['id']=$data[$index+1]['id'];
                    $valid_moves[]=$valid_move;
                    // self::debug(">>>>continuing          [");
                }
            } else {
                $invalid_moves[''] = $boxData;
            }
        }

        // check to see if we're blocked by a special that can't be used
        if(count($valid_moves)==0 && count($resources['special'])>0 && strlen($resources['special'][0])>0) {
            $this->delSpecial($resources['special'][0]);
            return $this->getValidMoves();
        }
        
        //$valid_moves['invalid'] = ['id'=>'0','moves'=>$invalid_moves];

        return $valid_moves;
    }

    function argUseResources() {
        //self::debug( "----> argUseResources" ); 
        $results=[];

        $results = $this->section_data;
        $results = $this->getValidMoves();

        return $results;
    }

    function stPictAttack() {
        self::debug( "----> stPictAttack" ); 

        

        $this->gamestate->setAllPlayersMultiactive();
        $this->gamestate->initializePrivateStateForAllActivePlayers();
    }

      ///////   ////   /////    //// 
        //     //  //  //  //  //  //
        //     //  //  //  //  //  //
        //      ////   ////     //// 
    ////////////////////////////////////  
    function stCheckFavor() {
        self::debug( "----> stCheckFavor" ); 
        $current_player_id = self::getCurrentPlayerId();

        $this->gamestate->nextPrivateState($current_player_id,'useFavor3');
    }

    function argDisplayAttack() {        
        self::debug("----> argDisplayAttack");

        $current_player_id = self::getCurrentPlayerId();
        $round = $this->getGameStateValue(self::GAME_ROUND);
        $sql = "SELECT * FROM attacks WHERE player_id = $current_player_id AND `round` = $round";

        $attacks = self::getCollectionFromDB($sql);
        $attacks = $attacks[$current_player_id];
        foreach(['left','center','right'] as $pos) {
            $loc = "fate_attack_cards_$pos";
            $attacks[$loc] = explode(",",$attacks[$loc]);
            if(strlen($attacks[$loc][0])==0) {
                $attacks[$loc] = [];
            }
        }

        $results=[
            'left'=>$attacks['fate_attack_cards_left'],
            'center'=>$attacks['fate_attack_cards_center'],
            'right'=>$attacks['fate_attack_cards_right'],
            'details'=>$attacks
        ];

        return $results;
    }

    function stPlayerTurn() {
        //self::debug( "----> stPlayerTurn" ); 

        $this->gamestate->setAllPlayersMultiactive();
        $this->gamestate->initializePrivateStateForAllActivePlayers();
    }


      ///////   ////   /////    //// 
        //     //  //  //  //  //  //
        //     //  //  //  //  //  //
        //      ////   ////     //// 
    ////////////////////////////////////  
    function stChooseGeneratedAttributes() {
        //self::debug( "----> stChooseGeneratedAttributes" ); 

        $current_player_id = self::getCurrentPlayerId();
        //self::debug("current_player_id = ".$current_player_id);        
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
        //self::debug( "----> stEndOfRound" ); 

        $round = $this->getGameStateValue(self::GAME_ROUND);
        $difficulty = $this->getGameStateValue(self::GAME_DIFFICULTY);

        $qty = $this->player_board_data[$round]['attackCards'][$difficulty];
        $max_valour = $this->player_board_data[$round]['attackPotential'];

        $cards = $this->fate_cards->pickCardsForLocation($qty,'fate_deck','fate_discard');
        $attacks['Left'] = [];
        $attacks['Center'] = [];
        $attacks['Right'] = [];

        foreach($cards as $card) {
            $fate_card = $this->fate_card_data[$card['type']];
            $attacks[$fate_card['attackDirection']][] = $card['type'];
        }        

        $players = self::loadPlayersBasicInfos();
        foreach($players as $player_id => $player) {
            $sql = "INSERT INTO attacks (player_id, `round`, extra,";
            $sql .= "fate_attacks_left, fate_attacks_center, fate_attacks_right, fate_attack_cards_left, fate_attack_cards_center, fate_attack_cards_right)";
            $sql .= "VALUES ($player_id, $round, 0, ".count($attacks['Left']).",".count($attacks['Center']).",".count($attacks['Right']);
            $sql .= ",'".implode(",",$attacks['Left'])."','".implode(",",$attacks['Center'])."','".implode(",",$attacks['Right'])."')";

            self::DbQuery($sql);
        }

        $this->notifyAllPlayers( "attack", clienttranslate("Round ".$round." the Picts attack "), [
            "round" => $round,
            "maxValour" => $max_valour,
            "attacks" => $attacks
        ]);

        $this->gamestate->nextState('pictAttack');
    }

      ///////   ////   /////    //// 
        //     //  //  //  //  //  //
        //     //  //  //  //  //  //
        //      ////   ////     //// 
    ////////////////////////////////////  
    function stCheckGameEnd() {
        self::debug( "----> stCheckGameEnd" ); 

        $round = $this->getGameStateValue(self::GAME_ROUND);
        if($round>=6) {
            $this->clearResources();
            $current_player_id = $this->getCurrentPlayerId();
            $this->notifyPlayer( $current_player_id, "resourcesUpdated", "", [
                'resources'=>$this->getResources(),
                'change'=>[]
            ]);
    
            $this->gamestate->nextState('gameEnd');
        } else {
            $this->gamestate->nextState('nextRound');
        }
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
