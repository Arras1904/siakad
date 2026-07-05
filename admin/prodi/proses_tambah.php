<?php
require_once "../../config/session.php";
require_once "../../core/Guard.php";
requireRole(['admin']);
require_once "../../core/Uuid.php";
require_once "../../config/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF Token Invalid");
    }

    $kode = trim($_POST['kode_prodi']);
    $nama = trim($_POST['nama_prodi']);

    try {
        $stmt = $pdo->prepare("INSERT INTO prodi (id_prodi, kode_prodi, nama_prodi) VALUES (?, ?, ?)");
        $stmt->execute([generateUuid(), $kode, $nama]);
        $_SESSION['success'] = "Data prodi berhasil ditambahkan.";
        header("Location: index.php");
        exit();
    } catch (\PDOException $e) {
        $_SESSION['error'] = "Gagal menyimpan data: " . $e->getMessage();
        header("Location: tambah.php");
        exit();
    }
}
header("Location: index.php");
