/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

DROP TABLE IF EXISTS `customers`;
CREATE TABLE `customers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `email` varchar(191) DEFAULT NULL,
  `phone` varchar(191) DEFAULT NULL,
  `address` varchar(191) DEFAULT NULL,
  `avatar` varchar(191) DEFAULT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customers_user_id_foreign` (`user_id`),
  CONSTRAINT `customers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `hpp_product`;
CREATE TABLE `hpp_product` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=203 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `price` decimal(14,4) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `order_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `supplier_id` bigint(20) unsigned DEFAULT NULL,
  `due_day` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `orders_user_id_foreign` (`user_id`),
  KEY `orders_supplier_id_foreign` (`supplier_id`),
  CONSTRAINT `orders_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
  `email` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `amount` decimal(14,4) NOT NULL,
  `order_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `supplier_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payments_order_id_foreign` (`order_id`),
  KEY `payments_user_id_foreign` (`user_id`),
  KEY `payments_supplier_id_foreign` (`supplier_id`),
  CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payments_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(191) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `barcode` varchar(191) NOT NULL,
  `price` decimal(14,2) NOT NULL,
  `purchase_price` decimal(8,2) NOT NULL,
  `expired_date` datetime DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `uom` enum('pcs','box','lusin','kodi','rim','gross','meter','sachet','centimeter','milimeter','liter','mililiter','gram','miligram','kilogram','ton','kwintal','ons','mg','ml','cc','buah','butir','lembar','batang','kantong','karung') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_barcode_unique` (`barcode`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(191) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(191) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `suppliers`;
CREATE TABLE `suppliers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `supplier_name` varchar(50) NOT NULL,
  `address` varchar(191) DEFAULT NULL,
  `phone` varchar(191) DEFAULT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `suppliers_user_id_foreign` (`user_id`),
  CONSTRAINT `suppliers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `user_cart`;
CREATE TABLE `user_cart` (
  `user_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `quantity` int(10) unsigned NOT NULL,
  KEY `user_cart_user_id_foreign` (`user_id`),
  KEY `user_cart_product_id_foreign` (`product_id`),
  CONSTRAINT `user_cart_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_cart_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `user_purchase`;
CREATE TABLE `user_purchase` (
  `user_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `quantity` int(10) unsigned NOT NULL,
  KEY `user_purchase_user_id_foreign` (`user_id`),
  KEY `user_purchase_product_id_foreign` (`product_id`),
  CONSTRAINT `user_purchase_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_purchase_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(191) NOT NULL,
  `password` varchar(191) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;





INSERT INTO `hpp_product` (`id`, `product_id`, `quantity`, `price`, `total`, `created_at`, `updated_at`) VALUES
(4, 12, 50, 11000, 550000, '2023-08-02 21:49:34', '2023-08-02 21:49:34');
INSERT INTO `hpp_product` (`id`, `product_id`, `quantity`, `price`, `total`, `created_at`, `updated_at`) VALUES
(5, 12, 41, 12000, 492000, '2023-08-02 21:50:10', '2023-08-02 21:50:10');
INSERT INTO `hpp_product` (`id`, `product_id`, `quantity`, `price`, `total`, `created_at`, `updated_at`) VALUES
(6, 12, 30, 11000, 330000, '2023-08-02 21:54:46', '2023-08-02 21:54:46');
INSERT INTO `hpp_product` (`id`, `product_id`, `quantity`, `price`, `total`, `created_at`, `updated_at`) VALUES
(7, 12, 30, 12000, 360000, '2023-08-02 21:55:02', '2023-08-02 21:55:02');

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(180, '2014_10_12_000000_create_users_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(181, '2014_10_12_100000_create_password_resets_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(182, '2019_08_19_000000_create_failed_jobs_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(183, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(184, '2020_04_19_081616_create_products_table', 1),
(185, '2020_04_22_181602_add_quantity_to_products_table', 1),
(186, '2020_04_24_170630_create_customers_table', 1),
(187, '2020_04_27_054355_create_settings_table', 1),
(188, '2020_04_30_053758_create_user_cart_table', 1),
(189, '2020_05_04_165730_create_orders_table', 1),
(190, '2020_05_04_165749_create_order_items_table', 1),
(191, '2020_05_04_165822_create_payments_table', 1),
(192, '2022_03_21_125336_change_price_column', 1),
(193, '2023_03_02_102055_create_suppliers_table', 1),
(194, '2023_03_06_041914_create_user_purchase_table', 1),
(195, '2023_06_03_214609_add_expired_date_to_products_table', 2),
(196, '2023_06_04_181539_add_supplier_id_to_orders_table', 3),
(197, '2023_06_04_182022_add_supplier_id_to_payments_table', 4),
(198, '2023_06_05_231148_add_field_purchase_price_to_products_table', 5),
(199, '2023_06_19_223141_create_sessions_table', 6),
(200, '2023_06_19_232002_add_field_uom_to_products_table', 6),
(201, '2023_06_26_231655_add_due_day_to_orders_table', 6),
(202, '2023_08_02_205930_create_hpp_product_table', 7);

INSERT INTO `order_items` (`id`, `price`, `quantity`, `order_id`, `product_id`, `created_at`, `updated_at`) VALUES
(41, 70000.0000, 5, 31, 12, '2023-08-02 22:19:48', '2023-08-02 22:19:48');


INSERT INTO `orders` (`id`, `user_id`, `supplier_id`, `due_day`, `created_at`, `updated_at`) VALUES
(8, 1, NULL, NULL, '2023-05-29 19:42:09', '2023-05-29 19:42:09');
INSERT INTO `orders` (`id`, `user_id`, `supplier_id`, `due_day`, `created_at`, `updated_at`) VALUES
(9, 1, NULL, NULL, '2023-06-03 21:28:31', '2023-06-03 21:28:31');
INSERT INTO `orders` (`id`, `user_id`, `supplier_id`, `due_day`, `created_at`, `updated_at`) VALUES
(10, 1, NULL, NULL, '2023-06-04 16:13:14', '2023-06-04 16:13:14');
INSERT INTO `orders` (`id`, `user_id`, `supplier_id`, `due_day`, `created_at`, `updated_at`) VALUES
(11, 1, NULL, NULL, '2023-06-04 16:37:30', '2023-06-04 16:37:30'),
(12, 1, NULL, NULL, '2023-06-04 16:37:59', '2023-06-04 16:37:59'),
(13, 1, NULL, NULL, '2023-06-04 16:38:37', '2023-06-04 16:38:37'),
(14, 1, NULL, NULL, '2023-06-04 16:40:13', '2023-06-04 16:40:13'),
(15, 1, NULL, NULL, '2023-06-04 16:40:32', '2023-06-04 16:40:32'),
(16, 1, NULL, NULL, '2023-06-04 16:40:39', '2023-06-04 16:40:39'),
(17, 1, NULL, NULL, '2023-06-04 18:25:19', '2023-06-04 18:25:19'),
(19, 1, 3, NULL, '2023-06-04 18:29:02', '2023-06-04 18:29:02'),
(20, 1, 3, NULL, '2023-06-04 18:29:22', '2023-06-04 18:29:22'),
(21, 1, 4, NULL, '2023-06-04 18:41:24', '2023-06-04 18:41:24'),
(22, 1, 3, NULL, '2023-06-04 18:45:27', '2023-06-04 18:45:27'),
(23, 1, 4, NULL, '2023-06-04 18:51:48', '2023-06-04 18:51:48'),
(24, 1, NULL, NULL, '2023-06-04 19:08:45', '2023-06-04 19:08:45'),
(25, 1, NULL, NULL, '2023-06-04 19:09:46', '2023-06-04 19:09:46'),
(26, 1, 3, NULL, '2023-06-05 23:09:39', '2023-06-05 23:09:39'),
(27, 1, 3, NULL, '2023-06-05 23:20:25', '2023-06-05 23:20:25'),
(28, 1, 3, NULL, '2023-06-05 23:21:05', '2023-06-05 23:21:05'),
(29, 1, 4, NULL, '2023-06-05 23:23:30', '2023-06-05 23:23:30'),
(30, 1, NULL, NULL, '2023-06-06 00:06:46', '2023-06-06 00:06:46'),
(31, 1, NULL, '1970-01-01 00:00:00', '2023-08-02 22:19:48', '2023-08-02 22:19:48');



INSERT INTO `payments` (`id`, `amount`, `order_id`, `user_id`, `supplier_id`, `created_at`, `updated_at`) VALUES
(8, 75000.0000, 8, 1, NULL, '2023-05-29 19:42:09', '2023-05-29 19:42:09');
INSERT INTO `payments` (`id`, `amount`, `order_id`, `user_id`, `supplier_id`, `created_at`, `updated_at`) VALUES
(9, 85000.0000, 9, 1, NULL, '2023-06-03 21:28:31', '2023-06-03 21:28:31');
INSERT INTO `payments` (`id`, `amount`, `order_id`, `user_id`, `supplier_id`, `created_at`, `updated_at`) VALUES
(10, 25000.0000, 10, 1, NULL, '2023-06-04 16:13:14', '2023-06-04 16:13:14');
INSERT INTO `payments` (`id`, `amount`, `order_id`, `user_id`, `supplier_id`, `created_at`, `updated_at`) VALUES
(11, 60000.0000, 11, 1, NULL, '2023-06-04 16:37:31', '2023-06-04 16:37:31'),
(12, 120000.0000, 12, 1, NULL, '2023-06-04 16:37:59', '2023-06-04 16:37:59'),
(13, 100000.0000, 13, 1, NULL, '2023-06-04 16:38:37', '2023-06-04 16:38:37'),
(14, 20000.0000, 14, 1, NULL, '2023-06-04 16:40:13', '2023-06-04 16:40:13'),
(15, 20000.0000, 15, 1, NULL, '2023-06-04 16:40:32', '2023-06-04 16:40:32'),
(16, 20000.0000, 16, 1, NULL, '2023-06-04 16:40:39', '2023-06-04 16:40:39'),
(17, 90000.0000, 17, 1, NULL, '2023-06-04 18:25:19', '2023-06-04 18:25:19'),
(18, 30000.0000, 20, 1, 3, '2023-06-04 18:29:22', '2023-06-04 18:29:22'),
(19, 150000.0000, 21, 1, 4, '2023-06-04 18:41:24', '2023-06-04 18:41:24'),
(20, 150000.0000, 22, 1, 3, '2023-06-04 18:45:27', '2023-06-04 18:45:27'),
(21, 40000.0000, 23, 1, 4, '2023-06-04 18:51:48', '2023-06-04 18:51:48'),
(22, 200000.0000, 24, 1, NULL, '2023-06-04 19:08:45', '2023-06-04 19:08:45'),
(23, 60000.0000, 25, 1, NULL, '2023-06-04 19:09:46', '2023-06-04 19:09:46'),
(24, 60000.0000, 26, 1, 3, '2023-06-05 23:09:39', '2023-06-05 23:09:39'),
(25, 95000.0000, 27, 1, 3, '2023-06-05 23:20:25', '2023-06-05 23:20:25'),
(26, 150000.0000, 28, 1, 3, '2023-06-05 23:21:06', '2023-06-05 23:21:06'),
(27, 87000.0000, 29, 1, 4, '2023-06-05 23:23:30', '2023-06-05 23:23:30'),
(28, 30000.0000, 30, 1, NULL, '2023-06-06 00:06:46', '2023-06-06 00:06:46'),
(29, 70000.0000, 31, 1, NULL, '2023-08-02 22:19:48', '2023-08-02 22:19:48');



INSERT INTO `products` (`id`, `name`, `description`, `barcode`, `price`, `purchase_price`, `expired_date`, `quantity`, `status`, `created_at`, `updated_at`, `uom`) VALUES
(12, 'Indomie Goreng Ayam Geprek', NULL, '089686598019', 14000.00, 12000.00, '2024-07-31 00:00:00', 25, 1, '2023-08-02 21:47:59', '2023-08-02 22:19:48', 'pcs');


INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('6MljSDCvadPPJGhz0rTBNb07Izl6tSSUkLm6v1Iz', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36 Config/90.2.7901.2', 'YTo5OntzOjY6Il90b2tlbiI7czo0MDoiTnBoam4zTUQxRDkyUUNrdjB5Vk1oZnI3cEF0N0pmc2xlQWI0QkhhMSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9yZXBvcnRzL2xhYmFrb3RvciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MzoidXJsIjthOjA6e31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6NDoiYXV0aCI7YToxOntzOjIxOiJwYXNzd29yZF9jb25maXJtZWRfYXQiO2k6MTY5MDk4NDUxNzt9czoxNToiZXhwaXJlZF9wcm9kdWN0IjtpOjE7czoyMDoiZXhwaXJlZF9wcm9kdWN0X2xpc3QiO086Mzk6IklsbHVtaW5hdGVcRGF0YWJhc2VcRWxvcXVlbnRcQ29sbGVjdGlvbiI6Mjp7czo4OiIAKgBpdGVtcyI7YToxOntpOjA7TzoxODoiQXBwXE1vZGVsc1xQcm9kdWN0IjozMDp7czoxMzoiACoAY29ubmVjdGlvbiI7czo1OiJteXNxbCI7czo4OiIAKgB0YWJsZSI7czo4OiJwcm9kdWN0cyI7czoxMzoiACoAcHJpbWFyeUtleSI7czoyOiJpZCI7czoxMDoiACoAa2V5VHlwZSI7czozOiJpbnQiO3M6MTI6ImluY3JlbWVudGluZyI7YjoxO3M6NzoiACoAd2l0aCI7YTowOnt9czoxMjoiACoAd2l0aENvdW50IjthOjA6e31zOjE5OiJwcmV2ZW50c0xhenlMb2FkaW5nIjtiOjA7czoxMDoiACoAcGVyUGFnZSI7aToxNTtzOjY6ImV4aXN0cyI7YjoxO3M6MTg6Indhc1JlY2VudGx5Q3JlYXRlZCI7YjowO3M6Mjg6IgAqAGVzY2FwZVdoZW5DYXN0aW5nVG9TdHJpbmciO2I6MDtzOjEzOiIAKgBhdHRyaWJ1dGVzIjthOjEyOntzOjI6ImlkIjtpOjExO3M6NDoibmFtZSI7czo0OiJUZXN0IjtzOjExOiJkZXNjcmlwdGlvbiI7czoxNToicHJwZHVjIGluaSB0ZXN0IjtzOjc6ImJhcmNvZGUiO3M6MTI6IjExMTIyMjIzMzM1NiI7czo1OiJwcmljZSI7czo4OiIyMDAwMC4wMCI7czoxNDoicHVyY2hhc2VfcHJpY2UiO3M6ODoiMTkwMDAuMDAiO3M6MTI6ImV4cGlyZWRfZGF0ZSI7czoxOToiMjAyMy0wNi0wNyAwMDowMDowMCI7czo4OiJxdWFudGl0eSI7aToyOTtzOjY6InN0YXR1cyI7aToxO3M6MTA6ImNyZWF0ZWRfYXQiO3M6MTk6IjIwMjMtMDYtMDMgMjE6NTQ6MDciO3M6MTA6InVwZGF0ZWRfYXQiO3M6MTk6IjIwMjMtMDYtMDQgMTk6MDg6NDUiO3M6MzoidW9tIjtzOjM6InBjcyI7fXM6MTE6IgAqAG9yaWdpbmFsIjthOjEyOntzOjI6ImlkIjtpOjExO3M6NDoibmFtZSI7czo0OiJUZXN0IjtzOjExOiJkZXNjcmlwdGlvbiI7czoxNToicHJwZHVjIGluaSB0ZXN0IjtzOjc6ImJhcmNvZGUiO3M6MTI6IjExMTIyMjIzMzM1NiI7czo1OiJwcmljZSI7czo4OiIyMDAwMC4wMCI7czoxNDoicHVyY2hhc2VfcHJpY2UiO3M6ODoiMTkwMDAuMDAiO3M6MTI6ImV4cGlyZWRfZGF0ZSI7czoxOToiMjAyMy0wNi0wNyAwMDowMDowMCI7czo4OiJxdWFudGl0eSI7aToyOTtzOjY6InN0YXR1cyI7aToxO3M6MTA6ImNyZWF0ZWRfYXQiO3M6MTk6IjIwMjMtMDYtMDMgMjE6NTQ6MDciO3M6MTA6InVwZGF0ZWRfYXQiO3M6MTk6IjIwMjMtMDYtMDQgMTk6MDg6NDUiO3M6MzoidW9tIjtzOjM6InBjcyI7fXM6MTA6IgAqAGNoYW5nZXMiO2E6MDp7fXM6ODoiACoAY2FzdHMiO2E6MDp7fXM6MTc6IgAqAGNsYXNzQ2FzdENhY2hlIjthOjA6e31zOjIxOiIAKgBhdHRyaWJ1dGVDYXN0Q2FjaGUiO2E6MDp7fXM6ODoiACoAZGF0ZXMiO2E6MDp7fXM6MTM6IgAqAGRhdGVGb3JtYXQiO047czoxMDoiACoAYXBwZW5kcyI7YTowOnt9czoxOToiACoAZGlzcGF0Y2hlc0V2ZW50cyI7YTowOnt9czoxNDoiACoAb2JzZXJ2YWJsZXMiO2E6MDp7fXM6MTI6IgAqAHJlbGF0aW9ucyI7YTowOnt9czoxMDoiACoAdG91Y2hlcyI7YTowOnt9czoxMDoidGltZXN0YW1wcyI7YjoxO3M6OToiACoAaGlkZGVuIjthOjA6e31zOjEwOiIAKgB2aXNpYmxlIjthOjA6e31zOjExOiIAKgBmaWxsYWJsZSI7YTo5OntpOjA7czo0OiJuYW1lIjtpOjE7czoxMToiZGVzY3JpcHRpb24iO2k6MjtzOjc6ImJhcmNvZGUiO2k6MztzOjU6InByaWNlIjtpOjQ7czoxNDoicHVyY2hhc2VfcHJpY2UiO2k6NTtzOjEyOiJleHBpcmVkX2RhdGUiO2k6NjtzOjg6InF1YW50aXR5IjtpOjc7czo2OiJzdGF0dXMiO2k6ODtzOjM6InVvbSI7fXM6MTA6IgAqAGd1YXJkZWQiO2E6MTp7aTowO3M6MToiKiI7fX19czoyODoiACoAZXNjYXBlV2hlbkNhc3RpbmdUb1N0cmluZyI7YjowO31zOjI3OiJleHBpcmVkX3Byb2R1Y3RfbGlzdF9sZW5naHQiO2k6MTt9', 1690990431);


INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'app_name', 'laravel', '2023-03-13 08:09:07', '2023-03-13 08:09:07');
INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(2, 'app_description', 'test', '2023-03-13 08:09:07', '2023-03-13 08:09:07');
INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(3, 'currency_symbol', 'Rp', '2023-03-13 08:09:07', '2023-03-13 08:09:07');
INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(4, 'warning_quantity', '5', '2023-03-13 08:09:07', '2023-06-04 19:07:48');

INSERT INTO `suppliers` (`id`, `supplier_name`, `address`, `phone`, `user_id`, `created_at`, `updated_at`) VALUES
(2, 'Aneka Jaya', 'SetiaBudi', '087834594813', 1, '2023-05-29 19:40:46', '2023-05-29 19:40:46');
INSERT INTO `suppliers` (`id`, `supplier_name`, `address`, `phone`, `user_id`, `created_at`, `updated_at`) VALUES
(3, 'Aneka Jaya1', 'SetiaBudi1', '1231233123', 1, '2023-05-29 19:41:05', '2023-05-29 19:41:05');
INSERT INTO `suppliers` (`id`, `supplier_name`, `address`, `phone`, `user_id`, `created_at`, `updated_at`) VALUES
(4, 'Aneka Jaya2', 'SetiaBudi2', '0856804', 1, '2023-05-29 19:41:23', '2023-05-29 19:41:23');
INSERT INTO `suppliers` (`id`, `supplier_name`, `address`, `phone`, `user_id`, `created_at`, `updated_at`) VALUES
(5, 'Aneka Jaya3', 'SetiaBudi3', '085680481231', 1, '2023-05-29 19:41:33', '2023-05-29 19:41:33');





INSERT INTO `users` (`id`, `username`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'kevin', '$2y$10$7fORTUvxGHzywO2tzN/5.e6pDbmNqpQpgQoGXYH8jyhuw2hUmPsIW', 'uucWr121oTd3npG7Q4V8LGYv9ltJjxT4UJ8B6hsUWQv86HxC6bZoyEswYwNP', '2023-03-12 15:34:31', '2023-03-12 15:34:31');



/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;