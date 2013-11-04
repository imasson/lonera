/*
SQLyog Ultimate v11.11 (64 bit)
MySQL - 5.5.16 : Database - tkttool
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`tkttool` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `tkttool`;

/*Table structure for table `helptopic` */

DROP TABLE IF EXISTS `helptopic`;

CREATE TABLE `helptopic` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `helptopic` */

insert  into `helptopic`(`id`,`title`) values (1,'Hill Valley - Ad-Hoc Report'),(2,'Hill Valley - Alert Report'),(3,'Hill Valley - Esintein Report'),(4,'Colectron - Facebook'),(5,'Colectron - Twitter'),(6,'Colectron - Youtube'),(7,'Colectron - News - Forums - Blog - Sites');

/*Table structure for table `ticket` */

DROP TABLE IF EXISTS `ticket`;

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `ticket` */

insert  into `ticket`(`id`,`title`,`description`,`id_helptopic`,`priority`,`status`,`created_date`,`updated_date`,`created_user`,`support_user`) values (1,'Prueba de tkt','Datos para un nuevo tkt!\r\n\r\nSalto de lÃ­nea doble!!! \r\n\r\n!\"#$%&/()Ã¡sdÃ¡sdÃ¡sdÂ´Â´fdgÂ´we,,,, \"\"\" \"\" \" \'\'\' \'\' \' \'Ã±Ã±Ã±Ã±Ã±Ã± Ã³ ~e',1,3,1,'2013-11-01 02:01:53','2013-11-01 02:01:53','sarrubia@gmail.com',NULL),(2,'Prueba de tkt 2','dedee',0,2,4,'2013-11-01 10:05:15','2013-11-01 10:05:15','sarrubia@gmail.com',NULL),(3,'DEMO DE NUEO TKT','DEMO DE NUEO TKT DEMO DE NUEO TKT DEMO DE NUEO TKT DEMO DE NUEO TKT DEMO DE NUEO TKT DEMO DE NUEO TKT DEMO DE NUEO TKT DEMO DE NUEO TKT DEMO DE NUEO TKT DEMO DE NUEO TKT DEMO DE NUEO TKT DEMO DE NUEO TKT DEMO DE NUEO TKT DEMO DE NUEO TKT DEMO DE NUEO TKT DEMO DE NUEO TKT DEMO DE NUEO TKT ',1,4,1,'2013-11-01 10:35:39','2013-11-01 10:35:39','sarrubia@gmail.com',NULL),(4,'Para Manu','DEMOO DEMOD EMOED *\r\nASDAS\r\nDASDA\r\ndsads',4,4,3,'2013-11-01 12:55:55','2013-11-01 12:55:55','sarrubia@gmail.com',NULL),(5,'DEMO DE EMAIL','DEMO DE DEMO DEMODEMO\r\nMas Demo',2,3,1,'2013-11-01 01:49:10','2013-11-01 01:49:10','sarrubia@gmail.com',NULL),(6,'DEMO DE EMAIL 2','asdasdasd\r\nasdasd\r\n\"#$%&/()\r\nÃ±oÃ±o Ãº Ã¡ Ã½',1,3,1,'2013-11-01 01:51:35','2013-11-01 01:51:35','sarrubia@gmail.com',NULL),(7,'Nuevo TKT prueba email','Nuevo TKT prueba email Nuevo TKT prueba emailNuevo TKT prueba emailNuevo TKT prueba emailNuevo TKT prueba emailNuevo TKT prueba emailNuevo TKT prueba emailNuevo TKT prueba emailNuevo TKT prueba emailNuevo TKT prueba emailNuevo TKT prueba emailNuevo TKT prueba emailNuevo TKT prueba emailNuevo TKT prueba emailNuevo TKT prueba emailNuevo TKT prueba emailNuevo TKT prueba email',4,4,1,'2013-11-01 04:21:30','2013-11-01 04:21:30','sarrubia@gmail.com',NULL);

/*Table structure for table `ticket_comment` */

DROP TABLE IF EXISTS `ticket_comment`;

CREATE TABLE `ticket_comment` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_ticket` bigint(20) DEFAULT NULL,
  `comment` text,
  `created_date` datetime DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `ticket_comment` */

insert  into `ticket_comment`(`id`,`id_ticket`,`comment`,`created_date`,`author`) values (1,3,'un comentario','2013-11-01 03:51:26','sarrubia@gmail.com'),(2,3,'Otro comentario mas!!! wiiiii','2013-11-01 04:13:54','sarrubia@gmail.com'),(3,3,'uno mas con un\r\nSALTOOO!','2013-11-01 04:14:04','sarrubia@gmail.com'),(4,2,'Un comentario che!','2013-11-01 04:16:54','sarrubia@gmail.com'),(5,5,'Nuevo comentario!\r\nUn salto de lÃ­nea!!! \" \' rompÃ© Ã±Ã±oÃ±o','2013-11-01 04:27:45','sarrubia@gmail.com'),(6,3,'DEMO DEMO DEMO','2013-11-01 04:41:35','sarrubia@gmail.com');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
