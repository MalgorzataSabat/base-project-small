-- MySQL dump 10.13  Distrib 5.6.17, for Win64 (x86_64)
--
-- Host: localhost    Database: base-crm.local_20171221
-- ------------------------------------------------------
-- Server version	5.6.17-log

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
-- Table structure for table `layout`
--

DROP TABLE IF EXISTS `layout`;
CREATE TABLE IF NOT EXISTS `layout` (
  `id_layout` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identyfikator',
  `id_service` int(10) UNSIGNED DEFAULT NULL COMMENT 'Identyfikator serwisu',
  `type` varchar(255) NOT NULL COMMENT 'Typ layoutu, np dashboard',
  `id_layout_template` smallint(5) UNSIGNED NOT NULL COMMENT 'Identyfikator szablonu',
  `id_user` int(10) UNSIGNED DEFAULT NULL COMMENT 'Identyfikator użytkowika',
  `name` varchar(255) NOT NULL COMMENT 'Nazwa layoutu',
  `data_map` text NOT NULL COMMENT 'Domyślna definicja/ustawienia',
  `is_default` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Czy domyślny',
  `is_public` tinyint(1) NOT NULL COMMENT 'Czy jest to układ publiczny',
  PRIMARY KEY (`id_layout`),
  KEY `id_user` (`id_user`),
  KEY `id_layout_template` (`id_layout_template`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8 COMMENT='Lista layoutów aplikacji. Wykorzystywana np.: na stronie głównej systemu';


--
-- Table structure for table `layout_template`
--
DROP TABLE IF EXISTS `layout_template`;
CREATE TABLE IF NOT EXISTS `layout_template` (
  `id_layout_template` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identyfikator szablonu',
  `id_service` int(10) UNSIGNED DEFAULT NULL COMMENT 'Identyfikator serwisu',
  `is_default` tinyint(1) NOT NULL COMMENT 'Czy jest to domyślny szablon',
  `is_system` tinyint(1) NOT NULL COMMENT 'Czy jest to szablon systemowy',
  `name` varchar(255) NOT NULL COMMENT 'Nazwa sablonu',
  `filename` varchar(255) NOT NULL COMMENT 'Nazwa pliku HTML',
  `data_map` text NOT NULL COMMENT 'Domyślna definicja ustawień szablonu',
  PRIMARY KEY (`id_layout_template`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-12-22 12:44:47
-- MySQL dump 10.13  Distrib 5.6.17, for Win64 (x86_64)
--
-- Host: localhost    Database: base-crm.local_20171221
-- ------------------------------------------------------
-- Server version	5.6.17-log

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
-- Dumping data for table `layout`
--

LOCK TABLES `layout` WRITE;
/*!40000 ALTER TABLE `layout` DISABLE KEYS */;

INSERT INTO `layout` (`id_layout`, `id_service`, `type`, `id_layout_template`, `id_user`, `name`, `data_map`, `is_default`, `is_public`) VALUES
(3, NULL, 'default', 1, NULL, 'layout', '[]', 1, 1),
(4, NULL, 'empty', 4, NULL, 'layout_empty', '[]', 1, 1),
(5, NULL, 'auth', 3, NULL, 'layout', '[]', 1, 1);
/*!40000 ALTER TABLE `layout` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `layout_template`
--

LOCK TABLES `layout_template` WRITE;
/*!40000 ALTER TABLE `layout_template` DISABLE KEYS */;

INSERT INTO `layout_template` (`id_layout_template`, `id_service`, `is_default`, `is_system`, `name`, `filename`, `data_map`) VALUES
(1, NULL, 0, 1, '', 'layout', '{\"main\":[]}'),
(3, NULL, 0, 1, '', 'layout_auth', '{\"main\":[]}');
/*!40000 ALTER TABLE `layout_template` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

