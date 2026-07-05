<?php
require_once "../../config/session.php";
require_once "../../core/Guard.php";
requireRole(['admin']);
require_once "../../config/koneksi.php";

$id = $_GET['id'] ?? '';
if ($id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM kelas WHERE id_kelas = ?");
        $stmt->execute([$id]);
        $_SESSION['success'] = "Data kelas berhasil dihapus.";
    } catch (\PDOException $e) {
        $_SESSION['error'] = "Gagal menghapus data (mungkin data sedang digunakan).";
    }
}
header("Location: index.php");