<?php
session_start();

// Hapus seluruh data session
$_SESSION = array();

// Hancurkan session
session_destroy();

// Hapus cache browser
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Kembali ke halaman login
header("Location: login.php");
exit();
?>