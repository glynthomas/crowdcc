CREATE DATABASE  IF NOT EXISTS `crowdcc_api` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `crowdcc_api`;
-- MySQL dump 10.13  Distrib 5.6.13, for osx10.6 (i386)
--
-- Host: 127.0.0.1    Database: crowdcc_api
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
-- Table structure for table `tweets`
--

DROP TABLE IF EXISTS `tweets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tweets` (
  `media_id` bigint(11) NOT NULL AUTO_INCREMENT,
  `time` int(10) unsigned NOT NULL,
  `tweet_id` varchar(20) NOT NULL,
  `from_user_id` varchar(10) NOT NULL,
  `tweet_owner` varchar(40) NOT NULL,
  `tweet_create_date` varchar(100) NOT NULL,
  `tweet_text` varchar(300) NOT NULL,
  `source` varchar(200) NOT NULL,
  `source_url` varchar(200) NOT NULL,
  `retweet_count` varchar(10) NOT NULL,
  `favorite_count` varchar(10) NOT NULL,
  `from_user` varchar(80) NOT NULL,
  `from_user_name` varchar(80) NOT NULL,
  `from_location` varchar(80) NOT NULL,
  `from_description` varchar(160) NOT NULL,
  `from_url` varchar(50) NOT NULL,
  `followers_count` varchar(50) NOT NULL,
  `friends_count` varchar(50) NOT NULL,
  `listed_count` varchar(50) NOT NULL,
  `created_at` varchar(50) NOT NULL,
  `favourites_count` varchar(50) NOT NULL,
  `time_zone` varchar(50) NOT NULL,
  `statuses_count` varchar(50) NOT NULL,
  `profile_image_url` varchar(300) NOT NULL,
  `entities_urls` varchar(100) NOT NULL,
  `entities_hashtags` varchar(100) NOT NULL,
  `entities_media_url` varchar(100) NOT NULL,
  `entities_url` varchar(100) NOT NULL,
  PRIMARY KEY (`media_id`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tweets`
--

LOCK TABLES `tweets` WRITE;
/*!40000 ALTER TABLE `tweets` DISABLE KEYS */;
INSERT INTO `tweets` VALUES (1,0,'505385166400159744','816653','glynthom','Fri Aug 29 16:03:07 +0000 2014','What\\\'s that? TechCrunch is giving away another free ticket to Disrupt San Francisco?!? http://t.co/awIkkdSqkQ http://t.co/pmpweR42c5','SocialFlow','<a href=\\\"http://www.socialflow.com\\\" rel=\\\"nofollow\\\">SocialFlow</a>','26','9','TechCrunch','TechCrunch','San Francisco, CA','Breaking Technology News And Opinions From TechCru','http://t.co/FQzFJNIg8e','3793826','874','90662','Wed Mar 07 01:27:09 +0000 2007','150','Pacific Time (US & Canada)','79223','http://pbs.twimg.com/profile_images/469171480832380928/rkZR1jIh_normal.png','http://tcrn.ch/1wO7ngQ','false','http://pbs.twimg.com/media/BwN9IYiIIAEMUex.jpg','http://t.co/pmpweR42c5'),(2,0,'505322983662301184','17648193','glynthom','Fri Aug 29 11:56:02 +0000 2014','RT @CreativeBloq: The 5 biggest logo designs of August 2014 http://t.co/5Ncq3sCUZg','TweetDeck','<a href=\\\"https://about.twitter.com/products/tweetdeck\\\" rel=\\\"nofollow\\\">TweetDeck</a>','3','0','netmag','netmag','Bath, UK','The No 1 magazine for web designers & developers. ','http://t.co/d5OIqalZna','69677','3989','4194','Wed Nov 26 12:08:34 +0000 2008','949','London','11615','http://pbs.twimg.com/profile_images/378800000697523305/d556bef753c6ac3933e9d5bf9f147418_normal.jpeg','http://www.creativebloq.com/logo-design/august-2014-81412801','false','false','false'),(3,0,'505385819302268928','14335498','glynthom','Fri Aug 29 16:05:43 +0000 2014','Chartist – Simple responsive charts http://t.co/XsETaQ4EWk','newsycombinator','<a href=\\\"http://www.steer.me\\\" rel=\\\"nofollow\\\">newsycombinator</a>','5','9','newsycombinator','newsycombinator','The Internet','I\\\'m a news.ycombinator bot, get the latest from H','http://t.co/gliZLgXpD1','80535','1','5297','Tue Apr 08 19:58:28 +0000 2008','0','London','65258','http://pbs.twimg.com/profile_images/469397708986269696/iUrYEOpJ_normal.png','http://gionkunz.github.io/chartist-js/','false','false','false'),(4,0,'505385818266279936','14335498','glynthom','Fri Aug 29 16:05:43 +0000 2014','Inside One of the World\\\'s Largest Bitcoin Mines http://t.co/bN31gbsU3o','newsycombinator','<a href=\\\"http://www.steer.me\\\" rel=\\\"nofollow\\\">newsycombinator</a>','7','4','newsycombinator','newsycombinator','The Internet','I\\\'m a news.ycombinator bot, get the latest from H','http://t.co/gliZLgXpD1','80535','1','5297','Tue Apr 08 19:58:28 +0000 2008','0','London','65258','http://pbs.twimg.com/profile_images/469397708986269696/iUrYEOpJ_normal.png','http://www.thecoinsman.com/2014/08/bitcoin/inside-one-worlds-largest-bitcoin-mines/','false','false','false'),(5,0,'506714915424722946','15743570','glynthom','Tue Sep 02 08:07:04 +0000 2014','Braintree payments on Rails: http://t.co/ub8czxe5xo http://t.co/BNnOEnCGy4','Buffer','<a href=\\\"http://bufferapp.com\\\" rel=\\\"nofollow\\\">Buffer</a>','3','6','sitepointdotcom','sitepointdotcom','Melbourne, Australia','THE online resource for web designers and develope','http://t.co/SMY6FtTXBz','120223','44033','6941','Wed Aug 06 00:02:41 +0000 2008','285','Melbourne','15746','http://pbs.twimg.com/profile_images/443563096318033920/tQPyOEvM_normal.png','http://buff.ly/1qVKgby','false','http://pbs.twimg.com/media/Bwg2h91IYAAeKPY.jpg','http://t.co/BNnOEnCGy4'),(18,1410797151,'511520275293171712','816653','glynthom','Mon Sep 15 14:21:51 +0000 2014','Qualcomm Quietly Acquires AI-Based Image Recognition Startup Euvision http://t.co/drrSSPvM2o by @ingridlunden','10up Publish Tweet','<a href=\\\"http://10up.com\\\" rel=\\\"nofollow\\\">10up Publish Tweet</a>','0','0','TechCrunch','TechCrunch','San Francisco, CA','Breaking Technology News And Opinions From TechCru','http://t.co/FQzFJNIg8e','3877688','897','91109','Wed Mar 07 01:27:09 +0000 2007','242','Pacific Time (US & Canada)','80622','http://pbs.twimg.com/profile_images/469171480832380928/rkZR1jIh_normal.png','http://tcrn.ch/1DaNj9f','false','false','false'),(7,0,'504723877453643777','816653','glynfoo','Wed Aug 27 19:48:35 +0000 2014','How Many People See Your Tweets? Twitter Opens Its Nifty Analytics Dashboard To Everyone http://t.co/RZTDchEWNf by @grg','10up Publish Tweet','<a href=\\\"http://10up.com\\\" rel=\\\"nofollow\\\">10up Publish Tweet</a>','76','39','TechCrunch','TechCrunch','San Francisco, CA','Breaking Technology News And Opinions From TechCru','http://t.co/FQzFJNIg8e','3786488','873','90586','Wed Mar 07 01:27:09 +0000 2007','144','Pacific Time (US & Canada)','79055','http://pbs.twimg.com/profile_images/469171480832380928/rkZR1jIh_normal.png','http://tcrn.ch/XTbXel','false','false','false'),(8,0,'504723877453643777','17648193','glynthom','Wed Aug 27 20:15:24 +0000 2014','What exactly is flat design and why is it so popular? http://t.co/0yFAJlAohS','Hootsuite','<a href=\\\"http://www.hootsuite.com\\\" rel=\\\"nofollow\\\">Hootsuite</a>','5','6','netmag','netmag','Bath, UK','The No 1 magazine for web designers & developers. ','http://t.co/d5OIqalZna','69582','3989','4187','Wed Nov 26 12:08:34 +0000 2008','949','London','11607','http://pbs.twimg.com/profile_images/378800000697523305/d556bef753c6ac3933e9d5bf9f147418_normal.jpeg','http://www.creativebloq.com/graphic-design/what-flat-design-3132112','false','false','false'),(9,0,'504723877453643777','15743570','glynthom','Thu Aug 28 06:48:03 +0000 2014','How to use lean development to quickly tell if your startup will fail: http://t.co/mbBFyA5aaC','Buffer','<a href=\\\"http://bufferapp.com\\\" rel=\\\"nofollow\\\">Buffer</a>','6','10','sitepointdotcom','sitepointdotcom','Melbourne, Australia','THE online resource for web designers and develope','http://t.co/SMY6FtTXBz','120055','44035','6940','Wed Aug 06 00:02:41 +0000 2008','284','Melbourne','15677','http://pbs.twimg.com/profile_images/443563096318033920/tQPyOEvM_normal.png','http://buff.ly/XTNTrQ','false','false','false'),(17,1410785459,'511491584618008576','816653','glynthom','Mon Sep 15 12:27:51 +0000 2014','Wikileaks Releases Spyware The German Government Used To Hack Journalists And Dissidents http://t.co/Rk3wBLj2Gi by @johnbiggs','10up Publish Tweet','<a href=\\\"http://10up.com\\\" rel=\\\"nofollow\\\">10up Publish Tweet</a>','8','3','TechCrunch','TechCrunch','San Francisco, CA','Breaking Technology News And Opinions From TechCru','http://t.co/FQzFJNIg8e','3877297','897','91111','Wed Mar 07 01:27:09 +0000 2007','242','Pacific Time (US & Canada)','80605','http://pbs.twimg.com/profile_images/469171480832380928/rkZR1jIh_normal.png','http://tcrn.ch/1nXcdPI','false','false','false'),(11,0,'506543525618806784','816653','glynthom','Mon Sep 01 20:46:02 +0000 2014','Typing Writer turns your iPad into a typewriter, and no it wasn\\\'t created by Tom Hanks http://t.co/K8ypKE8Qdp','SocialFlow','<a href=\\\"http://www.socialflow.com\\\" rel=\\\"nofollow\\\">SocialFlow</a>','20','9','TechCrunch','TechCrunch','San Francisco, CA','Breaking Technology News And Opinions From TechCru','http://t.co/FQzFJNIg8e','3806704','875','90737','Wed Mar 07 01:27:09 +0000 2007','150','Pacific Time (US & Canada)','79366','http://pbs.twimg.com/profile_images/469171480832380928/rkZR1jIh_normal.png','http://tcrn.ch/1py6aX4','false','false','false'),(12,1410536276,'510371397219389441','17648193','glynthom','Fri Sep 12 10:16:38 +0000 2014','What\\\'s the best blog / book / article / YouTube video you\\\'ve seen about winning pitches?','TweetDeck','<a href=\\\"https://about.twitter.com/products/tweetdeck\\\" rel=\\\"nofollow\\\">TweetDeck</a>','0','0','netmag','netmag','Bath, UK','The No 1 magazine for web designers & developers. ','http://t.co/d5OIqalZna','70230','3989','4202','Wed Nov 26 12:08:34 +0000 2008','965','London','11805','http://pbs.twimg.com/profile_images/378800000697523305/d556bef753c6ac3933e9d5bf9f147418_normal.jpeg','false','false','false','false'),(13,1410538746,'510458006845546496','14335498','glynthom','Fri Sep 12 16:00:47 +0000 2014','Researchers claim hydrogen energy advance http://t.co/s6TF1UUuu0','newsycombinator','<a href=\\\"http://www.steer.me\\\" rel=\\\"nofollow\\\">newsycombinator</a>','3','3','newsycombinator','newsycombinator','The Internet','I\\\'m a news.ycombinator bot, get the latest from H','http://t.co/gliZLgXpD1','81409','1','5328','Tue Apr 08 19:58:28 +0000 2008','0','London','65676','http://pbs.twimg.com/profile_images/469397708986269696/iUrYEOpJ_normal.png','http://www.bbc.com/news/uk-scotland-scotland-politics-29168382','false','false','false'),(16,1410542829,'510446020384145408','17648193','glynthom','Fri Sep 12 15:13:09 +0000 2014','7 design tips to attract more visitors to your websites http://t.co/8Ie35yhaY8','TweetDeck','<a href=\\\"https://about.twitter.com/products/tweetdeck\\\" rel=\\\"nofollow\\\">TweetDeck</a>','5','5','netmag','netmag','Bath, UK','The No 1 magazine for web designers & developers. ','http://t.co/d5OIqalZna','70260','3989','4202','Wed Nov 26 12:08:34 +0000 2008','972','London','11815','http://pbs.twimg.com/profile_images/378800000697523305/d556bef753c6ac3933e9d5bf9f147418_normal.jpeg','http://www.creativebloq.com/7-design-tips-attract-more-visitors-your-websites','false','false','false'),(15,1410541437,'510458006115737601','14335498','glynthom','Fri Sep 12 16:00:47 +0000 2014','New Requests for Startups http://t.co/4p5NzJNCKc','newsycombinator','<a href=\\\"http://www.steer.me\\\" rel=\\\"nofollow\\\">newsycombinator</a>','2','5','newsycombinator','newsycombinator','The Internet','I\\\'m a news.ycombinator bot, get the latest from H','http://t.co/gliZLgXpD1','81409','1','5328','Tue Apr 08 19:58:28 +0000 2008','0','London','65676','http://pbs.twimg.com/profile_images/469397708986269696/iUrYEOpJ_normal.png','http://blog.ycombinator.com/new-requests-for-startups','false','false','false'),(19,1410797248,'511523789839298560','816653','glynthom','Mon Sep 15 14:35:49 +0000 2014','RT @mikebutcher: Given how often startups START on US university campuses, I really wonder why Euro startups don\\\'t them more often http://t.co/C1LrnK3faB','SocialFlow','<a href=\\\"http://www.socialflow.com\\\" rel=\\\"nofollow\\\">SocialFlow</a>','9','0','TechCrunch','TechCrunch','San Francisco, CA','Breaking Technology News And Opinions From TechCru','http://t.co/FQzFJNIg8e','3877738','897','91110','Wed Mar 07 01:27:09 +0000 2007','242','Pacific Time (US & Canada)','80623','http://pbs.twimg.com/profile_images/469171480832380928/rkZR1jIh_normal.png','http://techcrunch.com/2014/02/05/khosla-leads-a-10-5m-round-for-tapingo-to-bring-mobile-food-orderin','false','false','false'),(27,1410975346,'512280784724828160','295131454','glynthom','Wed Sep 17 16:43:51 +0000 2014','1. I like FB Messenger. 2. I don\'t like lookin at FB to use it. 3. Here\'s how you can use FB Messenger without FB:  https://t.co/6mPbHyz96K','Twitter Web Client','<a href=\\\"http://twitter.com\\\" rel=\\\"nofollow\\\">Twitter Web Client</a>','0','0','glynthom','glynthom','wimbledon, London.','...developer','http://t.co/0hRRgIxSCC','186','65','7','Sun May 08 12:33:46 +0000 2011','360','London','2156','http://pbs.twimg.com/profile_images/440428562546298880/7bGYRdMN_normal.png','https://gist.github.com/cmod/1d00fc552d21d5dfdd65','false','false','false'),(23,1410814753,'511581465474498560','15743570','glynthom','Mon Sep 15 18:25:00 +0000 2014','Creating Autocomplete datalist Controls : http://t.co/AIcFoKE2uw','Buffer','<a href=\\\"http://bufferapp.com\\\" rel=\\\"nofollow\\\">Buffer</a>','0','10','sitepointdotcom','sitepointdotcom','Melbourne, Australia','THE online resource for web designers and develope','http://t.co/SMY6FtTXBz','120536','44018','7000','Wed Aug 06 00:02:41 +0000 2008','303','Melbourne','15946','http://pbs.twimg.com/profile_images/443563096318033920/tQPyOEvM_normal.png','http://buff.ly/1y7HF7I','false','false','false'),(28,1410975604,'512286835067088896','15948437','glynthom','Wed Sep 17 17:07:53 +0000 2014','RT @memoir: NEW Memoir, NEW design! Now it\'s even easier to relive memories &amp; share photos! @Apple #iOS8 https://t.co/5SDf12RMuf. http://t.co/iV0YgGxr6L','Twitter Web Client','<a href=\\\"http://twitter.com\\\" rel=\\\"nofollow\\\">Twitter Web Client</a>','2','0','spolsky','spolsky','New York, NY','Co-founder of Fog Creek Software (Trello, FogBugz, Kiln) and Stack Exchange (Stack Overflow etc). Member of NYC gay startup mafia.','http://t.co/s1Nfo3C6Z8','96262','442','5280','Fri Aug 22 18:34:03 +0000 2008','3383','Eastern Time (US & Canada)','5578','http://pbs.twimg.com/profile_images/451185078073171968/T4QKBj-E_normal.jpeg','https://itunes.apple.com/us/app/memoir/imt=8&pid=5&cid=version_2_launch','false','false','false'),(29,1410975686,'512284130231726080','17648193','glynthom','Wed Sep 17 16:57:09 +0000 2014','Our new issue features @monteiro, @dburka, @owltastic, @lornajane, @benschwarz, @joannecheng &amp; more. What a lineup!  http://t.co/qeLiI6QtmI','TweetDeck','<a href=\\\"https://about.twitter.com/products/tweetdeck\\\" rel=\\\"nofollow\\\">TweetDeck</a>','2','1','netmag','netmag','Bath, UK','The No 1 magazine for web designers & developers. The voice of web design. Established in 1994. We also run the Generate conference & the annual #netawards.','http://t.co/d5OIqalZna','70472','3991','4213','Wed Nov 26 12:08:34 +0000 2008','1004','London','11864','http://pbs.twimg.com/profile_images/378800000697523305/d556bef753c6ac3933e9d5bf9f147418_normal.jpeg','http://www.creativebloq.com/netmag/discover-wordpress-40s-new-features-latest-net-magazine-91412924','false','false','false'),(30,1411131232,'512755679518404608','35432643','glynthom','Fri Sep 19 00:10:55 +0000 2014','Runnable JS, CSS &amp; HTML snippets arrive on Stack Overflow http://t.co/em1b6Bw6P7 with revision history &amp; an editor http://t.co/4QTW9dBjhr','Twitter for Android','<a href=\\\"http://twitter.com/download/android\\\" rel=\\\"nofollow\\\">Twitter for Android</a>','300','163','addyosmani','addyosmani','London, England','Engineer at Google • Author • Chrome, Polymer • Creator of TodoMVC, @Yeoman, Web Starter Kit & others • Passionate about web tooling','http://t.co/Ss8VpfPH4Z','76128','1292','4031','Sun Apr 26 08:40:11 +0000 2009','8274','Pacific Time (US & Canada)','9799','http://pbs.twimg.com/profile_images/422476220442234880/jlx9HMtr_normal.jpeg','http://blog.stackoverflow.com/2014/09/introducing-runnable-javascript-css-and-html-code-snippets/','false','http://pbs.twimg.com/media/Bx2skpdCMAAWsQb.png','http://t.co/4QTW9dBjhr'),(31,1411140084,'512855364027826176','15736190','glynthom','Fri Sep 19 06:47:01 +0000 2014','RT @addyosmani: Published slides for CSS Performance Tooling: https://t.co/nmvRBqoKIl my @cssconfeu talk on automating optimisation http://t.co/krd6nfZRBw','Twitter Web Client','<a href=\\\"http://twitter.com\\\" rel=\\\"nofollow\\\">Twitter Web Client</a>','367','0','smashingmag','smashingmag','Freiburg, Germany','Vitaly Friedman, editor-in-chief of Smashing Magazine, an online magazine for professional Web designers and developers.','http://t.co/GWd3gP4kCk','816667','1182','38340','Tue Aug 05 14:00:40 +0000 2008','376','Greenland','37053','http://pbs.twimg.com/profile_images/477218012194304000/ytI5hY2H_normal.png','https://speakerdeck.com/addyosmani/css-performance-tooling','false','http://pbs.twimg.com/media/BxZzR9bCEAAH2Oe.png','http://t.co/krd6nfZRBw'),(32,1411141624,'512987113261105152','15743570','glynthom','Fri Sep 19 15:30:33 +0000 2014','Via today\'s Versioning: a JavaScript library for transformation of data - Transducers.js:  http://t.co/zmOCqVuIsR @jlongster','Buffer','<a href=\\\"http://bufferapp.com\\\" rel=\\\"nofollow\\\">Buffer</a>','0','0','sitepointdotcom','sitepointdotcom','Melbourne, Australia','THE online resource for web designers and developers. Follow us for web news, tips, freebies, deals & more. Hosted by @adam__roberts and @tomtrumble','http://t.co/QRQtSi0ZbU','120665','44011','7003','Wed Aug 06 00:02:41 +0000 2008','308','Melbourne','16000','http://pbs.twimg.com/profile_images/443563096318033920/tQPyOEvM_normal.png','http://buff.ly/XsrwIT','false','false','false');
/*!40000 ALTER TABLE `tweets` ENABLE KEYS */;
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
