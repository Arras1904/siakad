<?php
require_once "../../config/session.php";
require_once "../../core/Guard.php";
requireRole(['admin']);
require_once "../../core/Uuid.php";
require_once "../../config/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) die("CSRF Token Invalid");

    $id_prodi = $_POST['id_prodi'];
    $nama = trim($_POST['nama_kelas']);

    try {
        $stmt = $pdo->prepare("INSERT INTO kelas (id_kelas, id_prodi, nama_kelas) VALUES (?, ?, ?)");
        $stmt->execute([generateUuid(), $id_prodi, $nama]);
        $_SESSION['success'] = "Data kelas berhasil ditambahkan.";
        header("Location: index.php");
        exit();
    } catch (\PDOException $e) {
        $_SESSION['error'] = "Gagal menyimpan data: " . $e->getMessage();
        header("Location: tambah.php");
        exit();
    }
}
header("Location: index.php");