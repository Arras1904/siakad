<?php
require_once "../../config/session.php";
require_once "../../core/Guard.php";
requireRole(['admin']);
require_once "../../config/koneksi.php";

$pageTitle = "Kelas Perkuliahan";
require_once "../../layout/header.php";
require_once "../../layout/sidebar.php";
require_once "../../layout/navbar.php";

$jadwal = $pdo->query("
    SELECT kp.*, m.nama_matkul, d.nama_lengkap as dosen, k.nama_kelas, p.nama_periode
    FROM kelas_perkuliahan kp
    JOIN mata_kuliah m ON kp.id_matkul = m.id_matkul
    JOIN dosen ds ON kp.id_dosen = ds.id_dosen
    JOIN users d ON ds.id_user = d.id_user
    JOIN kelas k ON kp.id_kelas = k.id_kelas
    JOIN periode_akademik p ON kp.id_periode = p.id_periode
    WHERE p.status = 'aktif'
    ORDER BY kp.hari ASC, kp.jam_mulai ASC
")->fetchAll();
?>
<div class="p-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Data Kelas Perkuliahan (Periode Aktif)</h1>
        <a href="tambah.php" class="bg-blue-500 text-white px-4 py-2 rounded">Tambah Kelas Perkuliahan</a>
    </div>
    
    <?php if(isset($_SESSION['success'])): ?>
        <div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-4">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <table class="min-w-full bg-white border">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">Mata Kuliah</th>
                <th class="py-2 px-4 border-b">Dosen</th>
                <th class="py-2 px-4 border-b">Kelas</th>
                <th class="py-2 px-4 border-b">Jadwal (Hari, Jam)</th>
                <th class="py-2 px-4 border-b">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($jadwal as $j): ?>
            <tr>
                <td class="py-2 px-4 border-b"><?= htmlspecialchars($j['nama_matkul']) ?></td>
                <td class="py-2 px-4 border-b"><?= htmlspecialchars($j['dosen']) ?></td>
                <td class="py-2 px-4 border-b text-center"><?= htmlspecialchars($j['nama_kelas']) ?></td>
                <td class="py-2 px-4 border-b text-center"><?= htmlspecialchars($j['hari']) ?>, <?= $j['jam_mulai'] ?>-<?= $j['jam_selesai'] ?></td>
                <td class="py-2 px-4 border-b text-center">
                    <a href="detail.php?id=<?= $j['id_kelas_perkuliahan'] ?>" class="text-blue-500">Detail</a> |
                    <a href="hapus.php?id=<?= $j['id_kelas_perkuliahan'] ?>" class="text-red-500" onclick="return confirm('Yakin hapus?')">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require_once "../../layout/footer.php"; ?>