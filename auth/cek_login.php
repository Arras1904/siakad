
<?php

session_start();

require_once "../config/config.php";

// ==========================================
// Cek apakah form login dikirim
// ==========================================

if ($_SERVER['REQUEST_METHOD'] != "POST") {

    header("Location: " . BASE_URL . "auth/login.php");
    exit();

}

// ==========================================
// Ambil Data
// ==========================================

$username = trim($_POST['username'] ?? "");
$password = trim($_POST['password'] ?? "");

// ==========================================
// Validasi
// ==========================================

if ($username == "" || $password == "") {

    $_SESSION['error'] = "Username dan Password wajib diisi.";

    header("Location: " . BASE_URL . "auth/login.php");

    exit();

}

// ==========================================
// Cari User
// ==========================================

$sql = "SELECT * FROM users
        WHERE username = ?
        AND status = 'aktif'
        LIMIT 1";

$stmt = mysqli_prepare($koneksi, $sql);

mysqli_stmt_bind_param($stmt, "s", $username);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

// ==========================================
// Username tidak ditemukan
// ==========================================

if (mysqli_num_rows($result) == 0) {

    $_SESSION['error'] = "Username tidak ditemukan atau akun tidak aktif.";

    header("Location: " . BASE_URL . "auth/login.php");

    exit();

}

$user = mysqli_fetch_assoc($result);

// ==========================================
// Verifikasi Password
// ==========================================

// Jika nanti sudah memakai password_hash()
// cukup ganti bagian IF di bawah menjadi:
//
// if(password_verify($password,$user['password']))
//

if ($password == $user['password']) {

    // ======================================
    // Session Login
    // ======================================

    $_SESSION['login'] = true;

    $_SESSION['id_user'] = $user['id_user'];

    $_SESSION['username'] = $user['username'];

    $_SESSION['nama'] = $user['nama_lengkap'];

    $_SESSION['role'] = $user['role'];

    $_SESSION['foto'] = $user['foto'];

    // ======================================
    // Update Last Login
    // ======================================

    $update = mysqli_prepare(

        $koneksi,

        "UPDATE users
         SET last_login = NOW()
         WHERE id_user = ?"

    );

    mysqli_stmt_bind_param(

        $update,

        "i",

        $user['id_user']

    );

    mysqli_stmt_execute($update);

    // ======================================
    // Redirect Berdasarkan Role
    // ======================================

    switch ($user['role']) {

        case "admin":

            header("Location: " . BASE_URL . "admin/dashboard.php");

            exit();

        case "dosen":

            header("Location: " . BASE_URL . "dosen/dashboard.php");

            exit();

        case "mahasiswa":

            header("Location: " . BASE_URL . "mahasiswa/dashboard.php");

            exit();

        default:

            session_destroy();

            $_SESSION['error'] = "Role tidak dikenali.";

            header("Location: " . BASE_URL . "auth/login.php");

            exit();

    }

}

// ==========================================
// Password Salah
// ==========================================

$_SESSION['error'] = "Password yang Anda masukkan salah.";

header("Location: " . BASE_URL . "auth/login.php");

exit();

?>
```
