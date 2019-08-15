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

-- Dumping structure for table kinggems.user
DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `avatar` int(11) DEFAULT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `country_code` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `favorite` int(11) DEFAULT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `refer_code` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `referred_by` int(11) DEFAULT NULL,
  `affiliated_with` int(11) DEFAULT NULL,
  `is_reseller` smallint(6) DEFAULT '0',
  `saler_id` int(11) DEFAULT NULL,
  `saler_code` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `marketing_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `password_reset_token` (`password_reset_token`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table kinggems.user: ~25 rows (approximately)
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`id`, `name`, `username`, `avatar`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `country_code`, `phone`, `address`, `birthday`, `favorite`, `status`, `refer_code`, `referred_by`, `affiliated_with`, `is_reseller`, `saler_id`, `saler_code`, `marketing_id`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin', NULL, 't77JupbwmLZ7I1EIYKu_G8ci-ohksTSe', '$2y$13$2tNXeE3K29Z1apds6pFYQOch96L.EVciyGjZsqPSFHpxfPjrg2ceW', NULL, 'phamngocson1988@gmail.com', '+84', '+84986803325', 'Việt Nam', '1988-01-01', NULL, 10, NULL, NULL, NULL, 1, NULL, NULL, NULL, '2019-04-03 08:38:01', '2019-04-29 16:14:15');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
