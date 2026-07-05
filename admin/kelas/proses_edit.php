<?php
require_once "../../config/session.php";
require_once "../../core/Guard.php";
requireRole(['admin']);
require_once "../../config/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) die("CSRF Token Invalid");

    $id = $_POST['id_kelas'];
    $id_prodi = $_POST['id_prodi'];
    $nama = trim($_POST['nama_kelas']);

    try {
        $stmt = $pdo->prepare("UPDATE kelas SET id_prodi = ?, nama_kelas = ? WHERE id_kelas = ?");
        $stmt->execute([$id_prodi, $nama, $id]);
        $_SESSION['success'] = "Data kelas berhasil diupdate.";
    } catch (\PDOException $e) {
        $_SESSION['error'] = "Gagal mengupdate data: " . $e->getMessage();
        header("Location: edit.php?id=" . $id);
        exit();
    }
}
header("Location: index.php");