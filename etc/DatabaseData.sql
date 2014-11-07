-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 08, 2014 at 12:49 AM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `webshop`
--

--
-- Dumping data for table `configurationparameter`
--

INSERT INTO `configurationparameter` (`id`, `website`, `scope`, `scopevalue`, `name`, `value`, `created`, `createdby`, `modified`, `modifiedby`, `deleted`, `deletedby`) VALUES
('7695C632-9B9E-4830-ACD6-9B774808C817', '5E9D62A7-FA58-4638-A811-D66D586F5C1E', 'USER', 'D6C13D62-A30B-4316-8F17-99F47593801E', 'Database.UserScope', 'Foo Bar', '2014-10-31 20:14:00', 'D6C13D62-A30B-4316-8F17-99F47593801E', '2014-10-31 20:14:00', 'D6C13D62-A30B-4316-8F17-99F47593801E', NULL, NULL),
('D4C4152A-02E8-4E43-B2EC-8D36A241133D', '5E9D62A7-FA58-4638-A811-D66D586F5C1E', 'LOCAL', NULL, 'Database.Testentry', 'Hello', '2014-10-31 20:12:00', 'D6C13D62-A30B-4316-8F17-99F47593801E', '2014-10-31 20:12:00', 'D6C13D62-A30B-4316-8F17-99F47593801E', NULL, NULL);

--
-- Dumping data for table `cookie`
--

INSERT INTO `cookie` (`id`, `website`, `identifier`, `name`, `value`, `expiration`, `created`, `createdby`, `modified`, `modifiedby`, `deleted`, `deletedby`) VALUES
('3CBED023-A079-40A3-8B36-EA6CCFAA14D8', '5E9D62A7-FA58-4638-A811-D66D586F5C1E', '788B0B8D-E0CC-4512-B2E4-9F475B7EF79C', 'userpassword', '0552ea0ce439f5422675aecb4891343c', NULL, '2014-11-07 22:34:28', NULL, '2014-11-07 22:34:28', NULL, NULL, NULL),
('44D234E0-1B96-4DAF-879A-634FB09AF057', '5E9D62A7-FA58-4638-A811-D66D586F5C1E', 'D6F53830-3AC1-4A7E-9562-F35093ED85A4', 'userid', '1F01BDA1-D21F-4C12-9817-21FC44D11BD9', NULL, '2014-10-31 11:37:03', NULL, '2014-10-31 11:37:03', NULL, NULL, NULL),
('585F9815-6CE9-4C74-983F-FF1022D6BD32', '5E9D62A7-FA58-4638-A811-D66D586F5C1E', 'D6F53830-3AC1-4A7E-9562-F35093ED85A4', 'userpassword', '8ac1901dfbf1cc3f167d93a91f8ffac6', NULL, '2014-10-31 11:37:04', NULL, '2014-10-31 11:37:04', NULL, NULL, NULL),
('8430B698-AC2E-495C-9FBD-EB378BA42812', '5E9D62A7-FA58-4638-A811-D66D586F5C1E', 'E4017F5F-194A-41F4-A6B3-7EE726F722FB', 'userpassword', 'b45bfec60116150bd4fc06097d1de583', NULL, '2014-10-30 14:51:17', NULL, '2014-10-30 14:51:17', NULL, NULL, NULL),
('A6C9EA80-07EA-4D11-B8B2-54E8AFB3D288', '5E9D62A7-FA58-4638-A811-D66D586F5C1E', '788B0B8D-E0CC-4512-B2E4-9F475B7EF79C', 'userid', 'C36DC5C2-797E-4E19-9055-65EFF35A4D03', NULL, '2014-11-07 22:34:28', NULL, '2014-11-07 22:34:28', NULL, NULL, NULL),
('B1FF4042-27C9-41E3-A9D3-9F9FA691F62D', '5E9D62A7-FA58-4638-A811-D66D586F5C1E', '9B2FD042-3158-4591-BBF8-BEA20E57B837', 'userid', '2C83A9C7-017A-4432-959B-999A8DF08ADC', NULL, '2014-10-30 18:55:34', NULL, '2014-10-30 18:55:34', NULL, NULL, NULL),
('E845A8C7-F6E1-4208-B536-4AD1559D6DC7', '5E9D62A7-FA58-4638-A811-D66D586F5C1E', 'E4017F5F-194A-41F4-A6B3-7EE726F722FB', 'userid', '64E2636B-1C31-492A-8884-86400938AD54', NULL, '2014-10-30 14:51:17', NULL, '2014-10-30 14:51:17', NULL, NULL, NULL),
('F5DE95EA-668C-43BC-B5D5-0929277B8B78', '5E9D62A7-FA58-4638-A811-D66D586F5C1E', '9B2FD042-3158-4591-BBF8-BEA20E57B837', 'userpassword', 'bb207cab0da5681aa524f52b348ea501', NULL, '2014-10-30 18:55:34', NULL, '2014-10-30 18:55:34', NULL, NULL, NULL);

--
-- Dumping data for table `module`
--

INSERT INTO `module` (`id`, `website`, `name`, `location`, `status`, `created`, `createdby`, `modified`, `modifiedby`, `deleted`, `deletedby`) VALUES
('FAA814F3-7669-41B4-90AE-46CFAAAFF3CC', '5E9D62A7-FA58-4638-A811-D66D586F5C1E', 'UserManager', 'CORE', 'ACTIVE', '2014-10-30 18:55:00', 'D6C13D62-A30B-4316-8F17-99F47593801E', '2014-10-30 18:55:00', 'D6C13D62-A30B-4316-8F17-99F47593801E', NULL, NULL);

--
-- Dumping data for table `permission`
--

INSERT INTO `permission` (`id`, `website`, `usergroup`, `name`, `value`, `created`, `createdby`, `modified`, `modifiedby`, `deleted`, `deletedby`) VALUES
('3df0483a-61ea-11e4-9ac4-0013779f2cc7', '5E9D62A7-FA58-4638-A811-D66D586F5C1E', '60141BF8-48DD-4834-8E86-B234E4D2199A', 'YageCMS.Core.Test2', 'DENIED', '2014-11-01 18:08:00', 'D6C13D62-A30B-4316-8F17-99F47593801E', '2014-11-01 18:08:00', 'D6C13D62-A30B-4316-8F17-99F47593801E', NULL, NULL),
('9E7E8762-6804-45AE-A4C4-1720EAD1704B', '5E9D62A7-FA58-4638-A811-D66D586F5C1E', '60141BF8-48DD-4834-8E86-B234E4D2199A', 'YageCMS.Core.Test', 'GRANTED', '2014-11-01 18:08:00', 'D6C13D62-A30B-4316-8F17-99F47593801E', '2014-11-01 18:08:00', 'D6C13D62-A30B-4316-8F17-99F47593801E', NULL, NULL);

--
-- Dumping data for table `urihandler`
--

INSERT INTO `urihandler` (`id`, `website`, `name`, `pattern`, `method`, `handler`, `position`, `created`, `createdby`, `modified`, `modifiedby`, `deleted`, `deletedby`) VALUES
('D2BC0E2E-BE9A-46E4-AA8F-C09564355020', '5E9D62A7-FA58-4638-A811-D66D586F5C1E', 'LocalPath', '/local-%module%-%view%.html', 'GET', 'YageCMS.Core.Tools.Module.ModuleView->CallModuleView', 'LAST', '2014-11-01 12:31:00', 'D6C13D62-A30B-4316-8F17-99F47593801E', '2014-11-01 12:31:00', 'D6C13D62-A30B-4316-8F17-99F47593801E', NULL, NULL);

--
-- Dumping data for table `urihandlerparameter`
--

INSERT INTO `urihandlerparameter` (`id`, `website`, `urihandler`, `name`, `pattern`, `created`, `createdby`, `modified`, `modifiedby`, `deleted`, `deletedby`) VALUES
('84728E0D-AB8B-4973-9994-A5B9E6BCA564', '5E9D62A7-FA58-4638-A811-D66D586F5C1E', 'D2BC0E2E-BE9A-46E4-AA8F-C09564355020', 'module', '([a-zA-Z0-9_]+)', '2014-11-01 12:35:00', 'D6C13D62-A30B-4316-8F17-99F47593801E', '2014-11-01 12:35:00', 'D6C13D62-A30B-4316-8F17-99F47593801E', NULL, NULL),
('D00A2737-D63B-4741-BF02-72E653810454', '5E9D62A7-FA58-4638-A811-D66D586F5C1E', 'D2BC0E2E-BE9A-46E4-AA8F-C09564355020', 'view', '([a-zA-Z0-9_]+)', '2014-11-01 12:35:00', 'D6C13D62-A30B-4316-8F17-99F47593801E', '2014-11-01 12:35:00', 'D6C13D62-A30B-4316-8F17-99F47593801E', NULL, NULL);

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `website`, `loginname`, `password`, `passwordsalt`, `lastpasswordchange`, `emailaddress`, `usergroup`, `created`, `createdby`, `modified`, `modifiedby`, `deleted`, `deletedby`) VALUES
('1F01BDA1-D21F-4C12-9817-21FC44D11BD9', '5E9D62A7-FA58-4638-A811-D66D586F5C1E', 'guest-31f62625-34f5-46f9-8c06-9bf2296fd60e', '8ac1901dfbf1cc3f167d93a91f8ffac6', '1d91bd03ecf443f19ce374e9c84c62e9', '0000-00-00 00:00:00', 'guest-31f62625-34f5-46f9-8c06-9bf2296fd60e@guest.com', '60141BF8-48DD-4834-8E86-B234E4D2199A', '2014-10-31 11:37:03', NULL, '2014-10-31 11:37:03', NULL, NULL, NULL),
('2C83A9C7-017A-4432-959B-999A8DF08ADC', '5E9D62A7-FA58-4638-A811-D66D586F5C1E', 'guest-1a317375-e79e-4a80-8734-453ecc25f60b', 'bb207cab0da5681aa524f52b348ea501', '948c559803df41ccc6132b5136b00b3a', '0000-00-00 00:00:00', 'guest-1a317375-e79e-4a80-8734-453ecc25f60b@guest.com', '60141BF8-48DD-4834-8E86-B234E4D2199A', '2014-10-30 18:55:33', NULL, '2014-10-30 18:55:33', NULL, NULL, NULL),
('4597C0F1-3FAC-452E-85F8-5F086BA70CD7', '5E9D62A7-FA58-4638-A811-D66D586F5C1E', 'guest-490ec796-de11-426e-8965-7ce04d623906', '4ded304ab16db15b9300e8eeff25f404', '4da319831f9d4232b08f98272c2b1991', '0000-00-00 00:00:00', 'guest-490ec796-de11-426e-8965-7ce04d623906@guest.com', '60141BF8-48DD-4834-8E86-B234E4D2199A', '2014-11-07 22:34:06', NULL, '2014-11-07 22:34:06', NULL, NULL, NULL),
('64E2636B-1C31-492A-8884-86400938AD54', '5E9D62A7-FA58-4638-A811-D66D586F5C1E', 'guest-4f1559d5-4def-4cfc-92df-ac4757e93791', 'b45bfec60116150bd4fc06097d1de583', '9196b801c2d0e20dad0fc1f7068553c1', '2014-10-30 00:00:00', 'guest-4f1559d5-4def-4cfc-92df-ac4757e93791@guest.com', '60141BF8-48DD-4834-8E86-B234E4D2199A', '2014-10-30 14:51:17', NULL, '2014-10-30 14:51:17', NULL, NULL, NULL),
('82726B75-7020-478B-BAFD-A6A69CDED43D', '5E9D62A7-FA58-4638-A811-D66D586F5C1E', 'guest-79e68b5c-8a9e-40b8-b293-85c65e2c942c', 'b2e306b5d7d256d97d81b115fe610ba0', 'b8fc21d668a412e7ae7bf90c52f50182', '2014-10-30 00:00:00', 'guest-79e68b5c-8a9e-40b8-b293-85c65e2c942c@guest.com', '60141BF8-48DD-4834-8E86-B234E4D2199A', '2014-10-30 14:47:09', NULL, '2014-10-30 14:47:09', NULL, NULL, NULL),
('C36DC5C2-797E-4E19-9055-65EFF35A4D03', '5E9D62A7-FA58-4638-A811-D66D586F5C1E', 'guest-4b320b54-33ca-4553-a6f8-fd4ee4d5f290', '0552ea0ce439f5422675aecb4891343c', '85c3255cc918d813600fc4850faf922e', '0000-00-00 00:00:00', 'guest-4b320b54-33ca-4553-a6f8-fd4ee4d5f290@guest.com', '60141BF8-48DD-4834-8E86-B234E4D2199A', '2014-11-07 22:34:28', NULL, '2014-11-07 22:34:28', NULL, NULL, NULL),
('D6C13D62-A30B-4316-8F17-99F47593801E', '5E9D62A7-FA58-4638-A811-D66D586F5C1E', 'dominikj', '0c192c7a4371ea828350aab4a6a92508', '3071bfea7bcabb08d416d3e03699150e', '2014-10-30 00:00:00', 'dominik1991jahn@gmail.com', '60141BF8-48DD-4834-8E86-B234E4D2199A', '2014-10-29 22:39:00', 'D6C13D62-A30B-4316-8F17-99F47593801E', '2014-10-29 22:39:00', 'D6C13D62-A30B-4316-8F17-99F47593801E', NULL, NULL),
('F4450485-C664-4A44-9625-90B668FF849D', '5E9D62A7-FA58-4638-A811-D66D586F5C1E', 'guest-68716a05-50d6-41af-b8c1-db777062104f', '3ee2817ea8c48ef15b4df934862c915f', 'c98cfb916b204cedaec05d66eaf2ad57', '2014-10-30 00:00:00', 'guest-68716a05-50d6-41af-b8c1-db777062104f@guest.com', '60141BF8-48DD-4834-8E86-B234E4D2199A', '2014-10-30 14:46:09', NULL, '2014-10-30 14:46:09', NULL, NULL, NULL);

--
-- Dumping data for table `usergroup`
--

INSERT INTO `usergroup` (`id`, `website`, `name`, `created`, `createdby`, `modified`, `modifiedby`, `deleted`, `deletedby`) VALUES
('60141BF8-48DD-4834-8E86-B234E4D2199A', '5E9D62A7-FA58-4638-A811-D66D586F5C1E', 'Guests', '2014-10-30 13:03:00', 'D6C13D62-A30B-4316-8F17-99F47593801E', '2014-10-30 13:03:00', 'D6C13D62-A30B-4316-8F17-99F47593801E', NULL, NULL);

--
-- Dumping data for table `website`
--

INSERT INTO `website` (`id`, `hostname`, `created`, `createdby`, `modified`, `modifiedby`, `deleted`, `deletedby`) VALUES
('5E9D62A7-FA58-4638-A811-D66D586F5C1E', 'localhost', '2014-10-29 20:17:00', 'D6C13D62-A30B-4316-8F17-99F47593801E', '2014-10-29 20:17:00', 'D6C13D62-A30B-4316-8F17-99F47593801E', NULL, NULL);
