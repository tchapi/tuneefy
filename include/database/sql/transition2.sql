-- phpMyAdmin SQL Dump
-- version 3.3.7deb5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 01, 2011 at 01:37 PM
-- Server version: 5.1.49
-- PHP Version: 5.3.3-7+squeeze1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `tuneefy_****`
--
START TRANSACTION;

RENAME TABLE  `tracks` TO  `items` ;
RENAME TABLE  `stats_tracks` TO  `stats_items` ;

ALTER TABLE  `items` ADD  `type` INT NOT NULL AFTER  `id`;

SET foreign_key_checks = 0;

ALTER TABLE  `stats_platforms` DROP FOREIGN KEY  `stats_platforms_ibfk_1` ;
ALTER TABLE  `stats_platforms` CHANGE  `track_id`  `item_id` BIGINT( 20 ) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE  `stats_platforms` ADD FOREIGN KEY (  `item_id` ) REFERENCES  `items` (`id`);

ALTER TABLE  `stats_items` DROP FOREIGN KEY  `stats_items_ibfk_1` ;
ALTER TABLE  `stats_items` CHANGE  `track_id`  `item_id` BIGINT( 20 ) UNSIGNED NULL DEFAULT NULL;
ALTER TABLE  `stats_items` ADD FOREIGN KEY (  `item_id` ) REFERENCES  `items` (`id`);

SET foreign_key_checks = 1;

COMMIT;