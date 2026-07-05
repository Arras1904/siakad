<?php
require_once "../config/session.php";
require_once "../core/Guard.php";
requireRole(['mahasiswa']);
require_once "../config/koneksi.php";

$pageTitle = "Biaya Kuliah";
require_once "../layout/header.php";
require_once "../layout/sidebar.php";
require_once "../layout/navbar.php";

$id_user = $_SESSION['user_id'];
$stmtM = $pdo->prepare("SELECT id_mahasiswa FROM mahasiswa WHERE id_user = ?");
$stmtM->execute([$id_user]);
$id_mahasiswa = $stmtM->fetchColumn();

$biaya = $pdo->prepare("
    SELECT b.*, pa.nama_periode, pa.tanggal_mulai 
    FROM biaya_kuliah b
    JOIN periode_akademik pa ON b.id_periode = pa.id_periode
    WHERE b.id_mahasiswa = ?
    ORDER BY pa.tanggal_mulai DESC
");
$biaya->execute([$id_mahasiswa]);
$listBiaya = $biaya->fetchAll();
?>
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Informasi Biaya Kuliah</h1>
    
    <div class="bg-white p-6 rounded shadow max-w-4xl">
        <?php if(empty($listBiaya)): ?>
            <p>Belum ada tagihan biaya kuliah.</p>
        <?php else: ?>
            <table class="min-w-full border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="py-2 px-4 border-b">Periode / Semester</th>
                        <th class="py-2 px-4 border-b">Total Tagihan</th>
                        <th class="py-2 px-4 border-b">Status Pembayaran</th>
                        <th class="py-2 px-4 border-b">Tanggal Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($listBiaya as $b): ?>
                    <tr>
                        <td class="py-2 px-4 border-b text-center font-bold"><?= htmlspecialchars($b['nama_periode']) ?></td>
                        <td class="py-2 px-4 border-b text-right">Rp <?= number_format($b['jumlah'], 0, ',', '.') ?></td>
                        <td class="py-2 px-4 border-b text-center">
                            <?php if($b['status'] == 'Lunas'): ?>
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-bold">Lunas</span>
                            <?php else: ?>
                                <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-bold">Belum Lunas</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-2 px-4 border-b text-center">
                            <?= $b['tanggal_bayar'] ? date('d-m-Y H:i', strtotime($b['tanggal_bayar'])) : '-' ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <p class="text-sm text-gray-500 mt-4">* Jika status belum lunas padahal Anda sudah membayar, harap hubungi Bagian Keuangan atau BAAK.</p>
        <?php endif; ?>
    </div>
</div>
<?php require_once "../layout/footer.php"; ?>