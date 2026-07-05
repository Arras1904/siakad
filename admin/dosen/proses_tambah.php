<?php
require_once "../../config/session.php";
require_once "../../core/Guard.php";
requireRole(['admin']);
require_once "../../core/Uuid.php";
require_once "../../config/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) die("CSRF Token Invalid");

    $nama = trim($_POST['nama_lengkap']);
    $nip = trim($_POST['nip']);
    $id_prodi = $_POST['id_prodi'];

    $id_user = generateUuid();
    $id_dosen = generateUuid();
    $password_hash = password_hash($nip, PASSWORD_BCRYPT);

    try {
        $pdo->beginTransaction();

        $stmtUser = $pdo->prepare("INSERT INTO users (id_user, username, password_hash, nama_lengkap, role, status) VALUES (?, ?, ?, ?, 'dosen', 'aktif')");
        $stmtUser->execute([$id_user, $nip, $password_hash, $nama]);

        $stmtDosen = $pdo->prepare("INSERT INTO dosen (id_dosen, id_user, nip, id_prodi, profil_lengkap) VALUES (?, ?, ?, ?, 0)");
        $stmtDosen->execute([$id_dosen, $id_user, $nip, $id_prodi]);

        $pdo->commit();
        $_SESSION['success'] = "Data dosen berhasil ditambahkan.";
        header("Location: index.php");
        exit();
    } catch (\PDOException $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Gagal menyimpan data (NIP mungkin sudah ada): " . $e->getMessage();
        header("Location: tambah.php");
        exit();
    }
}
header("Location: index.php");