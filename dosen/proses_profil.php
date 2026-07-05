<?php
require_once "../config/session.php";
require_once "../core/Guard.php";
requireRole(['dosen']);
require_once "../config/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) die("CSRF Token Invalid");

    $id_user = $_SESSION['user_id'];
    $nama = trim($_POST['nama_lengkap']);
    $jk = $_POST['jk'];
    $email = trim($_POST['email']);
    $telepon = trim($_POST['telepon']);
    $alamat = trim($_POST['alamat']);
    $pass = $_POST['password'];

    try {
        $pdo->beginTransaction();

        if (!empty($pass)) {
            $hashed = password_hash($pass, PASSWORD_BCRYPT);
            $updUser = $pdo->prepare("UPDATE users SET nama_lengkap = ?, password_hash = ? WHERE id_user = ?");
            $updUser->execute([$nama, $hashed, $id_user]);
        } else {
            $updUser = $pdo->prepare("UPDATE users SET nama_lengkap = ? WHERE id_user = ?");
            $updUser->execute([$nama, $id_user]);
        }
        $_SESSION['nama'] = $nama;

        $updDsn = $pdo->prepare("UPDATE dosen SET jk=?, email=?, telepon=?, alamat=?, profil_lengkap=1 WHERE id_user=?");
        $updDsn->execute([$jk, $email, $telepon, $alamat, $id_user]);
        
        $_SESSION['profil_lengkap'] = 1;

        $pdo->commit();
        $_SESSION['success'] = "Profil berhasil diupdate.";
    } catch (\PDOException $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Gagal update profil: " . $e->getMessage();
    }
}
header("Location: profil.php");