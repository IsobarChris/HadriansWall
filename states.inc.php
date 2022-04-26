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
 * states.inc.php
 *
 * HadriansWall game states description
 *
 */

/*
   Game state machine is a tool used to facilitate game developpement by doing common stuff that can be set up
   in a very easy way from this configuration file.

   Please check the BGA Studio presentation about game state to understand this, and associated documentation.

   Summary:

   States types:
   _ activeplayer: in this type of state, we expect some action from the active player.
   _ multipleactiveplayer: in this type of state, we expect some action from multiple players (the active players)
   _ game: this is an intermediary state where we don't expect any actions from players. Your game logic must decide what is the next game state.
   _ manager: special type for initial and final state

   Arguments of game states:
   _ name: the name of the GameState, in order you can recognize it on your own code.
   _ description: the description of the current game state is always displayed in the action status bar on
                  the top of the game. Most of the time this is useless for game state with "game" type.
   _ descriptionmyturn: the description of the current game state when it's your turn.
   _ type: defines the type of game states (activeplayer / multipleactiveplayer / game / manager)
   _ action: name of the method to call when this game state become the current game state. Usually, the
             action method is prefixed by "st" (ex: "stMyGameStateName").
   _ possibleactions: array that specify possible player actions on this step. It allows you to use "checkAction"
                      method on both client side (Javacript: this.checkAction) and server side (PHP: self::checkAction).
   _ transitions: the transitions are the possible paths to go from a game state to another. You must name
                  transitions in order to use transition names in "nextState" PHP method, and use IDs to
                  specify the next game state for each transition.
   _ args: name of the method to call to retrieve arguments for this gamestate. Arguments are sent to the
           client side to be used on "onEnteringState" or to set arguments in the gamestate description.
   _ updateGameProgression: when specified, the game progression is updated (=> call to your getGameProgression
                            method).
*/

//    !! It is not a good idea to modify this file when a game is running !!

// gameSetup
// Game Round Start: 
//   - inc round number
//   - draw fate card for resources
//   - give fate resources
//   - give sheet resources
//   - draw two personal cards
// Player Round Start:
//   - wait for player selection
// Player turns
// Game End of Round:
//   - Draw attack cards
// Player End of Round:
//   - Wait for user choice for Favor
// 





 $machinestates = array(

    // The initial state. Please do not modify.
    1 => [
        "name" => "gameSetup",
        "description" => "",
        "type" => "manager",
        "action" => "stGameSetup",
        "transitions" => [ "" => 10 ]
    ],
    
    10 => [
        "name"=> "prepareRound",
        "type" => "game",
        "action" => "stPrepareRound",
        "transitions" => [
            "playerTurn" => 20,
        ]
    ],

    20 => [
        "name" => "playerTurn",
        "description" => clienttranslate('Other players need to finish their turn.'),
        "descriptionmyturn" => clienttranslate('${you} must complete your turn.'),
        "type" => "multipleactiveplayer",
        "initialprivate" => 21,
        "possibleactions" => [],
        "transitions" => [ "endOfRound" => 30 ],
        "action" => "stPlayerTurn",
    ],

    21 => [
        "name" => "acceptFateResources",
        "descriptionmyturn" => clienttranslate('${you} must accept workers and resources from the fate card.'),
        "type" => "private",
        "possibleactions" => ["acceptFateResources"], // TODO
        "transitions" => [ "acceptProducedResources" => 22 ],
        "args" => "argFateResources"
    ],

    22 => [
        "name" => "acceptProducedResources",
        "descriptionmyturn" => clienttranslate('${you} must accept workers and resources you produced.'),
        "type" => "private",
        "possibleactions" => ["acceptProducedResources"], // TODO
        "transitions" => [ "chooseGeneratedAttributes" => 23, "chooseGoalCard" => 24 ],
        "args" => "argProducedResources"
    ],

    23 => [
        "name" => "chooseGeneratedAttributes",
        "descriptionmyturn" => clienttranslate('${you} must choose your generated attribute.'),
        "type" => "private",
        "possibleactions" => ["chooseAttribute"], // TODO
        "transitions" => [ "chooseGoalCard" => 24 ],
        //"action" => "stChooseGeneratedAttributes"
        "args" => "argChooseGeneratedAttributes"
    ],

    24 => [
        "name" => "chooseGoalCard",
        "descriptionmyturn" => clienttranslate('${you} must choose a goal card and gain resources for the other.'),
        "type" => "private",
        "possibleactions" => ["chooseGoalCard"], // TODO
        "transitions" => [ "useResources" => 25 ],
        "args" => "argChooseGoalCard"
    ],

    25 => [
        "name" => "useResources",
        "descriptionmyturn" => clienttranslate('${you} may use resources and workers.'),
        "type" => "private",
        "possibleactions" => ["checkNextBox", "undoCheck", "restartRound", "endTurn" ],
        "transitions" => [ ], // possible states for choosing what to spend
        'args' => 'argUseResources',
    ],

    30 => [
        "name"=> "endOfRound",
        "type" => "game",
        "action" => "stEndOfRound",
        "transitions" => [
            "pictAttack" => 31
        ]
    ],

    31 => [
        "name" => "pictAttack",
        "description" => clienttranslate('Other players need to resolve attack.'),
        "descriptionmyturn" => clienttranslate('${you} must resolve attack.'),
        "type" => "multipleactiveplayer",
        "initialprivate" => 33,
        "possibleactions" => [],
        "transitions" => [ "checkEndGame" => 40 ],
        "action" => "stPictAttack",
    ],

    33 => [
        "name" => "displayAttack",
        "descriptionmyturn" => clienttranslate('The Picts Attack!'),
        "type" => "private",
        "possibleactions" => ["applyCohorts"],
        "transitions" => [ "useFavor" => 34, "gainValourAndDisdain" => 35 ],
        //"action" => "stCheckFavor",
        "args" => 'argDisplayAttack'
    ],

    // 33 => [
    //     "name" => "checkFavor",
    //     "descriptionmyturn" => clienttranslate('Checking for available favor.'),
    //     "type" => "private",
    //     "possibleactions" => [],
    //     "transitions" => [ "useFavor" => 33, "gainValourAndDisdain" => 34 ],
    //     "action" => "stCheckFavor"
    // ],

    34 => [
        "name" => "useFavor",
        "descriptionmyturn" => clienttranslate('Use favor to prevent disdain.'),
        "type" => "private",
        "possibleactions" => ["applyFavor","doneApplyingFavor"],
        "transitions" => [ "gainValourAndDisdain" => 34 ],
    ],

    35 => [
        "name" => "gainValourAndDisdain",
        "descriptionmyturn" => clienttranslate('Accept attack results.'),
        "type" => "private",
        "possibleactions" => ["acceptAttackResults"],
        "transitions" => [ "checkEndGame" => 40 ],
    ],

    40 => [
        "name"=> "checkEndGame",
        "type" => "game",
        "action" => "stCheckGameEnd",
        "transitions" => [
            "end" => 99,
            "nextRound" => 10
        ]
    ],

    // Final state.
    // Please do not modify (and do not overload action/args methods).
    99 => [
        "name" => "gameEnd",
        "description" => clienttranslate("End of game"),
        "type" => "manager",
        "action" => "stGameEnd",
        "args" => "argGameEnd"
    ]

);



