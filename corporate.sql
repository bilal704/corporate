/*
SQLyog Community v13.1.7 (64 bit)
MySQL - 10.4.17-MariaDB : Database - corporate
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`corporate` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `corporate`;

/*Table structure for table `department` */

DROP TABLE IF EXISTS `department`;

CREATE TABLE `department` (
  `department_id` int(11) NOT NULL AUTO_INCREMENT,
  `department_name` tinytext NOT NULL,
  `created_date_time` datetime NOT NULL,
  `updated_date_time` datetime NOT NULL,
  PRIMARY KEY (`department_id`) USING BTREE,
  UNIQUE KEY `UNIQUE` (`department_name`) USING HASH
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `department` */

insert  into `department`(`department_id`,`department_name`,`created_date_time`,`updated_date_time`) values 
(1,'Accounts','2021-08-18 12:04:50','0000-00-00 00:00:00'),
(2,'IT','2021-08-18 12:07:14','0000-00-00 00:00:00'),
(3,'HR','2021-08-18 12:11:30','0000-00-00 00:00:00');

/*Table structure for table `employee_contact_details` */

DROP TABLE IF EXISTS `employee_contact_details`;

CREATE TABLE `employee_contact_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `mobile_no` blob DEFAULT NULL,
  `address` longblob DEFAULT NULL,
  `created_date_time` datetime NOT NULL,
  `updated_date_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_id_foreign_key` (`employee_id`),
  CONSTRAINT `employee_id_foreign_key` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `employee_contact_details` */

insert  into `employee_contact_details`(`id`,`employee_id`,`mobile_no`,`address`,`created_date_time`,`updated_date_time`) values 
(2,2,'[\"8888888888\"]','[\"abc, pqr, kurla, santacurz(E), mumbai -70\"]','2021-08-19 02:27:30','2021-08-19 05:20:06');

/*Table structure for table `employees` */

DROP TABLE IF EXISTS `employees`;

CREATE TABLE `employees` (
  `employee_id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_name` text NOT NULL,
  `department_id` int(11) NOT NULL,
  `created_date_time` datetime NOT NULL,
  `updated_date_time` datetime NOT NULL,
  PRIMARY KEY (`employee_id`),
  KEY `department_id_foreign_key` (`department_id`),
  CONSTRAINT `department_id_foreign_key` FOREIGN KEY (`department_id`) REFERENCES `department` (`department_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `employees` */

insert  into `employees`(`employee_id`,`employee_name`,`department_id`,`created_date_time`,`updated_date_time`) values 
(2,'Ahmed Bilal',3,'2021-08-19 02:27:30','2021-08-19 05:20:06');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
