-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for siakad
DROP DATABASE IF EXISTS `siakad`;
CREATE DATABASE IF NOT EXISTS `siakad` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `siakad`;

-- Dumping structure for table siakad.biaya_kuliah
DROP TABLE IF EXISTS `biaya_kuliah`;
CREATE TABLE IF NOT EXISTS `biaya_kuliah` (
  `id_biaya` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_mahasiswa` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_periode` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah` decimal(12,2) NOT NULL,
  `status` enum('Lunas','Belum Lunas') COLLATE utf8mb4_unicode_ci DEFAULT 'Belum Lunas',
  `tanggal_bayar` date DEFAULT NULL,
  PRIMARY KEY (`id_biaya`),
  UNIQUE KEY `uk_mahasiswa_periode` (`id_mahasiswa`,`id_periode`),
  KEY `id_periode` (`id_periode`),
  CONSTRAINT `biaya_kuliah_ibfk_1` FOREIGN KEY (`id_mahasiswa`) REFERENCES `mahasiswa` (`id_mahasiswa`) ON DELETE CASCADE,
  CONSTRAINT `biaya_kuliah_ibfk_2` FOREIGN KEY (`id_periode`) REFERENCES `periode_akademik` (`id_periode`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table siakad.biaya_kuliah: ~0 rows (approximately)

-- Dumping structure for table siakad.bobot_nilai
DROP TABLE IF EXISTS `bobot_nilai`;
CREATE TABLE IF NOT EXISTS `bobot_nilai` (
  `nilai_huruf` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bobot` decimal(3,2) NOT NULL,
  PRIMARY KEY (`nilai_huruf`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table siakad.bobot_nilai: ~9 rows (approximately)
REPLACE INTO `bobot_nilai` (`nilai_huruf`, `bobot`) VALUES
	('A', 4.00),
	('A-', 3.70),
	('B', 3.00),
	('B-', 2.70),
	('B+', 3.30),
	('C', 2.00),
	('C-', 1.70),
	('C+', 2.30),
	('D', 1.00);

-- Dumping structure for table siakad.dosen
DROP TABLE IF EXISTS `dosen`;
CREATE TABLE IF NOT EXISTS `dosen` (
  `id_dosen` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_user` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nip` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jk` enum('L','P') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telepon` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `id_prodi` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `agama` enum('Islam','Kristen','Katolik','Hindu','Buddha','Konghucu','Lainnya') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profil_lengkap` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id_dosen`),
  UNIQUE KEY `id_user` (`id_user`),
  UNIQUE KEY `nip` (`nip`),
  KEY `id_prodi` (`id_prodi`),
  CONSTRAINT `dosen_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  CONSTRAINT `dosen_ibfk_2` FOREIGN KEY (`id_prodi`) REFERENCES `prodi` (`id_prodi`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table siakad.dosen: ~2 rows (approximately)
REPLACE INTO `dosen` (`id_dosen`, `id_user`, `nip`, `jk`, `email`, `telepon`, `alamat`, `id_prodi`, `agama`, `profil_lengkap`) VALUES
	('555f9bb1-e568-4a64-b81c-8cccf1964778', '93079986-8d9d-4fa6-98fd-8597513c6aef', '202343502269', 'L', 'Arrasyid1904@gmail.com', '087887448092', 'Jl. Hebat', '5a282315-8f05-498b-8227-2ead70c2cd5c', 'Islam', 1),
	('7da6c19b-10a8-4936-84d8-ce8ac0bda3c0', '97f80433-6935-4c58-9b19-fdea3995fd86', '1234567890', 'L', 'budi@kampus.ac.id', NULL, NULL, '5a282315-8f05-498b-8227-2ead70c2cd5c', NULL, 1);

-- Dumping structure for table siakad.kelas
DROP TABLE IF EXISTS `kelas`;
CREATE TABLE IF NOT EXISTS `kelas` (
  `id_kelas` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_prodi` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_kelas` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_kelas`),
  UNIQUE KEY `uk_prodi_kelas` (`id_prodi`,`nama_kelas`),
  CONSTRAINT `kelas_ibfk_1` FOREIGN KEY (`id_prodi`) REFERENCES `prodi` (`id_prodi`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table siakad.kelas: ~3 rows (approximately)
REPLACE INTO `kelas` (`id_kelas`, `id_prodi`, `nama_kelas`) VALUES
	('6dd2c7df-ab21-4eba-9500-7d7bf635619e', '5a282315-8f05-498b-8227-2ead70c2cd5c', 'TI-A'),
	('9c07b776-af52-459b-b13c-7e1388276802', '9fe8b17b-9784-4eec-b2f6-b04573d4bd8f', 'SI-A'),
	('d6ee0084-8acd-4631-95e4-1f790d111dde', 'a5999e91-d755-41ac-815f-3b61b8145b02', 'ILKOM-A');

-- Dumping structure for table siakad.kelas_perkuliahan
DROP TABLE IF EXISTS `kelas_perkuliahan`;
CREATE TABLE IF NOT EXISTS `kelas_perkuliahan` (
  `id_kelas_perkuliahan` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_matkul` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_dosen` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_kelas` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_periode` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hari` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jam_mulai` time DEFAULT NULL,
  `jam_selesai` time DEFAULT NULL,
  `ruangan` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_kelas_perkuliahan`),
  KEY `id_matkul` (`id_matkul`),
  KEY `id_dosen` (`id_dosen`),
  KEY `id_kelas` (`id_kelas`),
  KEY `id_periode` (`id_periode`),
  CONSTRAINT `kelas_perkuliahan_ibfk_1` FOREIGN KEY (`id_matkul`) REFERENCES `mata_kuliah` (`id_matkul`) ON DELETE CASCADE,
  CONSTRAINT `kelas_perkuliahan_ibfk_2` FOREIGN KEY (`id_dosen`) REFERENCES `dosen` (`id_dosen`) ON DELETE CASCADE,
  CONSTRAINT `kelas_perkuliahan_ibfk_3` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`) ON DELETE CASCADE,
  CONSTRAINT `kelas_perkuliahan_ibfk_4` FOREIGN KEY (`id_periode`) REFERENCES `periode_akademik` (`id_periode`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table siakad.kelas_perkuliahan: ~1 rows (approximately)
REPLACE INTO `kelas_perkuliahan` (`id_kelas_perkuliahan`, `id_matkul`, `id_dosen`, `id_kelas`, `id_periode`, `hari`, `jam_mulai`, `jam_selesai`, `ruangan`) VALUES
	('3491cf40-6b80-4ff6-8e5c-d52b237a9276', '9ae8abfd-33ce-4e54-b1e1-3ac2b811738c', '555f9bb1-e568-4a64-b81c-8cccf1964778', '6dd2c7df-ab21-4eba-9500-7d7bf635619e', '754d1d8c-65b5-44a6-a9d1-847a1a613edf', 'Kamis', '14:30:00', '17:00:00', '5.2.7');

-- Dumping structure for table siakad.mahasiswa
DROP TABLE IF EXISTS `mahasiswa`;
CREATE TABLE IF NOT EXISTS `mahasiswa` (
  `id_mahasiswa` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_user` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `npm` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jk` enum('L','P') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tempat_lahir` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telepon` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `angkatan` year DEFAULT NULL,
  `id_prodi` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_kelas` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `semester` int DEFAULT '1',
  `status_akademik` enum('aktif','cuti','lulus','dropout') COLLATE utf8mb4_unicode_ci DEFAULT 'aktif',
  `agama` enum('Islam','Kristen','Katolik','Hindu','Buddha','Konghucu','Lainnya') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profil_lengkap` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id_mahasiswa`),
  UNIQUE KEY `id_user` (`id_user`),
  UNIQUE KEY `npm` (`npm`),
  KEY `id_prodi` (`id_prodi`),
  KEY `id_kelas` (`id_kelas`),
  CONSTRAINT `mahasiswa_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  CONSTRAINT `mahasiswa_ibfk_2` FOREIGN KEY (`id_prodi`) REFERENCES `prodi` (`id_prodi`) ON DELETE CASCADE,
  CONSTRAINT `mahasiswa_ibfk_3` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table siakad.mahasiswa: ~2 rows (approximately)
REPLACE INTO `mahasiswa` (`id_mahasiswa`, `id_user`, `npm`, `jk`, `tempat_lahir`, `tanggal_lahir`, `alamat`, `email`, `telepon`, `angkatan`, `id_prodi`, `id_kelas`, `semester`, `status_akademik`, `agama`, `profil_lengkap`) VALUES
	('99ba4463-1622-4f58-b932-077a39331014', '2c5427ce-18aa-4f6a-ba25-33f025236c69', '202343502262', 'L', 'Jakarta', '2005-06-06', 'Jl. Jati', 'andra@gmail.com', '082124793703', '2023', '5a282315-8f05-498b-8227-2ead70c2cd5c', '6dd2c7df-ab21-4eba-9500-7d7bf635619e', 1, 'aktif', 'Islam', 1),
	('c58420e9-8422-4fac-a08f-f0d6feb1b12e', '2f9347d4-09bc-4d7c-b997-0cef14618c6c', '20260001', 'L', NULL, NULL, NULL, 'andi@kampus.ac.id', NULL, '2026', '5a282315-8f05-498b-8227-2ead70c2cd5c', '6dd2c7df-ab21-4eba-9500-7d7bf635619e', 1, 'aktif', NULL, 1);

-- Dumping structure for table siakad.mata_kuliah
DROP TABLE IF EXISTS `mata_kuliah`;
CREATE TABLE IF NOT EXISTS `mata_kuliah` (
  `id_matkul` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_prodi` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_matkul` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_matkul` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sks` int NOT NULL,
  PRIMARY KEY (`id_matkul`),
  UNIQUE KEY `kode_matkul` (`kode_matkul`),
  KEY `id_prodi` (`id_prodi`),
  CONSTRAINT `mata_kuliah_ibfk_1` FOREIGN KEY (`id_prodi`) REFERENCES `prodi` (`id_prodi`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table siakad.mata_kuliah: ~4 rows (approximately)
REPLACE INTO `mata_kuliah` (`id_matkul`, `id_prodi`, `kode_matkul`, `nama_matkul`, `sks`) VALUES
	('85c71378-1406-4add-ac46-bd95412a582e', '5a282315-8f05-498b-8227-2ead70c2cd5c', 'MK001', 'Algoritma', 3),
	('9ae8abfd-33ce-4e54-b1e1-3ac2b811738c', '5a282315-8f05-498b-8227-2ead70c2cd5c', 'PWL', 'Pemrograman Web Lanjut', 3),
	('c5bd6fca-baa4-49f4-9a0f-95d2d8b125fa', '9fe8b17b-9784-4eec-b2f6-b04573d4bd8f', 'MK003', 'Sistem Informasi Manajemen', 3),
	('f839b4fd-7359-4f17-b35a-664ae741f935', '5a282315-8f05-498b-8227-2ead70c2cd5c', 'MK002', 'Basis Data', 3);

-- Dumping structure for table siakad.nilai
DROP TABLE IF EXISTS `nilai`;
CREATE TABLE IF NOT EXISTS `nilai` (
  `id_nilai` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_peserta_kelas` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tugas` decimal(5,2) DEFAULT NULL,
  `uts` decimal(5,2) DEFAULT NULL,
  `uas` decimal(5,2) DEFAULT NULL,
  `nilai_akhir` decimal(5,2) DEFAULT NULL,
  `nilai_huruf` char(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_nilai`),
  UNIQUE KEY `id_peserta_kelas` (`id_peserta_kelas`),
  CONSTRAINT `nilai_ibfk_1` FOREIGN KEY (`id_peserta_kelas`) REFERENCES `peserta_kelas` (`id_peserta_kelas`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table siakad.nilai: ~2 rows (approximately)
REPLACE INTO `nilai` (`id_nilai`, `id_peserta_kelas`, `tugas`, `uts`, `uas`, `nilai_akhir`, `nilai_huruf`) VALUES
	('2673aa2e-ebaf-432f-b8d8-8641e69c6b73', '54aa30bf-a258-4098-963c-97388c28c812', 90.00, 80.00, 85.00, 84.50, 'A-'),
	('b8169676-2196-41b7-930d-df16591ac249', '20bcf426-34c0-4dd5-bb37-323e50757300', 30.00, 30.00, 30.00, 30.00, 'D');

-- Dumping structure for table siakad.pengaturan
DROP TABLE IF EXISTS `pengaturan`;
CREATE TABLE IF NOT EXISTS `pengaturan` (
  `key` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table siakad.pengaturan: ~1 rows (approximately)
REPLACE INTO `pengaturan` (`key`, `value`) VALUES
	('biaya_kuliah_default', '1600000');

-- Dumping structure for table siakad.periode_akademik
DROP TABLE IF EXISTS `periode_akademik`;
CREATE TABLE IF NOT EXISTS `periode_akademik` (
  `id_periode` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_periode` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_semester` enum('Ganjil','Genap') COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `status` enum('aktif','nonaktif') COLLATE utf8mb4_unicode_ci DEFAULT 'nonaktif',
  PRIMARY KEY (`id_periode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table siakad.periode_akademik: ~1 rows (approximately)
REPLACE INTO `periode_akademik` (`id_periode`, `nama_periode`, `jenis_semester`, `tanggal_mulai`, `tanggal_selesai`, `status`) VALUES
	('754d1d8c-65b5-44a6-a9d1-847a1a613edf', '2026/2027', 'Ganjil', '2026-08-01', '2027-01-31', 'aktif');

-- Dumping structure for table siakad.peserta_kelas
DROP TABLE IF EXISTS `peserta_kelas`;
CREATE TABLE IF NOT EXISTS `peserta_kelas` (
  `id_peserta_kelas` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_kelas_perkuliahan` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_mahasiswa` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_peserta_kelas`),
  UNIQUE KEY `uk_kelas_mahasiswa` (`id_kelas_perkuliahan`,`id_mahasiswa`),
  KEY `id_mahasiswa` (`id_mahasiswa`),
  CONSTRAINT `peserta_kelas_ibfk_1` FOREIGN KEY (`id_kelas_perkuliahan`) REFERENCES `kelas_perkuliahan` (`id_kelas_perkuliahan`) ON DELETE CASCADE,
  CONSTRAINT `peserta_kelas_ibfk_2` FOREIGN KEY (`id_mahasiswa`) REFERENCES `mahasiswa` (`id_mahasiswa`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table siakad.peserta_kelas: ~2 rows (approximately)
REPLACE INTO `peserta_kelas` (`id_peserta_kelas`, `id_kelas_perkuliahan`, `id_mahasiswa`, `created_at`) VALUES
	('20bcf426-34c0-4dd5-bb37-323e50757300', '3491cf40-6b80-4ff6-8e5c-d52b237a9276', 'c58420e9-8422-4fac-a08f-f0d6feb1b12e', '2026-07-05 14:51:43'),
	('54aa30bf-a258-4098-963c-97388c28c812', '3491cf40-6b80-4ff6-8e5c-d52b237a9276', '99ba4463-1622-4f58-b932-077a39331014', '2026-07-05 14:51:43');

-- Dumping structure for table siakad.prodi
DROP TABLE IF EXISTS `prodi`;
CREATE TABLE IF NOT EXISTS `prodi` (
  `id_prodi` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_prodi` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_prodi` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_prodi`),
  UNIQUE KEY `kode_prodi` (`kode_prodi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table siakad.prodi: ~3 rows (approximately)
REPLACE INTO `prodi` (`id_prodi`, `kode_prodi`, `nama_prodi`) VALUES
	('5a282315-8f05-498b-8227-2ead70c2cd5c', 'TI', 'Teknik Informatika'),
	('9fe8b17b-9784-4eec-b2f6-b04573d4bd8f', 'SI', 'Sistem Informasi'),
	('a5999e91-d755-41ac-815f-3b61b8145b02', 'ILKOM', 'Ilmu Komunikasi');

-- Dumping structure for table siakad.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id_user` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_lengkap` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'default-user.png',
  `role` enum('admin','dosen','mahasiswa') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('aktif','nonaktif') COLLATE utf8mb4_unicode_ci DEFAULT 'aktif',
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table siakad.users: ~5 rows (approximately)
REPLACE INTO `users` (`id_user`, `username`, `password_hash`, `nama_lengkap`, `foto`, `role`, `status`, `last_login`, `created_at`) VALUES
	('2c5427ce-18aa-4f6a-ba25-33f025236c69', '202343502262', '$2y$10$1e7FomHC6a8i5LBKM02vr.WztqavRcx.Ml8FRRsKI3fhAeLe7l87u', 'Andra Kurnia Cahya', 'default-user.png', 'mahasiswa', 'aktif', '2026-07-05 23:00:01', '2026-07-05 14:47:28'),
	('2f9347d4-09bc-4d7c-b997-0cef14618c6c', '20260001', '$2y$10$FkTMOFeGB0m5d8jJkGVLfuoS8kvdXxfCmXWJA.RsaxeXCkI70jyCi', 'Andi Mahasiswa', 'default-user.png', 'mahasiswa', 'aktif', '2026-07-05 21:58:04', '2026-07-05 14:36:00'),
	('93079986-8d9d-4fa6-98fd-8597513c6aef', '202343502269', '$2y$10$jwYyV8jCmCuFCIJYmmrndOaeIt8ilkCeoZHs9ARO3cjJonLcUayh6', 'Muhammad Arrasyid', '6a4a7e2485a2d.png', 'dosen', 'aktif', '2026-07-05 22:59:26', '2026-07-05 14:46:26'),
	('97f80433-6935-4c58-9b19-fdea3995fd86', '1234567890', '$2y$10$VyfPSILQ3yrZVGTkODwdPuXQRkCZXHowCpI52YE9tqJaa8y0d7LOu', 'Budi Dosen', 'default-user.png', 'dosen', 'aktif', '2026-07-05 22:36:24', '2026-07-05 14:36:00'),
	('cbc36218-bdb2-4972-8038-5899b892160b', 'admin', '$2y$10$.zIHYme/Ci7F9pW59fNFA.UlGaETMUU/U3v.V8qKLxjq2ZD5tT1ca', 'Administrator', 'default-user.png', 'admin', 'aktif', '2026-07-05 23:00:55', '2026-07-05 14:36:00');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
