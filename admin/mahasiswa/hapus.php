<?php
require_once "../../config/session.php";
require_once "../../core/Guard.php";
requireRole(['admin']);
require_once "../../config/koneksi.php";

$id = $_GET['id'] ?? '';
if ($id) {
    try {
        // Ambil id_user
        $stmt = $pdo->prepare("SELECT id_user FROM mahasiswa WHERE id_mahasiswa = ?");
        $stmt->execute([$id]);
        $mhs = $stmt->fetch();
        
        if($mhs) {
            // Hapus user otomatis hapus mahasiswa krn cascade
            $del = $pdo->prepare("DELETE FROM users WHERE id_user = ?");
            $del->execute([$mhs['id_user']]);
            $_SESSION['success'] = "Data mahasiswa berhasil dihapus.";
        }
    } catch (\PDOException $e) {
        $_SESSION['error'] = "Gagal menghapus data.";
    }
}
header("Location: index.php");