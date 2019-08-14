-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  mer. 14 août 2019 à 17:02
-- Version du serveur :  5.7.23
-- Version de PHP :  7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `pawtopia`
--

-- --------------------------------------------------------

--
-- Structure de la table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
CREATE TABLE IF NOT EXISTS `reviews` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_REVIEWER` int(11) NOT NULL,
  `ID_USER` int(11) NOT NULL,
  `NOTE` tinyint(4) NOT NULL,
  `MESSAGE` varchar(255) NOT NULL DEFAULT '',
  `DATE` datetime DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `reviews`
--

INSERT INTO `reviews` (`ID`, `ID_REVIEWER`, `ID_USER`, `NOTE`, `MESSAGE`, `DATE`) VALUES
(1, 45, 44, 4, 'Tro nul', '2019-08-08 00:00:00'),
(2, 20, 44, 5, 'Tro nul', NULL),
(3, 45, 44, 1, 'Tro nul', NULL),
(4, 44, 46, 5, 'v', '2019-08-10 00:00:00'),
(5, 44, 824, 5, 'Son robot a pissé de l\'huile', '2019-08-10 00:00:00');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
