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

-- --------------------------------------------------------

--
-- Table structure for table `platforms`
--

CREATE TABLE IF NOT EXISTS `platforms` (
  `id` tinyint(4) NOT NULL,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `platforms`
--

INSERT INTO `platforms` (`id`, `name`) VALUES
(0, 'Deezer'),
(1, 'Spotify'),
(2, 'Lastfm'),
(3, 'Grooveshark'),
(4, 'Jiwa'),
(5, 'Soundcloud'),
(6, 'Hypemachine'),
(7, 'Youtube'),
(8, 'Mixcloud'),
(9, 'MOG'),
(10, 'Rdio'),
(11, 'iTunes'),
(12, 'Echonest'),
(13, 'Qobuz');
--
-- Table structure for table `tracks`
--

CREATE TABLE IF NOT EXISTS `items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `artist` varchar(256) NOT NULL,
  `album` varchar(256) DEFAULT NULL,
  `image` varchar(256) DEFAULT NULL,
  `link_DEEZER` varchar(256) DEFAULT NULL,
  `link_SPOTIFY` varchar(256) DEFAULT NULL,
  `link_LASTFM` varchar(256) DEFAULT NULL,
  `link_GROOVESHARK` varchar(256) DEFAULT NULL,
  `link_JIWA` varchar(256) DEFAULT NULL,
  `link_SOUNDCLOUD` varchar(256) DEFAULT NULL,
  `link_HYPEMACHINE` varchar(256) DEFAULT NULL,
  `link_YOUTUBE` varchar(256) DEFAULT NULL,
  `link_MIXCLOUD` varchar(256) DEFAULT NULL,
  `link_MOG` varchar(256) DEFAULT NULL,
  `link_RDIO` varchar(256) DEFAULT NULL,
  `link_ITUNES` varchar(256) DEFAULT NULL,
  `link_ECHONEST` varchar(256) DEFAULT NULL,
  `link_QOBUZ` varchar(256) DEFAULT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;



--
-- Table structure for table `stats_platforms`
--

CREATE TABLE IF NOT EXISTS `stats_platforms` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `platform` tinyint(4) NOT NULL,
  `item_id` bigint(20) unsigned DEFAULT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`item_id`) REFERENCES tracks(`id`),
  FOREIGN KEY (`platform`) REFERENCES platforms(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


--
-- Table structure for table `stats_tracks`
--

CREATE TABLE IF NOT EXISTS `stats_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` bigint(20) unsigned DEFAULT NULL,
  `referer` varchar(256) DEFAULT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`item_id`) REFERENCES tracks(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


--
-- Table structure for table `stats_search_query`
--

CREATE TABLE IF NOT EXISTS `stats_search_query` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `query` varchar(256) DEFAULT NULL,
  `referer` VARCHAR( 256 ) NULL DEFAULT NULL AFTER `query`,
  `origin` VARCHAR( 256 ) NULL DEFAULT NULL AFTER `referer`,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Table structure for table `picks`
--

CREATE TABLE IF NOT EXISTS `picks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `artist` varchar(256) NOT NULL,
  `album` varchar(256) DEFAULT NULL,
  `image` varchar(256) DEFAULT NULL,
  `link` varchar(256) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `DATE` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


--
-- Table structure for table `coming_soon_mails`
--

CREATE TABLE IF NOT EXISTS `coming_soon_mails` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `mail` varchar(256) DEFAULT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Table structure for table `api_clients`
--

CREATE TABLE IF NOT EXISTS `api_clients` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `contact` varchar(256) NOT NULL,
  `consumer_key` char(40) NOT NULL,
  `consumer_secret` char(40) NOT NULL,
  `active` BOOLEAN NOT NULL DEFAULT '0',
  `creation_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
