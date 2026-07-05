<?php
require_once "../config/session.php";
require_once "../core/Guard.php";
requireRole(['dosen']);
require_once "../config/koneksi.php";

$pageTitle = "Data Kelas Perkuliahan";
require_once "../layout/header.php";
require_once "../layout/sidebar.php";
require_once "../layout/navbar.php";

$id_user = $_SESSION['user_id'];
$stmtD = $pdo->prepare("SELECT id_dosen FROM dosen WHERE id_user = ?");
$stmtD->execute([$id_user]);
$id_dosen = $stmtD->fetchColumn();

$jadwal = $pdo->prepare("
    SELECT kp.*, m.nama_matkul, k.nama_kelas
    FROM kelas_perkuliahan kp
    JOIN mata_kuliah m ON kp.id_matkul = m.id_matkul
    JOIN kelas k ON kp.id_kelas = k.id_kelas
    JOIN periode_akademik p ON kp.id_periode = p.id_periode
    WHERE kp.id_dosen = ? AND p.status = 'aktif'
    ORDER BY kp.hari ASC, kp.jam_mulai ASC
");
$jadwal->execute([$id_dosen]);
$listJadwal = $jadwal->fetchAll();
?>
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Kelas Perkuliahan Anda</h1>
    <table class="min-w-full bg-white border">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">Mata Kuliah</th>
                <th class="py-2 px-4 border-b">Kelas</th>
                <th class="py-2 px-4 border-b">Jadwal (Hari, Jam)</th>
                <th class="py-2 px-4 border-b">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($listJadwal as $j): ?>
            <tr>
                <td class="py-2 px-4 border-b"><?= htmlspecialchars($j['nama_matkul']) ?></td>
                <td class="py-2 px-4 border-b text-center"><?= htmlspecialchars($j['nama_kelas']) ?></td>
                <td class="py-2 px-4 border-b text-center"><?= $j['hari'] ?>, <?= $j['jam_mulai'] ?>-<?= $j['jam_selesai'] ?></td>
                <td class="py-2 px-4 border-b text-center">
                    <a href="detail.php?id=<?= $j['id_kelas_perkuliahan'] ?>" class="bg-blue-500 text-white px-3 py-1 rounded text-sm">Detail / Input Nilai</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require_once "../layout/footer.php"; ?>