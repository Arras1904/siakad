<?php
require_once "../config/session.php";
require_once "../core/Guard.php";
requireRole(['dosen']);
require_once "../config/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) die("CSRF Token Invalid");

    $id_user = $_SESSION['user_id'];
    $nama = trim($_POST['nama_lengkap']);
    $jk = $_POST['jk'];
    $email = trim($_POST['email']);
    $telepon = trim($_POST['telepon']);
    $alamat = trim($_POST['alamat']);
    $agama = trim($_POST['agama']);
    $pass = $_POST['password'];

    try {
        $pdo->beginTransaction();

        $fotoQuery = "";
        $paramsUser = [$nama];

        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            $filename = $_FILES['foto']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            if (in_array($ext, $allowed)) {
                $newFilename = uniqid() . '.' . $ext;
                $dest = "../assets/images/" . $newFilename;

                // Get old photo to delete it later
                $stmtOldFoto = $pdo->prepare("SELECT foto FROM users WHERE id_user = ?");
                $stmtOldFoto->execute([$id_user]);
                $oldFoto = $stmtOldFoto->fetchColumn();

                if (move_uploaded_file($_FILES['foto']['tmp_name'], $dest)) {
                    $fotoQuery = ", foto = ?";
                    $paramsUser[] = $newFilename;
                    $_SESSION['foto'] = $newFilename;
                    
                    // Delete old photo if it's not the default
                    if ($oldFoto && $oldFoto !== 'default-user.png') {
                        $oldPath = "../assets/images/" . $oldFoto;
                        if (file_exists($oldPath)) {
                            unlink($oldPath);
                        }
                    }
                }
            }
        }

        if (!empty($pass)) {
            $hashed = password_hash($pass, PASSWORD_BCRYPT);
            $fotoQuery .= ", password_hash = ?";
            $paramsUser[] = $hashed;
        }

        $paramsUser[] = $id_user;
        $updUser = $pdo->prepare("UPDATE users SET nama_lengkap = ? $fotoQuery WHERE id_user = ?");
        $updUser->execute($paramsUser);
        
        $_SESSION['nama'] = $nama;

        $updDsn = $pdo->prepare("UPDATE dosen SET jk=?, email=?, telepon=?, alamat=?, agama=?, profil_lengkap=1 WHERE id_user=?");
        $updDsn->execute([$jk, $email, $telepon, $alamat, $agama, $id_user]);
        
        $_SESSION['profil_lengkap'] = 1;

        $pdo->commit();
        $_SESSION['success'] = "Profil berhasil diupdate.";
    } catch (\PDOException $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Gagal update profil: " . $e->getMessage();
    }
}
header("Location: profil.php");