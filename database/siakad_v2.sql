-- Disable foreign key checks for dropping tables
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `pengaturan`;
DROP TABLE IF EXISTS `biaya_kuliah`;
DROP TABLE IF EXISTS `bobot_nilai`;
DROP TABLE IF EXISTS `nilai`;
DROP TABLE IF EXISTS `peserta_kelas`;
DROP TABLE IF EXISTS `kelas_perkuliahan`;
DROP TABLE IF EXISTS `periode_akademik`;
DROP TABLE IF EXISTS `mata_kuliah`;
DROP TABLE IF EXISTS `mahasiswa`;
DROP TABLE IF EXISTS `dosen`;
DROP TABLE IF EXISTS `kelas`;
DROP TABLE IF EXISTS `prodi`;
DROP TABLE IF EXISTS `users`;

-- Drop old tables that are no longer used
DROP TABLE IF EXISTS `krs`;
DROP TABLE IF EXISTS `presensi`;
DROP TABLE IF EXISTS `pengumuman`;

SET FOREIGN_KEY_CHECKS = 1;

-- 1. users
CREATE TABLE `users` (
  `id_user` CHAR(36) PRIMARY KEY,
  `username` VARCHAR(50) UNIQUE NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `nama_lengkap` VARCHAR(100) NOT NULL,
  `foto` VARCHAR(255) DEFAULT 'default-user.png',
  `role` ENUM('admin','dosen','mahasiswa') NOT NULL,
  `status` ENUM('aktif','nonaktif') DEFAULT 'aktif',
  `last_login` DATETIME NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. prodi
CREATE TABLE `prodi` (
  `id_prodi` CHAR(36) PRIMARY KEY,
  `kode_prodi` VARCHAR(10) UNIQUE NOT NULL,
  `nama_prodi` VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. kelas
CREATE TABLE `kelas` (
  `id_kelas` CHAR(36) PRIMARY KEY,
  `id_prodi` CHAR(36) NOT NULL,
  `nama_kelas` VARCHAR(20) NOT NULL,
  UNIQUE KEY `uk_prodi_kelas` (`id_prodi`, `nama_kelas`),
  FOREIGN KEY (`id_prodi`) REFERENCES `prodi`(`id_prodi`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. dosen
CREATE TABLE `dosen` (
  `id_dosen` CHAR(36) PRIMARY KEY,
  `id_user` CHAR(36) UNIQUE NOT NULL,
  `nip` VARCHAR(30) UNIQUE NOT NULL,
  `jk` ENUM('L','P'),
  `email` VARCHAR(100),
  `telepon` VARCHAR(20),
  `alamat` TEXT,
  `id_prodi` CHAR(36) NOT NULL,
  `agama` ENUM('Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu', 'Lainnya') NULL,
  `profil_lengkap` BOOLEAN DEFAULT 0,
  FOREIGN KEY (`id_user`) REFERENCES `users`(`id_user`) ON DELETE CASCADE,
  FOREIGN KEY (`id_prodi`) REFERENCES `prodi`(`id_prodi`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. mahasiswa
CREATE TABLE `mahasiswa` (
  `id_mahasiswa` CHAR(36) PRIMARY KEY,
  `id_user` CHAR(36) UNIQUE NOT NULL,
  `npm` VARCHAR(30) UNIQUE NOT NULL,
  `jk` ENUM('L','P'),
  `tempat_lahir` VARCHAR(100),
  `tanggal_lahir` DATE,
  `alamat` TEXT,
  `email` VARCHAR(100),
  `telepon` VARCHAR(20),
  `angkatan` YEAR(4),
  `id_prodi` CHAR(36) NOT NULL,
  `id_kelas` CHAR(36) NOT NULL,
  `semester` INT DEFAULT 1,
  `status_akademik` ENUM('aktif','cuti','lulus','dropout') DEFAULT 'aktif',
  `agama` ENUM('Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu', 'Lainnya') NULL,
  `profil_lengkap` BOOLEAN DEFAULT 0,
  FOREIGN KEY (`id_user`) REFERENCES `users`(`id_user`) ON DELETE CASCADE,
  FOREIGN KEY (`id_prodi`) REFERENCES `prodi`(`id_prodi`) ON DELETE CASCADE,
  FOREIGN KEY (`id_kelas`) REFERENCES `kelas`(`id_kelas`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. mata_kuliah
CREATE TABLE `mata_kuliah` (
  `id_matkul` CHAR(36) PRIMARY KEY,
  `id_prodi` CHAR(36) NOT NULL,
  `kode_matkul` VARCHAR(20) UNIQUE NOT NULL,
  `nama_matkul` VARCHAR(100) NOT NULL,
  `sks` INT NOT NULL,
  FOREIGN KEY (`id_prodi`) REFERENCES `prodi`(`id_prodi`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. periode_akademik
CREATE TABLE `periode_akademik` (
  `id_periode` CHAR(36) PRIMARY KEY,
  `nama_periode` VARCHAR(20) NOT NULL,
  `jenis_semester` ENUM('Ganjil','Genap') NOT NULL,
  `tanggal_mulai` DATE NOT NULL,
  `tanggal_selesai` DATE NOT NULL,
  `status` ENUM('aktif','nonaktif') DEFAULT 'nonaktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. kelas_perkuliahan
CREATE TABLE `kelas_perkuliahan` (
  `id_kelas_perkuliahan` CHAR(36) PRIMARY KEY,
  `id_matkul` CHAR(36) NOT NULL,
  `id_dosen` CHAR(36) NOT NULL,
  `id_kelas` CHAR(36) NOT NULL,
  `id_periode` CHAR(36) NOT NULL,
  `hari` VARCHAR(20),
  `jam_mulai` TIME,
  `jam_selesai` TIME,
  `ruangan` VARCHAR(50),
  FOREIGN KEY (`id_matkul`) REFERENCES `mata_kuliah`(`id_matkul`) ON DELETE CASCADE,
  FOREIGN KEY (`id_dosen`) REFERENCES `dosen`(`id_dosen`) ON DELETE CASCADE,
  FOREIGN KEY (`id_kelas`) REFERENCES `kelas`(`id_kelas`) ON DELETE CASCADE,
  FOREIGN KEY (`id_periode`) REFERENCES `periode_akademik`(`id_periode`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. peserta_kelas
CREATE TABLE `peserta_kelas` (
  `id_peserta_kelas` CHAR(36) PRIMARY KEY,
  `id_kelas_perkuliahan` CHAR(36) NOT NULL,
  `id_mahasiswa` CHAR(36) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `uk_kelas_mahasiswa` (`id_kelas_perkuliahan`, `id_mahasiswa`),
  FOREIGN KEY (`id_kelas_perkuliahan`) REFERENCES `kelas_perkuliahan`(`id_kelas_perkuliahan`) ON DELETE CASCADE,
  FOREIGN KEY (`id_mahasiswa`) REFERENCES `mahasiswa`(`id_mahasiswa`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. nilai
CREATE TABLE `nilai` (
  `id_nilai` CHAR(36) PRIMARY KEY,
  `id_peserta_kelas` CHAR(36) UNIQUE NOT NULL,
  `tugas` DECIMAL(5,2) NULL,
  `uts` DECIMAL(5,2) NULL,
  `uas` DECIMAL(5,2) NULL,
  `nilai_akhir` DECIMAL(5,2) NULL,
  `nilai_huruf` CHAR(2) NULL,
  FOREIGN KEY (`id_peserta_kelas`) REFERENCES `peserta_kelas`(`id_peserta_kelas`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. bobot_nilai
CREATE TABLE `bobot_nilai` (
  `nilai_huruf` CHAR(2) PRIMARY KEY,
  `bobot` DECIMAL(3,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12. biaya_kuliah
CREATE TABLE `biaya_kuliah` (
  `id_biaya` CHAR(36) PRIMARY KEY,
  `id_mahasiswa` CHAR(36) NOT NULL,
  `id_periode` CHAR(36) NOT NULL,
  `jumlah` DECIMAL(12,2) NOT NULL,
  `status` ENUM('Lunas','Belum Lunas') DEFAULT 'Belum Lunas',
  `tanggal_bayar` DATE NULL,
  UNIQUE KEY `uk_mahasiswa_periode` (`id_mahasiswa`, `id_periode`),
  FOREIGN KEY (`id_mahasiswa`) REFERENCES `mahasiswa`(`id_mahasiswa`) ON DELETE CASCADE,
  FOREIGN KEY (`id_periode`) REFERENCES `periode_akademik`(`id_periode`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 13. pengaturan
CREATE TABLE `pengaturan` (
  `key` VARCHAR(50) PRIMARY KEY,
  `value` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed data generated from generate_uuid_seed.php

INSERT INTO prodi (id_prodi, kode_prodi, nama_prodi) VALUES 
('5a282315-8f05-498b-8227-2ead70c2cd5c', 'TI', 'Teknik Informatika'),
('9fe8b17b-9784-4eec-b2f6-b04573d4bd8f', 'SI', 'Sistem Informasi');

INSERT INTO kelas (id_kelas, id_prodi, nama_kelas) VALUES 
('6dd2c7df-ab21-4eba-9500-7d7bf635619e', '5a282315-8f05-498b-8227-2ead70c2cd5c', 'TI-A'),
('9c07b776-af52-459b-b13c-7e1388276802', '9fe8b17b-9784-4eec-b2f6-b04573d4bd8f', 'SI-A');

INSERT INTO users (id_user, username, password_hash, nama_lengkap, role, status) VALUES 
/* Password: admin123 */
('cbc36218-bdb2-4972-8038-5899b892160b', 'admin', '$2y$10$.zIHYme/Ci7F9pW59fNFA.UlGaETMUU/U3v.V8qKLxjq2ZD5tT1ca', 'Administrator', 'admin', 'aktif'),
/* Password: 1234567890 */
('97f80433-6935-4c58-9b19-fdea3995fd86', '1234567890', '$2y$10$VyfPSILQ3yrZVGTkODwdPuXQRkCZXHowCpI52YE9tqJaa8y0d7LOu', 'Budi Dosen', 'dosen', 'aktif'),
/* Password: 20260001 */
('2f9347d4-09bc-4d7c-b997-0cef14618c6c', '20260001', '$2y$10$FkTMOFeGB0m5d8jJkGVLfuoS8kvdXxfCmXWJA.RsaxeXCkI70jyCi', 'Andi Mahasiswa', 'mahasiswa', 'aktif');

INSERT INTO dosen (id_dosen, id_user, nip, jk, email, id_prodi, profil_lengkap) VALUES 
('7da6c19b-10a8-4936-84d8-ce8ac0bda3c0', '97f80433-6935-4c58-9b19-fdea3995fd86', '1234567890', 'L', 'budi@kampus.ac.id', '5a282315-8f05-498b-8227-2ead70c2cd5c', 1);

INSERT INTO mahasiswa (id_mahasiswa, id_user, npm, jk, email, angkatan, id_prodi, id_kelas, semester, status_akademik, profil_lengkap) VALUES 
('c58420e9-8422-4fac-a08f-f0d6feb1b12e', '2f9347d4-09bc-4d7c-b997-0cef14618c6c', '20260001', 'L', 'andi@kampus.ac.id', '2026', '5a282315-8f05-498b-8227-2ead70c2cd5c', '6dd2c7df-ab21-4eba-9500-7d7bf635619e', 1, 'aktif', 1);

INSERT INTO mata_kuliah (id_matkul, id_prodi, kode_matkul, nama_matkul, sks) VALUES 
('85c71378-1406-4add-ac46-bd95412a582e', '5a282315-8f05-498b-8227-2ead70c2cd5c', 'MK001', 'Algoritma', 3),
('f839b4fd-7359-4f17-b35a-664ae741f935', '5a282315-8f05-498b-8227-2ead70c2cd5c', 'MK002', 'Basis Data', 3),
('c5bd6fca-baa4-49f4-9a0f-95d2d8b125fa', '9fe8b17b-9784-4eec-b2f6-b04573d4bd8f', 'MK003', 'Sistem Informasi Manajemen', 3);

INSERT INTO periode_akademik (id_periode, nama_periode, jenis_semester, tanggal_mulai, tanggal_selesai, status) VALUES 
('754d1d8c-65b5-44a6-a9d1-847a1a613edf', '2026/2027', 'Ganjil', '2026-08-01', '2027-01-31', 'aktif');

INSERT INTO bobot_nilai (nilai_huruf, bobot) VALUES 
('A', 4.00), ('A-', 3.70), ('B+', 3.30), ('B', 3.00), ('B-', 2.70), ('C+', 2.30), ('C', 2.00), ('C-', 1.70), ('D', 1.00);

INSERT INTO pengaturan (`key`, `value`) VALUES 
('biaya_kuliah_default', '1600000');
