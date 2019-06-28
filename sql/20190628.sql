-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.5.25a - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL Version:             9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table kinggems.game
DROP TABLE IF EXISTS `game`;
CREATE TABLE IF NOT EXISTS `game` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `excerpt` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `unit_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `image_id` int(11) DEFAULT NULL,
  `price` int(11) NOT NULL,
  `original_price` int(11) DEFAULT NULL,
  `pack` int(11) NOT NULL DEFAULT '1',
  `meta_title` varchar(160) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_keyword` varchar(160) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_description` varchar(160) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('Y','N','D') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Y',
  `pin` tinyint(4) DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.
-- Dumping structure for table kinggems.game_image
DROP TABLE IF EXISTS `game_image`;
CREATE TABLE IF NOT EXISTS `game_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` int(11) NOT NULL,
  `image_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.
-- Dumping structure for table kinggems.image
DROP TABLE IF EXISTS `image`;
CREATE TABLE IF NOT EXISTS `image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `extension` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `size` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Data exporting was unselected.
-- Dumping structure for table kinggems.order
DROP TABLE IF EXISTS `order`;
CREATE TABLE IF NOT EXISTS `order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `auth_key` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_data` text COLLATE utf8mb4_unicode_ci,
  `sub_total_price` int(11) DEFAULT '0',
  `quantity` int(11) NOT NULL DEFAULT '1',
  `total_discount` int(11) DEFAULT '0',
  `total_fee` int(11) DEFAULT '0',
  `total_tax` int(11) DEFAULT '0',
  `total_price` int(11) DEFAULT '0',
  `customer_id` int(11) NOT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `saler_id` int(11) DEFAULT NULL,
  `orderteam_id` int(11) DEFAULT NULL,
  `rating` tinyint(2) DEFAULT '0',
  `comment_rating` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `payment_at` datetime DEFAULT NULL,
  `status` enum('verifying','pending','processing','completed','deleted') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'verifying',
  `request_cancel` tinyint(4) NOT NULL DEFAULT '0',
  `request_cancel_time` datetime NOT NULL,
  `game_id` int(11) NOT NULL,
  `game_title` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_unit` int(11) NOT NULL,
  `doing_unit` int(11) NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `platform` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `login_method` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `character_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recover_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `server` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `process_start_time` datetime DEFAULT NULL,
  `process_end_time` datetime DEFAULT NULL,
  `process_duration_time` int(11) DEFAULT NULL,
  `provider_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.
-- Dumping structure for table kinggems.order_comments
DROP TABLE IF EXISTS `order_comments`;
CREATE TABLE IF NOT EXISTS `order_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `comment` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.
-- Dumping structure for table kinggems.order_complains
DROP TABLE IF EXISTS `order_complains`;
CREATE TABLE IF NOT EXISTS `order_complains` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `content` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` int(1) DEFAULT '0',
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.
-- Dumping structure for table kinggems.order_complain_template
DROP TABLE IF EXISTS `order_complain_template`;
CREATE TABLE IF NOT EXISTS `order_complain_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.
-- Dumping structure for table kinggems.order_fee
DROP TABLE IF EXISTS `order_fee`;
CREATE TABLE IF NOT EXISTS `order_fee` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `type` enum('discount','fee','tax') COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.
-- Dumping structure for table kinggems.order_file
DROP TABLE IF EXISTS `order_file`;
CREATE TABLE IF NOT EXISTS `order_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  `file_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.
-- Dumping structure for table kinggems.order_image
DROP TABLE IF EXISTS `order_image`;
CREATE TABLE IF NOT EXISTS `order_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `image_before_payment` int(11) DEFAULT NULL,
  `image_after_payment` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.
-- Dumping structure for table kinggems.order_items
DROP TABLE IF EXISTS `order_items`;
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_id` int(11) NOT NULL,
  `type` enum('product','payment_fee') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'product',
  `product_id` int(11) DEFAULT NULL,
  `game_id` int(11) DEFAULT NULL,
  `price` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `unit_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit` int(11) DEFAULT NULL,
  `total_unit` int(11) DEFAULT '0',
  `doing_unit` int(11) DEFAULT '0',
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `platform` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `login_method` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `character_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recover_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `server` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_before_payment` int(11) DEFAULT NULL,
  `image_after_payment` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.
-- Dumping structure for table kinggems.pricing_coin
DROP TABLE IF EXISTS `pricing_coin`;
CREATE TABLE IF NOT EXISTS `pricing_coin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `num_of_coin` int(11) NOT NULL,
  `amount` int(11) NOT NULL DEFAULT '0',
  `is_best` enum('Y','N') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('Y','N') COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.
-- Dumping structure for table kinggems.promotion
DROP TABLE IF EXISTS `promotion`;
CREATE TABLE IF NOT EXISTS `promotion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `image_id` int(11) DEFAULT NULL,
  `code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `promotion_type` enum('fix','percent') COLLATE utf8mb4_unicode_ci DEFAULT 'percent',
  `value` int(11) DEFAULT NULL,
  `promotion_scenario` enum('coin','money') COLLATE utf8mb4_unicode_ci DEFAULT 'money',
  `user_using` int(11) DEFAULT NULL,
  `total_using` int(11) DEFAULT NULL,
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `promotion_direction` enum('up','down') COLLATE utf8mb4_unicode_ci DEFAULT 'up',
  `status` enum('Y','N','D') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Y',
  `is_valid` smallint(6) DEFAULT '1',
  `rule_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rule_data` tinytext COLLATE utf8mb4_unicode_ci,
  `benefit_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `benefit_data` tinytext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.
-- Dumping structure for table kinggems.promotion_apply
DROP TABLE IF EXISTS `promotion_apply`;
CREATE TABLE IF NOT EXISTS `promotion_apply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `promotion_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.
-- Dumping structure for table kinggems.transaction
DROP TABLE IF EXISTS `transaction`;
CREATE TABLE IF NOT EXISTS `transaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `auth_key` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `payment_method` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_data` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `payment_at` datetime DEFAULT NULL,
  `price` int(11) NOT NULL DEFAULT '0',
  `discount_price` int(11) NOT NULL DEFAULT '0',
  `total_price` int(11) NOT NULL DEFAULT '0',
  `coin` int(11) NOT NULL DEFAULT '0',
  `discount_coin` int(11) NOT NULL DEFAULT '0',
  `total_coin` int(11) NOT NULL DEFAULT '0',
  `discount_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `status` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
