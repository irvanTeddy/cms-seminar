-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 29 Sep 2026 pada 16.06
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cms_seminar2`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id_admin`, `nama`, `username`, `email`, `password`, `created_at`) VALUES
(6, 'Super Admin', 'admin', 'SUPERADMIN@gmail.com', 'admin123', '2026-07-16 06:45:42');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`) VALUES
(1, 'Artificial Intelligence'),
(2, 'Cyber Security'),
(3, 'Web Development'),
(4, 'Data Science'),
(5, 'Internet of Things'),
(6, 'Machine Learning');

-- --------------------------------------------------------

--
-- Struktur dari tabel `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id_notifikasi` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `pesan` text DEFAULT NULL,
  `status` enum('Belum Dibaca','Sudah Dibaca') DEFAULT 'Belum Dibaca',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `panitia`
--

CREATE TABLE `panitia` (
  `id_panitia` int(11) NOT NULL,
  `nama_panitia` varchar(150) NOT NULL,
  `universitas` varchar(150) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `status` enum('Pending','Aktif','Nonaktif') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `panitia`
--

INSERT INTO `panitia` (`id_panitia`, `nama_panitia`, `universitas`, `email`, `password`, `telepon`, `logo`, `status`, `created_at`) VALUES
(4, 'IMPSI UST', 'UST', 'impsiust@gmail.com', 'ust123', '0898979798798', NULL, 'Aktif', '2026-07-16 05:47:33'),
(5, 'UGM IT', 'UGM', 'ugm2024@gmail.com', 'ugm123', '0898979798798', NULL, 'Aktif', '2026-07-16 06:22:31'),
(6, 'IMPSI UNY 2026', 'UNY', 'UNY@gmail.com', 'uny123', '0887837425325', NULL, 'Aktif', '2026-07-16 07:01:09'),
(7, 'IMPS UAD 2026', 'UAD', 'uad@gmail.com', 'uad123', '0887837425325', NULL, 'Aktif', '2026-07-16 10:43:45'),
(8, 'UPNVY IT', 'UPN VETERAN YOGYAKARTA', 'upn@gmail.com', 'upn123', '0877676726323', NULL, 'Aktif', '2026-07-16 23:44:55');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pendaftaran`
--

CREATE TABLE `pendaftaran` (
  `id_daftar` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_seminar` int(11) NOT NULL,
  `tanggal_daftar` timestamp NOT NULL DEFAULT current_timestamp(),
  `bukti_pembayaran` varchar(255) DEFAULT NULL,
  `status` enum('Pending','Diverifikasi','Ditolak') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pendaftaran`
--

INSERT INTO `pendaftaran` (`id_daftar`, `id_user`, `id_seminar`, `tanggal_daftar`, `bukti_pembayaran`, `status`) VALUES
(2, 2, 2, '2026-07-16 06:02:09', '1784181729_Screenshot (1).png', 'Diverifikasi'),
(3, 2, 3, '2026-07-16 06:47:27', '1784184447_Screenshot (4).png', 'Diverifikasi'),
(5, 3, 4, '2026-07-16 07:55:14', '1784188514_fddd9fb4dd5e11c8ad0c27e2d416ee6f.jpg', 'Diverifikasi'),
(6, 2, 4, '2026-07-16 08:15:42', '1784189742_fddd9fb4dd5e11c8ad0c27e2d416ee6f.jpg', 'Diverifikasi'),
(7, 3, 2, '2026-07-16 08:36:48', '1784191008_fddd9fb4dd5e11c8ad0c27e2d416ee6f.jpg', 'Pending'),
(10, 5, 4, '2026-07-17 00:28:30', '1784248110_Bukti Transfer.png', 'Pending');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengaturan`
--

CREATE TABLE `pengaturan` (
  `id_setting` int(11) NOT NULL,
  `nama_website` varchar(150) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `seminar`
--

CREATE TABLE `seminar` (
  `id_seminar` int(11) NOT NULL,
  `id_panitia` int(11) NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `judul` varchar(200) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `pemateri` varchar(150) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `jam` time DEFAULT NULL,
  `lokasi` varchar(200) DEFAULT NULL,
  `kuota` int(11) DEFAULT 0,
  `poster` varchar(255) DEFAULT NULL,
  `biaya` decimal(10,2) DEFAULT 0.00,
  `status` enum('Draft','Publish','Selesai') DEFAULT 'Draft',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `seminar`
--

INSERT INTO `seminar` (`id_seminar`, `id_panitia`, `id_kategori`, `judul`, `deskripsi`, `pemateri`, `tanggal`, `jam`, `lokasi`, `kuota`, `poster`, `biaya`, `status`, `created_at`) VALUES
(2, 4, 1, 'AI SEMINAR ', '📢 Pemateri\r\nDr. Bahlil\r\n\r\n💳 Pembayaran\r\nBCA 1234567890\r\na.n. IMPSI UST', 'Bahlil', '2026-07-17', '12:22:00', 'FT UST', 100, '1784186905.jpg', 15000.00, 'Publish', '2026-07-16 06:00:35'),
(3, 5, 3, 'SEMINAR WEB DEVELOPMENT UGM ', '📢 Pemateri\r\nDr. Budi Santoso\r\n\r\n💳 Pembayaran\r\nBCA 1234567890\r\na.n. IMPSI UNY', 'PRABOWO', '2026-08-06', '12:02:00', 'FT UGM', 100, '1784186508.jpg', 25000.00, 'Publish', '2026-07-16 06:26:50'),
(4, 6, 6, 'SEMINAR Machine Learning UNY 2026', '📢 Pemateri\r\nDr. Budi Santoso\r\n\r\n💳 Pembayaran\r\nBCA 1234567890\r\na.n. IMPSI UNY', 'PRABOWO', '2026-07-16', '20:59:00', 'UNY', 200, '1784187031.jpg', 50000.00, 'Publish', '2026-07-16 07:02:52');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `nim` varchar(30) DEFAULT NULL,
  `universitas` varchar(150) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `nama`, `nim`, `universitas`, `email`, `password`, `created_at`) VALUES
(2, 'teddy', '2928309809', 'ust', 'irvansetiawanteddy@gmail.com', '2929', '2026-07-16 05:07:14'),
(3, 'Novrian', '292849209', 'UST', 'iyan@gmail.com', 'iyan123', '2026-07-16 07:53:46'),
(4, 'eca', '2893939898', 'UST', 'eca@gmail.com', 'eca123', '2026-07-16 10:42:23'),
(5, 'jokowi', '23892849', 'UGM', 'jokowi@gmail.com', 'jokowi123', '2026-07-16 23:50:14');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indeks untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id_notifikasi`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `panitia`
--
ALTER TABLE `panitia`
  ADD PRIMARY KEY (`id_panitia`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeks untuk tabel `pendaftaran`
--
ALTER TABLE `pendaftaran`
  ADD PRIMARY KEY (`id_daftar`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_seminar` (`id_seminar`);

--
-- Indeks untuk tabel `pengaturan`
--
ALTER TABLE `pengaturan`
  ADD PRIMARY KEY (`id_setting`);

--
-- Indeks untuk tabel `seminar`
--
ALTER TABLE `seminar`
  ADD PRIMARY KEY (`id_seminar`),
  ADD KEY `id_panitia` (`id_panitia`),
  ADD KEY `id_kategori` (`id_kategori`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id_notifikasi` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `panitia`
--
ALTER TABLE `panitia`
  MODIFY `id_panitia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `pendaftaran`
--
ALTER TABLE `pendaftaran`
  MODIFY `id_daftar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `pengaturan`
--
ALTER TABLE `pengaturan`
  MODIFY `id_setting` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `seminar`
--
ALTER TABLE `seminar`
  MODIFY `id_seminar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD CONSTRAINT `notifikasi_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pendaftaran`
--
ALTER TABLE `pendaftaran`
  ADD CONSTRAINT `pendaftaran_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `pendaftaran_ibfk_2` FOREIGN KEY (`id_seminar`) REFERENCES `seminar` (`id_seminar`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `seminar`
--
ALTER TABLE `seminar`
  ADD CONSTRAINT `seminar_ibfk_1` FOREIGN KEY (`id_panitia`) REFERENCES `panitia` (`id_panitia`) ON DELETE CASCADE,
  ADD CONSTRAINT `seminar_ibfk_2` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
