-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
-- Database: `db_laundry`
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

DROP DATABASE IF EXISTS `db_laundry`;
CREATE DATABASE `db_laundry`;
USE `db_laundry`;

-- --------------------------------------------------------
-- Struktur tabel `detail_transaksi`
-- --------------------------------------------------------
CREATE TABLE `detail_transaksi` (
  `id_detail` int(11) NOT NULL,
  `id_transaksi` int(11) NOT NULL,
  `id_paket` int(11) DEFAULT NULL,
  `qty` double NOT NULL,
  `total_harga` double NOT NULL,
  `keterangan` text NOT NULL,
  `total_bayar` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `detail_transaksi` (`id_detail`, `id_transaksi`, `id_paket`, `qty`, `total_harga`, `keterangan`, `total_bayar`) VALUES
(16, 36, 20, 20, 44000, '', 500000),
(17, 37, 20, 50, 110000, '', 200000),
(18, 39, 21, 15, 21000, '', 25000),
(19, 40, 20, 10, 22000, '', 33000),
(20, 41, 20, 10, 22000, '', 50000),
(21, 42, 23, 12, 30000, '', 1000000),
(22, 43, 21, 12, 16800, '', 25000),
(23, 44, 20, 12, 266400, '', 300000);

-- --------------------------------------------------------
-- Struktur tabel `outlet`
-- --------------------------------------------------------
CREATE TABLE `outlet` (
  `id_outlet` int(11) NOT NULL,
  `nama_outlet` varchar(228) DEFAULT NULL,
  `alamat_outlet` text DEFAULT NULL,
  `telp_outlet` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `outlet` (`id_outlet`, `nama_outlet`, `alamat_outlet`, `telp_outlet`) VALUES
(9, 'Outlet Merah', 'ambon, Indonesia', '08555555555'),
(10, 'Outlet Putih', 'jln baru, ambon, Indonesia', '081222222222'),
(11, 'Outlet Biru', 'jaln baru', '081223446312'),
(12, 'Outlet Abu-abu', 'jln baru', '0826377453886');

-- --------------------------------------------------------
-- Struktur tabel `paket_cuci`
-- --------------------------------------------------------
CREATE TABLE `paket_cuci` (
  `id_paket` int(11) NOT NULL,
  `jenis_paket` enum('kiloan','satuan','jumbo') NOT NULL,
  `nama_paket` varchar(228) NOT NULL,
  `harga` int(11) NOT NULL,
  `outlet_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `paket_cuci` (`id_paket`, `jenis_paket`, `nama_paket`, `harga`, `outlet_id`) VALUES
(20, 'kiloan', 'Paket Wangi, Bersih dan Cepat', 12200, 9),
(21, 'satuan', 'Paket Ekslusif Premiun', 140000, 9),
(22, 'jumbo', 'Paket cuci cepat untuk barang besar', 128000, 9);

-- --------------------------------------------------------
-- Struktur tabel `pelanggan`
-- --------------------------------------------------------
CREATE TABLE `pelanggan` (
  `id_pelanggan` int(11) NOT NULL,
  `nama_pelanggan` varchar(228) NOT NULL,
  `alamat_pelanggan` text NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `telp_pelanggan` varchar(15) NOT NULL,
  `no_ktp` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `pelanggan` (`id_pelanggan`, `nama_pelanggan`, `alamat_pelanggan`, `jenis_kelamin`, `telp_pelanggan`, `no_ktp`) VALUES
(23, 'Lulu', 'jalan baru', 'P', '088888888888', '123456789'),
(24, 'Lily', 'waihaong, ambon', 'P', '0821123311131', '0987654321'),
(25, 'Apip Luki', 'silale, ambon', 'L', '08123456244567', '1234567890'),
(26, 'rania', 'soabali, ambon', 'P', '08634635536', '7887878787878'),
(27, 'Lilyyy', 'soabali', 'P', '87788789988', '65565565656657'),
(28, 'ranty', 'jalan baru', 'P', '088877334', '76767777');

-- --------------------------------------------------------
-- Struktur tabel `transaksi`
-- --------------------------------------------------------
-- Perbaikan struktur tabel transaksi
CREATE TABLE `transaksi` (
  `id_transaksi` INT(11) NOT NULL AUTO_INCREMENT,
  `outlet_id` INT(11) NOT NULL,
  `kode_invoice` VARCHAR(50) NOT NULL,
  `id_pelanggan` INT(11) NOT NULL,
  `tgl` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `batas_waktu` DATETIME NOT NULL,
  `tgl_pembayaran` DATETIME DEFAULT NULL,
  `biaya_tambahan` INT(11) NOT NULL DEFAULT 0,
  `diskon` DECIMAL(5,2) NOT NULL DEFAULT 0.00,
  `pajak` INT(11) NOT NULL DEFAULT 0,
  `status` ENUM('baru','proses','selesai','diambil') NOT NULL DEFAULT 'baru',
  `status_bayar` ENUM('dibayar','belum') NOT NULL DEFAULT 'belum',
  `antar_jemput` ENUM('gratis','bayar','ambil_sendiri') NOT NULL DEFAULT 'ambil_sendiri',
  `id_user` INT(11) DEFAULT NULL,
  PRIMARY KEY (`id_transaksi`),
  KEY `id_pelanggan` (`id_pelanggan`),
  KEY `outlet_id` (`outlet_id`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Contoh data
INSERT INTO `transaksi` (`id_transaksi`, `outlet_id`, `kode_invoice`, `id_pelanggan`, `tgl`, `batas_waktu`, `tgl_pembayaran`, `biaya_tambahan`, `diskon`, `pajak`, `status`, `status_bayar`, `antar_jemput`, `id_user`) VALUES
(36, 9, 'CLN202009033737', 23, '2020-09-03 04:37:43', '2020-09-10 12:00:00', '2020-09-03 04:40:03', 0, 0.00, 0, 'baru', 'dibayar', 'gratis', 1),
(37, 9, 'CLN202009035702', 23, '2020-09-03 05:03:37', '2020-09-10 12:00:00', '2020-09-03 05:08:28', 0, 0.00, 0, 'baru', 'dibayar', 'ambil_sendiri', 1),
(39, 10, 'CLN202009034317', 24, '2020-09-03 05:19:12', '2020-09-10 12:00:00', '2020-09-03 05:21:41', 0, 0.00, 0, 'baru', 'dibayar', 'bayar', NULL),
(40, 9, 'CLN202009040521', 24, '2020-09-04 03:21:09', '2020-09-11 12:00:00', '2025-07-08 05:42:34', 0, 0.00, 0, 'baru', 'dibayar', 'gratis', 1),
(41, 9, 'CLN202009040528', 25, '2020-09-04 03:28:21', '2020-09-11 12:00:00', '2020-09-04 03:29:00', 0, 0.00, 0, 'selesai', 'dibayar', 'ambil_sendiri', 1),
(42, 9, 'CLN202507110928', 26, '2025-07-11 05:29:07', '2025-07-18 12:00:00', '2025-07-13 05:35:54', 2, 50.00, 0, 'baru', 'dibayar', 'bayar', 6),
(43, 9, 'CLN202507203011', 27, '2025-07-20 15:16:44', '2025-07-27 00:00:00', '2025-07-20 03:21:22', 5, 10.00, 0, 'baru', 'dibayar', 'gratis', 6),
(44, 9, 'CLN202507215715', 28, '2025-07-21 06:16:32', '2025-07-28 00:00:00', '2025-07-21 06:17:29', 0, 0.00, 0, 'baru', 'dibayar', 'ambil_sendiri', 6);

-- --------------------------------------------------------
-- Struktur tabel `user`
-- --------------------------------------------------------
CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `nama_user` varchar(228) DEFAULT NULL,
  `username` varchar(228) DEFAULT NULL,
  `password` varchar(228) DEFAULT NULL,
  `outlet_id` int(11) DEFAULT NULL,
  `role` enum('admin','kasir','owner') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `user` (`id_user`, `nama_user`, `username`, `password`, `outlet_id`, `role`) VALUES
(1, 'adminku', 'admin', '21232f297a57a5a743894a0e4a801fc3', NULL, 'admin'),
(3, 'ownerku', 'owner', '72122ce96bfec66e2396d2e25225d70a', 12, 'owner'),
(6, 'Kasir Merah', 'kasirmerah', 'cdd9b843e296b9ff6745d122f19809d4', 9, 'kasir');

-- --------------------------------------------------------
-- Indexes & Auto Increment
-- --------------------------------------------------------
ALTER TABLE `detail_transaksi`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_transaksi` (`id_transaksi`),
  ADD KEY `id_paket` (`id_paket`);
ALTER TABLE `outlet` ADD PRIMARY KEY (`id_outlet`);
ALTER TABLE `paket_cuci` ADD PRIMARY KEY (`id_paket`), ADD KEY `outlet_id` (`outlet_id`);
ALTER TABLE `pelanggan` ADD PRIMARY KEY (`id_pelanggan`);
ALTER TABLE `transaksi` ADD PRIMARY KEY (`id_transaksi`), ADD KEY `id_user` (`id_user`), ADD KEY `id_pelanggan` (`id_pelanggan`), ADD KEY `outlet_id` (`outlet_id`);
ALTER TABLE `user` ADD PRIMARY KEY (`id_user`), ADD KEY `outlet_id` (`outlet_id`);

ALTER TABLE `detail_transaksi` MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
ALTER TABLE `outlet` MODIFY `id_outlet` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
ALTER TABLE `paket_cuci` MODIFY `id_paket` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
ALTER TABLE `pelanggan` MODIFY `id_pelanggan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
ALTER TABLE `transaksi` MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;
ALTER TABLE `user` MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

-- --------------------------------------------------------
-- Foreign Keys
-- --------------------------------------------------------
ALTER TABLE `detail_transaksi`
  ADD CONSTRAINT `detail_transaksi_ibfk_3` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_transaksi_ibfk_4` FOREIGN KEY (`id_paket`) REFERENCES `paket_cuci` (`id_paket`) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE `paket_cuci`
  ADD CONSTRAINT `paket_cuci_ibfk_1` FOREIGN KEY (`outlet_id`) REFERENCES `outlet` (`id_outlet`) ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_4` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `transaksi_ibfk_5` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `transaksi_ibfk_6` FOREIGN KEY (`outlet_id`) REFERENCES `outlet` (`id_outlet`) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`outlet_id`) REFERENCES `outlet` (`id_outlet`) ON DELETE SET NULL ON UPDATE CASCADE;

COMMIT;
