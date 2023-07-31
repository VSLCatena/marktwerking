-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Jul 31, 2023 at 08:38 AM
-- Server version: 10.11.4-MariaDB
-- PHP Version: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(4) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COLLATE=latin1;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(2, 'Bier (fles)'),
(3, 'Bier (tap)'),
(1, 'Fris'),
(4, 'Sterk');

-- --------------------------------------------------------

--
-- Table structure for table `drinks`
--

DROP TABLE IF EXISTS `drinks`;
CREATE TABLE IF NOT EXISTS `drinks` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `start_price` decimal(8,2) UNSIGNED DEFAULT NULL,
  `minimum_price` decimal(8,2) UNSIGNED DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `active` (`active`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `drinks`
--

INSERT INTO `drinks` (`id`, `name`, `start_price`, `minimum_price`, `active`) VALUES
(1, 'Bier', 3.00, 2.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `drink_category`
--

DROP TABLE IF EXISTS `drink_category`;
CREATE TABLE IF NOT EXISTS `drink_category` (
  `drink_id` int(10) UNSIGNED NOT NULL,
  `category_id` int(4) UNSIGNED NOT NULL,
  UNIQUE KEY `index` (`drink_id`,`category_id`),
  KEY `category_fk` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1;

--
-- Dumping data for table `drink_category`
--

INSERT INTO `drink_category` (`drink_id`, `category_id`) VALUES
(1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `drink_id` int(10) UNSIGNED NOT NULL,
  `amount` int(10) UNSIGNED NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `drink_id` (`drink_id`),
  KEY `date` (`date`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1;

-- --------------------------------------------------------

--
-- Stand-in structure for view `order_history`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `order_history`;
CREATE TABLE IF NOT EXISTS `order_history` (
`drink_id` int(10) unsigned
,`amount` decimal(32,0)
,`timeframe` bigint(18)
);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `setting` varchar(32) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`setting`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`setting`, `value`) VALUES
('limit', '0'),
('mode', '1'),
('time_round', '10'),
('time_total', '2');

-- --------------------------------------------------------

--
-- Structure for view `order_history`
--
DROP TABLE IF EXISTS `order_history`;

DROP VIEW IF EXISTS `order_history`;
CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `order_history`  AS SELECT `orders`.`drink_id` AS `drink_id`, sum(`orders`.`amount`) AS `amount`, floor(unix_timestamp(`orders`.`date`) / (10 * 60)) AS `timeframe` FROM `orders` GROUP BY `orders`.`drink_id`, floor(unix_timestamp(`orders`.`date`) / (10 * 60)) ORDER BY floor(unix_timestamp(`orders`.`date`) / (10 * 60)) ASC ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `drink_category`
--
ALTER TABLE `drink_category`
  ADD CONSTRAINT `category_fk` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `drink_fk` FOREIGN KEY (`drink_id`) REFERENCES `drinks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
