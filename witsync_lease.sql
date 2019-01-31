-- MySQL dump 10.13  Distrib 5.7.24, for Linux (x86_64)
--
-- Host: localhost    Database: witsync_lease
-- ------------------------------------------------------
-- Server version	5.7.24-0ubuntu0.16.04.1

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
-- Table structure for table `categories_lease_asset_excluded`
--

DROP TABLE IF EXISTS `categories_lease_asset_excluded`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories_lease_asset_excluded` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(10) unsigned NOT NULL,
  `business_account_id` int(10) unsigned NOT NULL,
  `status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `categories_lease_asset_excluded_category_id_foreign` (`category_id`),
  KEY `categories_lease_asset_excluded_business_account_id_foreign` (`business_account_id`),
  CONSTRAINT `categories_lease_asset_excluded_business_account_id_foreign` FOREIGN KEY (`business_account_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `categories_lease_asset_excluded_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `lease_assets_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories_lease_asset_excluded`
--

LOCK TABLES `categories_lease_asset_excluded` WRITE;
/*!40000 ALTER TABLE `categories_lease_asset_excluded` DISABLE KEYS */;
/*!40000 ALTER TABLE `categories_lease_asset_excluded` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_us`
--

DROP TABLE IF EXISTS `contact_us`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contact_us` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_of_realestate` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `comments` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_us`
--

LOCK TABLES `contact_us` WRITE;
/*!40000 ALTER TABLE `contact_us` DISABLE KEYS */;
INSERT INTO `contact_us` VALUES (1,'Himanshu','Rajput','himanshu_rajput@seologistics.com','7053314271','120','Looking for a demo on the same.','2018-12-21 07:10:13','2018-12-21 07:10:13'),(2,'Himanshu','Rajput','himanshu_rajput@seologistics.com','7053314271','120','Looking for a demo.','2018-12-21 07:13:50','2018-12-21 07:13:50'),(3,'Himanshu','Rajput','himanshu_rajput@seologistics.com','7053314271','120','Need to have a demo first.','2018-12-21 07:14:49','2018-12-21 07:14:49'),(4,'Robin','Deshwal','himanshu_rajput@seologistics.com','7053314271','120','Need to have a demo of the system first.','2018-12-21 07:16:17','2018-12-21 07:16:17');
/*!40000 ALTER TABLE `contact_us` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contract_classifications`
--

DROP TABLE IF EXISTS `contract_classifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contract_classifications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contract_classifications`
--

LOCK TABLES `contract_classifications` WRITE;
/*!40000 ALTER TABLE `contract_classifications` DISABLE KEYS */;
INSERT INTO `contract_classifications` VALUES (1,'Single Lease Contract','1','2018-12-14 06:50:39','2018-12-14 06:50:39'),(2,'Multiple Leases Contract','1','2018-12-14 06:50:39','2018-12-14 06:50:39'),(3,'Single Lease & Non-Lease Contract','1','2018-12-14 06:50:39','2018-12-14 06:50:39'),(4,'Multiple Leases & Non-Lease Contract','1','2018-12-14 06:50:39','2018-12-14 06:50:39');
/*!40000 ALTER TABLE `contract_classifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contract_escalation_basis`
--

DROP TABLE IF EXISTS `contract_escalation_basis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contract_escalation_basis` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contract_escalation_basis`
--

LOCK TABLES `contract_escalation_basis` WRITE;
/*!40000 ALTER TABLE `contract_escalation_basis` DISABLE KEYS */;
INSERT INTO `contract_escalation_basis` VALUES (1,'Percentage Rate Based','1','2018-12-18 02:24:01','2018-12-18 02:24:01'),(2,'Amount Based','1','2018-12-18 02:24:01','2018-12-18 02:24:01');
/*!40000 ALTER TABLE `contract_escalation_basis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `countries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `iso_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `latitude` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitude` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `trash` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=228 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `countries`
--

LOCK TABLES `countries` WRITE;
/*!40000 ALTER TABLE `countries` DISABLE KEYS */;
INSERT INTO `countries` VALUES (1,'United States Of America','US','37.09024','-95.712891','1','0','2013-08-22 08:54:01','2018-12-13 03:37:22'),(2,'Canada','CA','56.130366','-106.346771','1','0','2013-08-22 04:54:01','2018-12-13 01:10:36'),(3,'United Kingdom','UK',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(5,'Afghanistan','AF',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(6,'Albania','AL',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(7,'Algeria','AG',NULL,NULL,'1','0','2013-08-22 04:54:01','2018-12-13 01:10:37'),(8,'Andorra','AN',NULL,NULL,'1','0','2013-08-22 04:54:01','2018-12-13 01:10:37'),(9,'Angola','AO',NULL,NULL,'1','0','2013-08-22 04:54:01','2018-12-13 01:10:38'),(10,'Anguilla','AV',NULL,NULL,'0','0','2013-08-22 04:54:01','2018-12-13 01:14:27'),(11,'Antigua and Barbuda','AC',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(12,'Argentina','AR',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(13,'Armenia','AM',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(14,'Aruba','AA',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(15,'Australia','AS',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(16,'Austria','AU',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(17,'Azerbaijan','AJ',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(18,'Bahamas','BF',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(19,'Bahrain','BA',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(20,'Bangladesh','BG',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(21,'Barbados','BB',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(22,'Belarus','BO',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(23,'Belgium','BE',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(24,'Belize','BH',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(25,'Benin','BN',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(26,'Bermuda','BD',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(27,'Bhutan','BT',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(28,'Bolivia','BL',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(29,'Bosnia and Herzegovina','BK',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(30,'Botswana','BC',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(31,'Brazil','BR',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(32,'British Virgin Islands','VI',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(33,'Brunei','BX',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(34,'Bulgaria','BU',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(35,'Burkina Faso','UV',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(36,'Burundi','BY',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(37,'Cambodia','CB',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(38,'Cameroon','CM',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(39,'Cape Verde','CV',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(40,'Cayman Islands','CJ',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(41,'Central African Republic','CT',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(42,'Chad','CD',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(43,'Chile','CI',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(44,'China','CH',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(45,'Christmas Island','KT',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(46,'Cocos (Keeling) Islands','CK',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(47,'Colombia','CO',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(48,'Comoros','CN',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(49,'Congo','CF',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(50,'Congo, Democratic Republic','CG',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(51,'Cook Islands','CW',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(52,'Costa Rica','CS',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(53,'Cote D\'Ivoire','IV',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(54,'Croatia','HR',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(55,'Cuba','CU',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(56,'Cyprus','CY',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(57,'Czech Republic','EZ',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(58,'Denmark','DA',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(59,'Djibouti','DJ',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(60,'Dominica','DO',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(61,'Dominican Republic','DR',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(62,'Ecuador','EC',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(63,'Egypt','EG',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(64,'El Salvador','ES',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(65,'Equatorial Guinea','EK',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(66,'Eritrea','ER',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(67,'Estonia','EN',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(68,'Ethiopia','ET',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(69,'Falkland Islands','FK',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(70,'Faroe Islands','FO',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(71,'Fiji','FJ',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(72,'Finland','FI',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(73,'France','FR',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(74,'French Guiana','FG',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(75,'French Polynesia','FP',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(76,'French S. &amp; Antarctic Lands','FS',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(77,'Gabon','GB',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(78,'Gambia, The','GA',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(79,'Gaza Strip','GZ',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(80,'Georgia','GG',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(81,'Germany','GM',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(82,'Ghana','GH',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(83,'Gibraltar','GI',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(84,'Greece','GR',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(85,'Greenland','GL',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(86,'Grenada','GJ',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(87,'Guadeloupe','GP',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(88,'Guatemala','GT',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(89,'Guinea','GV',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(90,'Guinea-Bissau','PU',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(91,'Guyana','GY',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(92,'Haiti','HA',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(93,'Honduras','HO',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(94,'Hong Kong','HK',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(95,'Hungary','HU',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(96,'Iceland','IC',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(97,'India','IN',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(98,'Indonesia','ID',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(99,'Iran','IR',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(100,'Iraq','IZ',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(101,'Ireland','EI',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(102,'Israel','IS',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(103,'Italy','IT',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(104,'Jamaica','JM',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(105,'Japan','JA',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(106,'Jordan','JO',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(107,'Kazakhstan','KZ',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(108,'Kenya','KE',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(109,'Kiribati','KR',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(110,'Kuwait','KU',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(111,'Kyrgyzstan','KG',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(112,'Laos','LA',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(113,'Latvia','LG',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(114,'Lebanon','LE',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(115,'Lesotho','LT',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(116,'Liberia','LI',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(117,'Liechtenstein','LS',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(118,'Lithuania','LH',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(119,'Luxembourg','LU',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(120,'Macau','MC',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(121,'Macedonia','MK',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(122,'Madagascar','MA',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(123,'Malawi','MI',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(124,'Malaysia','MY',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(125,'Maldives','MV',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(126,'Mali','ML',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(127,'Malta','MT',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(128,'Marshall Islands','RM',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(129,'Martinique','MB',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(130,'Mauritania','MR',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(131,'Mauritius','MP',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(132,'Mayotte','MF',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(133,'Mexico','MX',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(134,'Micronesia, Fed. States','FM',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(135,'Moldova','MD',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(136,'Monaco','MN',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(137,'Mongolia','MG',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(138,'Montserrat','MH',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(139,'Morocco','MO',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(140,'Mozambique','MZ',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(141,'Namibia','WA',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(142,'Nauru','NR',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(143,'Nepal','NP',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(144,'Netherlands','NL',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(145,'Netherlands Antilles','NT',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(146,'New Caledonia','NC',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(147,'New Zealand','NZ',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(148,'Nicaragua','NU',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(149,'Niger','NG',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(150,'Nigeria','NI',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(151,'Niue','NE',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(152,'Norfolk Island','NF',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(153,'North Korea','KN',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(154,'Norway','NO',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(155,'Oman','MU',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(156,'Pakistan','PK',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(157,'Palau','PS',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(158,'Panama','PM',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(159,'Papua New Guinea','PP',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(160,'Paraguay','PA',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(161,'Peru','PE',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(162,'Philippines','RP',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(163,'Pitcairn Islands','PC',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(164,'Poland','PL',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(165,'Portugal','PO',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(166,'Puerto Rico','PR',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(167,'Qatar','QA',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(168,'Reunion','RE',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(169,'Romania','RO',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(170,'Russia','RS',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(171,'Rwanda','RW',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(172,'S. Georgia &amp; Sandwich Islands','SX',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(173,'Saint Kitts and Nevis','SC',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(174,'Saint Lucia','ST',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(175,'Samoa','WS',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(176,'San Marino','SM',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(177,'Sao Tome and Principe','TP',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(178,'Saudi Arabia','SA',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(179,'Senegal','SG',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(180,'Serbia','SR',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(181,'Seychelles','SE',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(182,'Sierra Leone','SL',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(183,'Singapore','SN',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(184,'Slovakia','LO',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(185,'Slovenia','SI',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(186,'Solomon Islands','BP',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(187,'Somalia','SO',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(188,'South Africa','SF',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(189,'South Korea','KS',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(190,'Spain','SP',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(191,'Sri Lanka','CE',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(192,'St. Vincent &amp; The Grenadines','VC',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(193,'Sudan','SU',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(194,'Suriname','NS',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(195,'Svalbard','SV',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(196,'Swaziland','WZ',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(197,'Sweden','SW',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(198,'Switzerland','SZ',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(199,'Syria','SY',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(200,'Taiwan','TW',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(201,'Tajikistan','TI',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(202,'Tanzania','TZ',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(203,'Thailand','TH',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(204,'Togo','TO',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(205,'Tokelau','TL',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(206,'Tonga','TN',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(207,'Trinidad and Tobago','TD',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(208,'Tunisia','TS',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(209,'Turkey','TU',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(210,'Turkmenistan','TX',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(211,'Turks and Caicos Islands','TK',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(212,'Tuvalu','TV',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(213,'Uganda','UG',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(214,'Ukraine','UP',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(215,'United Arab Emirates','AE',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(216,'Uruguay','UY',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(217,'Uzbekistan','UZ',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(218,'Vanuatu','NH',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(219,'Vatican City','VT',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(220,'Venezuela','VE',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(221,'Vietnam','VM',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(222,'Virgin Islands','VQ',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(223,'Wallis and Futuna','WF',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(224,'Western Sahara','WI',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(225,'Yemen','YM',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(226,'Zambia','ZA',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01'),(227,'Zimbabwe','ZI',NULL,NULL,'1','0','2013-08-22 04:54:01','2013-08-21 22:54:01');
/*!40000 ALTER TABLE `countries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `currencies`
--

DROP TABLE IF EXISTS `currencies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `currencies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `currency` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `symbol` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `thousand_separator` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `decimal_separator` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=132 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `currencies`
--

LOCK TABLES `currencies` WRITE;
/*!40000 ALTER TABLE `currencies` DISABLE KEYS */;
INSERT INTO `currencies` VALUES (1,'Leke','ALL','Lek',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(3,'Afghanis','AF','؋',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(4,'Pesos','ARS','$',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(5,'Guilders','AWG','ƒ',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(6,'Dollars','AUD','$',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(7,'New Manats','AZ','ман',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(8,'Dollars','BSD','$',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(9,'Dollars','BBD','$',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(10,'Rubles','BYR','p.',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(12,'Dollars','BZD','BZ$',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(13,'Dollars','BMD','$',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(14,'Bolivianos','BOB','$b',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(15,'Convertible Marka','BAM','KM',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(16,'Pula\'s','BWP','P',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(17,'Leva','BG','лв',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(18,'Reais','BRL','R$',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(20,'Dollars','BND','$',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(21,'Riels','KHR','៛',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(22,'Dollars','CAD','$',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(23,'Dollars','KYD','$',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(24,'Pesos','CLP','$',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(25,'Yuan Renminbi','CNY','¥',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(26,'Pesos','COP','$',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(27,'Colón','CRC','₡',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(28,'Kuna','HRK','kn',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(29,'Pesos','CUP','₱',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(31,'Koruny','CZK','Kč',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(32,'Kroner','DKK','kr',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(33,'Pesos','DOP ','RD$',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(34,'Dollars','XCD','$',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(35,'Pounds','EGP','£',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(36,'Colones','SVC','$',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(39,'Pounds','FKP','£',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(40,'Dollars','FJD','$',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(42,'Cedis','GHC','¢',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(43,'Pounds','GIP','£',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(45,'Quetzales','GTQ','Q',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(46,'Pounds','GGP','£',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(47,'Dollars','GYD','$',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(49,'Lempiras','HNL','L',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(50,'Dollars','HKD','$',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(51,'Forint','HUF','Ft',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(52,'Kronur','ISK','kr',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(53,'Rupees','INR','Rp',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(54,'Rupiahs','IDR','Rp',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(55,'Rials','IRR','﷼',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(57,'Pounds','IMP','£',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(58,'New Shekels','ILS','₪',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(60,'Dollars','JMD','J$',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(61,'Yen','JPY','¥',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(62,'Pounds','JEP','£',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(63,'Tenge','KZT','лв',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(66,'Soms','KGS','лв',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(67,'Kips','LAK','₭',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(68,'Lati','LVL','Ls',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(69,'Pounds','LBP','£',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(70,'Dollars','LRD','$',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(72,'Litai','LTL','Lt',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(74,'Denars','MKD','ден',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(75,'Ringgits','MYR','RM',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(77,'Rupees','MUR','₨',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(78,'Pesos','MX','$',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(79,'Tugriks','MNT','₮',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(80,'Meticais','MZ','MT',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(81,'Dollars','NAD','$',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(82,'Rupees','NPR','₨',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(83,'Guilders','ANG','ƒ',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(85,'Dollars','NZD','$',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(86,'Cordobas','NIO','C$',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(87,'Nairas','NG','₦',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(88,'Won','KPW','₩',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(89,'Krone','NOK','kr',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(90,'Rials','OMR','﷼',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(91,'Rupees','PKR','₨',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(92,'Balboa','PAB','B/.',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(93,'Guarani','PYG','Gs',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(94,'Nuevos Soles','PE','S/.',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(95,'Pesos','PHP','Php',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(96,'Zlotych','PL','zł',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(97,'Rials','QAR','﷼',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(98,'New Lei','RO','lei',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(99,'Rubles','RUB','руб',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(100,'Pounds','SHP','£',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(101,'Riyals','SAR','﷼',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(102,'Dinars','RSD','Дин.',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(103,'Rupees','SCR','₨',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(104,'Dollars','SGD','$',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(106,'Dollars','SBD','$',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(107,'Shillings','SOS','S',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(108,'Rand','ZAR','R',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(109,'Won','KRW','₩',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(111,'Rupees','LKR','₨',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(112,'Kronor','SEK','kr',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(113,'Francs','CHF','CHF',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(114,'Dollars','SRD','$',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(115,'Pounds','SYP','£',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(116,'New Dollars','TWD','NT$',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(117,'Baht','THB','฿',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(118,'Dollars','TTD','TT$',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(119,'Lira','TRY','TL',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(120,'Liras','TRL','£',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(121,'Dollars','TVD','$',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(122,'Hryvnia','UAH','₴',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(123,'Pounds','GBP','£',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(124,'Dollars','USD','$',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(125,'Pesos','UYU','$U',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(126,'Sums','UZS','лв',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(127,'Euro','EUR','€',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(128,'Bolivares Fuertes','VEF','Bs',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(129,'Dong','VND','₫',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(130,'Rials','YER','﷼',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49'),(131,'Zimbabwe Dollars','ZWD','Z$',',','.','1','2018-12-13 05:02:49','2018-12-13 05:02:49');
/*!40000 ALTER TABLE `currencies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer_details`
--

DROP TABLE IF EXISTS `customer_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customer_details` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lease_incentive_id` int(10) unsigned NOT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `incentive_date` date DEFAULT NULL,
  `currency_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(12,2) DEFAULT NULL,
  `exchange_rate` decimal(12,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_details_lease_incentive_id_foreign` (`lease_incentive_id`),
  CONSTRAINT `customer_details_lease_incentive_id_foreign` FOREIGN KEY (`lease_incentive_id`) REFERENCES `lease_incentives` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer_details`
--

LOCK TABLES `customer_details` WRITE;
/*!40000 ALTER TABLE `customer_details` DISABLE KEYS */;
/*!40000 ALTER TABLE `customer_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer_incentives`
--

DROP TABLE IF EXISTS `customer_incentives`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customer_incentives` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer_incentives`
--

LOCK TABLES `customer_incentives` WRITE;
/*!40000 ALTER TABLE `customer_incentives` DISABLE KEYS */;
/*!40000 ALTER TABLE `customer_incentives` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `depreciation_method`
--

DROP TABLE IF EXISTS `depreciation_method`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `depreciation_method` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_default` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '1 => Means the method is the default and will always presented to the users.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `depreciation_method`
--

LOCK TABLES `depreciation_method` WRITE;
/*!40000 ALTER TABLE `depreciation_method` DISABLE KEYS */;
INSERT INTO `depreciation_method` VALUES (1,'Straight Line Method','1','2018-12-21 04:06:04','2018-12-21 04:06:04');
/*!40000 ALTER TABLE `depreciation_method` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `email_templates`
--

DROP TABLE IF EXISTS `email_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `email_templates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `template_subject` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Subject that will be send to the users.',
  `template_code` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Unique Templates identifier.',
  `template_body` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Email Template Body',
  `template_special_variables` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Special variables that will be replaced.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_templates`
--

LOCK TABLES `email_templates` WRITE;
/*!40000 ALTER TABLE `email_templates` DISABLE KEYS */;
INSERT INTO `email_templates` VALUES (1,'Verify Your Email Address','Verify Your Email Address','EMAIL_VERIFICATION','<table cellpadding=\"0\" cellspacing=\"0\" style=\"width:100%\">\r\n	<tbody>\r\n		<tr>\r\n			<td>\r\n			<table cellpadding=\"0\" cellspacing=\"0\" style=\"width:100%\">\r\n				<tbody>\r\n					<tr>\r\n						<td><a href=\"[[APP_LINK]]\">[[APP_NAME]] </a></td>\r\n					</tr>\r\n					<!-- Email Body -->\r\n					<tr>\r\n						<td>\r\n						<table align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"height:336px; width:568px\"><!-- Body content -->\r\n							<tbody>\r\n								<tr>\r\n									<td>\r\n									<h1>Hi [[USER_NAME]],</h1>\r\n\r\n									<p>Thanks for registering your account at Mobile Wallet. Please click on the below email address to verify your email address.</p>\r\n									<!-- Action -->\r\n\r\n									<table align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"width:100%\">\r\n										<tbody>\r\n											<tr>\r\n												<td><!-- Border based button\r\n                       https://litmus.com/blog/a-guide-to-bulletproof-buttons-in-email-design -->\r\n												<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"width:100%\">\r\n													<tbody>\r\n														<tr>\r\n															<td>\r\n															<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n																<tbody>\r\n																	<tr>\r\n																		<td><a href=\"[[ACTION_URL]]\" target=\"_blank\">Verify Email Address</a></td>\r\n																	</tr>\r\n																</tbody>\r\n															</table>\r\n															</td>\r\n														</tr>\r\n													</tbody>\r\n												</table>\r\n												</td>\r\n											</tr>\r\n										</tbody>\r\n									</table>\r\n									<!-- Sub copy -->\r\n\r\n									<table>\r\n										<tbody>\r\n											<tr>\r\n												<td>\r\n												<p>If you&rsquo;re having trouble with the button above, copy and paste the URL below into your web browser.</p>\r\n\r\n												<p>[[ACTION_URL]]</p>\r\n\r\n												<p>&nbsp;</p>\r\n\r\n												<p>Thanks,<br />\r\n												The [[APP_NAME]] Team</p>\r\n												</td>\r\n											</tr>\r\n										</tbody>\r\n									</table>\r\n									</td>\r\n								</tr>\r\n							</tbody>\r\n						</table>\r\n						</td>\r\n					</tr>\r\n					<tr>\r\n						<td>\r\n						<table align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"width:570px\">\r\n							<tbody>\r\n								<tr>\r\n									<td>\r\n									<p>&copy; 2018 [[APP_NAME]]. All rights reserved.</p>\r\n\r\n									<p>[[COMPANY_NAME]]<br />\r\n									1234 Street Rd.<br />\r\n									Suite 1234</p>\r\n									</td>\r\n								</tr>\r\n							</tbody>\r\n						</table>\r\n						</td>\r\n					</tr>\r\n				</tbody>\r\n			</table>\r\n			</td>\r\n		</tr>\r\n	</tbody>\r\n</table>','[[APP_NAME]],[[APP_LINK]],[[USER_NAME]],[[ACTION_URL]],[[COMPANY_NAME]]','2018-11-12 23:44:15','2018-12-03 00:35:26'),(2,'Contact Us','Contact Us','CONTACT_US','<table cellpadding=\"0\" cellspacing=\"0\" style=\"width:100%\">\r\n	<tbody>\r\n		<tr>\r\n			<td>\r\n			<table cellpadding=\"0\" cellspacing=\"0\" style=\"width:100%\">\r\n				<tbody>\r\n					<tr>\r\n						<td><a href=\"[[APP_LINK]]\">[[APP_NAME]] </a></td>\r\n					</tr>\r\n					<!-- Email Body -->\r\n					<tr>\r\n						<td>\r\n						<table align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"height:336px; width:568px\"><!-- Body content -->\r\n							<tbody>\r\n								<tr>\r\n									<td>\r\n									<h1>Hi [[FIRST_NAME]] [[LAST_NAME]],</h1>\r\n\r\n									<p>Thank you for contacting us.we will contact you as soon as possible</p>\r\n									<!-- Action -->\r\n\r\n									<table align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"width:100%\">\r\n										<tbody>\r\n											<tr>\r\n												<td><!-- Border based button\r\n                       https://litmus.com/blog/a-guide-to-bulletproof-buttons-in-email-design -->\r\n												<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"width:100%\">\r\n													<tbody>\r\n														<tr>\r\n															<td>\r\n															<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\r\n																<tbody>\r\n																	<tr>\r\n																		<td>email-id:-&nbsp; [[EMAIL]]</td>\r\n																	</tr>\r\n																	<tr>\r\n																		<td>Phone Number:- [[PHONE]]</td>\r\n																	</tr>\r\n																	<tr>\r\n																		<td>No Of Real Estate:- [[NO_OF_REALESTATE]]</td>\r\n																	</tr>\r\n																	<tr>\r\n																		<td>Comments:- [[COMMENTS]]</td>\r\n																	</tr>\r\n																</tbody>\r\n															</table>\r\n															</td>\r\n														</tr>\r\n													</tbody>\r\n												</table>\r\n												</td>\r\n											</tr>\r\n										</tbody>\r\n									</table>\r\n									<!-- Sub copy -->\r\n\r\n									<p>&nbsp;</p>\r\n\r\n									<table>\r\n										<tbody>\r\n											<tr>\r\n												<td>\r\n												<p>&copy; 2018 [[APP_NAME]]. All rights reserved.</p>\r\n\r\n												<p>[[COMPANY_NAME]]<br />\r\n												1234 Street Rd.<br />\r\n												Suite 1234</p>\r\n												</td>\r\n											</tr>\r\n										</tbody>\r\n									</table>\r\n									</td>\r\n								</tr>\r\n							</tbody>\r\n						</table>\r\n						</td>\r\n					</tr>\r\n					<tr>\r\n						<td>\r\n						<table align=\"center\" cellpadding=\"0\" cellspacing=\"0\" style=\"width:570px\">\r\n							<tbody>\r\n							</tbody>\r\n						</table>\r\n						</td>\r\n					</tr>\r\n				</tbody>\r\n			</table>\r\n			</td>\r\n		</tr>\r\n	</tbody>\r\n</table>','[[FIRST_NAME]],[[LAST_NAME]],[[EMAIL]],[[PHONE]],[[NO_OF_REALESTATE]],[[COMMENTS]],[[APP_NAME]],[[COMPANY_NAME]]','2018-11-12 23:44:15','2018-12-17 05:13:12');
/*!40000 ALTER TABLE `email_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `escalation_amount_calculated_on`
--

DROP TABLE IF EXISTS `escalation_amount_calculated_on`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `escalation_amount_calculated_on` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `escalation_amount_calculated_on`
--

LOCK TABLES `escalation_amount_calculated_on` WRITE;
/*!40000 ALTER TABLE `escalation_amount_calculated_on` DISABLE KEYS */;
INSERT INTO `escalation_amount_calculated_on` VALUES (1,'Fixed Lease Payments','1','2018-12-18 22:22:58','2018-12-18 22:22:58'),(2,'Variable Lease Payments with Determinable Amounts','1','2018-12-18 22:22:58','2018-12-18 22:22:58');
/*!40000 ALTER TABLE `escalation_amount_calculated_on` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `escalation_frequency`
--

DROP TABLE IF EXISTS `escalation_frequency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `escalation_frequency` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `frequency` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `escalation_frequency`
--

LOCK TABLES `escalation_frequency` WRITE;
/*!40000 ALTER TABLE `escalation_frequency` DISABLE KEYS */;
INSERT INTO `escalation_frequency` VALUES (1,'One Time in Year',1,'2019-01-09 23:11:21','2019-01-09 23:11:21'),(2,'Two Times in a Year',2,'2019-01-09 23:11:21','2019-01-09 23:11:21'),(3,'Three Times in a Year',3,'2019-01-09 23:11:21','2019-01-09 23:11:21');
/*!40000 ALTER TABLE `escalation_frequency` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `escalation_percentage_settings`
--

DROP TABLE IF EXISTS `escalation_percentage_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `escalation_percentage_settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `business_account_id` int(10) unsigned NOT NULL,
  `number` int(10) unsigned NOT NULL,
  `status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `es_per_bu_id` (`business_account_id`),
  CONSTRAINT `es_per_bu_id` FOREIGN KEY (`business_account_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `escalation_percentage_settings`
--

LOCK TABLES `escalation_percentage_settings` WRITE;
/*!40000 ALTER TABLE `escalation_percentage_settings` DISABLE KEYS */;
INSERT INTO `escalation_percentage_settings` VALUES (1,30,0,'1','2018-12-25 23:22:58','2018-12-25 23:22:58'),(2,30,1,'1','2018-12-25 23:22:58','2018-12-25 23:22:58'),(3,30,2,'1','2019-01-09 23:03:04','2019-01-09 23:03:04');
/*!40000 ALTER TABLE `escalation_percentage_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `expected_useful_life_of_asset`
--

DROP TABLE IF EXISTS `expected_useful_life_of_asset`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `expected_useful_life_of_asset` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `business_account_id` int(10) unsigned NOT NULL,
  `years` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `expected_useful_life_of_asset_business_account_id_foreign` (`business_account_id`),
  CONSTRAINT `expected_useful_life_of_asset_business_account_id_foreign` FOREIGN KEY (`business_account_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `expected_useful_life_of_asset`
--

LOCK TABLES `expected_useful_life_of_asset` WRITE;
/*!40000 ALTER TABLE `expected_useful_life_of_asset` DISABLE KEYS */;
INSERT INTO `expected_useful_life_of_asset` VALUES (1,30,-1,'2018-12-25 23:22:58','2018-12-25 23:22:58'),(2,30,1,'2018-12-25 23:22:59','2018-12-25 23:22:59'),(3,30,5,'2018-12-25 23:22:59','2018-12-25 23:22:59'),(4,30,10,'2018-12-25 23:22:59','2018-12-25 23:22:59'),(5,30,25,'2018-12-25 23:22:59','2018-12-25 23:22:59');
/*!40000 ALTER TABLE `expected_useful_life_of_asset` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fair_market_value`
--

DROP TABLE IF EXISTS `fair_market_value`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fair_market_value` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lease_id` int(10) unsigned NOT NULL,
  `asset_id` int(10) unsigned NOT NULL,
  `is_market_value_present` enum('yes','no') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `similar_asset_items` int(10) unsigned DEFAULT NULL,
  `attachment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit` int(10) unsigned DEFAULT NULL,
  `total_units` int(10) unsigned DEFAULT NULL,
  `source` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fair_market_value_lease_id_foreign` (`lease_id`),
  KEY `fair_market_value_asset_id_foreign` (`asset_id`),
  CONSTRAINT `fair_market_value_asset_id_foreign` FOREIGN KEY (`asset_id`) REFERENCES `lease_assets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fair_market_value_lease_id_foreign` FOREIGN KEY (`lease_id`) REFERENCES `lease` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fair_market_value`
--

LOCK TABLES `fair_market_value` WRITE;
/*!40000 ALTER TABLE `fair_market_value` DISABLE KEYS */;
INSERT INTO `fair_market_value` VALUES (11,19,31,'no','INR',1,'',NULL,NULL,NULL,'2019-01-07 05:39:09','2019-01-07 05:39:09'),(12,19,32,'no','INR',1,'',NULL,NULL,NULL,'2019-01-07 05:39:13','2019-01-07 05:39:13');
/*!40000 ALTER TABLE `fair_market_value` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `foreign_currency_transaction_settings`
--

DROP TABLE IF EXISTS `foreign_currency_transaction_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `foreign_currency_transaction_settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `business_account_id` int(10) unsigned NOT NULL,
  `foreign_exchange_currency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `valid_from` date NOT NULL,
  `valid_to` date NOT NULL,
  `exchange_rate` decimal(8,2) NOT NULL,
  `base_currency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `foreign_currency_transaction_ac_id` (`business_account_id`),
  CONSTRAINT `foreign_currency_transaction_ac_id` FOREIGN KEY (`business_account_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `foreign_currency_transaction_settings`
--

LOCK TABLES `foreign_currency_transaction_settings` WRITE;
/*!40000 ALTER TABLE `foreign_currency_transaction_settings` DISABLE KEYS */;
INSERT INTO `foreign_currency_transaction_settings` VALUES (1,30,'ALL','2018-12-28','2019-04-30',10.00,'INR','2018-12-28 07:02:12','2018-12-28 07:02:12');
/*!40000 ALTER TABLE `foreign_currency_transaction_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `general_settings`
--

DROP TABLE IF EXISTS `general_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `general_settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `business_account_id` int(10) unsigned NOT NULL,
  `annual_year_end_on` date NOT NULL,
  `date_of_initial_application` enum('1','2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '1 => 01/01/2019 , 2 => Earlier than 01/01/2019',
  `date_of_initial_application_earlier_date` date DEFAULT NULL COMMENT 'Will have value only when `date_of_initial_application` is 2',
  `max_previous_lease_start_year` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `general_settings_business_account_id_foreign` (`business_account_id`),
  CONSTRAINT `general_settings_business_account_id_foreign` FOREIGN KEY (`business_account_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `general_settings`
--

LOCK TABLES `general_settings` WRITE;
/*!40000 ALTER TABLE `general_settings` DISABLE KEYS */;
INSERT INTO `general_settings` VALUES (1,30,'2018-12-05','1',NULL,'2016','2018-12-28 06:52:44','2018-12-28 06:52:44');
/*!40000 ALTER TABLE `general_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `industry_type`
--

DROP TABLE IF EXISTS `industry_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `industry_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=149 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `industry_type`
--

LOCK TABLES `industry_type` WRITE;
/*!40000 ALTER TABLE `industry_type` DISABLE KEYS */;
INSERT INTO `industry_type` VALUES (1,'Industry','1','2018-12-13 02:07:43','2018-12-13 02:07:43'),(2,'Accounting','1','2018-12-13 02:07:43','2018-12-13 02:07:43'),(3,'Airlines/Aviation','1','2018-12-13 02:07:43','2018-12-13 02:07:43'),(4,'Alternative Dispute Resolution','1','2018-12-13 02:07:43','2018-12-13 02:07:43'),(5,'Alternative Medicine','1','2018-12-13 02:07:43','2018-12-13 02:07:43'),(6,'Animation','1','2018-12-13 02:07:43','2018-12-13 02:07:43'),(7,'Apparel/Fashion','1','2018-12-13 02:07:43','2018-12-13 02:07:43'),(8,'Architecture/Planning','1','2018-12-13 02:07:43','2018-12-13 02:07:43'),(9,'Arts/Crafts','1','2018-12-13 02:07:43','2018-12-13 02:07:43'),(10,'Automotive','1','2018-12-13 02:07:43','2018-12-13 02:07:43'),(11,'Aviation/Aerospace','1','2018-12-13 02:07:43','2018-12-13 02:07:43'),(12,'Banking/Mortgage','1','2018-12-13 02:07:44','2018-12-13 02:07:44'),(13,'Biotechnology/Greentech','1','2018-12-13 02:07:44','2018-12-13 02:07:44'),(14,'Broadcast Media','1','2018-12-13 02:07:44','2018-12-13 02:07:44'),(15,'Building Materials','1','2018-12-13 02:07:44','2018-12-13 02:07:44'),(16,'Business Supplies/Equipment','1','2018-12-13 02:07:44','2018-12-13 02:07:44'),(17,'Capital Markets/Hedge Fund/Private Equity','1','2018-12-13 02:07:44','2018-12-13 02:07:44'),(18,'Chemicals','1','2018-12-13 02:07:44','2018-12-13 02:07:44'),(19,'Civic/Social Organization','1','2018-12-13 02:07:44','2018-12-13 02:07:44'),(20,'Civil Engineering','1','2018-12-13 02:07:44','2018-12-13 02:07:44'),(21,'Commercial Real Estate','1','2018-12-13 02:07:44','2018-12-13 02:07:44'),(22,'Computer Games','1','2018-12-13 02:07:44','2018-12-13 02:07:44'),(23,'Computer Hardware','1','2018-12-13 02:07:44','2018-12-13 02:07:44'),(24,'Computer Networking','1','2018-12-13 02:07:44','2018-12-13 02:07:44'),(25,'Computer Software/Engineering','1','2018-12-13 02:07:44','2018-12-13 02:07:44'),(26,'Computer/Network Security','1','2018-12-13 02:07:44','2018-12-13 02:07:44'),(27,'Construction','1','2018-12-13 02:07:44','2018-12-13 02:07:44'),(28,'Consumer Electronics','1','2018-12-13 02:07:44','2018-12-13 02:07:44'),(29,'Consumer Goods','1','2018-12-13 02:07:44','2018-12-13 02:07:44'),(30,'Consumer Services','1','2018-12-13 02:07:45','2018-12-13 02:07:45'),(31,'Cosmetics','1','2018-12-13 02:07:45','2018-12-13 02:07:45'),(32,'Dairy','1','2018-12-13 02:07:45','2018-12-13 02:07:45'),(33,'Defense/Space','1','2018-12-13 02:07:45','2018-12-13 02:07:45'),(34,'Design','1','2018-12-13 02:07:45','2018-12-13 02:07:45'),(35,'E-Learning','1','2018-12-13 02:07:45','2018-12-13 02:07:45'),(36,'Education Management','1','2018-12-13 02:07:45','2018-12-13 02:07:45'),(37,'Electrical/Electronic Manufacturing','1','2018-12-13 02:07:45','2018-12-13 02:07:45'),(38,'Entertainment/Movie Production','1','2018-12-13 02:07:45','2018-12-13 02:07:45'),(39,'Environmental Services','1','2018-12-13 02:07:45','2018-12-13 02:07:45'),(40,'Events Services','1','2018-12-13 02:07:45','2018-12-13 02:07:45'),(41,'Executive Office','1','2018-12-13 02:07:45','2018-12-13 02:07:45'),(42,'Facilities Services','1','2018-12-13 02:07:45','2018-12-13 02:07:45'),(43,'Farming','1','2018-12-13 02:07:45','2018-12-13 02:07:45'),(44,'Financial Services','1','2018-12-13 02:07:45','2018-12-13 02:07:45'),(45,'Fine Art','1','2018-12-13 02:07:45','2018-12-13 02:07:45'),(46,'Fishery','1','2018-12-13 02:07:45','2018-12-13 02:07:45'),(47,'Food Production','1','2018-12-13 02:07:46','2018-12-13 02:07:46'),(48,'Food/Beverages','1','2018-12-13 02:07:46','2018-12-13 02:07:46'),(49,'Fundraising','1','2018-12-13 02:07:46','2018-12-13 02:07:46'),(50,'Furniture','1','2018-12-13 02:07:46','2018-12-13 02:07:46'),(51,'Gambling/Casinos','1','2018-12-13 02:07:46','2018-12-13 02:07:46'),(52,'Glass/Ceramics/Concrete','1','2018-12-13 02:07:46','2018-12-13 02:07:46'),(53,'Government Administration','1','2018-12-13 02:07:46','2018-12-13 02:07:46'),(54,'Government Relations','1','2018-12-13 02:07:46','2018-12-13 02:07:46'),(55,'Graphic Design/Web Design','1','2018-12-13 02:07:46','2018-12-13 02:07:46'),(56,'Health/Fitness','1','2018-12-13 02:07:46','2018-12-13 02:07:46'),(57,'Higher Education/Acadamia','1','2018-12-13 02:07:46','2018-12-13 02:07:46'),(58,'Hospital/Health Care','1','2018-12-13 02:07:46','2018-12-13 02:07:46'),(59,'Hospitality','1','2018-12-13 02:07:46','2018-12-13 02:07:46'),(60,'Human Resources/HR','1','2018-12-13 02:07:46','2018-12-13 02:07:46'),(61,'Import/Export','1','2018-12-13 02:07:46','2018-12-13 02:07:46'),(62,'Individual/Family Services','1','2018-12-13 02:07:46','2018-12-13 02:07:46'),(63,'Industrial Automation','1','2018-12-13 02:07:46','2018-12-13 02:07:46'),(64,'Information Services','1','2018-12-13 02:07:46','2018-12-13 02:07:46'),(65,'Information Technology/IT','1','2018-12-13 02:07:46','2018-12-13 02:07:46'),(66,'Insurance','1','2018-12-13 02:07:46','2018-12-13 02:07:46'),(67,'International Affairs','1','2018-12-13 02:07:47','2018-12-13 02:07:47'),(68,'International Trade/Development','1','2018-12-13 02:07:47','2018-12-13 02:07:47'),(69,'Internet','1','2018-12-13 02:07:47','2018-12-13 02:07:47'),(70,'Investment Banking/Venture','1','2018-12-13 02:07:47','2018-12-13 02:07:47'),(71,'Investment Management/Hedge Fund/Private Equity','1','2018-12-13 02:07:47','2018-12-13 02:07:47'),(72,'Judiciary','1','2018-12-13 02:07:47','2018-12-13 02:07:47'),(73,'Law Enforcement','1','2018-12-13 02:07:47','2018-12-13 02:07:47'),(74,'Law Practice/Law Firms','1','2018-12-13 02:07:47','2018-12-13 02:07:47'),(75,'Legal Services','1','2018-12-13 02:07:47','2018-12-13 02:07:47'),(76,'Legislative Office','1','2018-12-13 02:07:47','2018-12-13 02:07:47'),(77,'Leisure/Travel','1','2018-12-13 02:07:47','2018-12-13 02:07:47'),(78,'Library','1','2018-12-13 02:07:47','2018-12-13 02:07:47'),(79,'Logistics/Procurement','1','2018-12-13 02:07:47','2018-12-13 02:07:47'),(80,'Luxury Goods/Jewelry','1','2018-12-13 02:07:47','2018-12-13 02:07:47'),(81,'Machinery','1','2018-12-13 02:07:47','2018-12-13 02:07:47'),(82,'Management Consulting','1','2018-12-13 02:07:47','2018-12-13 02:07:47'),(83,'Maritime','1','2018-12-13 02:07:47','2018-12-13 02:07:47'),(84,'Market Research','1','2018-12-13 02:07:47','2018-12-13 02:07:47'),(85,'Marketing/Advertising/Sales','1','2018-12-13 02:07:47','2018-12-13 02:07:47'),(86,'Mechanical or Industrial Engineering','1','2018-12-13 02:07:48','2018-12-13 02:07:48'),(87,'Media Production','1','2018-12-13 02:07:48','2018-12-13 02:07:48'),(88,'Medical Equipment','1','2018-12-13 02:07:48','2018-12-13 02:07:48'),(89,'Medical Practice','1','2018-12-13 02:07:48','2018-12-13 02:07:48'),(90,'Mental Health Care','1','2018-12-13 02:07:48','2018-12-13 02:07:48'),(91,'Military Industry','1','2018-12-13 02:07:48','2018-12-13 02:07:48'),(92,'Mining/Metals','1','2018-12-13 02:07:48','2018-12-13 02:07:48'),(93,'Motion Pictures/Film','1','2018-12-13 02:07:48','2018-12-13 02:07:48'),(94,'Museums/Institutions','1','2018-12-13 02:07:48','2018-12-13 02:07:48'),(95,'Music','1','2018-12-13 02:07:48','2018-12-13 02:07:48'),(96,'Nanotechnology','1','2018-12-13 02:07:48','2018-12-13 02:07:48'),(97,'Newspapers/Journalism','1','2018-12-13 02:07:48','2018-12-13 02:07:48'),(98,'Non-Profit/Volunteering','1','2018-12-13 02:07:48','2018-12-13 02:07:48'),(99,'Oil/Energy/Solar/Greentech','1','2018-12-13 02:07:48','2018-12-13 02:07:48'),(100,'Online Publishing','1','2018-12-13 02:07:48','2018-12-13 02:07:48'),(101,'Other Industry','1','2018-12-13 02:07:48','2018-12-13 02:07:48'),(102,'Outsourcing/Offshoring','1','2018-12-13 02:07:48','2018-12-13 02:07:48'),(103,'Package/Freight Delivery','1','2018-12-13 02:07:48','2018-12-13 02:07:48'),(104,'Packaging/Containers','1','2018-12-13 02:07:49','2018-12-13 02:07:49'),(105,'Paper/Forest Products','1','2018-12-13 02:07:49','2018-12-13 02:07:49'),(106,'Performing Arts','1','2018-12-13 02:07:49','2018-12-13 02:07:49'),(107,'Pharmaceuticals','1','2018-12-13 02:07:49','2018-12-13 02:07:49'),(108,'Philanthropy','1','2018-12-13 02:07:49','2018-12-13 02:07:49'),(109,'Photography','1','2018-12-13 02:07:49','2018-12-13 02:07:49'),(110,'Plastics','1','2018-12-13 02:07:49','2018-12-13 02:07:49'),(111,'Political Organization','1','2018-12-13 02:07:49','2018-12-13 02:07:49'),(112,'Primary/Secondary Education','1','2018-12-13 02:07:49','2018-12-13 02:07:49'),(113,'Printing','1','2018-12-13 02:07:49','2018-12-13 02:07:49'),(114,'Professional Training','1','2018-12-13 02:07:49','2018-12-13 02:07:49'),(115,'Program Development','1','2018-12-13 02:07:49','2018-12-13 02:07:49'),(116,'Public Relations/PR','1','2018-12-13 02:07:49','2018-12-13 02:07:49'),(117,'Public Safety','1','2018-12-13 02:07:49','2018-12-13 02:07:49'),(118,'Publishing Industry','1','2018-12-13 02:07:49','2018-12-13 02:07:49'),(119,'Railroad Manufacture','1','2018-12-13 02:07:49','2018-12-13 02:07:49'),(120,'Ranching','1','2018-12-13 02:07:49','2018-12-13 02:07:49'),(121,'Real Estate/Mortgage','1','2018-12-13 02:07:49','2018-12-13 02:07:49'),(122,'Recreational Facilities/Services','1','2018-12-13 02:07:50','2018-12-13 02:07:50'),(123,'Religious Institutions','1','2018-12-13 02:07:50','2018-12-13 02:07:50'),(124,'Renewables/Environment','1','2018-12-13 02:07:50','2018-12-13 02:07:50'),(125,'Research Industry','1','2018-12-13 02:07:50','2018-12-13 02:07:50'),(126,'Restaurants','1','2018-12-13 02:07:50','2018-12-13 02:07:50'),(127,'Retail Industry','1','2018-12-13 02:07:50','2018-12-13 02:07:50'),(128,'Security/Investigations','1','2018-12-13 02:07:50','2018-12-13 02:07:50'),(129,'Semiconductors','1','2018-12-13 02:07:50','2018-12-13 02:07:50'),(130,'Shipbuilding','1','2018-12-13 02:07:50','2018-12-13 02:07:50'),(131,'Sporting Goods','1','2018-12-13 02:07:50','2018-12-13 02:07:50'),(132,'Sports','1','2018-12-13 02:07:50','2018-12-13 02:07:50'),(133,'Staffing/Recruiting','1','2018-12-13 02:07:50','2018-12-13 02:07:50'),(134,'Supermarkets','1','2018-12-13 02:07:50','2018-12-13 02:07:50'),(135,'Telecommunications','1','2018-12-13 02:07:50','2018-12-13 02:07:50'),(136,'Textiles','1','2018-12-13 02:07:50','2018-12-13 02:07:50'),(137,'Think Tanks','1','2018-12-13 02:07:50','2018-12-13 02:07:50'),(138,'Tobacco','1','2018-12-13 02:07:50','2018-12-13 02:07:50'),(139,'Translation/Localization','1','2018-12-13 02:07:50','2018-12-13 02:07:50'),(140,'Transportation','1','2018-12-13 02:07:50','2018-12-13 02:07:50'),(141,'Utilities','1','2018-12-13 02:07:50','2018-12-13 02:07:50'),(142,'Venture Capital/VC','1','2018-12-13 02:07:50','2018-12-13 02:07:50'),(143,'Veterinary','1','2018-12-13 02:07:50','2018-12-13 02:07:50'),(144,'Warehousing','1','2018-12-13 02:07:50','2018-12-13 02:07:50'),(145,'Wholesale','1','2018-12-13 02:07:50','2018-12-13 02:07:50'),(146,'Wine/Spirits','1','2018-12-13 02:07:51','2018-12-13 02:07:51'),(147,'Wireless','1','2018-12-13 02:07:51','2018-12-13 02:07:51'),(148,'Writing/Editing','1','2018-12-13 02:07:51','2018-12-13 02:07:51');
/*!40000 ALTER TABLE `industry_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `initial_direct_cost`
--

DROP TABLE IF EXISTS `initial_direct_cost`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `initial_direct_cost` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lease_id` int(10) unsigned NOT NULL,
  `asset_id` int(10) unsigned NOT NULL,
  `initial_direct_cost_involved` enum('yes','no') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_initial_direct_cost` decimal(12,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `initial_direct_cost_lease_id_foreign` (`lease_id`),
  KEY `initial_direct_cost_asset_id_foreign` (`asset_id`),
  CONSTRAINT `initial_direct_cost_asset_id_foreign` FOREIGN KEY (`asset_id`) REFERENCES `lease_assets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `initial_direct_cost_lease_id_foreign` FOREIGN KEY (`lease_id`) REFERENCES `lease` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `initial_direct_cost`
--

LOCK TABLES `initial_direct_cost` WRITE;
/*!40000 ALTER TABLE `initial_direct_cost` DISABLE KEYS */;
INSERT INTO `initial_direct_cost` VALUES (6,19,31,'yes','INR',0.00,'2019-01-07 04:16:07','2019-01-07 04:16:14');
/*!40000 ALTER TABLE `initial_direct_cost` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB AUTO_INCREMENT=260 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
INSERT INTO `jobs` VALUES (257,'default','{\"displayName\":\"App\\\\Mail\\\\UserCreateConfirmation\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":3:{s:8:\\\"mailable\\\";O:31:\\\"App\\\\Mail\\\\UserCreateConfirmation\\\":22:{s:4:\\\"user\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":3:{s:5:\\\"class\\\";s:8:\\\"App\\\\User\\\";s:2:\\\"id\\\";i:31;s:10:\\\"connection\\\";s:5:\\\"mysql\\\";}s:4:\\\"html\\\";s:0:\\\"\\\";s:19:\\\"email_template_code\\\";s:12:\\\"USER_CREATE \\\";s:4:\\\"from\\\";a:0:{}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:24:\\\"rohit_shukla@yopmail.com\\\";}}s:2:\\\"cc\\\";a:0:{}s:3:\\\"bcc\\\";a:0:{}s:7:\\\"replyTo\\\";a:0:{}s:7:\\\"subject\\\";N;s:11:\\\"\\u0000*\\u0000markdown\\\";N;s:4:\\\"view\\\";N;s:8:\\\"textView\\\";N;s:8:\\\"viewData\\\";a:0:{}s:11:\\\"attachments\\\";a:0:{}s:14:\\\"rawAttachments\\\";a:0:{}s:9:\\\"callbacks\\\";a:0:{}s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";N;s:7:\\\"chained\\\";a:0:{}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;}\"}}',255,NULL,1546315585,1546315585),(258,'default','{\"displayName\":\"App\\\\Mail\\\\UserCreateConfirmation\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":3:{s:8:\\\"mailable\\\";O:31:\\\"App\\\\Mail\\\\UserCreateConfirmation\\\":22:{s:4:\\\"user\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":3:{s:5:\\\"class\\\";s:8:\\\"App\\\\User\\\";s:2:\\\"id\\\";i:31;s:10:\\\"connection\\\";s:5:\\\"mysql\\\";}s:4:\\\"html\\\";s:0:\\\"\\\";s:19:\\\"email_template_code\\\";s:12:\\\"USER_CREATE \\\";s:4:\\\"from\\\";a:0:{}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:24:\\\"rohit_shukla@yopmail.com\\\";}}s:2:\\\"cc\\\";a:0:{}s:3:\\\"bcc\\\";a:0:{}s:7:\\\"replyTo\\\";a:0:{}s:7:\\\"subject\\\";N;s:11:\\\"\\u0000*\\u0000markdown\\\";N;s:4:\\\"view\\\";N;s:8:\\\"textView\\\";N;s:8:\\\"viewData\\\";a:0:{}s:11:\\\"attachments\\\";a:0:{}s:14:\\\"rawAttachments\\\";a:0:{}s:9:\\\"callbacks\\\";a:0:{}s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";N;s:7:\\\"chained\\\";a:0:{}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;}\"}}',0,NULL,1546315680,1546315680),(259,'default','{\"displayName\":\"App\\\\Mail\\\\UserCreateConfirmation\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":3:{s:8:\\\"mailable\\\";O:31:\\\"App\\\\Mail\\\\UserCreateConfirmation\\\":22:{s:4:\\\"user\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":3:{s:5:\\\"class\\\";s:8:\\\"App\\\\User\\\";s:2:\\\"id\\\";i:30;s:10:\\\"connection\\\";s:5:\\\"mysql\\\";}s:4:\\\"html\\\";s:0:\\\"\\\";s:19:\\\"email_template_code\\\";s:12:\\\"USER_CREATE \\\";s:4:\\\"from\\\";a:0:{}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:27:\\\"himanshu_rajput@yopmail.com\\\";}}s:2:\\\"cc\\\";a:0:{}s:3:\\\"bcc\\\";a:0:{}s:7:\\\"replyTo\\\";a:0:{}s:7:\\\"subject\\\";N;s:11:\\\"\\u0000*\\u0000markdown\\\";N;s:4:\\\"view\\\";N;s:8:\\\"textView\\\";N;s:8:\\\"viewData\\\";a:0:{}s:11:\\\"attachments\\\";a:0:{}s:14:\\\"rawAttachments\\\";a:0:{}s:9:\\\"callbacks\\\";a:0:{}s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";N;s:7:\\\"chained\\\";a:0:{}}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;}\"}}',0,NULL,1546574324,1546574324);
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `la_similar_charac_number`
--

DROP TABLE IF EXISTS `la_similar_charac_number`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `la_similar_charac_number` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `business_account_id` int(10) unsigned NOT NULL,
  `number` int(10) unsigned NOT NULL,
  `status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `la_similar_bus_acc_id` (`business_account_id`),
  CONSTRAINT `la_similar_bus_acc_id` FOREIGN KEY (`business_account_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `la_similar_charac_number`
--

LOCK TABLES `la_similar_charac_number` WRITE;
/*!40000 ALTER TABLE `la_similar_charac_number` DISABLE KEYS */;
INSERT INTO `la_similar_charac_number` VALUES (1,30,1,'1','2018-12-25 23:22:58','2018-12-25 23:22:58'),(2,30,2,'1','2018-12-26 03:52:41','2018-12-26 03:52:41');
/*!40000 ALTER TABLE `la_similar_charac_number` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lease`
--

DROP TABLE IF EXISTS `lease`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lease` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `business_account_id` int(10) unsigned NOT NULL,
  `lessor_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lease_type_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lease_contract_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lease_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `total_assets` int(10) unsigned NOT NULL DEFAULT '0',
  `escalation_clause_applicable` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `business_account_id` (`business_account_id`),
  CONSTRAINT `lease_business_account_id_foreign` FOREIGN KEY (`business_account_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lease`
--

LOCK TABLES `lease` WRITE;
/*!40000 ALTER TABLE `lease` DISABLE KEYS */;
INSERT INTO `lease` VALUES (18,30,'Ajnara','2','INR','1546582245-960519143','','0',2,'yes','2018-12-31 01:56:03','2019-01-04 00:40:45'),(19,30,'SuperTech','2','INR','1546864122-315413039','','0',2,'yes','2018-12-31 22:52:11','2019-01-07 06:58:42'),(20,30,'Lease Latest','2','INR','1546841212-1018601149','','0',2,'no','2019-01-07 00:36:52','2019-01-07 00:36:54');
/*!40000 ALTER TABLE `lease` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lease_accounting_treatment`
--

DROP TABLE IF EXISTS `lease_accounting_treatment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lease_accounting_treatment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `upto_year` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lease_accounting_treatment`
--

LOCK TABLES `lease_accounting_treatment` WRITE;
/*!40000 ALTER TABLE `lease_accounting_treatment` DISABLE KEYS */;
INSERT INTO `lease_accounting_treatment` VALUES (1,'Operating Lease Accounting','2018','1','2018-12-18 05:25:17','2018-12-18 05:25:17'),(2,'Finance Lease Accounting','2018','1','2018-12-18 05:25:17','2018-12-18 05:25:17'),(3,'Previously Not Identified as Lease','2018','1','2018-12-18 05:25:17','2018-12-18 05:25:17');
/*!40000 ALTER TABLE `lease_accounting_treatment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lease_asset_payment_dates`
--

DROP TABLE IF EXISTS `lease_asset_payment_dates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lease_asset_payment_dates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `asset_id` int(10) unsigned NOT NULL,
  `payment_id` int(10) unsigned NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lease_asset_payment_dates_asset_id_foreign` (`asset_id`),
  KEY `lease_asset_payment_dates_payment_id_foreign` (`payment_id`),
  CONSTRAINT `lease_asset_payment_dates_asset_id_foreign` FOREIGN KEY (`asset_id`) REFERENCES `lease_assets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lease_asset_payment_dates_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `lease_assets_payments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=367 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lease_asset_payment_dates`
--

LOCK TABLES `lease_asset_payment_dates` WRITE;
/*!40000 ALTER TABLE `lease_asset_payment_dates` DISABLE KEYS */;
INSERT INTO `lease_asset_payment_dates` VALUES (305,33,30,'2020-02-01','2019-01-07 05:30:10','2019-01-07 05:30:10'),(306,33,30,'2020-05-01','2019-01-07 05:30:10','2019-01-07 05:30:10'),(307,33,30,'2018-12-19','2019-01-07 05:30:10','2019-01-07 05:30:10'),(308,33,30,'2019-12-01','2019-01-07 05:30:10','2019-01-07 05:30:10'),(309,34,31,'2019-01-01','2019-01-07 05:31:46','2019-01-07 05:31:46'),(310,34,31,'2020-01-01','2019-01-07 05:31:46','2019-01-07 05:31:46'),(311,34,31,'2019-04-01','2019-01-07 05:31:46','2019-01-07 05:31:46'),(312,34,31,'2019-07-01','2019-01-07 05:31:47','2019-01-07 05:31:47'),(313,34,31,'2018-10-21','2019-01-07 05:31:47','2019-01-07 05:31:47'),(314,34,31,'2019-10-01','2019-01-07 05:31:47','2019-01-07 05:31:47'),(315,33,32,'2019-02-01','2019-01-07 05:36:11','2019-01-07 05:36:11'),(316,33,32,'2020-02-01','2019-01-07 05:36:12','2019-01-07 05:36:12'),(317,33,32,'2019-05-01','2019-01-07 05:36:12','2019-01-07 05:36:12'),(318,33,32,'2020-05-01','2019-01-07 05:36:12','2019-01-07 05:36:12'),(319,33,32,'2019-08-01','2019-01-07 05:36:12','2019-01-07 05:36:12'),(320,33,32,'2018-11-21','2019-01-07 05:36:12','2019-01-07 05:36:12'),(321,33,32,'2019-11-01','2019-01-07 05:36:12','2019-01-07 05:36:12'),(340,32,34,'2019-03-01','2019-01-07 05:50:29','2019-01-07 05:50:29'),(341,32,34,'2019-06-01','2019-01-07 05:50:29','2019-01-07 05:50:29'),(342,32,34,'2019-08-01','2019-01-07 05:50:29','2019-01-07 05:50:29'),(352,31,33,'2019-02-20','2019-01-08 05:02:52','2019-01-08 05:02:52'),(353,31,33,'2020-02-01','2019-01-08 05:02:52','2019-01-08 05:02:52'),(354,31,33,'2021-02-01','2019-01-08 05:02:52','2019-01-08 05:02:52'),(355,31,33,'2022-02-01','2019-01-08 05:02:52','2019-01-08 05:02:52'),(356,31,33,'2019-05-01','2019-01-08 05:02:52','2019-01-08 05:02:52'),(357,31,33,'2020-05-01','2019-01-08 05:02:53','2019-01-08 05:02:53'),(358,31,33,'2021-05-01','2019-01-08 05:02:53','2019-01-08 05:02:53'),(359,31,33,'2022-05-01','2019-01-08 05:02:53','2019-01-08 05:02:53'),(360,31,33,'2019-08-01','2019-01-08 05:02:53','2019-01-08 05:02:53'),(361,31,33,'2020-08-01','2019-01-08 05:02:53','2019-01-08 05:02:53'),(362,31,33,'2021-08-01','2019-01-08 05:02:53','2019-01-08 05:02:53'),(363,31,33,'2022-09-01','2019-01-08 05:02:53','2019-01-08 05:02:53'),(364,31,33,'2019-11-01','2019-01-08 05:02:53','2019-01-08 05:02:53'),(365,31,33,'2020-11-01','2019-01-08 05:02:53','2019-01-08 05:02:53'),(366,31,33,'2021-11-01','2019-01-08 05:02:53','2019-01-08 05:02:53');
/*!40000 ALTER TABLE `lease_asset_payment_dates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lease_asset_payments_nature`
--

DROP TABLE IF EXISTS `lease_asset_payments_nature`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lease_asset_payments_nature` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lease_asset_payments_nature`
--

LOCK TABLES `lease_asset_payments_nature` WRITE;
/*!40000 ALTER TABLE `lease_asset_payments_nature` DISABLE KEYS */;
INSERT INTO `lease_asset_payments_nature` VALUES (1,'Fixed Lease Payment','1','2018-12-16 23:06:05','2018-12-16 23:06:05'),(2,'Variable Lease Payment','1','2018-12-16 23:06:05','2018-12-16 23:06:05');
/*!40000 ALTER TABLE `lease_asset_payments_nature` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lease_asset_use_master`
--

DROP TABLE IF EXISTS `lease_asset_use_master`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lease_asset_use_master` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lease_asset_use_master`
--

LOCK TABLES `lease_asset_use_master` WRITE;
/*!40000 ALTER TABLE `lease_asset_use_master` DISABLE KEYS */;
INSERT INTO `lease_asset_use_master` VALUES (1,'Own Use','1','2018-12-14 07:15:40','2018-12-14 07:15:40'),(2,'Sub-Lease','1','2018-12-14 07:15:41','2018-12-14 07:15:41');
/*!40000 ALTER TABLE `lease_asset_use_master` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lease_assets`
--

DROP TABLE IF EXISTS `lease_assets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lease_assets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lease_id` int(10) unsigned NOT NULL,
  `category_id` int(10) unsigned NOT NULL,
  `sub_category_id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `other_details` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_id` int(10) unsigned DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `specific_use` int(10) unsigned DEFAULT NULL,
  `use_of_asset` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expected_life` int(10) unsigned DEFAULT NULL,
  `lease_start_date` date DEFAULT NULL,
  `lease_free_period` int(10) unsigned DEFAULT NULL COMMENT 'holds the number of days only.',
  `accural_period` date DEFAULT NULL COMMENT 'should be lease_start_date + lease_free_period',
  `lease_end_date` date DEFAULT NULL,
  `lease_term` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `accounting_treatment` int(10) unsigned DEFAULT NULL,
  `similar_asset_items` int(10) unsigned NOT NULL,
  `is_details_completed` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `total_payments` int(10) unsigned DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lease_assets_lease_id_foreign` (`lease_id`),
  KEY `lease_assets_category_id_foreign` (`category_id`),
  KEY `sub_cat_for_key` (`sub_category_id`),
  KEY `lease_assets_country_id_foreign` (`country_id`),
  KEY `lease_assets_specific_use_foreign` (`specific_use`),
  KEY `lease_assets_expected_life_foreign` (`expected_life`),
  KEY `lease_assets_accounting_treatment_foreign` (`accounting_treatment`),
  CONSTRAINT `lease_assets_accounting_treatment_foreign` FOREIGN KEY (`accounting_treatment`) REFERENCES `lease_accounting_treatment` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lease_assets_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `lease_assets_categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lease_assets_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lease_assets_expected_life_foreign` FOREIGN KEY (`expected_life`) REFERENCES `expected_useful_life_of_asset` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lease_assets_lease_id_foreign` FOREIGN KEY (`lease_id`) REFERENCES `lease` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lease_assets_specific_use_foreign` FOREIGN KEY (`specific_use`) REFERENCES `lease_asset_use_master` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sub_cat_for_key` FOREIGN KEY (`sub_category_id`) REFERENCES `lease_assets_sub_categories_settings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lease_assets`
--

LOCK TABLES `lease_assets` WRITE;
/*!40000 ALTER TABLE `lease_assets` DISABLE KEYS */;
INSERT INTO `lease_assets` VALUES (31,'627b36e0-1248-11e9-b7b6-f74365447155',19,1,1,'Lenskart Shop','Food Court',1,'Prateek Laurel, Sector 120 Noida',1,'Runnig a shop for my own business.',1,'2019-01-01',50,'2019-02-20','2022-09-30','1 years 11 months 24 days',2,1,'1',1,'2019-01-07 01:19:45','2019-01-07 06:59:01'),(32,'627b3f40-1248-11e9-a656-e551f605c5f0',19,1,1,'Food Court','Shop on rent for the food court.',1,'Prateek Laurel, Sector 120 Noida',1,'Runnig a shop for my own business.',2,'2019-01-01',50,'2019-02-20','2019-08-31','0 years 6 months 11 days',2,1,'1',1,'2019-01-07 01:19:46','2019-01-07 05:38:12'),(33,'c0ac96e0-1248-11e9-9852-f9eaff5a6323',20,1,1,'Lenskart Shop','Food Court',1,'Prateek Laurel, Sector 120 Noida',1,'Runnig a shop for my own business.',2,'2018-11-01',20,'2018-11-21','2020-05-31','1 years 6 months 10 days',2,1,'1',2,'2019-01-07 01:22:19','2019-01-07 01:23:30'),(34,'c0ac9ca0-1248-11e9-a1bc-a7a33b84ee86',20,1,1,'Lenskart Store','Shop taken on rent for the Lenskart Showroom',2,'Prateek Laurel, Sector 120 Noida',2,NULL,3,'2018-10-01',20,'2018-10-21','2020-01-31','1 years 3 months 10 days',2,1,'1',1,'2019-01-07 01:22:19','2019-01-07 05:30:27');
/*!40000 ALTER TABLE `lease_assets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lease_assets_categories`
--

DROP TABLE IF EXISTS `lease_assets_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lease_assets_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lease_assets_categories`
--

LOCK TABLES `lease_assets_categories` WRITE;
/*!40000 ALTER TABLE `lease_assets_categories` DISABLE KEYS */;
INSERT INTO `lease_assets_categories` VALUES (1,'Tangible Properties - Land','1','2018-12-21 01:12:04','2018-12-21 01:12:04'),(2,'Tangible Properties - Other than Land','1','2018-12-21 01:12:04','2018-12-21 01:12:04'),(3,'Plants & Equipments','1','2018-12-21 01:12:04','2018-12-21 01:12:04'),(4,'Agricultural Assets','1','2018-12-21 01:12:04','2018-12-21 01:12:04'),(5,'Biological Assets','1','2018-12-21 01:12:04','2018-12-21 01:12:04'),(6,'Investment Properties','1','2018-12-21 01:12:04','2018-12-21 01:12:04'),(7,'Intangible Assets','1','2018-12-21 01:12:04','2018-12-21 01:12:04'),(8,'Intangible Assets under Licensing Arrangement','1','2018-12-21 01:12:04','2018-12-21 01:12:04');
/*!40000 ALTER TABLE `lease_assets_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lease_assets_payments`
--

DROP TABLE IF EXISTS `lease_assets_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lease_assets_payments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `asset_id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `nature` int(10) unsigned NOT NULL,
  `variable_basis` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `variable_amount_determinable` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no',
  `description` text COLLATE utf8mb4_unicode_ci,
  `payment_interval` int(10) unsigned NOT NULL,
  `payout_time` int(10) unsigned NOT NULL,
  `first_payment_start_date` date NOT NULL,
  `last_payment_end_date` date NOT NULL,
  `payment_currency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `using_lease_payment` enum('1','2') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '1 => Current Lease Payment as on Jan 01, 2019, 2=> Initial Lease Payment as on First Lease Start',
  `payment_per_interval_per_unit` decimal(12,2) NOT NULL,
  `total_amount_per_interval` decimal(12,2) NOT NULL,
  `attachment` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lease_assets_payments_asset_id_foreign` (`asset_id`),
  KEY `lease_assets_payments_type_foreign` (`type`),
  KEY `lease_assets_payments_nature_foreign` (`nature`),
  KEY `lease_assets_payments_payment_interval_foreign` (`payment_interval`),
  KEY `lease_assets_payments_payout_time_foreign` (`payout_time`),
  CONSTRAINT `lease_assets_payments_asset_id_foreign` FOREIGN KEY (`asset_id`) REFERENCES `lease_assets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lease_assets_payments_nature_foreign` FOREIGN KEY (`nature`) REFERENCES `lease_asset_payments_nature` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lease_assets_payments_payment_interval_foreign` FOREIGN KEY (`payment_interval`) REFERENCES `lease_payments_frequency` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lease_assets_payments_payout_time_foreign` FOREIGN KEY (`payout_time`) REFERENCES `lease_payment_interval` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lease_assets_payments_type_foreign` FOREIGN KEY (`type`) REFERENCES `lease_payments_components` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lease_assets_payments`
--

LOCK TABLES `lease_assets_payments` WRITE;
/*!40000 ALTER TABLE `lease_assets_payments` DISABLE KEYS */;
INSERT INTO `lease_assets_payments` VALUES (30,33,'Himanshu\'s Payment Second',1,1,NULL,'no',NULL,6,3,'2018-12-19','2020-05-01','INR','1',100.00,100.00,'','2019-01-07 05:08:02','2019-01-07 05:30:10'),(31,34,'Himanshu\'s Payment',1,1,NULL,'no','Test Description',6,3,'2018-10-21','2020-01-01','INR','1',100.00,100.00,'','2019-01-07 05:31:46','2019-01-07 05:31:46'),(32,33,'Himanshu\'s Payment Second',1,1,NULL,'no','Test Description',6,3,'2018-11-21','2020-05-01','INR','1',100.00,100.00,'','2019-01-07 05:36:11','2019-01-07 05:36:11'),(33,31,'Himanshu\'s Payment Second',1,1,NULL,'no','Test Description',6,3,'2019-02-20','2022-09-01','INR','1',500.00,500.00,'','2019-01-07 05:38:02','2019-01-08 05:02:52'),(34,32,'Himanshu\'s Payment',1,1,NULL,'no','Test Description',6,3,'2019-03-01','2019-08-01','INR','1',500.00,500.00,'','2019-01-07 05:38:59','2019-01-07 05:50:29');
/*!40000 ALTER TABLE `lease_assets_payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lease_assets_sub_categories_settings`
--

DROP TABLE IF EXISTS `lease_assets_sub_categories_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lease_assets_sub_categories_settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `business_account_id` int(10) unsigned NOT NULL,
  `category_id` int(10) unsigned NOT NULL,
  `depreciation_method_id` int(10) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lease_asset_bu_ac_id` (`business_account_id`),
  KEY `lease_assets_sub_categories_settings_category_id_foreign` (`category_id`),
  KEY `lease_asset_dep_met_id` (`depreciation_method_id`),
  CONSTRAINT `lease_asset_bu_ac_id` FOREIGN KEY (`business_account_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lease_asset_dep_met_id` FOREIGN KEY (`depreciation_method_id`) REFERENCES `depreciation_method` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lease_assets_sub_categories_settings_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `lease_assets_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lease_assets_sub_categories_settings`
--

LOCK TABLES `lease_assets_sub_categories_settings` WRITE;
/*!40000 ALTER TABLE `lease_assets_sub_categories_settings` DISABLE KEYS */;
INSERT INTO `lease_assets_sub_categories_settings` VALUES (1,30,1,1,'Land','2018-12-26 04:13:05','2018-12-26 04:13:05'),(2,30,2,1,'Building','2018-12-26 04:13:18','2018-12-26 04:13:18'),(3,30,3,1,'Machinery','2018-12-26 04:13:45','2018-12-26 04:13:45'),(4,30,3,1,'Motor Vehicle','2018-12-26 04:13:56','2018-12-26 04:13:56'),(5,30,3,1,'Computer Hardware','2018-12-26 04:14:08','2018-12-26 04:14:08'),(6,30,6,1,'Hotel Building','2018-12-26 04:14:43','2018-12-26 04:14:43'),(7,30,7,1,'Softwares','2018-12-26 04:14:54','2018-12-26 04:14:54'),(8,30,8,1,'Intellectual Property','2018-12-26 04:15:07','2018-12-26 04:15:07'),(9,30,8,1,'Motion Picture','2018-12-26 04:15:15','2018-12-26 04:15:15'),(10,30,8,1,'Video Recordings','2018-12-26 04:15:21','2018-12-26 04:15:21'),(11,30,8,1,'Plays','2018-12-26 04:15:28','2018-12-26 04:15:28'),(12,30,8,1,'Manuscripts','2018-12-26 04:15:35','2018-12-26 04:15:35'),(13,30,8,1,'Patents','2018-12-26 04:15:42','2018-12-26 04:15:42'),(14,30,8,1,'Copyrights','2018-12-26 04:15:49','2018-12-26 04:15:49'),(15,30,5,1,'Bearer Plants','2018-12-26 04:15:59','2018-12-26 04:15:59'),(16,30,4,1,'Tractors','2018-12-26 04:16:14','2018-12-26 04:16:14'),(17,30,4,1,'Tools','2018-12-26 04:16:27','2018-12-26 04:16:27');
/*!40000 ALTER TABLE `lease_assets_sub_categories_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lease_balance_as_on_dec`
--

DROP TABLE IF EXISTS `lease_balance_as_on_dec`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lease_balance_as_on_dec` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lease_id` int(10) unsigned NOT NULL,
  `asset_id` int(10) unsigned NOT NULL,
  `reporting_currency` enum('1','2') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `carrying_amount` int(10) unsigned DEFAULT NULL,
  `liability_balance` int(10) unsigned DEFAULT NULL,
  `prepaid_lease_payment_balance` int(10) unsigned DEFAULT NULL,
  `accrued_lease_payment_balance` int(10) unsigned DEFAULT NULL,
  `outstanding_lease_payment_balance` int(10) unsigned DEFAULT NULL,
  `any_provision_for_onerous_lease` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lease_balance_as_on_dec_lease_id_foreign` (`lease_id`),
  KEY `lease_balance_as_on_dec_asset_id_foreign` (`asset_id`),
  CONSTRAINT `lease_balance_as_on_dec_asset_id_foreign` FOREIGN KEY (`asset_id`) REFERENCES `lease_assets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lease_balance_as_on_dec_lease_id_foreign` FOREIGN KEY (`lease_id`) REFERENCES `lease` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lease_balance_as_on_dec`
--

LOCK TABLES `lease_balance_as_on_dec` WRITE;
/*!40000 ALTER TABLE `lease_balance_as_on_dec` DISABLE KEYS */;
/*!40000 ALTER TABLE `lease_balance_as_on_dec` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lease_contract_duration`
--

DROP TABLE IF EXISTS `lease_contract_duration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lease_contract_duration` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lower_limit` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Number of Months Only',
  `upper_limit` int(10) unsigned DEFAULT NULL COMMENT 'Number of Months Only, IF NULL Means can go from lower_limit and can be above that',
  `month_range_description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Ex : 1 Month or Less',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lease_contract_duration`
--

LOCK TABLES `lease_contract_duration` WRITE;
/*!40000 ALTER TABLE `lease_contract_duration` DISABLE KEYS */;
INSERT INTO `lease_contract_duration` VALUES (1,0,1,'1 Month or Less','Very Short Term Lease','1','2019-01-03 01:00:36','2019-01-03 01:00:36'),(2,1,12,'Above 1 to 12 Months','Short Term Lease','1','2019-01-03 01:00:36','2019-01-03 01:00:36'),(3,12,NULL,'Above 12 Months','Long-Term Lease','1','2019-01-03 01:00:36','2019-01-03 01:00:36');
/*!40000 ALTER TABLE `lease_contract_duration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lease_duarion_classified`
--

DROP TABLE IF EXISTS `lease_duarion_classified`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lease_duarion_classified` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lease_id` int(10) unsigned NOT NULL,
  `asset_id` int(10) unsigned NOT NULL,
  `lease_start_date` date DEFAULT NULL,
  `lease_end_date` date DEFAULT NULL,
  `lease_contract_duration_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lease_duarion_classified_lease_id_foreign` (`lease_id`),
  KEY `lease_duarion_classified_asset_id_foreign` (`asset_id`),
  KEY `lcdi_foreign_key` (`lease_contract_duration_id`),
  CONSTRAINT `lcdi_foreign_key` FOREIGN KEY (`lease_contract_duration_id`) REFERENCES `lease_contract_duration` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lease_duarion_classified_asset_id_foreign` FOREIGN KEY (`asset_id`) REFERENCES `lease_assets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lease_duarion_classified_lease_id_foreign` FOREIGN KEY (`lease_id`) REFERENCES `lease` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lease_duarion_classified`
--

LOCK TABLES `lease_duarion_classified` WRITE;
/*!40000 ALTER TABLE `lease_duarion_classified` DISABLE KEYS */;
INSERT INTO `lease_duarion_classified` VALUES (5,19,31,'2019-01-01','2019-07-31',2,'2019-01-07 05:39:39','2019-01-07 05:39:39'),(6,19,32,'2019-01-01','2019-08-31',2,'2019-01-07 05:39:42','2019-01-07 05:39:42');
/*!40000 ALTER TABLE `lease_duarion_classified` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lease_incentives`
--

DROP TABLE IF EXISTS `lease_incentives`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lease_incentives` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lease_id` int(10) unsigned NOT NULL,
  `asset_id` int(10) unsigned NOT NULL,
  `is_any_lease_incentives_receivable` enum('yes','no') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_lease_incentives` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lease_incentives_lease_id_foreign` (`lease_id`),
  KEY `lease_incentives_asset_id_foreign` (`asset_id`),
  CONSTRAINT `lease_incentives_asset_id_foreign` FOREIGN KEY (`asset_id`) REFERENCES `lease_assets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lease_incentives_lease_id_foreign` FOREIGN KEY (`lease_id`) REFERENCES `lease` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lease_incentives`
--

LOCK TABLES `lease_incentives` WRITE;
/*!40000 ALTER TABLE `lease_incentives` DISABLE KEYS */;
/*!40000 ALTER TABLE `lease_incentives` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lease_lock_year`
--

DROP TABLE IF EXISTS `lease_lock_year`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lease_lock_year` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `business_account_id` int(10) unsigned NOT NULL,
  `audit_year1_ended_on` date NOT NULL,
  `audit_year2_ended_on` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lease_lock_year_business_account_id_foreign` (`business_account_id`),
  CONSTRAINT `lease_lock_year_business_account_id_foreign` FOREIGN KEY (`business_account_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lease_lock_year`
--

LOCK TABLES `lease_lock_year` WRITE;
/*!40000 ALTER TABLE `lease_lock_year` DISABLE KEYS */;
/*!40000 ALTER TABLE `lease_lock_year` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lease_modification_reasons`
--

DROP TABLE IF EXISTS `lease_modification_reasons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lease_modification_reasons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lease_modification_reasons`
--

LOCK TABLES `lease_modification_reasons` WRITE;
/*!40000 ALTER TABLE `lease_modification_reasons` DISABLE KEYS */;
/*!40000 ALTER TABLE `lease_modification_reasons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lease_payment_interval`
--

DROP TABLE IF EXISTS `lease_payment_interval`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lease_payment_interval` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lease_payment_interval`
--

LOCK TABLES `lease_payment_interval` WRITE;
/*!40000 ALTER TABLE `lease_payment_interval` DISABLE KEYS */;
INSERT INTO `lease_payment_interval` VALUES (1,'At Lease Interval Start','1','2018-12-18 07:18:55','2018-12-18 07:18:55'),(2,'At Lease Interval End','1','2018-12-18 07:18:55','2018-12-18 07:18:55'),(3,'Custom','1','2019-01-06 18:30:00','2019-01-06 18:30:00');
/*!40000 ALTER TABLE `lease_payment_interval` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lease_payment_invoice`
--

DROP TABLE IF EXISTS `lease_payment_invoice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lease_payment_invoice` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lease_id` int(10) unsigned NOT NULL,
  `asset_id` int(10) unsigned NOT NULL,
  `lease_payment_invoice_received` enum('yes','no') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lease_payment_invoice_lease_id_foreign` (`lease_id`),
  KEY `lease_payment_invoice_asset_id_foreign` (`asset_id`),
  CONSTRAINT `lease_payment_invoice_asset_id_foreign` FOREIGN KEY (`asset_id`) REFERENCES `lease_assets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lease_payment_invoice_lease_id_foreign` FOREIGN KEY (`lease_id`) REFERENCES `lease` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lease_payment_invoice`
--

LOCK TABLES `lease_payment_invoice` WRITE;
/*!40000 ALTER TABLE `lease_payment_invoice` DISABLE KEYS */;
/*!40000 ALTER TABLE `lease_payment_invoice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lease_payments_basis`
--

DROP TABLE IF EXISTS `lease_payments_basis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lease_payments_basis` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `business_account_id` int(10) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lease_payments_basis_business_account_id_foreign` (`business_account_id`),
  CONSTRAINT `lease_payments_basis_business_account_id_foreign` FOREIGN KEY (`business_account_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lease_payments_basis`
--

LOCK TABLES `lease_payments_basis` WRITE;
/*!40000 ALTER TABLE `lease_payments_basis` DISABLE KEYS */;
INSERT INTO `lease_payments_basis` VALUES (1,30,'Turnover Lease','1','2018-12-25 23:22:58','2018-12-25 23:22:58'),(2,30,'Actual Usage Basis','1','2018-12-25 23:22:58','2018-12-25 23:22:58');
/*!40000 ALTER TABLE `lease_payments_basis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lease_payments_components`
--

DROP TABLE IF EXISTS `lease_payments_components`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lease_payments_components` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lease_payments_components`
--

LOCK TABLES `lease_payments_components` WRITE;
/*!40000 ALTER TABLE `lease_payments_components` DISABLE KEYS */;
INSERT INTO `lease_payments_components` VALUES (1,'Lease Component','1','2018-12-14 07:32:28','2018-12-14 07:32:28'),(2,'Non-Lease Component','1','2018-12-14 07:32:28','2018-12-14 07:32:28'),(3,'Lease & Non-Lease Component','1','2018-12-14 07:32:28','2018-12-14 07:32:28');
/*!40000 ALTER TABLE `lease_payments_components` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lease_payments_escalation_clause`
--

DROP TABLE IF EXISTS `lease_payments_escalation_clause`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lease_payments_escalation_clause` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lease_payments_escalation_clause`
--

LOCK TABLES `lease_payments_escalation_clause` WRITE;
/*!40000 ALTER TABLE `lease_payments_escalation_clause` DISABLE KEYS */;
INSERT INTO `lease_payments_escalation_clause` VALUES (1,'Lease Component','1','2018-12-18 07:34:01','2018-12-18 07:34:01'),(2,'Lease & Non-Lease Component','1','2018-12-18 07:34:02','2018-12-18 07:34:02');
/*!40000 ALTER TABLE `lease_payments_escalation_clause` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lease_payments_frequency`
--

DROP TABLE IF EXISTS `lease_payments_frequency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lease_payments_frequency` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lease_payments_frequency`
--

LOCK TABLES `lease_payments_frequency` WRITE;
/*!40000 ALTER TABLE `lease_payments_frequency` DISABLE KEYS */;
INSERT INTO `lease_payments_frequency` VALUES (1,'One-Time','1','2018-12-18 07:05:23','2018-12-18 07:05:23'),(2,'Monthly','1','2018-12-18 07:05:23','2018-12-18 07:05:23'),(3,'Quarterly','1','2018-12-18 07:05:23','2018-12-18 07:05:23'),(4,'Semi-Annualy','1','2018-12-18 07:05:23','2018-12-18 07:05:23'),(5,'Annualy','1','2018-12-18 07:05:24','2018-12-18 07:05:24'),(6,'Custom','1',NULL,NULL);
/*!40000 ALTER TABLE `lease_payments_frequency` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lease_renewal_option`
--

DROP TABLE IF EXISTS `lease_renewal_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lease_renewal_option` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lease_id` int(10) unsigned NOT NULL,
  `asset_id` int(10) unsigned NOT NULL,
  `is_renewal_option_under_contract` enum('yes','no') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `renewal_option_not_available_reason` text COLLATE utf8mb4_unicode_ci,
  `is_reasonable_certainity_option` enum('yes','no') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expected_lease_end_Date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lease_renewal_option_lease_id_foreign` (`lease_id`),
  KEY `lease_renewal_option_asset_id_foreign` (`asset_id`),
  CONSTRAINT `lease_renewal_option_asset_id_foreign` FOREIGN KEY (`asset_id`) REFERENCES `lease_assets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lease_renewal_option_lease_id_foreign` FOREIGN KEY (`lease_id`) REFERENCES `lease` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lease_renewal_option`
--

LOCK TABLES `lease_renewal_option` WRITE;
/*!40000 ALTER TABLE `lease_renewal_option` DISABLE KEYS */;
/*!40000 ALTER TABLE `lease_renewal_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lease_residual_value_gurantee`
--

DROP TABLE IF EXISTS `lease_residual_value_gurantee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lease_residual_value_gurantee` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lease_id` int(10) unsigned NOT NULL,
  `asset_id` int(10) unsigned NOT NULL,
  `any_residual_value_gurantee` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lease_payemnt_nature_id` int(10) unsigned DEFAULT NULL,
  `amount_determinable` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `similar_asset_items` int(10) unsigned NOT NULL,
  `residual_gurantee_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_residual_gurantee_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `other_desc` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lease_residual_value_gurantee_lease_id_foreign` (`lease_id`),
  KEY `lease_residual_value_gurantee_asset_id_foreign` (`asset_id`),
  KEY `lease_residual_value_gurantee_lease_payemnt_nature_id_foreign` (`lease_payemnt_nature_id`),
  CONSTRAINT `lease_residual_value_gurantee_asset_id_foreign` FOREIGN KEY (`asset_id`) REFERENCES `lease_assets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lease_residual_value_gurantee_lease_id_foreign` FOREIGN KEY (`lease_id`) REFERENCES `lease` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lease_residual_value_gurantee_lease_payemnt_nature_id_foreign` FOREIGN KEY (`lease_payemnt_nature_id`) REFERENCES `lease_asset_payments_nature` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lease_residual_value_gurantee`
--

LOCK TABLES `lease_residual_value_gurantee` WRITE;
/*!40000 ALTER TABLE `lease_residual_value_gurantee` DISABLE KEYS */;
INSERT INTO `lease_residual_value_gurantee` VALUES (3,19,31,'no',NULL,NULL,'INR',1,NULL,NULL,NULL,'','2019-01-07 05:39:20','2019-01-07 05:39:20'),(4,19,32,'no',NULL,NULL,'INR',1,NULL,NULL,NULL,'','2019-01-07 05:39:23','2019-01-07 05:39:23');
/*!40000 ALTER TABLE `lease_residual_value_gurantee` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lease_select_discount_rate`
--

DROP TABLE IF EXISTS `lease_select_discount_rate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lease_select_discount_rate` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lease_id` int(10) unsigned NOT NULL,
  `asset_id` int(10) unsigned NOT NULL,
  `interest_rate` int(10) unsigned DEFAULT NULL,
  `annual_average_esclation_rate` int(10) unsigned DEFAULT NULL,
  `discount_rate_to_use` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lease_select_discount_rate_lease_id_foreign` (`lease_id`),
  KEY `lease_select_discount_rate_asset_id_foreign` (`asset_id`),
  CONSTRAINT `lease_select_discount_rate_asset_id_foreign` FOREIGN KEY (`asset_id`) REFERENCES `lease_assets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lease_select_discount_rate_lease_id_foreign` FOREIGN KEY (`lease_id`) REFERENCES `lease` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lease_select_discount_rate`
--

LOCK TABLES `lease_select_discount_rate` WRITE;
/*!40000 ALTER TABLE `lease_select_discount_rate` DISABLE KEYS */;
/*!40000 ALTER TABLE `lease_select_discount_rate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lease_select_low_value`
--

DROP TABLE IF EXISTS `lease_select_low_value`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lease_select_low_value` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lease_id` int(10) unsigned NOT NULL,
  `asset_id` int(10) unsigned NOT NULL,
  `fair_market_value` int(10) unsigned DEFAULT NULL,
  `undiscounted_lease_payment` int(10) unsigned DEFAULT NULL,
  `is_classify_under_low_value` enum('yes','no') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lease_select_low_value_lease_id_foreign` (`lease_id`),
  KEY `lease_select_low_value_asset_id_foreign` (`asset_id`),
  CONSTRAINT `lease_select_low_value_asset_id_foreign` FOREIGN KEY (`asset_id`) REFERENCES `lease_assets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lease_select_low_value_lease_id_foreign` FOREIGN KEY (`lease_id`) REFERENCES `lease` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lease_select_low_value`
--

LOCK TABLES `lease_select_low_value` WRITE;
/*!40000 ALTER TABLE `lease_select_low_value` DISABLE KEYS */;
/*!40000 ALTER TABLE `lease_select_low_value` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lease_termination_option`
--

DROP TABLE IF EXISTS `lease_termination_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lease_termination_option` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lease_id` int(10) unsigned NOT NULL,
  `asset_id` int(10) unsigned NOT NULL,
  `lease_termination_option_available` enum('yes','no') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `exercise_termination_option_available` enum('yes','no') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `termination_penalty_applicable` enum('yes','no') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lease_end_date` date DEFAULT NULL,
  `currency` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `termination_penalty` decimal(12,2) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lease_termination_option_lease_id_foreign` (`lease_id`),
  KEY `lease_termination_option_asset_id_foreign` (`asset_id`),
  CONSTRAINT `lease_termination_option_asset_id_foreign` FOREIGN KEY (`asset_id`) REFERENCES `lease_assets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lease_termination_option_lease_id_foreign` FOREIGN KEY (`lease_id`) REFERENCES `lease` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lease_termination_option`
--

LOCK TABLES `lease_termination_option` WRITE;
/*!40000 ALTER TABLE `lease_termination_option` DISABLE KEYS */;
INSERT INTO `lease_termination_option` VALUES (5,19,31,'no',NULL,NULL,NULL,'INR',NULL,'2019-01-07 05:39:30','2019-01-07 05:39:30'),(6,19,32,'no',NULL,NULL,NULL,'INR',NULL,'2019-01-07 05:39:33','2019-01-07 05:39:33');
/*!40000 ALTER TABLE `lease_termination_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leases_excluded_from_transitional_valuation`
--

DROP TABLE IF EXISTS `leases_excluded_from_transitional_valuation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leases_excluded_from_transitional_valuation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value_for` enum('lease_asset_level','lease_payment') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'lease_asset_level',
  `status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leases_excluded_from_transitional_valuation`
--

LOCK TABLES `leases_excluded_from_transitional_valuation` WRITE;
/*!40000 ALTER TABLE `leases_excluded_from_transitional_valuation` DISABLE KEYS */;
INSERT INTO `leases_excluded_from_transitional_valuation` VALUES (1,'Investment Property Using Fair Value Model','lease_asset_level','1','2018-12-18 05:08:27','2018-12-18 05:08:27'),(2,'Low Value Asset Lease','lease_asset_level','1','2018-12-18 05:08:27','2018-12-18 05:08:27'),(3,'Short Term Lease Contract','lease_asset_level','1','2018-12-18 05:08:27','2018-12-18 05:08:27'),(4,'Variable Lease Payments','lease_payment','1','2018-12-18 05:08:27','2018-12-18 05:08:27'),(5,'Ended on December 31, 2018','lease_payment','1','2018-12-18 05:08:27','2018-12-18 05:08:27');
/*!40000 ALTER TABLE `leases_excluded_from_transitional_valuation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=115 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (2,'2014_10_12_100000_create_password_resets_table',1),(5,'2018_10_30_053854_create_table_email_templates',1),(6,'2018_10_30_060542_add_columns_to_table_email_templates',1),(7,'2018_10_30_124038_create_table_security_questions',1),(8,'2018_10_31_071721_create_table_invoice_templates',1),(10,'2018_11_01_084529_drop_column_phone_number_from_users_table',1),(11,'2018_11_02_060310_add_address_columns_to_users_table',1),(12,'2018_11_02_085555_drop_column_country_code_from_users_table',1),(13,'2018_11_02_101910_add_column_email_verification_code_to_users_table',1),(14,'2018_11_02_115505_create_jobs_table',1),(15,'2018_11_14_063237_add_column_profile_pic_to_users_table',2),(16,'2018_11_14_130144_alter_column_postal_code_on_users_table',3),(18,'2018_11_19_110636_create_table_information_page',4),(20,'2018_12_13_044110_create_table_countries',5),(21,'2018_12_13_072113_create_table_industry_type',6),(22,'2018_12_13_103338_create_table_currencies',7),(23,'2014_10_12_000000_create_users_table',8),(24,'2018_10_24_100151_add_column_user_type_to_users_table',9),(25,'2018_12_13_104839_alter_users_table_column_to_have_business_accounts_details',10),(26,'2018_12_13_123145_create_verify_users_table',11),(27,'2018_12_13_123301_add_verified_column_to_users_table',11),(28,'2018_12_13_124015_add_verified_column_to_users_table',12),(29,'2018_12_14_082416_create_table_general_settings',13),(30,'2018_12_14_114257_create_table_rate_types',14),(31,'2018_12_14_121727_create_table_contract_classifications',15),(32,'2018_12_14_122627_create_table_use_of_lease_asset',16),(33,'2018_12_14_125935_create_table_lease_payments_components',17),(34,'2018_12_17_043241_create_table_nature_of_lease_payments',18),(35,'2018_12_17_060611_create_table_lease_payments_basis',19),(36,'2018_12_17_061457_create_contact_us_table',20),(37,'2018_12_18_043725_create_table_number_of_underlying_lease_asset_settings',21),(38,'2018_12_18_064751_create_table_la_similar_charac_number',22),(39,'2018_12_18_074624_create_table_contract_escalation_basis',23),(40,'2018_12_18_084324_create_table_lease_contract_duration',24),(42,'2018_12_18_100050_create_table_leases_excluded_from_transitional_valuation',25),(43,'2018_12_18_105044_create_table_lease_accounting_treatment',26),(44,'2018_12_18_111258_create_table_lease_payments_number_setting',27),(45,'2018_12_18_122925_create_table_lease_payments_frequency',28),(47,'2018_12_18_124414_create_table_lease_payment_interval',29),(48,'2018_12_18_130009_create_table_lease_payments_escalation_clause',30),(49,'2018_12_19_035052_create_table_escalation_amount_calculated_on',31),(50,'2018_12_19_042913_create_table_escalation_percentage_settings',32),(52,'2018_12_19_072755_create_table_reporting_currency_settings',33),(53,'2018_12_19_090731_add_columns_to_reporting_currency_settings',34),(54,'2018_12_20_071734_create_table_foreign_currency_transaction_settings',35),(55,'2018_12_20_093311_add_column_is_foreign_transaction_involved_to_reporting_currency_settings',36),(56,'2018_12_21_035048_create_table_expected_life_of_asset',37),(57,'2018_12_21_062055_creat_table_lease_assets_categories',38),(59,'2018_12_21_084311_create_table_depreciation_method',39),(60,'2018_12_21_094809_create_table_lease_assets_sub_categories_settings',40),(61,'2018_12_21_100010_add_column_depreciation_method_to_table_lease_assets_sub_categories_settings',41),(62,'2018_12_19_072855_entrust_setup_tables',42),(63,'2018_12_20_063507_add_column_parrent_id_to_table_users',42),(64,'2018_12_20_064018_users',42),(65,'2018_12_26_071724_create_lease_table',43),(68,'2018_12_26_103122_create_table_lease_assets',44),(69,'2018_12_27_045753_add_columns_to_table_lease_assets',45),(70,'2018_12_27_075836_add_column_is_details_completed_to_lease_assets',46),(71,'2018_12_26_085701_add_max_previous_lease_start_year_to_general_settings',47),(72,'2018_12_26_095422_create_lease_lock_year_table',47),(73,'2018_12_28_071043_drop_currency_column_from_users_table',48),(76,'2018_12_28_131547_add_column_total_assets_to_lease_table',50),(77,'2018_12_31_045537_add_column_total_payments_to_lease_assets_table',51),(78,'2018_12_28_110018_create_table_lease_assets_payments',52),(79,'2018_12_28_082204_create_table_fair_market_value',53),(80,'2018_12_31_090808_add_columns_to_fair_market_vlue_table',54),(85,'2019_01_01_041733_create_lease_renewal_option_table',55),(87,'2018_12_31_050409_create_residual__value_gurantee_table',56),(88,'2019_01_01_130717_create_table_lease_asset_payment_due_dates',57),(89,'2018_12_31_120010_lease_termination_option',58),(90,'2019_01_01_085419_create_table_purchase_option',58),(91,'2019_01_02_064813_add_column_to_table_lease_renewal_option',59),(93,'2019_01_01_122355_create_lease_duration_classifed_table',60),(94,'2019_01_02_121523_add_escalation_clause_applicable_to_lease_table',61),(95,'2019_01_03_050308_create_lease_select_low_value_table',62),(96,'2019_01_03_085843_create_lease_select_discount_rate_table',62),(97,'2019_01_03_112355_create_lease_balance_as_on_dec_table',62),(98,'2019_01_03_120220_create_table_initial_direct_cost',63),(99,'2019_01_04_093246_create_table_lease_payment_invoice',63),(101,'2019_01_04_102955_create_table_supplier_details',64),(102,'2019_01_07_045446_create_trigger_on_supplier_details',65),(103,'2019_01_07_051901_create_after_delete_on_supplier_details',66),(104,'2019_01_04_051022_create_lease_incentives_table',67),(105,'2019_01_04_104836_create_customer_incentives_table',67),(106,'2019_01_08_143557_create_table_payment_escalation',67),(107,'2019_01_08_041009_create_customer_details_table',68),(109,'2019_01_09_074544_create_table_payment_escalation_dates',69),(110,'2019_01_09_072116_create_modify_lease_applcation_table',70),(111,'2019_01_10_043432_create_table_escalation_frequency',71),(112,'2019_01_11_072702_create_table_payment_escalation_inconsitent_inputs',72),(113,'2019_01_10_055757_create_lease_modification_reasons_table',73),(114,'2019_01_11_055617_create_categories_lease_asset_excluded_table',73);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modify_lease_application`
--

DROP TABLE IF EXISTS `modify_lease_application`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `modify_lease_application` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lease_id` int(10) unsigned NOT NULL,
  `valuation` enum('Modify Initial Valuation','Subsequent Valuation') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `effective_from` date DEFAULT NULL,
  `reason` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `modify_lease_application_lease_id_foreign` (`lease_id`),
  CONSTRAINT `modify_lease_application_lease_id_foreign` FOREIGN KEY (`lease_id`) REFERENCES `lease` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modify_lease_application`
--

LOCK TABLES `modify_lease_application` WRITE;
/*!40000 ALTER TABLE `modify_lease_application` DISABLE KEYS */;
/*!40000 ALTER TABLE `modify_lease_application` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `no_of_lease_payments`
--

DROP TABLE IF EXISTS `no_of_lease_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `no_of_lease_payments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `business_account_id` int(10) unsigned NOT NULL,
  `number` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `no_of_lease_payments_bus_acc_id` (`business_account_id`),
  CONSTRAINT `no_of_lease_payments_bus_acc_id` FOREIGN KEY (`business_account_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `no_of_lease_payments`
--

LOCK TABLES `no_of_lease_payments` WRITE;
/*!40000 ALTER TABLE `no_of_lease_payments` DISABLE KEYS */;
INSERT INTO `no_of_lease_payments` VALUES (1,30,1,'2018-12-25 23:22:58','2018-12-25 23:22:58'),(2,30,2,'2018-12-27 05:41:49','2018-12-27 05:41:49'),(3,30,3,'2018-12-27 05:41:56','2018-12-27 05:41:56'),(4,30,4,'2018-12-27 05:42:02','2018-12-27 05:42:02'),(5,30,5,'2018-12-27 05:42:09','2018-12-27 05:42:09');
/*!40000 ALTER TABLE `no_of_lease_payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_escalation`
--

DROP TABLE IF EXISTS `payment_escalation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_escalation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lease_id` int(10) unsigned NOT NULL,
  `asset_id` int(10) unsigned NOT NULL,
  `payment_id` int(10) unsigned NOT NULL,
  `is_escalation_applicable` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL,
  `effective_from` date DEFAULT NULL,
  `escalation_basis` int(10) unsigned DEFAULT NULL,
  `escalation_rate_type` int(10) unsigned DEFAULT NULL,
  `is_escalation_applied_annually_consistently` enum('yes','no') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fixed_rate` decimal(12,2) DEFAULT NULL,
  `current_variable_rate` decimal(12,2) DEFAULT NULL,
  `total_escalation_rate` decimal(12,2) DEFAULT NULL,
  `amount_based_currency` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `escalated_amount` decimal(12,2) DEFAULT NULL,
  `escalation_currency` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_undiscounted_lease_payment_amount` decimal(12,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payment_escalation_lease_id_foreign` (`lease_id`),
  KEY `payment_escalation_asset_id_foreign` (`asset_id`),
  KEY `payment_escalation_payment_id_foreign` (`payment_id`),
  KEY `esca_basis_fo_id` (`escalation_basis`),
  KEY `esca_rate_type_fo_id` (`escalation_rate_type`),
  CONSTRAINT `esca_basis_fo_id` FOREIGN KEY (`escalation_basis`) REFERENCES `contract_escalation_basis` (`id`) ON DELETE CASCADE,
  CONSTRAINT `esca_rate_type_fo_id` FOREIGN KEY (`escalation_rate_type`) REFERENCES `rate_types` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payment_escalation_asset_id_foreign` FOREIGN KEY (`asset_id`) REFERENCES `lease_assets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payment_escalation_lease_id_foreign` FOREIGN KEY (`lease_id`) REFERENCES `lease` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payment_escalation_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `lease_assets_payments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_escalation`
--

LOCK TABLES `payment_escalation` WRITE;
/*!40000 ALTER TABLE `payment_escalation` DISABLE KEYS */;
INSERT INTO `payment_escalation` VALUES (7,19,31,33,'yes','2019-01-09',2,NULL,'no',NULL,NULL,NULL,'INR',10.00,'INR',7710.00,'2019-01-09 05:53:38','2019-01-11 05:45:51');
/*!40000 ALTER TABLE `payment_escalation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_escalation_dates`
--

DROP TABLE IF EXISTS `payment_escalation_dates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_escalation_dates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `payment_id` int(10) unsigned NOT NULL,
  `escalation_year` int(10) unsigned NOT NULL,
  `escalation_month` int(10) unsigned NOT NULL,
  `percentage_or_amount_based` enum('percentage','amount') COLLATE utf8mb4_unicode_ci NOT NULL,
  `value_escalated` decimal(12,2) NOT NULL,
  `total_amount_payable` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payment_escalation_dates_payment_id_foreign` (`payment_id`),
  CONSTRAINT `payment_escalation_dates_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `lease_assets_payments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=817 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_escalation_dates`
--

LOCK TABLES `payment_escalation_dates` WRITE;
/*!40000 ALTER TABLE `payment_escalation_dates` DISABLE KEYS */;
INSERT INTO `payment_escalation_dates` VALUES (769,33,2019,1,'amount',0.00,0.00,'2019-01-11 05:45:52','2019-01-11 05:45:52'),(770,33,2019,2,'amount',10.00,510.00,'2019-01-11 05:45:52','2019-01-11 05:45:52'),(771,33,2019,3,'amount',10.00,0.00,'2019-01-11 05:45:52','2019-01-11 05:45:52'),(772,33,2019,4,'amount',10.00,0.00,'2019-01-11 05:45:52','2019-01-11 05:45:52'),(773,33,2019,5,'amount',10.00,510.00,'2019-01-11 05:45:52','2019-01-11 05:45:52'),(774,33,2019,6,'amount',10.00,0.00,'2019-01-11 05:45:52','2019-01-11 05:45:52'),(775,33,2019,7,'amount',10.00,0.00,'2019-01-11 05:45:52','2019-01-11 05:45:52'),(776,33,2019,8,'amount',10.00,510.00,'2019-01-11 05:45:52','2019-01-11 05:45:52'),(777,33,2019,9,'amount',10.00,0.00,'2019-01-11 05:45:52','2019-01-11 05:45:52'),(778,33,2019,10,'amount',10.00,0.00,'2019-01-11 05:45:52','2019-01-11 05:45:52'),(779,33,2019,11,'amount',10.00,510.00,'2019-01-11 05:45:52','2019-01-11 05:45:52'),(780,33,2019,12,'amount',10.00,0.00,'2019-01-11 05:45:52','2019-01-11 05:45:52'),(781,33,2020,1,'amount',10.00,0.00,'2019-01-11 05:45:52','2019-01-11 05:45:52'),(782,33,2020,2,'amount',10.00,510.00,'2019-01-11 05:45:52','2019-01-11 05:45:52'),(783,33,2020,3,'amount',10.00,0.00,'2019-01-11 05:45:52','2019-01-11 05:45:52'),(784,33,2020,4,'amount',10.00,0.00,'2019-01-11 05:45:52','2019-01-11 05:45:52'),(785,33,2020,5,'amount',10.00,510.00,'2019-01-11 05:45:52','2019-01-11 05:45:52'),(786,33,2020,6,'amount',10.00,0.00,'2019-01-11 05:45:52','2019-01-11 05:45:52'),(787,33,2020,7,'amount',10.00,0.00,'2019-01-11 05:45:52','2019-01-11 05:45:52'),(788,33,2020,8,'amount',10.00,510.00,'2019-01-11 05:45:53','2019-01-11 05:45:53'),(789,33,2020,9,'amount',10.00,0.00,'2019-01-11 05:45:53','2019-01-11 05:45:53'),(790,33,2020,10,'amount',10.00,0.00,'2019-01-11 05:45:53','2019-01-11 05:45:53'),(791,33,2020,11,'amount',10.00,510.00,'2019-01-11 05:45:53','2019-01-11 05:45:53'),(792,33,2020,12,'amount',10.00,0.00,'2019-01-11 05:45:53','2019-01-11 05:45:53'),(793,33,2021,1,'amount',10.00,0.00,'2019-01-11 05:45:53','2019-01-11 05:45:53'),(794,33,2021,2,'amount',10.00,510.00,'2019-01-11 05:45:53','2019-01-11 05:45:53'),(795,33,2021,3,'amount',10.00,0.00,'2019-01-11 05:45:53','2019-01-11 05:45:53'),(796,33,2021,4,'amount',10.00,0.00,'2019-01-11 05:45:53','2019-01-11 05:45:53'),(797,33,2021,5,'amount',10.00,510.00,'2019-01-11 05:45:53','2019-01-11 05:45:53'),(798,33,2021,6,'amount',10.00,0.00,'2019-01-11 05:45:53','2019-01-11 05:45:53'),(799,33,2021,7,'amount',10.00,0.00,'2019-01-11 05:45:53','2019-01-11 05:45:53'),(800,33,2021,8,'amount',10.00,510.00,'2019-01-11 05:45:53','2019-01-11 05:45:53'),(801,33,2021,9,'amount',10.00,0.00,'2019-01-11 05:45:53','2019-01-11 05:45:53'),(802,33,2021,10,'amount',10.00,0.00,'2019-01-11 05:45:53','2019-01-11 05:45:53'),(803,33,2021,11,'amount',10.00,510.00,'2019-01-11 05:45:53','2019-01-11 05:45:53'),(804,33,2021,12,'amount',10.00,0.00,'2019-01-11 05:45:53','2019-01-11 05:45:53'),(805,33,2022,1,'amount',10.00,0.00,'2019-01-11 05:45:53','2019-01-11 05:45:53'),(806,33,2022,2,'amount',10.00,520.00,'2019-01-11 05:45:53','2019-01-11 05:45:53'),(807,33,2022,3,'amount',10.00,0.00,'2019-01-11 05:45:53','2019-01-11 05:45:53'),(808,33,2022,4,'amount',10.00,0.00,'2019-01-11 05:45:54','2019-01-11 05:45:54'),(809,33,2022,5,'amount',10.00,520.00,'2019-01-11 05:45:54','2019-01-11 05:45:54'),(810,33,2022,6,'amount',10.00,0.00,'2019-01-11 05:45:54','2019-01-11 05:45:54'),(811,33,2022,7,'amount',10.00,0.00,'2019-01-11 05:45:54','2019-01-11 05:45:54'),(812,33,2022,8,'amount',10.00,0.00,'2019-01-11 05:45:54','2019-01-11 05:45:54'),(813,33,2022,9,'amount',30.00,550.00,'2019-01-11 05:45:54','2019-01-11 05:45:54'),(814,33,2022,10,'amount',30.00,0.00,'2019-01-11 05:45:54','2019-01-11 05:45:54'),(815,33,2022,11,'amount',30.00,0.00,'2019-01-11 05:45:54','2019-01-11 05:45:54'),(816,33,2022,12,'amount',30.00,0.00,'2019-01-11 05:45:54','2019-01-11 05:45:54');
/*!40000 ALTER TABLE `payment_escalation_dates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_escalation_inconsitent_inputs`
--

DROP TABLE IF EXISTS `payment_escalation_inconsitent_inputs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_escalation_inconsitent_inputs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `payment_id` int(10) unsigned NOT NULL,
  `inconsistent_data` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `incon_` (`payment_id`),
  CONSTRAINT `incon_` FOREIGN KEY (`payment_id`) REFERENCES `lease_assets_payments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_escalation_inconsitent_inputs`
--

LOCK TABLES `payment_escalation_inconsitent_inputs` WRITE;
/*!40000 ALTER TABLE `payment_escalation_inconsitent_inputs` DISABLE KEYS */;
INSERT INTO `payment_escalation_inconsitent_inputs` VALUES (1,33,'a:4:{s:33:\"inconsistent_escalation_frequency\";a:4:{i:2019;a:1:{i:0;s:1:\"1\";}i:2020;a:1:{i:0;N;}i:2021;a:1:{i:0;N;}i:2022;a:1:{i:0;s:1:\"2\";}}s:27:\"inconsistent_effective_date\";a:2:{i:2019;a:1:{i:0;s:10:\"2019-02-20\";}i:2022;a:2:{i:0;s:10:\"2022-02-01\";i:1;s:10:\"2022-09-01\";}}s:34:\"inconsistent_amount_based_currency\";a:2:{i:2019;a:1:{i:0;s:3:\"INR\";}i:2022;a:2:{i:0;s:3:\"INR\";i:1;s:3:\"INR\";}}s:29:\"inconsistent_escalated_amount\";a:2:{i:2019;a:1:{i:0;s:2:\"10\";}i:2022;a:2:{i:0;s:2:\"10\";i:1;s:2:\"30\";}}}','2019-01-11 04:49:27','2019-01-11 05:45:51');
/*!40000 ALTER TABLE `payment_escalation_inconsitent_inputs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permission_role`
--

DROP TABLE IF EXISTS `permission_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permission_role` (
  `permission_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `permission_role_role_id_foreign` (`role_id`),
  CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permission_role`
--

LOCK TABLES `permission_role` WRITE;
/*!40000 ALTER TABLE `permission_role` DISABLE KEYS */;
INSERT INTO `permission_role` VALUES (1,8),(2,8),(1,11);
/*!40000 ALTER TABLE `permission_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'add_lease','Add Lease','The user can create a new lease. If the user is not a parent user than the settings will be used from his parent account.','2018-12-25 22:52:33','2018-12-25 22:52:33'),(2,'settings','Settings','User who has been provided the access will be responsible for managing the settings on behalf of the main business account.','2018-12-25 22:52:33','2018-12-25 22:52:33');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_option`
--

DROP TABLE IF EXISTS `purchase_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase_option` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lease_id` int(10) unsigned NOT NULL,
  `asset_id` int(10) unsigned NOT NULL,
  `purchase_option_clause` enum('yes','no') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purchase_option_exerecisable` enum('yes','no') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expected_purchase_date` date DEFAULT NULL,
  `expected_lease_end_date` date DEFAULT NULL,
  `currency` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purchase_price` decimal(12,2) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_option_lease_id_foreign` (`lease_id`),
  KEY `purchase_option_asset_id_foreign` (`asset_id`),
  CONSTRAINT `purchase_option_asset_id_foreign` FOREIGN KEY (`asset_id`) REFERENCES `lease_assets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `purchase_option_lease_id_foreign` FOREIGN KEY (`lease_id`) REFERENCES `lease` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_option`
--

LOCK TABLES `purchase_option` WRITE;
/*!40000 ALTER TABLE `purchase_option` DISABLE KEYS */;
/*!40000 ALTER TABLE `purchase_option` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rate_types`
--

DROP TABLE IF EXISTS `rate_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rate_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rate_types`
--

LOCK TABLES `rate_types` WRITE;
/*!40000 ALTER TABLE `rate_types` DISABLE KEYS */;
INSERT INTO `rate_types` VALUES (1,'Fixed Rate','1','2018-12-14 06:30:13','2018-12-14 06:30:13'),(2,'Variable Rate','1','2018-12-14 06:30:13','2018-12-14 06:30:13'),(3,'Fixed & Variable Rate','1','2018-12-14 06:30:13','2018-12-14 06:30:13');
/*!40000 ALTER TABLE `rate_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reporting_currency_settings`
--

DROP TABLE IF EXISTS `reporting_currency_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reporting_currency_settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `business_account_id` int(10) unsigned NOT NULL,
  `statutory_financial_reporting_currency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `internal_company_financial_reporting_currency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_for_lease_reports` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lease_report_same_as_statutory_reporting` enum('1','2','3') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `internal_same_as_statutory_reporting` enum('yes','no') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_foreign_transaction_involved` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'no' COMMENT 'Whether the foreign currency transaction involved',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reporting_currency_bu_ac_id` (`business_account_id`),
  CONSTRAINT `reporting_currency_bu_ac_id` FOREIGN KEY (`business_account_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reporting_currency_settings`
--

LOCK TABLES `reporting_currency_settings` WRITE;
/*!40000 ALTER TABLE `reporting_currency_settings` DISABLE KEYS */;
INSERT INTO `reporting_currency_settings` VALUES (26,30,'INR','INR','INR','1','yes','no','2018-12-26 00:25:07','2018-12-28 07:02:38');
/*!40000 ALTER TABLE `reporting_currency_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_user`
--

DROP TABLE IF EXISTS `role_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_user` (
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `role_user_role_id_foreign` (`role_id`),
  CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_user`
--

LOCK TABLES `role_user` WRITE;
/*!40000 ALTER TABLE `role_user` DISABLE KEYS */;
INSERT INTO `role_user` VALUES (30,8),(31,11);
/*!40000 ALTER TABLE `role_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `business_account_id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`),
  KEY `roles_business_account_id_foreign` (`business_account_id`),
  CONSTRAINT `roles_business_account_id_foreign` FOREIGN KEY (`business_account_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (8,30,'super_admin','Super Admin','Super Admin is the main business account and the super admin will be assigned with all the permissions.','2018-12-25 23:22:58','2018-12-25 23:22:58'),(11,30,'Settings Manager','Settings Manager','Can manage the settings for the users','2018-12-31 22:31:20','2018-12-31 22:31:20');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `supplier_details`
--

DROP TABLE IF EXISTS `supplier_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `supplier_details` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `initial_direct_cost_id` int(10) unsigned NOT NULL,
  `supplier_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direct_cost_description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `expense_date` date NOT NULL,
  `currency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `rate` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `initial_fo_id` (`initial_direct_cost_id`),
  CONSTRAINT `initial_fo_id` FOREIGN KEY (`initial_direct_cost_id`) REFERENCES `initial_direct_cost` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `supplier_details`
--

LOCK TABLES `supplier_details` WRITE;
/*!40000 ALTER TABLE `supplier_details` DISABLE KEYS */;
/*!40000 ALTER TABLE `supplier_details` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `supplier_details_AFTER_INSERT` AFTER INSERT ON `supplier_details` FOR EACH ROW BEGIN
                UPDATE initial_direct_cost s
        SET s.total_initial_direct_cost = (select sum(`amount`) from `supplier_details` where `initial_direct_cost_id` = new.`initial_direct_cost_id`)
    WHERE s.id = new.initial_direct_cost_id;      
            END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `supplier_details_AFTER_DELETE` AFTER DELETE ON `supplier_details`
             FOR EACH ROW BEGIN
                UPDATE initial_direct_cost s
                    SET s.total_initial_direct_cost = (select sum(`amount`) from `supplier_details` where `initial_direct_cost_id` = old.`initial_direct_cost_id`)
                WHERE s.id = old.initial_direct_cost_id;   
            END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `un_lease_assets_numbers_settings`
--

DROP TABLE IF EXISTS `un_lease_assets_numbers_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `un_lease_assets_numbers_settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `business_account_id` int(10) unsigned NOT NULL,
  `number` int(10) unsigned NOT NULL,
  `status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `un_la_business_account_id` (`business_account_id`),
  CONSTRAINT `un_la_business_account_id` FOREIGN KEY (`business_account_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `un_lease_assets_numbers_settings`
--

LOCK TABLES `un_lease_assets_numbers_settings` WRITE;
/*!40000 ALTER TABLE `un_lease_assets_numbers_settings` DISABLE KEYS */;
INSERT INTO `un_lease_assets_numbers_settings` VALUES (1,30,1,'1','2018-12-25 23:22:58','2018-12-25 23:22:58'),(2,30,2,'1','2018-12-26 02:32:08','2018-12-26 02:32:08'),(3,30,5,'1','2018-12-26 05:40:48','2018-12-26 05:40:48');
/*!40000 ALTER TABLE `un_lease_assets_numbers_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned NOT NULL,
  `type` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '1 means that the user is a admin user, 0 means that the user is a normal user',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_verified` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `email_verification_code` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `authorised_person_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `authorised_person_designation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `authorised_person_dob` date DEFAULT NULL,
  `legal_entity_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `industry_type` int(11) DEFAULT NULL,
  `applicable_gaap` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `legal_status` int(11) DEFAULT NULL,
  `country` int(11) DEFAULT NULL,
  `gender` enum('1','2') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '1 => Male, 2 => Female',
  `annual_reporting_period` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,0,'1','adminflexsin@yopmail.com',NULL,NULL,'$2y$10$8NfMdgiEGYbuuG7TSwEZ3O8bHzyEIkIbzbEHc9yqM4iwYZcQmOtGO','u2mEu6tETpJ1N0EY6TAaJRIpSrpIMaNtFrWj20ijx0BLGmS2Zi24bxzH0QCZ','0',NULL,'2018-12-09 23:41:14','2018-12-09 23:41:14',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1',NULL),(30,30,'0','himanshu_rajput@yopmail.com','7053314271','himanshu_rajput','$2y$10$foLfCNChdLe/NMyv1Blz1ulL4Sb75F68mVzgzKNXuAp5jZ6YvzWYC','3akljy2lUVKBQGI4DT8a8CykLCdgnSLZOhmQYkN17isRBsl00rDbrQCuUMhy','1','07ce9c04de6753d75c7a4cb4fc974001','2018-12-25 23:22:57','2019-01-06 22:26:28','Himanshu Rajput','Senior Software Engineer','1994-12-12','Flexsin Technologies PVT LTD',65,'Ministry Of Corporate Affairs (MCA)',1,97,'1','2012-2020'),(31,30,'0','rohit_shukla@yopmail.com','7053314271','rohit_shukla','$2y$10$tgd3s3UVhMyXt328Loqp5.QrqB5ZvTBISq0LUMgJpr2rDxKq8LECW','jKszisuBGekZgjaXGxk0mECaVqQ8aSYw3hFYYdnniLNOCrPsoaOlt7E4v4Ih','1','421a28d3b4ccd23a2aee5d5735d2665e','2018-12-31 22:35:21','2018-12-31 22:38:00','Rohit Shukla','Settings Manager','2019-01-09',NULL,NULL,NULL,NULL,NULL,'1',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-01-11 18:33:59
