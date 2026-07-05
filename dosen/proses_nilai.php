<?php
require_once "../config/session.php";
require_once "../core/Guard.php";
requireRole(['dosen']);
require_once "../core/Uuid.php";
require_once "../config/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) die("CSRF Token Invalid");

    $id_kp = $_POST['id_kelas_perkuliahan'];
    $arr_tugas = $_POST['tugas'] ?? [];
    $arr_uts = $_POST['uts'] ?? [];
    $arr_uas = $_POST['uas'] ?? [];

    function getHuruf($na) {
        if ($na >= 90) return 'A';
        if ($na >= 80) return 'A-';
        if ($na >= 75) return 'B+';
        if ($na >= 70) return 'B';
        if ($na >= 65) return 'B-';
        if ($na >= 60) return 'C+';
        if ($na >= 55) return 'C';
        if ($na >= 50) return 'C-';
        return 'D';
    }

    try {
        $pdo->beginTransaction();
        
        $stmt = $pdo->prepare("
            INSERT INTO nilai (id_nilai, id_peserta_kelas, tugas, uts, uas, nilai_akhir, nilai_huruf) 
            VALUES (?, ?, ?, ?, ?, ?, ?) 
            ON DUPLICATE KEY UPDATE 
                tugas=VALUES(tugas), uts=VALUES(uts), uas=VALUES(uas), nilai_akhir=VALUES(nilai_akhir), nilai_huruf=VALUES(nilai_huruf)
        ");

        foreach ($arr_tugas as $id_peserta => $tugas) {
            $tugas = $tugas === '' ? null : (float)$tugas;
            $uts = $arr_uts[$id_peserta] === '' ? null : (float)$arr_uts[$id_peserta];
            $uas = $arr_uas[$id_peserta] === '' ? null : (float)$arr_uas[$id_peserta];
            
            if ($tugas !== null || $uts !== null || $uas !== null) {
                // If any value exists, we calculate. If a field is empty, treat as 0 for calculation, 
                // OR calculate average based only on entered ones? Request says: (tugas+uts+uas)/3.
                // Assuming empty = 0 for the average.
                $t = $tugas ?? 0;
                $ut = $uts ?? 0;
                $ua = $uas ?? 0;
                // Bobot: Tugas 20%, UTS 30%, UAS 50%
                $na = ($t * 0.20) + ($ut * 0.30) + ($ua * 0.50);
                $huruf = getHuruf($na);

                // Fetch existing id_nilai or generate new
                $cek = $pdo->prepare("SELECT id_nilai FROM nilai WHERE id_peserta_kelas = ?");
                $cek->execute([$id_peserta]);
                $existing = $cek->fetchColumn();
                $id_nilai = $existing ?: generateUuid();

                $stmt->execute([$id_nilai, $id_peserta, $tugas, $uts, $uas, $na, $huruf]);
            }
        }

        $pdo->commit();
        $_SESSION['success'] = "Nilai berhasil disimpan.";
        header("Location: detail.php?id=" . $id_kp);
        exit();
    } catch (\PDOException $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Gagal menyimpan nilai: " . $e->getMessage();
        header("Location: detail.php?id=" . $id_kp);
        exit();
    }
}
header("Location: jadwal.php");