<?php
require_once "../../config/session.php";
require_once "../../core/Guard.php";
requireRole(['admin']);

$pageTitle = "Manajemen Prodi";
require_once "../../layout/header.php";
require_once "../../layout/sidebar.php";
require_once "../../layout/navbar.php";
require_once "../../config/koneksi.php";

$prodi = $pdo->query("SELECT * FROM prodi ORDER BY kode_prodi ASC")->fetchAll();
?>
<div class="p-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Data Program Studi</h1>
        <a href="tambah.php" class="bg-blue-500 text-white px-4 py-2 rounded">Tambah Prodi</a>
    </div>
    
    <?php if(isset($_SESSION['success'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <table class="min-w-full bg-white border">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">No</th>
                <th class="py-2 px-4 border-b">Kode Prodi</th>
                <th class="py-2 px-4 border-b">Nama Prodi</th>
                <th class="py-2 px-4 border-b">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($prodi as $i => $p): ?>
            <tr>
                <td class="py-2 px-4 border-b text-center"><?= $i+1 ?></td>
                <td class="py-2 px-4 border-b text-center"><?= htmlspecialchars($p['kode_prodi']) ?></td>
                <td class="py-2 px-4 border-b"><?= htmlspecialchars($p['nama_prodi']) ?></td>
                <td class="py-2 px-4 border-b text-center">
                    <a href="edit.php?id=<?= $p['id_prodi'] ?>" class="text-yellow-500">Edit</a> | 
                    <a href="hapus.php?id=<?= $p['id_prodi'] ?>" class="text-red-500" onclick="return confirm('Yakin hapus?')">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require_once "../../layout/footer.php"; ?>
