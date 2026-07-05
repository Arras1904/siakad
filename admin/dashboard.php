<?php
require_once "../config/session.php";
require_once "../core/Guard.php";
requireRole(['admin']);

$pageTitle = "Dashboard Admin";
require_once "../layout/header.php";
require_once "../layout/sidebar.php";
require_once "../layout/navbar.php";
require_once "../config/koneksi.php";

$totalMahasiswa = $pdo->query("SELECT COUNT(*) FROM mahasiswa")->fetchColumn();
$totalDosen = $pdo->query("SELECT COUNT(*) FROM dosen")->fetchColumn();
$totalProdi = $pdo->query("SELECT COUNT(*) FROM prodi")->fetchColumn();
$totalMatkul = $pdo->query("SELECT COUNT(*) FROM mata_kuliah")->fetchColumn();
$totalKelas = $pdo->query("SELECT COUNT(*) FROM kelas")->fetchColumn();
$totalJadwal = $pdo->query("
    SELECT COUNT(*) FROM kelas_perkuliahan kp 
    JOIN periode_akademik pa ON kp.id_periode = pa.id_periode 
    WHERE pa.status = 'aktif'
")->fetchColumn();

?>
<div class="p-6 space-y-6 bg-gray-50 min-h-screen">

    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex flex-col md:flex-row justify-between items-center bg-gradient-to-r from-blue-600 to-blue-400 text-white">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold mb-1">
                Selamat Datang, <?= htmlspecialchars($_SESSION['nama']); ?>
            </h1>
            <p class="text-blue-50 opacity-90">
                Semoga aktivitas akademik hari ini berjalan dengan lancar.
            </p>
        </div>
        <div class="hidden md:block">
            <i class="fa-solid fa-graduation-cap text-5xl opacity-80"></i>
        </div>
    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4">
        <!-- Card 1 -->
        <div class="bg-blue-500 rounded-xl shadow-md p-5 text-white flex flex-col items-center justify-center transform transition duration-300 hover:scale-105">
            <i class="fa-solid fa-user-graduate text-3xl mb-2 opacity-80"></i>
            <h2 class="text-3xl font-bold mb-1"><?= $totalMahasiswa ?></h2>
            <p class="text-sm font-medium opacity-90">Mahasiswa</p>
        </div>
        <!-- Card 2 -->
        <div class="bg-emerald-500 rounded-xl shadow-md p-5 text-white flex flex-col items-center justify-center transform transition duration-300 hover:scale-105">
            <i class="fa-solid fa-chalkboard-user text-3xl mb-2 opacity-80"></i>
            <h2 class="text-3xl font-bold mb-1"><?= $totalDosen ?></h2>
            <p class="text-sm font-medium opacity-90">Dosen</p>
        </div>
        <!-- Card 3 -->
        <div class="bg-orange-500 rounded-xl shadow-md p-5 text-white flex flex-col items-center justify-center transform transition duration-300 hover:scale-105">
            <i class="fa-solid fa-building-columns text-3xl mb-2 opacity-80"></i>
            <h2 class="text-3xl font-bold mb-1"><?= $totalProdi ?></h2>
            <p class="text-sm font-medium opacity-90">Program Studi</p>
        </div>
        <!-- Card 4 -->
        <div class="bg-purple-500 rounded-xl shadow-md p-5 text-white flex flex-col items-center justify-center transform transition duration-300 hover:scale-105">
            <i class="fa-solid fa-book text-3xl mb-2 opacity-80"></i>
            <h2 class="text-3xl font-bold mb-1"><?= $totalMatkul ?></h2>
            <p class="text-sm font-medium opacity-90">Mata Kuliah</p>
        </div>
        <!-- Card 5 -->
        <div class="bg-cyan-500 rounded-xl shadow-md p-5 text-white flex flex-col items-center justify-center transform transition duration-300 hover:scale-105">
            <i class="fa-solid fa-users text-3xl mb-2 opacity-80"></i>
            <h2 class="text-3xl font-bold mb-1"><?= $totalKelas ?></h2>
            <p class="text-sm font-medium opacity-90">Kelas</p>
        </div>
        <!-- Card 6 -->
        <div class="bg-rose-500 rounded-xl shadow-md p-5 text-white flex flex-col items-center justify-center transform transition duration-300 hover:scale-105">
            <i class="fa-solid fa-calendar-days text-3xl mb-2 opacity-80"></i>
            <h2 class="text-3xl font-bold mb-1"><?= $totalJadwal ?></h2>
            <p class="text-sm font-medium opacity-90 text-center leading-tight">Kelas Perkuliahan<br>(Aktif)</p>
        </div>
    </div>

    <!-- Grafik & Informasi -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Box Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b">Statistik Akademik</h3>
            <canvas id="chartMahasiswa" class="w-full h-64"></canvas>
        </div>

        <!-- Box Info -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b">Informasi Sistem</h3>
            <ul class="space-y-3">
                <li class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                    <div class="flex items-center text-gray-700">
                        <i class="fa-solid fa-user-graduate w-6 text-blue-500"></i> Jumlah Mahasiswa Terdaftar
                    </div>
                    <span class="font-bold bg-blue-100 text-blue-800 py-1 px-3 rounded-full text-sm"><?= $totalMahasiswa ?></span>
                </li>
                <li class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                    <div class="flex items-center text-gray-700">
                        <i class="fa-solid fa-chalkboard-user w-6 text-emerald-500"></i> Jumlah Dosen Terdaftar
                    </div>
                    <span class="font-bold bg-emerald-100 text-emerald-800 py-1 px-3 rounded-full text-sm"><?= $totalDosen ?></span>
                </li>
                <li class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                    <div class="flex items-center text-gray-700">
                        <i class="fa-solid fa-building-columns w-6 text-orange-500"></i> Jumlah Program Studi
                    </div>
                    <span class="font-bold bg-orange-100 text-orange-800 py-1 px-3 rounded-full text-sm"><?= $totalProdi ?></span>
                </li>
                <li class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                    <div class="flex items-center text-gray-700">
                        <i class="fa-solid fa-book w-6 text-purple-500"></i> Jumlah Mata Kuliah
                    </div>
                    <span class="font-bold bg-purple-100 text-purple-800 py-1 px-3 rounded-full text-sm"><?= $totalMatkul ?></span>
                </li>
                <li class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                    <div class="flex items-center text-gray-700">
                        <i class="fa-solid fa-calendar-days w-6 text-rose-500"></i> Kelas Perkuliahan (Periode Aktif)
                    </div>
                    <span class="font-bold bg-rose-100 text-rose-800 py-1 px-3 rounded-full text-sm"><?= $totalJadwal ?></span>
                </li>
            </ul>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('chartMahasiswa');
    if(ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Mahasiswa', 'Dosen', 'Prodi', 'Matkul', 'Kelas', 'Jadwal Aktif'],
                datasets: [{
                    label: 'Data Akademik',
                    data: [
                        <?= $totalMahasiswa ?>,
                        <?= $totalDosen ?>,
                        <?= $totalProdi ?>,
                        <?= $totalMatkul ?>,
                        <?= $totalKelas ?>,
                        <?= $totalJadwal ?>
                    ],
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.7)',  // blue-500
                        'rgba(16, 185, 129, 0.7)',  // emerald-500
                        'rgba(249, 115, 22, 0.7)',  // orange-500
                        'rgba(168, 85, 247, 0.7)',  // purple-500
                        'rgba(6, 182, 212, 0.7)',   // cyan-500
                        'rgba(244, 63, 94, 0.7)'    // rose-500
                    ],
                    borderColor: [
                        'rgb(59, 130, 246)',
                        'rgb(16, 185, 129)',
                        'rgb(249, 115, 22)',
                        'rgb(168, 85, 247)',
                        'rgb(6, 182, 212)',
                        'rgb(244, 63, 94)'
                    ],
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    }
});
</script>

<?php require_once "../layout/footer.php"; ?>
