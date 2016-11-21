CREATE DATABASE  IF NOT EXISTS `crowdcc_feedback` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `crowdcc_feedback`;
-- MySQL dump 10.13  Distrib 5.6.13, for osx10.6 (i386)
--
-- Host: localhost    Database: crowdcc_feedback
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
-- Table structure for table `feedback_members`
--

DROP TABLE IF EXISTS `feedback_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `feedback_members` (
  `user_id` bigint(19) NOT NULL,
  `uname` varchar(30) NOT NULL,
  `email_current` varchar(30) NOT NULL,
  `email_confirm` tinyint(1) NOT NULL,
  `api_key` varchar(60) NOT NULL,
  `uid` int(11) NOT NULL,
  `a` int(1) NOT NULL,
  `b` int(1) NOT NULL,
  `c` int(1) NOT NULL,
  `d` int(1) NOT NULL,
  `e` int(1) NOT NULL,
  `comments` text NOT NULL,
  `ip_address` varchar(200) NOT NULL,
  `platform_user` varchar(100) NOT NULL,
  `browser_user` varchar(200) NOT NULL,
  `follow_user` tinyint(4) NOT NULL,
  `time` varchar(30) NOT NULL,
  `timezone` varchar(100) NOT NULL,
  `timelocal` datetime NOT NULL,
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `feedback_members`
--

LOCK TABLES `feedback_members` WRITE;
/*!40000 ALTER TABLE `feedback_members` DISABLE KEYS */;
INSERT INTO `feedback_members` VALUES (4017940157320739611,'aa','aa@gmail.com',0,'0',0,0,0,0,0,0,'','127.0.0.1','MacIntel','Mozilla/5.0 (Macintosh; Intel Mac OS X 10.8; rv:35.0) Gecko/20100101 Firefox/35.0',0,'2015-03-06 21:49:29','Europe/London','2015-03-06 21:49:51'),(4021592080955270289,'aa','aa@gmail.com',0,'0',0,0,0,0,0,0,'we love it','127.0.0.1','MacIntel','Mozilla/5.0 (Macintosh; Intel Mac OS X 10.8; rv:35.0) Gecko/20100101 Firefox/35.0',0,'2015-03-11 22:45:28','Europe/London','2015-03-11 22:45:34'),(4022085211804023311,'bb','bb@gmail.com',0,'0',0,0,0,0,0,0,'this is fab we love this shit. ','127.0.0.1','MacIntel','Mozilla/5.0 (Macintosh; Intel Mac OS X 10.8; rv:35.0) Gecko/20100101 Firefox/35.0',0,'2015-03-12 15:04:36','Europe/London','2015-03-12 15:05:20'),(4022091155353121920,'bb','bb@gmail.com',0,'0',0,0,0,0,0,0,'bb','127.0.0.1','MacIntel','Mozilla/5.0 (Macintosh; Intel Mac OS X 10.8; rv:35.0) Gecko/20100101 Firefox/35.0',0,'2015-03-12 15:16:43','Europe/London','2015-03-12 15:17:09'),(4022336662541765327,'dd','dd@gmail.com',0,'0',0,0,0,0,0,0,'ww','127.0.0.1','MacIntel','Mozilla/5.0 (Macintosh; Intel Mac OS X 10.8; rv:35.0) Gecko/20100101 Firefox/35.0',0,'2015-03-12 23:23:46','Europe/London','2015-03-12 23:24:55');
/*!40000 ALTER TABLE `feedback_members` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-05-26 23:32:42
