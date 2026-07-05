<?php
require_once "../../config/session.php";
require_once "../../core/Guard.php";
requireRole(['admin']);
require_once "../../config/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) die("CSRF Token Invalid");
    try {
        $stmt = $pdo->prepare("UPDATE mata_kuliah SET id_prodi=?, kode_matkul=?, nama_matkul=?, sks=? WHERE id_matkul=?");
        $stmt->execute([$_POST['id_prodi'], $_POST['kode_matkul'], $_POST['nama_matkul'], $_POST['sks'], $_POST['id_matkul']]);
        $_SESSION['success'] = "Data matkul berhasil diupdate.";
    } catch (\PDOException $e) {
        $_SESSION['error'] = "Gagal mengupdate data.";
    }
}
header("Location: index.php");