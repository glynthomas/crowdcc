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
-- Table structure for table `signin_attempts`
--

DROP TABLE IF EXISTS `signin_attempts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `signin_attempts` (
  `user_id` bigint(19) NOT NULL,
  `uname` varchar(30) NOT NULL,
  `email_past` varchar(50) NOT NULL,
  `email_current` varchar(50) NOT NULL,
  `ip_address` varchar(200) NOT NULL,
  `platform_user` varchar(100) NOT NULL,
  `browser_user` varchar(200) NOT NULL,
  `time` varchar(30) NOT NULL,
  `timezone` varchar(100) NOT NULL,
  `timelocal` datetime NOT NULL,
  `ip_unequal` varchar(30) NOT NULL,
  `platform_unequal` varchar(30) NOT NULL DEFAULT '0',
  `browser_unequal` varchar(30) NOT NULL DEFAULT '0',
  `timezone_unequal` varchar(30) NOT NULL DEFAULT '0',
  `password_bad` varchar(30) NOT NULL,
  `attempts` varchar(30) NOT NULL DEFAULT '0',
  `eraser` varchar(30) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `signin_attempts`
--

LOCK TABLES `signin_attempts` WRITE;
/*!40000 ALTER TABLE `signin_attempts` DISABLE KEYS */;
/*!40000 ALTER TABLE `signin_attempts` ENABLE KEYS */;
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
