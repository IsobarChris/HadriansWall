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
 * hadrianswall.view.php
 *
 * This is your "view" file.
 *
 * The method "build_page" below is called each time the game interface is displayed to a player, ie:
 * _ when the game starts
 * _ when a player refreshes the game page (F5)
 *
 * "build_page" method allows you to dynamically modify the HTML generated for the game interface. In
 * particular, you can set here the values of variables elements defined in hadrianswall_hadrianswall.tpl (elements
 * like {MY_VARIABLE_ELEMENT}), and insert HTML block elements (also defined in your HTML template file)
 *
 * Note: if the HTML of your game interface is always the same, you don't have to place anything here.
 *
 */

require_once( APP_BASE_PATH."view/common/game.view.php" );

class view_hadrianswall_hadrianswall extends game_view
{
    function getGameName() {
        return "hadrianswall";
    }
    
    function processPlayerBlock($player_id, $player) {
        $color = $player ['player_color'];
        $name = $player ['player_name'];
        $no = $player ['player_no'];

        global $g_user;
        if($player_id == $g_user->get_id()) {
            $this->page->insert_block("my_player_board", 
            array ("COLOR" => $color,"PLAYER_NAME" => $name,
                   "PLAYER_NO" => $no, "PLAYER_ID" => $player_id,
                   "VALUE" => "It's me." ));    
        } else {
            $this->page->insert_block("player_board", 
            array ("COLOR" => $color,"PLAYER_NAME" => $name,
                    "PLAYER_NO" => $no, "PLAYER_ID" => $player_id,
                    "VALUE" => $name ));    
        }

        // DEBUG: make each board different
        $sql = "UPDATE board SET wall_guard=".$no.", renown=".$no." WHERE player_id='".$player_id."'";
        $this->DbQuery($sql);
        // END DEBUG
    }

    function getTemplateName() {
        return self::getGameName() . "_" . self::getGameName();
    }

  	function build_page( $viewArgs )
  	{		
        self::debug( "----> build_page" ); 

  	    // Get players & players number
        $players = $this->game->loadPlayersBasicInfos();
        $players_nbr = count( $players );

        /*********** Place your code below:  ************/

        global $g_user;
        $template = self::getTemplateName();

        $this->page->begin_block($template, "player_board");
        foreach($players as $player_id => $player) {
            if($player_id==$g_user->get_id()) {
                continue;
            }
            $this->processPlayerBlock($player_id,$player);
        }

        $this->page->begin_block($template, "my_player_board");
        foreach($players as $player_id => $player) {
            if($player_id!=$g_user->get_id()) {
                continue;
            }
            $this->processPlayerBlock($player_id,$player);
        }

        /*
        
        // Examples: set the value of some element defined in your tpl file like this: {MY_VARIABLE_ELEMENT}

        // Display a specific number / string
        $this->tpl['MY_VARIABLE_ELEMENT'] = $number_to_display;

        // Display a string to be translated in all languages: 
        $this->tpl['MY_VARIABLE_ELEMENT'] = self::_("A string to be translated");

        // Display some HTML content of your own:
        $this->tpl['MY_VARIABLE_ELEMENT'] = self::raw( $some_html_code );
        
        */
        
        /*
        
        // Example: display a specific HTML block for each player in this game.
        // (note: the block is defined in your .tpl file like this:
        //      <!-- BEGIN myblock --> 
        //          ... my HTML code ...
        //      <!-- END myblock --> 
        

        $this->page->begin_block( "hadrianswall_hadrianswall", "myblock" );
        foreach( $players as $player )
        {
            $this->page->insert_block( "myblock", array( 
                                                    "PLAYER_NAME" => $player['player_name'],
                                                    "SOME_VARIABLE" => $some_value
                                                    ...
                                                     ) );
        }
        
        */



        /*********** Do not change anything below this line  ************/
  	}
}
  

