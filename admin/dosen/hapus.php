<?php
require_once "../../config/session.php";
require_once "../../core/Guard.php";
requireRole(['admin']);
require_once "../../config/koneksi.php";

$id = $_GET['id'] ?? '';
if ($id) {
    try {
        $stmt = $pdo->prepare("SELECT id_user FROM dosen WHERE id_dosen = ?");
        $stmt->execute([$id]);
        $dsn = $stmt->fetch();
        
        if($dsn) {
            $del = $pdo->prepare("DELETE FROM users WHERE id_user = ?");
            $del->execute([$dsn['id_user']]);
            $_SESSION['success'] = "Data dosen berhasil dihapus.";
        }
    } catch (\PDOException $e) {
        $_SESSION['error'] = "Gagal menghapus data.";
    }
}
header("Location: index.php");