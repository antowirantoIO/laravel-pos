-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 04 Jun 2023 pada 13.59
-- Versi server: 10.4.28-MariaDB
-- Versi PHP: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `skripsi2`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `email` varchar(191) DEFAULT NULL,
  `phone` varchar(191) DEFAULT NULL,
  `address` varchar(191) DEFAULT NULL,
  `avatar` varchar(191) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(180, '2014_10_12_000000_create_users_table', 1),
(181, '2014_10_12_100000_create_password_resets_table', 1),
(182, '2019_08_19_000000_create_failed_jobs_table', 1),
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
(197, '2023_06_04_182022_add_supplier_id_to_payments_table', 4);

-- --------------------------------------------------------

--
-- Struktur dari tabel `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `supplier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `supplier_id`, `created_at`, `updated_at`) VALUES
(8, 1, NULL, '2023-05-29 12:42:09', '2023-05-29 12:42:09'),
(9, 1, NULL, '2023-06-03 14:28:31', '2023-06-03 14:28:31'),
(10, 1, NULL, '2023-06-04 09:13:14', '2023-06-04 09:13:14'),
(11, 1, NULL, '2023-06-04 09:37:30', '2023-06-04 09:37:30'),
(12, 1, NULL, '2023-06-04 09:37:59', '2023-06-04 09:37:59'),
(13, 1, NULL, '2023-06-04 09:38:37', '2023-06-04 09:38:37'),
(14, 1, NULL, '2023-06-04 09:40:13', '2023-06-04 09:40:13'),
(15, 1, NULL, '2023-06-04 09:40:32', '2023-06-04 09:40:32'),
(16, 1, NULL, '2023-06-04 09:40:39', '2023-06-04 09:40:39'),
(17, 1, NULL, '2023-06-04 11:25:19', '2023-06-04 11:25:19'),
(19, 1, 3, '2023-06-04 11:29:02', '2023-06-04 11:29:02'),
(20, 1, 3, '2023-06-04 11:29:22', '2023-06-04 11:29:22'),
(21, 1, 4, '2023-06-04 11:41:24', '2023-06-04 11:41:24'),
(22, 1, 3, '2023-06-04 11:45:27', '2023-06-04 11:45:27'),
(23, 1, 4, '2023-06-04 11:51:48', '2023-06-04 11:51:48');

-- --------------------------------------------------------

--
-- Struktur dari tabel `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `price` decimal(14,4) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `order_items`
--

INSERT INTO `order_items` (`id`, `price`, `quantity`, `order_id`, `product_id`, `created_at`, `updated_at`) VALUES
(11, 25000.0000, 5, 8, 3, '2023-05-29 12:42:09', '2023-05-29 12:42:09'),
(12, 50000.0000, 5, 8, 4, '2023-05-29 12:42:09', '2023-05-29 12:42:09'),
(13, 40000.0000, 1, 9, 10, '2023-06-03 14:28:31', '2023-06-03 14:28:31'),
(14, 10000.0000, 1, 9, 4, '2023-06-03 14:28:31', '2023-06-03 14:28:31'),
(15, 5000.0000, 1, 9, 3, '2023-06-03 14:28:31', '2023-06-03 14:28:31'),
(16, 30000.0000, 1, 9, 8, '2023-06-03 14:28:31', '2023-06-03 14:28:31'),
(17, 25000.0000, 1, 10, 7, '2023-06-04 09:13:14', '2023-06-04 09:13:14'),
(18, 60000.0000, 3, 11, 11, '2023-06-04 09:37:31', '2023-06-04 09:37:31'),
(19, 120000.0000, 3, 12, 10, '2023-06-04 09:37:59', '2023-06-04 09:37:59'),
(20, 100000.0000, 5, 13, 11, '2023-06-04 09:38:37', '2023-06-04 09:38:37'),
(21, 20000.0000, 1, 14, 11, '2023-06-04 09:40:13', '2023-06-04 09:40:13'),
(22, 20000.0000, 1, 15, 11, '2023-06-04 09:40:32', '2023-06-04 09:40:32'),
(23, 20000.0000, 1, 16, 11, '2023-06-04 09:40:39', '2023-06-04 09:40:39'),
(24, 20000.0000, 1, 17, 11, '2023-06-04 11:25:19', '2023-06-04 11:25:19'),
(25, 40000.0000, 1, 17, 10, '2023-06-04 11:25:19', '2023-06-04 11:25:19'),
(26, 30000.0000, 1, 17, 8, '2023-06-04 11:25:19', '2023-06-04 11:25:19'),
(27, 30000.0000, 1, 20, 8, '2023-06-04 11:29:22', '2023-06-04 11:29:22'),
(28, 150000.0000, 5, 21, 8, '2023-06-04 11:41:24', '2023-06-04 11:41:24'),
(29, 150000.0000, 5, 22, 8, '2023-06-04 11:45:27', '2023-06-04 11:45:27'),
(30, 40000.0000, 2, 23, 11, '2023-06-04 11:51:48', '2023-06-04 11:51:48');

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(14,4) NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `supplier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `payments`
--

INSERT INTO `payments` (`id`, `amount`, `order_id`, `user_id`, `supplier_id`, `created_at`, `updated_at`) VALUES
(8, 75000.0000, 8, 1, NULL, '2023-05-29 12:42:09', '2023-05-29 12:42:09'),
(9, 85000.0000, 9, 1, NULL, '2023-06-03 14:28:31', '2023-06-03 14:28:31'),
(10, 25000.0000, 10, 1, NULL, '2023-06-04 09:13:14', '2023-06-04 09:13:14'),
(11, 60000.0000, 11, 1, NULL, '2023-06-04 09:37:31', '2023-06-04 09:37:31'),
(12, 120000.0000, 12, 1, NULL, '2023-06-04 09:37:59', '2023-06-04 09:37:59'),
(13, 100000.0000, 13, 1, NULL, '2023-06-04 09:38:37', '2023-06-04 09:38:37'),
(14, 20000.0000, 14, 1, NULL, '2023-06-04 09:40:13', '2023-06-04 09:40:13'),
(15, 20000.0000, 15, 1, NULL, '2023-06-04 09:40:32', '2023-06-04 09:40:32'),
(16, 20000.0000, 16, 1, NULL, '2023-06-04 09:40:39', '2023-06-04 09:40:39'),
(17, 90000.0000, 17, 1, NULL, '2023-06-04 11:25:19', '2023-06-04 11:25:19'),
(18, 30000.0000, 20, 1, 3, '2023-06-04 11:29:22', '2023-06-04 11:29:22'),
(19, 150000.0000, 21, 1, 4, '2023-06-04 11:41:24', '2023-06-04 11:41:24'),
(20, 150000.0000, 22, 1, 3, '2023-06-04 11:45:27', '2023-06-04 11:45:27'),
(21, 40000.0000, 23, 1, 4, '2023-06-04 11:51:48', '2023-06-04 11:51:48');

-- --------------------------------------------------------

--
-- Struktur dari tabel `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(191) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `description` text DEFAULT NULL,
  `barcode` varchar(191) NOT NULL,
  `price` decimal(14,2) NOT NULL,
  `expired_date` datetime DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `barcode`, `price`, `expired_date`, `quantity`, `status`, `created_at`, `updated_at`) VALUES
(3, 'Aqua', NULL, '089686598018', 5000.00, NULL, 94, 1, '2023-05-29 12:38:55', '2023-06-03 14:28:31'),
(4, 'Aqua1', NULL, '089686598019', 10000.00, NULL, 94, 1, '2023-05-29 12:39:28', '2023-06-03 14:28:31'),
(5, 'Aqua2', NULL, '089686598020', 15000.00, NULL, 100, 1, '2023-05-29 12:39:39', '2023-05-29 12:39:39'),
(6, 'Aqua3', NULL, '089686598021', 20000.00, NULL, 100, 1, '2023-05-29 12:39:51', '2023-05-29 12:39:51'),
(7, 'Aqua5', NULL, '089686598022', 25000.00, NULL, 99, 1, '2023-05-29 12:40:03', '2023-06-04 09:13:14'),
(8, 'Aqua6', NULL, '089686598023', 30000.00, NULL, 8, 1, '2023-05-29 12:40:13', '2023-06-04 11:45:27'),
(9, 'Aqua7', NULL, '089686598024', 35000.00, NULL, 100, 1, '2023-05-29 12:40:22', '2023-05-29 12:40:22'),
(10, 'Aqua8', NULL, '089686598025', 40000.00, NULL, 95, 1, '2023-05-29 12:40:32', '2023-06-04 11:25:19'),
(11, 'Test', 'prpduc ini test', '111222233356', 20000.00, '2023-06-07 00:00:00', 30, 1, '2023-06-03 14:54:07', '2023-06-04 11:51:48');

-- --------------------------------------------------------

--
-- Struktur dari tabel `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(191) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'app_name', 'laravel', '2023-03-13 01:09:07', '2023-03-13 01:09:07'),
(2, 'app_description', 'test', '2023-03-13 01:09:07', '2023-03-13 01:09:07'),
(3, 'currency_symbol', 'Rp', '2023-03-13 01:09:07', '2023-03-13 01:09:07'),
(4, 'warning_quantity', NULL, '2023-03-13 01:09:07', '2023-03-13 01:09:07');

-- --------------------------------------------------------

--
-- Struktur dari tabel `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `supplier_name` varchar(50) NOT NULL,
  `address` varchar(191) DEFAULT NULL,
  `phone` varchar(191) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `suppliers`
--

INSERT INTO `suppliers` (`id`, `supplier_name`, `address`, `phone`, `user_id`, `created_at`, `updated_at`) VALUES
(2, 'Aneka Jaya', 'SetiaBudi', '087834594813', 1, '2023-05-29 12:40:46', '2023-05-29 12:40:46'),
(3, 'Aneka Jaya1', 'SetiaBudi1', '1231233123', 1, '2023-05-29 12:41:05', '2023-05-29 12:41:05'),
(4, 'Aneka Jaya2', 'SetiaBudi2', '0856804', 1, '2023-05-29 12:41:23', '2023-05-29 12:41:23'),
(5, 'Aneka Jaya3', 'SetiaBudi3', '085680481231', 1, '2023-05-29 12:41:33', '2023-05-29 12:41:33');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(191) NOT NULL,
  `password` varchar(191) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'kevin', '$2y$10$7fORTUvxGHzywO2tzN/5.e6pDbmNqpQpgQoGXYH8jyhuw2hUmPsIW', 'uucWr121oTd3npG7Q4V8LGYv9ltJjxT4UJ8B6hsUWQv86HxC6bZoyEswYwNP', '2023-03-12 08:34:31', '2023-03-12 08:34:31');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_cart`
--

CREATE TABLE `user_cart` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_purchase`
--

CREATE TABLE `user_purchase` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customers_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_supplier_id_foreign` (`supplier_id`);

--
-- Indeks untuk tabel `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_product_id_foreign` (`product_id`);

--
-- Indeks untuk tabel `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indeks untuk tabel `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_order_id_foreign` (`order_id`),
  ADD KEY `payments_user_id_foreign` (`user_id`),
  ADD KEY `payments_supplier_id_foreign` (`supplier_id`);

--
-- Indeks untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_barcode_unique` (`barcode`);

--
-- Indeks untuk tabel `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indeks untuk tabel `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `suppliers_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- Indeks untuk tabel `user_cart`
--
ALTER TABLE `user_cart`
  ADD KEY `user_cart_user_id_foreign` (`user_id`),
  ADD KEY `user_cart_product_id_foreign` (`product_id`);

--
-- Indeks untuk tabel `user_purchase`
--
ALTER TABLE `user_purchase`
  ADD KEY `user_purchase_user_id_foreign` (`user_id`),
  ADD KEY `user_purchase_product_id_foreign` (`product_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=198;

--
-- AUTO_INCREMENT untuk tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT untuk tabel `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT untuk tabel `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `customers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `suppliers`
--
ALTER TABLE `suppliers`
  ADD CONSTRAINT `suppliers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `user_cart`
--
ALTER TABLE `user_cart`
  ADD CONSTRAINT `user_cart_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_cart_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `user_purchase`
--
ALTER TABLE `user_purchase`
  ADD CONSTRAINT `user_purchase_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_purchase_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
