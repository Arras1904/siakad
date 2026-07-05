<?php
require_once "../../config/session.php";
require_once "../../core/Guard.php";
requireRole(['admin']);
require_once "../../config/koneksi.php";

$id = $_GET['id'] ?? '';
$stmt = $pdo->prepare("SELECT * FROM prodi WHERE id_prodi = ?");
$stmt->execute([$id]);
$prodi = $stmt->fetch();

if (!$prodi) {
    header("Location: index.php");
    exit();
}

if (!isset($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

$pageTitle = "Edit Prodi";
require_once "../../layout/header.php";
require_once "../../layout/sidebar.php";
require_once "../../layout/navbar.php";
?>
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Edit Program Studi</h1>
    
    <?php if(isset($_SESSION['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <form action="proses_edit.php" method="POST" class="bg-white p-6 rounded shadow max-w-md">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <input type="hidden" name="id_prodi" value="<?= htmlspecialchars($prodi['id_prodi']) ?>">
        
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Kode Prodi</label>
            <input type="text" name="kode_prodi" class="w-full border p-2 rounded" value="<?= htmlspecialchars($prodi['kode_prodi']) ?>" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Nama Prodi</label>
            <input type="text" name="nama_prodi" class="w-full border p-2 rounded" value="<?= htmlspecialchars($prodi['nama_prodi']) ?>" required>
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan</button>
        <a href="index.php" class="ml-2 text-gray-600">Batal</a>
    </form>
</div>
<?php require_once "../../layout/footer.php"; ?>