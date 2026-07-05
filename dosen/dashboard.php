<?php
require_once "../config/session.php";
require_once "../core/Guard.php";
requireRole(['dosen']);
require_once "../config/koneksi.php";

$pageTitle = "Dashboard Dosen";
require_once "../layout/header.php";
require_once "../layout/sidebar.php";
require_once "../layout/navbar.php";

$id_user = $_SESSION['user_id'];
// Get id_dosen
$stmtD = $pdo->prepare("SELECT id_dosen FROM dosen WHERE id_user = ?");
$stmtD->execute([$id_user]);
$dosen = $stmtD->fetch();
$id_dosen = $dosen['id_dosen'];

// Get jadwal mengajar periode aktif
$jadwal = $pdo->prepare("
    SELECT kp.*, m.nama_matkul, k.nama_kelas, p.nama_periode
    FROM kelas_perkuliahan kp
    JOIN mata_kuliah m ON kp.id_matkul = m.id_matkul
    JOIN kelas k ON kp.id_kelas = k.id_kelas
    JOIN periode_akademik p ON kp.id_periode = p.id_periode
    WHERE kp.id_dosen = ? AND p.status = 'aktif'
    ORDER BY FIELD(kp.hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'), kp.jam_mulai ASC
");
$jadwal->execute([$id_dosen]);
$listJadwal = $jadwal->fetchAll();
?>
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Dashboard Dosen</h1>
    <div class="bg-blue-100 p-4 rounded shadow mb-6">
        <p class="text-blue-800">Selamat datang, <strong><?= htmlspecialchars($_SESSION['nama']) ?></strong>!</p>
        <p class="text-sm mt-1">Ini adalah halaman dashboard akademik Anda.</p>
    </div>

    <h2 class="text-xl font-bold mb-4">Jadwal Mengajar Anda (Periode Aktif)</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <?php if(empty($listJadwal)): ?>
            <div class="bg-white p-4 rounded shadow">Belum ada jadwal mengajar pada periode ini.</div>
        <?php else: ?>
            <?php foreach($listJadwal as $j): ?>
            <div class="bg-white p-4 rounded shadow border-l-4 border-blue-500">
                <h3 class="font-bold text-lg"><?= htmlspecialchars($j['nama_matkul']) ?></h3>
                <p class="text-sm text-gray-600">Kelas: <?= htmlspecialchars($j['nama_kelas']) ?></p>
                <p class="text-sm text-gray-600">Jadwal: <?= $j['hari'] ?>, <?= $j['jam_mulai'] ?> - <?= $j['jam_selesai'] ?></p>
                <p class="text-sm text-gray-600">Ruangan: <?= htmlspecialchars($j['ruangan']) ?></p>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<?php require_once "../layout/footer.php"; ?>