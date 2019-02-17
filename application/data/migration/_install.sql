
DROP TABLE IF EXISTS `acl_role`;
CREATE TABLE IF NOT EXISTS `acl_role` (
  `id_acl_role` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identyfikator',
  `object` varchar(255) DEFAULT NULL COMMENT 'Opis',
  `order` tinyint(3) UNSIGNED NOT NULL COMMENT 'Kolejność wyświetlania',
  `is_visible` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Czy rola jest widoczna ',
  `name` varchar(255) NOT NULL COMMENT 'Nazwa roli',
  `name_role` varchar(255) NOT NULL COMMENT 'Nazwa systemowa roli',
  `id_service` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id_acl_role`),
  KEY `name` (`name`),
  KEY `name_role` (`name_role`),
  KEY `channel` (`object`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='Role aplikacji, np.: tester, developer itp';
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `label`
--

DROP TABLE IF EXISTS `label`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `label` (
  `id_label` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Identyfikator',
  `label` varchar(255) NOT NULL COMMENT 'Klucz, nazwa',
  `type` varchar(32) NOT NULL COMMENT 'Typ; label – opis; route – definicje routingu',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data dodania',
  `modified_at` timestamp NULL DEFAULT NULL COMMENT 'Data modyfikacji',
  `module` varchar(255) NOT NULL COMMENT 'Moduł',
  `from_import` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Czy dodany z importu',
  PRIMARY KEY (`id_label`)
) ENGINE=InnoDB AUTO_INCREMENT=8261 DEFAULT CHARSET=utf8 COMMENT='Lista etykiet aplikacji';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `label_translation`
--

DROP TABLE IF EXISTS `label_translation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `label_translation` (
  `id_label` int(10) unsigned NOT NULL COMMENT 'Identyfikator',
  `id_language` tinyint(3) unsigned NOT NULL COMMENT 'Identyfikator wersji językowej',
  `value` text NOT NULL COMMENT 'Tłumaczenie',
  PRIMARY KEY (`id_label`,`id_language`),
  KEY `id_language` (`id_language`),
  CONSTRAINT `label_translation_ibfk_1` FOREIGN KEY (`id_label`) REFERENCES `label` (`id_label`) ON DELETE CASCADE,
  CONSTRAINT `label_translation_ibfk_2` FOREIGN KEY (`id_language`) REFERENCES `language` (`id_language`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Lista tłumaczeń dla etykiet';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `language`
--

DROP TABLE IF EXISTS `language`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `language` (
  `id_language` tinyint(3) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Identyfikator',
  `is_active` tinyint(1) NOT NULL COMMENT 'Czy aktywny',
  `is_main` tinyint(1) NOT NULL COMMENT 'Czy główna wersja językowa',
  `code` varchar(8) NOT NULL COMMENT 'Kod języka',
  `name` varchar(255) NOT NULL COMMENT 'Nazwa',
  `domain` varchar(255) NOT NULL COMMENT 'Domena',
  `locale` varchar(16) NOT NULL COMMENT 'Ustawienia lokalizacji',
  PRIMARY KEY (`id_language`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='Lista wersji językowych aplikacji';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `query`
--

DROP TABLE IF EXISTS `query`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `query` (
  `id_query` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Identyfikator',
  `id_service` int(10) unsigned NOT NULL COMMENT 'Identyfikator serwisu',
  `id_user` int(10) unsigned NOT NULL COMMENT 'Identyfikator użytkownika do którego jest przypisana kwerenda',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Data dodania',
  `is_public` tinyint(1) NOT NULL COMMENT 'Czy kwerenda jest pubiczna/dostępna dla wszystkich',
  `is_default` tinyint(1) NOT NULL COMMENT 'Czy jest to domyślna',
  `order` smallint(6) NOT NULL COMMENT 'Kolejność wyświetlania',
  `title` varchar(255) NOT NULL COMMENT 'Nazwa kwerendy',
  `form` varchar(255) NOT NULL COMMENT 'Formularz kwerendy',
  `data` text NOT NULL COMMENT 'Dane kwerendy dla formularza',
  PRIMARY KEY (`id_query`),
  KEY `id_user` (`id_user`),
  KEY `id_service` (`id_service`),
  CONSTRAINT `query_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE,
  CONSTRAINT `query_ibfk_2` FOREIGN KEY (`id_service`) REFERENCES `service` (`id_service`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Lista kwerend użytkowników';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `session`
--

DROP TABLE IF EXISTS `session`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `session` (
  `id` char(32) NOT NULL COMMENT 'Identyfikator',
  `modified` int(11) NOT NULL COMMENT 'Data modyfikacji',
  `lifetime` int(11) NOT NULL COMMENT 'Czas życia sesji',
  `data` text NOT NULL COMMENT 'Dane sesji',
  `id_user` int(10) unsigned DEFAULT NULL COMMENT 'Identyfikator zalogowanego użytkownika',
  `ip` char(16) NOT NULL COMMENT 'Numer IP',
  `agent` text NOT NULL COMMENT 'Dane przeglądarki',
  `address` text NOT NULL COMMENT 'Obecnie przeglądany adres',
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `session_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Sesje użytkowników';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `setting`
--

DROP TABLE IF EXISTS `setting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `setting` (
  `id_setting` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identyfikator',
  `id_service` int(10) unsigned DEFAULT NULL COMMENT 'Identyfikator serwisu',
  `key` varchar(255) NOT NULL COMMENT 'Klucz',
  `value` text NOT NULL COMMENT 'Wartość',
  PRIMARY KEY (`id_setting`),
  KEY `id_service` (`id_service`),
  CONSTRAINT `setting_ibfk_1` FOREIGN KEY (`id_service`) REFERENCES `service` (`id_service`)
) ENGINE=InnoDB AUTO_INCREMENT=164 DEFAULT CHARSET=utf8 COMMENT='Ustawienia aplikacji';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-12-22 12:44:44
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
-- Dumping data for table `acl_role`
--

LOCK TABLES `acl_role` WRITE;
/*!40000 ALTER TABLE `acl_role` DISABLE KEYS */;
INSERT INTO `acl_role` (`id_acl_role`, `object`, `order`, `is_visible`, `name`, `name_role`, `id_service`) VALUES
(1, NULL, 1, 1, 'Tester', 'tester', 0),
(2, NULL, 2, 1, 'Developer', 'developer', 0),
(3, NULL, 3, 1, 'Project manager', 'project-manager', 0);

/*!40000 ALTER TABLE `acl_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `language`
--

LOCK TABLES `language` WRITE;
/*!40000 ALTER TABLE `language` DISABLE KEYS */;
INSERT INTO `language` (`id_language`, `is_active`, `is_main`, `code`, `name`, `domain`, `locale`) VALUES (2, 1, 1, 'pl', 'Polski', '', 'pl_PL');
/*!40000 ALTER TABLE `language` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

INSERT INTO `layout` (`id_layout`, `id_service`, `type`, `id_layout_template`, `id_user`, `name`, `data_map`, `is_default`, `is_public`) VALUES
(3, NULL, 'default', 1, NULL, 'layout', '[]', 1, 1),
(4, NULL, 'empty', 4, NULL, 'layout_empty', '[]', 1, 1),
(5, NULL, 'auth', 3, NULL, 'layout', '[]', 1, 1);


INSERT INTO `layout_template` (`id_layout_template`, `id_service`, `is_default`, `is_system`, `name`, `filename`, `data_map`) VALUES
(1, NULL, 0, 1, '', 'layout', '{\"main\":[]}'),
(3, NULL, 0, 1, '', 'layout_auth', '{\"main\":[]}');
