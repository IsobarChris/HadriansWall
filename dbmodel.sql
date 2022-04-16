
-- ------
-- BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
-- HadriansWall implementation : © <Your name here> <Your email address here>
-- 
-- This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
-- See http://en.boardgamearena.com/#!doc/Studio for more information.
-- -----

-- dbmodel.sql

-- This is the file where you are describing the database schema of your game
-- Basically, you just have to export from PhpMyAdmin your table structure and copy/paste
-- this export here.
-- Note that the database itself and the standard tables ("global", "stats", "gamelog" and "player") are
-- already created and must not be created here

-- Note: The database schema is created from this file when the game starts. If you modify this file,
--       you have to restart a game to see your changes in database.

-- Example 1: create a standard "card" table to be used with the "Deck" tools (see example game "hearts"):

-- CREATE TABLE IF NOT EXISTS `card` (
--   `card_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
--   `card_type` varchar(16) NOT NULL,
--   `card_type_arg` int(11) NOT NULL,
--   `card_location` varchar(16) NOT NULL,
--   `card_location_arg` int(11) NOT NULL,
--   PRIMARY KEY (`card_id`)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- Example 2: add a custom field to the standard "player" table
-- ALTER TABLE `player` ADD `player_my_custom_field` INT UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE `player` ADD `civilians` INT UNSIGNED NOT NULL DEFAULT 0;
ALTER TABLE `player` ADD `servants`  INT UNSIGNED NOT NULL DEFAULT 0;
ALTER TABLE `player` ADD `soldiers`  INT UNSIGNED NOT NULL DEFAULT 0;
ALTER TABLE `player` ADD `builders`  INT UNSIGNED NOT NULL DEFAULT 0;
ALTER TABLE `player` ADD `bricks`    INT UNSIGNED NOT NULL DEFAULT 0;
ALTER TABLE `player` ADD `special`    varchar(32);

CREATE TABLE IF NOT EXISTS `fate_cards` (
  `card_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `card_type` varchar(16) NOT NULL,
  `card_type_arg` int(11) NOT NULL,
  `card_location` varchar(16) NOT NULL,
  `card_location_arg` int(11) NOT NULL,
  PRIMARY KEY (`card_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `player_cards` (
  `card_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `card_type` varchar(16) NOT NULL,
  `card_type_arg` int(11) NOT NULL,
  `card_location` varchar(16) NOT NULL,
  `card_location_arg` int(11) NOT NULL,
  PRIMARY KEY (`card_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `rounds` (
    `round` int(2) NOT NULL,
    `fate_resource_card` varchar(32) NOT NULL,
    PRIMARY KEY (`round`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `attacks` (
    `player_id` int(10) NOT NULL,
    `round` int(2) NOT NULL,
    `fate_attack_card` varchar(32) NOT NULL,
    PRIMARY KEY (`player_id`,`round`,`fate_attack_card`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `board` (
    `player_id` int(10) NOT NULL,
    `round` int(1) NOT NULL DEFAULT 0,
    `left_cohort` int(1) unsigned NOT NULL DEFAULT 0,
    `center_cohort` int(1) unsigned NOT NULL DEFAULT 0,
    `right_cohort` int(1) unsigned NOT NULL DEFAULT 0,
    `mining_and_foresting` int(2) unsigned NOT NULL DEFAULT 0,
    `wall_guard` int(2) unsigned NOT NULL DEFAULT 0,
    `cippi` int(1) unsigned NOT NULL DEFAULT 0,
    `wall` int(2) unsigned NOT NULL DEFAULT 0,
    `fort` int(2) unsigned NOT NULL DEFAULT 0,
    `granary` int(1) unsigned NOT NULL DEFAULT 0,
    `resource_production` int(1) unsigned NOT NULL DEFAULT 0,
    `training_grounds` int(1) unsigned NOT NULL DEFAULT 0,
    `training_grounds_1` int(1) unsigned NOT NULL DEFAULT 0,
    `training_grounds_2` int(1) unsigned NOT NULL DEFAULT 0,
    `training_grounds_3` int(1) unsigned NOT NULL DEFAULT 0,
    `training_grounds_4` int(1) unsigned NOT NULL DEFAULT 0,
    `training_grounds_5` int(1) unsigned NOT NULL DEFAULT 0,
    `hotel` int(1) unsigned NOT NULL DEFAULT 0,
    `workshop` int(1) unsigned NOT NULL DEFAULT 0,
    `road` int(1) unsigned NOT NULL DEFAULT 0,
    `forum` int(1) unsigned NOT NULL DEFAULT 0,
    `forum_1` int(1) unsigned NOT NULL DEFAULT 0,
    `forum_2` int(1) unsigned NOT NULL DEFAULT 0,
    `forum_3` int(1) unsigned NOT NULL DEFAULT 0,
    `forum_4` int(1) unsigned NOT NULL DEFAULT 0,
    `archway` int(1) unsigned NOT NULL DEFAULT 0,
    `monolith` int(1) unsigned NOT NULL DEFAULT 0,
    `column` int(1) unsigned NOT NULL DEFAULT 0,
    `statue` int(1) unsigned NOT NULL DEFAULT 0,
    `renown` int(2) unsigned NOT NULL DEFAULT 0,
    `piety` int(2) unsigned NOT NULL DEFAULT 0,
    `valour` int(2) unsigned NOT NULL DEFAULT 0,
    `discipline` int(2) unsigned NOT NULL DEFAULT 0,
    `disdain` int(2) unsigned NOT NULL DEFAULT 0,
    `removed_disdain` int(2) unsigned NOT NULL DEFAULT 0,
    `traders` int(1) unsigned NOT NULL DEFAULT 0,
    `precinct` int(1) unsigned NOT NULL DEFAULT 0,
    `market` int(1) unsigned NOT NULL DEFAULT 0,
    `market_1` int(1) unsigned NOT NULL DEFAULT 0,
    `market_2` int(1) unsigned NOT NULL DEFAULT 0,
    `market_3` int(1) unsigned NOT NULL DEFAULT 0,
    `market_4` int(1) unsigned NOT NULL DEFAULT 0,
    `market_5` int(1) unsigned NOT NULL DEFAULT 0,
    `market_6` int(1) unsigned NOT NULL DEFAULT 0,
    `market_7` int(1) unsigned NOT NULL DEFAULT 0,
    `market_8` int(1) unsigned NOT NULL DEFAULT 0,
    `performers` int(1) unsigned NOT NULL DEFAULT 0,
    `theatre` int(1) unsigned NOT NULL DEFAULT 0,
    `theatre_1` int(1) unsigned NOT NULL DEFAULT 0,
    `theatre_2` int(1) unsigned NOT NULL DEFAULT 0,
    `theatre_3` int(1) unsigned NOT NULL DEFAULT 0,
    `theatre_4` int(1) unsigned NOT NULL DEFAULT 0,
    `theatre_5` int(1) unsigned NOT NULL DEFAULT 0,
    `theatre_6` int(1) unsigned NOT NULL DEFAULT 0,
    `gladiatorious` int(1) unsigned NOT NULL DEFAULT 0,
    `red_training` int(1) unsigned NOT NULL DEFAULT 0,
    `red_damage` int(1) unsigned NOT NULL DEFAULT 0,
    `blue_training` int(1) unsigned NOT NULL DEFAULT 0,
    `blue_damage` int(1) unsigned NOT NULL DEFAULT 0,
    `red_combat` int(1) unsigned NOT NULL DEFAULT 0,
    `red_combat_1` int(1) unsigned NOT NULL DEFAULT 0,
    `red_combat_2` int(1) unsigned NOT NULL DEFAULT 0,
    `red_combat_3` int(1) unsigned NOT NULL DEFAULT 0,
    `red_combat_4` int(1) unsigned NOT NULL DEFAULT 0,
    `red_combat_5` int(1) unsigned NOT NULL DEFAULT 0,
    `red_combat_6` int(1) unsigned NOT NULL DEFAULT 0,
    `blue_combat` int(1) unsigned NOT NULL DEFAULT 0,
    `blue_combat_1` int(1) unsigned NOT NULL DEFAULT 0,
    `blue_combat_2` int(1) unsigned NOT NULL DEFAULT 0,
    `blue_combat_3` int(1) unsigned NOT NULL DEFAULT 0,
    `blue_combat_4` int(1) unsigned NOT NULL DEFAULT 0,
    `blue_combat_5` int(1) unsigned NOT NULL DEFAULT 0,
    `blue_combat_6` int(1) unsigned NOT NULL DEFAULT 0,
    `priests` int(1) unsigned NOT NULL DEFAULT 0,
    `gardens` int(1) unsigned NOT NULL DEFAULT 0,
    `temple` int(1) unsigned NOT NULL DEFAULT 0,
    `small_temple` int(1) unsigned NOT NULL DEFAULT 0,
    `medium_temple` int(1) unsigned NOT NULL DEFAULT 0,
    `large_temple` int(1) unsigned NOT NULL DEFAULT 0,
    `apparitores` int(1) unsigned NOT NULL DEFAULT 0,
    `baths` int(1) unsigned NOT NULL DEFAULT 0,
    `baths_1` int(1) unsigned NOT NULL DEFAULT 0,
    `baths_2` int(1) unsigned NOT NULL DEFAULT 0,
    `baths_3` int(1) unsigned NOT NULL DEFAULT 0,
    `baths_4` int(1) unsigned NOT NULL DEFAULT 0,
    `baths_5` int(1) unsigned NOT NULL DEFAULT 0,
    `baths_6` int(1) unsigned NOT NULL DEFAULT 0,
    `courthouse` int(1) unsigned NOT NULL DEFAULT 0,
    `courthouse_c1_1` int(1) unsigned NOT NULL DEFAULT 0,
    `courthouse_c1_2` int(1) unsigned NOT NULL DEFAULT 0,
    `courthouse_c1_3` int(1) unsigned NOT NULL DEFAULT 0,
    `courthouse_c2_1` int(1) unsigned NOT NULL DEFAULT 0,
    `courthouse_c2_2` int(1) unsigned NOT NULL DEFAULT 0,
    `courthouse_c2_3` int(1) unsigned NOT NULL DEFAULT 0,
    `courthouse_c3_1` int(1) unsigned NOT NULL DEFAULT 0,
    `courthouse_c3_2` int(1) unsigned NOT NULL DEFAULT 0,
    `courthouse_c3_3` int(1) unsigned NOT NULL DEFAULT 0,
    `patricians` int(1) unsigned NOT NULL DEFAULT 0,
    `diplomat` int(1) unsigned NOT NULL DEFAULT 0,
    `diplomat_1_direction` varchar(6) NOT NULL DEFAULT 0,
    `diplomat_1_favor` int(1) unsigned NOT NULL DEFAULT 0,
    `diplomat_2_direction` varchar(6) NOT NULL DEFAULT 'none',
    `diplomat_2_favor` int(1) unsigned NOT NULL DEFAULT 0,
    `diplomat_3_direction` varchar(6) NOT NULL DEFAULT 'none',
    `diplomat_3_favor` int(1) unsigned NOT NULL DEFAULT 0,
    `scout` int(1) unsigned NOT NULL DEFAULT 0,
    `map` varchar(5) NOT NULL DEFAULT '00000',
    PRIMARY KEY (`player_id`,`round`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


-- CREATE TABLE IF NOT EXISTS 'board' (
--     'player_id' int(10) NOT NULL,
--     'round' int(1) NOT NULL DEFAULT 0, -- 0~6
--     'left_cohort' int(1) unsigned NOT NULL DEFAULT 0, -- 0~6
--     'center_cohort' int(1) unsigned NOT NULL DEFAULT 0, -- 0~6
--     'right_cohort' int(1) unsigned NOT NULL DEFAULT 0, -- 0~6
--     'mining_and_foresting' int(2) unsigned NOT NULL DEFAULT 0, -- 0~14
--     'wall_guard' int(2) unsigned NOT NULL DEFAULT 0, -- 0~18
--     'cippi' int(1) unsigned NOT NULL DEFAULT 0, -- 0~7
--     'wall' int(2) unsigned NOT NULL DEFAULT 0, -- 0~21
--     'fort' int(2) unsigned NOT NULL DEFAULT 0, -- 0~21
--     'resource_production' int(1) unsigned NOT NULL DEFAULT 1, -- 1~9

--     'training_grounds' int(1) unsigned NOT NULL DEFAULT 0, -- 0~5
--     'training_grounds_1' int(1) unsigned NOT NULL DEFAULT 0, -- 1~6
--     'training_grounds_2' int(1) unsigned NOT NULL DEFAULT 0, -- 1~6
--     'training_grounds_3' int(1) unsigned NOT NULL DEFAULT 0, -- 1~6
--     'training_grounds_4' int(1) unsigned NOT NULL DEFAULT 0, -- 1~6
--     'training_grounds_5' int(1) unsigned NOT NULL DEFAULT 0, -- 1~6

--     'hotel' int(1) unsigned NOT NULL DEFAULT 0, -- 0~2
--     'workshop' int(1) unsigned NOT NULL DEFAULT 0, -- 0~2
--     'road' int(1) unsigned NOT NULL DEFAULT 0, -- 0~2

--     'forum' int(1) unsigned NOT NULL DEFAULT 0, -- 0~4
--     'forum_1' int(1) unsigned NOT NULL DEFAULT 0, -- 0~4
--     'forum_2' int(1) unsigned NOT NULL DEFAULT 0, -- 0~4
--     'forum_3' int(1) unsigned NOT NULL DEFAULT 0, -- 0~4
--     'forum_4' int(1) unsigned NOT NULL DEFAULT 0, -- 0~4
    
--     'archway' int(1) unsigned NOT NULL DEFAULT 0, -- 0~1
--     'monolith' int(1) unsigned NOT NULL DEFAULT 0, -- 0~1
--     'column' int(1) unsigned NOT NULL DEFAULT 0, -- 0~1
--     'statue' int(1) unsigned NOT NULL DEFAULT 0, -- 0~1

--     'renown' int(2) unsigned NOT NULL DEFAULT 0, -- 0~25
--     'piety' int(2) unsigned NOT NULL DEFAULT 0, -- 0~25
--     'valour' int(2) unsigned NOT NULL DEFAULT 0, -- 0~25
--     'discipline' int(2) unsigned NOT NULL DEFAULT 0, -- 0~25

--     'disdain' int(2) unsigned NOT NULL DEFAULT 0, -- 0~15
--     'removed_disdain' int(2) unsigned NOT NULL DEFAULT 0, -- 0~15

--     'traders' int(1) unsigned NOT NULL DEFAULT 0, -- 0~9
--     'precinct' int(1) unsigned NOT NULL DEFAULT 0, -- 0~3
--     'market' int(1) unsigned NOT NULL DEFAULT 0, -- 0~1
--     'market_1' int(1) unsigned NOT NULL DEFAULT 0, -- 0~6
--     'market_2' int(1) unsigned NOT NULL DEFAULT 0, -- 0~6
--     'market_3' int(1) unsigned NOT NULL DEFAULT 0, -- 0~6
--     'market_4' int(1) unsigned NOT NULL DEFAULT 0, -- 0~6
--     'market_5' int(1) unsigned NOT NULL DEFAULT 0, -- 0~6
--     'market_6' int(1) unsigned NOT NULL DEFAULT 0, -- 0~6
--     'market_7' int(1) unsigned NOT NULL DEFAULT 0, -- 0~6
--     'market_8' int(1) unsigned NOT NULL DEFAULT 0, -- 0~6

--     'performers' int(1) unsigned NOT NULL DEFAULT 0, -- 0~9
--     'theatre' int(1) unsigned NOT NULL DEFAULT 0, -- 0~1
--     'theatre_1' int(1) unsigned NOT NULL DEFAULT 0, -- 0~6
--     'theatre_2' int(1) unsigned NOT NULL DEFAULT 0, -- 0~6
--     'theatre_3' int(1) unsigned NOT NULL DEFAULT 0, -- 0~6
--     'theatre_4' int(1) unsigned NOT NULL DEFAULT 0, -- 0~6
--     'theatre_5' int(1) unsigned NOT NULL DEFAULT 0, -- 0~6
--     'theatre_6' int(1) unsigned NOT NULL DEFAULT 0, -- 0~6
--     'gladiatorious' int(1) unsigned NOT NULL DEFAULT 0, -- 0~1
--     'red_training' int(1) unsigned NOT NULL DEFAULT 0, -- 0~6
--     'red_damage' int(1) unsigned NOT NULL DEFAULT 0, -- 0~6
--     'blue_training' int(1) unsigned NOT NULL DEFAULT 0, -- 0~6
--     'blue_damage' int(1) unsigned NOT NULL DEFAULT 0, -- 0~6    
--     'red_combat' int(1) unsigned NOT NULL DEFAULT 0, -- 0~6
--     'red_combat_1' int(1) unsigned NOT NULL DEFAULT 0, -- 0~6
--     'red_combat_2' int(1) unsigned NOT NULL DEFAULT 0, -- 0~6
--     'red_combat_3' int(1) unsigned NOT NULL DEFAULT 0, -- 0~6
--     'red_combat_4' int(1) unsigned NOT NULL DEFAULT 0, -- 0~6
--     'red_combat_5' int(1) unsigned NOT NULL DEFAULT 0, -- 0~6
--     'red_combat_6' int(1) unsigned NOT NULL DEFAULT 0, -- 0~6
--     'blue_combat' int(1) unsigned NOT NULL DEFAULT 0, -- 0~6
--     'blue_combat_1' int(1) unsigned NOT NULL DEFAULT 0, -- 0~6
--     'blue_combat_2' int(1) unsigned NOT NULL DEFAULT 0, -- 0~6
--     'blue_combat_3' int(1) unsigned NOT NULL DEFAULT 0, -- 0~6
--     'blue_combat_4' int(1) unsigned NOT NULL DEFAULT 0, -- 0~6
--     'blue_combat_5' int(1) unsigned NOT NULL DEFAULT 0, -- 0~6
--     'blue_combat_6' int(1) unsigned NOT NULL DEFAULT 0, -- 0~6

--     'priests' int(1) unsigned NOT NULL DEFAULT 0, -- 0~9
--     'gardens' int(1) unsigned NOT NULL DEFAULT 0, -- 0~2
--     'temple' int(1) unsigned NOT NULL DEFAULT 0, -- 0~3
--     'small_temple' int(1) unsigned NOT NULL DEFAULT 0, -- 0~2
--     'medium_temple' int(1) unsigned NOT NULL DEFAULT 0, -- 0~6
--     'large_temple' int(1) unsigned NOT NULL DEFAULT 0, -- 0~6

--     'apparitores' int(1) unsigned NOT NULL DEFAULT 0, -- 0~9
--     'baths' int(1) unsigned NOT NULL DEFAULT 0, -- 0~1
--     'baths_1' int(1) unsigned NOT NULL DEFAULT 0, -- 0~6
--     'baths_2' int(1) unsigned NOT NULL DEFAULT 0, -- 0~6
--     'baths_3' int(1) unsigned NOT NULL DEFAULT 0, -- 0~6
--     'baths_4' int(1) unsigned NOT NULL DEFAULT 0, -- 0~6
--     'baths_5' int(1) unsigned NOT NULL DEFAULT 0, -- 0~6
--     'baths_6' int(1) unsigned NOT NULL DEFAULT 0, -- 0~6
--     'courthouse' int(1) unsigned NOT NULL DEFAULT 0, --0~1
--     'courthouse_c1_1' int(1) unsigned NOT NULL DEFAULT 0, --0~6
--     'courthouse_c1_2' int(1) unsigned NOT NULL DEFAULT 0, --0~6
--     'courthouse_c1_3' int(1) unsigned NOT NULL DEFAULT 0, --0~6
--     'courthouse_c2_1' int(1) unsigned NOT NULL DEFAULT 0, --0~6
--     'courthouse_c2_2' int(1) unsigned NOT NULL DEFAULT 0, --0~6
--     'courthouse_c2_3' int(1) unsigned NOT NULL DEFAULT 0, --0~6
--     'courthouse_c3_1' int(1) unsigned NOT NULL DEFAULT 0, --0~6
--     'courthouse_c3_2' int(1) unsigned NOT NULL DEFAULT 0, --0~6
--     'courthouse_c3_3' int(1) unsigned NOT NULL DEFAULT 0, --0~6

--     'patricians' int(1) unsigned NOT NULL DEFAULT 0, -- 0~9
--     'diplomat' int(1) unsigned NOT NULL DEFAULT 0, -- 0~1
--     'diplomat_1_direction' varchar(6) unsigned NOT NULL DEFAULT 0, -- left, right, center
--     'diplomat_1_favor' int(1) unsigned NOT NULL DEFAULT 0, -- 0~2
--     'diplomat_2_direction' varchar(6) NOT NULL DEFAULT 'none', -- none, left, right, center
--     'diplomat_2_favor' int(1) unsigned NOT NULL DEFAULT 0, -- 0~2
--     'diplomat_3_direction' varchar(6) unsigned NOT NULL DEFAULT 0, -- left, right, center
--     'diplomat_3_favor' int(1) unsigned NOT NULL DEFAULT 0, -- 0~2
--     'scout' int(1) unsigned NOT NULL DEFAULT 0, -- 0~5
--     'map' varchar(5) NOT NULL DEFAULT '00000', -- 00000~FFFFF, bit map of checked boxes
--     PRIMARY KEY ('player_id','round')
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
