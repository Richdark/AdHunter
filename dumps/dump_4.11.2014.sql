-- MySQL dump 10.13  Distrib 5.5.40, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: adhunter
-- ------------------------------------------------------
-- Server version	5.5.40-0ubuntu0.14.04.1

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
-- Table structure for table `nosice`
--

DROP TABLE IF EXISTS `nosice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `nosice` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `vlastnik_id` smallint(5) unsigned NOT NULL,
  `poloha_id` smallint(5) unsigned NOT NULL,
  `cislo_nosica` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vlastnik_id` (`vlastnik_id`),
  KEY `poloha_id` (`poloha_id`),
  CONSTRAINT `nosice_ibfk_1` FOREIGN KEY (`vlastnik_id`) REFERENCES `vlastnici` (`id`),
  CONSTRAINT `nosice_ibfk_2` FOREIGN KEY (`poloha_id`) REFERENCES `polohy_bodov` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nosice`
--

LOCK TABLES `nosice` WRITE;
/*!40000 ALTER TABLE `nosice` DISABLE KEYS */;
/*!40000 ALTER TABLE `nosice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `parcely`
--

DROP TABLE IF EXISTS `parcely`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `parcely` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `poloha_bodu_id` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `poloha_bodu_id` (`poloha_bodu_id`),
  CONSTRAINT `parcely_ibfk_1` FOREIGN KEY (`poloha_bodu_id`) REFERENCES `polohy_bodov` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `parcely`
--

LOCK TABLES `parcely` WRITE;
/*!40000 ALTER TABLE `parcely` DISABLE KEYS */;
/*!40000 ALTER TABLE `parcely` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `polohy_bodov`
--

DROP TABLE IF EXISTS `polohy_bodov`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `polohy_bodov` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `predpokladane_suradnice` point DEFAULT NULL,
  `mesto` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ulica` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `polohy_bodov`
--

LOCK TABLES `polohy_bodov` WRITE;
/*!40000 ALTER TABLE `polohy_bodov` DISABLE KEYS */;
/*!40000 ALTER TABLE `polohy_bodov` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pouzivatelia`
--

DROP TABLE IF EXISTS `pouzivatelia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pouzivatelia` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `meno` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pouzivatelia`
--

LOCK TABLES `pouzivatelia` WRITE;
/*!40000 ALTER TABLE `pouzivatelia` DISABLE KEYS */;
INSERT INTO `pouzivatelia` VALUES (1,'default');
/*!40000 ALTER TABLE `pouzivatelia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reklamne_kampane`
--

DROP TABLE IF EXISTS `reklamne_kampane`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reklamne_kampane` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `nosic_id` smallint(5) unsigned NOT NULL,
  `zadavatel_id` mediumint(8) unsigned NOT NULL,
  `poznamky` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `nosic_id` (`nosic_id`),
  KEY `zadavatel_id` (`zadavatel_id`),
  CONSTRAINT `reklamne_kampane_ibfk_1` FOREIGN KEY (`nosic_id`) REFERENCES `nosice` (`id`),
  CONSTRAINT `reklamne_kampane_ibfk_2` FOREIGN KEY (`zadavatel_id`) REFERENCES `zadavatelia` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reklamne_kampane`
--

LOCK TABLES `reklamne_kampane` WRITE;
/*!40000 ALTER TABLE `reklamne_kampane` DISABLE KEYS */;
/*!40000 ALTER TABLE `reklamne_kampane` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reklamy`
--

DROP TABLE IF EXISTS `reklamy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reklamy` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `od` datetime NOT NULL,
  `do` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reklamy`
--

LOCK TABLES `reklamy` WRITE;
/*!40000 ALTER TABLE `reklamy` DISABLE KEYS */;
INSERT INTO `reklamy` VALUES (1,'2014-11-04 22:18:18','2015-02-12 22:18:18');
/*!40000 ALTER TABLE `reklamy` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ulovky`
--

DROP TABLE IF EXISTS `ulovky`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ulovky` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pouzivatel_id` mediumint(8) unsigned NOT NULL,
  `reklama_id` mediumint(8) unsigned NOT NULL,
  `suradnice` point NOT NULL,
  `nazov_suboru` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `vytvoreny` datetime NOT NULL,
  `typ` char(1) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pouzivatel_id` (`pouzivatel_id`),
  KEY `reklama_id` (`reklama_id`),
  CONSTRAINT `ulovky_ibfk_1` FOREIGN KEY (`pouzivatel_id`) REFERENCES `pouzivatelia` (`id`),
  CONSTRAINT `ulovky_ibfk_2` FOREIGN KEY (`reklama_id`) REFERENCES `reklamy` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ulovky`
--

LOCK TABLES `ulovky` WRITE;
/*!40000 ALTER TABLE `ulovky` DISABLE KEYS */;
/*!40000 ALTER TABLE `ulovky` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vlastnici`
--

DROP TABLE IF EXISTS `vlastnici`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vlastnici` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `nazov` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vlastnici`
--

LOCK TABLES `vlastnici` WRITE;
/*!40000 ALTER TABLE `vlastnici` DISABLE KEYS */;
/*!40000 ALTER TABLE `vlastnici` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zadavatelia`
--

DROP TABLE IF EXISTS `zadavatelia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zadavatelia` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `meno` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zadavatelia`
--

LOCK TABLES `zadavatelia` WRITE;
/*!40000 ALTER TABLE `zadavatelia` DISABLE KEYS */;
/*!40000 ALTER TABLE `zadavatelia` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-11-04 22:56:30
