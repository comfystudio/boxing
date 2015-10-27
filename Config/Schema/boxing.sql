-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.6.12-log - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL version:             7.0.0.4053
-- Date/time:                    2014-11-03 11:56:41
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET FOREIGN_KEY_CHECKS=0 */;

-- Dumping database structure for boxing
CREATE DATABASE IF NOT EXISTS `boxing` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `boxing`;


-- Dumping structure for table boxing.belts
CREATE TABLE IF NOT EXISTS `belts` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `position` int(3) DEFAULT '0',
  `boxer_id` int(10) NOT NULL,
  `weight_type` int(10) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table boxing.boxers
CREATE TABLE IF NOT EXISTS `boxers` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `manager_id` int(10) DEFAULT NULL,
  `trainer_id` int(10) DEFAULT NULL,
  `forname_id` int(10) DEFAULT NULL,
  `surname_id` int(10) DEFAULT NULL,
  `retired` int(3) DEFAULT '0',
  `region` int(3) DEFAULT NULL,
  `rank` int(10) DEFAULT NULL,
  `weight_type` int(3) DEFAULT NULL,
  `age` int(3) DEFAULT NULL,
  `fame` int(5) DEFAULT '0',
  `wins` int(3) DEFAULT '0',
  `loses` int(3) DEFAULT '0',
  `draws` int(3) DEFAULT '0',
  `knockouts` int(3) DEFAULT '0',
  `floored` int(3) DEFAULT '0',
  `happiness` int(3) DEFAULT '50',
  `ambition` int(3) DEFAULT NULL,
  `greed` int(3) DEFAULT NULL,
  `aggression` int(3) DEFAULT NULL,
  `discipline` int(3) DEFAULT NULL,
  `dirty` int(3) DEFAULT NULL,
  `lifestyle` int(3) DEFAULT NULL,
  `confidence` int(3) DEFAULT '200',
  `injury_prone` int(3) DEFAULT NULL,
  `injured` tinyint(4) DEFAULT '0',
  `injured_text` text,
  `injured_duration` date DEFAULT NULL,
  `tech` int(3) DEFAULT NULL,
  `power` int(3) DEFAULT NULL,
  `hand_speed` int(3) DEFAULT NULL,
  `foot_speed` int(3) DEFAULT NULL,
  `block` int(3) DEFAULT NULL,
  `defence` int(3) DEFAULT NULL,
  `chin` int(3) DEFAULT NULL,
  `heart` int(3) DEFAULT NULL,
  `cut` int(3) DEFAULT NULL,
  `endurance` int(3) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `boxer_manager_id` (`manager_id`),
  KEY `boxer_trainer_id` (`trainer_id`),
  KEY `boxer_forname_id` (`forname_id`),
  KEY `boxer_surname_id` (`surname_id`),
  CONSTRAINT `boxer_forname_id` FOREIGN KEY (`forname_id`) REFERENCES `names` (`id`),
  CONSTRAINT `boxer_manager_id` FOREIGN KEY (`manager_id`) REFERENCES `managers` (`id`),
  CONSTRAINT `boxer_surname_id` FOREIGN KEY (`surname_id`) REFERENCES `names` (`id`),
  CONSTRAINT `boxer_trainer_id` FOREIGN KEY (`trainer_id`) REFERENCES `trainers` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table boxing.contracts
CREATE TABLE IF NOT EXISTS `contracts` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `manager_id` int(10) DEFAULT NULL,
  `boxer_id` int(10) DEFAULT NULL,
  `trainer_id` int(10) DEFAULT NULL,
  `active` int(3) DEFAULT '0',
  `percentage` int(3) DEFAULT NULL,
  `salary` int(3) DEFAULT NULL,
  `bonus` int(20) DEFAULT NULL,
  `value` int(20) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `contracts_manager_id` (`manager_id`),
  KEY `contracts_boxer_id` (`boxer_id`),
  KEY `contracts_trainer_id` (`trainer_id`),
  CONSTRAINT `contracts_boxer_id` FOREIGN KEY (`boxer_id`) REFERENCES `boxers` (`id`),
  CONSTRAINT `contracts_manager_id` FOREIGN KEY (`manager_id`) REFERENCES `managers` (`id`),
  CONSTRAINT `contracts_trainer_id` FOREIGN KEY (`trainer_id`) REFERENCES `trainers` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table boxing.feeds
CREATE TABLE IF NOT EXISTS `feeds` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `manager_id` int(10) DEFAULT NULL,
  `type` int(2) DEFAULT '0',
  `content` text,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `feed_id` (`manager_id`),
  CONSTRAINT `feed_id` FOREIGN KEY (`manager_id`) REFERENCES `managers` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table boxing.fights
CREATE TABLE IF NOT EXISTS `fights` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `venue_id` int(10) DEFAULT NULL,
  `fighter1_id` int(10) DEFAULT NULL,
  `fighter2_id` int(10) DEFAULT NULL,
  `winner_id` int(10) DEFAULT NULL,
  `manager_id` int(10) DEFAULT NULL,
  `accepted` int(3) DEFAULT '0',
  `fee` int(10) DEFAULT '0',
  `attendence` int(10) DEFAULT NULL,
  `ticket_price` int(10) DEFAULT NULL,
  `overview` text,
  `round1_description` text,
  `fighter1_r1_stats` text,
  `fighter2_r1_stats` text,
  `round2_description` text,
  `fighter1_r2_stats` text,
  `fighter2_r2_stats` text,
  `round3_description` text,
  `fighter1_r3_stats` text,
  `fighter2_r3_stats` text,
  `round4_description` text,
  `fighter1_r4_stats` text,
  `fighter2_r4_stats` text,
  `round5_description` text,
  `fighter1_r5_stats` text,
  `fighter2_r5_stats` text,
  `round6_description` text,
  `fighter1_r6_stats` text,
  `fighter2_r6_stats` text,
  `round7_description` text,
  `fighter1_r7_stats` text,
  `fighter2_r7_stats` text,
  `round8_description` text,
  `fighter1_r8_stats` text,
  `fighter2_r8_stats` text,
  `round9_description` text,
  `fighter1_r9_stats` text,
  `fighter2_r9_stats` text,
  `round10_description` text,
  `fighter1_r10_stats` text,
  `fighter2_r10_stats` text,
  `round11_description` text,
  `fighter1_r11_stats` text,
  `fighter2_r11_stats` text,
  `round12_description` text,
  `fighter1_r12_stats` text,
  `fighter2_r12_stats` text,
  `fighter1_total_stats` text,
  `fighter2_total_stats` text,
  `game_time` date DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fight_venue_id` (`venue_id`),
  KEY `fight_fighter1_id` (`fighter1_id`),
  KEY `fight_fighter2_id` (`fighter2_id`),
  KEY `fight_winner_id` (`winner_id`),
  KEY `fight_manager_id` (`manager_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- Data exporting was unselected.


-- Dumping structure for table boxing.halls
CREATE TABLE IF NOT EXISTS `halls` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `boxer_id` int(10) NOT NULL DEFAULT '0',
  `manager_name` varchar(50) DEFAULT NULL,
  `type` int(10) DEFAULT '0',
  `name` varchar(50) DEFAULT NULL,
  `region` int(3) DEFAULT NULL,
  `rank` int(10) DEFAULT NULL,
  `weight_type` int(3) DEFAULT NULL,
  `fame` int(5) DEFAULT '0',
  `wins` int(3) DEFAULT '0',
  `loses` int(3) DEFAULT '0',
  `draws` int(3) DEFAULT '0',
  `knockouts` int(3) DEFAULT '0',
  `floored` int(3) DEFAULT '0',
  `tech` int(3) DEFAULT '0',
  `power` int(3) DEFAULT '0',
  `hand_speed` int(3) DEFAULT '0',
  `foot_speed` int(3) DEFAULT '0',
  `block` int(3) DEFAULT '0',
  `defence` int(3) DEFAULT '0',
  `chin` int(3) DEFAULT '0',
  `heart` int(3) DEFAULT '0',
  `cut` int(3) DEFAULT '0',
  `endurance` int(3) DEFAULT '0',
  `game_date_start` datetime DEFAULT NULL,
  `game_date_end` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table boxing.items
CREATE TABLE IF NOT EXISTS `items` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `price` int(10) DEFAULT NULL,
  `buff_value` int(10) DEFAULT NULL,
  `buff_stat` varchar(50) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `description` text,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table boxing.managers
CREATE TABLE IF NOT EXISTS `managers` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) DEFAULT NULL,
  `balance` int(20) DEFAULT '25000',
  `belts_held` int(10) DEFAULT '0',
  `career_belts_total` int(10) DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table boxing.manager_items
CREATE TABLE IF NOT EXISTS `manager_items` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `manager_id` int(10) DEFAULT NULL,
  `item_id` int(10) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `items_id` (`manager_id`),
  KEY `manageritems_items_id` (`item_id`),
  CONSTRAINT `manageritems_items_id` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table boxing.names
CREATE TABLE IF NOT EXISTS `names` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) DEFAULT NULL,
  `region` enum('North American','South American','Europe','Middle Eastern','African','Asian') DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table boxing.notifications
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `recipient_id` int(10) DEFAULT NULL,
  `sender_id` int(10) DEFAULT NULL,
  `fight_id` int(10) DEFAULT NULL,
  `title` varchar(250) DEFAULT NULL,
  `text` text,
  `type` int(3) DEFAULT NULL,
  `response` int(3) DEFAULT '0',
  `game_date` date DEFAULT NULL,
  `viewed` tinyint(3) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table boxing.params
CREATE TABLE IF NOT EXISTS `params` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `game_time` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table boxing.trainers
CREATE TABLE IF NOT EXISTS `trainers` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `manager_id` int(10) DEFAULT NULL,
  `forname_id` int(10) DEFAULT NULL,
  `surname_id` int(10) DEFAULT NULL,
  `region` int(3) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `salary` int(10) DEFAULT NULL,
  `scout` int(3) DEFAULT NULL,
  `tech` int(3) DEFAULT NULL,
  `power` int(3) DEFAULT NULL,
  `hand_speed` int(3) DEFAULT NULL,
  `foot_speed` int(3) DEFAULT NULL,
  `block` int(3) DEFAULT NULL,
  `defence` int(3) DEFAULT NULL,
  `chin` int(3) DEFAULT NULL,
  `heart` int(3) DEFAULT NULL,
  `cut` int(3) DEFAULT NULL,
  `endurance` int(3) DEFAULT NULL,
  `corner` int(3) DEFAULT NULL,
  `overall` int(3) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trainer_manager_id` (`manager_id`),
  KEY `trainer_surname_id` (`surname_id`),
  KEY `trainer_forname_id` (`forname_id`),
  CONSTRAINT `trainer_forname_id` FOREIGN KEY (`forname_id`) REFERENCES `names` (`id`),
  CONSTRAINT `trainer_manager_id` FOREIGN KEY (`manager_id`) REFERENCES `managers` (`id`),
  CONSTRAINT `trainer_surname_id` FOREIGN KEY (`surname_id`) REFERENCES `names` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table boxing.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `manager_id` int(10) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `level` tinyint(1) DEFAULT '0',
  `active` tinyint(1) DEFAULT '0',
  `last_login` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `manager_id` (`manager_id`),
  CONSTRAINT `manager_id` FOREIGN KEY (`manager_id`) REFERENCES `managers` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.


-- Dumping structure for table boxing.venues
CREATE TABLE IF NOT EXISTS `venues` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `capacity` int(10) DEFAULT NULL,
  `cost` int(10) DEFAULT NULL,
  `charge` int(10) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
/*!40014 SET FOREIGN_KEY_CHECKS=1 */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
