<?php
require_once "../config/session.php";
require_once "../core/Guard.php";
requireRole(['dosen']);
require_once "../config/koneksi.php";

$pageTitle = "Dashboard Dosen";
require_once "../layout/header.php";
require_once "../layout/sidebar.php";
require_once "../layout/navbar.php";

$id_user = $_SESSION['user_id'];
// Get id_dosen
$stmtD = $pdo->prepare("SELECT id_dosen FROM dosen WHERE id_user = ?");
$stmtD->execute([$id_user]);
$dosen = $stmtD->fetch();
$id_dosen = $dosen['id_dosen'];

// Get jadwal mengajar periode aktif
$jadwal = $pdo->prepare("
    SELECT kp.*, m.nama_matkul, k.nama_kelas, p.nama_periode
    FROM kelas_perkuliahan kp
    JOIN mata_kuliah m ON kp.id_matkul = m.id_matkul
    JOIN kelas k ON kp.id_kelas = k.id_kelas
    JOIN periode_akademik p ON kp.id_periode = p.id_periode
    WHERE kp.id_dosen = ? AND p.status = 'aktif'
    ORDER BY FIELD(kp.hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'), kp.jam_mulai ASC
");
$jadwal->execute([$id_dosen]);
$listJadwal = $jadwal->fetchAll();
?>
<?php if(isset($_SESSION['profil_lengkap']) && $_SESSION['profil_lengkap'] == 0): 
    $stmtF = $pdo->prepare("
        SELECT u.nama_lengkap, d.* 
        FROM users u 
        JOIN dosen d ON u.id_user = d.id_user 
        WHERE u.id_user = ?
    ");
    $stmtF->execute([$id_user]);
    $dsnData = $stmtF->fetch();
    if (!isset($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
?>
<div class="p-6">
    <div class="bg-white rounded-xl shadow-md p-6 border-t-4 border-red-500">
        <h2 class="text-2xl font-bold mb-2 text-red-600"><i class="fa-solid fa-triangle-exclamation"></i> Lengkapi Profil Dosen</h2>
        <p class="text-gray-600 mb-4">Anda harus melengkapi profil Anda sebelum dapat mengakses halaman lainnya.</p>
        
        <?php if(isset($_SESSION['error'])): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <form action="proses_profil.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            
            <div class="mb-4 bg-gray-50 p-4 rounded border">
                <span class="text-sm text-gray-500">NIP</span><br>
                <strong><?= htmlspecialchars($dsnData['nip']) ?></strong>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-2">
                    <label class="block text-gray-700 text-sm mb-1">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="w-full border p-2 rounded" value="<?= htmlspecialchars($dsnData['nama_lengkap']) ?>" required>
                </div>
                <div class="mb-2">
                    <label class="block text-gray-700 text-sm mb-1">Foto Profil (opsional)</label>
                    <div class="flex items-center gap-3">
                        <div id="icon-preview-dsn-dash" class="<?= !empty($dsnData['foto']) ? 'hidden' : 'flex' ?> w-12 h-12 rounded-full bg-gray-200 text-gray-500 items-center justify-center border border-gray-300 shadow-sm">
                            <i class="fa-solid fa-user text-xl"></i>
                        </div>
                        <img id="preview-foto-dsn-dash" src="<?= !empty($dsnData['foto']) ? BASE_URL.'assets/images/'.$dsnData['foto'] : '' ?>" alt="Preview" class="<?= empty($dsnData['foto']) ? 'hidden' : 'block' ?> w-12 h-12 rounded-full object-cover border border-gray-300 shadow-sm">
                        <input type="file" name="foto" accept="image/png, image/jpeg, image/webp" class="w-full border p-2 rounded bg-white text-sm" onchange="document.getElementById('icon-preview-dsn-dash').classList.add('hidden'); document.getElementById('preview-foto-dsn-dash').classList.remove('hidden'); document.getElementById('preview-foto-dsn-dash').classList.add('block'); document.getElementById('preview-foto-dsn-dash').src = window.URL.createObjectURL(this.files[0])">
                    </div>
                </div>
                <div class="mb-2">
                    <label class="block text-gray-700 text-sm mb-1">Jenis Kelamin</label>
                    <select name="jk" class="w-full border p-2 rounded" required>
                        <option value="L" <?= $dsnData['jk']=='L'?'selected':'' ?>>Laki-laki</option>
                        <option value="P" <?= $dsnData['jk']=='P'?'selected':'' ?>>Perempuan</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label class="block text-gray-700 text-sm mb-1">Email</label>
                    <input type="email" name="email" class="w-full border p-2 rounded" value="<?= htmlspecialchars($dsnData['email'] ?? '') ?>" required>
                </div>
                <div class="mb-2">
                    <label class="block text-gray-700 text-sm mb-1">Agama</label>
                    <select name="agama" class="w-full border p-2 rounded" required>
                        <?php 
                        $agamaOpt = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu', 'Lainnya'];
                        foreach($agamaOpt as $ag) {
                            $sel = ($dsnData['agama'] == $ag) ? 'selected' : '';
                            echo "<option value=\"$ag\" $sel>$ag</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-2">
                    <label class="block text-gray-700 text-sm mb-1">Telepon / No. HP</label>
                    <input type="text" name="telepon" class="w-full border p-2 rounded" value="<?= htmlspecialchars($dsnData['telepon'] ?? '') ?>" required>
                </div>
                <div class="mb-2 md:col-span-2">
                    <label class="block text-gray-700 text-sm mb-1">Password Baru (opsional)</label>
                    <input type="password" name="password" class="w-full border p-2 rounded">
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm mb-1">Alamat Lengkap</label>
                <textarea name="alamat" class="w-full border p-2 rounded" rows="2" required><?= htmlspecialchars($dsnData['alamat'] ?? '') ?></textarea>
            </div>
            
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded transition duration-200">Simpan & Lanjutkan ke Dashboard</button>
        </form>
    </div>
</div>

<?php else: ?>
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Dashboard Dosen</h1>
    <div class="bg-blue-100 p-4 rounded shadow mb-6">
        <p class="text-blue-800">Selamat datang, <strong><?= htmlspecialchars($_SESSION['nama']) ?></strong>!</p>
        <p class="text-sm mt-1">Ini adalah halaman dashboard akademik Anda.</p>
    </div>

    <h2 class="text-xl font-bold mb-4">Jadwal Mengajar Anda (Periode Aktif)</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <?php if(empty($listJadwal)): ?>
            <div class="bg-white p-4 rounded shadow">Belum ada jadwal mengajar pada periode ini.</div>
        <?php else: ?>
            <?php foreach($listJadwal as $j): ?>
            <div class="bg-white p-4 rounded shadow border-l-4 border-blue-500">
                <h3 class="font-bold text-lg"><?= htmlspecialchars($j['nama_matkul']) ?></h3>
                <p class="text-sm text-gray-600">Kelas: <?= htmlspecialchars($j['nama_kelas']) ?></p>
                <p class="text-sm text-gray-600">Jadwal: <?= $j['hari'] ?>, <?= $j['jam_mulai'] ?> - <?= $j['jam_selesai'] ?></p>
                <p class="text-sm text-gray-600">Ruangan: <?= htmlspecialchars($j['ruangan']) ?></p>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<?php require_once "../layout/footer.php"; ?>