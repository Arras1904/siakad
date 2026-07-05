<?php
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/session.php';

function attemptLogin($username, $password) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND status = 'aktif'");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id_user'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['nama'] = $user['nama_lengkap'];
        
        // Update last_login
        $update = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id_user = ?");
        $update->execute([$user['id_user']]);

        // Cek profil_lengkap
        $profil_lengkap = 1;
        if ($user['role'] === 'mahasiswa') {
            $stmtMhs = $pdo->prepare("SELECT profil_lengkap FROM mahasiswa WHERE id_user = ?");
            $stmtMhs->execute([$user['id_user']]);
            $mhs = $stmtMhs->fetch();
            if ($mhs) {
                $profil_lengkap = $mhs['profil_lengkap'];
            }
        } elseif ($user['role'] === 'dosen') {
            $stmtDsn = $pdo->prepare("SELECT profil_lengkap FROM dosen WHERE id_user = ?");
            $stmtDsn->execute([$user['id_user']]);
            $dsn = $stmtDsn->fetch();
            if ($dsn) {
                $profil_lengkap = $dsn['profil_lengkap'];
            }
        }
        $_SESSION['profil_lengkap'] = $profil_lengkap;

        return true;
    }
    
    return false;
}

function logoutUser() {
    require_once __DIR__ . '/../config/session.php';
    session_unset();
    session_destroy();
}
?>
