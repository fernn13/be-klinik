-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 21, 2025 at 01:53 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `klinik`
--

-- --------------------------------------------------------

--
-- Table structure for table `expired_tokens`
--

CREATE TABLE `expired_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_personal_tokens` bigint(20) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expired_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expired_tokens`
--

INSERT INTO `expired_tokens` (`id`, `id_personal_tokens`, `token`, `expired_at`, `created_at`, `updated_at`) VALUES
(235, 235, '235|4UUIar7H1OthpQkyYyy2I8Diqni84VdiASGTF2reaa1ce80a', '2025-05-02 13:17:57', '2025-05-02 07:17:57', '2025-05-02 07:17:57'),
(236, 236, '236|hVnJwft9puOA9n6DtpyJg7kWqSfS1uQYlbWWSjCX71ef9898', '2025-05-14 10:47:49', '2025-05-14 04:47:49', '2025-05-14 04:47:49'),
(237, 237, '237|sYQneLcp4XMj1kFq4kdjqkQHalJmfuK0GMNDzBa9e2224b8d', '2025-05-15 08:37:38', '2025-05-15 02:37:38', '2025-05-15 02:37:38'),
(238, 238, '238|xoSdPvO8UxFvT3F8RRYZYlNnrZbnAgkhkJAQuT5r76c75f0f', '2025-05-28 10:04:42', '2025-05-28 04:04:42', '2025-05-28 04:04:42'),
(239, 239, '239|DkpKvB5xkxpCDvni4B3OOIA5ZRlZQMxheWcLBY2c6b98715d', '2025-05-28 10:08:08', '2025-05-28 04:08:08', '2025-05-28 04:08:08'),
(240, 240, '240|PaLFGPVQ3tIVp1FXdil9rzxfktO1zdEqDTpJysaCcee968b4', '2025-05-28 18:25:12', '2025-05-28 12:25:13', '2025-05-28 12:25:13'),
(241, 241, '241|E6CKDRXQWAzrNSpVCt2HVREcxBKiTrVJMjc8hSkS3f4aa04b', '2025-05-29 20:44:13', '2025-05-29 14:44:13', '2025-05-29 14:44:13'),
(242, 242, '242|X4o08DoSYtQa6vygeXV0iynf85ml05N4t0kPLWGg6349a723', '2025-05-30 18:43:04', '2025-05-30 12:43:04', '2025-05-30 12:43:04'),
(243, 243, '243|LLLty4EXA8WvXdkMJvHeExw1JvfzGJ7G4eSH2Mfoa1d8c269', '2025-05-30 18:48:10', '2025-05-30 12:48:10', '2025-05-30 12:48:10'),
(244, 244, '244|FajqOzmdUCcjf0xRDrSnsJAJJ33CjiJs2ACK2UgP3174cff0', '2025-05-30 18:48:21', '2025-05-30 12:48:21', '2025-05-30 12:48:21'),
(245, 245, '245|q7UbRWsBaz1PqLImXHer1r3PfWgCHyzVC4P8S0Ls543746a0', '2025-05-30 18:48:46', '2025-05-30 12:48:46', '2025-05-30 12:48:46'),
(246, 246, '246|TNbDbxutoRIezWktNxQ9YRn8cdF6fysjALf6b1W72841fa06', '2025-05-30 18:49:46', '2025-05-30 12:49:46', '2025-05-30 12:49:46'),
(247, 247, '247|v3tWEE43IKFYQ6sdoVrp1JcdCkPhi1yRSspmEP5H305f27a5', '2025-05-30 18:55:41', '2025-05-30 12:55:41', '2025-05-30 12:55:41'),
(248, 248, '248|LjuybQriUeq0fszpDeJPtoDyEuhYFvfb8uuIUo3g2d01a200', '2025-05-31 10:05:36', '2025-05-31 04:05:36', '2025-05-31 04:05:36');

-- --------------------------------------------------------

--
-- Table structure for table `pasien_pendaftaran`
--

CREATE TABLE `pasien_pendaftaran` (
  `id` int(11) NOT NULL,
  `no_rm` varchar(255) DEFAULT NULL,
  `nama` varchar(255) NOT NULL,
  `tempat_lahir` varchar(255) NOT NULL,
  `tgl_lahir` datetime NOT NULL,
  `jns_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `no_tlp` varchar(255) NOT NULL,
  `pendidikan` varchar(255) NOT NULL,
  `pekerjaan` varchar(255) NOT NULL,
  `no_ktp` varchar(255) NOT NULL,
  `no_asuransi` varchar(255) NOT NULL,
  `jns_asuransi` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pasien_pendaftaran`
--

INSERT INTO `pasien_pendaftaran` (`id`, `no_rm`, `nama`, `tempat_lahir`, `tgl_lahir`, `jns_kelamin`, `alamat`, `no_tlp`, `pendidikan`, `pekerjaan`, `no_ktp`, `no_asuransi`, `jns_asuransi`, `created_at`, `updated_at`) VALUES
(12, 'RM0001', 'awfawfawf', 'awfawfwf', '2025-06-21 00:00:00', 'Laki-laki', 'adwawdawd', '08626262622', 'awfawfawf', 'awfawfawfaw', '1212121212121212', '12124242412414141', 'BPJS', '2025-06-21 11:22:44', '2025-06-21 11:22:44');

-- --------------------------------------------------------

--
-- Table structure for table `pasien_reservasi`
--

CREATE TABLE `pasien_reservasi` (
  `id` int(11) NOT NULL,
  `no_rm` varchar(255) NOT NULL,
  `tgl_reservasi` datetime NOT NULL,
  `kode_kunjungan` varchar(255) NOT NULL,
  `ruangan` varchar(255) NOT NULL,
  `keluhan` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(235, 'App\\Models\\User', '125d870b-2723-11f0-8e07-f1a04d9f3735', 'Amindita', '45ce4cff35c1bafa22197adb40706df595ad715705d8f14c6e1d08634aa297a6', '[\"*\"]', '2025-05-02 07:25:54', NULL, '2025-05-02 07:17:57', '2025-05-02 07:25:54'),
(236, 'App\\Models\\User', '125d870b-2723-11f0-8e07-f1a04d9f3735', 'Amindita', '1ab174dfd5cfc44ecd04a01c66513de89b66851e040c7a8db7a0687335798542', '[\"*\"]', '2025-05-14 07:54:22', NULL, '2025-05-14 04:47:49', '2025-05-14 07:54:22'),
(237, 'App\\Models\\User', '125d870b-2723-11f0-8e07-f1a04d9f3735', 'Amindita', 'bc6bd85b890f96500cb0347c7539c46c044123b44694a70333af92e69754fd11', '[\"*\"]', '2025-05-15 07:05:20', NULL, '2025-05-15 02:37:38', '2025-05-15 07:05:20'),
(238, 'App\\Models\\User', '125d870b-2723-11f0-8e07-f1a04d9f3735', 'Amindita', '3dd61c50633ca7393fae922ccbf81cb8e32fe00697168be0c1deb48926e3903b', '[\"*\"]', '2025-05-28 12:23:52', '2025-05-28 10:04:42', '2025-05-28 04:04:42', '2025-05-28 12:23:52'),
(239, 'App\\Models\\User', '125d870b-2723-11f0-8e07-f1a04d9f3735', 'Amindita', '737dc1531136f7641b8fb748b43b73c34695ce670a251f66e9a6338f7e625197', '[\"*\"]', '2025-05-28 12:23:48', '2025-05-28 10:08:08', '2025-05-28 04:08:08', '2025-05-28 12:23:48'),
(240, 'App\\Models\\User', '125d870b-2723-11f0-8e07-f1a04d9f3735', 'Amindita', 'a5d275fc248e488ce9cfe885f9f31daafc169b5547b71a0dc6e2bc65b88831ca', '[\"*\"]', '2025-05-29 14:44:05', '2025-05-28 18:25:12', '2025-05-28 12:25:12', '2025-05-29 14:44:05'),
(241, 'App\\Models\\User', '125d870b-2723-11f0-8e07-f1a04d9f3735', 'Amindita', 'e8d28170fbcd0ef91283e076f7e4b5bc4d86d0d30d893bd8e6c2d3faa760f37f', '[\"*\"]', '2025-05-29 16:04:12', NULL, '2025-05-29 14:44:13', '2025-05-29 16:04:12'),
(242, 'App\\Models\\User', '125d870b-2723-11f0-8e07-f1a04d9f3735', 'Amindita', '04fb1dc96781d794940db0bc2a0b4baf185296e4e92eda754c5032cbf6868ee8', '[\"*\"]', NULL, NULL, '2025-05-30 12:43:04', '2025-05-30 12:43:04'),
(243, 'App\\Models\\User', '125d870b-2723-11f0-8e07-f1a04d9f3735', 'Amindita', 'c79d1006a12e8e3fb496a26df8bf91ed47ba7665be01b08b6ecb897f4e3ef87d', '[\"*\"]', NULL, NULL, '2025-05-30 12:48:10', '2025-05-30 12:48:10'),
(244, 'App\\Models\\User', '125d870b-2723-11f0-8e07-f1a04d9f3735', 'Amindita', '26dce862306eecd6349169ddd642e5f09a60f7a5fb10e32bfe2a104d8fb2eb17', '[\"*\"]', NULL, NULL, '2025-05-30 12:48:21', '2025-05-30 12:48:21'),
(245, 'App\\Models\\User', '125d870b-2723-11f0-8e07-f1a04d9f3735', 'Amindita', '2792511544154c03d7df7173043091d56603c1e4bf341b27f13ec7e92abde51f', '[\"*\"]', NULL, NULL, '2025-05-30 12:48:46', '2025-05-30 12:48:46'),
(246, 'App\\Models\\User', '125d870b-2723-11f0-8e07-f1a04d9f3735', 'Amindita', '14e59be29e23a2028c2a21fe0d4d219645ec0b9c2883b70f957df8087061c2c2', '[\"*\"]', NULL, NULL, '2025-05-30 12:49:46', '2025-05-30 12:49:46'),
(247, 'App\\Models\\User', '125d870b-2723-11f0-8e07-f1a04d9f3735', 'Amindita', '23c3917b0e7a2fa5ea281e0b699bc7fd439bf5dbbb4787747db4643a6e01634f', '[\"*\"]', NULL, NULL, '2025-05-30 12:55:41', '2025-05-30 12:55:41'),
(248, 'App\\Models\\User', '125d870b-2723-11f0-8e07-f1a04d9f3735', 'Amindita', 'a28d306747778a322746497e2b819d0235f2281d58cf0107d9afdfffe25e7f29', '[\"*\"]', NULL, NULL, '2025-05-31 04:05:36', '2025-05-31 04:05:36');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `role` varchar(10) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `status` varchar(50) DEFAULT 'nonactive',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `no_hp`, `role`, `password`, `status`, `remember_token`, `created_at`, `updated_at`) VALUES
('125d870b-2723-11f0-8e07-f1a04d9f3735', 'Amindita', 'amindtmstr@admin.com', NULL, '087700288071', 'superadmin', '$2a$12$2wN3/NqLjAXOPO9fna3mNOO3vHLbtMrGQYKevYkc9edynGYFJ.Xbq', 'active', NULL, NULL, NULL),
('d54e18a3-e577-fb6c-38b5-2947462ca51d', 'Super Admin', 'superadmin@admin.com', '2025-01-13 08:50:58', '085700000000', 'superadmin', '$2y$10$4A72xKICjnw5VW6xLuG60OUHU.rnwylxPF4hO38oWvdFcg4wCTehq', 'active', '-', '2025-01-13 08:53:33', '2025-02-04 13:54:28');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `expired_tokens`
--
ALTER TABLE `expired_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expired_tokens_id_personal_tokens_index` (`id_personal_tokens`);

--
-- Indexes for table `pasien_pendaftaran`
--
ALTER TABLE `pasien_pendaftaran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pasien_reservasi`
--
ALTER TABLE `pasien_reservasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `expired_tokens`
--
ALTER TABLE `expired_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=249;

--
-- AUTO_INCREMENT for table `pasien_pendaftaran`
--
ALTER TABLE `pasien_pendaftaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `pasien_reservasi`
--
ALTER TABLE `pasien_reservasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=249;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
