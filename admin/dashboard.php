<?php
require_once "../config/session.php";
require_once "../core/Guard.php";
requireRole(['admin']);

$pageTitle = "Dashboard Admin";
require_once "../layout/header.php";
require_once "../layout/sidebar.php";
require_once "../layout/navbar.php";
require_once "../config/koneksi.php";

$totalMahasiswa = $pdo->query("SELECT COUNT(*) FROM mahasiswa")->fetchColumn();
$totalDosen = $pdo->query("SELECT COUNT(*) FROM dosen")->fetchColumn();
$totalProdi = $pdo->query("SELECT COUNT(*) FROM prodi")->fetchColumn();
$totalMatkul = $pdo->query("SELECT COUNT(*) FROM mata_kuliah")->fetchColumn();
$totalKelas = $pdo->query("SELECT COUNT(*) FROM kelas")->fetchColumn();
$totalJadwal = $pdo->query("
    SELECT COUNT(*) FROM kelas_perkuliahan kp 
    JOIN periode_akademik pa ON kp.id_periode = pa.id_periode 
    WHERE pa.status = 'aktif'
")->fetchColumn();

?>
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Dashboard Admin</h1>
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        <div class="bg-blue-100 p-4 rounded shadow">
            <h2 class="text-blue-800">Total Mahasiswa</h2>
            <p class="text-3xl font-bold"><?= $totalMahasiswa ?></p>
        </div>
        <div class="bg-green-100 p-4 rounded shadow">
            <h2 class="text-green-800">Total Dosen</h2>
            <p class="text-3xl font-bold"><?= $totalDosen ?></p>
        </div>
        <div class="bg-orange-100 p-4 rounded shadow">
            <h2 class="text-orange-800">Program Studi</h2>
            <p class="text-3xl font-bold"><?= $totalProdi ?></p>
        </div>
        <div class="bg-purple-100 p-4 rounded shadow">
            <h2 class="text-purple-800">Mata Kuliah</h2>
            <p class="text-3xl font-bold"><?= $totalMatkul ?></p>
        </div>
        <div class="bg-cyan-100 p-4 rounded shadow">
            <h2 class="text-cyan-800">Kelas</h2>
            <p class="text-3xl font-bold"><?= $totalKelas ?></p>
        </div>
        <div class="bg-red-100 p-4 rounded shadow">
            <h2 class="text-red-800">Kelas Perkuliahan (Aktif)</h2>
            <p class="text-3xl font-bold"><?= $totalJadwal ?></p>
        </div>
    </div>
</div>
<?php require_once "../layout/footer.php"; ?>
