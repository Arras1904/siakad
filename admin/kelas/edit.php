<?php
require_once "../../config/session.php";
require_once "../../core/Guard.php";
requireRole(['admin']);
require_once "../../config/koneksi.php";

$id = $_GET['id'] ?? '';
$stmt = $pdo->prepare("SELECT * FROM kelas WHERE id_kelas = ?");
$stmt->execute([$id]);
$kelas = $stmt->fetch();

if (!$kelas) {
    header("Location: index.php");
    exit();
}

if (!isset($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

$prodi = $pdo->query("SELECT * FROM prodi ORDER BY nama_prodi ASC")->fetchAll();

$pageTitle = "Edit Kelas";
require_once "../../layout/header.php";
require_once "../../layout/sidebar.php";
require_once "../../layout/navbar.php";
?>
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Edit Kelas</h1>
    <form action="proses_edit.php" method="POST" class="bg-white p-6 rounded shadow max-w-md">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <input type="hidden" name="id_kelas" value="<?= htmlspecialchars($kelas['id_kelas']) ?>">
        
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Program Studi</label>
            <select name="id_prodi" class="w-full border p-2 rounded" required>
                <?php foreach($prodi as $p): ?>
                    <option value="<?= $p['id_prodi'] ?>" <?= ($p['id_prodi'] == $kelas['id_prodi']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($p['nama_prodi']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Nama Kelas</label>
            <input type="text" name="nama_kelas" class="w-full border p-2 rounded" value="<?= htmlspecialchars($kelas['nama_kelas']) ?>" required>
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan</button>
        <a href="index.php" class="ml-2 text-gray-600">Batal</a>
    </form>
</div>
<?php require_once "../../layout/footer.php"; ?>