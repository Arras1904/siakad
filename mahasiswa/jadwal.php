<?php
require_once "../config/session.php";
require_once "../core/Guard.php";
requireRole(['mahasiswa']);
require_once "../config/koneksi.php";

$pageTitle = "Jadwal Kuliah";
require_once "../layout/header.php";
require_once "../layout/sidebar.php";
require_once "../layout/navbar.php";

$id_user = $_SESSION['user_id'];
$stmtM = $pdo->prepare("SELECT id_mahasiswa FROM mahasiswa WHERE id_user = ?");
$stmtM->execute([$id_user]);
$id_mahasiswa = $stmtM->fetchColumn();

// Get Jadwal Periode Aktif
$jadwal = $pdo->prepare("
    SELECT kp.*, m.nama_matkul, m.sks, d.nama_lengkap as dosen_nama, k.nama_kelas, pa.nama_periode
    FROM peserta_kelas pk
    JOIN kelas_perkuliahan kp ON pk.id_kelas_perkuliahan = kp.id_kelas_perkuliahan
    JOIN mata_kuliah m ON kp.id_matkul = m.id_matkul
    JOIN dosen ds ON kp.id_dosen = ds.id_dosen
    JOIN users d ON ds.id_user = d.id_user
    JOIN kelas k ON kp.id_kelas = k.id_kelas
    JOIN periode_akademik pa ON kp.id_periode = pa.id_periode
    WHERE pk.id_mahasiswa = ? AND pa.status = 'aktif'
    ORDER BY FIELD(kp.hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'), kp.jam_mulai ASC
");
$jadwal->execute([$id_mahasiswa]);
$listJadwal = $jadwal->fetchAll();
?>
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Jadwal Kuliah Anda (Periode Aktif)</h1>
    
    <?php if(empty($listJadwal)): ?>
        <div class="bg-white p-4 rounded shadow">Anda belum terdaftar di kelas manapun pada periode aktif ini.</div>
    <?php else: ?>
        <table class="min-w-full bg-white border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="py-2 px-4 border-b">Mata Kuliah</th>
                    <th class="py-2 px-4 border-b">SKS</th>
                    <th class="py-2 px-4 border-b">Dosen</th>
                    <th class="py-2 px-4 border-b">Kelas</th>
                    <th class="py-2 px-4 border-b">Jadwal</th>
                    <th class="py-2 px-4 border-b">Ruangan</th>
                </tr>
            </thead>
            <tbody>
                <?php $totalSks = 0; ?>
                <?php foreach($listJadwal as $j): ?>
                <?php $totalSks += $j['sks']; ?>
                <tr>
                    <td class="py-2 px-4 border-b"><?= htmlspecialchars($j['nama_matkul']) ?></td>
                    <td class="py-2 px-4 border-b text-center"><?= $j['sks'] ?></td>
                    <td class="py-2 px-4 border-b"><?= htmlspecialchars($j['dosen_nama']) ?></td>
                    <td class="py-2 px-4 border-b text-center"><?= htmlspecialchars($j['nama_kelas']) ?></td>
                    <td class="py-2 px-4 border-b text-center"><?= $j['hari'] ?>, <?= $j['jam_mulai'] ?>-<?= $j['jam_selesai'] ?></td>
                    <td class="py-2 px-4 border-b text-center"><?= htmlspecialchars($j['ruangan']) ?></td>
                </tr>
                <?php endforeach; ?>
                <tr class="bg-gray-50 font-bold">
                    <td class="py-2 px-4 border-b text-right">Total SKS:</td>
                    <td class="py-2 px-4 border-b text-center"><?= $totalSks ?></td>
                    <td colspan="4" class="py-2 px-4 border-b"></td>
                </tr>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<?php require_once "../layout/footer.php"; ?>