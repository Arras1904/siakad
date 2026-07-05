<?php
require_once "../config/session.php";
require_once "../core/Guard.php";
requireRole(['mahasiswa']);
require_once "../config/koneksi.php";

$pageTitle = "Dashboard Mahasiswa";
require_once "../layout/header.php";
require_once "../layout/sidebar.php";
require_once "../layout/navbar.php";

$id_user = $_SESSION['user_id'];
$mhsQuery = $pdo->prepare("SELECT id_mahasiswa, semester, status_akademik FROM mahasiswa WHERE id_user = ?");
$mhsQuery->execute([$id_user]);
$mhs = $mhsQuery->fetch();
$id_mahasiswa = $mhs['id_mahasiswa'];

// Get Active Period
$periodeAktif = $pdo->query("SELECT id_periode, nama_periode FROM periode_akademik WHERE status = 'aktif'")->fetch();
$id_periode_aktif = $periodeAktif['id_periode'] ?? null;

// Calculate Total SKS and IPK
// IPK = SUM(SKS * Bobot) / SUM(SKS)
$qStats = $pdo->prepare("
    SELECT m.sks, b.bobot 
    FROM nilai n
    JOIN peserta_kelas pk ON n.id_peserta_kelas = pk.id_peserta_kelas
    JOIN kelas_perkuliahan kp ON pk.id_kelas_perkuliahan = kp.id_kelas_perkuliahan
    JOIN mata_kuliah m ON kp.id_matkul = m.id_matkul
    JOIN bobot_nilai b ON n.nilai_huruf = b.nilai_huruf
    WHERE pk.id_mahasiswa = ?
");
$qStats->execute([$id_mahasiswa]);
$semuaNilai = $qStats->fetchAll();

$totalSksDiambil = 0;
$totalSksLulus = 0;
$totalMutu = 0;

foreach ($semuaNilai as $n) {
    $sks = $n['sks'];
    $bobot = $n['bobot'];
    
    $totalSksDiambil += $sks;
    $totalMutu += ($sks * $bobot);
    
    if ($bobot > 0) {
        $totalSksLulus += $sks;
    }
}
$ipk = $totalSksDiambil > 0 ? ($totalMutu / $totalSksDiambil) : 0;

// SKS Semester Aktif
$sksAktif = 0;
if ($id_periode_aktif) {
    $qSksAktif = $pdo->prepare("
        SELECT SUM(m.sks) as total_sks_aktif
        FROM peserta_kelas pk
        JOIN kelas_perkuliahan kp ON pk.id_kelas_perkuliahan = kp.id_kelas_perkuliahan
        JOIN mata_kuliah m ON kp.id_matkul = m.id_matkul
        WHERE pk.id_mahasiswa = ? AND kp.id_periode = ?
    ");
    $qSksAktif->execute([$id_mahasiswa, $id_periode_aktif]);
    $sksAktif = $qSksAktif->fetchColumn() ?: 0;
}

// Chart IPS Per Semester
$qIps = $pdo->prepare("
    SELECT pa.nama_periode, pa.tanggal_mulai, SUM(m.sks) as sum_sks, SUM(m.sks * b.bobot) as sum_mutu
    FROM nilai n
    JOIN peserta_kelas pk ON n.id_peserta_kelas = pk.id_peserta_kelas
    JOIN kelas_perkuliahan kp ON pk.id_kelas_perkuliahan = kp.id_kelas_perkuliahan
    JOIN mata_kuliah m ON kp.id_matkul = m.id_matkul
    JOIN bobot_nilai b ON n.nilai_huruf = b.nilai_huruf
    JOIN periode_akademik pa ON kp.id_periode = pa.id_periode
    WHERE pk.id_mahasiswa = ?
    GROUP BY pa.id_periode
    ORDER BY pa.tanggal_mulai ASC
");
$qIps->execute([$id_mahasiswa]);
$dataIpsRaw = $qIps->fetchAll();

$labelsIps = [];
$valuesIps = [];
foreach ($dataIpsRaw as $row) {
    $labelsIps[] = $row['nama_periode'];
    $ips = $row['sum_sks'] > 0 ? ($row['sum_mutu'] / $row['sum_sks']) : 0;
    $valuesIps[] = round($ips, 2);
}

?>
<?php if(isset($_SESSION['profil_lengkap']) && $_SESSION['profil_lengkap'] == 0): 
    $stmtF = $pdo->prepare("
        SELECT u.nama_lengkap, m.*, p.nama_prodi, k.nama_kelas 
        FROM users u 
        JOIN mahasiswa m ON u.id_user = m.id_user 
        JOIN prodi p ON m.id_prodi = p.id_prodi
        JOIN kelas k ON m.id_kelas = k.id_kelas
        WHERE u.id_user = ?
    ");
    $stmtF->execute([$id_user]);
    $mhsData = $stmtF->fetch();
    if (!isset($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
?>
<div class="p-6">
    <div class="bg-white rounded-xl shadow-md p-6 border-t-4 border-red-500">
        <h2 class="text-2xl font-bold mb-2 text-red-600"><i class="fa-solid fa-triangle-exclamation"></i> Lengkapi Biodata Anda</h2>
        <p class="text-gray-600 mb-4">Anda harus melengkapi formulir ini sebelum dapat menggunakan Sistem Informasi Akademik.</p>
        
        <?php if(isset($_SESSION['error'])): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <form action="proses_biodata.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            
            <div class="grid grid-cols-2 gap-4 mb-4 bg-gray-50 p-4 rounded border">
                <div><span class="text-sm text-gray-500">NPM</span><br><strong><?= htmlspecialchars($mhsData['npm']) ?></strong></div>
                <div><span class="text-sm text-gray-500">Prodi</span><br><strong><?= htmlspecialchars($mhsData['nama_prodi']) ?></strong></div>
                <div><span class="text-sm text-gray-500">Kelas</span><br><strong><?= htmlspecialchars($mhsData['nama_kelas']) ?></strong></div>
                <div><span class="text-sm text-gray-500">Angkatan</span><br><strong><?= htmlspecialchars($mhsData['angkatan']) ?></strong></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-2">
                    <label class="block text-gray-700 text-sm mb-1">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="w-full border p-2 rounded" value="<?= htmlspecialchars($mhsData['nama_lengkap']) ?>" required>
                </div>
                <div class="mb-2">
                    <label class="block text-gray-700 text-sm mb-1">Foto Profil (opsional)</label>
                    <div class="flex items-center gap-3">
                        <div id="icon-preview-mhs-dash" class="<?= !empty($mhsData['foto']) ? 'hidden' : 'flex' ?> w-12 h-12 rounded-full bg-gray-200 text-gray-500 items-center justify-center border border-gray-300 shadow-sm">
                            <i class="fa-solid fa-user text-xl"></i>
                        </div>
                        <img id="preview-foto-mhs-dash" src="<?= !empty($mhsData['foto']) ? BASE_URL.'assets/images/'.$mhsData['foto'] : '' ?>" alt="Preview" class="<?= empty($mhsData['foto']) ? 'hidden' : 'block' ?> w-12 h-12 rounded-full object-cover border border-gray-300 shadow-sm">
                        <input type="file" name="foto" accept="image/png, image/jpeg, image/webp" class="w-full border p-2 rounded bg-white text-sm" onchange="document.getElementById('icon-preview-mhs-dash').classList.add('hidden'); document.getElementById('preview-foto-mhs-dash').classList.remove('hidden'); document.getElementById('preview-foto-mhs-dash').classList.add('block'); document.getElementById('preview-foto-mhs-dash').src = window.URL.createObjectURL(this.files[0])">
                    </div>
                </div>
                <div class="mb-2">
                    <label class="block text-gray-700 text-sm mb-1">Email</label>
                    <input type="email" name="email" class="w-full border p-2 rounded" value="<?= htmlspecialchars($mhsData['email'] ?? '') ?>" required>
                </div>
                <div class="mb-2">
                    <label class="block text-gray-700 text-sm mb-1">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" class="w-full border p-2 rounded" value="<?= htmlspecialchars($mhsData['tempat_lahir'] ?? '') ?>" required>
                </div>
                <div class="mb-2">
                    <label class="block text-gray-700 text-sm mb-1">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="w-full border p-2 rounded" value="<?= htmlspecialchars($mhsData['tanggal_lahir'] ?? '') ?>" required>
                </div>
                <div class="mb-2">
                    <label class="block text-gray-700 text-sm mb-1">Jenis Kelamin</label>
                    <select name="jk" class="w-full border p-2 rounded" required>
                        <option value="L" <?= $mhsData['jk']=='L'?'selected':'' ?>>Laki-laki</option>
                        <option value="P" <?= $mhsData['jk']=='P'?'selected':'' ?>>Perempuan</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label class="block text-gray-700 text-sm mb-1">Agama</label>
                    <select name="agama" class="w-full border p-2 rounded" required>
                        <?php 
                        $agamaOpt = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu', 'Lainnya'];
                        foreach($agamaOpt as $ag) {
                            $sel = ($mhsData['agama'] == $ag) ? 'selected' : '';
                            echo "<option value=\"$ag\" $sel>$ag</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-2">
                    <label class="block text-gray-700 text-sm mb-1">Telepon / No. HP</label>
                    <input type="text" name="telepon" class="w-full border p-2 rounded" value="<?= htmlspecialchars($mhsData['telepon'] ?? '') ?>" required>
                </div>
                <div class="mb-2">
                    <label class="block text-gray-700 text-sm mb-1">Password Baru (opsional)</label>
                    <input type="password" name="password" class="w-full border p-2 rounded">
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm mb-1">Alamat Lengkap</label>
                <textarea name="alamat" class="w-full border p-2 rounded" rows="2" required><?= htmlspecialchars($mhsData['alamat'] ?? '') ?></textarea>
            </div>
            
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded transition duration-200">Simpan & Lanjutkan ke Dashboard</button>
        </form>
    </div>
</div>

<?php else: ?>
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Dashboard Mahasiswa</h1>
    
    <div class="bg-white p-4 rounded shadow mb-6 border-l-4 border-blue-500">
        <h2 class="text-lg">Selamat Datang, <strong><?= htmlspecialchars($_SESSION['nama']) ?></strong></h2>
        <p class="text-sm text-gray-600">Semester Anda Saat Ini: <strong><?= $mhs['semester'] ?></strong> | Status: <strong><?= strtoupper($mhs['status_akademik']) ?></strong></p>
    </div>

    <!-- Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-blue-100 p-4 rounded shadow text-center">
            <h3 class="text-blue-800 font-bold">IPK</h3>
            <p class="text-3xl font-bold"><?= number_format($ipk, 2) ?></p>
        </div>
        <div class="bg-green-100 p-4 rounded shadow text-center">
            <h3 class="text-green-800 font-bold">Total SKS Lulus</h3>
            <p class="text-3xl font-bold"><?= $totalSksLulus ?></p>
        </div>
        <div class="bg-yellow-100 p-4 rounded shadow text-center">
            <h3 class="text-yellow-800 font-bold">SKS Diambil</h3>
            <p class="text-3xl font-bold"><?= $totalSksDiambil ?></p>
        </div>
        <div class="bg-purple-100 p-4 rounded shadow text-center">
            <h3 class="text-purple-800 font-bold">SKS Semester Aktif</h3>
            <p class="text-3xl font-bold"><?= $sksAktif ?></p>
        </div>
    </div>

    <!-- Chart -->
    <div class="bg-white p-6 rounded shadow max-w-4xl">
        <h3 class="font-bold mb-4">Grafik Indeks Prestasi Semester (IPS)</h3>
        <?php if (empty($labelsIps)): ?>
            <p class="text-gray-500 text-sm">Belum ada riwayat nilai (KHS).</p>
        <?php else: ?>
            <canvas id="chartIps"></canvas>
        <?php endif; ?>
    </div>
</div>

<?php if (!empty($labelsIps)): ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('chartIps').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode($labelsIps) ?>,
            datasets: [{
                label: 'IPS',
                data: <?= json_encode($valuesIps) ?>,
                borderColor: 'rgba(59, 130, 246, 1)',
                backgroundColor: 'rgba(59, 130, 246, 0.2)',
                borderWidth: 2,
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            scales: {
                y: { min: 0, max: 4 }
            }
        }
    });
</script>
<?php endif; ?>

<?php endif; ?>
<?php require_once "../layout/footer.php"; ?>