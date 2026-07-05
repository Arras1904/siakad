-- MySQL dump 10.13  Distrib 8.4.3, for Win64 (x86_64)
--
-- Host: localhost    Database: siakad
-- ------------------------------------------------------
-- Server version	8.4.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `biaya_kuliah`
--

DROP TABLE IF EXISTS `biaya_kuliah`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `biaya_kuliah` (
  `id_biaya` char(36) NOT NULL,
  `id_mahasiswa` char(36) DEFAULT NULL,
  `id_periode` char(36) DEFAULT NULL,
  `jumlah` decimal(12,2) NOT NULL,
  `status` enum('Lunas','Belum Lunas') DEFAULT 'Belum Lunas',
  `tanggal_bayar` date DEFAULT NULL,
  PRIMARY KEY (`id_biaya`),
  UNIQUE KEY `id_mahasiswa` (`id_mahasiswa`,`id_periode`),
  KEY `id_periode` (`id_periode`),
  CONSTRAINT `biaya_kuliah_ibfk_1` FOREIGN KEY (`id_mahasiswa`) REFERENCES `mahasiswa` (`id_mahasiswa`) ON DELETE CASCADE,
  CONSTRAINT `biaya_kuliah_ibfk_2` FOREIGN KEY (`id_periode`) REFERENCES `periode_akademik` (`id_periode`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `biaya_kuliah`
--

LOCK TABLES `biaya_kuliah` WRITE;
/*!40000 ALTER TABLE `biaya_kuliah` DISABLE KEYS */;
/*!40000 ALTER TABLE `biaya_kuliah` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bobot_nilai`
--

DROP TABLE IF EXISTS `bobot_nilai`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bobot_nilai` (
  `nilai_huruf` char(2) NOT NULL,
  `bobot` decimal(3,2) NOT NULL,
  PRIMARY KEY (`nilai_huruf`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bobot_nilai`
--

LOCK TABLES `bobot_nilai` WRITE;
/*!40000 ALTER TABLE `bobot_nilai` DISABLE KEYS */;
INSERT INTO `bobot_nilai` VALUES ('A',4.00),('A-',3.70),('B',3.00),('B-',2.70),('B+',3.30),('C',2.00),('C-',1.70),('C+',2.30),('D',1.00);
/*!40000 ALTER TABLE `bobot_nilai` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dosen`
--

DROP TABLE IF EXISTS `dosen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dosen` (
  `id_dosen` char(36) NOT NULL,
  `id_user` char(36) DEFAULT NULL,
  `nip` varchar(30) NOT NULL,
  `jk` enum('L','P') DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `alamat` text,
  `id_prodi` char(36) DEFAULT NULL,
  `profil_lengkap` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id_dosen`),
  UNIQUE KEY `nip` (`nip`),
  UNIQUE KEY `id_user` (`id_user`),
  KEY `id_prodi` (`id_prodi`),
  CONSTRAINT `dosen_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  CONSTRAINT `dosen_ibfk_2` FOREIGN KEY (`id_prodi`) REFERENCES `prodi` (`id_prodi`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dosen`
--

LOCK TABLES `dosen` WRITE;
/*!40000 ALTER TABLE `dosen` DISABLE KEYS */;
INSERT INTO `dosen` VALUES ('6633c1ad-2cb6-47d7-a097-681840588494','e9bb124c-d2ed-43d4-b646-4eca0760c40a','1234567890','L',NULL,NULL,NULL,'114479bb-298c-4dbf-b102-f87d16b6aa02',1);
/*!40000 ALTER TABLE `dosen` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kelas`
--

DROP TABLE IF EXISTS `kelas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kelas` (
  `id_kelas` char(36) NOT NULL,
  `id_prodi` char(36) DEFAULT NULL,
  `nama_kelas` varchar(20) NOT NULL,
  PRIMARY KEY (`id_kelas`),
  UNIQUE KEY `id_prodi` (`id_prodi`,`nama_kelas`),
  CONSTRAINT `kelas_ibfk_1` FOREIGN KEY (`id_prodi`) REFERENCES `prodi` (`id_prodi`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kelas`
--

LOCK TABLES `kelas` WRITE;
/*!40000 ALTER TABLE `kelas` DISABLE KEYS */;
INSERT INTO `kelas` VALUES ('2d729719-4f5e-4b78-aba6-84556650fcf2','114479bb-298c-4dbf-b102-f87d16b6aa02','TI-1A'),('9711aed4-879d-4e52-8b69-b783a3416a1c','79467ec8-a26d-459a-8580-ba3907e499ac','SI-1A');
/*!40000 ALTER TABLE `kelas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kelas_perkuliahan`
--

DROP TABLE IF EXISTS `kelas_perkuliahan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kelas_perkuliahan` (
  `id_kelas_perkuliahan` char(36) NOT NULL,
  `id_matkul` char(36) DEFAULT NULL,
  `id_dosen` char(36) DEFAULT NULL,
  `id_kelas` char(36) DEFAULT NULL,
  `id_periode` char(36) DEFAULT NULL,
  `hari` varchar(20) DEFAULT NULL,
  `jam_mulai` time DEFAULT NULL,
  `jam_selesai` time DEFAULT NULL,
  `ruangan` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_kelas_perkuliahan`),
  KEY `id_matkul` (`id_matkul`),
  KEY `id_dosen` (`id_dosen`),
  KEY `id_kelas` (`id_kelas`),
  KEY `id_periode` (`id_periode`),
  CONSTRAINT `kelas_perkuliahan_ibfk_1` FOREIGN KEY (`id_matkul`) REFERENCES `mata_kuliah` (`id_matkul`) ON DELETE CASCADE,
  CONSTRAINT `kelas_perkuliahan_ibfk_2` FOREIGN KEY (`id_dosen`) REFERENCES `dosen` (`id_dosen`) ON DELETE SET NULL,
  CONSTRAINT `kelas_perkuliahan_ibfk_3` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`) ON DELETE CASCADE,
  CONSTRAINT `kelas_perkuliahan_ibfk_4` FOREIGN KEY (`id_periode`) REFERENCES `periode_akademik` (`id_periode`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kelas_perkuliahan`
--

LOCK TABLES `kelas_perkuliahan` WRITE;
/*!40000 ALTER TABLE `kelas_perkuliahan` DISABLE KEYS */;
/*!40000 ALTER TABLE `kelas_perkuliahan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mahasiswa`
--

DROP TABLE IF EXISTS `mahasiswa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mahasiswa` (
  `id_mahasiswa` char(36) NOT NULL,
  `id_user` char(36) DEFAULT NULL,
  `npm` varchar(30) NOT NULL,
  `jk` enum('L','P') DEFAULT NULL,
  `tempat_lahir` varchar(100) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `alamat` text,
  `email` varchar(100) DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `angkatan` year DEFAULT NULL,
  `id_prodi` char(36) DEFAULT NULL,
  `id_kelas` char(36) DEFAULT NULL,
  `semester` int DEFAULT '1',
  `status_akademik` enum('aktif','cuti','lulus','dropout') DEFAULT 'aktif',
  `profil_lengkap` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id_mahasiswa`),
  UNIQUE KEY `npm` (`npm`),
  UNIQUE KEY `id_user` (`id_user`),
  KEY `id_prodi` (`id_prodi`),
  KEY `id_kelas` (`id_kelas`),
  CONSTRAINT `mahasiswa_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  CONSTRAINT `mahasiswa_ibfk_2` FOREIGN KEY (`id_prodi`) REFERENCES `prodi` (`id_prodi`) ON DELETE SET NULL,
  CONSTRAINT `mahasiswa_ibfk_3` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mahasiswa`
--

LOCK TABLES `mahasiswa` WRITE;
/*!40000 ALTER TABLE `mahasiswa` DISABLE KEYS */;
INSERT INTO `mahasiswa` VALUES ('33127be4-5be0-4843-b82a-0b493dd12432','b84f06f5-d07c-4852-9c95-e7052920f4db','240001','L',NULL,NULL,NULL,NULL,NULL,2024,'114479bb-298c-4dbf-b102-f87d16b6aa02','2d729719-4f5e-4b78-aba6-84556650fcf2',1,'aktif',1);
/*!40000 ALTER TABLE `mahasiswa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mata_kuliah`
--

DROP TABLE IF EXISTS `mata_kuliah`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mata_kuliah` (
  `id_matkul` char(36) NOT NULL,
  `id_prodi` char(36) DEFAULT NULL,
  `kode_matkul` varchar(20) NOT NULL,
  `nama_matkul` varchar(100) NOT NULL,
  `sks` int NOT NULL,
  PRIMARY KEY (`id_matkul`),
  UNIQUE KEY `kode_matkul` (`kode_matkul`),
  KEY `id_prodi` (`id_prodi`),
  CONSTRAINT `mata_kuliah_ibfk_1` FOREIGN KEY (`id_prodi`) REFERENCES `prodi` (`id_prodi`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mata_kuliah`
--

LOCK TABLES `mata_kuliah` WRITE;
/*!40000 ALTER TABLE `mata_kuliah` DISABLE KEYS */;
INSERT INTO `mata_kuliah` VALUES ('03489d57-f911-412c-bc43-dc49c87bed82','114479bb-298c-4dbf-b102-f87d16b6aa02','TI102','Basis Data',3),('600aba85-c321-4373-a9b7-1f560a289ae6','114479bb-298c-4dbf-b102-f87d16b6aa02','TI101','Algoritma dan Pemrograman',3),('9789ddf1-29f7-4884-be2d-932a433376bc','79467ec8-a26d-459a-8580-ba3907e499ac','SI101','Pengantar Sistem Informasi',2);
/*!40000 ALTER TABLE `mata_kuliah` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `nilai`
--

DROP TABLE IF EXISTS `nilai`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `nilai` (
  `id_nilai` char(36) NOT NULL,
  `id_peserta_kelas` char(36) DEFAULT NULL,
  `tugas` decimal(5,2) DEFAULT NULL,
  `uts` decimal(5,2) DEFAULT NULL,
  `uas` decimal(5,2) DEFAULT NULL,
  `nilai_akhir` decimal(5,2) DEFAULT NULL,
  `nilai_huruf` char(2) DEFAULT NULL,
  PRIMARY KEY (`id_nilai`),
  UNIQUE KEY `id_peserta_kelas` (`id_peserta_kelas`),
  CONSTRAINT `nilai_ibfk_1` FOREIGN KEY (`id_peserta_kelas`) REFERENCES `peserta_kelas` (`id_peserta_kelas`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nilai`
--

LOCK TABLES `nilai` WRITE;
/*!40000 ALTER TABLE `nilai` DISABLE KEYS */;
/*!40000 ALTER TABLE `nilai` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pengaturan`
--

DROP TABLE IF EXISTS `pengaturan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pengaturan` (
  `key` varchar(50) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pengaturan`
--

LOCK TABLES `pengaturan` WRITE;
/*!40000 ALTER TABLE `pengaturan` DISABLE KEYS */;
INSERT INTO `pengaturan` VALUES ('biaya_kuliah_default','1600000');
/*!40000 ALTER TABLE `pengaturan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `periode_akademik`
--

DROP TABLE IF EXISTS `periode_akademik`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `periode_akademik` (
  `id_periode` char(36) NOT NULL,
  `nama_periode` varchar(20) NOT NULL,
  `jenis_semester` enum('Ganjil','Genap') NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'nonaktif',
  PRIMARY KEY (`id_periode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `periode_akademik`
--

LOCK TABLES `periode_akademik` WRITE;
/*!40000 ALTER TABLE `periode_akademik` DISABLE KEYS */;
INSERT INTO `periode_akademik` VALUES ('2e8b6465-c95c-4a90-930a-0cd50693c90e','2026/2027','Ganjil','2026-08-01','2026-12-31','aktif');
/*!40000 ALTER TABLE `periode_akademik` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `peserta_kelas`
--

DROP TABLE IF EXISTS `peserta_kelas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `peserta_kelas` (
  `id_peserta_kelas` char(36) NOT NULL,
  `id_kelas_perkuliahan` char(36) DEFAULT NULL,
  `id_mahasiswa` char(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_peserta_kelas`),
  UNIQUE KEY `id_kelas_perkuliahan` (`id_kelas_perkuliahan`,`id_mahasiswa`),
  KEY `id_mahasiswa` (`id_mahasiswa`),
  CONSTRAINT `peserta_kelas_ibfk_1` FOREIGN KEY (`id_kelas_perkuliahan`) REFERENCES `kelas_perkuliahan` (`id_kelas_perkuliahan`) ON DELETE CASCADE,
  CONSTRAINT `peserta_kelas_ibfk_2` FOREIGN KEY (`id_mahasiswa`) REFERENCES `mahasiswa` (`id_mahasiswa`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `peserta_kelas`
--

LOCK TABLES `peserta_kelas` WRITE;
/*!40000 ALTER TABLE `peserta_kelas` DISABLE KEYS */;
/*!40000 ALTER TABLE `peserta_kelas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prodi`
--

DROP TABLE IF EXISTS `prodi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prodi` (
  `id_prodi` char(36) NOT NULL,
  `kode_prodi` varchar(10) NOT NULL,
  `nama_prodi` varchar(100) NOT NULL,
  PRIMARY KEY (`id_prodi`),
  UNIQUE KEY `kode_prodi` (`kode_prodi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prodi`
--

LOCK TABLES `prodi` WRITE;
/*!40000 ALTER TABLE `prodi` DISABLE KEYS */;
INSERT INTO `prodi` VALUES ('114479bb-298c-4dbf-b102-f87d16b6aa02','TI','Teknik Informatika'),('79467ec8-a26d-459a-8580-ba3907e499ac','SI','Sistem Informasi');
/*!40000 ALTER TABLE `prodi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id_user` char(36) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `foto` varchar(255) DEFAULT 'default-user.png',
  `role` enum('admin','dosen','mahasiswa') NOT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES ('84bffbd0-a3e1-4dd1-a706-e604c2c97c2a','admin','$2y$10$FRWpIFfukgGXISYHxTiGjuNvmbxYuEyBn2DugX.pdzYbvkQ81CJYC','Administrator','default-user.png','admin','aktif',NULL,'2026-07-05 13:40:06'),('b84f06f5-d07c-4852-9c95-e7052920f4db','240001','$2y$10$M9MMjwB6u6MAZ.mUhZQNlOTHX512LIiz/FZbDAtEZ2udLWubf96de','Andi Mahasiswa','default-user.png','mahasiswa','aktif',NULL,'2026-07-05 13:40:06'),('e9bb124c-d2ed-43d4-b646-4eca0760c40a','1234567890','$2y$10$ohPhz5m92r.3GOqY35aurOQUH.mBRLQMYochDvbTmIflIphw1mjki','Budi Dosen','default-user.png','dosen','aktif',NULL,'2026-07-05 13:40:06');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-07-05 20:48:30
