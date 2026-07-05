<?php
require_once "../config/session.php";
require_once "../config/config.php";
require_once "../core/Auth.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Username dan Password tidak boleh kosong.";
        header("Location: login.php");
        exit();
    }

    if (attemptLogin($username, $password)) {
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
        }
        exit();
    } else {
        $_SESSION['error'] = "Username atau Password salah, atau akun nonaktif.";
        header("Location: login.php");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
