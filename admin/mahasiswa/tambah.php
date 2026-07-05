<?php
require_once "../../config/session.php";
require_once "../../core/Guard.php";
requireRole(['admin']);
require_once "../../config/koneksi.php";

$pageTitle = "Tambah Mahasiswa";
require_once "../../layout/header.php";
require_once "../../layout/sidebar.php";
require_once "../../layout/navbar.php";

if (!isset($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

$prodi = $pdo->query("SELECT * FROM prodi ORDER BY nama_prodi ASC")->fetchAll();
$kelas = $pdo->query("SELECT * FROM kelas ORDER BY nama_kelas ASC")->fetchAll();
?>
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Tambah Mahasiswa Baru</h1>
    
    <?php if(isset($_SESSION['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <form action="proses_tambah.php" method="POST" class="bg-white p-6 rounded shadow max-w-md">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Nama Lengkap</label>
            <input type="text" name="nama_lengkap" class="w-full border p-2 rounded" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">NPM</label>
            <input type="text" name="npm" class="w-full border p-2 rounded" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Program Studi</label>
            <select name="id_prodi" class="w-full border p-2 rounded" required>
                <option value="">-- Pilih Prodi --</option>
                <?php foreach($prodi as $p): ?>
                    <option value="<?= $p['id_prodi'] ?>"><?= htmlspecialchars($p['nama_prodi']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Kelas</label>
            <select name="id_kelas" class="w-full border p-2 rounded" required>
                <option value="">-- Pilih Kelas --</option>
                <?php foreach($kelas as $k): ?>
                    <option value="<?= $k['id_kelas'] ?>"><?= htmlspecialchars($k['nama_kelas']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <p class="text-sm text-gray-500 mb-4">Data lainnya akan diisi sendiri oleh mahasiswa bersangkutan pada saat login pertama kali (menggunakan username: NPM, password: NPM).</p>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan</button>
        <a href="index.php" class="ml-2 text-gray-600">Batal</a>
    </form>
</div>
<?php require_once "../../layout/footer.php"; ?>