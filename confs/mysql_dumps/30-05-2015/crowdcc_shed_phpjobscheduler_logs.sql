CREATE DATABASE  IF NOT EXISTS `crowdcc_shed` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `crowdcc_shed`;
-- MySQL dump 10.13  Distrib 5.6.13, for osx10.6 (i386)
--
-- Host: localhost    Database: crowdcc_shed
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
-- Table structure for table `phpjobscheduler_logs`
--

DROP TABLE IF EXISTS `phpjobscheduler_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phpjobscheduler_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_added` int(11) DEFAULT NULL,
  `script` varchar(128) DEFAULT NULL,
  `output` text,
  `execution_time` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8402 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `phpjobscheduler_logs`
--

LOCK TABLES `phpjobscheduler_logs` WRITE;
/*!40000 ALTER TABLE `phpjobscheduler_logs` DISABLE KEYS */;
INSERT INTO `phpjobscheduler_logs` VALUES (8401,1432834415,'http://crowdcc.apc/shed/scripts/tmin.php','token admin completed ...','0.05002 seconds via PHP CURL '),(8400,1432834415,'http://crowdcc.apc/shed/scripts/smin.php','session admin completed ...','0.12963 seconds via PHP CURL '),(8399,1432740597,'http://crowdcc.apc/shed/scripts/tmin.php','token admin completed ...','0.00239 seconds via PHP CURL '),(8398,1432740597,'http://crowdcc.apc/shed/scripts/smin.php','session admin completed ...','0.00293 seconds via PHP CURL '),(8397,1432722493,'http://crowdcc.apc/shed/scripts/tmin.php','token admin completed ...','0.00242 seconds via PHP CURL '),(8396,1432722493,'http://crowdcc.apc/shed/scripts/smin.php','session admin completed ...','0.00435 seconds via PHP CURL '),(8395,1432721723,'http://crowdcc.apc/shed/scripts/tmin.php','token admin completed ...','0.00259 seconds via PHP CURL '),(8394,1432721723,'http://crowdcc.apc/shed/scripts/smin.php','session admin completed ...','0.00441 seconds via PHP CURL '),(8393,1432675411,'http://crowdcc.apc/shed/scripts/tmin.php','token admin completed ...','0.00229 seconds via PHP CURL '),(8392,1432675411,'http://crowdcc.apc/shed/scripts/smin.php','session admin completed ...','0.00437 seconds via PHP CURL '),(8391,1432675370,'http://crowdcc.apc/shed/scripts/tmin.php','token admin completed ...','0.00359 seconds via PHP CURL '),(8390,1432675370,'http://crowdcc.apc/shed/scripts/smin.php','session admin completed ...','0.02325 seconds via PHP CURL '),(8389,1432311206,'http://crowdcc.apc/shed/scripts/tmin.php','token admin completed ...','0.05824 seconds via PHP CURL '),(8388,1432311206,'http://crowdcc.apc/shed/scripts/smin.php','session admin completed ...','0.01329 seconds via PHP CURL '),(8387,1432237708,'http://crowdcc.apc/shed/scripts/tmin.php','token admin completed ...','0.00268 seconds via PHP CURL '),(8386,1432237708,'http://crowdcc.apc/shed/scripts/smin.php','session admin completed ...','0.00304 seconds via PHP CURL '),(8385,1432237553,'http://crowdcc.apc/shed/scripts/tmin.php','token admin completed ...','0.00305 seconds via PHP CURL '),(8384,1432237553,'http://crowdcc.apc/shed/scripts/smin.php','session admin completed ...','0.08071 seconds via PHP CURL '),(8383,1432143866,'http://crowdcc.apc/shed/scripts/tmin.php','token admin completed ...','0.00218 seconds via PHP CURL '),(8382,1432143866,'http://crowdcc.apc/shed/scripts/smin.php','session admin completed ...','0.00284 seconds via PHP CURL '),(8381,1432141201,'http://crowdcc.apc/shed/scripts/tmin.php','token admin completed ...','0.00342 seconds via PHP CURL '),(8380,1432141201,'http://crowdcc.apc/shed/scripts/smin.php','session admin completed ...','0.00349 seconds via PHP CURL '),(8379,1432140230,'http://crowdcc.apc/shed/scripts/tmin.php','token admin completed ...','0.00287 seconds via PHP CURL '),(8378,1432140230,'http://crowdcc.apc/shed/scripts/smin.php','session admin completed ...','0.00412 seconds via PHP CURL '),(8377,1432140020,'http://crowdcc.apc/shed/scripts/tmin.php','token admin completed ...','0.00283 seconds via PHP CURL '),(8376,1432140020,'http://crowdcc.apc/shed/scripts/smin.php','session admin completed ...','0.01618 seconds via PHP CURL '),(8375,1431702830,'http://crowdcc.apc/shed/scripts/tmin.php','token admin completed ...','0.00262 seconds via PHP CURL '),(8374,1431702830,'http://crowdcc.apc/shed/scripts/smin.php','session admin completed ...','0.00643 seconds via PHP CURL '),(8373,1431616488,'http://crowdcc.apc/shed/scripts/tmin.php','token admin completed ...','0.00258 seconds via PHP CURL '),(8372,1431616488,'http://crowdcc.apc/shed/scripts/smin.php','session admin completed ...','0.00385 seconds via PHP CURL '),(8371,1431592099,'http://crowdcc.apc/shed/scripts/tmin.php','token admin completed ...','0.00191 seconds via PHP CURL '),(8370,1431592099,'http://crowdcc.apc/shed/scripts/smin.php','session admin completed ...','0.01344 seconds via PHP CURL '),(8369,1431519501,'http://crowdcc.apc/shed/scripts/tmin.php','token admin completed ...','1.08901 seconds via PHP CURL '),(8368,1431519500,'http://crowdcc.apc/shed/scripts/smin.php','session admin completed ...','2.97300 seconds via PHP CURL '),(8367,1431369045,'http://crowdcc.apc/shed/scripts/tmin.php','token admin completed ...','0.00201 seconds via PHP CURL '),(8366,1431369045,'http://crowdcc.apc/shed/scripts/smin.php','session admin completed ...','0.00675 seconds via PHP CURL '),(8365,1431368574,'http://crowdcc.apc/shed/scripts/tmin.php','token admin completed ...','0.00205 seconds via PHP CURL '),(8364,1431368574,'http://crowdcc.apc/shed/scripts/smin.php','session admin completed ...','0.00622 seconds via PHP CURL '),(8363,1431368494,'http://crowdcc.apc/shed/scripts/tmin.php','token admin completed ...','0.02808 seconds via PHP CURL '),(8362,1431368494,'http://crowdcc.apc/shed/scripts/smin.php','session admin completed ...','0.63957 seconds via PHP CURL '),(8361,1431098483,'http://crowdcc.apc/shed/scripts/tmin.php','token admin completed ...','0.00598 seconds via PHP CURL '),(8360,1431098483,'http://crowdcc.apc/shed/scripts/smin.php','session admin completed ...','0.05531 seconds via PHP CURL '),(8359,1431011040,'http://crowdcc.apc/shed/scripts/tmin.php','token admin completed ...','0.00310 seconds via PHP CURL '),(8358,1431011040,'http://crowdcc.apc/shed/scripts/smin.php','session admin completed ...','0.00557 seconds via PHP CURL ');
/*!40000 ALTER TABLE `phpjobscheduler_logs` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-05-30  0:10:43
