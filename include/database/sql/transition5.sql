
-- Beats Music

INSERT INTO `platforms` (`id`, `name`) VALUES
(15, 'Beats Music');

ALTER TABLE `items` ADD `link_BEATS` VARCHAR( 256 ) NULL DEFAULT NULL AFTER `link_XBOX`;
