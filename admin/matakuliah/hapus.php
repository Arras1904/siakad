<?php
require_once "../../config/session.php";
require_once "../../core/Guard.php";
requireRole(['admin']);
require_once "../../config/koneksi.php";

$id = $_GET['id'] ?? '';
if ($id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM mata_kuliah WHERE id_matkul = ?");
        $stmt->execute([$id]);
        $_SESSION['success'] = "Mata Kuliah berhasil dihapus.";
    } catch (\PDOException $e) {
        $_SESSION['error'] = "Gagal menghapus data (Mungkin terpakai di kelas perkuliahan).";
    }
}
header("Location: index.php");