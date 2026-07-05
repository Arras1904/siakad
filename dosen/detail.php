<?php
require_once "../config/session.php";
require_once "../core/Guard.php";
requireRole(['dosen']);
require_once "../config/koneksi.php";

$id_kp = $_GET['id'] ?? '';
$id_user = $_SESSION['user_id'];
$id_dosen = $pdo->prepare("SELECT id_dosen FROM dosen WHERE id_user = ?");
$id_dosen->execute([$id_user]);
$id_dosen = $id_dosen->fetchColumn();

// Pastikan kelas ini milik dosen ybs
$cek = $pdo->prepare("SELECT kp.*, m.nama_matkul, k.nama_kelas FROM kelas_perkuliahan kp JOIN mata_kuliah m ON kp.id_matkul=m.id_matkul JOIN kelas k ON kp.id_kelas=k.id_kelas WHERE kp.id_kelas_perkuliahan = ? AND kp.id_dosen = ?");
$cek->execute([$id_kp, $id_dosen]);
$kp = $cek->fetch();
if(!$kp) { header("Location: jadwal.php"); exit(); }

if (!isset($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

$peserta = $pdo->prepare("
    SELECT p.id_peserta_kelas, m.npm, u.nama_lengkap, n.tugas, n.uts, n.uas, n.nilai_akhir, n.nilai_huruf
    FROM peserta_kelas p 
    JOIN mahasiswa m ON p.id_mahasiswa = m.id_mahasiswa 
    JOIN users u ON m.id_user = u.id_user
    LEFT JOIN nilai n ON p.id_peserta_kelas = n.id_peserta_kelas
    WHERE p.id_kelas_perkuliahan = ? 
    ORDER BY m.npm ASC
");
$peserta->execute([$id_kp]);
$listPeserta = $peserta->fetchAll();

$pageTitle = "Detail Kelas";
require_once "../layout/header.php";
require_once "../layout/sidebar.php";
require_once "../layout/navbar.php";
?>
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Kelas: <?= htmlspecialchars($kp['nama_matkul']) ?> (<?= htmlspecialchars($kp['nama_kelas']) ?>)</h1>
    
    <?php if(isset($_SESSION['success'])): ?>
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <?php if(isset($_SESSION['error'])): ?>
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <!-- Sub Navbar/Tabs -->
    <div class="flex gap-4 mb-4 border-b pb-2">
        <button id="btn-peserta" class="font-bold text-blue-600 pb-2 border-b-2 border-blue-600" onclick="switchTab('peserta')">Peserta Kelas</button>
        <button id="btn-nilai" class="text-gray-500 pb-2 border-b-2 border-transparent hover:text-blue-600" onclick="switchTab('nilai')">Input Nilai</button>
    </div>

    <div id="tab-peserta">
        <table class="min-w-full bg-white border">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">No</th>
                    <th class="py-2 px-4 border-b">NPM</th>
                    <th class="py-2 px-4 border-b">Nama Lengkap</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($listPeserta as $i => $p): ?>
                <tr>
                    <td class="py-2 px-4 border-b text-center"><?= $i+1 ?></td>
                    <td class="py-2 px-4 border-b text-center"><?= htmlspecialchars($p['npm']) ?></td>
                    <td class="py-2 px-4 border-b"><?= htmlspecialchars($p['nama_lengkap']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div id="tab-nilai" style="display:none;">
        <form action="proses_nilai.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <input type="hidden" name="id_kelas_perkuliahan" value="<?= htmlspecialchars($id_kp) ?>">
            <table class="min-w-full bg-white border mb-4">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b">NPM - Nama</th>
                        <th class="py-2 px-4 border-b">Tugas</th>
                        <th class="py-2 px-4 border-b">UTS</th>
                        <th class="py-2 px-4 border-b">UAS</th>
                        <th class="py-2 px-4 border-b">Nilai Akhir (Huruf)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($listPeserta as $p): ?>
                    <tr>
                        <td class="py-2 px-4 border-b">
                            <?= htmlspecialchars($p['npm']) ?> - <?= htmlspecialchars($p['nama_lengkap']) ?>
                        </td>
                        <td class="py-2 px-4 border-b text-center">
                            <input type="number" name="tugas[<?= $p['id_peserta_kelas'] ?>]" class="border p-1 w-20 text-center" value="<?= $p['tugas'] ?? '' ?>" min="0" max="100" step="0.01">
                        </td>
                        <td class="py-2 px-4 border-b text-center">
                            <input type="number" name="uts[<?= $p['id_peserta_kelas'] ?>]" class="border p-1 w-20 text-center" value="<?= $p['uts'] ?? '' ?>" min="0" max="100" step="0.01">
                        </td>
                        <td class="py-2 px-4 border-b text-center">
                            <input type="number" name="uas[<?= $p['id_peserta_kelas'] ?>]" class="border p-1 w-20 text-center" value="<?= $p['uas'] ?? '' ?>" min="0" max="100" step="0.01">
                        </td>
                        <td class="py-2 px-4 border-b text-center font-bold">
                            <?= $p['nilai_akhir'] !== null ? number_format($p['nilai_akhir'], 2) . " (" . $p['nilai_huruf'] . ")" : '-' ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan Nilai Semua</button>
        </form>
    </div>
</div>
<script>
function switchTab(tab) {
    if(tab === 'peserta') {
        document.getElementById('tab-peserta').style.display = 'block';
        document.getElementById('tab-nilai').style.display = 'none';
        document.getElementById('btn-peserta').className = 'font-bold text-blue-600 pb-2 border-b-2 border-blue-600';
        document.getElementById('btn-nilai').className = 'text-gray-500 pb-2 border-b-2 border-transparent hover:text-blue-600';
    } else {
        document.getElementById('tab-peserta').style.display = 'none';
        document.getElementById('tab-nilai').style.display = 'block';
        document.getElementById('btn-nilai').className = 'font-bold text-blue-600 pb-2 border-b-2 border-blue-600';
        document.getElementById('btn-peserta').className = 'text-gray-500 pb-2 border-b-2 border-transparent hover:text-blue-600';
    }
}
</script>
<?php require_once "../layout/footer.php"; ?>