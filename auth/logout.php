<?php
require_once "../config/config.php";
require_once "../core/Auth.php";

logoutUser();

// Hapus cache browser
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

header("Location: login.php");
exit();
?>