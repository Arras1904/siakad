<?php
require_once "../../config/session.php";
require_once "../../core/Guard.php";
requireRole(['admin']);
require_once "../../config/koneksi.php";

$pageTitle = "Tambah Kelas Perkuliahan";
require_once "../../layout/header.php";
require_once "../../layout/sidebar.php";
require_once "../../layout/navbar.php";

if (!isset($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

$matkul = $pdo->query("SELECT * FROM mata_kuliah")->fetchAll();
$dosen = $pdo->query("SELECT d.id_dosen, u.nama_lengkap FROM dosen d JOIN users u ON d.id_user = u.id_user")->fetchAll();
$kelas = $pdo->query("SELECT * FROM kelas")->fetchAll();
$periode = $pdo->query("SELECT * FROM periode_akademik WHERE status='aktif'")->fetch();

if (!$periode) {
    echo "<div class='p-6'><div class='bg-red-100 text-red-700 p-4 rounded'>Tidak ada periode akademik yang aktif. Aktifkan terlebih dahulu di Manajemen Periode.</div></div>";
    require_once "../../layout/footer.php";
    exit();
}
?>
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Tambah Kelas Perkuliahan</h1>
    <form action="proses_tambah.php" method="POST" class="bg-white p-6 rounded shadow max-w-md">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <input type="hidden" name="id_periode" value="<?= $periode['id_periode'] ?>">
        
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Mata Kuliah</label>
            <select name="id_matkul" class="w-full border p-2 rounded" required>
                <?php foreach($matkul as $m): ?>
                    <option value="<?= $m['id_matkul'] ?>"><?= htmlspecialchars($m['nama_matkul']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Dosen Pengajar</label>
            <select name="id_dosen" class="w-full border p-2 rounded" required>
                <?php foreach($dosen as $d): ?>
                    <option value="<?= $d['id_dosen'] ?>"><?= htmlspecialchars($d['nama_lengkap']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Kelas</label>
            <select name="id_kelas" class="w-full border p-2 rounded" required>
                <?php foreach($kelas as $k): ?>
                    <option value="<?= $k['id_kelas'] ?>"><?= htmlspecialchars($k['nama_kelas']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Hari</label>
            <select name="hari" class="w-full border p-2 rounded" required>
                <option value="Senin">Senin</option><option value="Selasa">Selasa</option>
                <option value="Rabu">Rabu</option><option value="Kamis">Kamis</option>
                <option value="Jumat">Jumat</option><option value="Sabtu">Sabtu</option>
            </select>
        </div>
        <div class="flex gap-4 mb-4">
            <div class="w-1/2">
                <label class="block text-gray-700 mb-2">Jam Mulai</label>
                <input type="time" name="jam_mulai" class="w-full border p-2 rounded" required>
            </div>
            <div class="w-1/2">
                <label class="block text-gray-700 mb-2">Jam Selesai</label>
                <input type="time" name="jam_selesai" class="w-full border p-2 rounded" required>
            </div>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Ruangan</label>
            <input type="text" name="ruangan" class="w-full border p-2 rounded" required>
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan & Auto-Populate Peserta</button>
        <a href="index.php" class="ml-2 text-gray-600">Batal</a>
    </form>
</div>
<?php require_once "../../layout/footer.php"; ?>