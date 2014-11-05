-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 04, 2014 at 08:25 PM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `webshop`
--

-- --------------------------------------------------------

--
-- Table structure for table `configurationparameter`
--

CREATE TABLE IF NOT EXISTS `configurationparameter` (
  `id` char(36) NOT NULL,
  `website` char(36) NOT NULL,
  `scope` varchar(50) NOT NULL,
  `scopevalue` char(36) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `createdby` char(36) DEFAULT NULL,
  `modified` datetime NOT NULL,
  `modifiedby` char(36) DEFAULT NULL,
  `deleted` datetime DEFAULT NULL,
  `deletedby` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cookie`
--

CREATE TABLE IF NOT EXISTS `cookie` (
  `id` char(36) NOT NULL,
  `website` char(36) NOT NULL,
  `identifier` char(36) NOT NULL,
  `name` varchar(50) NOT NULL,
  `value` varchar(255) NOT NULL,
  `expiration` datetime DEFAULT NULL,
  `created` datetime NOT NULL,
  `createdby` char(36) DEFAULT NULL,
  `modified` datetime NOT NULL,
  `modifiedby` char(36) DEFAULT NULL,
  `deleted` datetime DEFAULT NULL,
  `deletedby` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `eventhandler`
--

CREATE TABLE IF NOT EXISTS `eventhandler` (
  `id` char(36) NOT NULL,
  `website` char(36) NOT NULL,
  `name` varchar(80) NOT NULL,
  `event` varchar(80) NOT NULL,
  `handler` varchar(80) NOT NULL,
  `position` varchar(90) NOT NULL DEFAULT 'LAST',
  `created` datetime NOT NULL,
  `createdby` char(36) DEFAULT NULL,
  `modified` datetime NOT NULL,
  `modifiedby` char(36) DEFAULT NULL,
  `deleted` datetime DEFAULT NULL,
  `deletedby` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `module`
--

CREATE TABLE IF NOT EXISTS `module` (
  `id` char(36) NOT NULL,
  `website` char(36) NOT NULL,
  `name` varchar(50) NOT NULL,
  `location` enum('CORE','GLOBAL','LOCAL') NOT NULL,
  `status` enum('ACTIVE','DEACTIVATED') NOT NULL,
  `created` datetime NOT NULL,
  `createdby` char(36) DEFAULT NULL,
  `modified` datetime NOT NULL,
  `modifiedby` char(36) DEFAULT NULL,
  `deleted` datetime DEFAULT NULL,
  `deletedby` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `permission`
--

CREATE TABLE IF NOT EXISTS `permission` (
  `id` char(36) NOT NULL,
  `website` char(36) NOT NULL,
  `usergroup` char(36) NOT NULL,
  `name` varchar(80) NOT NULL,
  `value` enum('GRANTED','DENIED') NOT NULL,
  `created` datetime NOT NULL,
  `createdby` char(36) DEFAULT NULL,
  `modified` datetime NOT NULL,
  `modifiedby` char(36) DEFAULT NULL,
  `deleted` datetime DEFAULT NULL,
  `deletedby` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `template`
--

CREATE TABLE IF NOT EXISTS `template` (
  `id` char(36) NOT NULL,
  `website` char(36) NOT NULL,
  `name` varchar(50) NOT NULL,
  `title` varchar(80) NOT NULL,
  `type` enum('DESIGN','VIEW','SUBTEMPLATE') NOT NULL,
  `created` datetime NOT NULL,
  `createdby` char(36) DEFAULT NULL,
  `modified` datetime NOT NULL,
  `modifiedby` char(36) DEFAULT NULL,
  `deleted` datetime DEFAULT NULL,
  `deletedby` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `urihandler`
--

CREATE TABLE IF NOT EXISTS `urihandler` (
  `id` char(36) NOT NULL,
  `website` char(36) NOT NULL,
  `name` varchar(80) NOT NULL,
  `pattern` varchar(255) NOT NULL,
  `method` enum('POST','GET','PUT','DELETE') NOT NULL DEFAULT 'GET',
  `handler` varchar(80) NOT NULL,
  `position` varchar(90) NOT NULL DEFAULT 'LAST',
  `created` datetime NOT NULL,
  `createdby` char(36) DEFAULT NULL,
  `modified` datetime NOT NULL,
  `modifiedby` char(36) DEFAULT NULL,
  `deleted` datetime DEFAULT NULL,
  `deletedby` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `urihandlerparameter`
--

CREATE TABLE IF NOT EXISTS `urihandlerparameter` (
  `id` char(36) NOT NULL,
  `website` char(36) NOT NULL,
  `urihandler` char(36) NOT NULL,
  `name` varchar(50) NOT NULL,
  `pattern` varchar(50) NOT NULL,
  `created` datetime NOT NULL,
  `createdby` char(36) DEFAULT NULL,
  `modified` datetime NOT NULL,
  `modifiedby` char(36) DEFAULT NULL,
  `deleted` datetime DEFAULT NULL,
  `deletedby` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` char(36) NOT NULL,
  `website` char(36) NOT NULL,
  `loginname` varchar(50) NOT NULL,
  `password` char(32) NOT NULL,
  `passwordsalt` char(32) NOT NULL,
  `lastpasswordchange` datetime NOT NULL,
  `emailaddress` varchar(255) NOT NULL,
  `usergroup` char(36) NOT NULL,
  `created` datetime NOT NULL,
  `createdby` char(36) DEFAULT NULL,
  `modified` datetime NOT NULL,
  `modifiedby` char(36) DEFAULT NULL,
  `deleted` datetime DEFAULT NULL,
  `deletedby` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `usergroup`
--

CREATE TABLE IF NOT EXISTS `usergroup` (
  `id` char(36) NOT NULL,
  `website` char(36) NOT NULL,
  `name` varchar(50) NOT NULL,
  `created` datetime NOT NULL,
  `createdby` char(36) DEFAULT NULL,
  `modified` datetime NOT NULL,
  `modifiedby` char(36) DEFAULT NULL,
  `deleted` datetime DEFAULT NULL,
  `deletedby` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `website`
--

CREATE TABLE IF NOT EXISTS `website` (
  `id` char(36) NOT NULL,
  `hostname` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `createdby` char(36) DEFAULT NULL,
  `modified` datetime NOT NULL,
  `modifiedby` char(36) DEFAULT NULL,
  `deleted` datetime DEFAULT NULL,
  `deletedby` char(36) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `configurationparameter`
--
ALTER TABLE `configurationparameter`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `website` (`website`,`scope`,`scopevalue`,`name`,`deleted`);

--
-- Indexes for table `cookie`
--
ALTER TABLE `cookie`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `identifier` (`identifier`,`name`,`deleted`);

--
-- Indexes for table `eventhandler`
--
ALTER TABLE `eventhandler`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `website` (`website`,`name`,`deleted`);

--
-- Indexes for table `module`
--
ALTER TABLE `module`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `website` (`website`,`name`,`deleted`);

--
-- Indexes for table `permission`
--
ALTER TABLE `permission`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `website` (`website`,`usergroup`,`name`,`deleted`);

--
-- Indexes for table `template`
--
ALTER TABLE `template`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `website` (`website`,`name`,`deleted`);

--
-- Indexes for table `urihandler`
--
ALTER TABLE `urihandler`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `website` (`website`,`name`,`deleted`), ADD UNIQUE KEY `website_2` (`website`,`pattern`,`method`,`deleted`);

--
-- Indexes for table `urihandlerparameter`
--
ALTER TABLE `urihandlerparameter`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `website` (`website`,`urihandler`,`name`,`deleted`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `website` (`website`,`loginname`,`deleted`), ADD UNIQUE KEY `website_2` (`website`,`emailaddress`,`deleted`);

--
-- Indexes for table `usergroup`
--
ALTER TABLE `usergroup`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `website` (`website`,`name`,`deleted`);

--
-- Indexes for table `website`
--
ALTER TABLE `website`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `hostname` (`hostname`,`deleted`);
