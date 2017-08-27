SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE `categories` (
  `id` int(4) UNSIGNED NOT NULL,
  `name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `drinks` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `start_price` decimal(8,2) UNSIGNED DEFAULT NULL,
  `minimum_price` decimal(8,2) UNSIGNED DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

CREATE TABLE `drink_category` (
  `drink_id` int(10) UNSIGNED NOT NULL,
  `category_id` int(4) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `drink_id` int(10) UNSIGNED NOT NULL,
  `amount` int(10) UNSIGNED NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED;
CREATE TABLE `order_history` (
   `drink_id` int(10) unsigned
  ,`amount` decimal(32,0)
  ,`timeframe` bigint(17)
);

CREATE TABLE `settings` (
  `key` varchar(32) NOT NULL,
  `value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
DROP TABLE IF EXISTS `order_history`;

CREATE VIEW `order_history`  AS  select `orders`.`drink_id` AS `drink_id`,sum(`orders`.`amount`) AS `amount`,floor((unix_timestamp(`orders`.`date`) / (10 * 60))) AS `timeframe` from `orders` group by `orders`.`drink_id`,floor((unix_timestamp(`orders`.`date`) / (10 * 60))) order by floor((unix_timestamp(`orders`.`date`) / (10 * 60))) ;


ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);

ALTER TABLE `drinks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `active` (`active`),
  ADD KEY `name` (`name`);

ALTER TABLE `drink_category`
  ADD UNIQUE KEY `index` (`drink_id`,`category_id`),
  ADD KEY `category_fk` (`category_id`);

ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `drink_id` (`drink_id`),
  ADD KEY `date` (`date`);

ALTER TABLE `settings`
  ADD PRIMARY KEY (`key`);


ALTER TABLE `categories`
  MODIFY `id` int(4) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `drinks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `drink_category`
  ADD CONSTRAINT `category_fk` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `drink_fk` FOREIGN KEY (`drink_id`) REFERENCES `drinks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
