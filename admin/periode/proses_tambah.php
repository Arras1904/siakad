<?php
require_once "../../config/session.php";
require_once "../../core/Guard.php";
requireRole(['admin']);
require_once "../../core/Uuid.php";
require_once "../../config/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) die("CSRF Token Invalid");

    $nama = trim($_POST['nama_periode']);
    $jenis = $_POST['jenis_semester'];
    $mulai = $_POST['tanggal_mulai'];
    $selesai = $_POST['tanggal_selesai'];
    $id_periode_baru = generateUuid();

    try {
        // Cek overlap tgl dengan yang sudah ada (hanya cek yg aktif)
        // Disini kita asumsikan validasi simpel, pastikan admin tau apa yg mereka lakukan.

        $pdo->beginTransaction();

        // 1. Nonaktifkan semua periode aktif
        $pdo->query("UPDATE periode_akademik SET status='nonaktif' WHERE status='aktif'");

        // 2. Insert periode baru status aktif
        $stmt = $pdo->prepare("INSERT INTO periode_akademik (id_periode, nama_periode, jenis_semester, tanggal_mulai, tanggal_selesai, status) VALUES (?, ?, ?, ?, ?, 'aktif')");
        $stmt->execute([$id_periode_baru, $nama, $jenis, $mulai, $selesai]);

        // 3. Naikkan semester mahasiswa aktif
        $pdo->query("UPDATE mahasiswa SET semester = semester + 1 WHERE status_akademik = 'aktif'");

        // 4. Generate tagihan baru
        $defaultBiaya = $pdo->query("SELECT value FROM pengaturan WHERE `key`='biaya_kuliah_default'")->fetchColumn();
        if(!$defaultBiaya) $defaultBiaya = 0;

        $mhs = $pdo->query("SELECT id_mahasiswa FROM mahasiswa WHERE status_akademik = 'aktif'")->fetchAll();
        $insBiaya = $pdo->prepare("INSERT INTO biaya_kuliah (id_biaya, id_mahasiswa, id_periode, jumlah, status) VALUES (?, ?, ?, ?, 'Belum Lunas')");
        foreach($mhs as $m) {
            $insBiaya->execute([generateUuid(), $m['id_mahasiswa'], $id_periode_baru, $defaultBiaya]);
        }

        $pdo->commit();
        $_SESSION['success'] = "Rollover berhasil. Periode baru aktif dan tagihan di-generate.";
        header("Location: index.php");
        exit();
    } catch (\PDOException $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Terjadi kesalahan saat rollover: " . $e->getMessage();
        header("Location: tambah.php");
        exit();
    }
}
header("Location: index.php");