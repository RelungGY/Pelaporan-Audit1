
-- Dumping structure for table websitesewain.admins
CREATE TABLE `admins` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_email_unique` (`email`)
) ;

-- Dumping data for table websitesewain.admins: ~0 rows (approximately)
DELETE FROM `admins`;

-- Dumping structure for table websitesewain.failed_jobs
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ;

-- Dumping data for table websitesewain.failed_jobs: ~0 rows (approximately)
DELETE FROM `failed_jobs`;

-- Dumping structure for table websitesewain.feedback
CREATE TABLE `feedback` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `rating` int NOT NULL,
  `comments` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ;

-- Dumping data for table websitesewain.feedback: ~0 rows (approximately)
DELETE FROM `feedback`;

-- Dumping structure for table websitesewain.fines
CREATE TABLE `fines` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `payment_proof` text,
  `status` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ;

-- Dumping data for table websitesewain.fines: ~1 rows (approximately)
DELETE FROM `fines`;
INSERT INTO `fines` (`id`, `user_id`, `payment_proof`, `status`, `created_at`, `updated_at`) VALUES
	(1, 2, 'fines/nvm7HkONWjeIpmsMs3oX0JCIrJ2vcqzIRVkDh7qj.jpg', 'approved', '2025-01-14 09:27:48', '2025-01-14 09:59:00');

-- Dumping structure for table websitesewain.items
CREATE TABLE `items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `seller_id` bigint unsigned NOT NULL,
  `kategori_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `price_per_day` decimal(8,2) NOT NULL,
  `available` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `images` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `stok` int DEFAULT '0',
  PRIMARY KEY (`id`)
) ;

-- Dumping data for table websitesewain.items: ~9 rows (approximately)
DELETE FROM `items`;
INSERT INTO `items` (`id`, `seller_id`, `kategori_id`, `name`, `description`, `price_per_day`, `available`, `created_at`, `updated_at`, `images`, `stok`) VALUES
	(1, 1, 1, 'Agil Satria Ancang Pamungkas', '12 aaa', 45.00, 0, '2024-12-31 20:20:19', '2025-01-13 20:56:33', NULL, 0),
	(2, 2, 1, 'Testing', 'twewe', 12.00, 1, '2024-12-31 20:22:19', '2025-01-14 20:50:39', '["images\\/J76OQuxRr1ptACrnV4FZOsmJMPxmPJLI1awkO1Wv.jpg","images\\/ktYebN9SmO1D1J1d0EcQbS6CNljMHhK58GmAstRx.jpg"]', 5),
	(3, 1, 2, 'Agil', 'aaa', 12.00, 0, '2024-12-31 20:23:14', '2025-01-13 20:37:20', NULL, 0),
	(5, 1, 1, 'Agil 1212', 'aaa', 12.00, 0, '2025-01-03 03:33:21', '2025-01-13 01:59:15', NULL, 0),
	(6, 1, 1, 'Agil 12', 'aaa aaa', 12.00, 1, '2025-01-03 03:35:51', '2025-01-14 06:37:05', '["images\\/Rb9MvT6Kec93N4rXx6aBw4POiGV0MgeRxuGiHblZ.png","images\\/0JH2he2VggG1MzK39NCS5iZWOEW4DGfoOgAttCVz.png","images\\/7PDNdymjCD1y2EjAYyKkve1kwWVtEFkvWI65cdto.png","images\\/zfyho3NfNLBTaHEYno0GkDHvS1jOrGNxG8OicFSk.png"]', 5),
	(7, 2, 1, 'Stick PS', 'stik ps 4 5 6 7', 25000.00, 1, '2025-01-08 00:35:35', '2025-01-14 20:50:45', '["images\\/F7fqGyfVkFhbyBjKxHISgVMujHtORGkv9uyCgRYy.jpg"]', 5),
	(9, 2, 1, 'PS 4', 'SATU SET PS 4', 100000.00, 1, '2025-01-08 00:38:26', '2025-01-14 20:58:41', '["images\\/NiARiMyMKFlG3YCDrGDPSWAxmp9lKCxdBUV9QaLr.jpg","images\\/T6qocOZk55r5dVoqkXmwJcHVnPqnaWpFSq1pR0o0.jpg"]', 4),
	(10, 2, 1, 'Agil 121212 1212121', 'aaa', 1212.00, 0, '2025-01-12 07:12:58', '2025-01-14 20:49:46', '["images\\/EJysaIp2f7gyLa9LiTXuqGw9eg2Sji36Tt7ILiSU.png"]', 5),
	(11, 2, 1, 'Agil 121212 1212121', 'aaa', 1212.00, 0, '2025-01-12 07:13:25', '2025-01-12 20:19:10', '["images\\/RCLxQNCotrjKOnFJMgQNCSL690FieAfKx3nfFUAa.png"]', 0);

-- Dumping structure for table websitesewain.kategori
CREATE TABLE `kategori` (
  `id` int NOT NULL AUTO_INCREMENT,
  `namakategori` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ;

-- Dumping data for table websitesewain.kategori: ~1 rows (approximately)
DELETE FROM `kategori`;
INSERT INTO `kategori` (`id`, `namakategori`, `status`, `created_at`, `updated_at`) VALUES
	(1, 'Elektronik', 'disetujui', '2025-01-12 07:12:35', '2025-01-12 07:12:35');

-- Dumping structure for table websitesewain.midtrans_transactions
CREATE TABLE `midtrans_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fraud_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gross_amount` decimal(15,2) NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `midtrans_transactions_order_id_unique` (`order_id`),
  KEY `midtrans_transactions_user_id_foreign` (`user_id`),
  CONSTRAINT `midtrans_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ;

-- Dumping data for table websitesewain.midtrans_transactions: ~0 rows (approximately)
DELETE FROM `midtrans_transactions`;

-- Dumping structure for table websitesewain.migrations
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ;

-- Dumping data for table websitesewain.migrations: ~11 rows (approximately)
DELETE FROM `migrations`;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_000000_create_users_table', 1),
	(2, '2014_10_12_100000_create_password_resets_table', 1),
	(3, '2019_08_19_000000_create_failed_jobs_table', 1),
	(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(5, '2024_12_19_072618_create_admins_table', 1),
	(6, '2024_12_19_072646_create_feedback_table', 1),
	(7, '2024_12_19_072700_create_items_table', 1),
	(8, '2024_12_19_072715_create_services_table', 1),
	(9, '2024_12_19_072731_create_transactions_table', 1),
	(10, '2025_01_06_092347_create_midtrans_transactions_table', 2),
	(11, '2025_01_07_125100_create_notifications_table', 3);

-- Dumping structure for table websitesewain.notifications
CREATE TABLE `notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `sender_id` bigint unsigned DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_user_id_foreign` (`user_id`),
  KEY `notifications_sender_id_foreign` (`sender_id`),
  CONSTRAINT `notifications_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table websitesewain.notifications: ~2 rows (approximately)
DELETE FROM `notifications`;
INSERT INTO `notifications` (`id`, `user_id`, `sender_id`, `type`, `message`, `is_read`, `created_at`, `updated_at`) VALUES
	(2, 2, 1, 'alert', 'Segera kirimkan barang.', 1, '2025-01-07 07:49:11', '2025-01-07 08:38:51'),
	(3, 2, 2, 'alert', 'Peringatan untuk mengembalikan barang', 1, '2025-01-07 10:31:17', '2025-01-07 10:47:05'),
	(4, 4, 2, 'alert', 'Peringatan untuk mengembalikan barang.', 1, '2025-01-14 21:18:32', '2025-01-14 21:18:46');

-- Dumping structure for table websitesewain.password_resets
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ;

-- Dumping data for table websitesewain.password_resets: ~0 rows (approximately)
DELETE FROM `password_resets`;

-- Dumping structure for table websitesewain.personal_access_tokens
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ;

-- Dumping data for table websitesewain.personal_access_tokens: ~0 rows (approximately)
DELETE FROM `personal_access_tokens`;

-- Dumping structure for table websitesewain.services
CREATE TABLE `services` (
  `id` int NOT NULL AUTO_INCREMENT,
  `seller_id` int DEFAULT '0',
  `item_id` int DEFAULT '0',
  `nama_pelanggan` varchar(100) DEFAULT NULL,
  `nomor_telepon` varchar(20) DEFAULT NULL,
  `tanggal_pemesanan` date DEFAULT NULL,
  `tanggal_event` date DEFAULT NULL,
  `jenis_layanan` varchar(100) DEFAULT NULL,
  `total_harga` decimal(10,2) DEFAULT NULL,
  `status_pembayaran` varchar(100) DEFAULT NULL,
  `keterangan` text,
  `jumlah_pesanan` int DEFAULT NULL,
  `status_pengembalian` varchar(50) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table websitesewain.services: ~8 rows (approximately)
DELETE FROM `services`;
INSERT INTO `services` (`id`, `seller_id`, `item_id`, `nama_pelanggan`, `nomor_telepon`, `tanggal_pemesanan`, `tanggal_event`, `jenis_layanan`, `total_harga`, `status_pembayaran`, `keterangan`, `jumlah_pesanan`, `status_pengembalian`, `updated_at`, `created_at`) VALUES
	(1, 2, 11, 'aa', '089665881651', '2025-01-12', NULL, 'aaa', 1212.00, 'Belum Dibayar', 'aaa', 1, 'dipinjam', '2025-01-12 19:32:10', '2025-01-12 08:15:09'),
	(2, 2, 10, 'aa', '089665881651', '2025-01-13', NULL, 'aaa', 5.00, 'Belum Dibayar', 'aaa', 2, 'dipinjam', '2025-01-12 19:46:28', '2025-01-12 19:46:28'),
	(3, 2, 10, 'aa', '089665881651', '2025-01-13', NULL, 'aaa', 5.00, 'Belum Dibayar', 'aaa', 2, 'dipinjam', '2025-01-12 19:47:09', '2025-01-12 19:47:09'),
	(4, 2, 11, 'aa', '089665881651', '2025-01-13', NULL, 'aaa', 4.00, 'Belum Dibayar', 'aaa', 1, 'dipinjam', '2025-01-12 19:48:24', '2025-01-12 19:48:24'),
	(5, 2, 11, 'aa', '089665881651', '2025-01-13', NULL, 'aaa', 1.00, 'Belum Dibayar', 'aa', 1, 'dipinjam', '2025-01-12 19:54:04', '2025-01-12 19:54:04'),
	(6, 2, 11, 'aa', '089665881651', '2025-01-13', NULL, 'aaa', 1.97, 'Sudah Dibayar', 'aaa', 2, 'dipinjam', '2025-01-12 20:12:59', '2025-01-12 20:12:59'),
	(7, 2, 11, 'aa', '089665881651', '2025-01-13', NULL, 'aaa', 2.00, 'Belum Dibayar', 'mkmk', 1, 'dipinjam', '2025-01-12 20:15:18', '2025-01-12 20:15:18'),
	(8, 2, 11, 'aa', '089665881651', '2025-01-13', NULL, 'aaa', 1.00, 'Sudah Dibayar', 'hhh', 1, 'dipinjam', '2025-01-12 20:19:10', '2025-01-12 20:19:10');

-- Dumping structure for table websitesewain.transactions
CREATE TABLE `transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `item_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `deadline` date NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_confirm` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `subtotal` int DEFAULT NULL,
  `bukti_pembayaran` text COLLATE utf8mb4_unicode_ci,
  `tanggal_pembayaran` timestamp NULL DEFAULT NULL,
  `bukti_pengembalian` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table websitesewain.transactions: ~2 rows (approximately)
DELETE FROM `transactions`;
INSERT INTO `transactions` (`id`, `item_id`, `user_id`, `start_date`, `end_date`, `deadline`, `total_amount`, `status`, `status_confirm`, `created_at`, `updated_at`, `subtotal`, `bukti_pembayaran`, `tanggal_pembayaran`, `bukti_pengembalian`) VALUES
	(31, 1, 2, '2025-01-13', '2025-01-13', '2025-01-12', 45.00, 'dikembalikan', 'confirmed', '2025-01-13 02:33:55', '2025-01-14 20:34:04', NULL, 'bukti_pembayaran/VyRCI1I6BLrrLNnNVlNCecDJaC3aCiPQb3tN8mvs.png', '2025-01-13 02:34:46', 'bukti_pengembalian/f0jcoaGuLOFyEwqN2DsfIIIBrBs9EnPCO7YXnlDJ.jpg'),
	(42, 9, 4, '2025-01-15', '2025-01-16', '2025-01-16', 100000.00, 'Selesai Dikirim', 'confirmed', '2025-01-14 20:58:41', '2025-01-14 21:18:26', NULL, 'bukti_pembayaran/cH03ibuhWgQWVkXsY76CDL912uwqUG9zVDU8xWdJ.jpg', '2025-01-14 20:59:25', NULL);

-- Dumping structure for table websitesewain.users
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `isseller` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'no',
  `images` text COLLATE utf8mb4_unicode_ci,
  `alamat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_wajah` text COLLATE utf8mb4_unicode_ci,
  `foto_ktp` text COLLATE utf8mb4_unicode_ci,
  `foto_ttd` text COLLATE utf8mb4_unicode_ci,
  `status_verifikasi` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hukuman` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'sehat',
  `denda` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table websitesewain.users: ~4 rows (approximately)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `phone`, `user_type`, `remember_token`, `created_at`, `updated_at`, `isseller`, `images`, `alamat`, `foto_wajah`, `foto_ktp`, `foto_ttd`, `status_verifikasi`, `hukuman`, `denda`) VALUES
	(1, 'Agil Satria', 'gilsatria121@gmail.com', NULL, '$2y$10$HLdLKHsZFMJa5RS2EgSiaOLGE83vDcO5OI.bD3tTR2f1hAfRP7tPu', '089665881651', 'admin', NULL, '2025-01-03 00:25:15', '2025-01-05 18:00:02', 'yes', 'profile-images/b9JtF2Z7w2tbVc6DYyfy9JlbxAEcJjgmkoGL53aQ.png', 'Bojongsari', NULL, NULL, NULL, NULL, NULL, NULL),
	(2, 'Agil', 'gilsatria1211@gmail.com', NULL, '$2y$10$cb.cIqNaMWC0/wTV2AUGVulyZjw.nkXfIr4K14xer/jvlp5bvmROO', '089665881651', 'user', NULL, '2025-01-05 21:45:44', '2025-01-14 09:59:00', 'yes', NULL, 'Bojongsari', 'verifikasi/foto_wajah/IfmhMBkMbiTS9UwFT7Lo5bVNn1qpeTClCs0TNabm.png', 'verifikasi/foto_ktp/ZgeNJgYF7JXCyCO08CgENYYL0x89aLYLAmVNqj3r.png', 'verifikasi/foto_ttd/lNw3cNC57rgy2Y1xCgZVL1gunzqghYmN4KcpvffV.png', 'verified', 'clear', NULL),
	(3, 'Agil', 'datauser@gmail.com', NULL, '$2y$10$.gueTo1iDzcVUUowe9kJaeQ620c68T3yEpHipN7curuVJ7NUT9CNi', '089665881651', 'user', NULL, '2025-01-08 00:41:45', '2025-01-08 00:41:45', 'no', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(4, 'test terapis', 'fadelanas08072001@gmail.com', NULL, '$2y$10$KhhVfvs0wa6DD37gsqN6tuTBBXm2irmgoUh89k1xeVcgzLONELXWu', '1231123123123', 'user', NULL, '2025-01-14 20:50:15', '2025-01-14 20:52:47', 'no', NULL, NULL, 'verifikasi/foto_wajah/WPxMRzP6zMh1xYNU0Qm4hvwC8gxjWao1j3PjvPtg.jpg', 'verifikasi/foto_ktp/yQuSxtfCAbbs9WqOPUl9CLvbCFvSEysTt7yTCJBV.jpg', 'verifikasi/foto_ttd/QwsL1TgMjKazwOBWbCRzfpOzKB0X2c1ygL90p7B1.jpg', 'Verified', 'sehat', NULL);

-- Dumping structure for trigger websitesewain.items_after_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `items_after_insert` AFTER INSERT ON `items` FOR EACH ROW BEGIN
    IF NEW.stok = 0 THEN
        UPDATE items
        SET available = 0
        WHERE id = NEW.id;
    END IF;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
