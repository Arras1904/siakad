<?php
require_once "../../config/session.php";
require_once "../../core/Guard.php";
requireRole(['admin']);

$pageTitle = "Rollover Periode";
require_once "../../layout/header.php";
require_once "../../layout/sidebar.php";
require_once "../../layout/navbar.php";

if (!isset($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
?>
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Aktifkan Periode Baru</h1>
    
    <div class="bg-yellow-100 text-yellow-800 p-4 rounded mb-4 border border-yellow-300">
        <strong>Peringatan!</strong> Tindakan ini akan:<br>
        1. Menonaktifkan periode saat ini.<br>
        2. Mendaftarkan periode baru sebagai status 'aktif'.<br>
        3. Menaikkan semester (+1) seluruh mahasiswa yang berstatus 'aktif'.<br>
        4. Meng-generate tagihan biaya kuliah untuk periode baru ke semua mahasiswa aktif.<br>
        Tindakan ini tidak dapat diurungkan dari antarmuka ini.
    </div>

    <form action="proses_tambah.php" method="POST" class="bg-white p-6 rounded shadow max-w-md">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Nama Periode (Misal: 2026/2027)</label>
            <input type="text" name="nama_periode" class="w-full border p-2 rounded" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Jenis Semester</label>
            <select name="jenis_semester" class="w-full border p-2 rounded" required>
                <option value="Ganjil">Ganjil</option>
                <option value="Genap">Genap</option>
            </select>
        </div>
        <div class="flex gap-4 mb-4">
            <div class="w-1/2">
                <label class="block text-gray-700 mb-2">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" class="w-full border p-2 rounded" required>
            </div>
            <div class="w-1/2">
                <label class="block text-gray-700 mb-2">Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" class="w-full border p-2 rounded" required>
            </div>
        </div>
        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded" onclick="return confirm('Apakah Anda benar-benar yakin ingin melakukan rollover semester?')">Jalankan Rollover</button>
        <a href="index.php" class="ml-2 text-gray-600">Batal</a>
    </form>
</div>
<?php require_once "../../layout/footer.php"; ?>