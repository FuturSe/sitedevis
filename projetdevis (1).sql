-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 19 fév. 2024 à 00:24
-- Version du serveur : 5.7.36
-- Version de PHP : 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `projetdevis`
--

-- --------------------------------------------------------

--
-- Structure de la table `author`
--

DROP TABLE IF EXISTS `author`;
CREATE TABLE IF NOT EXISTS `author` (
  `author_nom` varchar(15) NOT NULL,
  `author_prenom` varchar(15) NOT NULL,
  `author_portable` varchar(20) NOT NULL,
  `author_mail` varchar(40) NOT NULL,
  `author_adresse` varchar(70) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `devis`
--

DROP TABLE IF EXISTS `devis`;
CREATE TABLE IF NOT EXISTS `devis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom_entreprise` varchar(30) NOT NULL,
  `nom_dirigeant` varchar(20) NOT NULL,
  `statut_juridique` varchar(10) NOT NULL,
  `date` date NOT NULL,
  `activite` varchar(20) NOT NULL,
  `adresse` varchar(60) NOT NULL,
  `portable` varchar(20) NOT NULL,
  `mail` varchar(30) NOT NULL,
  `objectif` varchar(40) NOT NULL,
  `montant_total_ht` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `mission`
--

DROP TABLE IF EXISTS `mission`;
CREATE TABLE IF NOT EXISTS `mission` (
  `devis_id` int(11) NOT NULL,
  `description` varchar(40) NOT NULL,
  `quantite` int(11) NOT NULL,
  `prix_unitaire` double NOT NULL,
  `montant` double NOT NULL,
  `montant_total_ht` double NOT NULL,
  `tva` double NOT NULL,
  `montant_total_ttc` double NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
