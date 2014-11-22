CREATE TABLE `website` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hostname` varchar(255) CHARACTER SET ascii NOT NULL,
  `created` datetime NOT NULL,
  `createdby` int(10) unsigned NOT NULL DEFAULT 1,
  `modified` datetime NOT NULL,
  `modifiedby` int(10) unsigned NOT NULL DEFAULT 1,
  `deleted` datetime NOT NULL DEFAULT '9999-12-31 23:59:59',
  `deletedby` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hostname` (`hostname`,`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `website` int(10) unsigned NOT NULL,
  `loginname` varchar(50) CHARACTER SET ascii NOT NULL,
  `password` char(32) CHARACTER SET ascii NOT NULL,
  `passwordsalt` char(32) CHARACTER SET ascii NOT NULL,
  `lastpasswordchange` datetime NOT NULL,
  `emailaddress` varchar(255) CHARACTER SET ascii NOT NULL,
  `created` datetime NOT NULL,
  `createdby` int(10) unsigned NOT NULL DEFAULT 1,
  `modified` datetime NOT NULL,
  `modifiedby` int(10) unsigned NOT NULL DEFAULT 1,
  `deleted` datetime NOT NULL DEFAULT '9999-12-31 23:59:59',
  `deletedby` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `loginname` (`website`,`loginname`,`deleted`),
  UNIQUE KEY `emailaddress` (`website`,`emailaddress`,`deleted`),
  
  FOREIGN KEY (website) REFERENCES website(id)
	ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `usergroup` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `website` int(10) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `created` datetime NOT NULL,
  `createdby` int(10) unsigned NOT NULL DEFAULT 1,
  `modified` datetime NOT NULL,
  `modifiedby` int(10) unsigned NOT NULL DEFAULT 1,
  `deleted` datetime NOT NULL DEFAULT '9999-12-31 23:59:59',
  `deletedby` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`website`,`name`,`deleted`),
  
  FOREIGN KEY (website) REFERENCES website(id)
	ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `configurationgroup` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `website` int(10) unsigned NOT NULL,
  `name` varchar(200) CHARACTER SET ascii NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `created` datetime NOT NULL,
  `createdby` int(10) unsigned NOT NULL DEFAULT 1,
  `modified` datetime NOT NULL,
  `modifiedby` int(10) unsigned NOT NULL DEFAULT 1,
  `deleted` datetime NOT NULL DEFAULT '9999-12-31 23:59:59',
  `deletedby` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`website`,`name`,`deleted`),
  
  FOREIGN KEY (website) REFERENCES website(id)
	ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `configurationitem` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `website` int(10) unsigned NOT NULL,
  `name` varchar(200) CHARACTER SET ascii NOT NULL,
  `label` varchar(200) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `configurationgroup` int(10) unsigned DEFAULT NULL,
  `created` datetime NOT NULL,
  `createdby` int(10) unsigned NOT NULL DEFAULT 1,
  `modified` datetime NOT NULL,
  `modifiedby` int(10) unsigned NOT NULL DEFAULT 1,
  `deleted` datetime NOT NULL DEFAULT '9999-12-31 23:59:59',
  `deletedby` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`website`,`name`,`deleted`),
  
  FOREIGN KEY (website) REFERENCES website(id)
	ON DELETE CASCADE
    ON UPDATE CASCADE,
    
  FOREIGN KEY (configurationgroup) REFERENCES configurationgroup(id)
	ON DELETE CASCADE
    ON UPDATE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `configurationparameter` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `website` int(10) unsigned NOT NULL,
  `scope` varchar(50) NOT NULL,
  `scopevalue` varchar(50) CHARACTER SET ascii DEFAULT NULL,
  `name` varchar(255) CHARACTER SET ascii NOT NULL,
  `value` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `createdby` int(10) unsigned NOT NULL DEFAULT 1,
  `modified` datetime NOT NULL,
  `modifiedby` int(10) unsigned NOT NULL DEFAULT 1,
  `deleted` datetime NOT NULL DEFAULT '9999-12-31 23:59:59',
  `deletedby` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `scope_name` (`website`,`scope`,`scopevalue`,`name`,`deleted`),
  
  FOREIGN KEY (website) REFERENCES website(id)
	ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `cookie` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `website` int(10) unsigned NOT NULL,
  `identifier` char(36) CHARACTER SET ascii NOT NULL,
  `name` varchar(50) CHARACTER SET ascii NOT NULL,
  `value` varchar(255) NOT NULL,
  `expiration` datetime DEFAULT NULL,
  `created` datetime NOT NULL,
  `createdby` int(10) unsigned NOT NULL DEFAULT 1,
  `modified` datetime NOT NULL,
  `modifiedby` int(10) unsigned NOT NULL DEFAULT 1,
  `deleted` datetime NOT NULL DEFAULT '9999-12-31 23:59:59',
  `deletedby` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `identifier_name` (`website`,`identifier`,`name`,`deleted`),
  
  FOREIGN KEY (website) REFERENCES website(id)
	ON DELETE CASCADE
    ON UPDATE CASCADE,
    
  FOREIGN KEY (createdby) REFERENCES user(id)
	ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `eventhandler` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `website` int(10) unsigned NOT NULL,
  `name` varchar(80) CHARACTER SET ascii NOT NULL,
  `event` varchar(80) CHARACTER SET ascii NOT NULL,
  `handler` varchar(80) CHARACTER SET ascii NOT NULL,
  `position` varchar(90) CHARACTER SET ascii NOT NULL DEFAULT 'LAST',
  `created` datetime NOT NULL,
  `createdby` int(10) unsigned NOT NULL DEFAULT 1,
  `modified` datetime NOT NULL,
  `modifiedby` int(10) unsigned NOT NULL DEFAULT 1,
  `deleted` datetime NOT NULL DEFAULT '9999-12-31 23:59:59',
  `deletedby` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`website`,`name`,`deleted`),
  
  FOREIGN KEY (website) REFERENCES website(id)
	ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `module` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `website` int(10) unsigned NOT NULL,
  `name` varchar(50) CHARACTER SET ascii NOT NULL,
  `location` enum('CORE','GLOBAL','LOCAL') NOT NULL,
  `status` enum('ACTIVE','DEACTIVATED') NOT NULL,
  `created` datetime NOT NULL,
  `createdby` int(10) unsigned NOT NULL DEFAULT 1,
  `modified` datetime NOT NULL,
  `modifiedby` int(10) unsigned NOT NULL DEFAULT 1,
  `deleted` datetime NOT NULL DEFAULT '9999-12-31 23:59:59',
  `deletedby` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`website`,`name`,`deleted`),
  
  FOREIGN KEY (website) REFERENCES website(id)
	ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `permission` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `website` int(10) unsigned NOT NULL,
  `usergroup` int(10) unsigned NOT NULL,
  `name` varchar(80) CHARACTER SET ascii NOT NULL,
  `value` enum('GRANTED','DENIED') NOT NULL,
  `created` datetime NOT NULL,
  `createdby` int(10) unsigned NOT NULL DEFAULT 1,
  `modified` datetime NOT NULL,
  `modifiedby` int(10) unsigned NOT NULL DEFAULT 1,
  `deleted` datetime NOT NULL DEFAULT '9999-12-31 23:59:59',
  `deletedby` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usergroup_name` (`website`,`usergroup`,`name`,`deleted`),
  
  FOREIGN KEY (website) REFERENCES website(id)
	ON DELETE CASCADE
    ON UPDATE CASCADE,
    
  FOREIGN KEY (usergroup) REFERENCES usergroup(id)
	ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `permissiongroup` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `website` int(10) unsigned NOT NULL,
  `name` varchar(200) CHARACTER SET ascii NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `created` datetime NOT NULL,
  `createdby` int(10) unsigned NOT NULL DEFAULT 1,
  `modified` datetime NOT NULL,
  `modifiedby` int(10) unsigned NOT NULL DEFAULT 1,
  `deleted` datetime NOT NULL DEFAULT '9999-12-31 23:59:59',
  `deletedby` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`website`,`name`,`deleted`),
  
  FOREIGN KEY (website) REFERENCES website(id)
	ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `permissionitem` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `website` int(10) unsigned NOT NULL,
  `name` varchar(200) CHARACTER SET ascii NOT NULL,
  `label` varchar(200) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `permissiongroup` int(10) unsigned DEFAULT NULL,
  `created` datetime NOT NULL,
  `createdby` int(10) unsigned NOT NULL DEFAULT 1,
  `modified` datetime NOT NULL,
  `modifiedby` int(10) unsigned NOT NULL DEFAULT 1,
  `deleted` datetime NOT NULL DEFAULT '9999-12-31 23:59:59',
  `deletedby` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`website`,`name`,`deleted`),
  
  FOREIGN KEY (website) REFERENCES website(id)
	ON DELETE CASCADE
    ON UPDATE CASCADE,
    
  FOREIGN KEY (permissiongroup) REFERENCES permissiongroup(id)
	ON DELETE SET NULL
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `template` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `website` int(10) unsigned NOT NULL,
  `name` varchar(50) CHARACTER SET ascii NOT NULL,
  `type` enum('DESIGN','VIEW','SUBTEMPLATE') NOT NULL,
  `label` varchar(200) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `created` datetime NOT NULL,
  `createdby` int(10) unsigned NOT NULL DEFAULT 1,
  `modified` datetime NOT NULL,
  `modifiedby` int(10) unsigned NOT NULL DEFAULT 1,
  `deleted` datetime NOT NULL DEFAULT '9999-12-31 23:59:59',
  `deletedby` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`website`,`name`,`deleted`),
  
  FOREIGN KEY (website) REFERENCES website(id)
	ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `setup` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `website` int(10) unsigned NOT NULL,
  `module` varchar(100) CHARACTER SET ascii DEFAULT NULL,
  `name` varchar(100) CHARACTER SET ascii NOT NULL,
  `template` int(10) unsigned NOT NULL,
  `label` varchar(200) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `created` datetime NOT NULL,
  `createdby` int(10) unsigned NOT NULL DEFAULT 1,
  `modified` datetime NOT NULL,
  `modifiedby` int(10) unsigned NOT NULL DEFAULT 1,
  `deleted` datetime NOT NULL DEFAULT '9999-12-31 23:59:59',
  `deletedby` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`website`,`name`,`deleted`),
  
  FOREIGN KEY (website) REFERENCES website(id)
	ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (template) REFERENCES template(id)
	ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `setupsectionplugin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `website` int(10) unsigned NOT NULL,
  `setup` int(10) unsigned NOT NULL,
  `section` varchar(50) CHARACTER SET ascii NOT NULL,
  `plugin` varchar(100) CHARACTER SET ascii NOT NULL,
  `configuration` text,
  `position` int(4) NOT NULL,
  `created` datetime NOT NULL,
  `createdby` int(10) unsigned NOT NULL DEFAULT 1,
  `modified` datetime NOT NULL,
  `modifiedby` int(10) unsigned NOT NULL DEFAULT 1,
  `deleted` datetime NOT NULL DEFAULT '9999-12-31 23:59:59',
  `deletedby` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  
  FOREIGN KEY (website) REFERENCES website(id)
	ON DELETE CASCADE
    ON UPDATE CASCADE,
    
  FOREIGN KEY (setup) REFERENCES setup(id)
	ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `urihandler` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `website` int(10) unsigned NOT NULL,
  `name` varchar(80) CHARACTER SET ascii NOT NULL,
  `pattern` varchar(255) CHARACTER SET ascii NOT NULL,
  `method` enum('POST','GET','PUT','DELETE') NOT NULL DEFAULT 'GET',
  `handler` varchar(80) CHARACTER SET ascii NOT NULL,
  `position` varchar(90) CHARACTER SET ascii NOT NULL DEFAULT 'LAST',
  `created` datetime NOT NULL,
  `createdby` int(10) unsigned NOT NULL DEFAULT 1,
  `modified` datetime NOT NULL,
  `modifiedby` int(10) unsigned NOT NULL DEFAULT 1,
  `deleted` datetime NOT NULL DEFAULT '9999-12-31 23:59:59',
  `deletedby` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`website`,`name`,`deleted`),
  UNIQUE KEY `pattern_method` (`website`,`pattern`,`method`,`deleted`),
  
  FOREIGN KEY (website) REFERENCES website(id)
	ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `urihandlerparameter` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `website` int(10) unsigned NOT NULL,
  `urihandler` int(10) unsigned NOT NULL,
  `name` varchar(50) CHARACTER SET ascii NOT NULL,
  `pattern` varchar(50) CHARACTER SET ascii NOT NULL,
  `created` datetime NOT NULL,
  `createdby` int(10) unsigned NOT NULL DEFAULT 1,
  `modified` datetime NOT NULL,
  `modifiedby` int(10) unsigned NOT NULL DEFAULT 1,
  `deleted` datetime NOT NULL DEFAULT '9999-12-31 23:59:59',
  `deletedby` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `urihandler_name` (`website`,`urihandler`,`name`,`deleted`),
  
  FOREIGN KEY (website) REFERENCES website(id)
	ON DELETE CASCADE
    ON UPDATE CASCADE,
    
  FOREIGN KEY (urihandler) REFERENCES urihandler(id)
	ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `usergroupitem` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `website` int(10) unsigned NOT NULL,
  `usergroup` int(10) unsigned NOT NULL,
  `user` int(10) unsigned NOT NULL,
  `priority` int(4) NOT NULL DEFAULT '10',
  `created` datetime NOT NULL,
  `createdby` int(10) unsigned NOT NULL DEFAULT 1,
  `modified` datetime NOT NULL,
  `modifiedby` int(10) unsigned NOT NULL DEFAULT 1,
  `deleted` datetime NOT NULL DEFAULT '9999-12-31 23:59:59',
  `deletedby` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usergroup_user` (`website`,`usergroup`,`user`,`deleted`),
  FOREIGN KEY (website) REFERENCES website(id)
	ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (usergroup) REFERENCES usergroup(id)
	ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (user) REFERENCES user(id)
	ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
