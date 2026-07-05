<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/config.php';

function requireRole(array $roles) {
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
        $_SESSION['error'] = "Silakan login terlebih dahulu.";
        header("Location: " . BASE_URL . "auth/login.php");
        exit();
    }

    if (!in_array($_SESSION['role'], $roles)) {
        $_SESSION['error'] = "Akses ditolak. Anda tidak memiliki izin untuk halaman ini.";
        switch ($_SESSION['role']) {
            case 'admin':
                header("Location: " . BASE_URL . "admin/dashboard.php");
                break;
            case 'dosen':
                header("Location: " . BASE_URL . "dosen/dashboard.php");
                break;
            case 'mahasiswa':
                header("Location: " . BASE_URL . "mahasiswa/dashboard.php");
                break;
            default:
                header("Location: " . BASE_URL . "auth/login.php");
                break;
        }
        exit();
    }
    
    // Cek profil belum lengkap
    if (isset($_SESSION['profil_lengkap']) && $_SESSION['profil_lengkap'] == 0) {
        $currentFile = basename($_SERVER['PHP_SELF']);
        $currentPath = $_SERVER['REQUEST_URI'];
        
        if ($_SESSION['role'] === 'mahasiswa') {
            if (strpos($currentPath, 'biodata.php') === false && 
                strpos($currentPath, 'proses_biodata.php') === false && 
                strpos($currentPath, 'logout.php') === false) {
                header("Location: " . BASE_URL . "mahasiswa/biodata.php");
                exit();
            }
        } elseif ($_SESSION['role'] === 'dosen') {
            if (strpos($currentPath, 'profil.php') === false && 
                strpos($currentPath, 'proses_profil.php') === false && 
                strpos($currentPath, 'logout.php') === false) {
                header("Location: " . BASE_URL . "dosen/profil.php");
                exit();
            }
        }
    }
}
?>
