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
    <div class="sidebar-logo text-center flex flex-col items-center justify-center">
        <img src="<?= BASE_URL ?>assets/images/Logo Unindra.png" alt="Logo" class="mx-auto mb-2">
        <h2 class="font-bold text-lg">SIAKAD</h2>
        <p class="text-sm">Universitas Indraprasta PGRI</p>
    </div>





    <!-- Menu -->
    <ul class="sidebar-menu <?= (isset($_SESSION['profil_lengkap']) && $_SESSION['profil_lengkap'] == 0) ? 'pointer-events-none opacity-50 cursor-not-allowed' : '' ?>">
        <?php if ($role === 'Admin'): ?>
            <li>
                <a href="<?= BASE_URL ?>admin/dashboard.php" class="<?= ($currentPage == 'dashboard.php') ? 'active' : '' ?>">
                    <i class="fa-solid fa-house"></i><span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>admin/prodi/index.php">
                    <i class="fa-solid fa-building-columns"></i><span>Program Studi</span>
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>admin/kelas/index.php">
                    <i class="fa-solid fa-users"></i><span>Data Kelas</span>
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>admin/dosen/index.php">
                    <i class="fa-solid fa-chalkboard-user"></i><span>Data Dosen</span>
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>admin/mahasiswa/index.php">
                    <i class="fa-solid fa-user-graduate"></i><span>Data Mahasiswa</span>
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>admin/matakuliah/index.php">
                    <i class="fa-solid fa-book"></i><span>Mata Kuliah</span>
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>admin/jadwal/index.php">
                    <i class="fa-solid fa-calendar-days"></i><span>Kelas Perkuliahan</span>
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>admin/biaya/index.php">
                    <i class="fa-solid fa-wallet"></i><span>Biaya Kuliah</span>
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>admin/periode/index.php">
                    <i class="fa-solid fa-clock-rotate-left"></i><span>Periode Akademik</span>
                </a>
            </li>

        <?php elseif ($role === 'Dosen'): ?>
            <li>
                <a href="<?= BASE_URL ?>dosen/dashboard.php" class="<?= ($currentPage == 'dashboard.php') ? 'active' : '' ?>">
                    <i class="fa-solid fa-house"></i><span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>dosen/profil.php" class="<?= ($currentPage == 'profil.php') ? 'active' : '' ?>">
                    <i class="fa-solid fa-user"></i><span>Profil</span>
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>dosen/jadwal.php">
                    <i class="fa-solid fa-calendar-days"></i><span>Kelas & Nilai</span>
                </a>
            </li>

        <?php elseif ($role === 'Mahasiswa'): ?>
            <li>
                <a href="<?= BASE_URL ?>mahasiswa/dashboard.php" class="<?= ($currentPage == 'dashboard.php') ? 'active' : '' ?>">
                    <i class="fa-solid fa-house"></i><span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>mahasiswa/biodata.php" class="<?= ($currentPage == 'biodata.php') ? 'active' : '' ?>">
                    <i class="fa-solid fa-user"></i><span>Biodata</span>
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>mahasiswa/jadwal.php">
                    <i class="fa-solid fa-calendar-days"></i><span>Jadwal Kuliah</span>
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>mahasiswa/nilai.php">
                    <i class="fa-solid fa-square-poll-vertical"></i><span>KHS / Nilai</span>
                </a>
            </li>
            <li>
                <a href="<?= BASE_URL ?>mahasiswa/biaya.php">
                    <i class="fa-solid fa-wallet"></i><span>Biaya Kuliah</span>
                </a>
            </li>
        <?php endif; ?>


    </ul>
</aside>

<!-- =========================
     MAIN CONTENT
========================= -->

<main class="main-content">