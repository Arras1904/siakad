<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/../config/config.php";

// Cek Login
if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "auth/login.php");
    exit();
}

if (!isset($pageTitle)) {
    $pageTitle = "Sistem Informasi Akademik";
}
?>
<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= $pageTitle ?> | <?= NAMA_APLIKASI ?></title>

    <!-- Favicon -->
    <link rel="icon" href="<?= BASE_URL ?>assets/images/Logo Unindra.png">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- ChartJS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/dashboard.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/table.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/form.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/responsive.css">

</head>

<body>

<div class="wrapper">