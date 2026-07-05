<?php
session_start();
require_once "../config/config.php";

// Jika sudah login, arahkan ke dashboard sesuai role
if (isset($_SESSION['login'])) {

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
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login | Sistem Informasi Akademik</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet"
        href="<?= BASE_URL ?>assets/css/login.css">

    <!-- Logo Browser -->
    <link rel="icon"
        href="<?= BASE_URL ?>assets/images/Logo Unindra.png">

</head>

<body>

    <div class="login-card">

        <!-- Logo -->

        <img src="<?= BASE_URL ?>assets/images/Logo Unindra.png"
            alt="Logo UNINDRA"
            class="login-logo">

        <!-- Judul -->

        <div class="login-title">

            <h2>UNIVERSITAS INDRAPRASTA PGRI</h2>

            <p>Sistem Informasi Akademik</p>

            <p>Silakan login menggunakan akun Anda</p>

        </div>

        <!-- Form -->

        <form
            action="cek_login.php"
            method="POST"
            class="login-form">

            <!-- Username -->

            <div class="input-group">

                <label>

                    Username

                </label>

                <input
                    type="text"
                    name="username"
                    class="input-box"
                    placeholder="Masukkan Username"
                    autocomplete="off"
                    required>

            </div>

            <!-- Password -->

            <div class="input-group">

                <label>

                    Password

                </label>

                <div style="position:relative;">

                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="input-box"
                        placeholder="Masukkan Password"
                        required>

                    <button
					type="button"
					onclick="togglePassword('password','iconPassword')">

                        <i id="iconPassword"
                            class="fa-solid fa-eye"></i>

                    </button>

                </div>

            </div>

            <!-- Remember -->

            <div class="flex justify-between items-center text-white text-sm mb-5">

                <label class="flex items-center gap-2">

                    <input type="checkbox">

                    Ingat Saya

                </label>

                <span>

                    Academic System

                </span>

            </div>

            <!-- Button -->

            <button
                type="submit"
                name="login"
                class="login-button">

                <i class="fa-solid fa-right-to-bracket"></i>

                Login

            </button>

        </form>

        <!-- Footer -->

        <div class="login-footer">

            © <?= date("Y"); ?>

            Sistem Informasi Akademik

        </div>

    </div>

    <script src="<?= BASE_URL ?>assets/js/script.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="<?= BASE_URL ?>assets/js/alert.js"></script>

</body>

</html>