CREATE DATABASE  IF NOT EXISTS `crowdcc_api` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `crowdcc_api`;
-- MySQL dump 10.13  Distrib 5.6.13, for osx10.6 (i386)
--
-- Host: localhost    Database: crowdcc_api
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
-- Table structure for table `tweet_urls`
--

DROP TABLE IF EXISTS `tweet_urls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tweet_urls` (
  `tweet_id` bigint(20) NOT NULL,
  `from_user_id` int(10) NOT NULL,
  `entities_urls` varchar(140) NOT NULL,
  PRIMARY KEY (`tweet_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tweet_urls`
--

LOCK TABLES `tweet_urls` WRITE;
/*!40000 ALTER TABLE `tweet_urls` DISABLE KEYS */;
INSERT INTO `tweet_urls` VALUES (411133570300657665,816653,'http://tcrn.ch/Ji7PNY'),(411133547752067072,816653,'http://tcrn.ch/1fn1cEy'),(411133496191483904,816653,'http://tcrn.ch/1jScPq9'),(411134696090001408,14335498,'https://yogainternational.com/article/view/yogaglo-patent-issued'),(411130986442031104,18799825,'https://chrome.google.com/webstore/detail/mq-debug/jioaidppomokmfjfnnhdkekhlbagdhko'),(411133491284164608,816653,'http://tcrn.ch/1fn1bAt'),(411134695007862784,14335498,'http://www.theguardian.com/media/2013/apr/12/news-is-bad-rolf-dobelli'),(411127451830722561,1383161,'http://laravel.com/docs/upgrade'),(411132113887703040,14843763,'http://trhou.se/19habWP'),(411146289686933504,12534,'http://cl.ly/image/0I203W2g2S1a'),(411192127171727360,1671811,'http://aerotwist.com/blog/the-web-needs-containment/'),(411181136979570688,14171886,'http://www.made.com/kringle-sleigh-scandinavian-ash'),(411186764033032192,15737681,'http://blog.hidemyass.com/2013/12/11/hma-tip-5-ways-to-keep-your-passwords-safe-over-the-holidays/'),(428876716283809792,14335498,'http://matt.might.net/articles/cps-conversion'),(428876715138772992,14335498,'http://thenextweb.com/insider/2014/01/29/paypal-denies-providing-payment-information-hacker-hijacked-50000-twitter-username/#!tXHD5');
/*!40000 ALTER TABLE `tweet_urls` ENABLE KEYS */;
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
