<?php
require_once "../../config/session.php";
require_once "../../core/Guard.php";
requireRole(['admin']);
require_once "../../config/koneksi.php";

$id = $_GET['id'] ?? '';
$stmt = $pdo->prepare("
    SELECT kp.*, m.nama_matkul, d.nama_lengkap as dosen, k.nama_kelas 
    FROM kelas_perkuliahan kp
    JOIN mata_kuliah m ON kp.id_matkul = m.id_matkul
    JOIN dosen ds ON kp.id_dosen = ds.id_dosen
    JOIN users d ON ds.id_user = d.id_user
    JOIN kelas k ON kp.id_kelas = k.id_kelas
    WHERE kp.id_kelas_perkuliahan = ?
");
$stmt->execute([$id]);
$kp = $stmt->fetch();

if (!$kp) { header("Location: index.php"); exit(); }

$peserta = $pdo->query("SELECT p.id_peserta_kelas, m.npm, u.nama_lengkap FROM peserta_kelas p JOIN mahasiswa m ON p.id_mahasiswa = m.id_mahasiswa JOIN users u ON m.id_user = u.id_user WHERE p.id_kelas_perkuliahan = '$id' ORDER BY m.npm ASC")->fetchAll();

$pageTitle = "Detail Kelas";
require_once "../../layout/header.php";
require_once "../../layout/sidebar.php";
require_once "../../layout/navbar.php";
?>
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Detail Kelas Perkuliahan</h1>
    <div class="bg-white p-6 rounded shadow mb-4">
        <p><strong>Mata Kuliah:</strong> <?= htmlspecialchars($kp['nama_matkul']) ?></p>
        <p><strong>Dosen:</strong> <?= htmlspecialchars($kp['dosen']) ?></p>
        <p><strong>Kelas / Jadwal:</strong> <?= htmlspecialchars($kp['nama_kelas']) ?> (<?= $kp['hari'] ?>, <?= $kp['jam_mulai'] ?>-<?= $kp['jam_selesai'] ?>) di <?= htmlspecialchars($kp['ruangan']) ?></p>
    </div>

    <h2 class="text-xl font-bold mb-2">Daftar Peserta (<?= count($peserta) ?> Mahasiswa)</h2>
    <table class="min-w-full bg-white border">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">NPM</th>
                <th class="py-2 px-4 border-b">Nama Mahasiswa</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($peserta as $p): ?>
            <tr>
                <td class="py-2 px-4 border-b text-center"><?= htmlspecialchars($p['npm']) ?></td>
                <td class="py-2 px-4 border-b"><?= htmlspecialchars($p['nama_lengkap']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <br>
    <a href="index.php" class="text-blue-500 underline">Kembali</a>
</div>
<?php require_once "../../layout/footer.php"; ?>