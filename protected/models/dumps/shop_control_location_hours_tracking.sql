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
-- Table structure for table `location_hours_tracking`
--

DROP TABLE IF EXISTS `location_hours_tracking`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `location_hours_tracking` (
  `lht_id` int(11) NOT NULL AUTO_INCREMENT,
  `lht_username` varchar(45) NOT NULL,
  `lht_loc_name` varchar(45) NOT NULL,
  `lht_mon_open_hrs` time NOT NULL,
  `lht_mon_closed_hrs` time NOT NULL,
  `lht_tue_open_hrs` time NOT NULL,
  `lht_tue_closed_hrs` time NOT NULL,
  `lht_wed_open_hrs` time NOT NULL,
  `lht_wed_closed_hrs` time NOT NULL,
  `lht_thu_open_hrs` time NOT NULL,
  `lht_thu_closed_hrs` time NOT NULL,
  `lht_fri_open_hrs` time NOT NULL,
  `lht_fri_closed_hrs` time NOT NULL,
  `lht_sat_open_hrs` time NOT NULL,
  `lht_sat_closed_hrs` varchar(45) NOT NULL,
  `lht_sun_open_hrs` time NOT NULL,
  `lht_sun_closed_hrs` time NOT NULL,
  `lht_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`lht_id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `location_hours_tracking`
--

LOCK TABLES `location_hours_tracking` WRITE;
/*!40000 ALTER TABLE `location_hours_tracking` DISABLE KEYS */;
INSERT INTO `location_hours_tracking` VALUES (37,'Matt Scaperoth','localhost','09:30:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','21:00:00','08:00:00','18:00:00','2013-08-25 20:15:52'),(38,'Matt Scaperoth','localhost','09:30:00','18:30:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','21:00:00','08:00:00','18:00:00','2013-08-25 20:16:11'),(39,'Matt Scaperoth','Funger','07:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','2013-08-25 20:16:43'),(40,'Matt Scaperoth','localhost','09:30:00','19:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','21:00:00','08:00:00','18:00:00','2013-08-25 20:17:02'),(41,'Matt Scaperoth','localhost','09:00:00','19:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','21:00:00','08:00:00','18:00:00','2013-08-25 20:24:49'),(42,'Matt Scaperoth','localhost','09:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','21:00:00','08:00:00','18:00:00','2013-08-25 20:25:00'),(43,'Matt Scaperoth','localhost','09:00:00','17:30:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','21:00:00','08:00:00','18:00:00','2013-08-25 20:25:22'),(44,'Matt Scaperoth','localhost','09:00:00','17:30:00','07:30:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','21:00:00','08:00:00','18:00:00','2013-08-25 22:59:49'),(45,'Matt Scaperoth','Gelman','08:00:00','18:00:00','08:00:00','19:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','2013-08-25 22:59:49'),(46,'Matt Scaperoth','E-Street','08:30:00','21:30:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','2013-08-25 23:08:40'),(47,'Matt scapero-admin','Funger','06:30:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','2013-08-25 23:20:11'),(48,'Matt Scaperoth','E-Street','08:30:00','21:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','2013-08-26 00:02:15'),(49,'Matt Scaperoth','Funger','06:30:00','17:30:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','2013-08-26 00:03:11'),(50,'Matt Scaperoth','E-Street','08:00:00','21:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','2013-08-26 00:04:27');
/*!40000 ALTER TABLE `location_hours_tracking` ENABLE KEYS */;
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
