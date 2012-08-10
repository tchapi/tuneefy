ALTER TABLE  `stats_search_query` ADD  `referer` VARCHAR( 256 ) NULL DEFAULT NULL AFTER `query`;
ALTER TABLE  `stats_search_query` ADD  `origin` VARCHAR( 256 ) NULL DEFAULT NULL AFTER `referer`;

-- QOBUZ

INSERT INTO `platforms` (`id`, `name`) VALUES
(13, 'Qobuz');

ALTER TABLE `items` ADD `link_QOBUZ` VARCHAR( 256 ) NULL DEFAULT NULL AFTER `link_ECHONEST`;

-- API 

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