<?php

$nama = $_SESSION['nama'] ?? $_SESSION['username'];
$role = ucfirst($_SESSION['role']);
$foto = !empty($_SESSION['foto'])
    ? BASE_URL . "assets/images/" . $_SESSION['foto']
    : BASE_URL . "assets/images/default-user.png";
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

        <div class="navbar-user relative cursor-pointer" style="padding: 0.5rem; border-radius: 0.5rem;" onclick="toggleUserDropdown(event)">
            <div class="flex items-center gap-3">
                <?php if(!empty($_SESSION['foto'])): ?>
                    <img src="<?= BASE_URL . 'assets/images/' . $_SESSION['foto'] ?>" alt="Profil" class="w-10 h-10 rounded-full object-cover shadow-sm border border-gray-200">
                <?php else: ?>
                    <div class="w-10 h-10 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center shadow-sm border border-gray-200">
                        <i class="fa-solid fa-user text-xl"></i>
                    </div>
                <?php endif; ?>
                
                <div class="leading-tight">
                    <strong class="block text-sm"><?= htmlspecialchars($nama) ?></strong>
                    <small class="block text-xs text-gray-500"><?= htmlspecialchars($role) ?></small>
                </div>
                <i id="user-dropdown-arrow" class="fa-solid fa-chevron-down text-xs text-gray-400 transition-transform duration-300"></i>
            </div>

            <!-- Dropdown Menu -->
            <div id="user-dropdown-menu" class="absolute right-0 top-full mt-1 w-48 bg-white rounded-lg shadow-xl hidden z-[9000] overflow-hidden border border-gray-100">
                <a href="<?= BASE_URL ?>auth/logout.php" onclick="confirmLogout('<?= BASE_URL ?>auth/logout.php'); return false;" class="block px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors font-medium">
                    <i class="fa-solid fa-right-from-bracket mr-2 w-4"></i> Logout
                </a>
            </div>
        </div>

    </div>

</nav>

<script>
function toggleUserDropdown(event) {
    event.stopPropagation();
    const menu = document.getElementById('user-dropdown-menu');
    const arrow = document.getElementById('user-dropdown-arrow');
    menu.classList.toggle('hidden');
    arrow.classList.toggle('rotate-180');
}

// Close dropdown when clicking outside
document.addEventListener('click', function() {
    const menu = document.getElementById('user-dropdown-menu');
    const arrow = document.getElementById('user-dropdown-arrow');
    if (menu && !menu.classList.contains('hidden')) {
        menu.classList.add('hidden');
        arrow.classList.remove('rotate-180');
    }
});
</script>

<!-- =========================
     CONTENT
========================= -->

<div class="content">