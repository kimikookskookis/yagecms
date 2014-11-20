-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 20, 2014 at 08:44 PM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `webshop`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `getNewID`(
	IN  iTable VARCHAR(25),
	IN  iGUID CHAR(36),
	OUT oNewID INT UNSIGNED,
	OUT oResult TINYINT(2)
)
BEGIN
	DECLARE mMaxID INT UNSIGNED DEFAULT 1;
	
	SELECT MAX(value)+1 INTO mMaxID
	FROM sequence
	WHERE tablename = iTable;
	
	IF mMaxID IS NULL THEN
		SET mMaxID = 1;
	END IF;
	
	-- Increase sequence
	INSERT INTO sequence (tablename, value) VALUES (iTable, mMaxID);

	WHILE oNewID IS NULL DO
		-- Try to reserve the ID
		UPDATE sequence SET reserved = iGUID WHERE tablename = iTable AND reserved IS NULL;
		
		SELECT value INTO oNewID
		FROM sequence
		WHERE
				reserved = iGUID
			AND tablename = iTable;
		
	END WHILE;
	
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `configurationgroup`
--

CREATE TABLE IF NOT EXISTS `configurationgroup` (
  `id` int(10) unsigned NOT NULL,
  `website` int(10) unsigned NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `created` datetime NOT NULL,
  `createdby` int(10) unsigned NOT NULL,
  `modified` datetime NOT NULL,
  `modifiedby` int(10) unsigned NOT NULL,
  `deleted` datetime NOT NULL,
  `deletedby` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `configurationitem`
--

CREATE TABLE IF NOT EXISTS `configurationitem` (
  `id` int(10) unsigned NOT NULL,
  `website` int(10) unsigned NOT NULL,
  `name` varchar(200) NOT NULL,
  `label` varchar(200) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `configurationgroup` int(10) unsigned DEFAULT NULL,
  `created` datetime NOT NULL,
  `createdby` int(10) unsigned NOT NULL,
  `modified` datetime NOT NULL,
  `modifiedby` int(10) unsigned NOT NULL,
  `deleted` datetime DEFAULT NULL,
  `deletedby` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `configurationparameter`
--

CREATE TABLE IF NOT EXISTS `configurationparameter` (
  `id` int(10) unsigned NOT NULL,
  `website` int(10) unsigned NOT NULL,
  `scope` varchar(50) NOT NULL,
  `scopevalue` int(11) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `createdby` int(10) unsigned NOT NULL,
  `modified` datetime NOT NULL,
  `modifiedby` int(10) unsigned NOT NULL,
  `deleted` datetime DEFAULT NULL,
  `deletedby` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cookie`
--

CREATE TABLE IF NOT EXISTS `cookie` (
  `id` int(10) unsigned NOT NULL,
  `website` int(10) unsigned NOT NULL,
  `identifier` char(36) NOT NULL,
  `name` varchar(50) NOT NULL,
  `value` varchar(255) NOT NULL,
  `expiration` datetime DEFAULT NULL,
  `created` datetime NOT NULL,
  `createdby` int(10) unsigned NOT NULL,
  `modified` datetime NOT NULL,
  `modifiedby` int(10) unsigned NOT NULL,
  `deleted` datetime DEFAULT NULL,
  `deletedby` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `eventhandler`
--

CREATE TABLE IF NOT EXISTS `eventhandler` (
  `id` int(10) unsigned NOT NULL,
  `website` int(10) unsigned NOT NULL,
  `name` varchar(80) NOT NULL,
  `event` varchar(80) NOT NULL,
  `handler` varchar(80) NOT NULL,
  `position` varchar(90) NOT NULL DEFAULT 'LAST',
  `created` datetime NOT NULL,
  `createdby` int(10) unsigned NOT NULL,
  `modified` datetime NOT NULL,
  `modifiedby` int(10) unsigned NOT NULL,
  `deleted` datetime DEFAULT NULL,
  `deletedby` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `module`
--

CREATE TABLE IF NOT EXISTS `module` (
  `id` int(10) unsigned NOT NULL,
  `website` int(10) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `location` enum('CORE','GLOBAL','LOCAL') NOT NULL,
  `status` enum('ACTIVE','DEACTIVATED') NOT NULL,
  `created` datetime NOT NULL,
  `createdby` int(10) unsigned NOT NULL,
  `modified` datetime NOT NULL,
  `modifiedby` int(10) unsigned NOT NULL,
  `deleted` datetime DEFAULT NULL,
  `deletedby` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `permission`
--

CREATE TABLE IF NOT EXISTS `permission` (
  `id` int(10) unsigned NOT NULL,
  `website` int(10) unsigned NOT NULL,
  `usergroup` int(10) unsigned NOT NULL,
  `name` varchar(80) NOT NULL,
  `value` enum('GRANTED','DENIED') NOT NULL,
  `created` datetime NOT NULL,
  `createdby` int(10) unsigned NOT NULL,
  `modified` datetime NOT NULL,
  `modifiedby` int(10) unsigned NOT NULL,
  `deleted` datetime DEFAULT NULL,
  `deletedby` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `permissiongroup`
--

CREATE TABLE IF NOT EXISTS `permissiongroup` (
  `id` int(10) unsigned NOT NULL,
  `website` int(10) unsigned NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `created` datetime NOT NULL,
  `createdby` int(10) unsigned NOT NULL,
  `modified` datetime NOT NULL,
  `modifiedby` int(10) unsigned NOT NULL,
  `deleted` datetime DEFAULT NULL,
  `deletedby` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `permissionitem`
--

CREATE TABLE IF NOT EXISTS `permissionitem` (
  `id` int(10) unsigned NOT NULL,
  `website` int(10) unsigned NOT NULL,
  `name` varchar(200) NOT NULL,
  `label` varchar(200) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `permissiogroup` int(10) unsigned DEFAULT NULL,
  `created` datetime NOT NULL,
  `createdby` int(10) unsigned NOT NULL,
  `modified` datetime NOT NULL,
  `modifiedby` int(10) unsigned NOT NULL,
  `deleted` datetime DEFAULT NULL,
  `deletedby` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sequence`
--

CREATE TABLE IF NOT EXISTS `sequence` (
  `tablename` varchar(25) CHARACTER SET ascii NOT NULL,
  `value` int(10) unsigned NOT NULL,
  `reserved` char(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `setup`
--

CREATE TABLE IF NOT EXISTS `setup` (
  `id` int(10) unsigned NOT NULL,
  `website` int(10) unsigned NOT NULL,
  `module` varchar(50) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `template` int(10) unsigned NOT NULL,
  `label` varchar(200) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `created` datetime NOT NULL,
  `createdby` int(10) unsigned NOT NULL,
  `modified` datetime NOT NULL,
  `modifiedby` int(10) unsigned NOT NULL,
  `deleted` datetime DEFAULT NULL,
  `deletedby` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `setupsectionplugin`
--

CREATE TABLE IF NOT EXISTS `setupsectionplugin` (
  `id` int(10) unsigned NOT NULL,
  `website` int(10) unsigned NOT NULL,
  `setup` int(10) unsigned NOT NULL,
  `section` varchar(50) NOT NULL,
  `plugin` varchar(100) NOT NULL,
  `configuration` text,
  `position` int(4) NOT NULL,
  `created` datetime NOT NULL,
  `createdby` int(10) unsigned NOT NULL,
  `modified` datetime NOT NULL,
  `modifiedby` int(10) unsigned NOT NULL,
  `deleted` datetime DEFAULT NULL,
  `deletedby` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `template`
--

CREATE TABLE IF NOT EXISTS `template` (
  `id` int(10) unsigned NOT NULL,
  `website` int(10) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `type` enum('DESIGN','VIEW','SUBTEMPLATE') NOT NULL,
  `label` varchar(200) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `created` datetime NOT NULL,
  `createdby` int(10) unsigned NOT NULL,
  `modified` datetime NOT NULL,
  `modifiedby` int(10) unsigned NOT NULL,
  `deleted` datetime DEFAULT NULL,
  `deletedby` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `urihandler`
--

CREATE TABLE IF NOT EXISTS `urihandler` (
  `id` int(10) unsigned NOT NULL,
  `website` int(10) unsigned NOT NULL,
  `name` varchar(80) NOT NULL,
  `pattern` varchar(255) NOT NULL,
  `method` enum('POST','GET','PUT','DELETE') NOT NULL DEFAULT 'GET',
  `handler` varchar(80) NOT NULL,
  `position` varchar(90) NOT NULL DEFAULT 'LAST',
  `created` datetime NOT NULL,
  `createdby` int(10) unsigned NOT NULL,
  `modified` datetime NOT NULL,
  `modifiedby` int(10) unsigned NOT NULL,
  `deleted` datetime DEFAULT NULL,
  `deletedby` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `urihandlerparameter`
--

CREATE TABLE IF NOT EXISTS `urihandlerparameter` (
  `id` int(10) unsigned NOT NULL,
  `website` int(10) unsigned NOT NULL,
  `urihandler` int(10) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `pattern` varchar(50) NOT NULL,
  `created` datetime NOT NULL,
  `createdby` int(10) unsigned NOT NULL,
  `modified` datetime NOT NULL,
  `modifiedby` int(10) unsigned NOT NULL,
  `deleted` datetime DEFAULT NULL,
  `deletedby` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) unsigned NOT NULL,
  `website` int(10) unsigned NOT NULL,
  `loginname` varchar(50) NOT NULL,
  `password` char(32) NOT NULL,
  `passwordsalt` char(32) NOT NULL,
  `lastpasswordchange` datetime NOT NULL,
  `emailaddress` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `createdby` int(10) unsigned NOT NULL,
  `modified` datetime NOT NULL,
  `modifiedby` int(10) unsigned NOT NULL,
  `deleted` datetime DEFAULT NULL,
  `deletedby` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `usergroup`
--

CREATE TABLE IF NOT EXISTS `usergroup` (
  `id` int(10) unsigned NOT NULL,
  `website` int(10) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `created` datetime NOT NULL,
  `createdby` int(10) unsigned NOT NULL,
  `modified` datetime NOT NULL,
  `modifiedby` int(10) unsigned NOT NULL,
  `deleted` datetime DEFAULT NULL,
  `deletedby` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `usergroupitem`
--

CREATE TABLE IF NOT EXISTS `usergroupitem` (
  `id` int(10) unsigned NOT NULL,
  `website` int(10) unsigned NOT NULL,
  `usergroup` int(10) unsigned NOT NULL,
  `user` int(10) unsigned NOT NULL,
  `priority` int(4) NOT NULL DEFAULT '10',
  `created` datetime NOT NULL,
  `createdby` int(10) unsigned NOT NULL,
  `modified` datetime NOT NULL,
  `modifiedby` int(10) unsigned NOT NULL,
  `deleted` datetime DEFAULT NULL,
  `deletedby` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `website`
--

CREATE TABLE IF NOT EXISTS `website` (
  `id` int(10) unsigned NOT NULL,
  `hostname` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `createdby` int(10) unsigned NOT NULL,
  `modified` datetime NOT NULL,
  `modifiedby` int(10) unsigned NOT NULL,
  `deleted` datetime DEFAULT NULL,
  `deletedby` int(10) unsigned DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `configurationgroup`
--
ALTER TABLE `configurationgroup`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `website` (`website`,`deleted`);

--
-- Indexes for table `configurationitem`
--
ALTER TABLE `configurationitem`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `website` (`website`,`name`,`deleted`);

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
-- Indexes for table `permissiongroup`
--
ALTER TABLE `permissiongroup`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `website` (`website`,`name`,`deleted`);

--
-- Indexes for table `permissionitem`
--
ALTER TABLE `permissionitem`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `website` (`website`,`name`,`deleted`);

--
-- Indexes for table `sequence`
--
ALTER TABLE `sequence`
 ADD UNIQUE KEY `tablename` (`tablename`,`value`);

--
-- Indexes for table `setup`
--
ALTER TABLE `setup`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `website` (`website`,`name`,`deleted`);

--
-- Indexes for table `setupsectionplugin`
--
ALTER TABLE `setupsectionplugin`
 ADD PRIMARY KEY (`id`);

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
-- Indexes for table `usergroupitem`
--
ALTER TABLE `usergroupitem`
 ADD UNIQUE KEY `website` (`website`,`usergroup`,`user`,`deleted`);

--
-- Indexes for table `website`
--
ALTER TABLE `website`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `hostname` (`hostname`,`deleted`);
