
-- XBOX Music

INSERT INTO `platforms` (`id`, `name`) VALUES
(14, 'Xbox');

ALTER TABLE `items` ADD `link_XBOX` VARCHAR( 256 ) NULL DEFAULT NULL AFTER `link_QOBUZ`;
