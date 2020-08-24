-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 13 Agu 2020 pada 10.17
-- Versi server: 10.4.10-MariaDB
-- Versi PHP: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `penjadwalan`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `guru`
--

CREATE TABLE `guru` (
  `id_guru` int(10) NOT NULL,
  `NIP` varchar(50) DEFAULT NULL,
  `nama_guru` varchar(50) DEFAULT NULL,
  `alamat` varchar(50) DEFAULT NULL,
  `telp` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `guru`
--

INSERT INTO `guru` (`id_guru`, `NIP`, `nama_guru`, `alamat`, `telp`) VALUES
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
-- Struktur dari tabel `hari`
--

CREATE TABLE `hari` (
  `id_hari` int(10) NOT NULL,
  `nama_hari` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `hari`
--

INSERT INTO `hari` (`id_hari`, `nama_hari`) VALUES
(1, 'Senin'),
(2, 'Selasa'),
(3, 'Rabu'),
(4, 'Kamis'),
(5, 'Jum\'at');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jadwal`
--

CREATE TABLE `jadwal` (
  `id_jadwal` int(10) NOT NULL,
  `id_pengampu` int(10) DEFAULT NULL,
  `id_jam` int(10) DEFAULT NULL,
  `id_hari` int(10) DEFAULT NULL,
  `id_ruang` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jam`
--

CREATE TABLE `jam` (
  `id_jam` int(10) NOT NULL,
  `range_jam` varchar(50) DEFAULT NULL,
  `aktif` enum('Y','N') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `jam`
--

INSERT INTO `jam` (`id_jam`, `range_jam`, `aktif`) VALUES
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
-- Struktur dari tabel `mapel`
--

CREATE TABLE `mapel` (
  `id_mapel` int(10) NOT NULL,
  `nama_mapel` varchar(50) DEFAULT NULL,
  `semester` int(6) DEFAULT NULL,
  `aktif` enum('True','False') DEFAULT NULL,
  `sks` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `mapel`
--

INSERT INTO `mapel` (`id_mapel`, `nama_mapel`, `semester`, `aktif`, `sks`) VALUES
(1, 'Agama Islam', 6, 'True', 0),
(2, 'PKn', 2, 'True', 0),
(3, 'Bahasa Indonesia', 6, 'True', 0),
(4, 'Bahasa Inggris', 6, 'True', 0),
(5, 'Matematika Wajib', 6, 'True', 0),
(6, 'Fisika', 4, 'True', 0),
(7, 'Kimia', 4, 'True', 0),
(8, 'Biologi', 4, 'True', 0),
(9, 'Sejarah Indonesia', 6, 'True', 0),
(10, 'Geografi', 4, 'True', 0),
(11, 'Ekonomi', 4, 'True', 0),
(12, 'Sosiologi', 4, 'True', 0),
(13, 'Seni Rupa', 4, 'True', 0),
(14, 'Penjaskes', 6, 'True', 0),
(15, 'TIK', 6, 'False', 0),
(16, 'Bahasa Jawa', 6, 'True', 0),
(17, 'Bahasa Jerman', 6, 'True', 0),
(18, 'Bahasa Jepang', 6, 'True', 0),
(19, 'Seni Musik', 4, 'True', 0),
(20, 'BK', 6, 'True', 0),
(21, 'PKWU', 6, 'True', 0),
(22, 'Matematika Peminatan', 4, 'True', 0),
(23, 'Sejarah Peminatan', 4, 'True', 0),
(24, 'Sastra Inggris', 2, 'True', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengampu`
--

CREATE TABLE `pengampu` (
  `id_pengampu` int(10) NOT NULL,
  `id_mapel` int(10) DEFAULT NULL,
  `id_guru` int(10) DEFAULT NULL,
  `kelas` varchar(10) CHARACTER SET latin1 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `pengampu`
--

INSERT INTO `pengampu` (`id_pengampu`, `id_mapel`, `id_guru`, `kelas`) VALUES
(1, 6, 36, 'X-MIPA-1'),
(2, 22, 44, 'X-MIPA-1'),
(3, 4, 21, 'X-MIPA-1'),
(4, 25, 37, 'X-MIPA-1'),
(5, 5, 1, 'X-MIPA-1'),
(6, 1, 29, 'X-MIPA-1'),
(7, 11, 3, 'X-MIPA-1'),
(8, 9, 14, 'X-MIPA-1'),
(9, 21, 39, 'X-MIPA-1'),
(10, 3, 6, 'X-MIPA-1'),
(11, 14, 18, 'X-MIPA-1'),
(12, 19, 30, 'X-MIPA-1'),
(13, 2, 33, 'X-MIPA-1'),
(14, 8, 5, 'X-MIPA-1'),
(15, 7, 34, 'X-MIPA-1'),
(16, 16, 28, 'X-MIPA-1'),
(17, 20, 44, 'X-MIPA-1'),
(18, 3, 6, 'X-MIPA-2'),
(19, 20, 45, 'X-MIPA-2'),
(20, 21, 39, 'X-MIPA-2'),
(21, 7, 34, 'X-MIPA-2'),
(22, 14, 18, 'X-MIPA-2'),
(23, 6, 36, 'X-MIPA-2'),
(24, 2, 33, 'X-MIPA-2'),
(25, 1, 29, 'X-MIPA-2'),
(26, 9, 14, 'X-MIPA-2'),
(27, 5, 1, 'X-MIPA-2'),
(28, 8, 5, 'X-MIPA-2'),
(29, 16, 28, 'X-MIPA-2'),
(30, 25, 37, 'X-MIPA-2'),
(31, 22, 44, 'X-MIPA-2'),
(32, 11, 3, 'X-MIPA-2'),
(33, 4, 21, 'X-MIPA-2'),
(34, 19, 30, 'X-MIPA-2'),
(35, 5, 1, 'X-MIPA-3'),
(36, 7, 34, 'X-MIPA-3'),
(37, 8, 5, 'X-MIPA-3'),
(38, 22, 44, 'X-MIPA-3'),
(39, 10, 23, 'X-MIPA-3'),
(40, 1, 29, 'X-MIPA-3'),
(41, 3, 6, 'X-MIPA-3'),
(42, 25, 37, 'X-MIPA-3'),
(43, 14, 18, 'X-MIPA-3'),
(44, 9, 14, 'X-MIPA-3'),
(45, 6, 36, 'X-MIPA-3'),
(46, 19, 30, 'X-MIPA-3'),
(47, 2, 33, 'X-MIPA-3'),
(48, 20, 45, 'X-MIPA-3'),
(49, 4, 21, 'X-MIPA-3'),
(50, 21, 39, 'X-MIPA-3'),
(51, 8, 5, 'X-MIPA-4'),
(52, 25, 37, 'X-MIPA-4'),
(53, 10, 23, 'X-MIPA-4'),
(54, 3, 6, 'X-MIPA-4'),
(55, 16, 28, 'X-MIPA-4'),
(56, 5, 1, 'X-MIPA-4'),
(57, 1, 29, 'X-MIPA-4'),
(58, 6, 36, 'X-MIPA-4'),
(59, 14, 18, 'X-MIPA-4'),
(60, 22, 44, 'X-MIPA-4'),
(61, 4, 21, 'X-MIPA-4'),
(62, 21, 39, 'X-MIPA-4'),
(63, 2, 33, 'X-MIPA-4'),
(64, 7, 34, 'X-MIPA-4'),
(65, 20, 45, 'X-MIPA-4'),
(66, 19, 30, 'X-MIPA-4'),
(67, 9, 14, 'X-MIPA-4'),
(68, 14, 18, 'X-IPS-1'),
(69, 5, 1, 'X-IPS-1'),
(70, 7, 34, 'X-IPS-1'),
(71, 24, 38, 'X-IPS-1'),
(72, 19, 38, 'X-IPS-1'),
(73, 19, 30, 'X-IPS-1'),
(74, 12, 4, 'X-IPS-1'),
(75, 1, 29, 'X-IPS-1'),
(76, 8, 5, 'X-IPS-1'),
(77, 10, 23, 'X-IPS-1'),
(78, 3, 6, 'X-IPS-1'),
(79, 11, 3, 'X-IPS-1'),
(80, 4, 21, 'X-IPS-1'),
(81, 16, 28, 'X-IPS-1'),
(82, 21, 39, 'X-IPS-1'),
(83, 20, 27, 'X-IPS-1'),
(84, 2, 33, 'X-IPS-1'),
(85, 9, 47, 'X-IPS-1'),
(86, 9, 47, 'X-IPS-2'),
(87, 3, 6, 'X-IPS-2'),
(88, 10, 2, 'X-IPS-2'),
(89, 1, 29, 'X-IPS-2'),
(90, 4, 21, 'X-IPS-2'),
(91, 14, 18, 'X-IPS-2'),
(92, 7, 34, 'X-IPS-2'),
(93, 5, 1, 'X-IPS-2'),
(94, 19, 30, 'X-IPS-2'),
(95, 16, 28, 'X-IPS-2'),
(96, 11, 3, 'X-IPS-2'),
(97, 24, 38, 'X-IPS-2'),
(98, 20, 27, 'X-IPS-2'),
(99, 12, 4, 'X-IPS-2'),
(100, 21, 39, 'X-IPS-2'),
(101, 2, 33, 'X-IPS-2'),
(102, 20, 27, 'X-IPS-3'),
(103, 7, 34, 'X-IPS-3'),
(104, 21, 39, 'X-IPS-3'),
(105, 11, 3, 'X-IPS-3'),
(106, 8, 5, 'X-IPS-3'),
(107, 3, 20, 'X-IPS-3'),
(108, 2, 31, 'X-IPS-3'),
(109, 2, 33, 'X-IPS-3'),
(110, 1, 29, 'X-IPS-3'),
(111, 24, 38, 'X-IPS-3'),
(112, 12, 4, 'X-IPS-3'),
(113, 5, 1, 'X-IPS-3'),
(114, 14, 18, 'X-IPS-3'),
(115, 10, 2, 'X-IPS-3'),
(116, 9, 47, 'X-IPS-3'),
(117, 4, 22, 'X-IPS-3'),
(118, 19, 30, 'X-IPS-3'),
(119, 16, 28, 'X-IPS-3'),
(120, 19, 30, 'X-IPS-4'),
(121, 11, 3, 'X-IPS-4'),
(122, 9, 47, 'X-IPS-4'),
(123, 7, 34, 'X-IPS-4'),
(124, 10, 2, 'X-IPS-4'),
(125, 2, 33, 'X-IPS-4'),
(126, 24, 38, 'X-IPS-4'),
(127, 12, 4, 'X-IPS-4'),
(128, 8, 7, 'X-IPS-4'),
(129, 14, 18, 'X-IPS-4'),
(130, 1, 29, 'X-IPS-4'),
(131, 5, 1, 'X-IPS-4'),
(132, 4, 21, 'X-IPS-4'),
(133, 3, 24, 'X-IPS-4'),
(134, 21, 39, 'X-IPS-4'),
(135, 16, 28, 'X-IPS-4'),
(136, 20, 27, 'X-IPS-4');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ruang`
--

CREATE TABLE `ruang` (
  `id_ruang` int(10) NOT NULL,
  `nama_ruang` varchar(50) DEFAULT NULL,
  `kapasitas` int(10) DEFAULT NULL,
  `jenis` enum('TEORI','LABORATORIUM') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `ruang`
--

INSERT INTO `ruang` (`id_ruang`, `nama_ruang`, `kapasitas`, `jenis`) VALUES
(1, 'X-MIPA-1', NULL, 'TEORI'),
(2, 'X-MIPA-2', NULL, 'TEORI'),
(3, 'X-MIPA-3', NULL, 'TEORI'),
(4, 'X-MIPA-4', NULL, 'TEORI'),
(5, 'X-IPS-1', NULL, 'TEORI'),
(6, 'X-IPS-2', NULL, 'TEORI'),
(7, 'X-IPS-3', NULL, 'TEORI'),
(8, 'X-IPS-4', NULL, 'TEORI'),
(9, 'XI-MIPA-1', NULL, 'TEORI'),
(10, 'XI-MIPA-2', NULL, 'TEORI'),
(11, 'XI-MIPA-3', NULL, 'TEORI'),
(12, 'XI-MIPA-4', NULL, 'TEORI'),
(13, 'XI-IPS-1', NULL, 'TEORI'),
(14, 'XI-IPS-2', NULL, 'TEORI'),
(15, 'XI-IPS-3', NULL, 'TEORI'),
(16, 'XI-IPS-4', NULL, 'TEORI'),
(17, 'XII-MIPA-1', NULL, 'TEORI'),
(18, 'XII-MIPA-2', NULL, 'TEORI'),
(19, 'XII-MIPA-3', NULL, 'TEORI'),
(20, 'XII-MIPA-4', NULL, 'TEORI'),
(21, 'XII-IPS-1', NULL, 'TEORI'),
(22, 'XII-IPS-2', NULL, 'TEORI'),
(23, 'XII-IPS-3', NULL, 'TEORI'),
(24, 'XII-IPS-4', NULL, 'TEORI'),
(25, 'Lab Fisika ', NULL, 'LABORATORIUM'),
(26, 'Lab Kimia', NULL, 'LABORATORIUM'),
(27, 'Lab Biologi', NULL, 'LABORATORIUM'),
(28, 'Lab Komputer', NULL, 'LABORATORIUM'),
(29, 'Lab Seni Musik', NULL, 'LABORATORIUM');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `pass` varchar(50) DEFAULT NULL,
  `level` enum('Y','N') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `waktu_tidak_bersedia`
--

CREATE TABLE `waktu_tidak_bersedia` (
  `id_waktu_tb` int(10) NOT NULL,
  `id_guru` int(10) DEFAULT NULL,
  `id_hari` int(10) DEFAULT NULL,
  `id_jam` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `guru`
--
ALTER TABLE `guru`
  ADD PRIMARY KEY (`id_guru`);

--
-- Indeks untuk tabel `hari`
--
ALTER TABLE `hari`
  ADD PRIMARY KEY (`id_hari`);

--
-- Indeks untuk tabel `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`id_jadwal`);

--
-- Indeks untuk tabel `jam`
--
ALTER TABLE `jam`
  ADD PRIMARY KEY (`id_jam`);

--
-- Indeks untuk tabel `mapel`
--
ALTER TABLE `mapel`
  ADD PRIMARY KEY (`id_mapel`);

--
-- Indeks untuk tabel `pengampu`
--
ALTER TABLE `pengampu`
  ADD PRIMARY KEY (`id_pengampu`);

--
-- Indeks untuk tabel `ruang`
--
ALTER TABLE `ruang`
  ADD PRIMARY KEY (`id_ruang`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- Indeks untuk tabel `waktu_tidak_bersedia`
--
ALTER TABLE `waktu_tidak_bersedia`
  ADD PRIMARY KEY (`id_waktu_tb`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `guru`
--
ALTER TABLE `guru`
  MODIFY `id_guru` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT untuk tabel `hari`
--
ALTER TABLE `hari`
  MODIFY `id_hari` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `jadwal`
--
ALTER TABLE `jadwal`
  MODIFY `id_jadwal` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jam`
--
ALTER TABLE `jam`
  MODIFY `id_jam` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `mapel`
--
ALTER TABLE `mapel`
  MODIFY `id_mapel` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT untuk tabel `pengampu`
--
ALTER TABLE `pengampu`
  MODIFY `id_pengampu` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=137;

--
-- AUTO_INCREMENT untuk tabel `ruang`
--
ALTER TABLE `ruang`
  MODIFY `id_ruang` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `waktu_tidak_bersedia`
--
ALTER TABLE `waktu_tidak_bersedia`
  MODIFY `id_waktu_tb` int(10) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
