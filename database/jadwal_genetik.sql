-- phpMyAdmin SQL Dump
-- version 4.9.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 03, 2020 at 11:30 PM
-- Server version: 5.7.26
-- PHP Version: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `penjadwalan`
--

-- --------------------------------------------------------

--
-- Table structure for table `guru`
--

CREATE TABLE `guru` (
  `kode` int(2) NOT NULL,
  `nip` varchar(50) DEFAULT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `alamat` varchar(50) DEFAULT NULL,
  `telp` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `guru`
--

INSERT INTO `guru` (`kode`, `nip`, `nama`, `alamat`, `telp`) VALUES
(1, NULL, 'Suradiono, SP.d, M.Pd.', NULL, NULL),
(2, NULL, 'Rofi\'un, S.Pd.', NULL, NULL),
(3, NULL, 'Umi Kulsum, S.Pd.', NULL, NULL),
(4, NULL, 'Hj. Retno Tribangun, S.Pd.', NULL, NULL),
(5, NULL, 'Ali Imron, S.Pd.', NULL, NULL),
(6, NULL, 'Samidi, S.Pd.', NULL, NULL),
(7, NULL, 'Ismoyo, S.Pd.', NULL, NULL),
(8, NULL, 'Dra. Hj. Tri Subiyanti', NULL, NULL),
(9, NULL, 'Widijati Santosa,S.Pd', NULL, NULL),
(10, NULL, 'Sunarta, S.Pd, M.M.', NULL, NULL),
(11, NULL, 'Kahar Murdanianto, S.Pd. ', NULL, NULL),
(12, NULL, 'Nurhayati, S.Pd, M.Pd.', NULL, NULL),
(13, NULL, 'Drs. Edi Harlianto', NULL, NULL),
(14, NULL, 'Drs. Chusnul Huda', NULL, NULL),
(15, NULL, 'Mukh. Ta\'yin, S.Pd.', NULL, NULL),
(16, NULL, 'Dra. Sri Wahyuningsih', NULL, NULL),
(17, NULL, 'Heri Santoso, S.Pd. ', NULL, NULL),
(18, NULL, 'Agustulas Harmiyanto, S.Pd,M.Pd.', NULL, NULL),
(19, NULL, 'Achmad Nur Auladi, S.Sos.', NULL, NULL),
(20, NULL, 'Rini Tjahjaningrum, S.Pd.', NULL, NULL),
(21, NULL, 'Nuratri Purwaningsih, S.Pd', NULL, NULL),
(22, NULL, 'Rohmadi, S.Pd', NULL, NULL),
(23, NULL, 'Sri Peni, S.Pd', NULL, NULL),
(24, NULL, 'Fitri Nur Rochmah, S.Pd', NULL, NULL),
(25, NULL, 'Suhirman, S.Pd.', NULL, NULL),
(26, NULL, 'Hadi Sutrisno, S.Pd', NULL, NULL),
(27, NULL, 'Sri Muryati, S.Psi.', NULL, NULL),
(28, NULL, 'Lina Wahyu Setya U, S.Pd.', NULL, NULL),
(29, NULL, 'Sri Indarwati, S.Pd.I.', NULL, NULL),
(30, NULL, 'Achmad Solichul Hadi, S.Pd.', NULL, NULL),
(31, NULL, 'Dra. Nur Tuti Hariyanti', NULL, NULL),
(32, NULL, 'Drs. Gimin ', NULL, NULL),
(33, NULL, 'Dwi Winarni Indrawati, S.IP,S.Pd.', NULL, NULL),
(34, NULL, 'Farida Ariyani, S.Pd.', NULL, NULL),
(35, NULL, 'Deri Harwanto, S.Kom', NULL, NULL),
(36, NULL, 'Miftachul Hadi,S.Pd', NULL, NULL),
(37, NULL, 'Wahyu Puji Lestari, S.Pd.', NULL, NULL),
(38, NULL, 'Musbichin, S.Pd.', NULL, NULL),
(39, NULL, 'Lia Agustina,S.Pd', NULL, NULL),
(40, NULL, 'Christiana Marliyah,S.Pd', NULL, NULL),
(41, NULL, 'Hariyam Sulistyowati, S.Pd.', NULL, NULL),
(42, NULL, 'Widi Astiyono, S.Ag.', NULL, NULL),
(43, NULL, 'Adi Winarto, M.Miss.', NULL, NULL),
(44, NULL, 'Dewi Maratun Nafi\'ah, S.Pd.', NULL, NULL),
(45, NULL, 'Parwanti, S.Pd.', NULL, NULL),
(46, NULL, 'Renny Tri Susanti, S.Pd.', NULL, NULL),
(47, NULL, 'Rudi Taryono, S.Pd.', NULL, NULL),
(48, NULL, 'Pingki Tantri Novita, S.Pd', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `hari`
--

CREATE TABLE `hari` (
  `kode` int(10) NOT NULL,
  `nama` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hari`
--

INSERT INTO `hari` (`kode`, `nama`) VALUES
(1, 'Senin'),
(2, 'Selasa'),
(3, 'Rabu'),
(4, 'Kamis'),
(5, 'Jumat');

-- --------------------------------------------------------

--
-- Table structure for table `jadwalpelajaran`
--

CREATE TABLE `jadwalpelajaran` (
  `kode` int(10) NOT NULL,
  `kode_pengampu` int(10) DEFAULT NULL,
  `kode_jam` int(10) DEFAULT NULL,
  `kode_hari` int(10) DEFAULT NULL,
  `kode_ruang` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='hasil proses';

-- --------------------------------------------------------

--
-- Table structure for table `jam`
--

CREATE TABLE `jam` (
  `kode` int(10) NOT NULL,
  `range_jam` varchar(50) DEFAULT NULL,
  `aktif` enum('Y','N') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jam`
--

INSERT INTO `jam` (`kode`, `range_jam`, `aktif`) VALUES
(1, '07.15-08.00', NULL),
(2, '08.00-08.45', NULL),
(3, '08.40-09.20', NULL),
(4, '08.45-09.45', NULL),
(5, '09.35-10.15', NULL),
(6, '09.45-10.30', NULL),
(7, '10.15-10.55', NULL),
(8, '10.30-11.15', NULL),
(9, '10.55-11.35', NULL),
(10, '11.15-12.00', NULL),
(11, '12.30-13.15', NULL),
(12, '12.45-13.30', NULL),
(13, '13.15-14.00', NULL),
(14, '13.30-14.15', NULL),
(15, '14.00-14.45', NULL),
(16, '14.15-14.45', NULL),
(17, '14.45-15.30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `matapelajaran`
--

CREATE TABLE `matapelajaran` (
  `kode` int(10) NOT NULL,
  `kode_mp` varchar(50) DEFAULT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `beban` int(6) DEFAULT NULL,
  `semester` int(6) DEFAULT NULL,
  `aktif` enum('True','False') DEFAULT 'True',
  `jenis` enum('TEORI','PRAKTIKUM') DEFAULT 'TEORI'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='example kode_mp = 0765109 ';

-- --------------------------------------------------------

--
-- Table structure for table `pengampu`
--

CREATE TABLE `pengampu` (
  `kode` int(10) NOT NULL,
  `kode_mp` int(10) DEFAULT NULL,
  `kode_guru` int(10) DEFAULT NULL,
  `kelas` varchar(10) DEFAULT NULL,
  `tahun_akademik` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ruang`
--

CREATE TABLE `ruang` (
  `kode` int(10) NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `kapasitas` int(10) DEFAULT NULL,
  `jenis` enum('TEORI','LABORATORIUM') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ruang`
--

INSERT INTO `ruang` (`kode`, `nama`, `kapasitas`, `jenis`) VALUES
(1, 'Ruang 1', NULL, 'TEORI'),
(2, 'Ruang 2', NULL, 'TEORI'),
(3, 'Ruang 3', NULL, 'TEORI'),
(4, 'Ruang 4', NULL, 'TEORI'),
(5, 'Ruang 5', NULL, 'TEORI'),
(6, 'Ruang 6', NULL, 'TEORI'),
(7, 'Ruang 7', NULL, 'TEORI'),
(8, 'Ruang 8', NULL, 'TEORI'),
(9, 'Ruang 9', NULL, 'TEORI'),
(10, 'Ruang 10', NULL, 'TEORI'),
(11, 'Ruang 11', NULL, 'TEORI'),
(12, 'Ruang 12', NULL, 'TEORI'),
(13, 'Ruang 13', NULL, 'TEORI'),
(14, 'Ruang 14', NULL, 'TEORI'),
(15, 'Ruang 15', NULL, 'TEORI'),
(16, 'Ruang 16', NULL, 'TEORI'),
(17, 'Lab. Sisfo 1', NULL, 'LABORATORIUM'),
(18, 'Lab. Sisfo 2', NULL, 'LABORATORIUM'),
(19, 'Lab Inherent', NULL, 'LABORATORIUM'),
(20, 'Lab Aplikasi ', NULL, 'LABORATORIUM'),
(21, 'Lab Jar ', NULL, 'LABORATORIUM'),
(22, 'Lab Micro', NULL, 'LABORATORIUM'),
(23, 'Lab Maintenence', NULL, 'LABORATORIUM');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `pass` varchar(50) DEFAULT NULL,
  `level` enum('Y','N') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `waktu_tidak_bersedia`
--

CREATE TABLE `waktu_tidak_bersedia` (
  `kode` int(10) NOT NULL,
  `kode_guru` int(10) DEFAULT NULL,
  `kode_hari` int(10) DEFAULT NULL,
  `kode_jam` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `guru`
--
ALTER TABLE `guru`
  ADD PRIMARY KEY (`kode`);

--
-- Indexes for table `hari`
--
ALTER TABLE `hari`
  ADD PRIMARY KEY (`kode`);

--
-- Indexes for table `jadwalpelajaran`
--
ALTER TABLE `jadwalpelajaran`
  ADD PRIMARY KEY (`kode`);

--
-- Indexes for table `jam`
--
ALTER TABLE `jam`
  ADD PRIMARY KEY (`kode`);

--
-- Indexes for table `matapelajaran`
--
ALTER TABLE `matapelajaran`
  ADD PRIMARY KEY (`kode`);

--
-- Indexes for table `pengampu`
--
ALTER TABLE `pengampu`
  ADD PRIMARY KEY (`kode`);

--
-- Indexes for table `ruang`
--
ALTER TABLE `ruang`
  ADD PRIMARY KEY (`kode`);

--
-- Indexes for table `waktu_tidak_bersedia`
--
ALTER TABLE `waktu_tidak_bersedia`
  ADD PRIMARY KEY (`kode`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `guru`
--
ALTER TABLE `guru`
  MODIFY `kode` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `hari`
--
ALTER TABLE `hari`
  MODIFY `kode` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `jadwalpelajaran`
--
ALTER TABLE `jadwalpelajaran`
  MODIFY `kode` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jam`
--
ALTER TABLE `jam`
  MODIFY `kode` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `matapelajaran`
--
ALTER TABLE `matapelajaran`
  MODIFY `kode` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengampu`
--
ALTER TABLE `pengampu`
  MODIFY `kode` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ruang`
--
ALTER TABLE `ruang`
  MODIFY `kode` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `waktu_tidak_bersedia`
--
ALTER TABLE `waktu_tidak_bersedia`
  MODIFY `kode` int(10) NOT NULL AUTO_INCREMENT;
