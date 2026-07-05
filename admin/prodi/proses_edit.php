<?php
require_once "../../config/session.php";
require_once "../../core/Guard.php";
requireRole(['admin']);
require_once "../../config/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) die("CSRF Token Invalid");

    $id = $_POST['id_prodi'];
    $kode = trim($_POST['kode_prodi']);
    $nama = trim($_POST['nama_prodi']);

    try {
        $stmt = $pdo->prepare("UPDATE prodi SET kode_prodi = ?, nama_prodi = ? WHERE id_prodi = ?");
        $stmt->execute([$kode, $nama, $id]);
        $_SESSION['success'] = "Data prodi berhasil diupdate.";
    } catch (\PDOException $e) {
        $_SESSION['error'] = "Gagal mengupdate data: " . $e->getMessage();
        header("Location: edit.php?id=" . $id);
        exit();
    }
}
header("Location: index.php");