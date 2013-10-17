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
-- Table structure for table `locations`
--

DROP TABLE IF EXISTS `locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `locations` (
  `loc_id` int(11) NOT NULL AUTO_INCREMENT,
  `loc_name` varchar(65) NOT NULL,
  `loc_status` int(11) NOT NULL DEFAULT '0',
  `loc_mon_open_hrs` time NOT NULL DEFAULT '08:00:00',
  `loc_mon_closed_hrs` time NOT NULL DEFAULT '18:00:00',
  `loc_tue_open_hrs` time NOT NULL DEFAULT '08:00:00',
  `loc_tue_closed_hrs` time NOT NULL DEFAULT '18:00:00',
  `loc_wed_open_hrs` time NOT NULL DEFAULT '08:00:00',
  `loc_wed_closed_hrs` time NOT NULL DEFAULT '18:00:00',
  `loc_thu_open_hrs` time NOT NULL DEFAULT '08:00:00',
  `loc_thu_closed_hrs` time NOT NULL DEFAULT '18:00:00',
  `loc_fri_open_hrs` time NOT NULL DEFAULT '08:00:00',
  `loc_fri_closed_hrs` time NOT NULL DEFAULT '18:00:00',
  `loc_sat_open_hrs` time NOT NULL DEFAULT '08:00:00',
  `loc_sat_closed_hrs` time NOT NULL DEFAULT '18:00:00',
  `loc_sun_open_hrs` time NOT NULL DEFAULT '08:00:00',
  `loc_sun_closed_hrs` time NOT NULL DEFAULT '18:00:00',
  `loc_flag` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`loc_id`),
  UNIQUE KEY `loc_name_UNIQUE` (`loc_name`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=latin1 COMMENT='\n\n	';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `locations`
--

LOCK TABLES `locations` WRITE;
/*!40000 ALTER TABLE `locations` DISABLE KEYS */;
INSERT INTO `locations` VALUES (37,'Funger',0,'08:00:00','18:00:00','07:30:00','18:30:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00',1),(38,'Gelman',0,'08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00',1),(39,'E-Street',0,'08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00',1),(55,'localhost',0,'08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00','08:00:00','18:00:00',1);
/*!40000 ALTER TABLE `locations` ENABLE KEYS */;
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
