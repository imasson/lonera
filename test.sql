-- MySQL dump 10.13  Distrib 5.7.28, for osx10.15 (x86_64)
--
-- Host: localhost    Database: tkttool
-- ------------------------------------------------------
-- Server version	5.7.28

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
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clients` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `cuit` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clients`
--

LOCK TABLES `clients` WRITE;
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;
INSERT INTO `clients` VALUES (1,'first','12345678','1234566','sarmiento 2235',NULL),(2,'second','87654321','654321','rivadavia 123',NULL),(3,'third','123123123','123123','avellaneda 123',NULL),(4,'ivan masson','20327238621',NULL,'saavedra 751 dto 8','ivan.masson@gmail.com');
/*!40000 ALTER TABLE `clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `helptopic`
--

DROP TABLE IF EXISTS `helptopic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `helptopic` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `helptopic`
--

LOCK TABLES `helptopic` WRITE;
/*!40000 ALTER TABLE `helptopic` DISABLE KEYS */;
INSERT INTO `helptopic` VALUES (1,'Hill Valley - Ad-Hoc Report'),(2,'Hill Valley - Alert Report'),(3,'Hill Valley - Esintein Report'),(4,'Colectron - Facebook'),(5,'Colectron - Twitter'),(6,'Colectron - Youtube'),(7,'Colectron - News - Forums - Blog - Sites');
/*!40000 ALTER TABLE `helptopic` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_type`
--

DROP TABLE IF EXISTS `order_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_type` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `color` varchar(255) DEFAULT '',
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_type`
--

LOCK TABLES `order_type` WRITE;
/*!40000 ALTER TABLE `order_type` DISABLE KEYS */;
INSERT INTO `order_type` VALUES (1,'azul','25x30',NULL),(2,'verde','30x50',NULL),(3,'negro','10x10',NULL),(4,'blanco','100x20',NULL),(5,'Azul','1x1',NULL);
/*!40000 ALTER TABLE `order_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `total` float DEFAULT NULL,
  `paid_total` float DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `paid_at` date DEFAULT NULL,
  `type_id` int(11) unsigned DEFAULT NULL,
  `client_id` int(11) unsigned DEFAULT NULL,
  `payment_method` varchar(255) DEFAULT '',
  `check_date` date DEFAULT NULL,
  `meters` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`),
  KEY `type_id` (`type_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `order_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,100,0,'2008-07-04','2008-07-04',1,1,'cheque','2018-07-04',NULL,NULL),(3,1000,0,NULL,NULL,1,2,'Efectivo',NULL,'1000','xxxx'),(4,500,0,NULL,NULL,2,1,'Efectivo',NULL,'20','xxxx'),(5,200,0,'2019-06-02','2019-06-02',3,4,'Cheque','2019-02-06','200','sssdas'),(6,1500,10,'2019-02-04',NULL,3,4,'Cheque','2019-02-06','1000','');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ticket`
--

DROP TABLE IF EXISTS `ticket`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ticket` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `id_helptopic` bigint(20) DEFAULT NULL,
  `priority` int(11) DEFAULT '2' COMMENT '1 low | 2 normal | 3 high | 4 urgent',
  `status` int(11) DEFAULT '1' COMMENT '1 waiting | 2 in progress | 3 resolved | 4 closed | 5 rejected',
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `created_user` varchar(255) DEFAULT NULL,
  `support_user` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ticket`
--

LOCK TABLES `ticket` WRITE;
/*!40000 ALTER TABLE `ticket` DISABLE KEYS */;
INSERT INTO `ticket` VALUES (2,'Prueba de tkt 2','dedee',0,2,4,'2013-11-01 10:05:15','2013-11-01 10:05:15','sarrubia@gmail.com',NULL),(8,'test','asdadsad',1,1,1,'2018-12-03 07:53:39','2018-12-03 07:53:39','sarrubia@gmail.com',NULL),(9,'test','asdadsad',1,1,1,'2018-12-03 07:55:40','2018-12-03 07:55:40',NULL,NULL),(10,'test','asdadsad',1,1,1,'2018-12-03 07:58:14','2018-12-03 07:58:14',NULL,NULL),(11,'test','asdadsad',1,1,1,'2018-12-03 08:00:19','2018-12-03 08:00:19','sarrubia@gmail.com',NULL),(12,'test','asdadsad',1,1,1,'2018-12-03 08:00:26','2018-12-03 08:00:26','sarrubia@gmail.com',NULL),(13,'test','ascada',1,1,1,'2018-12-03 08:04:44','2018-12-03 08:04:44','sarrubia@gmail.com',NULL);
/*!40000 ALTER TABLE `ticket` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ticket_comment`
--

DROP TABLE IF EXISTS `ticket_comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ticket_comment` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_ticket` bigint(20) DEFAULT NULL,
  `comment` text,
  `created_date` datetime DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ticket_comment`
--

LOCK TABLES `ticket_comment` WRITE;
/*!40000 ALTER TABLE `ticket_comment` DISABLE KEYS */;
INSERT INTO `ticket_comment` VALUES (1,3,'un comentario','2013-11-01 03:51:26','sarrubia@gmail.com'),(2,3,'Otro comentario mas!!! wiiiii','2013-11-01 04:13:54','sarrubia@gmail.com'),(4,2,'Un comentario che!','2013-11-01 04:16:54','sarrubia@gmail.com'),(6,3,'DEMO DEMO DEMO','2013-11-01 04:41:35','sarrubia@gmail.com');
/*!40000 ALTER TABLE `ticket_comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
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

-- Dump completed on 2020-01-22 18:32:52
