<?php
require_once "../../config/session.php";
require_once "../../core/Guard.php";
requireRole(['admin']);
require_once "../../config/koneksi.php";

$pageTitle = "Manajemen Kelas";
require_once "../../layout/header.php";
require_once "../../layout/sidebar.php";
require_once "../../layout/navbar.php";

$kelas = $pdo->query("
    SELECT k.*, p.nama_prodi 
    FROM kelas k 
    JOIN prodi p ON k.id_prodi = p.id_prodi 
    ORDER BY p.nama_prodi ASC, k.nama_kelas ASC
")->fetchAll();
?>
<div class="p-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Data Kelas</h1>
        <a href="tambah.php" class="bg-blue-500 text-white px-4 py-2 rounded">Tambah Kelas</a>
    </div>
    
    <?php if(isset($_SESSION['success'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    <?php if(isset($_SESSION['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <table class="min-w-full bg-white border">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">No</th>
                <th class="py-2 px-4 border-b">Program Studi</th>
                <th class="py-2 px-4 border-b">Nama Kelas</th>
                <th class="py-2 px-4 border-b">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($kelas as $i => $k): ?>
            <tr>
                <td class="py-2 px-4 border-b text-center"><?= $i+1 ?></td>
                <td class="py-2 px-4 border-b"><?= htmlspecialchars($k['nama_prodi']) ?></td>
                <td class="py-2 px-4 border-b text-center"><?= htmlspecialchars($k['nama_kelas']) ?></td>
                <td class="py-2 px-4 border-b text-center">
                    <a href="edit.php?id=<?= $k['id_kelas'] ?>" class="text-yellow-500">Edit</a> | 
                    <a href="hapus.php?id=<?= $k['id_kelas'] ?>" class="text-red-500" onclick="return confirm('Yakin hapus?')">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require_once "../../layout/footer.php"; ?>