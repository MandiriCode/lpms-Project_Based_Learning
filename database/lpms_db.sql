-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 07, 2026 at 08:06 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lpms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `hasil_tes`
--

CREATE TABLE `hasil_tes` (
  `id` int NOT NULL,
  `siswa_id` int NOT NULL,
  `jenis` enum('pretest','posttest') COLLATE utf8mb4_unicode_ci NOT NULL,
  `fluency` decimal(5,2) NOT NULL DEFAULT '0.00',
  `flexibility` decimal(5,2) NOT NULL DEFAULT '0.00',
  `originality` decimal(5,2) NOT NULL DEFAULT '0.00',
  `elaboration` decimal(5,2) NOT NULL DEFAULT '0.00',
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `guru_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hasil_tes`
--

INSERT INTO `hasil_tes` (`id`, `siswa_id`, `jenis`, `fluency`, `flexibility`, `originality`, `elaboration`, `catatan`, `guru_id`, `created_at`) VALUES
(1, 3, 'pretest', '60.00', '55.00', '65.00', '50.00', '', 2, '2026-11-07 15:33:11');

-- --------------------------------------------------------

--
-- Table structure for table `monitoring`
--

CREATE TABLE `monitoring` (
  `id` int NOT NULL,
  `unggah_id` int NOT NULL,
  `guru_id` int NOT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `progres` tinyint UNSIGNED DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `monitoring`
--

INSERT INTO `monitoring` (`id`, `unggah_id`, `guru_id`, `catatan`, `progres`, `created_at`) VALUES
(1, 1, 2, 'Siste Telah Menyelesaikan besar tahapan proyek', 75, '2026-11-07 15:31:09');

-- --------------------------------------------------------

--
-- Table structure for table `penilaian`
--

CREATE TABLE `penilaian` (
  `id` int NOT NULL,
  `unggah_id` int NOT NULL,
  `guru_id` int NOT NULL,
  `fluency` decimal(5,2) NOT NULL DEFAULT '0.00',
  `flexibility` decimal(5,2) NOT NULL DEFAULT '0.00',
  `originality` decimal(5,2) NOT NULL DEFAULT '0.00',
  `elaboration` decimal(5,2) NOT NULL DEFAULT '0.00',
  `umpan_balik` text COLLATE utf8mb4_unicode_ci,
  `nilai_akhir` decimal(5,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `penilaian`
--

INSERT INTO `penilaian` (`id`, `unggah_id`, `guru_id`, `fluency`, `flexibility`, `originality`, `elaboration`, `umpan_balik`, `nilai_akhir`, `created_at`) VALUES
(1, 1, 2, '85.00', '80.00', '90.00', '85.00', '', '85.00', '2026-11-07 15:32:04');

-- --------------------------------------------------------

--
-- Table structure for table `proyek`
--

CREATE TABLE `proyek` (
  `id` int NOT NULL,
  `guru_id` int NOT NULL,
  `judul` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `instruksi` text COLLATE utf8mb4_unicode_ci,
  `tanggal_mulai` date DEFAULT NULL,
  `tenggat` date DEFAULT NULL,
  `status` enum('draft','aktif','selesai') COLLATE utf8mb4_unicode_ci DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `proyek`
--

INSERT INTO `proyek` (`id`, `guru_id`, `judul`, `deskripsi`, `instruksi`, `tanggal_mulai`, `tenggat`, `status`, `created_at`) VALUES
(1, 2, 'Membuat Produk Kreatif', 'Membuat Produk Sederhanan danri Bahan Bekas', 'Buat Produk dan Komuentasikan Prosesnya', '2026-11-07', '2026-11-14', 'aktif', '2026-11-07 15:28:01');

-- --------------------------------------------------------

--
-- Table structure for table `unggah_proyek`
--

CREATE TABLE `unggah_proyek` (
  `id` int NOT NULL,
  `proyek_id` int NOT NULL,
  `siswa_id` int NOT NULL,
  `nama_file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_asli` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `status` enum('terkirim','direvisi','diterima') COLLATE utf8mb4_unicode_ci DEFAULT 'terkirim',
  `uploaded_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `unggah_proyek`
--

INSERT INTO `unggah_proyek` (`id`, `proyek_id`, `siswa_id`, `nama_file`, `file_asli`, `catatan`, `status`, `uploaded_at`) VALUES
(1, 1, 3, '7b39f9d4fcbdcdbea23356087488ea6a.pdf', 'Surat Lamaran Pekerjaan.pdf', 'dd', 'terkirim', '2026-11-07 15:29:39');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `nama` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','guru','siswa') COLLATE utf8mb4_unicode_ci NOT NULL,
  `nis_nip` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kelas` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jurusan` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `nama`, `username`, `password`, `role`, `nis_nip`, `kelas`, `jurusan`, `created_at`) VALUES
(1, 'admin', 'admin', '$2y$10$ak1FRbsM5s9TAgluIOGam.16LRzSJP1M6HaybqTesJ2S7yZEJEKKm', 'admin', NULL, NULL, NULL, '2026-11-07 15:16:20'),
(2, 'TesGUru', 'guru', '$2y$10$mzcAMqUNNp0pBc1035kpMerg4Hx/tBmmkYuxoCmz0lQzI910mpnZm', 'guru', '2345678', '', '', '2026-11-07 15:18:17'),
(3, 'TesMurid', 'murid', '$2y$10$NZ/Qe2z2ebsTzGY7UPAGiOv.Z00K2iQiRXqpK3kGAz0buSBhghN6.', 'siswa', '1234567', 'X2', 'si', '2026-11-07 15:18:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hasil_tes`
--
ALTER TABLE `hasil_tes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tes_siswa` (`siswa_id`),
  ADD KEY `fk_tes_guru` (`guru_id`);

--
-- Indexes for table `monitoring`
--
ALTER TABLE `monitoring`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_monitor_unggah` (`unggah_id`),
  ADD KEY `fk_monitor_guru` (`guru_id`);

--
-- Indexes for table `penilaian`
--
ALTER TABLE `penilaian`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unggah_id` (`unggah_id`),
  ADD KEY `fk_nilai_guru` (`guru_id`);

--
-- Indexes for table `proyek`
--
ALTER TABLE `proyek`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_proyek_guru` (`guru_id`);

--
-- Indexes for table `unggah_proyek`
--
ALTER TABLE `unggah_proyek`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unik_pengumpulan` (`proyek_id`,`siswa_id`),
  ADD KEY `fk_unggah_siswa` (`siswa_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hasil_tes`
--
ALTER TABLE `hasil_tes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `monitoring`
--
ALTER TABLE `monitoring`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `penilaian`
--
ALTER TABLE `penilaian`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `proyek`
--
ALTER TABLE `proyek`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `unggah_proyek`
--
ALTER TABLE `unggah_proyek`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `hasil_tes`
--
ALTER TABLE `hasil_tes`
  ADD CONSTRAINT `fk_tes_guru` FOREIGN KEY (`guru_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `fk_tes_siswa` FOREIGN KEY (`siswa_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `monitoring`
--
ALTER TABLE `monitoring`
  ADD CONSTRAINT `fk_monitor_guru` FOREIGN KEY (`guru_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `fk_monitor_unggah` FOREIGN KEY (`unggah_id`) REFERENCES `unggah_proyek` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `penilaian`
--
ALTER TABLE `penilaian`
  ADD CONSTRAINT `fk_nilai_guru` FOREIGN KEY (`guru_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `fk_nilai_unggah` FOREIGN KEY (`unggah_id`) REFERENCES `unggah_proyek` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `proyek`
--
ALTER TABLE `proyek`
  ADD CONSTRAINT `fk_proyek_guru` FOREIGN KEY (`guru_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `unggah_proyek`
--
ALTER TABLE `unggah_proyek`
  ADD CONSTRAINT `fk_unggah_proyek` FOREIGN KEY (`proyek_id`) REFERENCES `proyek` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_unggah_siswa` FOREIGN KEY (`siswa_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
