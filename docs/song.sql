-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: 20-Nov-2018 às 19:03
-- Versão do servidor: 5.7.23-log
-- PHP Version: 5.6.35

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `song`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `cd`
--

DROP TABLE IF EXISTS `cd`;
CREATE TABLE IF NOT EXISTS `cd` (
  `id_cd` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `singer` varchar(200) NOT NULL,
  `category` varchar(200) NOT NULL,
  `volume` varchar(32) NOT NULL,
  `fg_ativo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_cd`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `hymn`
--

DROP TABLE IF EXISTS `hymn`;
CREATE TABLE IF NOT EXISTS `hymn` (
  `id_hymn` int(11) NOT NULL AUTO_INCREMENT,
  `id_cd` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `url` varchar(200) NOT NULL,
  `fg_ativo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_hymn`),
  KEY `id_cd` (`id_cd`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `hymn`
--
ALTER TABLE `hymn`
  ADD CONSTRAINT `hymn_ibfk_1` FOREIGN KEY (`id_cd`) REFERENCES `cd` (`id_cd`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
