<?php
require 'config/koneksi.php';
$stmt = $pdo->query("SELECT * FROM mahasiswa WHERE npm = '20260001'");
var_dump($stmt->fetch(PDO::FETCH_ASSOC));
