<?php
require_once "../../config/session.php";
require_once "../../core/Guard.php";
requireRole(['admin']);
require_once "../../core/Uuid.php";
require_once "../../config/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) die("CSRF Token Invalid");

    $nama = trim($_POST['nama_lengkap']);
    $npm = trim($_POST['npm']);
    $id_prodi = $_POST['id_prodi'];
    $id_kelas = $_POST['id_kelas'];

    $id_user = generateUuid();
    $id_mhs = generateUuid();
    $password_hash = password_hash($npm, PASSWORD_BCRYPT);
    $angkatan = substr($npm, 0, 4); // asumsi sederhana 4 digit awal adalah tahun
    if(!is_numeric($angkatan) || strlen($angkatan) != 4) $angkatan = date('Y');

    try {
        $pdo->beginTransaction();

        $stmtUser = $pdo->prepare("INSERT INTO users (id_user, username, password_hash, nama_lengkap, role, status) VALUES (?, ?, ?, ?, 'mahasiswa', 'aktif')");
        $stmtUser->execute([$id_user, $npm, $password_hash, $nama]);

        $stmtMhs = $pdo->prepare("INSERT INTO mahasiswa (id_mahasiswa, id_user, npm, angkatan, id_prodi, id_kelas, semester, status_akademik, profil_lengkap) VALUES (?, ?, ?, ?, ?, ?, 1, 'aktif', 0)");
        $stmtMhs->execute([$id_mhs, $id_user, $npm, $angkatan, $id_prodi, $id_kelas]);

        $pdo->commit();
        $_SESSION['success'] = "Data mahasiswa berhasil ditambahkan.";
        header("Location: index.php");
        exit();
    } catch (\PDOException $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Gagal menyimpan data (NPM mungkin sudah ada): " . $e->getMessage();
        header("Location: tambah.php");
        exit();
    }
}
header("Location: index.php");