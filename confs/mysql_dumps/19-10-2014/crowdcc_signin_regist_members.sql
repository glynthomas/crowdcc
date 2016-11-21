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
  `user_id` bigint(11) NOT NULL AUTO_INCREMENT,
  `uname` varchar(30) NOT NULL,
  `email_past` varchar(50) DEFAULT NULL,
  `email_current` varchar(50) DEFAULT NULL,
  `email_confirm` tinyint(1) NOT NULL,
  `passcode` char(128) DEFAULT NULL,
  `salt` char(128) DEFAULT NULL,
  `uid` int(11) NOT NULL,
  `oauth_token` varchar(90) NOT NULL,
  `oauth_token_secret` varchar(90) NOT NULL,
  `ip_address` varchar(200) DEFAULT NULL,
  `platform_user` varchar(100) DEFAULT NULL,
  `browser_user` varchar(200) DEFAULT NULL,
  `follow_user` tinyint(4) NOT NULL,
  `time` varchar(30) DEFAULT NULL,
  `timezone` varchar(100) DEFAULT NULL,
  `timelocal` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=121 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `regist_members`
--

LOCK TABLES `regist_members` WRITE;
/*!40000 ALTER TABLE `regist_members` DISABLE KEYS */;
INSERT INTO `regist_members` VALUES (120,'glynthom','monumentivefail@gmail.com','glynthoma@gmail.com',1,'3029c6589eb331c8b98a7902bd2c6d4723f17da32ddb02980ccf554561f0aadd6b234508df4d2ced909ce4f77dd1d55cefda0571cd9c441bdee019dc8aa27405','5e4195c751066a1453d6b053e03114a92450320d7bd08e8485930a31f88838dfdab5891df1df90888b1de7430faf67ddb10f0b605913511ff4746d5c9271e264',295131454,'295131454-wYMOwfuKhz1dYeAPGddzAWDa6h2UZtX5sIghJhAQ','KDYvGgWDiCO4UXmywPjef04ampfRnwwTWspC8DZ6zBc','127.0.0.1','MacIntel','Mozilla/5.0 (Macintosh; Intel Mac OS X 10.8; rv:26.0) Gecko/20100101 Firefox/26.0',1,'1404413616','Europe/London','2014-07-03 19:53:36'),(115,'monumentivefail','','',0,'38270f1a12649e07c166ea3e0983745597b572e3263a7eb7b61ca590c8d31d45be11a9b49c061821d0df590d397dd41798dc5ecc4162ae03f03156abe7544f43','f4b12a48f41e2bdbcb3a0fe8f53ca7f4031b9e307f60d67d765027b80a556a6b032ba1b390a367668877344e79064a7a0b92ce9ea06728fe43585d8952c139fc',1926741331,'1926741331-3HlMB3DJGNM55TkOG3DtNZYBtRvvU55gad7icZQ','Lr45w8iekYGFAS3JHVwvjGTceo3ftJmrmFJRnZIkF44','127.0.0.1','MacIntel','Mozilla/5.0 (Macintosh; Intel Mac OS X 10.8; rv:27.0) Gecko/20100101 Firefox/27.0',0,'1399590487','Europe/London','2014-05-09 00:08:07'),(116,'crowdccteam','','',0,'abcdbf1ace7729f03ce115c60a00b4c0d9cc9169e73ee9c70632fed7acd90fd5a608d7442b6de2529674ecc2d91e3a4e0553aaa73082f5f046c1666caf0e40c3','827f3672da33ef9ef0e135ddde01b15ad4661dfbaa3ca951318446a2370fe73da186309c231b6e4831d4d073dadabf673bfaaaccf3b12185ad164d8d0a3a9be6',2147483647,'2179631712-iZbzLRaGIASinTMVpxeuIZfXBNtUdnWx2Zj7Fhh','KciFkYXj0gaxWYRTchwKe445dnEyhOUCcYJp0o5YGuYWD','127.0.0.1','MacIntel','Mozilla/5.0 (Macintosh; Intel Mac OS X 10.8; rv:27.0) Gecko/20100101 Firefox/27.0',0,'1400152803','Europe/London','2014-05-15 12:20:03');
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

-- Dump completed on 2014-09-19 17:14:45
