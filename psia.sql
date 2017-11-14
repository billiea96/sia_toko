-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 08, 2017 at 09:49 AM
-- Server version: 10.1.10-MariaDB
-- PHP Version: 7.0.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `psia`
--

-- --------------------------------------------------------

--
-- Table structure for table `akun`
--

CREATE TABLE `akun` (
  `NoAkun` char(3) NOT NULL,
  `Nama` varchar(100) NOT NULL,
  `SaldoNormal` smallint(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `akun`
--

INSERT INTO `akun` (`NoAkun`, `Nama`, `SaldoNormal`) VALUES
('101', 'Kas Di Tangan', 1),
('102', 'Rekening Bank Baca Baca', 1),
('103', 'Rekening Bank Suka Sendiri', 1),
('104', 'Piutang Dagang', 1),
('105', 'Piutang Cek', 1),
('106', 'Sediaan Barang Alat Tulis', 1),
('107', 'Sediaan Barang Rumah Tangga', 1),
('108', 'Sediaan Habis Pakai', 1),
('109', 'Kendaraan', 1),
('110', 'Akumulasi Depresiasi Kendaraan', -1),
('201', 'Hutang Dagang', -1),
('202', 'Hutang Bank', -1),
('203', 'Hutang Cek', -1),
('204', 'Hutang PPN', -1),
('301', 'Modal Pemilik', -1),
('302', 'Prive', 1),
('401', 'Penjualan', -1),
('402', 'Diskon Penjualan', 1),
('403', 'Pendapatan Lain-Lain', -1),
('501', 'HPP', 1),
('506', 'Biaya Gaji Pegawai', 1),
('507', 'Biaya Sediaan Habis Pakai', 1),
('508', 'Biaya Depresiasi Kendaraan', 1),
('509', 'Biaya Listrik Telpon, Air', 1),
('515', 'Rugi Penjualan Aset Tetap', 1),
('520', 'Biaya/Rugi Lain-Lain', 1);

-- --------------------------------------------------------

--
-- Table structure for table `akun_has_laporan`
--

CREATE TABLE `akun_has_laporan` (
  `NoAkun` char(3) NOT NULL,
  `IDLaporan` char(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `bank`
--

CREATE TABLE `bank` (
  `IdBank` int(11) NOT NULL,
  `Nama` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `bank`
--

INSERT INTO `bank` (`IdBank`, `Nama`) VALUES
(1, 'Baca Baca'),
(2, 'Suka Sendiri');

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `KodeBarang` int(11) NOT NULL,
  `Nama` varchar(100) NOT NULL,
  `HargaJual` float NOT NULL,
  `HargaBeliRata2` float NOT NULL,
  `Stok` int(11) NOT NULL,
  `NoJenisBarang` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`KodeBarang`, `Nama`, `HargaJual`, `HargaBeliRata2`, `Stok`, `NoJenisBarang`) VALUES
(1, 'Buku Gambar ''Disney''', 40000, 20000, 100, 1),
(2, 'Map ''Rainbow''', 70000, 50000, 200, 2),
(3, 'Sapu ''Cleany Sweep''', 150000, 100000, 100, 2),
(4, 'Ember ''Tiger Star''', 70000, 50000, 200, 2);

-- --------------------------------------------------------

--
-- Table structure for table `jenis_barang`
--

CREATE TABLE `jenis_barang` (
  `NoJenisBarang` int(11) NOT NULL,
  `Nama` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `jenis_barang`
--

INSERT INTO `jenis_barang` (`NoJenisBarang`, `Nama`) VALUES
(1, 'Alat Tulis'),
(2, 'Barang Rumah Tangga');

-- --------------------------------------------------------

--
-- Table structure for table `jurnal`
--

CREATE TABLE `jurnal` (
  `IDJurnal` int(11) NOT NULL,
  `Tanggal` date NOT NULL,
  `Keterangan` text NOT NULL,
  `NoBukti` varchar(45) NOT NULL,
  `JenisJurnal` enum('JU','JK','JPN','JP') DEFAULT NULL,
  `IDPeriode` char(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `jurnal`
--

INSERT INTO `jurnal` (`IDJurnal`, `Tanggal`, `Keterangan`, `NoBukti`, `JenisJurnal`, `IDPeriode`) VALUES
(1, '2017-11-08', 'Transaksi Pembelian Tunai', 'NB0001', 'JU', '20171'),
(2, '2017-11-08', 'Transaksi Penjualan Tunai', 'NJ0001', 'JU', '20171');

-- --------------------------------------------------------

--
-- Table structure for table `jurnal_has_akun`
--

CREATE TABLE `jurnal_has_akun` (
  `IDJurnal` int(11) NOT NULL,
  `NoAkun` char(3) NOT NULL,
  `Urutan` smallint(6) DEFAULT NULL,
  `NominalDebet` mediumtext,
  `NominalKredit` mediumtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `jurnal_has_akun`
--

INSERT INTO `jurnal_has_akun` (`IDJurnal`, `NoAkun`, `Urutan`, `NominalDebet`, `NominalKredit`) VALUES
(1, '101', 2, '0', '12000000'),
(1, '106', 1, '12000000', '0'),
(2, '101', 1, '800000', '0'),
(2, '106', 4, '0', '400000'),
(2, '401', 2, '0', '800000'),
(2, '501', 3, '400000', '0');

-- --------------------------------------------------------

--
-- Table structure for table `laporan`
--

CREATE TABLE `laporan` (
  `IDLaporan` char(2) NOT NULL,
  `Nama` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nota_beli`
--

CREATE TABLE `nota_beli` (
  `NoNotaBeli` varchar(45) NOT NULL,
  `Tanggal` date NOT NULL,
  `Total` float NOT NULL,
  `Bayar` float NOT NULL,
  `JenisPembayaran` enum('T','TR','K','C') NOT NULL,
  `Diskon` decimal(10,0) NOT NULL,
  `DiskonPelunasan` decimal(10,0) NOT NULL,
  `TanggalBatasDiskon` date NOT NULL,
  `TanggalJatuhTempo` date NOT NULL,
  `FOB` varchar(45) NOT NULL,
  `OngkosKirim` int(11) NOT NULL,
  `StatusKirim` tinyint(1) NOT NULL,
  `KodeSupplier` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nota_jual`
--

CREATE TABLE `nota_jual` (
  `NoNotaJual` varchar(45) NOT NULL,
  `Tanggal` date NOT NULL,
  `Total` float NOT NULL,
  `Bayar` float NOT NULL,
  `JenisPembayaran` enum('T','K','TF','C') NOT NULL,
  `Diskon` decimal(10,0) NOT NULL,
  `DiskonPelunasan` decimal(10,0) DEFAULT NULL,
  `TanggalBatasDiskon` date NOT NULL,
  `PPN` decimal(10,0) NOT NULL,
  `TanggalJatuhTempo` date NOT NULL,
  `FOB` varchar(45) NOT NULL,
  `OngkosKirin` int(11) DEFAULT NULL,
  `StatusKirim` tinyint(1) DEFAULT NULL,
  `KodePelanggan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nota_terima_beli`
--

CREATE TABLE `nota_terima_beli` (
  `NoNotaTerima` varchar(45) NOT NULL,
  `Tanggal` date NOT NULL,
  `NoNotaBeli` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `nota_terima_jual`
--

CREATE TABLE `nota_terima_jual` (
  `NoNotaTerima` varchar(45) NOT NULL,
  `Tanggal` date NOT NULL,
  `NoNotaJual` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `KodePelanggan` int(11) NOT NULL,
  `Nama` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`KodePelanggan`, `Nama`) VALUES
(1, 'Pelanggan Umum'),
(2, 'PT ''KERJA TERUS''');

-- --------------------------------------------------------

--
-- Table structure for table `pelunasan_hutang`
--

CREATE TABLE `pelunasan_hutang` (
  `NoPelunasan` int(11) NOT NULL,
  `Tanggal` date NOT NULL,
  `NominalSeharusnya` float NOT NULL,
  `DiskonPelunasan` float NOT NULL,
  `Bayar` float NOT NULL,
  `JenisPembayaran` enum('T','TR','K','C') NOT NULL,
  `NoNotaBeli` varchar(45) NOT NULL,
  `IdBank` int(11) DEFAULT NULL,
  `NoRekening` varchar(45) DEFAULT NULL,
  `PemilikRekening` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pelunasan_piutang`
--

CREATE TABLE `pelunasan_piutang` (
  `NoPelunasan` int(11) NOT NULL,
  `Tanggal` date NOT NULL,
  `NominalSeharusnya` int(11) NOT NULL,
  `DiskonPelunasan` int(11) NOT NULL,
  `Bayar` float NOT NULL,
  `JenisPembayaran` enum('T','K') DEFAULT NULL,
  `NoNotaJual` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pembelian`
--

CREATE TABLE `pembelian` (
  `NoNotaBeli` varchar(45) NOT NULL,
  `KodeBarang` int(11) NOT NULL,
  `Harga` float NOT NULL,
  `Jumlah` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `penjualan`
--

CREATE TABLE `penjualan` (
  `NoNotaJual` varchar(45) NOT NULL,
  `KodeBarang` int(11) NOT NULL,
  `Harga` float NOT NULL,
  `Jumlah` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `periode`
--

CREATE TABLE `periode` (
  `IDPeriode` char(5) NOT NULL,
  `TglAwal` date DEFAULT NULL,
  `TglAkhir` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `periode`
--

INSERT INTO `periode` (`IDPeriode`, `TglAwal`, `TglAkhir`) VALUES
('20171', '2017-01-01', '2017-01-31'),
('20172', '2017-02-01', '2017-02-28');

-- --------------------------------------------------------

--
-- Table structure for table `periode_has_akun`
--

CREATE TABLE `periode_has_akun` (
  `IDPeriode` char(5) NOT NULL,
  `NoAkun` char(3) NOT NULL,
  `SaldoAwal` mediumtext,
  `SaldoAkhir` mediumtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `periode_has_akun`
--

INSERT INTO `periode_has_akun` (`IDPeriode`, `NoAkun`, `SaldoAwal`, `SaldoAkhir`) VALUES
('20171', '101', '300000000', '0'),
('20171', '102', '200000000', '0'),
('20171', '103', '170000000', '0'),
('20171', '104', '20000000', '0'),
('20171', '105', '0', '0'),
('20171', '106', '0', '0'),
('20171', '107', '0', '0'),
('20171', '108', '10000000', '0'),
('20171', '109', '50000000', '0'),
('20171', '110', '75000000', '0'),
('20171', '201', '40000000', '0'),
('20171', '202', '10000000', '0'),
('20171', '203', '0', '0'),
('20171', '204', '25000000', '0'),
('20171', '301', '600000000', '0'),
('20171', '302', '0', '0'),
('20171', '401', '0', '0'),
('20171', '402', '0', '0'),
('20171', '403', '0', '0'),
('20171', '501', '0', '0'),
('20171', '506', '0', '0'),
('20171', '507', '0', '0'),
('20171', '508', '0', '0'),
('20171', '509', '0', '0'),
('20171', '515', '0', '0'),
('20171', '520', '0', '0');

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `KodeSupplier` int(11) NOT NULL,
  `Nama` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`KodeSupplier`, `Nama`) VALUES
(1, 'UD ''SUKA TULIS'''),
(2, 'UD ''BERSIH SELALU'''),
(3, 'UD ''MAKMUR BAHAGIA'''),
(4, 'UD ''SUKSES SEJATI''');

-- --------------------------------------------------------

--
-- Stand-in structure for view `vlaporanjurnal`
--
CREATE TABLE `vlaporanjurnal` (
`Tanggal` date
,`Keterangan` text
,`NamaAkun` varchar(100)
,`NominalDebet` mediumtext
,`NominalKredit` mediumtext
);

-- --------------------------------------------------------

--
-- Structure for view `vlaporanjurnal`
--
DROP TABLE IF EXISTS `vlaporanjurnal`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vlaporanjurnal`  AS  select `j`.`Tanggal` AS `Tanggal`,`j`.`Keterangan` AS `Keterangan`,`a`.`Nama` AS `NamaAkun`,`jj`.`NominalDebet` AS `NominalDebet`,`jj`.`NominalKredit` AS `NominalKredit` from ((`jurnal` `j` join `jurnal_has_akun` `jj` on((`j`.`IDJurnal` = `jj`.`IDJurnal`))) join `akun` `a` on((`jj`.`NoAkun` = `a`.`NoAkun`))) order by `j`.`IDJurnal`,`jj`.`Urutan` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `akun`
--
ALTER TABLE `akun`
  ADD PRIMARY KEY (`NoAkun`);

--
-- Indexes for table `akun_has_laporan`
--
ALTER TABLE `akun_has_laporan`
  ADD PRIMARY KEY (`NoAkun`,`IDLaporan`),
  ADD KEY `fk_Akun_has_Laporan_Laporan1_idx` (`IDLaporan`),
  ADD KEY `fk_Akun_has_Laporan_Akun1_idx` (`NoAkun`);

--
-- Indexes for table `bank`
--
ALTER TABLE `bank`
  ADD PRIMARY KEY (`IdBank`);

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`KodeBarang`),
  ADD KEY `fk_Barang_Jenis_Barang1_idx` (`NoJenisBarang`);

--
-- Indexes for table `jenis_barang`
--
ALTER TABLE `jenis_barang`
  ADD PRIMARY KEY (`NoJenisBarang`);

--
-- Indexes for table `jurnal`
--
ALTER TABLE `jurnal`
  ADD PRIMARY KEY (`IDJurnal`),
  ADD KEY `fk_Jurnal_Periode1_idx` (`IDPeriode`);

--
-- Indexes for table `jurnal_has_akun`
--
ALTER TABLE `jurnal_has_akun`
  ADD PRIMARY KEY (`IDJurnal`,`NoAkun`),
  ADD KEY `fk_Jurnal_has_Akun_Akun1_idx` (`NoAkun`),
  ADD KEY `fk_Jurnal_has_Akun_Jurnal1_idx` (`IDJurnal`);

--
-- Indexes for table `laporan`
--
ALTER TABLE `laporan`
  ADD PRIMARY KEY (`IDLaporan`);

--
-- Indexes for table `nota_beli`
--
ALTER TABLE `nota_beli`
  ADD PRIMARY KEY (`NoNotaBeli`),
  ADD KEY `fk_Nota_Beli_Supplier1_idx` (`KodeSupplier`);

--
-- Indexes for table `nota_jual`
--
ALTER TABLE `nota_jual`
  ADD PRIMARY KEY (`NoNotaJual`),
  ADD KEY `fk_Nota_Jual_Pelanggan1_idx` (`KodePelanggan`);

--
-- Indexes for table `nota_terima_beli`
--
ALTER TABLE `nota_terima_beli`
  ADD PRIMARY KEY (`NoNotaTerima`),
  ADD KEY `fk_Nota_Terima_Nota_Beli1_idx` (`NoNotaBeli`);

--
-- Indexes for table `nota_terima_jual`
--
ALTER TABLE `nota_terima_jual`
  ADD PRIMARY KEY (`NoNotaTerima`),
  ADD KEY `fk_Nota_Terima_Jual_Nota_Jual1_idx` (`NoNotaJual`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`KodePelanggan`);

--
-- Indexes for table `pelunasan_hutang`
--
ALTER TABLE `pelunasan_hutang`
  ADD PRIMARY KEY (`NoPelunasan`),
  ADD KEY `fk_Pelunasan_Hutang_Nota_Beli1_idx` (`NoNotaBeli`),
  ADD KEY `fk_Pelunasan_Hutang_Bank1_idx` (`IdBank`);

--
-- Indexes for table `pelunasan_piutang`
--
ALTER TABLE `pelunasan_piutang`
  ADD PRIMARY KEY (`NoPelunasan`),
  ADD KEY `fk_Pelunasan_Piutang_Nota_Jual1_idx` (`NoNotaJual`);

--
-- Indexes for table `pembelian`
--
ALTER TABLE `pembelian`
  ADD PRIMARY KEY (`NoNotaBeli`,`KodeBarang`),
  ADD KEY `fk_Nota_Beli_has_Barang_Barang1_idx` (`KodeBarang`),
  ADD KEY `fk_Nota_Beli_has_Barang_Nota_Beli_idx` (`NoNotaBeli`);

--
-- Indexes for table `penjualan`
--
ALTER TABLE `penjualan`
  ADD PRIMARY KEY (`NoNotaJual`,`KodeBarang`),
  ADD KEY `fk_Nota_Jual_has_Barang_Barang1_idx` (`KodeBarang`),
  ADD KEY `fk_Nota_Jual_has_Barang_Nota_Jual1_idx` (`NoNotaJual`);

--
-- Indexes for table `periode`
--
ALTER TABLE `periode`
  ADD PRIMARY KEY (`IDPeriode`);

--
-- Indexes for table `periode_has_akun`
--
ALTER TABLE `periode_has_akun`
  ADD PRIMARY KEY (`IDPeriode`,`NoAkun`),
  ADD KEY `fk_Periode_has_Akun_Akun1_idx` (`NoAkun`),
  ADD KEY `fk_Periode_has_Akun_Periode1_idx` (`IDPeriode`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`KodeSupplier`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bank`
--
ALTER TABLE `bank`
  MODIFY `IdBank` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `KodeBarang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `jenis_barang`
--
ALTER TABLE `jenis_barang`
  MODIFY `NoJenisBarang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `KodePelanggan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `KodeSupplier` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `akun_has_laporan`
--
ALTER TABLE `akun_has_laporan`
  ADD CONSTRAINT `fk_Akun_has_Laporan_Akun1` FOREIGN KEY (`NoAkun`) REFERENCES `akun` (`NoAkun`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Akun_has_Laporan_Laporan1` FOREIGN KEY (`IDLaporan`) REFERENCES `laporan` (`IDLaporan`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `barang`
--
ALTER TABLE `barang`
  ADD CONSTRAINT `fk_Barang_Jenis_Barang1` FOREIGN KEY (`NoJenisBarang`) REFERENCES `jenis_barang` (`NoJenisBarang`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `jurnal`
--
ALTER TABLE `jurnal`
  ADD CONSTRAINT `fk_Jurnal_Periode1` FOREIGN KEY (`IDPeriode`) REFERENCES `periode` (`IDPeriode`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `jurnal_has_akun`
--
ALTER TABLE `jurnal_has_akun`
  ADD CONSTRAINT `fk_Jurnal_has_Akun_Akun1` FOREIGN KEY (`NoAkun`) REFERENCES `akun` (`NoAkun`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Jurnal_has_Akun_Jurnal1` FOREIGN KEY (`IDJurnal`) REFERENCES `jurnal` (`IDJurnal`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `nota_beli`
--
ALTER TABLE `nota_beli`
  ADD CONSTRAINT `fk_Nota_Beli_Supplier1` FOREIGN KEY (`KodeSupplier`) REFERENCES `supplier` (`KodeSupplier`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `nota_jual`
--
ALTER TABLE `nota_jual`
  ADD CONSTRAINT `fk_Nota_Jual_Pelanggan1` FOREIGN KEY (`KodePelanggan`) REFERENCES `pelanggan` (`KodePelanggan`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `nota_terima_beli`
--
ALTER TABLE `nota_terima_beli`
  ADD CONSTRAINT `fk_Nota_Terima_Nota_Beli1` FOREIGN KEY (`NoNotaBeli`) REFERENCES `nota_beli` (`NoNotaBeli`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `nota_terima_jual`
--
ALTER TABLE `nota_terima_jual`
  ADD CONSTRAINT `fk_Nota_Terima_Jual_Nota_Jual1` FOREIGN KEY (`NoNotaJual`) REFERENCES `nota_jual` (`NoNotaJual`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `pelunasan_hutang`
--
ALTER TABLE `pelunasan_hutang`
  ADD CONSTRAINT `fk_Pelunasan_Hutang_Bank1` FOREIGN KEY (`IdBank`) REFERENCES `bank` (`IdBank`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Pelunasan_Hutang_Nota_Beli1` FOREIGN KEY (`NoNotaBeli`) REFERENCES `nota_beli` (`NoNotaBeli`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `pelunasan_piutang`
--
ALTER TABLE `pelunasan_piutang`
  ADD CONSTRAINT `fk_Pelunasan_Piutang_Nota_Jual1` FOREIGN KEY (`NoNotaJual`) REFERENCES `nota_jual` (`NoNotaJual`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `pembelian`
--
ALTER TABLE `pembelian`
  ADD CONSTRAINT `fk_Nota_Beli_has_Barang_Barang1` FOREIGN KEY (`KodeBarang`) REFERENCES `barang` (`KodeBarang`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Nota_Beli_has_Barang_Nota_Beli` FOREIGN KEY (`NoNotaBeli`) REFERENCES `nota_beli` (`NoNotaBeli`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `penjualan`
--
ALTER TABLE `penjualan`
  ADD CONSTRAINT `fk_Nota_Jual_has_Barang_Barang1` FOREIGN KEY (`KodeBarang`) REFERENCES `barang` (`KodeBarang`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Nota_Jual_has_Barang_Nota_Jual1` FOREIGN KEY (`NoNotaJual`) REFERENCES `nota_jual` (`NoNotaJual`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `periode_has_akun`
--
ALTER TABLE `periode_has_akun`
  ADD CONSTRAINT `fk_Periode_has_Akun_Akun1` FOREIGN KEY (`NoAkun`) REFERENCES `akun` (`NoAkun`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Periode_has_Akun_Periode1` FOREIGN KEY (`IDPeriode`) REFERENCES `periode` (`IDPeriode`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
