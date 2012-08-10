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

INSERT INTO `platforms` (`id`, `name`) VALUES
(6, 'Hypemachine'),
(7, 'Youtube'),
(8, 'Mixcloud'),
(9, 'MOG'),
(10, 'Rdio'),
(11, 'iTunes'),
(12, 'Echonest');

ALTER TABLE `tracks` ADD `link_HYPEMACHINE` VARCHAR( 256 ) NULL DEFAULT NULL AFTER `link_JIWA`;
ALTER TABLE `tracks` ADD `link_YOUTUBE` VARCHAR( 256 ) NULL DEFAULT NULL AFTER `link_HYPEMACHINE`;
ALTER TABLE `tracks` ADD `link_MIXCLOUD` VARCHAR( 256 ) NULL DEFAULT NULL AFTER `link_YOUTUBE`;
ALTER TABLE `tracks` ADD `link_MOG` VARCHAR( 256 ) NULL DEFAULT NULL AFTER `link_MIXCLOUD`;
ALTER TABLE `tracks` ADD `link_RDIO` VARCHAR( 256 ) NULL DEFAULT NULL AFTER `link_MOG`;
ALTER TABLE `tracks` ADD `link_ITUNES` VARCHAR( 256 ) NULL DEFAULT NULL AFTER `link_RDIO`;
ALTER TABLE `tracks` ADD `link_ECHONEST` VARCHAR( 256 ) NULL DEFAULT NULL AFTER `link_ITUNES`;