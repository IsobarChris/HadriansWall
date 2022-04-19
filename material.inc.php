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
 * material.inc.php
 *
 * HadriansWall game material description
 *
 * Here, you can describe the material of your game with PHP variables.
 *   
 * This file is loaded in your game logic class constructor, ie these variables
 * are available everywhere in your game logic code.
 *
 */


/*

Example:

$this->card_types = array(
    1 => array( "card_name" => ...,
                ...
              )
);


$this->token_types = [
 'card_role_survey' => [
   'type' => 'card_role',
   'name' => clienttranslate("Survey"),
   'tooltip' => clienttranslate("ACTION: Draw 2 Cards<br/><br/>ROLE: Look at <div class='icon survey'></div> - 1 planet cards, keep 1<br/> <span class='yellow'>Leader:</span> Look at 1 additional card"),
   'b'=>0,
   'p'=>'',
   'i'=>'S',
   'v'=>0,
   'a'=>'dd',
   'r'=>'S', 
   'l'=>'v',
  ],

 'card_tech_1_51' => [
   'type' => 'tech',
   'name' => clienttranslate("Improved Trade"),
   'b' => 3,
   'p' => 'E',
   'i' => 'TP',
   'v' => 0,
   'a' => 'i',
   'side' => 0,
   'tooltip' => clienttranslate("Collect 1 Influence from the supply."),
 ],

...
];


*/




$this->round_data = [
  'round1' => [
    'attackPotential' => 1,
    'easy' => 1,  
    'normal' => 1,
    'hard' => 1
  ],
  'round2' => [
    'attackPotential' => 2,
    'easy' => 2,  
    'normal' => 2,
    'hard' => 3
  ],
  'round3' => [
    'attackPotential' => 2,
    'easy' => 3,  
    'normal' => 4,
    'hard' => 5
  ],
  'round4' => [
    'attackPotential' => 3,
    'easy' => 4,  
    'normal' => 6,
    'hard' => 7
  ],
  'round5' => [
    'attackPotential' => 3,
    'easy' => 6,  
    'normal' => 8,
    'hard' => 9
  ],
  'round6' => [
    'attackPotential' => 4,
    'easy' => 8,  
    'normal' => 10,
    'hard' => 12
  ]
];


$this->player_card_data = [
  'player_card_1' => [
    'type' => 'player_card',
    'name' => clienttranslate('Engineer'),
    'bonusGoal' => 'Large Buildings',

    'scout' => 'L', // L O I T Z
    'tradeGood' => 2,

    'soldiers' => 0,
    'builders' => 1,
    'servants' => 0,
    'civilians' => 0,
    'bricks' => 1,
  ],
  'player_card_2' => [
    'type' => 'player_card',
    'name' => clienttranslate('Defender'),
    'bonusGoal' => 'Completed Wall Sections',

    'scout' => 'L', // L O I T Z
    'tradeGood' => 1,

    'soldiers' => 0,
    'builders' => 1,
    'servants' => 0,
    'civilians' => 0,
    'bricks' => 1,
  ],
  'player_card_3' => [
    'type' => 'player_card',
    'name' => clienttranslate('Merchant'),
    'bonusGoal' => 'Collected Goods',

    'scout' => 'O', // L O I T Z
    'tradeGood' => 4,

    'soldiers' => 0,
    'builders' => 0,
    'servants' => 0,
    'civilians' => 1,
    'bricks' => 1,
  ],
  'player_card_4' => [
    'type' => 'player_card',
    'name' => clienttranslate('Fighter'),
    'bonusGoal' => 'Completed Coharts',

    'scout' => 'O', // L O I T Z
    'tradeGood' => 5,

    'soldiers' => 1,
    'builders' => 0,
    'servants' => 0,
    'civilians' => 0,
    'bricks' => 0,
  ],
  'player_card_5' => [
    'type' => 'player_card',
    'name' => clienttranslate('Ranger'),
    'bonusGoal' => 'Completed Scout Columns',

    'scout' => 'I', // L O I T Z
    'tradeGood' => 6,

    'soldiers' => 1,
    'builders' => 0,
    'servants' => 0,
    'civilians' => 0,
    'bricks' => 0,
  ],
  'player_card_6' => [
    'type' => 'player_card',
    'name' => clienttranslate('Architect'),
    'bonusGoal' => 'Constructed Landmarks',

    'scout' => 'I', // L O I T Z
    'tradeGood' => 2,

    'soldiers' => 0,
    'builders' => 0,
    'servants' => 0,
    'civilians' => 0,
    'bricks' => 2,
  ],
  'player_card_7' => [
    'type' => 'player_card',
    'name' => clienttranslate('Forger'),
    'bonusGoal' => 'Resource Production',

    'scout' => 'T', // L O I T Z
    'tradeGood' => 3,

    'soldiers' => 0,
    'builders' => 0,
    'servants' => 1,
    'civilians' => 0,
    'bricks' => 1,
  ],
  'player_card_8' => [
    'type' => 'player_card',
    'name' => clienttranslate('Aristocrat'),
    'bonusGoal' => 'Final Disdain',

    'scout' => 'T', // L O I T Z
    'tradeGood' => 1,

    'soldiers' => 0,
    'builders' => 1,
    'servants' => 0,
    'civilians' => 1,
    'bricks' => 0,
  ],
  'player_card_9' => [
    'type' => 'player_card',
    'name' => clienttranslate('Pontiff'),
    'bonusGoal' => 'Filled Temples',

    'scout' => 'T', // L O I T Z
    'tradeGood' => 2,

    'soldiers' => 0,
    'builders' => 0,
    'servants' => 2,
    'civilians' => 0,
    'bricks' => 0,
  ],
  'player_card_10' => [
    'type' => 'player_card',
    'name' => clienttranslate('Planner'),
    'bonusGoal' => 'Completed Citizen Tracks',

    'scout' => 'Z', // L O I T Z
    'tradeGood' => 1,

    'soldiers' => 0,
    'builders' => 0,
    'servants' => 1,
    'civilians' => 1,
    'bricks' => 0,
  ],
  'player_card_11' => [
    'type' => 'player_card',
    'name' => clienttranslate('Trainer'),
    'bonusGoal' => 'Total Gladiator Strength',

    'scout' => 'L', // L O I T Z
    'tradeGood' => 4,

    'soldiers' => 0,
    'builders' => 0,
    'servants' => 1,
    'civilians' => 0,
    'bricks' => 1,
  ],
  'player_card_12' => [
    'type' => 'player_card',
    'name' => clienttranslate('Vanguard'),
    'bonusGoal' => 'Completed Wall Guard Sections',

    'scout' => 'Z', // L O I T Z
    'tradeGood' => 2,

    'soldiers' => 0,
    'builders' => 1,
    'servants' => 0,
    'civilians' => 1,
    'bricks' => 0,
  ],
];

$this->fate_card_data =
[
  'fate_card_1' => [
    'type' => 'fate',
    'name' => clienttranslate('Fate Card'),
    'attackDirection' => 'Center',
    'gladiatorStrength' => 2,
    'tradeGood' => 3,
    'soldiers' => 1,
    'builders' => 2,
    'servants' => 2,
    'civilians' => 2,
    'bricks' => 1,
  ],
  'fate_card_2' => [
    'type' => 'fate',
    'name' => clienttranslate('Fate Card'),
    'attackDirection' => 'Right',
    'gladiatorStrength' => 2,
    'tradeGood' => 3,
    'soldiers' => 1,
    'builders' => 2,
    'servants' => 2,
    'civilians' => 1,
    'bricks' => 2,
  ],
  'fate_card_3' => [
    'type' => 'fate',
    'name' => clienttranslate('Fate Card'),
    'attackDirection' => 'Right',
    'gladiatorStrength' => 1,
    'tradeGood' => 2,
    'soldiers' => 1,
    'builders' => 2,
    'servants' => 3,
    'civilians' => 1,
    'bricks' => 1,
  ],
  'fate_card_4' => [
    'type' => 'fate',
    'name' => clienttranslate('Fate Card'),
    'attackDirection' => 'Left',
    'gladiatorStrength' => 3,
    'tradeGood' => 2,
    'soldiers' => 1,
    'builders' => 3,
    'servants' => 1,
    'civilians' => 1,
    'bricks' => 2,
  ],
  'fate_card_5' => [
    'type' => 'fate',
    'name' => clienttranslate('Fate Card'),
    'attackDirection' => 'Center',
    'gladiatorStrength' => 3,
    'tradeGood' => 1,
    'soldiers' => 1,
    'builders' => 1,
    'servants' => 3,
    'civilians' => 2,
    'bricks' => 1,
  ],
  'fate_card_6' => [
    'type' => 'fate',
    'name' => clienttranslate('Fate Card'),
    'attackDirection' => 'Left',
    'gladiatorStrength' => 3,
    'tradeGood' => 2,
    'soldiers' => 2,
    'builders' => 1,
    'servants' => 2,
    'civilians' => 1,
    'bricks' => 2,
  ],
  'fate_card_7' => [
    'type' => 'fate',
    'name' => clienttranslate('Fate Card'),
    'attackDirection' => 'Right',
    'gladiatorStrength' => 2,
    'tradeGood' => 2,
    'soldiers' => 2,
    'builders' => 1,
    'servants' => 2,
    'civilians' => 2,
    'bricks' => 1,
  ],
  'fate_card_8' => [
    'type' => 'fate',
    'name' => clienttranslate('Fate Card'),
    'attackDirection' => 'Left',
    'gladiatorStrength' => 1,
    'tradeGood' => 1,
    'soldiers' => 2,
    'builders' => 1,
    'servants' => 1,
    'civilians' => 2,
    'bricks' => 2,
  ],
  'fate_card_9' => [
    'type' => 'fate',
    'name' => clienttranslate('Fate Card'),
    'attackDirection' => 'Left',
    'gladiatorStrength' => 2,
    'tradeGood' => 5,
    'soldiers' => 2,
    'builders' => 2,
    'servants' => 1,
    'civilians' => 1,
    'bricks' => 2,
  ],
  'fate_card_10' => [
    'type' => 'fate',
    'name' => clienttranslate('Fate Card'),
    'attackDirection' => 'Center',
    'gladiatorStrength' => 2,
    'tradeGood' => 1,
    'soldiers' => 2,
    'builders' => 2,
    'servants' => 2,
    'civilians' => 1,
    'bricks' => 1,
  ],
  'fate_card_11' => [
    'type' => 'fate',
    'name' => clienttranslate('Fate Card'),
    'attackDirection' => 'Center',
    'gladiatorStrength' => 1,
    'tradeGood' => 4,
    'soldiers' => 2,
    'builders' => 2,
    'servants' => 3,
    'civilians' => 1,
    'bricks' => 0,
  ],
  'fate_card_12' => [
    'type' => 'fate',
    'name' => clienttranslate('Fate Card'),
    'attackDirection' => 'Right',
    'gladiatorStrength' => 1,
    'tradeGood' => 1,
    'soldiers' => 2,
    'builders' => 3,
    'servants' => 1,
    'civilians' => 1,
    'bricks' => 1,
  ],
  'fate_card_13' => [
    'type' => 'fate',
    'name' => clienttranslate('Fate Card'),
    'attackDirection' => 'Center',
    'gladiatorStrength' => 2,
    'tradeGood' => 2,
    'soldiers' => 1,
    'builders' => 2,
    'servants' => 2,
    'civilians' => 2,
    'bricks' => 1,
  ],
  'fate_card_14' => [
    'type' => 'fate',
    'name' => clienttranslate('Fate Card'),
    'attackDirection' => 'Right',
    'gladiatorStrength' => 2,
    'tradeGood' => 2,
    'soldiers' => 1,
    'builders' => 2,
    'servants' => 2,
    'civilians' => 1,
    'bricks' => 2,
  ],
  'fate_card_15' => [
    'type' => 'fate',
    'name' => clienttranslate('Fate Card'),
    'attackDirection' => 'Right',
    'gladiatorStrength' => 1,
    'tradeGood' => 6,
    'soldiers' => 1,
    'builders' => 2,
    'servants' => 3,
    'civilians' => 1,
    'bricks' => 1,
  ],
  'fate_card_16' => [
    'type' => 'fate',
    'name' => clienttranslate('Fate Card'),
    'attackDirection' => 'Left',
    'gladiatorStrength' => 3,
    'tradeGood' => 1,
    'soldiers' => 1,
    'builders' => 3,
    'servants' => 1,
    'civilians' => 1,
    'bricks' => 2,
  ],
  'fate_card_17' => [
    'type' => 'fate',
    'name' => clienttranslate('Fate Card'),
    'attackDirection' => 'Center',
    'gladiatorStrength' => 3,
    'tradeGood' => 3,
    'soldiers' => 1,
    'builders' => 1,
    'servants' => 3,
    'civilians' => 2,
    'bricks' => 1,
  ],
  'fate_card_18' => [
    'type' => 'fate',
    'name' => clienttranslate('Fate Card'),
    'attackDirection' => 'Left',
    'gladiatorStrength' => 3,
    'tradeGood' => 1,
    'soldiers' => 2,
    'builders' => 1,
    'servants' => 2,
    'civilians' => 1,
    'bricks' => 2,
  ],
  'fate_card_19' => [
    'type' => 'fate',
    'name' => clienttranslate('Fate Card'),
    'attackDirection' => 'Right',
    'gladiatorStrength' => 2,
    'tradeGood' => 4,
    'soldiers' => 2,
    'builders' => 1,
    'servants' => 2,
    'civilians' => 2,
    'bricks' => 1,
  ],
  'fate_card_20' => [
    'type' => 'fate',
    'name' => clienttranslate('Fate Card'),
    'attackDirection' => 'Left',
    'gladiatorStrength' => 1,
    'tradeGood' => 6,
    'soldiers' => 2,
    'builders' => 1,
    'servants' => 1,
    'civilians' => 2,
    'bricks' => 2,
  ],
  'fate_card_21' => [
    'type' => 'fate',
    'name' => clienttranslate('Fate Card'),
    'attackDirection' => 'Left',
    'gladiatorStrength' => 2,
    'tradeGood' => 1,
    'soldiers' => 2,
    'builders' => 2,
    'servants' => 1,
    'civilians' => 1,
    'bricks' => 2,
  ],
  'fate_card_22' => [
    'type' => 'fate',
    'name' => clienttranslate('Fate Card'),
    'attackDirection' => 'Center',
    'gladiatorStrength' => 2,
    'tradeGood' => 3,
    'soldiers' => 2,
    'builders' => 2,
    'servants' => 2,
    'civilians' => 1,
    'bricks' => 1,
  ],
  'fate_card_23' => [
    'type' => 'fate',
    'name' => clienttranslate('Fate Card'),
    'attackDirection' => 'Center',
    'gladiatorStrength' => 1,
    'tradeGood' => 2,
    'soldiers' => 2,
    'builders' => 2,
    'servants' => 3,
    'civilians' => 1,
    'bricks' => 0,
  ],
  'fate_card_24' => [
    'type' => 'fate',
    'name' => clienttranslate('Fate Card'),
    'attackDirection' => 'Left',
    'gladiatorStrength' => 1,
    'tradeGood' => 4,
    'soldiers' => 2,
    'builders' => 3,
    'servants' => 1,
    'civilians' => 1,
    'bricks' => 1,
  ],
  'fate_card_25' => [
    'type' => 'fate',
    'name' => clienttranslate('Fate Card'),
    'attackDirection' => 'Center',
    'gladiatorStrength' => 2,
    'tradeGood' => 5,
    'soldiers' => 1,
    'builders' => 2,
    'servants' => 2,
    'civilians' => 2,
    'bricks' => 1,
  ],
  'fate_card_26' => [
    'type' => 'fate',
    'name' => clienttranslate('Fate Card'),
    'attackDirection' => 'Right',
    'gladiatorStrength' => 2,
    'tradeGood' => 1,
    'soldiers' => 1,
    'builders' => 2,
    'servants' => 2,
    'civilians' => 1,
    'bricks' => 2,
  ],
  'fate_card_27' => [
    'type' => 'fate',
    'name' => clienttranslate('Fate Card'),
    'attackDirection' => 'Right',
    'gladiatorStrength' => 1,
    'tradeGood' => 4,
    'soldiers' => 1,
    'builders' => 2,
    'servants' => 3,
    'civilians' => 1,
    'bricks' => 1,
  ],
  'fate_card_28' => [
    'type' => 'fate',
    'name' => clienttranslate('Fate Card'),
    'attackDirection' => 'Left',
    'gladiatorStrength' => 3,
    'tradeGood' => 3,
    'soldiers' => 1,
    'builders' => 3,
    'servants' => 1,
    'civilians' => 1,
    'bricks' => 2,
  ],
  'fate_card_29' => [
    'type' => 'fate',
    'name' => clienttranslate('Fate Card'),
    'attackDirection' => 'Center',
    'gladiatorStrength' => 3,
    'tradeGood' => 5,
    'soldiers' => 1,
    'builders' => 1,
    'servants' => 3,
    'civilians' => 2,
    'bricks' => 1,
  ],
  'fate_card_30' => [
    'type' => 'fate',
    'name' => clienttranslate('Fate Card'),
    'attackDirection' => 'Left',
    'gladiatorStrength' => 3,
    'tradeGood' => 3,
    'soldiers' => 2,
    'builders' => 1,
    'servants' => 2,
    'civilians' => 1,
    'bricks' => 2,
  ],
  'fate_card_31' => [
    'type' => 'fate',
    'name' => clienttranslate('Fate Card'),
    'attackDirection' => 'Right',
    'gladiatorStrength' => 2,
    'tradeGood' => 1,
    'soldiers' => 2,
    'builders' => 1,
    'servants' => 2,
    'civilians' => 2,
    'bricks' => 1,
  ],
  'fate_card_32' => [
    'type' => 'fate',
    'name' => clienttranslate('Fate Card'),
    'attackDirection' => 'Left',
    'gladiatorStrength' => 1,
    'tradeGood' => 4,
    'soldiers' => 2,
    'builders' => 1,
    'servants' => 1,
    'civilians' => 2,
    'bricks' => 2,
  ],
  'fate_card_33' => [
    'type' => 'fate',
    'name' => clienttranslate('Fate Card'),
    'attackDirection' => 'Left',
    'gladiatorStrength' => 2,
    'tradeGood' => 3,
    'soldiers' => 2,
    'builders' => 2,
    'servants' => 1,
    'civilians' => 1,
    'bricks' => 2,
  ],
  'fate_card_34' => [
    'type' => 'fate',
    'name' => clienttranslate('Fate Card'),
    'attackDirection' => 'Center',
    'gladiatorStrength' => 2,
    'tradeGood' => 2,
    'soldiers' => 2,
    'builders' => 2,
    'servants' => 2,
    'civilians' => 1,
    'bricks' => 1,
  ],
  'fate_card_35' => [
    'type' => 'fate',
    'name' => clienttranslate('Fate Card'),
    'attackDirection' => 'Center',
    'gladiatorStrength' => 1,
    'tradeGood' => 1,
    'soldiers' => 2,
    'builders' => 2,
    'servants' => 3,
    'civilians' => 1,
    'bricks' => 0,
  ],
  'fate_card_36' => [
    'type' => 'fate',
    'name' => clienttranslate('Fate Card'),
    'attackDirection' => 'Left',
    'gladiatorStrength' => 1,
    'tradeGood' => 6,
    'soldiers' => 2,
    'builders' => 3,
    'servants' => 1,
    'civilians' => 1,
    'bricks' => 1,
  ],
  'fate_card_37' => [
    'type' => 'fate',
    'name' => clienttranslate('Fate Card'),
    'attackDirection' => 'Center',
    'gladiatorStrength' => 2,
    'tradeGood' => 1,
    'soldiers' => 1,
    'builders' => 2,
    'servants' => 2,
    'civilians' => 2,
    'bricks' => 1,
  ],
  'fate_card_38' => [
    'type' => 'fate',
    'name' => clienttranslate('Fate Card'),
    'attackDirection' => 'Right',
    'gladiatorStrength' => 2,
    'tradeGood' => 5,
    'soldiers' => 1,
    'builders' => 2,
    'servants' => 2,
    'civilians' => 1,
    'bricks' => 2,
  ],
  'fate_card_39' => [
    'type' => 'fate',
    'name' => clienttranslate('Fate Card'),
    'attackDirection' => 'Right',
    'gladiatorStrength' => 1,
    'tradeGood' => 1,
    'soldiers' => 1,
    'builders' => 2,
    'servants' => 3,
    'civilians' => 1,
    'bricks' => 1,
  ],
  'fate_card_40' => [
    'type' => 'fate',
    'name' => clienttranslate('Fate Card'),
    'attackDirection' => 'Left',
    'gladiatorStrength' => 3,
    'tradeGood' => 4,
    'soldiers' => 1,
    'builders' => 3,
    'servants' => 1,
    'civilians' => 1,
    'bricks' => 2,
  ],
  'fate_card_41' => [
    'type' => 'fate',
    'name' => clienttranslate('Fate Card'),
    'attackDirection' => 'Center',
    'gladiatorStrength' => 3,
    'tradeGood' => 2,
    'soldiers' => 1,
    'builders' => 1,
    'servants' => 3,
    'civilians' => 2,
    'bricks' => 1,
  ],
  'fate_card_42' => [
    'type' => 'fate',
    'name' => clienttranslate('Fate Card'),
    'attackDirection' => 'Left',
    'gladiatorStrength' => 3,
    'tradeGood' => 4,
    'soldiers' => 2,
    'builders' => 1,
    'servants' => 2,
    'civilians' => 1,
    'bricks' => 2,
  ],
  'fate_card_43' => [
    'type' => 'fate',
    'name' => clienttranslate('Fate Card'),
    'attackDirection' => 'Right',
    'gladiatorStrength' => 2,
    'tradeGood' => 3,
    'soldiers' => 2,
    'builders' => 1,
    'servants' => 2,
    'civilians' => 2,
    'bricks' => 1,
  ],
  'fate_card_44' => [
    'type' => 'fate',
    'name' => clienttranslate('Fate Card'),
    'attackDirection' => 'Left',
    'gladiatorStrength' => 1,
    'tradeGood' => 2,
    'soldiers' => 2,
    'builders' => 1,
    'servants' => 1,
    'civilians' => 2,
    'bricks' => 2,
  ],
  'fate_card_45' => [
    'type' => 'fate',
    'name' => clienttranslate('Fate Card'),
    'attackDirection' => 'Left',
    'gladiatorStrength' => 2,
    'tradeGood' => 2,
    'soldiers' => 2,
    'builders' => 2,
    'servants' => 1,
    'civilians' => 1,
    'bricks' => 2,
  ],
  'fate_card_46' => [
    'type' => 'fate',
    'name' => clienttranslate('Fate Card'),
    'attackDirection' => 'Center',
    'gladiatorStrength' => 2,
    'tradeGood' => 4,
    'soldiers' => 2,
    'builders' => 2,
    'servants' => 2,
    'civilians' => 1,
    'bricks' => 1,
  ],
  'fate_card_47' => [
    'type' => 'fate',
    'name' => clienttranslate('Fate Card'),
    'attackDirection' => 'Center',
    'gladiatorStrength' => 1,
    'tradeGood' => 6,
    'soldiers' => 2,
    'builders' => 2,
    'servants' => 3,
    'civilians' => 1,
    'bricks' => 0,
  ],
  'fate_card_48' => [
    'type' => 'fate',
    'name' => clienttranslate('Fate Card'),
    'attackDirection' => 'Right',
    'gladiatorStrength' => 1,
    'tradeGood' => 2,
    'soldiers' => 2,
    'builders' => 3,
    'servants' => 1,
    'civilians' => 1,
    'bricks' => 1,
  ],
];

//google sheets formula to generate the data below
// =CONCATENATE(if(A2<>A1,CONCATENATE("'",A2,"'=>[",char(13)),""),"[",
// if(isblank(c2),"",CONCATENATE("'lockedBy'=>'",C2,"',")),
// if(isblank(d2),"",CONCATENATE("'cost'=>'",D2,"',")),
// if(isblank(e2),"",CONCATENATE("'altCost'=>'",E2,"',")),
// if(isblank(g2),"",CONCATENATE("'continue'=>",G2,",")),
// if(isblank(f2),"",CONCATENATE("'reward'=>'",F2,"',")),
// "'id'=>'",H2,"'],",
// if(a2<>a3,CONCATENATE(char(13),"],"),""))

// =CONCATENATE(
//   if(isblank(D101),"",
//     concatenate(index(split(D101,","),1,1),"=>",
//     if(iserror(index(split(D101,","),1,2)),1,
//     index(split(D101,","),1,2)))),
//   if(isblank(e101),"",
//     concatenate(",",index(split(e101,","),1,1),"=>",
//     if(iserror(index(split(e101,","),1,2)),1,
//     index(split(e101,","),1,2)))),
//   if(isblank(f101),"",
//     concatenate(",",index(split(f101,","),1,1),"=>",
//     if(iserror(index(split(f101,","),1,2)),1,
//     index(split(f101,","),1,2))))
//   )


$this->section_data = [
  'left_cohort'=>[
    ['cost'=>['cohort'=>1],'id'=>'left_cohort_1'],
    ['cost'=>['cohort'=>1],'id'=>'left_cohort_2'],
    ['cost'=>['cohort'=>1],'reward'=>'discipline','id'=>'left_cohort_3'],
    ['cost'=>['cohort'=>1],'id'=>'left_cohort_4'],
    ['cost'=>['cohort'=>1],'reward'=>'valour','id'=>'left_cohort_5'],
    ['cost'=>['cohort'=>1],'reward'=>'discipline','id'=>'left_cohort_6'],
    ],
    'center_cohort'=>[
    ['cost'=>['cohort'=>1],'id'=>'center_cohort_1'],
    ['cost'=>['cohort'=>1],'id'=>'center_cohort_2'],
    ['cost'=>['cohort'=>1],'reward'=>'discipline','id'=>'center_cohort_3'],
    ['cost'=>['cohort'=>1],'id'=>'center_cohort_4'],
    ['cost'=>['cohort'=>1],'reward'=>'valour','id'=>'center_cohort_5'],
    ['cost'=>['cohort'=>1],'reward'=>'discipline','id'=>'center_cohort_6'],
    ],
    'right_cohort'=>[
    ['cost'=>['cohort'=>1],'id'=>'right_cohort_1'],
    ['cost'=>['cohort'=>1],'id'=>'right_cohort_2'],
    ['cost'=>['cohort'=>1],'reward'=>'discipline','id'=>'right_cohort_3'],
    ['cost'=>['cohort'=>1],'id'=>'right_cohort_4'],
    ['cost'=>['cohort'=>1],'reward'=>'valour','id'=>'right_cohort_5'],
    ['cost'=>['cohort'=>1],'reward'=>'discipline','id'=>'right_cohort_6'],
    ],
    'mining_and_foresting'=>[
    ['cost'=>['servant'=>1],'id'=>'mining_and_foresting_1'],
    ['cost'=>['servant'=>3],'reward'=>'production,brick','id'=>'mining_and_foresting_2'],
    ['cost'=>['servant'=>6],'id'=>'mining_and_foresting_3'],
    ['cost'=>['servant'=>10],'id'=>'mining_and_foresting_4'],
    ['lockedBy'=>['granary'=>1],'cost'=>['servant'=>1],'reward'=>'production,brick','id'=>'mining_and_foresting_5'],
    ['lockedBy'=>['granary'=>1],'cost'=>['servant'=>1],'id'=>'mining_and_foresting_6'],
    ['lockedBy'=>['granary'=>1],'cost'=>['servant'=>1],'id'=>'mining_and_foresting_7'],
    ['lockedBy'=>['granary'=>1],'cost'=>['servant'=>1],'reward'=>'production,brick','id'=>'mining_and_foresting_8'],
    ['lockedBy'=>['granary'=>1],'cost'=>['servant'=>1],'id'=>'mining_and_foresting_9'],
    ['lockedBy'=>['granary'=>2],'cost'=>['servant'=>1],'id'=>'mining_and_foresting_10'],
    ['lockedBy'=>['granary'=>2],'cost'=>['servant'=>1],'reward'=>'production,brick','id'=>'mining_and_foresting_11'],
    ['lockedBy'=>['granary'=>2],'cost'=>['servant'=>1],'id'=>'mining_and_foresting_12'],
    ['lockedBy'=>['granary'=>2],'cost'=>['servant'=>1],'id'=>'mining_and_foresting_13'],
    ['lockedBy'=>['granary'=>2],'cost'=>['servant'=>1],'reward'=>'production,brick','id'=>'mining_and_foresting_14'],
    ],
    'wall_guard'=>[
    ['cost'=>['soldier'=>1],'altCost'=>['sword'=>1],'reward'=>'cohort','id'=>'wall_guard_1'],
    ['cost'=>['soldier'=>1],'altCost'=>['sword'=>1],'reward'=>'discipline','id'=>'wall_guard_2'],
    ['cost'=>['soldier'=>1],'altCost'=>['sword'=>1],'reward'=>'cohort','id'=>'wall_guard_3'],
    ['cost'=>['soldier'=>1],'altCost'=>['sword'=>1],'id'=>'wall_guard_4'],
    ['cost'=>['soldier'=>1],'altCost'=>['sword'=>1],'reward'=>'discipline','id'=>'wall_guard_5'],
    ['cost'=>['soldier'=>1],'altCost'=>['sword'=>1],'reward'=>'cohort','id'=>'wall_guard_6'],
    ['lockedBy'=>['granary'=>1],'cost'=>['soldier'=>1],'altCost'=>['sword'=>1],'id'=>'wall_guard_7'],
    ['lockedBy'=>['granary'=>1],'cost'=>['soldier'=>1],'altCost'=>['sword'=>1],'reward'=>'discipline','id'=>'wall_guard_8'],
    ['lockedBy'=>['granary'=>1],'cost'=>['soldier'=>1],'altCost'=>['sword'=>1],'reward'=>'cohort','id'=>'wall_guard_9'],
    ['lockedBy'=>['granary'=>1],'cost'=>['soldier'=>1],'altCost'=>['sword'=>1],'id'=>'wall_guard_10'],
    ['lockedBy'=>['granary'=>1],'cost'=>['soldier'=>1],'altCost'=>['sword'=>1],'reward'=>'discipline','id'=>'wall_guard_11'],
    ['lockedBy'=>['granary'=>1],'cost'=>['soldier'=>1],'altCost'=>['sword'=>1],'reward'=>'cohort','id'=>'wall_guard_12'],
    ['lockedBy'=>['granary'=>2],'cost'=>['soldier'=>1],'altCost'=>['sword'=>1],'id'=>'wall_guard_13'],
    ['lockedBy'=>['granary'=>2],'cost'=>['soldier'=>1],'altCost'=>['sword'=>1],'reward'=>'discipline','id'=>'wall_guard_14'],
    ['lockedBy'=>['granary'=>2],'cost'=>['soldier'=>1],'altCost'=>['sword'=>1],'reward'=>'cohort','id'=>'wall_guard_15'],
    ['lockedBy'=>['granary'=>2],'cost'=>['soldier'=>1],'altCost'=>['sword'=>1],'id'=>'wall_guard_16'],
    ['lockedBy'=>['granary'=>2],'cost'=>['soldier'=>1],'altCost'=>['sword'=>1],'reward'=>'discipline','id'=>'wall_guard_17'],
    ['lockedBy'=>['granary'=>2],'cost'=>['soldier'=>1],'altCost'=>['sword'=>1],'reward'=>'cohort','id'=>'wall_guard_18'],
    ],
    'cippi'=>[
    ['lockedBy'=>['fort'=>1],'cost'=>['brick'=>1],'id'=>'cippi_1'],
    ['lockedBy'=>['fort'=>2],'cost'=>['brick'=>1],'id'=>'cippi_2'],
    ['lockedBy'=>['fort'=>7],'cost'=>['brick'=>1],'reward'=>'cohort','id'=>'cippi_3'],
    ['lockedBy'=>['fort'=>10],'cost'=>['brick'=>1],'reward'=>'civilian','id'=>'cippi_4'],
    ['lockedBy'=>['fort'=>13],'cost'=>['brick'=>1],'reward'=>'cohort','id'=>'cippi_5'],
    ['lockedBy'=>['fort'=>16],'cost'=>['brick'=>1],'reward'=>'civilian','id'=>'cippi_6'],
    ['lockedBy'=>['fort'=>20],'cost'=>['brick'=>1],'reward'=>'cohort','id'=>'cippi_7'],
    ],
    'wall'=>[
    ['lockedBy'=>['fort'=>1],'cost'=>['brick'=>1],'reward'=>'civilian','id'=>'wall_1'],
    ['lockedBy'=>['fort'=>2],'cost'=>['brick'=>1],'id'=>'wall_2'],
    ['lockedBy'=>['fort'=>3],'cost'=>['brick'=>1],'reward'=>'civilian','id'=>'wall_3'],
    ['lockedBy'=>['fort'=>4],'cost'=>['brick'=>1],'reward'=>'renown,cohort','id'=>'wall_4'],
    ['lockedBy'=>['fort'=>5],'cost'=>['brick'=>1],'reward'=>'civilian','id'=>'wall_5'],
    ['lockedBy'=>['fort'=>6],'cost'=>['brick'=>1],'id'=>'wall_6'],
    ['lockedBy'=>['fort'=>7],'cost'=>['brick'=>1],'reward'=>'cohort','id'=>'wall_7'],
    ['lockedBy'=>['fort'=>8],'cost'=>['brick'=>1],'reward'=>'renown','id'=>'wall_8'],
    ['lockedBy'=>['fort'=>9],'cost'=>['brick'=>1],'reward'=>'civilian','id'=>'wall_9'],
    ['lockedBy'=>['fort'=>10],'cost'=>['brick'=>1],'id'=>'wall_10'],
    ['lockedBy'=>['fort'=>11],'cost'=>['brick'=>1],'reward'=>'renown,cohort','id'=>'wall_11'],
    ['lockedBy'=>['fort'=>12],'cost'=>['brick'=>1],'id'=>'wall_12'],
    ['lockedBy'=>['fort'=>13],'cost'=>['brick'=>1],'reward'=>'civilian','id'=>'wall_13'],
    ['lockedBy'=>['fort'=>14],'cost'=>['brick'=>1],'reward'=>'cohort','id'=>'wall_14'],
    ['lockedBy'=>['fort'=>15],'cost'=>['brick'=>1],'reward'=>'renown','id'=>'wall_15'],
    ['lockedBy'=>['fort'=>16],'cost'=>['brick'=>1],'reward'=>'civilian','id'=>'wall_16'],
    ['lockedBy'=>['fort'=>17],'cost'=>['brick'=>1],'id'=>'wall_17'],
    ['lockedBy'=>['fort'=>18],'cost'=>['brick'=>1],'reward'=>'renown,cohort','id'=>'wall_18'],
    ['lockedBy'=>['fort'=>19],'cost'=>['brick'=>1],'reward'=>'civilian','id'=>'wall_19'],
    ['lockedBy'=>['fort'=>20],'cost'=>['brick'=>1],'id'=>'wall_20'],
    ['lockedBy'=>['fort'=>21],'cost'=>['brick'=>1],'reward'=>'cohort','id'=>'wall_21'],
    ],
    'fort'=>[
    ['cost'=>['builder'=>1],'altCost'=>['soldier'=>1],'id'=>'fort_1'],
    ['cost'=>['builder'=>1],'altCost'=>['soldier'=>1],'id'=>'fort_2'],
    ['cost'=>['builder'=>1],'altCost'=>['soldier'=>1],'id'=>'fort_3'],
    ['cost'=>['builder'=>1],'altCost'=>['soldier'=>1],'reward'=>'civilian','id'=>'fort_4'],
    ['cost'=>['builder'=>1],'altCost'=>['soldier'=>1],'id'=>'fort_5'],
    ['cost'=>['builder'=>1],'altCost'=>['soldier'=>1],'reward'=>'civilian','id'=>'fort_6'],
    ['cost'=>['builder'=>1],'altCost'=>['soldier'=>1],'reward'=>'discipline','id'=>'fort_7'],
    ['lockedBy'=>['granary'=>1],'cost'=>['builder'=>1],'altCost'=>['soldier'=>1],'reward'=>'civilian','id'=>'fort_8'],
    ['lockedBy'=>['granary'=>1],'cost'=>['builder'=>1],'altCost'=>['soldier'=>1],'id'=>'fort_9'],
    ['lockedBy'=>['granary'=>1],'cost'=>['builder'=>1],'altCost'=>['soldier'=>1],'reward'=>'civilian','id'=>'fort_10'],
    ['lockedBy'=>['granary'=>1],'cost'=>['builder'=>1],'altCost'=>['soldier'=>1],'id'=>'fort_11'],
    ['lockedBy'=>['granary'=>1],'cost'=>['builder'=>1],'altCost'=>['soldier'=>1],'reward'=>'civilian','id'=>'fort_12'],
    ['lockedBy'=>['granary'=>1],'cost'=>['builder'=>1],'altCost'=>['soldier'=>1],'id'=>'fort_13'],
    ['lockedBy'=>['granary'=>1],'cost'=>['builder'=>1],'altCost'=>['soldier'=>1],'reward'=>'discipline,cohort','id'=>'fort_14'],
    ['lockedBy'=>['granary'=>2],'cost'=>['builder'=>1],'altCost'=>['soldier'=>1],'id'=>'fort_15'],
    ['lockedBy'=>['granary'=>2],'cost'=>['builder'=>1],'altCost'=>['soldier'=>1],'reward'=>'civilian','id'=>'fort_16'],
    ['lockedBy'=>['granary'=>2],'cost'=>['builder'=>1],'altCost'=>['soldier'=>1],'id'=>'fort_17'],
    ['lockedBy'=>['granary'=>2],'cost'=>['builder'=>1],'altCost'=>['soldier'=>1],'reward'=>'civilian','id'=>'fort_18'],
    ['lockedBy'=>['granary'=>2],'cost'=>['builder'=>1],'altCost'=>['soldier'=>1],'id'=>'fort_19'],
    ['lockedBy'=>['granary'=>2],'cost'=>['builder'=>1],'altCost'=>['soldier'=>1],'reward'=>'civilian','id'=>'fort_20'],
    ['lockedBy'=>['granary'=>2],'cost'=>['builder'=>1],'altCost'=>['soldier'=>1],'reward'=>'discipline,cohort','id'=>'fort_21'],
    ],
    'granary'=>[
    ['lockedBy'=>['fort'=>1],'cost'=>['servant'=>1,'builder'=>1,'brick'=>1],'id'=>'granary_1'],
    ['lockedBy'=>['fort'=>11],'cost'=>['servant'=>1,'builder'=>1,'brick'=>2],'continue'=>TRUE,'id'=>'granary_2'],
    ['reward'=>'renown','id'=>'granary_3'],
    ],
    'renown'=>[
    ['cost'=>['renown'=>1],'id'=>'renown_1'],
    ['cost'=>['renown'=>1],'id'=>'renown_2'],
    ['cost'=>['renown'=>1],'reward'=>'civilian','id'=>'renown_3'],
    ['cost'=>['renown'=>1],'id'=>'renown_4'],
    ['cost'=>['renown'=>1],'id'=>'renown_5'],
    ['cost'=>['renown'=>1],'reward'=>'civilian','id'=>'renown_6'],
    ['cost'=>['renown'=>1],'id'=>'renown_7'],
    ['cost'=>['renown'=>1],'id'=>'renown_8'],
    ['cost'=>['renown'=>1],'reward'=>'civilian','id'=>'renown_9'],
    ['cost'=>['renown'=>1],'id'=>'renown_10'],
    ['cost'=>['renown'=>1],'id'=>'renown_11'],
    ['cost'=>['renown'=>1],'reward'=>'civilian','id'=>'renown_12'],
    ['cost'=>['renown'=>1],'id'=>'renown_13'],
    ['cost'=>['renown'=>1],'id'=>'renown_14'],
    ['cost'=>['renown'=>1],'reward'=>'civilian','id'=>'renown_15'],
    ['cost'=>['renown'=>1],'id'=>'renown_16'],
    ['cost'=>['renown'=>1],'reward'=>'peity','id'=>'renown_17'],
    ['cost'=>['renown'=>1],'id'=>'renown_18'],
    ['cost'=>['renown'=>1],'reward'=>'civilian','id'=>'renown_19'],
    ['cost'=>['renown'=>1],'id'=>'renown_20'],
    ['cost'=>['renown'=>1],'reward'=>'discipline','id'=>'renown_21'],
    ['cost'=>['renown'=>1],'id'=>'renown_22'],
    ['cost'=>['renown'=>1],'reward'=>'civilian','id'=>'renown_23'],
    ['cost'=>['renown'=>1],'id'=>'renown_24'],
    ['cost'=>['renown'=>1],'reward'=>'valour','id'=>'renown_25'],
    ],
    'piety'=>[
    ['cost'=>['piety'=>1],'id'=>'piety_1'],
    ['cost'=>['piety'=>1],'id'=>'piety_2'],
    ['cost'=>['piety'=>1],'reward'=>'servant','id'=>'piety_3'],
    ['cost'=>['piety'=>1],'id'=>'piety_4'],
    ['cost'=>['piety'=>1],'id'=>'piety_5'],
    ['cost'=>['piety'=>1],'reward'=>'servant','id'=>'piety_6'],
    ['cost'=>['piety'=>1],'id'=>'piety_7'],
    ['cost'=>['piety'=>1],'id'=>'piety_8'],
    ['cost'=>['piety'=>1],'reward'=>'servant','id'=>'piety_9'],
    ['cost'=>['piety'=>1],'id'=>'piety_10'],
    ['cost'=>['piety'=>1],'id'=>'piety_11'],
    ['cost'=>['piety'=>1],'reward'=>'servant','id'=>'piety_12'],
    ['cost'=>['piety'=>1],'id'=>'piety_13'],
    ['cost'=>['piety'=>1],'id'=>'piety_14'],
    ['cost'=>['piety'=>1],'reward'=>'servant','id'=>'piety_15'],
    ['cost'=>['piety'=>1],'id'=>'piety_16'],
    ['cost'=>['piety'=>1],'reward'=>'renown','id'=>'piety_17'],
    ['cost'=>['piety'=>1],'id'=>'piety_18'],
    ['cost'=>['piety'=>1],'reward'=>'servant','id'=>'piety_19'],
    ['cost'=>['piety'=>1],'id'=>'piety_20'],
    ['cost'=>['piety'=>1],'reward'=>'valour','id'=>'piety_21'],
    ['cost'=>['piety'=>1],'id'=>'piety_22'],
    ['cost'=>['piety'=>1],'reward'=>'servant','id'=>'piety_23'],
    ['cost'=>['piety'=>1],'id'=>'piety_24'],
    ['cost'=>['piety'=>1],'reward'=>'discipline','id'=>'piety_25'],
    ],
    'valour'=>[
    ['cost'=>['valour'=>1],'id'=>'valour_1'],
    ['cost'=>['valour'=>1],'id'=>'valour_2'],
    ['cost'=>['valour'=>1],'reward'=>'soldier','id'=>'valour_3'],
    ['cost'=>['valour'=>1],'id'=>'valour_4'],
    ['cost'=>['valour'=>1],'id'=>'valour_5'],
    ['cost'=>['valour'=>1],'reward'=>'soldier','id'=>'valour_6'],
    ['cost'=>['valour'=>1],'id'=>'valour_7'],
    ['cost'=>['valour'=>1],'id'=>'valour_8'],
    ['cost'=>['valour'=>1],'reward'=>'soldier','id'=>'valour_9'],
    ['cost'=>['valour'=>1],'id'=>'valour_10'],
    ['cost'=>['valour'=>1],'id'=>'valour_11'],
    ['cost'=>['valour'=>1],'reward'=>'soldier','id'=>'valour_12'],
    ['cost'=>['valour'=>1],'id'=>'valour_13'],
    ['cost'=>['valour'=>1],'id'=>'valour_14'],
    ['cost'=>['valour'=>1],'reward'=>'soldier','id'=>'valour_15'],
    ['cost'=>['valour'=>1],'id'=>'valour_16'],
    ['cost'=>['valour'=>1],'reward'=>'discipline','id'=>'valour_17'],
    ['cost'=>['valour'=>1],'id'=>'valour_18'],
    ['cost'=>['valour'=>1],'reward'=>'soldier','id'=>'valour_19'],
    ['cost'=>['valour'=>1],'id'=>'valour_20'],
    ['cost'=>['valour'=>1],'reward'=>'renown','id'=>'valour_21'],
    ['cost'=>['valour'=>1],'id'=>'valour_22'],
    ['cost'=>['valour'=>1],'reward'=>'soldier','id'=>'valour_23'],
    ['cost'=>['valour'=>1],'id'=>'valour_24'],
    ['cost'=>['valour'=>1],'reward'=>'piety','id'=>'valour_25'],
    ],
    'discipline'=>[
    ['cost'=>['discipline'=>1],'id'=>'discipline_1'],
    ['cost'=>['discipline'=>1],'id'=>'discipline_2'],
    ['cost'=>['discipline'=>1],'reward'=>'builder','id'=>'discipline_3'],
    ['cost'=>['discipline'=>1],'id'=>'discipline_4'],
    ['cost'=>['discipline'=>1],'id'=>'discipline_5'],
    ['cost'=>['discipline'=>1],'reward'=>'builder','id'=>'discipline_6'],
    ['cost'=>['discipline'=>1],'id'=>'discipline_7'],
    ['cost'=>['discipline'=>1],'id'=>'discipline_8'],
    ['cost'=>['discipline'=>1],'reward'=>'builder','id'=>'discipline_9'],
    ['cost'=>['discipline'=>1],'id'=>'discipline_10'],
    ['cost'=>['discipline'=>1],'id'=>'discipline_11'],
    ['cost'=>['discipline'=>1],'reward'=>'builder','id'=>'discipline_12'],
    ['cost'=>['discipline'=>1],'id'=>'discipline_13'],
    ['cost'=>['discipline'=>1],'id'=>'discipline_14'],
    ['cost'=>['discipline'=>1],'reward'=>'builder','id'=>'discipline_15'],
    ['cost'=>['discipline'=>1],'id'=>'discipline_16'],
    ['cost'=>['discipline'=>1],'reward'=>'valour','id'=>'discipline_17'],
    ['cost'=>['discipline'=>1],'id'=>'discipline_18'],
    ['cost'=>['discipline'=>1],'reward'=>'builder','id'=>'discipline_19'],
    ['cost'=>['discipline'=>1],'id'=>'discipline_20'],
    ['cost'=>['discipline'=>1],'reward'=>'piety','id'=>'discipline_21'],
    ['cost'=>['discipline'=>1],'id'=>'discipline_22'],
    ['cost'=>['discipline'=>1],'reward'=>'builder','id'=>'discipline_23'],
    ['cost'=>['discipline'=>1],'id'=>'discipline_24'],
    ['cost'=>['discipline'=>1],'reward'=>'renown','id'=>'discipline_25'],
    ],
    'traders'=>[
    ['cost'=>['traders'=>1],'altCost'=>['civilian'=>1],'reward'=>'servant','id'=>'traders_1'],
    ['cost'=>['traders'=>1],'altCost'=>['civilian'=>1],'id'=>'traders_2'],
    ['cost'=>['traders'=>1],'altCost'=>['civilian'=>1],'reward'=>'builder','id'=>'traders_3'],
    ['cost'=>['traders'=>1],'altCost'=>['civilian'=>1],'id'=>'traders_4'],
    ['cost'=>['traders'=>1],'altCost'=>['civilian'=>1],'reward'=>'brick','id'=>'traders_5'],
    ['cost'=>['traders'=>1],'altCost'=>['civilian'=>1],'id'=>'traders_6'],
    ['cost'=>['traders'=>1],'altCost'=>['civilian'=>1],'reward'=>'builder','id'=>'traders_7'],
    ['cost'=>['traders'=>1],'altCost'=>['civilian'=>1],'reward'=>'renown','id'=>'traders_8'],
    ['cost'=>['traders'=>1],'altCost'=>['civilian'=>1],'reward'=>'brick','id'=>'traders_9'],
    ],
    'performers'=>[
    ['cost'=>['performers'=>1],'altCost'=>['civilian'=>1],'reward'=>'builder','id'=>'performers_1'],
    ['cost'=>['performers'=>1],'altCost'=>['civilian'=>1],'id'=>'performers_2'],
    ['cost'=>['performers'=>1],'altCost'=>['civilian'=>1],'reward'=>'servant','id'=>'performers_3'],
    ['cost'=>['performers'=>1],'altCost'=>['civilian'=>1],'id'=>'performers_4'],
    ['cost'=>['performers'=>1],'altCost'=>['civilian'=>1],'reward'=>'soldier','id'=>'performers_5'],
    ['cost'=>['performers'=>1],'altCost'=>['civilian'=>1],'id'=>'performers_6'],
    ['cost'=>['performers'=>1],'altCost'=>['civilian'=>1],'reward'=>'servant','id'=>'performers_7'],
    ['cost'=>['performers'=>1],'altCost'=>['civilian'=>1],'reward'=>'renown','id'=>'performers_8'],
    ['cost'=>['performers'=>1],'altCost'=>['civilian'=>1],'reward'=>'builder','id'=>'performers_9'],
    ],
    'priests'=>[
    ['cost'=>['priests'=>1],'altCost'=>['civilian'=>1],'reward'=>'servant','id'=>'priests_1'],
    ['cost'=>['priests'=>1],'altCost'=>['civilian'=>1],'id'=>'priests_2'],
    ['cost'=>['priests'=>1],'altCost'=>['civilian'=>1],'reward'=>'servant','id'=>'priests_3'],
    ['cost'=>['priests'=>1],'altCost'=>['civilian'=>1],'reward'=>'piety','id'=>'priests_4'],
    ['cost'=>['priests'=>1],'altCost'=>['civilian'=>1],'reward'=>'servant','id'=>'priests_5'],
    ['cost'=>['priests'=>1],'altCost'=>['civilian'=>1],'reward'=>'piety','id'=>'priests_6'],
    ['cost'=>['priests'=>1],'altCost'=>['civilian'=>1],'reward'=>'servant','id'=>'priests_7'],
    ['cost'=>['priests'=>1],'altCost'=>['civilian'=>1],'reward'=>'piety','id'=>'priests_8'],
    ['cost'=>['priests'=>1],'altCost'=>['civilian'=>1],'reward'=>'servant','id'=>'priests_9'],
    ],
    'apparitores'=>[
    ['cost'=>['apparitores'=>1],'altCost'=>['civilian'=>1],'reward'=>'builder','id'=>'apparitores_1'],
    ['cost'=>['apparitores'=>1],'altCost'=>['civilian'=>1],'id'=>'apparitores_2'],
    ['cost'=>['apparitores'=>1],'altCost'=>['civilian'=>1],'reward'=>'soldier','id'=>'apparitores_3'],
    ['cost'=>['apparitores'=>1],'altCost'=>['civilian'=>1],'reward'=>'discipline','id'=>'apparitores_4'],
    ['cost'=>['apparitores'=>1],'altCost'=>['civilian'=>1],'reward'=>'builder','id'=>'apparitores_5'],
    ['cost'=>['apparitores'=>1],'altCost'=>['civilian'=>1],'reward'=>'discipline','id'=>'apparitores_6'],
    ['cost'=>['apparitores'=>1],'altCost'=>['civilian'=>1],'reward'=>'soldier','id'=>'apparitores_7'],
    ['cost'=>['apparitores'=>1],'altCost'=>['civilian'=>1],'reward'=>'discipline','id'=>'apparitores_8'],
    ['cost'=>['apparitores'=>1],'altCost'=>['civilian'=>1],'reward'=>'builder','id'=>'apparitores_9'],
    ],
    'patricians'=>[
    ['cost'=>['patricians'=>1],'altCost'=>['civilian'=>1],'reward'=>'brick','id'=>'patricians_1'],
    ['cost'=>['patricians'=>1],'altCost'=>['civilian'=>1],'id'=>'patricians_2'],
    ['cost'=>['patricians'=>1],'altCost'=>['civilian'=>1],'reward'=>'soldier','id'=>'patricians_3'],
    ['cost'=>['patricians'=>1],'altCost'=>['civilian'=>1],'reward'=>'renown','id'=>'patricians_4'],
    ['cost'=>['patricians'=>1],'altCost'=>['civilian'=>1],'reward'=>'brick','id'=>'patricians_5'],
    ['cost'=>['patricians'=>1],'altCost'=>['civilian'=>1],'reward'=>'renown','id'=>'patricians_6'],
    ['cost'=>['patricians'=>1],'altCost'=>['civilian'=>1],'reward'=>'soldier','id'=>'patricians_7'],
    ['cost'=>['patricians'=>1],'altCost'=>['civilian'=>1],'reward'=>'renown','id'=>'patricians_8'],
    ['cost'=>['patricians'=>1],'altCost'=>['civilian'=>1],'reward'=>'brick','id'=>'patricians_9'],
    ],
];


