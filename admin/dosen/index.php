<?php
require_once "../../config/session.php";
require_once "../../core/Guard.php";
requireRole(['admin']);
require_once "../../config/koneksi.php";

$pageTitle = "Manajemen Dosen";
require_once "../../layout/header.php";
require_once "../../layout/sidebar.php";
require_once "../../layout/navbar.php";

$dosen = $pdo->query("
    SELECT d.*, u.nama_lengkap, p.nama_prodi 
    FROM dosen d 
    JOIN users u ON d.id_user = u.id_user
    JOIN prodi p ON d.id_prodi = p.id_prodi
    ORDER BY d.nip ASC
")->fetchAll();
?>
<div class="p-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Data Dosen</h1>
        <a href="tambah.php" class="bg-blue-500 text-white px-4 py-2 rounded">Tambah Dosen</a>
    </div>
    
    <?php if(isset($_SESSION['success'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <table class="min-w-full bg-white border">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">NIP</th>
                <th class="py-2 px-4 border-b">Nama</th>
                <th class="py-2 px-4 border-b">Prodi</th>
                <th class="py-2 px-4 border-b">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($dosen as $d): ?>
            <tr>
                <td class="py-2 px-4 border-b text-center"><?= htmlspecialchars($d['nip']) ?></td>
                <td class="py-2 px-4 border-b"><?= htmlspecialchars($d['nama_lengkap']) ?></td>
                <td class="py-2 px-4 border-b"><?= htmlspecialchars($d['nama_prodi']) ?></td>
                <td class="py-2 px-4 border-b text-center">
                    <a href="detail.php?id=<?= $d['id_dosen'] ?>" class="text-blue-500">Detail</a> |
                    <a href="hapus.php?id=<?= $d['id_dosen'] ?>" class="text-red-500" onclick="return confirm('Yakin hapus dosen ini?')">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require_once "../../layout/footer.php"; ?>