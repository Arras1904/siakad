<?php
require_once "../../config/session.php";
require_once "../../core/Guard.php";
requireRole(['admin']);
require_once "../../config/koneksi.php";

$pageTitle = "Periode Akademik";
require_once "../../layout/header.php";
require_once "../../layout/sidebar.php";
require_once "../../layout/navbar.php";

$periode = $pdo->query("SELECT * FROM periode_akademik ORDER BY tanggal_mulai DESC")->fetchAll();
?>
<div class="p-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Periode Akademik</h1>
        <a href="tambah.php" class="bg-blue-500 text-white px-4 py-2 rounded">Aktifkan Periode Baru (Rollover)</a>
    </div>
    
    <?php if(isset($_SESSION['success'])): ?>
        <div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-4"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <?php if(isset($_SESSION['error'])): ?>
        <div class="bg-red-100 text-red-700 px-4 py-3 rounded mb-4"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <table class="min-w-full bg-white border">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">Nama Periode</th>
                <th class="py-2 px-4 border-b">Jenis</th>
                <th class="py-2 px-4 border-b">Tanggal Mulai</th>
                <th class="py-2 px-4 border-b">Tanggal Selesai</th>
                <th class="py-2 px-4 border-b">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($periode as $p): ?>
            <tr class="<?= $p['status']=='aktif' ? 'bg-green-50' : '' ?>">
                <td class="py-2 px-4 border-b text-center"><?= htmlspecialchars($p['nama_periode']) ?></td>
                <td class="py-2 px-4 border-b text-center"><?= htmlspecialchars($p['jenis_semester']) ?></td>
                <td class="py-2 px-4 border-b text-center"><?= date('d-m-Y', strtotime($p['tanggal_mulai'])) ?></td>
                <td class="py-2 px-4 border-b text-center"><?= date('d-m-Y', strtotime($p['tanggal_selesai'])) ?></td>
                <td class="py-2 px-4 border-b text-center font-bold text-<?= $p['status']=='aktif'?'green':'gray' ?>-600">
                    <?= strtoupper($p['status']) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require_once "../../layout/footer.php"; ?>