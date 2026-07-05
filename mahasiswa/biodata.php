<?php
require_once "../config/session.php";
require_once "../core/Guard.php";
requireRole(['mahasiswa']);
require_once "../config/koneksi.php";

$pageTitle = "Biodata Mahasiswa";
require_once "../layout/header.php";
require_once "../layout/sidebar.php";
require_once "../layout/navbar.php";

if (!isset($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

$id_user = $_SESSION['user_id'];
$stmt = $pdo->prepare("
    SELECT u.nama_lengkap, m.*, p.nama_prodi, k.nama_kelas 
    FROM users u 
    JOIN mahasiswa m ON u.id_user = m.id_user 
    JOIN prodi p ON m.id_prodi = p.id_prodi
    JOIN kelas k ON m.id_kelas = k.id_kelas
    WHERE u.id_user = ?
");
$stmt->execute([$id_user]);
$mhs = $stmt->fetch();
?>
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Biodata Mahasiswa</h1>
    
    <?php if($mhs['profil_lengkap'] == 0): ?>
        <div class="bg-yellow-100 text-yellow-800 p-4 rounded mb-4 border border-yellow-300">
            <strong>Peringatan!</strong> Mohon lengkapi biodata Anda sebelum dapat mengakses menu lainnya (NPM, Prodi, Kelas, Semester telah diset oleh Akademik).
        </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['success'])): ?>
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <?php if(isset($_SESSION['error'])): ?>
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form action="proses_biodata.php" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded shadow max-w-lg">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        
        <div class="mb-4 bg-gray-50 p-4 rounded">
            <p><strong>NPM:</strong> <?= htmlspecialchars($mhs['npm']) ?></p>
            <p><strong>Prodi:</strong> <?= htmlspecialchars($mhs['nama_prodi']) ?></p>
            <p><strong>Kelas:</strong> <?= htmlspecialchars($mhs['nama_kelas']) ?></p>
            <p><strong>Angkatan:</strong> <?= htmlspecialchars($mhs['angkatan']) ?></p>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Nama Lengkap</label>
            <input type="text" name="nama_lengkap" class="w-full border p-2 rounded" value="<?= htmlspecialchars($mhs['nama_lengkap']) ?>" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Foto Profil (opsional)</label>
            <div class="flex items-center gap-4">
                <div id="icon-preview-biodata" class="<?= !empty($mhs['foto']) ? 'hidden' : 'flex' ?> w-16 h-16 rounded-full bg-gray-200 text-gray-500 items-center justify-center border border-gray-300 shadow-sm">
                    <i class="fa-solid fa-user text-3xl"></i>
                </div>
                <img id="preview-foto-biodata" src="<?= !empty($mhs['foto']) ? BASE_URL.'assets/images/'.$mhs['foto'] : '' ?>" alt="Preview" class="<?= empty($mhs['foto']) ? 'hidden' : 'block' ?> w-16 h-16 rounded-full object-cover border border-gray-300 shadow-sm">
                <div class="flex-1">
                    <input type="file" name="foto" accept="image/png, image/jpeg, image/webp" class="w-full border p-2 rounded bg-white text-sm" onchange="document.getElementById('icon-preview-biodata').classList.add('hidden'); document.getElementById('preview-foto-biodata').classList.remove('hidden'); document.getElementById('preview-foto-biodata').classList.add('block'); document.getElementById('preview-foto-biodata').src = window.URL.createObjectURL(this.files[0])">
                    <small class="text-gray-500 block mt-1">Format: JPG, PNG, WEBP.</small>
                </div>
            </div>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Tempat Lahir</label>
            <input type="text" name="tempat_lahir" class="w-full border p-2 rounded" value="<?= htmlspecialchars($mhs['tempat_lahir'] ?? '') ?>" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Tanggal Lahir</label>
            <input type="date" name="tanggal_lahir" class="w-full border p-2 rounded" value="<?= htmlspecialchars($mhs['tanggal_lahir'] ?? '') ?>" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Jenis Kelamin</label>
            <select name="jk" class="w-full border p-2 rounded" required>
                <option value="L" <?= $mhs['jk']=='L'?'selected':'' ?>>Laki-laki</option>
                <option value="P" <?= $mhs['jk']=='P'?'selected':'' ?>>Perempuan</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Agama</label>
            <select name="agama" class="w-full border p-2 rounded" required>
                <?php 
                $agamaOpt = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu', 'Lainnya'];
                foreach($agamaOpt as $ag) {
                    $sel = ($mhs['agama'] == $ag) ? 'selected' : '';
                    echo "<option value=\"$ag\" $sel>$ag</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Email</label>
            <input type="email" name="email" class="w-full border p-2 rounded" value="<?= htmlspecialchars($mhs['email'] ?? '') ?>" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Telepon / No. HP</label>
            <input type="text" name="telepon" class="w-full border p-2 rounded" value="<?= htmlspecialchars($mhs['telepon'] ?? '') ?>" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Alamat</label>
            <textarea name="alamat" class="w-full border p-2 rounded" required><?= htmlspecialchars($mhs['alamat'] ?? '') ?></textarea>
        </div>
        <div class="mb-4 pt-4 border-t">
            <label class="block text-gray-700 mb-2">Password Baru (kosongkan jika tidak ingin ganti)</label>
            <input type="password" name="password" class="w-full border p-2 rounded">
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded mt-2">Simpan Biodata</button>
    </form>
</div>
<?php require_once "../layout/footer.php"; ?>