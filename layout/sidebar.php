<?php

$currentPage = basename($_SERVER['PHP_SELF']);

$nama = $_SESSION['nama'] ?? $_SESSION['username'];
$role = ucfirst($_SESSION['role']);
$foto = !empty($_SESSION['foto'])
    ? BASE_URL . "assets/images/" . $_SESSION['foto']
    : BASE_URL . "assets/images/default-user.png";

?>

<!-- =========================
     SIDEBAR
========================= -->

<aside class="sidebar">

    <!-- Logo -->
    <div class="sidebar-logo">

        <img src="<?= BASE_URL ?>assets/images/Logo Unindra.png" alt="Logo">

        <h2>SIAKAD</h2>

        <p>Universitas Indraprasta PGRI</p>

    </div>


    <!-- User -->
    <div class="sidebar-user">

        <img src="<?= $foto ?>" alt="Foto User">

        <h4><?= htmlspecialchars($nama) ?></h4>

        <span><?= htmlspecialchars($role) ?></span>

    </div>


    <!-- Menu -->
    <ul class="sidebar-menu">

        <li>
            <a href="<?= BASE_URL ?>admin/dashboard.php"
                class="<?= ($currentPage == 'dashboard.php') ? 'active' : '' ?>">

                <i class="fa-solid fa-house"></i>

                <span>Dashboard</span>

            </a>
        </li>

        <li>
            <a href="<?= BASE_URL ?>admin/prodi/index.php">

                <i class="fa-solid fa-building-columns"></i>

                <span>Program Studi</span>

            </a>
        </li>

        <li>
            <a href="<?= BASE_URL ?>admin/kelas/index.php">

                <i class="fa-solid fa-users"></i>

                <span>Data Kelas</span>

            </a>
        </li>

        <li>
            <a href="<?= BASE_URL ?>admin/dosen/index.php">

                <i class="fa-solid fa-chalkboard-user"></i>

                <span>Data Dosen</span>

            </a>
        </li>

        <li>
            <a href="<?= BASE_URL ?>admin/mahasiswa/index.php">

                <i class="fa-solid fa-user-graduate"></i>

                <span>Data Mahasiswa</span>

            </a>
        </li>

        <li>
            <a href="<?= BASE_URL ?>admin/matakuliah/index.php">

                <i class="fa-solid fa-book"></i>

                <span>Mata Kuliah</span>

            </a>
        </li>

        <li>
            <a href="<?= BASE_URL ?>admin/jadwal/index.php">

                <i class="fa-solid fa-calendar-days"></i>

                <span>Jadwal Kuliah</span>

            </a>
        </li>

        <li>
            <a href="<?= BASE_URL ?>admin/krs/index.php">

                <i class="fa-solid fa-file-signature"></i>

                <span>KRS</span>

            </a>
        </li>

        <li>
            <a href="<?= BASE_URL ?>admin/presensi/index.php">

                <i class="fa-solid fa-clipboard-check"></i>

                <span>Presensi</span>

            </a>
        </li>

        <li>
            <a href="<?= BASE_URL ?>admin/nilai/index.php">

                <i class="fa-solid fa-square-poll-vertical"></i>

                <span>Nilai</span>

            </a>
        </li>

        <li>
            <a href="<?= BASE_URL ?>admin/biaya/index.php">

                <i class="fa-solid fa-wallet"></i>

                <span>Biaya Kuliah</span>

            </a>
        </li>

        <li>
            <a href="<?= BASE_URL ?>admin/pengumuman/index.php">

                <i class="fa-solid fa-bullhorn"></i>

                <span>Pengumuman</span>

            </a>
        </li>

        <li>

            <hr style="border-color:rgba(255,255,255,.15); margin:15px 0;">

        </li>

        <li>

            <a href="<?= BASE_URL ?>auth/logout.php"
               onclick="confirmLogout('<?= BASE_URL ?>auth/logout.php'); return false;">

                <i class="fa-solid fa-right-from-bracket"></i>

                <span>Logout</span>

            </a>

        </li>

    </ul>

</aside>

<!-- =========================
     MAIN CONTENT
========================= -->

<main class="main-content">