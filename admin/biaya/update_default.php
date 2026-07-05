<?php
require_once "../../config/session.php";
require_once "../../core/Guard.php";
requireRole(['admin']);
require_once "../../config/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) die("CSRF Token Invalid");
    try {
        $stmt = $pdo->prepare("UPDATE pengaturan SET value=? WHERE `key`='biaya_kuliah_default'");
        $stmt->execute([$_POST['biaya_default']]);
        $_SESSION['success'] = "Default biaya kuliah berhasil diupdate.";
    } catch (\PDOException $e) {
        $_SESSION['error'] = "Gagal mengupdate.";
    }
}
header("Location: index.php");