<?php
/* 
 * struktura tabulek databáze bez dat
 */

$this->tables = <<< EEE

CREATE TABLE IF NOT EXISTS `uc_clanky` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sekce` int(11) DEFAULT NULL,
  `nazev` varchar(250) COLLATE utf8_czech_ci DEFAULT NULL,
  `pocitadlo` int(10) NOT NULL DEFAULT '0',
  `uvodnik` text COLLATE utf8_czech_ci NOT NULL,
  `clanek` text COLLATE utf8_czech_ci,
  `zobrazovat` tinyint(1) NOT NULL DEFAULT '0',
  `datum` datetime DEFAULT NULL,
  `blokall` tinyint(1) NOT NULL DEFAULT '0',
  `texyuvodnik` text COLLATE utf8_czech_ci NOT NULL,
  `texyclanek` text COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `texyuvodnik` (`texyuvodnik`,`texyclanek`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=200 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `uc_kategorie`
--

CREATE TABLE IF NOT EXISTS `uc_kategorie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nazev` varchar(30) COLLATE utf8_czech_ci DEFAULT NULL,
  `zobrazovat` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `uc_kniha`
--

CREATE TABLE IF NOT EXISTS `uc_kniha` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jmeno` varchar(15) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `email` varchar(50) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `ip_adr` varchar(50) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `komentar` text COLLATE utf8_czech_ci NOT NULL,
  `zobrazovat` tinyint(1) NOT NULL DEFAULT '0',
  `cas` datetime NOT NULL,
  `titulek` varchar(50) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=113 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `uc_komentare`
--

CREATE TABLE IF NOT EXISTS `uc_komentare` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_clanku` int(11) NOT NULL DEFAULT '0',
  `jmeno` varchar(30) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `email` varchar(50) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `web` varchar(50) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `ip_adr` varchar(20) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `komentar` text COLLATE utf8_czech_ci NOT NULL,
  `zobrazovat` tinyint(1) NOT NULL DEFAULT '0',
  `cas` datetime NOT NULL,
  `titulek` varchar(50) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=212 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `uc_pristupy`
--

CREATE TABLE IF NOT EXISTS `uc_pristupy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kdo_ip` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `odkud` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `prohlizec` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `skript` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `cas` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=6886 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `uc_sekce`
--

CREATE TABLE IF NOT EXISTS `uc_sekce` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nazev` varchar(30) COLLATE utf8_czech_ci NOT NULL DEFAULT '',
  `kategorie` int(3) DEFAULT NULL,
  `claneksekce` text COLLATE utf8_czech_ci NOT NULL,
  `zobrazovat` tinyint(1) NOT NULL DEFAULT '0',
  `blokall` tinyint(1) NOT NULL DEFAULT '0',
  `texyclaneksekce` text COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=27 ;



EEE;


