<?php
require_once "../../config/session.php";
require_once "../../core/Guard.php";
requireRole(['admin']);
require_once "../../core/Uuid.php";
require_once "../../config/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) die("CSRF Token Invalid");

    try {
        $stmt = $pdo->prepare("INSERT INTO mata_kuliah (id_matkul, id_prodi, kode_matkul, nama_matkul, sks) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([generateUuid(), $_POST['id_prodi'], $_POST['kode_matkul'], $_POST['nama_matkul'], $_POST['sks']]);
        $_SESSION['success'] = "Mata kuliah berhasil ditambahkan.";
        header("Location: index.php");
        exit();
    } catch (\PDOException $e) {
        $_SESSION['error'] = "Gagal menyimpan data (Kode mungkin duplikat).";
        header("Location: tambah.php");
        exit();
    }
}
header("Location: index.php");