<?php
require_once "../../config/session.php";
require_once "../../core/Guard.php";
requireRole(['admin']);
require_once "../../config/koneksi.php";

$id = $_GET['id'] ?? '';
$stmt = $pdo->prepare("
    SELECT m.*, u.nama_lengkap, u.foto, p.nama_prodi, k.nama_kelas 
    FROM mahasiswa m 
    JOIN users u ON m.id_user = u.id_user
    JOIN prodi p ON m.id_prodi = p.id_prodi
    JOIN kelas k ON m.id_kelas = k.id_kelas
    WHERE m.id_mahasiswa = ?
");
$stmt->execute([$id]);
$mhs = $stmt->fetch();

if (!$mhs) {
    header("Location: index.php");
    exit();
}

$pageTitle = "Detail Mahasiswa";
require_once "../../layout/header.php";
require_once "../../layout/sidebar.php";
require_once "../../layout/navbar.php";
?>
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Detail Mahasiswa: <?= htmlspecialchars($mhs['nama_lengkap']) ?></h1>
    <div class="bg-white p-6 rounded shadow mb-4">
        <p><strong>NPM:</strong> <?= htmlspecialchars($mhs['npm']) ?></p>
        <p><strong>Prodi / Kelas:</strong> <?= htmlspecialchars($mhs['nama_prodi']) ?> / <?= htmlspecialchars($mhs['nama_kelas']) ?></p>
        <p><strong>Semester / Status:</strong> <?= $mhs['semester'] ?> / <?= $mhs['status_akademik'] ?></p>
        <p><strong>Email / Telp:</strong> <?= htmlspecialchars($mhs['email'] ?? '-') ?> / <?= htmlspecialchars($mhs['telepon'] ?? '-') ?></p>
        <p><strong>Alamat:</strong> <?= htmlspecialchars($mhs['alamat'] ?? '-') ?></p>
        <a href="index.php" class="text-blue-500 underline mt-4 block">Kembali ke Daftar</a>
    </div>
</div>
<?php require_once "../../layout/footer.php"; ?>