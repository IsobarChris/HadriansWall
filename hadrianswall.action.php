<?php
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * HadriansWall implementation : © <Your name here> <Your email address here>
 *
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 * -----
 * 
 * hadrianswall.action.php
 *
 * HadriansWall main action entry point
 *
 *
 * In this file, you are describing all the methods that can be called from your
 * user interface logic (javascript).
 *       
 * If you define a method "myAction" here, then you can call it from your javascript code with:
 * this.ajaxcall( "/hadrianswall/hadrianswall/myAction.html", ...)
 *
 */

class action_hadrianswall extends APP_GameAction
{ 
    // Constructor: please do not modify
   	public function __default()
  	{
  	    if( self::isArg( 'notifwindow') )
  	    {
            $this->view = "common_notifwindow";
  	        $this->viewArgs['table'] = self::getArg( "table", AT_posint, true );
  	    }
  	    else
  	    {
            $this->view = "hadrianswall_hadrianswall";
            self::trace( "Complete reinitialization of board game" );
        }
  	} 
  	
    public function acceptFateResources() {
        self::setAjaxMode();

        $this->game->acceptFateResources();

        self::ajaxResponse();
    }
    public function acceptProducedResources() {
        self::setAjaxMode();

        $this->game->acceptProducedResources();
        
        self::ajaxResponse();        
    }
    public function chooseAttribute() {
        self::setAjaxMode();

        $attribute = self::getArg("attribute",AT_alphanum,true);
        $this->game->chooseAttribute($attribute);
        
        self::ajaxResponse();        
    }
    public function chooseGoalCard() {
        self::setAjaxMode();

        $card = self::getArg("card",AT_alphanum,true);
        $this->game->chooseGoalCard($card);

        self::ajaxResponse();        
    }
      
    public function checkNextBox() {
        self::setAjaxMode();

        $section = self::getArg("section",AT_alphanum,true);
        $spend = self::getArg("spend",AT_alphanum);
        $reward = self::getArg("reward",AT_alphanum);

        $this->game->checkNextBox($section,$spend,$reward);

        self::ajaxResponse();
    }

    public function undoCheck() {
        self::setAjaxMode();

        self::ajaxResponse();
    }
    public function restartRound() {
        self::setAjaxMode();

        self::ajaxResponse();
    }
    public function endTurn() {
        self::setAjaxMode();

        $this->game->endTurn();

        self::ajaxResponse();
    }

    public function applyCohorts() {
        self::setAjaxMode();

        $this->game->applyCohorts();

        self::ajaxResponse();
    }

    public function acceptAttackResults() {
        self::setAjaxMode();

        $this->game->acceptAttackResults();

        self::ajaxResponse();
    }

    /*
    
    Example:
  	
    public function myAction()
    {
        self::setAjaxMode();     

        // Retrieve arguments
        // Note: these arguments correspond to what has been sent through the javascript "ajaxcall" method
        $arg1 = self::getArg( "myArgument1", AT_posint, true );
        $arg2 = self::getArg( "myArgument2", AT_posint, true );

        // Then, call the appropriate method in your game logic, like "playCard" or "myAction"
        $this->game->myAction( $arg1, $arg2 );

        self::ajaxResponse( );
    }
    
    */

}
