<?php

date_default_timezone_set('Asia/Jakarta');

define('BASE_URL', 'http://localhost/siakad/');

define('NAMA_APLIKASI', 'Sistem Informasi Akademik');

define('NAMA_KAMPUS', 'Universitas Indraprasta PGRI');

define('VERSI', '1.0');

define('UPLOAD_MAHASISWA', '../assets/uploads/mahasiswa/');

define('UPLOAD_DOSEN', '../assets/uploads/dosen/');

/*
=========================================
Koneksi Database
=========================================
*/

require_once __DIR__ . '/koneksi.php';

?>