CREATE DATABASE  IF NOT EXISTS `crowdcc_sessions` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `crowdcc_sessions`;
-- MySQL dump 10.13  Distrib 5.6.13, for osx10.6 (i386)
--
-- Host: 127.0.0.1    Database: crowdcc_sessions
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
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `session_id` char(128) NOT NULL,
  `ip_address` varchar(200) NOT NULL,
  `set_time` char(10) NOT NULL,
  `data` text NOT NULL,
  `session_key` char(128) NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('5psohsimn18lpajdoarfckclnre7a066n95pq72c8vsp8mrd3gunq13555lmr8kk0ktdnsu19qu4kvktlgpod87mvka3cupciajbd13','127.0.0.1','1414086531','c49OmFI2LwvUECz/LZ8jcZJ9lpzYlPTSxbM4rb3RdgHZf3gAB1909b6tC17aXJL1DJzNoDcnyyNcE3SLT5DLJ8UIIADX44QXntVT8RZ7ZRVRvOe5aGwh+VYdr91rpSLXZa0bCkGSx9JSTMZZSQ6+2YpplIRjCBErgATUhQYbMTSEAWKmXDaYMvYX9CYnctZdF/bNapx4g3x0J/YI0cccln7atQOSkvEoASLyYWEamdAKgM7XZFJu36FUMysSEfq5DYS0ivHS9IYXpHyZr+mDr+Fsxyh3pTC+2aJU0XYZao6tormOg/YIAw+eOHUPtXbPrWPsSrLuygUqGoQA/cwRwviZwWikktrTvzO2i1l77wBRjuw30VUqkxh/VkFw4IRxN19PAS0fLjU8IHYAykP1gBgBgxAaXDnrqdfsiLwhTpp/6ZZ5EU8iMTXsnhr7XSy+d8DrY6JnWDnRZfbZqu3XGLB4Js7cVKW/PdMz2Z4a/qiWaKxusLmeDspRnbyGfQcLVHbdms8DGLIlWaOMLixYb2p/cR6TmzgkUDMcvOH2rur4hk7kk3wpe35oJi83jahbi2C73A6W1FnYA4Gwq9ZiWSTLIrb4UseN7nZq9GNgrvTuEZtni8q1z3+kMcfu1oMPabnSGV/gf6NDLDO90csYvId2MH9+ejbxhzu3i4W4e+s=','6780e524a7a6b36319252fc9d49ed95c1b9a766cce845e0a3ca4f3f92519aca241fe3d027eb417d139f0c83fd5f138d2830317a9ef328c11ebce791fd868bed3');
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-10-24 12:16:39
