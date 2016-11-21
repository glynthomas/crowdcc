CREATE DATABASE  IF NOT EXISTS `crowdcc_signin` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `crowdcc_signin`;
-- MySQL dump 10.13  Distrib 5.6.13, for osx10.6 (i386)
--
-- Host: 127.0.0.1    Database: crowdcc_signin
-- ------------------------------------------------------
-- Server version	5.5.36

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `regist_members`
--

DROP TABLE IF EXISTS `regist_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `regist_members` (
  `user_id` bigint(19) NOT NULL,
  `uname` varchar(30) NOT NULL,
  `email_past` varchar(50) NOT NULL,
  `email_current` varchar(50) NOT NULL,
  `email_confirm` tinyint(1) NOT NULL,
  `api_key` char(60) NOT NULL,
  `passcode` char(128) NOT NULL,
  `salt` char(128) NOT NULL,
  `uid` int(11) NOT NULL,
  `oauth_token` varchar(128) NOT NULL,
  `oauth_token_secret` varchar(128) NOT NULL,
  `ip_address` varchar(200) NOT NULL,
  `platform_user` varchar(100) NOT NULL,
  `browser_user` varchar(200) NOT NULL,
  `follow_user` tinyint(4) NOT NULL,
  `time` varchar(30) NOT NULL,
  `timezone` varchar(100) NOT NULL,
  `timelocal` datetime NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=142 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `regist_members`
--

LOCK TABLES `regist_members` WRITE;
/*!40000 ALTER TABLE `regist_members` DISABLE KEYS */;
INSERT INTO `regist_members` VALUES (3931713472040068190,'glynthom','glynthoma@gmail.com','glynthoma@gmail.com',1,'3457863f3401ee5ef7-297d9d64-340-3a798b67-ee5-6c25c2-b9b16163','55da91d0788cabdc77c4f9dce7edc27334967687620500846022c06d099961847b41ea2e7e86b526ca4cffd6a2bd3fa9a4bbcdebd6b71a62f809be09709ac727','df93887be24343b3ecebbef38d2c32ff25146167caa150e0d830ceebc9688069eda44982cb9c404711b6a03959ac65feb86191f0e1c2e83122a66835efd25ac5',295131454,'6f2cc738bb32f81e5bdd41c0fe84a3d2767b866712eb867029ff30f66fefba97632301a5e227f65b8dc21aa0323b5f3b99a6','1651ab7fcf649b6f06b379ade693b9cd7460847845bfeb7405df11c065e28c947031478ee836f619afb50a','127.0.0.1','MacIntel','Mozilla/5.0 (Macintosh; Intel Mac OS X 10.8; rv:32.0) Gecko/20100101 Firefox/32.0',1,'1417986344','Europe/London','2014-12-07 21:05:44');
/*!40000 ALTER TABLE `regist_members` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-12-23 23:27:07
