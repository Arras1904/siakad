<?php
require_once "../../config/session.php";
require_once "../../core/Guard.php";
requireRole(['admin']);
require_once "../../core/Uuid.php";
require_once "../../config/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) die("CSRF Token Invalid");

    $id_kp = generateUuid();
    $id_kelas = $_POST['id_kelas'];

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("INSERT INTO kelas_perkuliahan (id_kelas_perkuliahan, id_matkul, id_dosen, id_kelas, id_periode, hari, jam_mulai, jam_selesai, ruangan) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $id_kp, $_POST['id_matkul'], $_POST['id_dosen'], $id_kelas, $_POST['id_periode'], 
            $_POST['hari'], $_POST['jam_mulai'], $_POST['jam_selesai'], trim($_POST['ruangan'])
        ]);

        // Auto Populate Peserta Kelas
        $stmtMhs = $pdo->prepare("SELECT id_mahasiswa FROM mahasiswa WHERE id_kelas = ? AND status_akademik = 'aktif'");
        $stmtMhs->execute([$id_kelas]);
        $mahasiswa = $stmtMhs->fetchAll();

        $insertPeserta = $pdo->prepare("INSERT INTO peserta_kelas (id_peserta_kelas, id_kelas_perkuliahan, id_mahasiswa) VALUES (?, ?, ?)");
        $count = 0;
        foreach($mahasiswa as $m) {
            $insertPeserta->execute([generateUuid(), $id_kp, $m['id_mahasiswa']]);
            $count++;
        }

        $pdo->commit();
        $_SESSION['success'] = "Kelas Perkuliahan ditambahkan dan $count mahasiswa otomatis terdaftar.";
        header("Location: index.php");
        exit();
    } catch (\PDOException $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Gagal menyimpan data: " . $e->getMessage();
        header("Location: tambah.php");
        exit();
    }
}
header("Location: index.php");