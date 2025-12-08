-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 08, 2025 at 02:21 AM
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
-- Database: `db_panenku`
--

-- --------------------------------------------------------

--
-- Table structure for table `hasil_panen`
--

CREATE TABLE `hasil_panen` (
  `panen_id` int(11) NOT NULL,
  `siklus_id` int(11) DEFAULT NULL,
  `tanggal_realisasi` date DEFAULT NULL,
  `jumlah_ton` float DEFAULT NULL,
  `kualitas` enum('Grade A','Grade B','Grade C') DEFAULT NULL,
  `status_validasi` enum('Pending','Valid','Reject') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lahan`
--

CREATE TABLE `lahan` (
  `lahan_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `luas_lahan` float DEFAULT NULL,
  `lokasi_desa` varchar(100) DEFAULT NULL,
  `kategori_lahan` enum('Sawah Irigasi','Tadah Hujan','Ladang','Perkebunan') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lahan`
--

INSERT INTO `lahan` (`lahan_id`, `user_id`, `luas_lahan`, `lokasi_desa`, `kategori_lahan`) VALUES
(1, 6, 10, 'Desa suka makmur', 'Sawah Irigasi'),
(2, 8, 5, 'Desa kemasan', 'Sawah Irigasi');

-- --------------------------------------------------------

--
-- Table structure for table `laporan_hama`
--

CREATE TABLE `laporan_hama` (
  `laporan_id` int(11) NOT NULL,
  `siklus_id` int(11) DEFAULT NULL,
  `hama_id` int(11) DEFAULT NULL,
  `tingkat_keparahan` enum('Ringan','Sedang','Berat/Puso') DEFAULT NULL,
  `foto_bukti` varchar(255) DEFAULT NULL,
  `tanggal_lapor` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `laporan_hama`
--

INSERT INTO `laporan_hama` (`laporan_id`, `siklus_id`, `hama_id`, `tingkat_keparahan`, `foto_bukti`, `tanggal_lapor`) VALUES
(1, 2, 1, 'Ringan', NULL, '2025-12-07 18:35:38'),
(2, 2, 1, 'Ringan', 'hama_1765155515.jpeg', '2025-12-07 18:58:35');

-- --------------------------------------------------------

--
-- Table structure for table `ref_jenis_hama`
--

CREATE TABLE `ref_jenis_hama` (
  `hama_id` int(11) NOT NULL,
  `nama_hama` varchar(100) DEFAULT NULL,
  `deskripsi_penanganan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ref_jenis_hama`
--

INSERT INTO `ref_jenis_hama` (`hama_id`, `nama_hama`, `deskripsi_penanganan`) VALUES
(1, 'Wereng Coklat', 'Semprot insektisida berbahan aktif imidakloprid atau buprofezin.'),
(2, 'Tikus Sawah', 'Lakukan gropyokan, pasang racun tikus, atau gunakan burung hantu.'),
(3, 'Ulat Grayak', 'Gunakan insektisida biologi Bacillus thuringiensis atau rotasi tanaman.'),
(4, 'Walang Sangit', 'Kendalikan gulma dan gunakan perangkap bau busuk.'),
(5, 'Keong Mas', 'Ambil secara manual atau gunakan moluskisida.');

-- --------------------------------------------------------

--
-- Table structure for table `ref_komoditas`
--

CREATE TABLE `ref_komoditas` (
  `komoditas_id` int(11) NOT NULL,
  `nama_komoditas` varchar(100) DEFAULT NULL,
  `kategori` enum('Pangan','Hortikultura','Perkebunan') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ref_komoditas`
--

INSERT INTO `ref_komoditas` (`komoditas_id`, `nama_komoditas`, `kategori`) VALUES
(1, 'Padi Ciherang', 'Pangan'),
(2, 'Jagung Hibrida', 'Pangan'),
(3, 'Kedelai', 'Pangan'),
(4, 'Cabai Rawit', 'Hortikultura'),
(5, 'Bawang Merah', 'Hortikultura');

-- --------------------------------------------------------

--
-- Table structure for table `riwayat_pupuk`
--

CREATE TABLE `riwayat_pupuk` (
  `pupuk_id` int(11) NOT NULL,
  `siklus_id` int(11) NOT NULL,
  `jenis_pupuk` varchar(100) NOT NULL,
  `jumlah_sebar` float NOT NULL,
  `satuan` enum('kg','liter','karung') DEFAULT 'kg',
  `tanggal_sebar` date NOT NULL,
  `catatan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `riwayat_pupuk`
--

INSERT INTO `riwayat_pupuk` (`pupuk_id`, `siklus_id`, `jenis_pupuk`, `jumlah_sebar`, `satuan`, `tanggal_sebar`, `catatan`) VALUES
(1, 2, 'Urea', 50, 'kg', '2025-12-08', 'Untuk 1 karung pupuk urea');

-- --------------------------------------------------------

--
-- Table structure for table `siklus_tanam`
--

CREATE TABLE `siklus_tanam` (
  `siklus_id` int(11) NOT NULL,
  `lahan_id` int(11) DEFAULT NULL,
  `komoditas_id` int(11) DEFAULT NULL,
  `tanggal_tanam` date DEFAULT NULL,
  `estimasi_panen` date DEFAULT NULL,
  `status_aktif` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `siklus_tanam`
--

INSERT INTO `siklus_tanam` (`siklus_id`, `lahan_id`, `komoditas_id`, `tanggal_tanam`, `estimasi_panen`, `status_aktif`) VALUES
(2, 2, 1, '2025-12-08', '2026-03-08', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(150) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `nama_pertanian` varchar(100) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `role` enum('petani','dinas','admin') DEFAULT 'petani',
  `is_active` tinyint(1) DEFAULT 1,
  `avatar` varchar(255) DEFAULT 'default.jpg',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email`, `phone_number`, `password`, `full_name`, `address`, `nama_pertanian`, `bio`, `role`, `is_active`, `avatar`, `created_at`) VALUES
(6, 'bagas04@gmail.com', '81231951241', '$2y$10$jqsmcWkBmio.vQL1mu0xEOoyCjTmLlA5Jt03W2mmyoJy88UriPCJG', 'bagas', NULL, NULL, NULL, 'petani', 1, 'default.jpg', '2025-12-06 18:37:26'),
(7, 'admin@dinas.go.id', '0811-1234-5678', '$2y$10$8hMvv/O0KQTx997CsIlivOxP8VxDb4m8Lsv9FZTj5Fr/pgNjx4RFC', 'Super Admin Dinas', NULL, NULL, NULL, 'dinas', 1, 'default.jpg', '2025-12-07 00:40:34'),
(8, 'akmal06@gmail.com', '12345676543', '$2y$10$13NPcrw/of6ibAmEIo2MEueq36GvrRvuZVvP9Wel5qH7gb.7q5ZQi', 'Akmal briandhito', 'jln.Malaya raya ', 'Pertanian Mal', 'aku seorang petani', 'petani', 1, 'default.jpg', '2025-12-07 17:02:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hasil_panen`
--
ALTER TABLE `hasil_panen`
  ADD PRIMARY KEY (`panen_id`),
  ADD KEY `siklus_id` (`siklus_id`);

--
-- Indexes for table `lahan`
--
ALTER TABLE `lahan`
  ADD PRIMARY KEY (`lahan_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `laporan_hama`
--
ALTER TABLE `laporan_hama`
  ADD PRIMARY KEY (`laporan_id`),
  ADD KEY `siklus_id` (`siklus_id`),
  ADD KEY `hama_id` (`hama_id`);

--
-- Indexes for table `ref_jenis_hama`
--
ALTER TABLE `ref_jenis_hama`
  ADD PRIMARY KEY (`hama_id`);

--
-- Indexes for table `ref_komoditas`
--
ALTER TABLE `ref_komoditas`
  ADD PRIMARY KEY (`komoditas_id`);

--
-- Indexes for table `riwayat_pupuk`
--
ALTER TABLE `riwayat_pupuk`
  ADD PRIMARY KEY (`pupuk_id`),
  ADD KEY `siklus_id` (`siklus_id`);

--
-- Indexes for table `siklus_tanam`
--
ALTER TABLE `siklus_tanam`
  ADD PRIMARY KEY (`siklus_id`),
  ADD KEY `lahan_id` (`lahan_id`),
  ADD KEY `komoditas_id` (`komoditas_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `unique_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hasil_panen`
--
ALTER TABLE `hasil_panen`
  MODIFY `panen_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lahan`
--
ALTER TABLE `lahan`
  MODIFY `lahan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `laporan_hama`
--
ALTER TABLE `laporan_hama`
  MODIFY `laporan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ref_jenis_hama`
--
ALTER TABLE `ref_jenis_hama`
  MODIFY `hama_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `ref_komoditas`
--
ALTER TABLE `ref_komoditas`
  MODIFY `komoditas_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `riwayat_pupuk`
--
ALTER TABLE `riwayat_pupuk`
  MODIFY `pupuk_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `siklus_tanam`
--
ALTER TABLE `siklus_tanam`
  MODIFY `siklus_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `hasil_panen`
--
ALTER TABLE `hasil_panen`
  ADD CONSTRAINT `hasil_panen_ibfk_1` FOREIGN KEY (`siklus_id`) REFERENCES `siklus_tanam` (`siklus_id`) ON DELETE CASCADE;

--
-- Constraints for table `lahan`
--
ALTER TABLE `lahan`
  ADD CONSTRAINT `lahan_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `laporan_hama`
--
ALTER TABLE `laporan_hama`
  ADD CONSTRAINT `laporan_hama_ibfk_1` FOREIGN KEY (`siklus_id`) REFERENCES `siklus_tanam` (`siklus_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `laporan_hama_ibfk_2` FOREIGN KEY (`hama_id`) REFERENCES `ref_jenis_hama` (`hama_id`);

--
-- Constraints for table `riwayat_pupuk`
--
ALTER TABLE `riwayat_pupuk`
  ADD CONSTRAINT `fk_pupuk_siklus` FOREIGN KEY (`siklus_id`) REFERENCES `siklus_tanam` (`siklus_id`) ON DELETE CASCADE;

--
-- Constraints for table `siklus_tanam`
--
ALTER TABLE `siklus_tanam`
  ADD CONSTRAINT `siklus_tanam_ibfk_1` FOREIGN KEY (`lahan_id`) REFERENCES `lahan` (`lahan_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `siklus_tanam_ibfk_2` FOREIGN KEY (`komoditas_id`) REFERENCES `ref_komoditas` (`komoditas_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
