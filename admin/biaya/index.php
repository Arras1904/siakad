<?php
require_once "../../config/session.php";
require_once "../../core/Guard.php";
requireRole(['admin']);
require_once "../../config/koneksi.php";

$pageTitle = "Biaya Kuliah";
require_once "../../layout/header.php";
require_once "../../layout/sidebar.php";
require_once "../../layout/navbar.php";

if (!isset($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

$filterPeriode = $_GET['periode'] ?? '';
$filterStatus = $_GET['status'] ?? '';

$periodeData = $pdo->query("SELECT * FROM periode_akademik ORDER BY tanggal_mulai DESC")->fetchAll();
$defaultBiaya = $pdo->query("SELECT value FROM pengaturan WHERE `key`='biaya_kuliah_default'")->fetchColumn();

$sql = "SELECT b.*, m.npm, u.nama_lengkap, p.nama_periode 
        FROM biaya_kuliah b 
        JOIN mahasiswa m ON b.id_mahasiswa = m.id_mahasiswa
        JOIN users u ON m.id_user = u.id_user
        JOIN periode_akademik p ON b.id_periode = p.id_periode
        WHERE 1=1";
$params = [];
if ($filterPeriode) { $sql .= " AND b.id_periode = ?"; $params[] = $filterPeriode; }
if ($filterStatus) { $sql .= " AND b.status = ?"; $params[] = $filterStatus; }
$sql .= " ORDER BY p.tanggal_mulai DESC, m.npm ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$biaya = $stmt->fetchAll();
?>
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Biaya Kuliah</h1>
    
    <?php if(isset($_SESSION['success'])): ?>
        <div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-4"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <!-- Form Ubah Default -->
    <div class="bg-white p-4 rounded shadow mb-6 max-w-lg">
        <h2 class="font-bold text-lg mb-2">Pengaturan Default Biaya (Semester Baru)</h2>
        <form action="update_default.php" method="POST" class="flex gap-2">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <input type="number" name="biaya_default" class="border p-2 rounded flex-1" value="<?= htmlspecialchars($defaultBiaya) ?>" required>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
        </form>
    </div>

    <!-- Filter -->
    <form class="mb-4 flex gap-4" method="GET">
        <select name="periode" class="border p-2 rounded">
            <option value="">Semua Periode</option>
            <?php foreach($periodeData as $p): ?>
                <option value="<?= $p['id_periode'] ?>" <?= $p['id_periode']==$filterPeriode?'selected':'' ?>><?= htmlspecialchars($p['nama_periode']) ?></option>
            <?php endforeach; ?>
        </select>
        <select name="status" class="border p-2 rounded">
            <option value="">Semua Status</option>
            <option value="Lunas" <?= $filterStatus=='Lunas'?'selected':'' ?>>Lunas</option>
            <option value="Belum Lunas" <?= $filterStatus=='Belum Lunas'?'selected':'' ?>>Belum Lunas</option>
        </select>
        <button type="submit" class="bg-gray-500 text-white px-4 py-2 rounded">Filter</button>
    </form>

    <table class="min-w-full bg-white border">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">NPM</th>
                <th class="py-2 px-4 border-b">Nama Mahasiswa</th>
                <th class="py-2 px-4 border-b">Periode</th>
                <th class="py-2 px-4 border-b">Jumlah (Rp)</th>
                <th class="py-2 px-4 border-b">Status</th>
                <th class="py-2 px-4 border-b">Tanggal Bayar</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($biaya as $b): ?>
            <tr>
                <td class="py-2 px-4 border-b text-center"><?= htmlspecialchars($b['npm']) ?></td>
                <td class="py-2 px-4 border-b"><?= htmlspecialchars($b['nama_lengkap']) ?></td>
                <td class="py-2 px-4 border-b text-center"><?= htmlspecialchars($b['nama_periode']) ?></td>
                <td class="py-2 px-4 border-b text-right"><?= number_format($b['jumlah'], 0, ',', '.') ?></td>
                <td class="py-2 px-4 border-b text-center">
                    <?php if($b['status'] == 'Lunas'): ?>
                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Lunas</span>
                    <?php else: ?>
                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Belum Lunas</span>
                    <?php endif; ?>
                </td>
                <td class="py-2 px-4 border-b text-center"><?= $b['tanggal_bayar'] ? date('d-m-Y', strtotime($b['tanggal_bayar'])) : '-' ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require_once "../../layout/footer.php"; ?>