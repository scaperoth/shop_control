CREATE DATABASE  IF NOT EXISTS `shop_control` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `shop_control`;
-- MySQL dump 10.13  Distrib 5.5.16, for Win32 (x86)
--
-- Host: localhost    Database: shop_control
-- ------------------------------------------------------
-- Server version	5.1.68-community

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
-- Table structure for table `location_change_tracking`
--

DROP TABLE IF EXISTS `location_change_tracking`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `location_change_tracking` (
  `lct_id` int(11) NOT NULL AUTO_INCREMENT,
  `lct_location` varchar(45) DEFAULT NULL,
  `lct_user` varchar(45) DEFAULT NULL,
  `lct_action` varchar(45) DEFAULT NULL,
  `lct_on_time` int(11) DEFAULT NULL,
  `lct_message` text,
  `lct_timestamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`lct_id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `location_change_tracking`
--

LOCK TABLES `location_change_tracking` WRITE;
/*!40000 ALTER TABLE `location_change_tracking` DISABLE KEYS */;
INSERT INTO `location_change_tracking` VALUES (41,'localhost','Matt mscapero-user','close',0,'Matt mscapero-user closed the localhost shop early by 1 hours 39 minutes and 34 seconds','2013-08-25 20:10:26'),(42,'localhost','Matt mscapero-user','open',0,'Matt mscapero-user opened the localhost shop late. Latest opening time is 08:10am. Late by: 8 hours 0 minutes and 47 seconds','2013-08-25 20:10:47'),(43,'localhost','Matt mscapero-user','open',0,'Matt mscapero-user opened the localhost shop late. Latest opening time is 08:10am. Late by: 2 hours 16 minutes and 20 seconds','2013-09-05 14:26:20'),(44,'localhost','Matt mscapero-user','close',0,'Matt mscapero-user closed the localhost shop early by 7 hours 23 minutes and 38 seconds','2013-09-05 14:26:22'),(45,'localhost','Matt mscapero-user','open',0,'Matt mscapero-user opened the localhost shop late. Latest opening time is 08:10am. Late by: 2 hours 21 minutes and 44 seconds','2013-09-05 14:31:44');
/*!40000 ALTER TABLE `location_change_tracking` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-10-16  6:48:41
