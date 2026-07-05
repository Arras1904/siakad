<?php
require_once "../config/session.php";
require_once "../core/Guard.php";
requireRole(['admin']);


$pageTitle = "Dashboard";

require_once "../layout/header.php";
require_once "../layout/sidebar.php";
require_once "../layout/navbar.php";
require_once "../config/koneksi.php";

/*
=========================================================
QUERY STATISTIK
=========================================================
*/

$totalMahasiswa = mysqli_num_rows(
    mysqli_query($koneksi, "SELECT id_mahasiswa FROM mahasiswa")
);

$totalDosen = mysqli_num_rows(
    mysqli_query($koneksi, "SELECT id_dosen FROM dosen")
);

$totalProdi = mysqli_num_rows(
    mysqli_query($koneksi, "SELECT id_prodi FROM prodi")
);

$totalMatkul = mysqli_num_rows(
    mysqli_query($koneksi, "SELECT id_matkul FROM mata_kuliah")
);

$totalKelas = mysqli_num_rows(
    mysqli_query($koneksi, "SELECT id_kelas FROM kelas")
);

$totalJadwal = mysqli_num_rows(
    mysqli_query($koneksi, "SELECT id_jadwal FROM jadwal")
);

$totalKRS = mysqli_num_rows(
    mysqli_query($koneksi, "SELECT id_krs FROM krs")
);

$totalPengumuman = mysqli_num_rows(
    mysqli_query($koneksi, "SELECT id_pengumuman FROM pengumuman")
);

?>

<div id="dashboard-page">

    <!-- Header -->

    <div class="dashboard-header">

        <div>

            <h1>

                Selamat Datang,

                <?= htmlspecialchars($_SESSION['nama']); ?>

            </h1>

            <p>

                Semoga aktivitas akademik hari ini berjalan dengan lancar.

            </p>

        </div>

    </div>

    <!-- Statistik -->

    <div class="card-grid">

        <div class="card blue">

            <i class="fa-solid fa-user-graduate"></i>

            <h2><?= $totalMahasiswa ?></h2>

            <p>Mahasiswa</p>

        </div>

        <div class="card green">

            <i class="fa-solid fa-chalkboard-user"></i>

            <h2><?= $totalDosen ?></h2>

            <p>Dosen</p>

        </div>

        <div class="card orange">

            <i class="fa-solid fa-building-columns"></i>

            <h2><?= $totalProdi ?></h2>

            <p>Program Studi</p>

        </div>

        <div class="card purple">

            <i class="fa-solid fa-book"></i>

            <h2><?= $totalMatkul ?></h2>

            <p>Mata Kuliah</p>

        </div>

        <div class="card cyan">

            <i class="fa-solid fa-users"></i>

            <h2><?= $totalKelas ?></h2>

            <p>Kelas</p>

        </div>

        <div class="card red">

            <i class="fa-solid fa-calendar-days"></i>

            <h2><?= $totalJadwal ?></h2>

            <p>Jadwal</p>

        </div>

        <div class="card yellow">

            <i class="fa-solid fa-file-signature"></i>

            <h2><?= $totalKRS ?></h2>

            <p>KRS</p>

        </div>

        <div class="card dark">

            <i class="fa-solid fa-bullhorn"></i>

            <h2><?= $totalPengumuman ?></h2>

            <p>Pengumuman</p>

        </div>

    </div>

    <!-- Grafik -->

    <div class="dashboard-row">

        <div class="dashboard-box">

            <h3>Statistik Akademik</h3>

            <canvas id="chartMahasiswa"></canvas>

        </div>

        <div class="dashboard-box">

            <h3>Informasi</h3>

            <ul class="dashboard-info">

                <li>Jumlah Mahasiswa : <?= $totalMahasiswa ?></li>

                <li>Jumlah Dosen : <?= $totalDosen ?></li>

                <li>Jumlah Program Studi : <?= $totalProdi ?></li>

                <li>Jumlah Mata Kuliah : <?= $totalMatkul ?></li>

                <li>Jumlah Jadwal : <?= $totalJadwal ?></li>

            </ul>

        </div>

    </div>

</div>

<script>

const ctx = document.getElementById('chartMahasiswa');

new Chart(ctx, {

    type: 'bar',

    data: {

        labels: [

            'Mahasiswa',

            'Dosen',

            'Prodi',

            'Matkul',

            'Kelas'

        ],

        datasets: [{

            label: 'Data Akademik',

            data: [

                <?= $totalMahasiswa ?>,

                <?= $totalDosen ?>,

                <?= $totalProdi ?>,

                <?= $totalMatkul ?>,

                <?= $totalKelas ?>

            ]

        }]

    }

});

</script>

<?php

require_once "../layout/footer.php";

?>
```
