<?php
require_once "../../config/session.php";
require_once "../../core/Guard.php";
requireRole(['admin']);
require_once "../../config/koneksi.php";

$pageTitle = "Manajemen Mahasiswa";
require_once "../../layout/header.php";
require_once "../../layout/sidebar.php";
require_once "../../layout/navbar.php";

$mahasiswa = $pdo->query("
    SELECT m.*, u.nama_lengkap, p.nama_prodi, k.nama_kelas 
    FROM mahasiswa m 
    JOIN users u ON m.id_user = u.id_user
    JOIN prodi p ON m.id_prodi = p.id_prodi
    JOIN kelas k ON m.id_kelas = k.id_kelas
    ORDER BY m.npm ASC
")->fetchAll();
?>
<div class="p-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Data Mahasiswa</h1>
        <a href="tambah.php" class="bg-blue-500 text-white px-4 py-2 rounded">Tambah Mahasiswa</a>
    </div>
    
    <?php if(isset($_SESSION['success'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <table class="min-w-full bg-white border">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">NPM</th>
                <th class="py-2 px-4 border-b">Nama</th>
                <th class="py-2 px-4 border-b">Prodi</th>
                <th class="py-2 px-4 border-b">Kelas</th>
                <th class="py-2 px-4 border-b">Smt</th>
                <th class="py-2 px-4 border-b">Status</th>
                <th class="py-2 px-4 border-b">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($mahasiswa as $m): ?>
            <tr>
                <td class="py-2 px-4 border-b text-center"><?= htmlspecialchars($m['npm']) ?></td>
                <td class="py-2 px-4 border-b"><?= htmlspecialchars($m['nama_lengkap']) ?></td>
                <td class="py-2 px-4 border-b"><?= htmlspecialchars($m['nama_prodi']) ?></td>
                <td class="py-2 px-4 border-b text-center"><?= htmlspecialchars($m['nama_kelas']) ?></td>
                <td class="py-2 px-4 border-b text-center"><?= $m['semester'] ?></td>
                <td class="py-2 px-4 border-b text-center"><?= $m['status_akademik'] ?></td>
                <td class="py-2 px-4 border-b text-center">
                    <a href="detail.php?id=<?= $m['id_mahasiswa'] ?>" class="text-blue-500">Detail</a> |
                    <a href="hapus.php?id=<?= $m['id_mahasiswa'] ?>" class="text-red-500" onclick="return confirm('Yakin hapus mahasiswa ini?')">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require_once "../../layout/footer.php"; ?>