-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.5.25a - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL Version:             9.4.0.5125
-- --------------------------------------------------------
-- Dumping structure for table kinggems.auth_rule
CREATE TABLE IF NOT EXISTS `auth_rule` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
-- Dumping structure for table kinggems.auth_item
-- Dumping data for table kinggems.auth_rule: ~5 rows (approximately)
/*!40000 ALTER TABLE `auth_rule` DISABLE KEYS */;
INSERT INTO `auth_rule` (`name`, `data`, `created_at`, `updated_at`) VALUES
	('cancel_order_rule', _binary 0x4F3A32383A226261636B656E645C726261635C43616E63656C4F7264657252756C65223A333A7B733A343A226E616D65223B733A31373A2263616E63656C5F6F726465725F72756C65223B733A393A22637265617465644174223B693A313535343639353234303B733A393A22757064617465644174223B693A313535343639353234303B7D, 1554695240, 1554695240),
	('delete_order_rule', _binary 0x4F3A32383A226261636B656E645C726261635C44656C6574654F7264657252756C65223A333A7B733A343A226E616D65223B733A31373A2264656C6574655F6F726465725F72756C65223B733A393A22637265617465644174223B693A313535343639353035353B733A393A22757064617465644174223B693A313535343639353035353B7D, 1554695055, 1554695055),
	('edit_order_rule', _binary 0x4F3A32363A226261636B656E645C726261635C456469744F7264657252756C65223A333A7B733A343A226E616D65223B733A31353A22656469745F6F726465725F72756C65223B733A393A22637265617465644174223B693A313535343639323731393B733A393A22757064617465644174223B693A313535343639323731393B7D, 1554692719, 1554692719),
	('taken_order_rule', _binary 0x4F3A32373A226261636B656E645C726261635C54616B656E4F7264657252756C65223A333A7B733A343A226E616D65223B733A31363A2274616B656E5F6F726465725F72756C65223B733A393A22637265617465644174223B693A313535343639343431363B733A393A22757064617465644174223B693A313535343639343431363B7D, 1554694416, 1554694416),
	('view_customer_rule', _binary 0x4F3A32393A226261636B656E645C726261635C56696577437573746F6D657252756C65223A333A7B733A343A226E616D65223B733A31383A22766965775F637573746F6D65725F72756C65223B733A393A22637265617465644174223B693A313535383333383736363B733A393A22757064617465644174223B693A313535383333383736363B7D, 1558338766, 1558338766);
/*!40000 ALTER TABLE `auth_rule` ENABLE KEYS */;

CREATE TABLE IF NOT EXISTS `auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` smallint(6) NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`),
  CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table kinggems.auth_item: ~12 rows (approximately)
/*!40000 ALTER TABLE `auth_item` DISABLE KEYS */;
INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES
	('accounting', 1, 'Kế toán', NULL, NULL, 1554284228, 1554284228),
	('admin', 1, 'Admin', NULL, NULL, 1554255482, 1554692240),
	('cancel_order', 2, 'Cancel order', 'cancel_order_rule', NULL, 1554695240, 1554695240),
	('customer', 1, 'Khách hàng', NULL, NULL, 1557286082, 1557286082),
	('delete_order', 2, 'Delete order', 'delete_order_rule', NULL, 1554695055, 1554695055),
	('edit_order', 2, 'Edit order', 'edit_order_rule', NULL, 1554692719, 1554692719),
	('orderteam', 1, 'Nhân viên quản lý đơn hàng', NULL, NULL, 1557290272, 1561607043),
	('orderteam_manager', 1, 'Quản lý xử lý đơn hàng', NULL, NULL, 1557286231, 1557286231),
	('sale_manager', 1, 'Quản lý nhân viên bán hàng', NULL, NULL, 1557288735, 1557288735),
	('saler', 1, 'Nhân viên bán hàng', NULL, NULL, 1557290728, 1557291165),
	('taken_order', 2, 'Taken order', 'taken_order_rule', NULL, 1554694416, 1554694416),
	('view_customer', 2, 'View customer', 'view_customer_rule', NULL, 1558338767, 1558338767);
/*!40000 ALTER TABLE `auth_item` ENABLE KEYS */;


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table kinggems.auth_assignment
CREATE TABLE IF NOT EXISTS `auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  KEY `idx-auth_assignment-user_id` (`user_id`),
  CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table kinggems.auth_assignment: ~13 rows (approximately)
/*!40000 ALTER TABLE `auth_assignment` DISABLE KEYS */;
INSERT INTO `auth_assignment` (`item_name`, `user_id`, `created_at`) VALUES ('admin', '1', 1554255482);
/*!40000 ALTER TABLE `auth_assignment` ENABLE KEYS */;





-- Dumping structure for table kinggems.auth_item_child
CREATE TABLE IF NOT EXISTS `auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table kinggems.auth_item_child: ~15 rows (approximately)
/*!40000 ALTER TABLE `auth_item_child` DISABLE KEYS */;
INSERT INTO `auth_item_child` (`parent`, `child`) VALUES
	('admin', 'accounting'),
	('orderteam', 'cancel_order'),
	('saler', 'cancel_order'),
	('orderteam', 'delete_order'),
	('saler', 'delete_order'),
	('orderteam', 'edit_order'),
	('saler', 'edit_order'),
	('orderteam_manager', 'orderteam'),
	('admin', 'orderteam_manager'),
	('admin', 'sale_manager'),
	('sale_manager', 'saler'),
	('orderteam', 'taken_order'),
	('saler', 'taken_order'),
	('orderteam', 'view_customer'),
	('saler', 'view_customer');
/*!40000 ALTER TABLE `auth_item_child` ENABLE KEYS */;

