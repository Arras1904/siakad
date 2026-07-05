<?php
require_once "../config/session.php";
require_once "../core/Guard.php";
requireRole(['dosen']);
require_once "../config/koneksi.php";

$pageTitle = "Profil Dosen";
require_once "../layout/header.php";
require_once "../layout/sidebar.php";
require_once "../layout/navbar.php";

if (!isset($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

$id_user = $_SESSION['user_id'];
$stmt = $pdo->prepare("
    SELECT u.nama_lengkap, d.* 
    FROM users u 
    JOIN dosen d ON u.id_user = d.id_user 
    WHERE u.id_user = ?
");
$stmt->execute([$id_user]);
$dosen = $stmt->fetch();
?>
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Profil Dosen</h1>
    
    <?php if($dosen['profil_lengkap'] == 0): ?>
        <div class="bg-yellow-100 text-yellow-800 p-4 rounded mb-4">
            Mohon lengkapi biodata Anda sebelum dapat mengakses menu lainnya.
        </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['success'])): ?>
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <?php if(isset($_SESSION['error'])): ?>
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form action="proses_profil.php" method="POST" class="bg-white p-6 rounded shadow max-w-lg">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Nama Lengkap</label>
            <input type="text" name="nama_lengkap" class="w-full border p-2 rounded" value="<?= htmlspecialchars($dosen['nama_lengkap']) ?>" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">NIP</label>
            <input type="text" class="w-full border p-2 rounded bg-gray-100" value="<?= htmlspecialchars($dosen['nip']) ?>" readonly>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Jenis Kelamin</label>
            <select name="jk" class="w-full border p-2 rounded" required>
                <option value="L" <?= $dosen['jk']=='L'?'selected':'' ?>>Laki-laki</option>
                <option value="P" <?= $dosen['jk']=='P'?'selected':'' ?>>Perempuan</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Email</label>
            <input type="email" name="email" class="w-full border p-2 rounded" value="<?= htmlspecialchars($dosen['email'] ?? '') ?>" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Telepon / No. HP</label>
            <input type="text" name="telepon" class="w-full border p-2 rounded" value="<?= htmlspecialchars($dosen['telepon'] ?? '') ?>" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Alamat</label>
            <textarea name="alamat" class="w-full border p-2 rounded" required><?= htmlspecialchars($dosen['alamat'] ?? '') ?></textarea>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Password Baru (kosongkan jika tidak ingin ganti)</label>
            <input type="password" name="password" class="w-full border p-2 rounded">
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan Profil</button>
    </form>
</div>
<?php require_once "../layout/footer.php"; ?>