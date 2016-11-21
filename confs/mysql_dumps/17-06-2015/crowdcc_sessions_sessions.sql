CREATE DATABASE  IF NOT EXISTS `crowdcc_sessions` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `crowdcc_sessions`;
-- MySQL dump 10.13  Distrib 5.6.13, for osx10.6 (i386)
--
-- Host: localhost    Database: crowdcc_sessions
-- ------------------------------------------------------
-- Server version	5.5.42

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
  `ip_address` varchar(16) NOT NULL,
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
INSERT INTO `sessions` VALUES ('h9k9d3e1vbntvi9udeo9fdpc7tq2ajkqh63f54i6lacm7rlkdobjdkcplnc4mn2v1fbng6gts4v0gaqsesshd9hhmd5a58mkuqgfu10','127.0.0.1','1434490016','43MxjNTv2FUOX4EYMvSobgpiYcdjhGl9OdvMpssqUpybKbOJJlZPldEbUOas9RQ+cBOLl+oeeILFuSxZoHvHUIRO0uEGj9b9CP3g84YbDMra47gl6wepN4FpNQ6rBSIp6jq+xOhwE7xOQdqgBhT8MsfjbSYz8bHFyVRIWznmb/dtY4I7+ARuH3swbuUX57XdDKs8doM91diPv6LDkvE3iBa8MG+oUyjldyxkrTT4xg+G6MAQhC2oGmY8Xzw5QtJ1+7Lq+0J6nJsRNsLlS1saPwZ4BCn31vCo3un+mo22gQXsGzAsVqlCN3Ob31AUGTbUIyNicR4/+cdOHem38ni9yT8Bd30dtJRAlmkt44JFpzo2wKtYlhpfM1KhUeJBmxVQqHsTlubN063Jz8ggoqwRXk2zFuINS/qX7G1xG2AxQUuN04tupu9W5F88KQRIHw+jioLRgROaLE6XBvstRXvdzg==','7163ddf2ccbf916e4ab7ff260239281c9a9456db12c7ac86752d05dee201c2dca19c9b05b33ab1171a772d870ef952fe421d6dff9b672ebb53f807c0bb4eebbe'),('nl623vn1nk2gsm5813bh5t8vf1f1qiaf8mk44apc5su6bgjclibbc4hunulbakph5mh4os6c4ri4o23506grjsp1b782kj0hk9c8tt3','127.0.0.1','1434491177','UpXOM1bWHX1auoyNE58c/Ugl+UuRK/S2g41Gx8mkDfT1xiOJ66BXyXaqwrVutGTUgT5TkiByXEtT2MI5/pbnXMD0hkpMH0NnuMs/37d+EoWQpy2GSzzq7+Pyov52834Ti8LpAX7xShviCeIu2jnT43DlYNEdJXL/eRcxxzY3znEJiFcd83JlPQ8K84H401uuaCy+kz08Vpd2OuvDfU89PWlDHbIivHWRzfT2E0CGkpYYPFLbomjQ5yOxb6e0IXpppGRhSNbk2XQnxpg1h9i3WBRX1uamYGuAoSwl2LXY2L01p6It0lSxqLq14BMVlQQNTXfIVGm8ivv9a6wvplnlg3XvSnnS6HEFPZ4LdSV43WhTR+qwQXPbe3636vyZj3lGBwWvGGArvQDyIjIJxJQRqBfivZbIMm7G0vDGnVFT20oDWFzxJHUA0Tq8DL8xNjK0/GfwmvFt24E7wWRtkP9BIQ==','e471a60d0939c1da2dc9f27dedf1c8d5e881e7b2888c323a16f1fe05ad6e34494573bf80f144051d45f5b77bfe5b1b7ba2978c10e326e0568e45df57e4dc4056'),('5n0jrsi6hmkibh9erl7jhq5ktqngha2apmhb7rg8g512tdfq2u6fap8vmsj8jq9jscc9gfmm4alevldealhp7fgeq7hoh2ngm0dmqv2','127.0.0.1','1434491585','13KZYJkJBcoEW5a59RcIONdfthgbmxi//QlVbmkA+3KkKoQdl6JBEx46BDD3TnKMpCBpMcAJVH1vKyp/feHLx9ogBUfH4Px6AgBBEBjoI1C3vRamd9bGzlVCwlwxS6F35DNHXSgnDoHSuDv98eeKcuC4b9b9WsMSbeuZAV1Eeujmw10lK2CXdoXe5wBFfq3shijGqWooYzjNDn3Qo0IRaqPKQFhsKyA3PXsdsPkRTX0SpWki7k3OSdlVmOfaKzuFbTp4areEUOTwQuKy/8uJJ2LtlSGjFXaonlbLbJ+kr2E1NiVVaUrhkhfmYx0VlEzYWHUfl0IiII+03Kf3m95vgh0ApfLWMAdiVXiNqseWlZmA11jkGle5XI29CC6oQzBY2fHcYqV7qQSr7uHc6vBaJSGzcQp5/ppZRIKSyj8fuxv373wzhf4aqiJIC0ol14Gbk8U9J4RV5akfSLaxQRq4wg==','21528d51b6a48dfe18684d2850b5b7280311f8ea8aea580afa8594bc272b7edaf4ce40dce4e8b5dc618544526a81100f916decbf4b666d134e106cfa1b70f71e'),('hv5brn6t1abhbm3j4b9u52h3puj3r6p8qqevkdujcvtb7rjn2t6041kasta9p6qae5q55atdfbbqk5j3dpchhb7hqvu9os64gtna3v0','127.0.0.1','1434491624','isCdc9ebaddNWlzB6o1iX/JpCJ5JrkUNsfHVLzxClGWw981demPQ1+sImbhb9UxTuwrW4VlgGVDoPEN3S73RoM33CYXtb8NMO6EOhKsjXDm0xgCK4qiJQWxk0bCMEU/V5cdEgKLRm4rxCctJxAP2eLtDnDrnK8Q9+iSH50FCdSXRRrWkQA1KFywIMiK55Q9/wzDyxk+/gzMTZbuxa9/q9dJNAkOfwXrQ1UV0AypSxHSnGjhwiv9MlxfscMeAkKoem2Q1SRb1zpoIqiJ7Oa5cAyfiy2KVX6c+KGw+xqWCHKO3OkSC9UNiFYBqnulqlTK7+yaTeO8VTbHSoaJM/RVJSg56uRnmp8AQgmPdglxrKy04oBiRqqA13J56lgB9s3JupFpP3HmEfGabFm+Dgs9nr4qswWEu2ZiSzsrhoa7NZQ0X1rbDMA0/siSWneQ6W3D7jhIMNNRIiYpAARJnZ+b9DmKkTqs5wlieJUcH3IaqAkl+P6CXQv3mpcXp8YpC0EjIcbPicodn2eddUTLOPgHjes1YI9odZT78wNmyygAYtcL5N/Yac1Mas2No6dllH0suAnXRoJ1Hk6MZnhTPA3CxKjXbXlKTbjMuWhLV6ZWPa1fw+Up8Pq9BEQH5jEswm2fvxuFZeRrAKZl4r8mzTem/bjJTLS6L58De6gX6nLwTZLlq2Aqst6J1c5q0t/qHh7LSRw5CHhZ374vdnO4ipjepovtgiD89gT8wW4Mpd/LqfdGwdc+FSX+okEDkUi0b8Rz2wGtyIInT5VX4qfMthtRqbQtVB5R42REIOrKWlPy5iCtMmYr2F8ukz89T06/fbUOVFpoC973cR84szcWq07Dj0Q==','e8cfb74aab509b5af2b176dddbaed89f2d6f37a4a81b1a38d665d5d41d776b9d9cd8c337aae0b1ddb44a0a7f41d50f1b60167cf6820ca7fada8e805c18f88785');
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

-- Dump completed on 2015-06-17 14:13:24
