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
        // Your global variables labels:
        //  Here, you can assign labels to global variables you are using for this game.
        //  You can use any number of global variables with IDs between 10 and 99.
        //  If your game has options (variants), you also have to associate here a label to
        //  the corresponding ID in gameoptions.inc.php.
        // Note: afterwards, you can get/set the global variables with getGameStateValue/setGameStateInitialValue/setGameStateValue
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
		// Used for translations and stuff. Please do not modify.
        return "hadrianswall";
    }	

    /*
        setupNewGame:
        
        This method is called only once, when a new game is launched.
        In this method, you must setup the game according to the game rules, so that
        the game is ready to be played.
    */
    protected function setupNewGame( $players, $options = array() )
    {    
        self::debug( "PHP - setupNewGame" ); 

        // Set the colors of the players with HTML color code
        // The default below is red/green/blue/orange/brown
        // The number of colors defined here must correspond to the maximum number of players allowed for the gams
        $gameinfos = self::getGameinfos();
        $default_colors = $gameinfos['player_colors'];
 
        // Create players
        // Note: if you added some extra field on "player" table in the database (dbmodel.sql), you can initialize it there.
        $sql = "INSERT INTO player (player_id, player_color, player_canal, player_name, player_avatar) VALUES ";
        $values = [];

        $board_sql = "INSERT INTO board (`round`,player_id) VALUES ";
        $board_values = [];

        $goal_sql = "INSERT INTO goals (player_id,round_1) VALUES ";
        $goal_values = [];


        foreach( $players as $player_id => $player )
        {
            $color = array_shift( $default_colors );
            $values[] = "('".$player_id."','$color','".$player['player_canal']."','".addslashes( $player['player_name'] )."','".addslashes( $player['player_avatar'] )."')";

            $board_values[] = "(0,'".$player_id."')";

            $goal_values[] = "(".$player_id.",2)";

            $player_cards=[];
            for($index=0; $index<12; $index++) {
                $player_cards[] = ['type'=>'player_card_'.($index+1),'type_arg'=>$player_id,'nbr'=>1];
            }
            $this->player_cards->createCards($player_cards,$color."_deck");   
            $this->player_cards->shuffle($color."_deck"); 
        }
        $sql .= implode( $values, ',' );
        self::DbQuery( $sql );

        $board_sql .= implode( $board_values, ',' );        
        self::DbQuery( $board_sql );

        $goal_sql .= implode( $goal_values, ',' );
        self::DbQuery( $goal_sql );

        $fate_cards=[];
        for($index=0; $index<48; $index++) {
            $fate_cards[] = ['type'=>'fate_card_'.($index+1),'type_arg'=>$index,'nbr'=>1];
        }
        $this->fate_cards->createCards($fate_cards,'fate_deck');
        $this->fate_cards->shuffle('fate_deck');

        //self::reattributeColorsBasedOnPreferences( $players, $gameinfos['player_colors'] );
        self::reloadPlayersBasicInfos();
        
        /************ Start the game initialization *****/

        $this->setGameStateInitialValue(self::GAME_ROUND,0);

        // Activate first player (which is in general a good idea :) )
        $this->activeNextPlayer();

        /************ End of the game initialization *****/
    }

    /*
        getAllDatas: 
        
        Gather all informations about current game situation (visible by the current player).
        
        The method is called each time the game interface is displayed to a player, ie:
        _ when the game starts
        _ when a player refreshes the game page (F5)
    */
    protected function getAllDatas()
    {
        $result = array();
    
        $current_player_id = self::getCurrentPlayerId();    // !! We must only return informations visible by this player !!
    
        // Get information about players
        // Note: you can retrieve some extra field you added for "player" table in "dbmodel.sql" if you need it.
        $sql = "SELECT player_id id, player_color color, player_score score FROM player ";
        $result['players'] = self::getCollectionFromDb( $sql );
  
        // TODO: Gather all information about current game situation (visible by player $current_player_id).
  
        $sql = "SELECT * FROM board WHERE player_id = $current_player_id";
        $result['board'] = self::getCollectionFromDb( $sql );

        $sql = "SELECT renown, piety, valour, discipline, disdain, player_id id FROM board";
        $result['scores'] = self::getCollectionFromDb( $sql );

        $goals = [];
        $sql = "SELECT player_id id, round_1, round_2, round_3, round_4, round_5, round_6 FROM goals";
        $goals[] = self::getCollectionFromDb( $sql );

        $result['goals'] = $goals;

        $current_round = $this->getGameStateValue(self::GAME_ROUND);
        $result['round'] = $current_round;
        $result['attack_potential'] = [1,2,2,3,3,4];
        $result['difficulty'] = 'easy';
        $result['attacks'] = [1,2,3,4,6,8];

        $display_round = max([0,$current_round-1]);
        $opsql = "SELECT player_id, renown, piety, valour, discipline, disdain FROM board WHERE `round`=$display_round";
        $result['score_boards'] = self::getCollectionFromDb( $opsql );

        // $resource_sql = "SELECT `civilians`, `servants`, `soldiers`, `builders`, `bricks`, `special` FROM player WHERE player_id=$current_player_id";
        // $result['resources'] = self::getCollectionFromDb($resource_sql);
        $result['resources'] = $this->getResources();

        return $result;
    }

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

    /*
        getGameProgression:
        
        Compute and return the current game progression.
        The number returned must be an integer beween 0 (=the game just started) and
        100 (= the game is finished or almost finished).
    
        This method is called each time we are in a game state with the "updateGameProgression" property set to true 
        (see states.inc.php)
    */
    function getGameProgression()
    {
        $current_round = $this->getGameStateValue(self::GAME_ROUND);

        return max([1,($current_round-1)/6*100]);
    }


//////////////////////////////////////////////////////////////////////////////
//////////// Utility functions
////////////    

    /*
        In this space, you can put any utility methods useful for your game logic
    */



//////////////////////////////////////////////////////////////////////////////
//////////// Player actions
//////////// 

    /*
        Each time a player is doing some game action, one of the methods below is called.
        (note: each method below must match an input method in hadrianswall.action.php)
    */

    function doCheckNextBox( $section ) {
        // todo - make sure resources and prereqs are met
        // todo - reduce resources as needed

        $current_player_id = self::getCurrentPlayerId();

        $sql = "UPDATE board SET $section = $section + 1 WHERE player_id=$current_player_id";
        self::DbQuery( $sql );

        $sql = "SELECT * FROM board WHERE player_id = $current_player_id";
        $board = self::getCollectionFromDb( $sql );

        $this->notifyPlayer( $current_player_id, "sheetsUpdated", "", [
            "board"=>$board[$current_player_id]
        ]);
    }

    function checkNextBox( $section ) {
        $this->checkAction('checkNextBox');
        $current_player_id = self::getCurrentPlayerId();

        $this->doCheckNextBox($section);
    }

    function acceptFateResources() {
        $this->checkAction('acceptFateResources');
        $current_player_id = self::getCurrentPlayerId();

        $round = $this->getGameStateValue(self::GAME_ROUND);
        $sql = "SELECT `round`, `fate_resource_card` FROM rounds WHERE `round`=".$round;
        $round_info = self::getCollectionFromDB($sql);
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

    function chooseCard($card_id) {
        $this->checkAction('chooseCard');
        $current_player_id = self::getCurrentPlayerId();

        self::debug("picked card ".$card_id);

        $this->gamestate->nextPrivateState($current_player_id, 'useResources');
    }

    function undoCheck() {
        $this->checkAction('undoCheck');
        $current_player_id = self::getCurrentPlayerId();

        self::debug("undo last check");

        //$this->gamestate->nextPrivateState($current_player_id, ' ');
    }

    function restartRound() {
        $this->checkAction('restartRound');
        $current_player_id = self::getCurrentPlayerId();

        self::debug("restart round");

        //$this->gamestate->nextPrivateState($current_player_id, ' ');
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
    
    function useFavor() {
        $this->checkAction('useFavor');

        self::debug("useFavor");

    }

    function doneUsingFavor() {
        $this->checkAction('doneUsingFavor');

        self::debug("doneUsingFavor");

    }


    /*
    
    Example:

    function playCard( $card_id )
    {
        // Check that this is the player's turn and that it is a "possible action" at this game state (see states.inc.php)
        self::checkAction( 'playCard' ); 
        
        $player_id = self::getActivePlayerId();
        
        // Add your game logic to play a card there 
        ...
        
        // Notify all players about the card played
        self::notifyAllPlayers( "cardPlayed", clienttranslate( '${player_name} plays ${card_name}' ), array(
            'player_id' => $player_id,
            'player_name' => self::getActivePlayerName(),
            'card_name' => $card_name,
            'card_id' => $card_id
        ) );
          
    }
    
    */

    private function isPlayerActive($playerId){
        return array_search($playerId, $this->gamestate->getActivePlayerList()) !== false;
    }

    
//////////////////////////////////////////////////////////////////////////////
//////////// Game state arguments
////////////

    /*
        Here, you can create methods defined as "game state arguments" (see "args" property in states.inc.php).
        These methods function is to return some additional information that is specific to the current
        game state.
    */

    /*
    
    Example for game state "MyGameState":
    
    function argMyGameState()
    {
        // Get some values from the current game situation in database...
    
        // return values:
        return array(
            'variable1' => $value1,
            'variable2' => $value2,
            ...
        );
    }    
    */

//////////////////////////////////////////////////////////////////////////////
//////////// Game state actions
////////////

    /*
        Here, you can create methods defined as "game state actions" (see "action" property in states.inc.php).
        The action method of state X is called everytime the current game state is set to X.
    */
    
    /*
    
    Example for game state "MyGameState":

    function stMyGameState()
    {
        // Do some stuff ...
        
        // (very often) go to another gamestate
        $this->gamestate->nextState( 'some_gamestate_transition' );
    }    
    */

    function stGameSetup() {
        self::debug( "----> stGameSetup" ); 
    }

    function stPrepareRound() {
        self::debug( "----> stPrepareRound" ); 

        $this->incGameStateValue(self::GAME_ROUND,1);
        $round = $this->getGameStateValue(self::GAME_ROUND);

        $card = $this->fate_cards->pickCardForLocation('fate_deck','fate_discard');

        self::debug("picked ".$card['type']);
        $sql = "INSERT INTO `rounds` (`round`,`fate_resource_card`) VALUES (".$round.",'".$card['type']."')";
        self::DbQuery( $sql );

        $this->notifyAllPlayers( "newRound", clienttranslate("Round ".$round." starts with ".$card['type']), [
            "round" => $round,
            "fate_resource_card" => $card['type']
        ]);

        $this->gamestate->nextState('playerTurn');
    }

    function argFateResources() {

        $round = $this->getGameStateValue(self::GAME_ROUND);
        $sql = "SELECT `round`, `fate_resource_card` FROM rounds WHERE `round`=".$round;
        $round_info = self::getCollectionFromDB($sql);
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

    function stChooseGeneratedAttributes() {
        self::debug( "----> stChooseGeneratedAttributes" ); 

        $current_player_id = self::getCurrentPlayerId();
        self::debug("current_player_id = ".$current_player_id);        
    }

    function argChooseGeneratedAttributes()
    {
        return [
            //'renown','piety','valour','discipline'
        ];
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

    function stEndOfRound() {
        self::debug( "----> stEndOfRound" ); 

        // TODO: draw pict attack

        $this->gamestate->nextState('acceptPictAttack');
    }

    function stCheckFavor() {
        self::debug( "----> stCheckFavor" ); 
        $current_player_id = self::getCurrentPlayerId();

        $this->gamestate->nextPrivateState($current_player_id,'useFavor');
    }


    function stCheckGameEnd() {
        self::debug( "----> stCheckGameEnd" ); 

        $this->gamestate->nextState('nextRound');
    }


//////////////////////////////////////////////////////////////////////////////
//////////// Zombie
////////////

    /*
        zombieTurn:
        
        This method is called each time it is the turn of a player who has quit the game (= "zombie" player).
        You can do whatever you want in order to make sure the turn of this player ends appropriately
        (ex: pass).
        
        Important: your zombie code will be called when the player leaves the game. This action is triggered
        from the main site and propagated to the gameserver from a server, not from a browser.
        As a consequence, there is no current player associated to this action. In your zombieTurn function,
        you must _never_ use getCurrentPlayerId() or getCurrentPlayerName(), otherwise it will fail with a "Not logged" error message. 
    */

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
    
///////////////////////////////////////////////////////////////////////////////////:
////////// DB upgrade
//////////

    /*
        upgradeTableDb:
        
        You don't have to care about this until your game has been published on BGA.
        Once your game is on BGA, this method is called everytime the system detects a game running with your old
        Database scheme.
        In this case, if you change your Database scheme, you just have to apply the needed changes in order to
        update the game database and allow the game to continue to run with your new version.
    
    */
    
    function upgradeTableDb( $from_version )
    {
        // $from_version is the current version of this game database, in numerical form.
        // For example, if the game was running with a release of your game named "140430-1345",
        // $from_version is equal to 1404301345
        
        // Example:
//        if( $from_version <= 1404301345 )
//        {
//            // ! important ! Use DBPREFIX_<table_name> for all tables
//
//            $sql = "ALTER TABLE DBPREFIX_xxxxxxx ....";
//            self::applyDbUpgradeToAllDB( $sql );
//        }
//        if( $from_version <= 1405061421 )
//        {
//            // ! important ! Use DBPREFIX_<table_name> for all tables
//
//            $sql = "CREATE TABLE DBPREFIX_xxxxxxx ....";
//            self::applyDbUpgradeToAllDB( $sql );
//        }
//        // Please add your future database scheme changes here
//
//


    }    
}
