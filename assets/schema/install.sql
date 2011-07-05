-- phpMyAdmin SQL Dump
-- version 3.3.10
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 01, 2011 at 02:20 PM
-- Server version: 5.1.44
-- PHP Version: 5.3.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `app_kms`
--

-- --------------------------------------------------------

--
-- Table structure for table `actions`
--

DROP TABLE IF EXISTS `actions`;
CREATE TABLE `actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `system` tinyint(1) NOT NULL DEFAULT '1',
  `open` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(80) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `data` (`system`,`name`,`open`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

--
-- Dumping data for table `actions`
--

INSERT INTO `actions` (`id`, `system`, `open`, `name`, `description`) VALUES
(1, 1, 1, 'create_account', 'This action occurs when a user creates an account.'),
(2, 1, 0, 'role_change', 'A users role was changed'),
(3, 1, 1, 'login', 'User logged into their account.'),
(4, 1, 1, 'logout', 'User logged out of their account.'),
(5, 1, 0, 'content_add', 'User added content'),
(6, 1, 0, 'content_edit', 'User edited content'),
(7, 1, 0, 'content_delete', 'User deleted content'),
(8, 1, 0, 'resource_enable', 'User enabled a site snippet or chunk'),
(9, 1, 0, 'resource_disable', 'User disabled a site snippet or chunk'),
(10, 1, 0, 'variable_edit', 'User edited a site variable'),
(11, 1, 0, 'variable_delete', 'User deleted a site variable'),
(12, 1, 0, 'variable_create', 'User created a site variable'),
(13, 1, 0, 'template_activate', 'User activated a site template.'),
(14, 1, 0, 'template_edit', 'User edited a site template.'),
(15, 1, 0, 'template_delete', 'User deleted a site template.'),
(16, 1, 0, 'template_add', 'User added a site template.'),
(17, 1, 0, 'list_view_edit', 'User edited an item in a custom list.'),
(18, 1, 0, 'list_view_delete', 'User deleted an item in a custom list.'),
(19, 1, 0, 'list_view_add', 'User added an item in a custom list.'),
(20, 1, 0, 'list_delete', 'User deleted a custom list.'),
(21, 1, 0, 'list_add', 'User adds a custom list.'),
(22, 1, 0, 'profile_edit', 'User edited the profile of their account.'),
(23, 1, 0, 'user_add', 'Allows user to add users'),
(24, 1, 0, 'user_edit', 'Allows user to edit users'),
(25, 1, 0, 'user_delete', 'Allows user to delete users');

-- --------------------------------------------------------

--
-- Table structure for table `action_roles`
--

DROP TABLE IF EXISTS `action_roles`;
CREATE TABLE `action_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;

--
-- Dumping data for table `action_roles`
--

INSERT INTO `action_roles` (`id`, `action_id`, `role_id`) VALUES
(1, 2, 2),
(2, 5, 2),
(3, 6, 2),
(4, 7, 2),
(5, 8, 2),
(6, 9, 2),
(7, 10, 2),
(8, 11, 2),
(9, 12, 2),
(10, 13, 2),
(11, 14, 2),
(12, 15, 2),
(13, 16, 2),
(14, 17, 2),
(15, 18, 2),
(16, 19, 2),
(17, 20, 2),
(18, 21, 2),
(19, 1, 2),
(20, 3, 2),
(21, 4, 2),
(22, 22, 2),
(27, 22, 1),
(28, 23, 2),
(29, 24, 2),
(30, 25, 2);

-- --------------------------------------------------------

--
-- Table structure for table `action_users`
--

DROP TABLE IF EXISTS `action_users`;
CREATE TABLE `action_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_id` int(11) DEFAULT NULL,
  `name` varchar(70) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_name` (`site_id`,`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `site_id`, `name`, `description`) VALUES
(1, NULL, 'User', 'Default user account. Can login/logout'),
(2, NULL, 'Administrator', 'Administrator of a site');

-- --------------------------------------------------------

--
-- Table structure for table `sites`
--

DROP TABLE IF EXISTS `sites`;
CREATE TABLE `sites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain` varchar(60) NOT NULL,
  `description` text NOT NULL,
  `created` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`domain`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `site_contents`
--

DROP TABLE IF EXISTS `site_contents`;
CREATE TABLE `site_contents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_id` int(11) NOT NULL,
  `uri` varchar(120) NOT NULL,
  `title` varchar(120) NOT NULL,
  `body` longtext NOT NULL,
  `mime_type` varchar(40) NOT NULL,
  `meta_keywords` varchar(160) NOT NULL,
  `meta_description` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uri` (`site_id`,`uri`),
  KEY `site_id` (`site_id`,`mime_type`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `site_lists`
--

DROP TABLE IF EXISTS `site_lists`;
CREATE TABLE `site_lists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `records` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_name` (`site_id`,`name`),
  KEY `site_id` (`site_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `site_routes`
--

DROP TABLE IF EXISTS `site_routes`;
CREATE TABLE `site_routes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_id` int(11) NOT NULL,
  `name` varchar(80) NOT NULL,
  `route` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `site_id_2` (`site_id`,`name`),
  KEY `site_id` (`site_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `site_routes`
--

INSERT INTO `site_routes` (`id`, `site_id`, `name`, `route`) VALUES
(1, 1, 'default', '(<path>)');

-- --------------------------------------------------------

--
-- Table structure for table `site_route_defaults`
--

DROP TABLE IF EXISTS `site_route_defaults`;
CREATE TABLE `site_route_defaults` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_route_id` int(11) NOT NULL,
  `key` varchar(120) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `site_route_id_2` (`site_route_id`,`key`),
  KEY `site_route_id` (`site_route_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `site_route_defaults`
--

INSERT INTO `site_route_defaults` (`id`, `site_route_id`, `key`, `value`) VALUES
(1, 1, 'controller', 'kms'),
(2, 1, 'action', 'index');

-- --------------------------------------------------------

--
-- Table structure for table `site_route_regexps`
--

DROP TABLE IF EXISTS `site_route_regexps`;
CREATE TABLE `site_route_regexps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_route_id` int(11) NOT NULL,
  `key` varchar(120) NOT NULL,
  `regexp` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `site_route_id` (`site_route_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `site_route_regexps`
--

INSERT INTO `site_route_regexps` (`id`, `site_route_id`, `key`, `regexp`) VALUES
(1, 1, 'path', '.*');

-- --------------------------------------------------------

--
-- Table structure for table `site_snippets`
--

DROP TABLE IF EXISTS `site_snippets`;
CREATE TABLE `site_snippets` (
  `site_id` int(11) NOT NULL,
  `snippet_id` int(11) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '0',
  UNIQUE KEY `site_id` (`site_id`,`snippet_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `site_templates`
--

DROP TABLE IF EXISTS `site_templates`;
CREATE TABLE `site_templates` (
  `site_id` int(11) NOT NULL,
  `template_id` int(11) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '0',
  UNIQUE KEY `unique_template` (`site_id`,`template_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `site_users`
--

DROP TABLE IF EXISTS `site_users`;
CREATE TABLE `site_users` (
  `site_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  UNIQUE KEY `site_id` (`site_id`,`user_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `site_variables`
--

DROP TABLE IF EXISTS `site_variables`;
CREATE TABLE `site_variables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_id` int(11) NOT NULL,
  `name` varchar(120) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `variable_name` (`site_id`,`name`),
  KEY `site_id` (`site_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `snippets`
--

DROP TABLE IF EXISTS `snippets`;
CREATE TABLE `snippets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(80) NOT NULL,
  `description` text NOT NULL,
  `body` longtext NOT NULL,
  `eval` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `templates`
--

DROP TABLE IF EXISTS `templates`;
CREATE TABLE `templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL,
  `body` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) NOT NULL,
  `password` varchar(40) NOT NULL,
  `first_name` varchar(80) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `super` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_actions`
--

DROP TABLE IF EXISTS `user_actions`;
CREATE TABLE `user_actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action_id` int(11) NOT NULL,
  `details` longtext NOT NULL,
  `identifier` varchar(120) NOT NULL,
  `created` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`action_id`),
  KEY `site_id` (`site_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_tokens`
--

DROP TABLE IF EXISTS `user_tokens`;
CREATE TABLE `user_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `token` varchar(40) NOT NULL,
  `expires` int(10) NOT NULL,
  `created` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Used for cookie auth' AUTO_INCREMENT=1 ;