<?php
require_once "../../config/session.php";
require_once "../../core/Guard.php";
requireRole(['admin']);
require_once "../../config/koneksi.php";

$id = $_GET['id'] ?? '';
$stmt = $pdo->prepare("
    SELECT d.*, u.nama_lengkap, p.nama_prodi 
    FROM dosen d 
    JOIN users u ON d.id_user = u.id_user
    JOIN prodi p ON d.id_prodi = p.id_prodi
    WHERE d.id_dosen = ?
");
$stmt->execute([$id]);
$dsn = $stmt->fetch();

if (!$dsn) {
    header("Location: index.php");
    exit();
}

$pageTitle = "Detail Dosen";
require_once "../../layout/header.php";
require_once "../../layout/sidebar.php";
require_once "../../layout/navbar.php";
?>
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Detail Dosen: <?= htmlspecialchars($dsn['nama_lengkap']) ?></h1>
    <div class="bg-white p-6 rounded shadow mb-4">
        <p><strong>NIP:</strong> <?= htmlspecialchars($dsn['nip']) ?></p>
        <p><strong>Prodi:</strong> <?= htmlspecialchars($dsn['nama_prodi']) ?></p>
        <p><strong>Email / Telp:</strong> <?= htmlspecialchars($dsn['email'] ?? '-') ?> / <?= htmlspecialchars($dsn['telepon'] ?? '-') ?></p>
        <p><strong>Alamat:</strong> <?= htmlspecialchars($dsn['alamat'] ?? '-') ?></p>
        <a href="index.php" class="text-blue-500 underline mt-4 block">Kembali ke Daftar</a>
    </div>
</div>
<?php require_once "../../layout/footer.php"; ?>