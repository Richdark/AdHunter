-- --------------------------------------------------------
-- Hostitel:                     127.0.0.1
-- Verze serveru:                5.6.17 - MySQL Community Server (GPL)
-- OS serveru:                   Win32
-- HeidiSQL Verze:               8.3.0.4694
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Exportování struktury databáze pro
CREATE DATABASE IF NOT EXISTS `adhunter` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `adhunter`;


-- Exportování struktury pro tabulka adhunter.nosice
CREATE TABLE IF NOT EXISTS `nosice` (
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

-- Export dat nebyl vybrán.


-- Exportování struktury pro tabulka adhunter.parcely
CREATE TABLE IF NOT EXISTS `parcely` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `poloha_bodu_id` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `poloha_bodu_id` (`poloha_bodu_id`),
  CONSTRAINT `parcely_ibfk_1` FOREIGN KEY (`poloha_bodu_id`) REFERENCES `polohy_bodov` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Export dat nebyl vybrán.


-- Exportování struktury pro tabulka adhunter.polohy_bodov
CREATE TABLE IF NOT EXISTS `polohy_bodov` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `predpokladane_suradnice` point DEFAULT NULL,
  `mesto` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ulica` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Export dat nebyl vybrán.


-- Exportování struktury pro tabulka adhunter.pouzivatelia
CREATE TABLE IF NOT EXISTS `pouzivatelia` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `meno` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `priezvisko` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `heslo` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `salt` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Export dat nebyl vybrán.


-- Exportování struktury pro tabulka adhunter.reklamne_kampane
CREATE TABLE IF NOT EXISTS `reklamne_kampane` (
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

-- Export dat nebyl vybrán.


-- Exportování struktury pro tabulka adhunter.reklamy
CREATE TABLE IF NOT EXISTS `reklamy` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `od` datetime NOT NULL,
  `do` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Export dat nebyl vybrán.


-- Exportování struktury pro tabulka adhunter.ulovky
CREATE TABLE IF NOT EXISTS `ulovky` (
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

-- Export dat nebyl vybrán.


-- Exportování struktury pro tabulka adhunter.vlastnici
CREATE TABLE IF NOT EXISTS `vlastnici` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `nazov` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Export dat nebyl vybrán.


-- Exportování struktury pro tabulka adhunter.zadavatelia
CREATE TABLE IF NOT EXISTS `zadavatelia` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `meno` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Export dat nebyl vybrán.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
