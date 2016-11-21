CREATE DATABASE  IF NOT EXISTS `crowdcc_signin` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `crowdcc_signin`;
-- MySQL dump 10.13  Distrib 5.6.13, for osx10.6 (i386)
--
-- Host: localhost    Database: crowdcc_signin
-- ------------------------------------------------------
-- Server version	5.6.24

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
-- Table structure for table `block_members`
--

DROP TABLE IF EXISTS `block_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `block_members` (
  `user_id` bigint(19) NOT NULL,
  `uname` varchar(30) NOT NULL,
  `email_past` int(11) NOT NULL,
  `email_current` varchar(50) NOT NULL,
  `email_confirm` tinyint(1) NOT NULL,
  `passcode` char(128) NOT NULL,
  `salt` char(128) NOT NULL,
  `uid` int(11) NOT NULL,
  `oauth_token` varchar(90) NOT NULL,
  `oauth_token_secret` varchar(90) NOT NULL,
  `ip_address` varchar(200) NOT NULL,
  `platform_user` varchar(100) NOT NULL,
  `browser_user` varchar(200) NOT NULL,
  `time` varchar(30) NOT NULL,
  `timezone` varchar(200) NOT NULL,
  `timelocal` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `block_members`
--

LOCK TABLES `block_members` WRITE;
/*!40000 ALTER TABLE `block_members` DISABLE KEYS */;
/*!40000 ALTER TABLE `block_members` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-05-06 17:23:14
