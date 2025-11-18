-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 15 Nov 2025 pada 18.39
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_sistem_donasi_new`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id_admin`, `username`, `password`, `nama_lengkap`) VALUES
(1, 'admin123', 'admin123', 'administrator');

-- --------------------------------------------------------

--
-- Struktur dari tabel `donasi`
--

CREATE TABLE `donasi` (
  `id_donasi` int(11) NOT NULL,
  `id_kampanye` int(11) NOT NULL,
  `nama_donatur` varchar(100) DEFAULT NULL,
  `email_donatur` varchar(100) DEFAULT NULL,
  `jumlah_donasi` decimal(15,2) NOT NULL,
  `metode_pembayaran` varchar(50) DEFAULT NULL,
  `tanggal_donasi` datetime DEFAULT current_timestamp(),
  `status_pembayaran` enum('Menunggu Konfirmasi','Berhasil','Gagal') DEFAULT 'Menunggu Konfirmasi',
  `pesan_donatur` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `donasi`
--

INSERT INTO `donasi` (`id_donasi`, `id_kampanye`, `nama_donatur`, `email_donatur`, `jumlah_donasi`, `metode_pembayaran`, `tanggal_donasi`, `status_pembayaran`, `pesan_donatur`) VALUES
(19, 3, 'Muhammad Hovid Arman', 'hovid@gmail.com', 100000000.00, 'Kartu Kredit', '2025-11-16 00:06:24', 'Berhasil', 'Bismillah');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kampanye`
--

CREATE TABLE `kampanye` (
  `id_kampanye` int(11) NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `deskripsi` text NOT NULL,
  `target_dana` decimal(15,2) NOT NULL,
  `dana_terkumpul` decimal(15,2) DEFAULT 0.00,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_akhir` date DEFAULT NULL,
  `foto_utama` varchar(255) DEFAULT NULL,
  `status_kampanye` enum('Aktif','Selesai','Dihentikan') DEFAULT 'Aktif',
  `gambar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kampanye`
--

INSERT INTO `kampanye` (`id_kampanye`, `id_kategori`, `judul`, `deskripsi`, `target_dana`, `dana_terkumpul`, `tanggal_mulai`, `tanggal_akhir`, `foto_utama`, `status_kampanye`, `gambar`) VALUES
(1, 1, 'Bantuan Korban Gempa Lombok', 'Membantu saudara-saudara kita yang terdampak gempa di Lombok dengan kebutuhan pokok, obat-obatan, dan tempat tinggal sementara.', 500000000.00, 0.00, '2025-01-01', '2025-03-31', NULL, 'Aktif', 'gempa-lombok.jpg'),
(2, 2, 'Beasiswa Anak Yatim', 'Program beasiswa untuk anak yatim yang kurang mampu agar dapat terus bersekolah dan meraih cita-cita.', 200000000.00, 0.00, '2024-08-24', '2024-12-31', NULL, 'Aktif', 'anak-yatim.jpg'),
(3, 3, 'Bantuan Operasi Jantung', 'Membantu biaya operasi jantung untuk anak-anak dari keluarga tidak mampu yang membutuhkan penanganan medis segera.', 300000000.00, 100000000.00, '2025-04-01', '2025-11-30', NULL, 'Aktif', 'operasi-jantung.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`, `deskripsi`) VALUES
(1, 'Bencana Alam', 'Kampanye untuk membantu korban bencana alam'),
(2, 'Pendidikan', 'Kampanye untuk mendukung pendidikan anak kurang mampu'),
(3, 'Kesehatan', 'Kampanye untuk biaya pengobatan dan kesehatan'),
(4, 'Sosial', 'Kampanye untuk kegiatan sosial dan kemanusiaan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pencairan_dana`
--

CREATE TABLE `pencairan_dana` (
  `id_pencairan` int(11) NOT NULL,
  `id_kampanye` int(11) NOT NULL,
  `jumlah_cair` decimal(15,2) NOT NULL,
  `tanggal_cair` datetime DEFAULT current_timestamp(),
  `tujuan` text NOT NULL,
  `bukti_dokumen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pencairan_dana`
--

INSERT INTO `pencairan_dana` (`id_pencairan`, `id_kampanye`, `jumlah_cair`, `tanggal_cair`, `tujuan`, `bukti_dokumen`) VALUES
(1, 1, 100000.00, '2025-11-15 22:46:36', 'Untuk mendonasikan dana\r\n', NULL),
(2, 1, 100000000.00, '2025-11-15 22:47:01', 'Untuk donasi\r\n', NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `donasi`
--
ALTER TABLE `donasi`
  ADD PRIMARY KEY (`id_donasi`),
  ADD KEY `id_kampanye` (`id_kampanye`);

--
-- Indeks untuk tabel `kampanye`
--
ALTER TABLE `kampanye`
  ADD PRIMARY KEY (`id_kampanye`),
  ADD KEY `id_kategori` (`id_kategori`);

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`),
  ADD UNIQUE KEY `nama_kategori` (`nama_kategori`);

--
-- Indeks untuk tabel `pencairan_dana`
--
ALTER TABLE `pencairan_dana`
  ADD PRIMARY KEY (`id_pencairan`),
  ADD KEY `id_kampanye` (`id_kampanye`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `donasi`
--
ALTER TABLE `donasi`
  MODIFY `id_donasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `kampanye`
--
ALTER TABLE `kampanye`
  MODIFY `id_kampanye` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `pencairan_dana`
--
ALTER TABLE `pencairan_dana`
  MODIFY `id_pencairan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `donasi`
--
ALTER TABLE `donasi`
  ADD CONSTRAINT `donasi_ibfk_1` FOREIGN KEY (`id_kampanye`) REFERENCES `kampanye` (`id_kampanye`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `kampanye`
--
ALTER TABLE `kampanye`
  ADD CONSTRAINT `kampanye_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pencairan_dana`
--
ALTER TABLE `pencairan_dana`
  ADD CONSTRAINT `pencairan_dana_ibfk_1` FOREIGN KEY (`id_kampanye`) REFERENCES `kampanye` (`id_kampanye`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
