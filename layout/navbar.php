<?php

$nama = $_SESSION['nama'] ?? $_SESSION['username'];
$role = ucfirst($_SESSION['role']);

?>

<!-- =========================
     NAVBAR
========================= -->

<nav class="navbar">

    <!-- Kiri -->
    <div class="navbar-left">

        <button class="menu-button" id="toggleSidebar">

            <i class="fa-solid fa-bars"></i>

        </button>

        <div>

            <h2 class="page-title">
                <?= $pageTitle ?>
            </h2>

            <small class="page-subtitle">
                Sistem Informasi Akademik Universitas Indraprasta PGRI
            </small>

        </div>

    </div>

    <!-- Kanan -->
    <div class="navbar-right">

        <div class="navbar-info">

            <i class="fa-solid fa-calendar-days"></i>

            <span id="tanggal"></span>

        </div>

        <div class="navbar-info">

            <i class="fa-solid fa-clock"></i>

            <span id="jam"></span>

        </div>

        <div class="navbar-user">

            <i class="fa-solid fa-circle-user fa-2x"></i>

            <div>

                <strong><?= htmlspecialchars($nama) ?></strong>

                <small><?= htmlspecialchars($role) ?></small>

            </div>

        </div>

    </div>

</nav>

<!-- =========================
     CONTENT
========================= -->

<div class="content">