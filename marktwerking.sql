SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE `drinks` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `start_price` decimal(8,2) UNSIGNED DEFAULT NULL,
  `minimum_price` decimal(8,2) UNSIGNED DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

CREATE TABLE `orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `drink_id` int(10) NOT NULL,
  `amount` int(10) UNSIGNED NOT NULL,
  `date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=FIXED;
CREATE TABLE `order_history` (
   `drink_id` int(10)
  ,`amount` decimal(32,0)
  ,`timeframe` bigint(17)
);
DROP TABLE IF EXISTS `order_history`;

CREATE VIEW `order_history`  AS  select `orders`.`drink_id` AS `drink_id`,sum(`orders`.`amount`) AS `amount`,floor((unix_timestamp(`orders`.`date`) / (10 * 60))) AS `timeframe` from `orders` group by `orders`.`drink_id`,floor((unix_timestamp(`orders`.`date`) / (10 * 60))) order by floor((unix_timestamp(`orders`.`date`) / (10 * 60)));


ALTER TABLE `drinks`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `drink_id` (`drink_id`);


ALTER TABLE `drinks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
