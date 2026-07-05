<?php
require_once "../config/session.php";
require_once "../core/Guard.php";
requireRole(['mahasiswa']);
require_once "../config/koneksi.php";

$pageTitle = "Riwayat Nilai (KHS)";
require_once "../layout/header.php";
require_once "../layout/sidebar.php";
require_once "../layout/navbar.php";

$id_user = $_SESSION['user_id'];
$stmtM = $pdo->prepare("SELECT id_mahasiswa FROM mahasiswa WHERE id_user = ?");
$stmtM->execute([$id_user]);
$id_mahasiswa = $stmtM->fetchColumn();

// Ambil Periode Unik yang pernah diikuti (yang ada di history kelasnya)
$periodeList = $pdo->prepare("
    SELECT DISTINCT pa.id_periode, pa.nama_periode, pa.tanggal_mulai
    FROM peserta_kelas pk
    JOIN kelas_perkuliahan kp ON pk.id_kelas_perkuliahan = kp.id_kelas_perkuliahan
    JOIN periode_akademik pa ON kp.id_periode = pa.id_periode
    WHERE pk.id_mahasiswa = ?
    ORDER BY pa.tanggal_mulai DESC
");
$periodeList->execute([$id_mahasiswa]);
$periodes = $periodeList->fetchAll();

$filterPeriode = $_GET['periode'] ?? ($periodes[0]['id_periode'] ?? '');

$listNilai = [];
$ips = 0;
$totalSks = 0;
$totalMutu = 0;

if ($filterPeriode) {
    $qNilai = $pdo->prepare("
        SELECT m.kode_matkul, m.nama_matkul, m.sks, n.tugas, n.uts, n.uas, n.nilai_akhir, n.nilai_huruf, b.bobot, b.lulus
        FROM nilai n
        JOIN peserta_kelas pk ON n.id_peserta_kelas = pk.id_peserta_kelas
        JOIN kelas_perkuliahan kp ON pk.id_kelas_perkuliahan = kp.id_kelas_perkuliahan
        JOIN mata_kuliah m ON kp.id_matkul = m.id_matkul
        LEFT JOIN bobot_nilai b ON n.nilai_huruf = b.nilai_huruf
        WHERE pk.id_mahasiswa = ? AND kp.id_periode = ?
    ");
    $qNilai->execute([$id_mahasiswa, $filterPeriode]);
    $listNilai = $qNilai->fetchAll();

    foreach($listNilai as $nl) {
        $totalSks += $nl['sks'];
        if ($nl['bobot'] !== null) {
            $totalMutu += ($nl['sks'] * $nl['bobot']);
        }
    }
    $ips = $totalSks > 0 ? ($totalMutu / $totalSks) : 0;
}
?>
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Kartu Hasil Studi (KHS)</h1>
    
    <form method="GET" class="mb-4 flex gap-2 max-w-sm">
        <select name="periode" class="w-full border p-2 rounded" onchange="this.form.submit()">
            <?php if(empty($periodes)): ?>
                <option value="">-- Belum ada riwayat periode --</option>
            <?php else: ?>
                <?php foreach($periodes as $p): ?>
                    <option value="<?= $p['id_periode'] ?>" <?= $p['id_periode']==$filterPeriode?'selected':'' ?>><?= htmlspecialchars($p['nama_periode']) ?></option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
        <noscript><button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Lihat</button></noscript>
    </form>

    <?php if($filterPeriode && empty($listNilai)): ?>
        <div class="bg-white p-4 rounded shadow">Nilai untuk periode ini belum tersedia (atau belum diinput dosen).</div>
    <?php elseif($filterPeriode): ?>
        <div class="bg-white rounded shadow p-4">
            <div class="mb-4 flex gap-4">
                <div class="bg-blue-100 px-4 py-2 rounded border border-blue-200">
                    <span class="text-sm text-blue-800">Total SKS Diambil:</span> <strong class="text-xl"><?= $totalSks ?></strong>
                </div>
                <div class="bg-green-100 px-4 py-2 rounded border border-green-200">
                    <span class="text-sm text-green-800">IPS (Semester Ini):</span> <strong class="text-xl"><?= number_format($ips, 2) ?></strong>
                </div>
            </div>

            <table class="min-w-full border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="py-2 px-4 border-b">Kode Matkul</th>
                        <th class="py-2 px-4 border-b">Nama Matkul</th>
                        <th class="py-2 px-4 border-b">SKS</th>
                        <th class="py-2 px-4 border-b">Tugas</th>
                        <th class="py-2 px-4 border-b">UTS</th>
                        <th class="py-2 px-4 border-b">UAS</th>
                        <th class="py-2 px-4 border-b">Nilai Akhir</th>
                        <th class="py-2 px-4 border-b">Huruf</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($listNilai as $nl): ?>
                    <tr>
                        <td class="py-2 px-4 border-b text-center"><?= htmlspecialchars($nl['kode_matkul']) ?></td>
                        <td class="py-2 px-4 border-b"><?= htmlspecialchars($nl['nama_matkul']) ?></td>
                        <td class="py-2 px-4 border-b text-center"><?= $nl['sks'] ?></td>
                        <td class="py-2 px-4 border-b text-center"><?= $nl['tugas'] ?? '-' ?></td>
                        <td class="py-2 px-4 border-b text-center"><?= $nl['uts'] ?? '-' ?></td>
                        <td class="py-2 px-4 border-b text-center"><?= $nl['uas'] ?? '-' ?></td>
                        <td class="py-2 px-4 border-b text-center font-bold"><?= $nl['nilai_akhir'] !== null ? number_format($nl['nilai_akhir'], 2) : '-' ?></td>
                        <td class="py-2 px-4 border-b text-center font-bold text-lg <?= $nl['lulus']==0 ? 'text-red-500' : 'text-green-600' ?>">
                            <?= $nl['nilai_huruf'] ?? '-' ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
<?php require_once "../layout/footer.php"; ?>