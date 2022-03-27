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

$this->rounds = [
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


$this->player_cards = [
  'player_card_1' => [
    'type' => 'player_card',
    'name' => clienttranslate('Engineer'),
    'bonusGoal' => 'Large Buildings',

    'scout' => 'L', // O I T Z
    'tradeGood' => 2,

    'soldiers' => 0,
    'builders' => 1,
    'servants' => 0,
    'civilians' => 0,
    'resources' => 1,
  ],

  'player_card_2' => [
    'type' => 'player_card',
    'name' => clienttranslate('Defender'),
    'bonusGoal' => 'Completed Wall Sections',

    'scout' => 'L', // O I T Z
    'tradeGood' => 1,

    'soldiers' => 0,
    'builders' => 1,
    'servants' => 0,
    'civilians' => 0,
    'resources' => 1,
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
    'resources' => 1,
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
    'resources' => 0,
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
    'resources' => 0,
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
    'resources' => 2,
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
    'resources' => 1,
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
    'resources' => 0,
  ],
  'player_card_9' => [
    'type' => 'player_card',
    'name' => clienttranslate('Pontiff'),
    'bonusGoal' => 'Filled Temples',

    'scout' => 'T', // L O I T Z
    'tradeGood' => 2,

    'soldiers' => 0,
    'builders' => 2,
    'servants' => 0,
    'civilians' => 0,
    'resources' => 0,
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
    'resources' => 0,
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
    'resources' => 1,
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
    'resources' => 0,
  ],
];

$this->fate_cards =
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
    'resources' => 1,
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
    'resources' => 2,
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
    'resources' => 1,
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
    'resources' => 2,
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
    'resources' => 1,
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
    'resources' => 2,
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
    'resources' => 1,
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
    'resources' => 2,
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
    'resources' => 2,
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
    'resources' => 1,
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
    'resources' => 0,
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
    'resources' => 1,
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
    'resources' => 1,
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
    'resources' => 2,
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
    'resources' => 1,
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
    'resources' => 2,
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
    'resources' => 1,
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
    'resources' => 2,
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
    'resources' => 1,
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
    'resources' => 2,
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
    'resources' => 2,
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
    'resources' => 1,
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
    'resources' => 0,
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
    'resources' => 1,
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
    'resources' => 1,
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
    'resources' => 2,
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
    'resources' => 1,
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
    'resources' => 2,
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
    'resources' => 1,
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
    'resources' => 2,
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
    'resources' => 1,
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
    'resources' => 2,
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
    'resources' => 2,
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
    'resources' => 1,
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
    'resources' => 0,
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
    'resources' => 1,
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
    'resources' => 1,
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
    'resources' => 2,
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
    'resources' => 1,
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
    'resources' => 2,
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
    'resources' => 1,
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
    'resources' => 2,
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
    'resources' => 1,
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
    'resources' => 2,
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
    'resources' => 2,
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
    'resources' => 1,
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
    'resources' => 0,
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
    'resources' => 1,
  ],
];


/*
cardId	
attackDirection	
gladiatorStrength	
tradeGood	
soldiers	
builders	
servants	
civilians	
resources
1	Left	1	1	2	1	1	2	2
2	Left	2	1	2	2	1	1	2
3	Left	3	1	2	1	2	1	2
4	Left	3	1	1	3	1	1	2
5	Centre	1	1	2	2	3	1	0
6	Centre	2	1	2	2	2	1	1
7	Centre	2	1	1	2	2	2	1
8	Centre	3	1	1	1	3	2	1
9	Right	1	1	2	3	1	1	1
10	Right	1	1	1	2	3	1	1
11	Right	2	1	2	1	2	2	1
12	Right	2	1	1	2	2	1	2
13	Left	1	2	2	1	1	2	2
14	Left	2	2	2	2	1	1	2
15	Left	3	2	2	1	2	1	2
16	Left	3	2	1	3	1	1	2
17	Centre	1	2	2	2	3	1	0
18	Centre	2	2	2	2	2	1	1
19	Centre	2	2	1	2	2	2	1
20	Centre	3	2	1	1	3	2	1
21	Right	1	2	2	3	1	1	1
22	Right	1	2	1	2	3	1	1
23	Right	2	2	1	2	2	1	2
24	Right	2	2	2	1	2	2	1
25	Left	2	3	2	2	1	1	2
26	Left	3	3	2	1	2	1	2
27	Left	3	3	1	3	1	1	2
28	Centre	2	3	2	2	2	1	1
29	Centre	2	3	1	2	2	2	1
30	Centre	3	3	1	1	3	2	1
31	Right	2	3	2	1	2	2	1
32	Right	2	3	1	2	2	1	2
33	Left	1	4	2	1	1	2	2
34	Left	3	4	1	3	1	1	2
35	Left	3	4	2	1	2	1	2
36	Centre	1	4	2	2	3	1	0
37	Centre	2	4	2	2	2	1	1
38	Right	1	4	2	3	1	1	1
39	Right	1	4	1	2	3	1	1
40	Right	2	4	2	1	2	2	1
41	Left	2	5	2	2	1	1	2
42	Centre	2	5	1	2	2	2	1
43	Centre	3	5	1	1	3	2	1
44	Right	2	5	1	2	2	1	2
45	Left	1	6	2	1	1	2	2
46	Centre	1	6	2	2	3	1	0
47	Right	1	6	1	2	3	1	1
48	Right	1	6	2	3	1	1	1
*/


