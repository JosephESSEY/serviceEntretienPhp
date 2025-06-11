-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 11 juin 2025 à 09:31
-- Version du serveur : 8.3.0
-- Version de PHP : 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `unitetechnique`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id_admin` int NOT NULL AUTO_INCREMENT,
  `membreId` int NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_admin`),
  KEY `membreId` (`membreId`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `admin`
--

INSERT INTO `admin` (`id_admin`, `membreId`, `password`) VALUES
(16, 42, '$2y$10$x/TSzX4PGV7RPl09W6EyQuweHFVQ6SWDbdNhwhSgFn2Ry288n/7Eu');

-- --------------------------------------------------------

--
-- Structure de la table `autres_cotisations`
--

DROP TABLE IF EXISTS `autres_cotisations`;
CREATE TABLE IF NOT EXISTS `autres_cotisations` (
  `id_ac` int NOT NULL AUTO_INCREMENT,
  `motif_acotisation` text COLLATE utf8mb4_general_ci NOT NULL,
  `date_acot` text COLLATE utf8mb4_general_ci NOT NULL,
  `montant_acotisation` int NOT NULL,
  `anne` int NOT NULL,
  PRIMARY KEY (`id_ac`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `autres_cotisations`
--

INSERT INTO `autres_cotisations` (`id_ac`, `motif_acotisation`, `date_acot`, `montant_acotisation`, `anne`) VALUES
(24, 'Test', '2025-06-09', 5000, 2025);

-- --------------------------------------------------------

--
-- Structure de la table `contributions_cotisations`
--

DROP TABLE IF EXISTS `contributions_cotisations`;
CREATE TABLE IF NOT EXISTS `contributions_cotisations` (
  `id_cont` int NOT NULL AUTO_INCREMENT,
  `cotisation_id` int NOT NULL,
  `membres_id` int NOT NULL,
  `montant` int NOT NULL,
  `date_contribution` date NOT NULL,
  PRIMARY KEY (`id_cont`),
  KEY `cotisation_id` (`cotisation_id`),
  KEY `membres_id` (`membres_id`)
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `contributions_cotisations`
--

INSERT INTO `contributions_cotisations` (`id_cont`, `cotisation_id`, `membres_id`, `montant`, `date_contribution`) VALUES
(84, 24, 42, 200345, '2025-06-09'),
(85, 24, 42, 5000, '2025-06-09');

-- --------------------------------------------------------

--
-- Structure de la table `cotisation`
--

DROP TABLE IF EXISTS `cotisation`;
CREATE TABLE IF NOT EXISTS `cotisation` (
  `id_cotisation` int NOT NULL AUTO_INCREMENT,
  `montant` int NOT NULL,
  `datepayement` date NOT NULL,
  `anne` varchar(5) COLLATE utf8mb4_general_ci NOT NULL,
  `id_membre` int NOT NULL,
  `id_mois` int NOT NULL,
  PRIMARY KEY (`id_cotisation`),
  KEY `id_membre` (`id_membre`),
  KEY `id_mois` (`id_mois`)
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `cotisation`
--

INSERT INTO `cotisation` (`id_cotisation`, `montant`, `datepayement`, `anne`, `id_membre`, `id_mois`) VALUES
(67, 200, '2025-06-09', '2025', 42, 1),
(68, 200, '2025-06-09', '2025', 42, 2),
(69, 50000, '2025-06-09', '2025', 42, 3);

-- --------------------------------------------------------

--
-- Structure de la table `cotisationtotal`
--

DROP TABLE IF EXISTS `cotisationtotal`;
CREATE TABLE IF NOT EXISTS `cotisationtotal` (
  `id_cotisationtotal` int NOT NULL AUTO_INCREMENT,
  `id_m` int NOT NULL,
  `montant` int NOT NULL,
  `anne` int NOT NULL,
  PRIMARY KEY (`id_cotisationtotal`),
  KEY `id_m` (`id_m`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `cotisationtotal`
--

INSERT INTO `cotisationtotal` (`id_cotisationtotal`, `id_m`, `montant`, `anne`) VALUES
(31, 42, 52600, 2025);

-- --------------------------------------------------------

--
-- Structure de la table `membres`
--

DROP TABLE IF EXISTS `membres`;
CREATE TABLE IF NOT EXISTS `membres` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `prenom` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `datenaiss` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `telephone` varchar(12) COLLATE utf8mb4_general_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `membres`
--

INSERT INTO `membres` (`id`, `nom`, `prenom`, `datenaiss`, `telephone`, `photo`) VALUES
(42, 'ESSEY', 'Joseph', '2025-06-07', '93829581', '../assets/images/WhatsApp Image 2025-02-23 à 04.53.19_ac6b84d6.jpg'),
(43, 'MBOUROU', 'Mercedes', '2025-06-06', '93849382', '../assets/images/Capture d’écran 2024-11-01 122903.png');

-- --------------------------------------------------------

--
-- Structure de la table `mois`
--

DROP TABLE IF EXISTS `mois`;
CREATE TABLE IF NOT EXISTS `mois` (
  `id_mois` int NOT NULL AUTO_INCREMENT,
  `nom_mois` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_mois`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `mois`
--

INSERT INTO `mois` (`id_mois`, `nom_mois`) VALUES
(1, 'Janvier'),
(2, 'Février'),
(3, 'Mars'),
(4, 'Avril'),
(5, 'Mai'),
(6, 'Juin'),
(7, 'Juillet'),
(8, 'Aout'),
(9, 'Septembre'),
(10, 'Octobre'),
(11, 'Novembre'),
(12, 'Décembre');

-- --------------------------------------------------------

--
-- Structure de la table `retraits_evenements`
--

DROP TABLE IF EXISTS `retraits_evenements`;
CREATE TABLE IF NOT EXISTS `retraits_evenements` (
  `id_rc` int NOT NULL AUTO_INCREMENT,
  `id_cot` int NOT NULL,
  `montant` int NOT NULL,
  `date_retrait` date NOT NULL,
  `description` text COLLATE utf8mb4_general_ci NOT NULL,
  `anne` int NOT NULL,
  PRIMARY KEY (`id_rc`),
  KEY `id_cot` (`id_cot`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `retraits_mensuels`
--

DROP TABLE IF EXISTS `retraits_mensuels`;
CREATE TABLE IF NOT EXISTS `retraits_mensuels` (
  `id_rm` int NOT NULL AUTO_INCREMENT,
  `montant` int NOT NULL,
  `restant` int NOT NULL,
  `date_retrait` date NOT NULL,
  `description` text COLLATE utf8mb4_general_ci NOT NULL,
  `anne` varchar(5) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_rm`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `retraits_mensuels`
--

INSERT INTO `retraits_mensuels` (`id_rm`, `montant`, `restant`, `date_retrait`, `description`, `anne`) VALUES
(36, 45500, 4900, '2025-06-09', 'Fête à la Plage', '2025');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`membreId`) REFERENCES `membres` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `contributions_cotisations`
--
ALTER TABLE `contributions_cotisations`
  ADD CONSTRAINT `contributions_cotisations_ibfk_1` FOREIGN KEY (`membres_id`) REFERENCES `membres` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `contributions_cotisations_ibfk_2` FOREIGN KEY (`cotisation_id`) REFERENCES `autres_cotisations` (`id_ac`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `cotisation`
--
ALTER TABLE `cotisation`
  ADD CONSTRAINT `cotisation_ibfk_1` FOREIGN KEY (`id_membre`) REFERENCES `membres` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cotisation_ibfk_2` FOREIGN KEY (`id_mois`) REFERENCES `mois` (`id_mois`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `cotisationtotal`
--
ALTER TABLE `cotisationtotal`
  ADD CONSTRAINT `cotisationtotal_ibfk_2` FOREIGN KEY (`id_m`) REFERENCES `membres` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `retraits_evenements`
--
ALTER TABLE `retraits_evenements`
  ADD CONSTRAINT `retraits_evenements_ibfk_1` FOREIGN KEY (`id_cot`) REFERENCES `autres_cotisations` (`id_ac`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
