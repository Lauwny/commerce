-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 13 juin 2022 à 21:52
-- Version du serveur :  8.0.21
-- Version de PHP : 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `commerce`
--

-- --------------------------------------------------------

--
-- Structure de la table `adress`
--

DROP TABLE IF EXISTS `adress`;
CREATE TABLE IF NOT EXISTS `adress` (
  `adressId` int NOT NULL AUTO_INCREMENT,
  `adressStreetNumber` varchar(100) NOT NULL,
  `adressStreetName` varchar(300) NOT NULL,
  `adressPostaleCode` varchar(300) NOT NULL,
  `adressCity` varchar(300) NOT NULL,
  `adressCountry` varchar(300) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`adressId`),
  UNIQUE KEY `adressPostaleCode` (`adressPostaleCode`),
  UNIQUE KEY `adressCity` (`adressCity`),
  UNIQUE KEY `adressCountry` (`adressCountry`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `adress`
--

INSERT INTO `adress` (`adressId`, `adressStreetNumber`, `adressStreetName`, `adressPostaleCode`, `adressCity`, `adressCountry`, `created_at`, `updated_at`) VALUES
(1, '2', 'rue jules lemoine', '91290', 'Arpajon', 'France', '2022-06-13 20:39:11', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `categoryId` int NOT NULL AUTO_INCREMENT,
  `categoryLabel` varchar(300) NOT NULL,
  PRIMARY KEY (`categoryId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `customerprofile`
--

DROP TABLE IF EXISTS `customerprofile`;
CREATE TABLE IF NOT EXISTS `customerprofile` (
  `customerId` int NOT NULL AUTO_INCREMENT,
  `customerName` varchar(100) NOT NULL,
  `customerLastname` varchar(100) NOT NULL,
  `customerAdressId` int NOT NULL,
  `customerUserId` int NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`customerId`),
  UNIQUE KEY `customerAdressId` (`customerAdressId`),
  UNIQUE KEY `customerUserId` (`customerUserId`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `customerprofile`
--

INSERT INTO `customerprofile` (`customerId`, `customerName`, `customerLastname`, `customerAdressId`, `customerUserId`, `created_at`, `updated_at`) VALUES
(1, 'Corentin', 'Lauret', 1, 1, '2022-06-13 20:40:08', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int NOT NULL,
  `batch` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=133 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(127, '2022-06-13-145653', 'App\\Database\\Migrations\\Adress', 'default', 'App', 1655135221, 1),
(128, '2022-06-13-145701', 'App\\Database\\Migrations\\UserProfile', 'default', 'App', 1655135221, 1),
(129, '2022-06-13-145708', 'App\\Database\\Migrations\\Category', 'default', 'App', 1655135221, 1),
(130, '2022-06-13-145811', 'App\\Database\\Migrations\\CustomerProfile', 'default', 'App', 1655135221, 1),
(131, '2022-06-13-145816', 'App\\Database\\Migrations\\Product', 'default', 'App', 1655135221, 1),
(132, '2022-06-13-145820', 'App\\Database\\Migrations\\Purchase', 'default', 'App', 1655135221, 1);

-- --------------------------------------------------------

--
-- Structure de la table `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `productId` int NOT NULL AUTO_INCREMENT,
  `productRef` varchar(300) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  `productName` varchar(300) NOT NULL,
  `productDescription` varchar(300) NOT NULL,
  `pictures` json NOT NULL,
  `stock` int NOT NULL,
  `categoryId` int NOT NULL,
  PRIMARY KEY (`productId`),
  KEY `Product_categoryId_foreign` (`categoryId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `purchase`
--

DROP TABLE IF EXISTS `purchase`;
CREATE TABLE IF NOT EXISTS `purchase` (
  `purchaseId` int NOT NULL AUTO_INCREMENT,
  `purchaseRef` varchar(300) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  `productNb` int NOT NULL,
  `price` float NOT NULL,
  `idProduct` int NOT NULL,
  `idCustomer` int NOT NULL,
  PRIMARY KEY (`purchaseId`),
  KEY `Purchase_idProduct_foreign` (`idProduct`),
  KEY `Purchase_idCustomer_foreign` (`idCustomer`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `userprofile`
--

DROP TABLE IF EXISTS `userprofile`;
CREATE TABLE IF NOT EXISTS `userprofile` (
  `userId` int NOT NULL AUTO_INCREMENT,
  `userUsername` varchar(100) NOT NULL,
  `userPassword` varchar(100) NOT NULL,
  `userMail` varchar(100) NOT NULL,
  `userRetainerFee` int NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  `last_visited_at` datetime DEFAULT NULL,
  PRIMARY KEY (`userId`),
  UNIQUE KEY `userMail` (`userMail`),
  UNIQUE KEY `userRetainerFee` (`userRetainerFee`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `userprofile`
--

INSERT INTO `userprofile` (`userId`, `userUsername`, `userPassword`, `userMail`, `userRetainerFee`, `created_at`, `updated_at`, `last_visited_at`) VALUES
(1, 'launy', '53175bcc0524f37b47062fafdda28e3f8eb91d519ca0a184ca71bbebe72f969a', 'macflodla@gmail.com', 0, '2022-06-13 20:39:47', NULL, NULL);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `customerprofile`
--
ALTER TABLE `customerprofile`
  ADD CONSTRAINT `customerProfile_customerAdressId_foreign` FOREIGN KEY (`customerAdressId`) REFERENCES `adress` (`adressId`),
  ADD CONSTRAINT `customerProfile_customerUserId_foreign` FOREIGN KEY (`customerUserId`) REFERENCES `userprofile` (`userId`);

--
-- Contraintes pour la table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `Product_categoryId_foreign` FOREIGN KEY (`categoryId`) REFERENCES `category` (`categoryId`);

--
-- Contraintes pour la table `purchase`
--
ALTER TABLE `purchase`
  ADD CONSTRAINT `Purchase_idCustomer_foreign` FOREIGN KEY (`idCustomer`) REFERENCES `customerprofile` (`customerId`),
  ADD CONSTRAINT `Purchase_idProduct_foreign` FOREIGN KEY (`idProduct`) REFERENCES `product` (`productId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
