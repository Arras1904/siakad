<?php
require_once "../../config/session.php";
require_once "../../core/Guard.php";
requireRole(['admin']);
require_once "../../config/koneksi.php";

$pageTitle = "Mata Kuliah";
require_once "../../layout/header.php";
require_once "../../layout/sidebar.php";
require_once "../../layout/navbar.php";

$matkul = $pdo->query("
    SELECT m.*, p.nama_prodi 
    FROM mata_kuliah m
    JOIN prodi p ON m.id_prodi = p.id_prodi
    ORDER BY p.nama_prodi ASC, m.nama_matkul ASC
")->fetchAll();
?>
<div class="p-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Data Mata Kuliah</h1>
        <a href="tambah.php" class="bg-blue-500 text-white px-4 py-2 rounded">Tambah Matkul</a>
    </div>
    
    <?php if(isset($_SESSION['success'])): ?>
        <div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-4">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <table class="min-w-full bg-white border">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">Kode</th>
                <th class="py-2 px-4 border-b">Mata Kuliah</th>
                <th class="py-2 px-4 border-b">SKS</th>
                <th class="py-2 px-4 border-b">Prodi</th>
                <th class="py-2 px-4 border-b">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($matkul as $m): ?>
            <tr>
                <td class="py-2 px-4 border-b text-center"><?= htmlspecialchars($m['kode_matkul']) ?></td>
                <td class="py-2 px-4 border-b"><?= htmlspecialchars($m['nama_matkul']) ?></td>
                <td class="py-2 px-4 border-b text-center"><?= $m['sks'] ?></td>
                <td class="py-2 px-4 border-b text-center"><?= htmlspecialchars($m['nama_prodi']) ?></td>
                <td class="py-2 px-4 border-b text-center">
                    <a href="edit.php?id=<?= $m['id_matkul'] ?>" class="text-yellow-500">Edit</a> |
                    <a href="hapus.php?id=<?= $m['id_matkul'] ?>" class="text-red-500" onclick="return confirm('Yakin hapus?')">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require_once "../../layout/footer.php"; ?>