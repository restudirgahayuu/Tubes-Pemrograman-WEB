<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

include("../../config/koneksi.php");

// Tambah Tagihan
if(isset($_POST['simpan'])){

    $id_penghuni = $_POST['id_penghuni'];
    $bulan = $_POST['bulan'];
    $tahun = $_POST['tahun'];

    mysqli_query($conn,"INSERT INTO tagihan
    (id_penghuni,bulan,tahun,nominal,jatuh_tempo,status)
    VALUES
    ('$id_penghuni',
    '$bulan',
    '$tahun',
    '100000',
    CURDATE(),
    'Belum Bayar')");

    echo "<script>
    alert('Tagihan berhasil ditambahkan');
    window.location='index.php';
    </script>";
}

// Hapus
if(isset($_GET['hapus'])){

    mysqli_query($conn,"DELETE FROM tagihan
    WHERE id_tagihan='$_GET[hapus]'");

    echo "<script>
    alert('Data berhasil dihapus');
    window.location='index.php';
    </script>";
}

$query = mysqli_query($conn,"
SELECT
tagihan.*,
penghuni.nama,
penghuni.blok,
penghuni.no_rumah
FROM tagihan
JOIN penghuni
ON tagihan.id_penghuni=penghuni.id_penghuni
ORDER BY id_tagihan DESC
");

$penghuni = mysqli_query($conn,"SELECT * FROM penghuni");
?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<title>Data Tagihan</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<link rel="stylesheet" href="../../assets/css/style.css">

</head>

<body>

<div class="d-flex">

<div class="sidebar">

    <h3 class="text-center text-white mb-4">
        🌾<br>Padi Residence
    </h3>

    <a href="../dashboard.php">
        <i class="bi bi-speedometer2"></i>
        Dashboard
    </a>

    <a href="../penghuni/index.php">
        <i class="bi bi-people-fill"></i>
        Data Penghuni
    </a>

    <a href="index.php" class="active">
        <i class="bi bi-cash-stack"></i>
        Data Tagihan
    </a>

    <a href="../pembayaran/index.php">
        <i class="bi bi-credit-card"></i>
        Verifikasi Pembayaran
    </a>

    <a href="../laporan/index.php">
        <i class="bi bi-file-earmark-text"></i>
        Laporan
    </a>

    <a href="../logout.php">
        <i class="bi bi-box-arrow-right"></i>
        Logout
    </a>

</div>

</div>

<div class="content">

<div class="d-flex justify-content-between mb-3">

<h2>Data Tagihan IPL</h2>

<button
class="btn btn-success"
data-bs-toggle="modal"
data-bs-target="#tambah">

Tambah Tagihan

</button>

</div>

<table class="table table-bordered table-hover">

<thead class="table-success">

<tr>

<th>No</th>

<th>Nama</th>

<th>Blok</th>

<th>Bulan</th>

<th>Tahun</th>

<th>Nominal</th>

<th>Status</th>

<th>Aksi</th>

</tr>

</thead>

<tbody>

<?php

$no=1;

while($d=mysqli_fetch_array($query)){

?>

<tr>

<td><?= $no++; ?></td>

<td><?= $d['nama']; ?></td>

<td><?= $d['blok']; ?>-<?= $d['no_rumah']; ?></td>

<td><?= $d['bulan']; ?></td>

<td><?= $d['tahun']; ?></td>

<td>Rp <?= number_format($d['nominal']); ?></td>

<td><?= $d['status']; ?></td>

<td>

<a
href="?hapus=<?= $d['id_tagihan']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Hapus data?')">

<i class="bi bi-trash"></i>

</a>

</td>

</tr>

<?php } ?>

</tbody>

</table>
<!-- Modal Tambah -->
<div class="modal fade" id="tambah" tabindex="-1">

<div class="modal-dialog">

<div class="modal-content">

<form method="POST">

<div class="modal-header bg-success text-white">

<h5 class="modal-title">

Tambah Tagihan

</h5>

<button
type="button"
class="btn-close btn-close-white"
data-bs-dismiss="modal">
</button>

</div>

<div class="modal-body">

<div class="mb-3">

<label>Penghuni</label>

<select
name="id_penghuni"
class="form-control"
required>

<option value="">-- Pilih Penghuni --</option>

<?php
mysqli_data_seek($penghuni,0);
while($p=mysqli_fetch_array($penghuni)){
?>

<option value="<?= $p['id_penghuni']; ?>">

<?= $p['nama']; ?> -
<?= $p['blok']; ?>
No.<?= $p['no_rumah']; ?>

</option>

<?php } ?>

</select>

</div>

<div class="mb-3">

<label>Bulan</label>

<select
name="bulan"
class="form-control"
required>

<option>Januari</option>
<option>Februari</option>
<option>Maret</option>
<option>April</option>
<option>Mei</option>
<option>Juni</option>
<option>Juli</option>
<option>Agustus</option>
<option>September</option>
<option>Oktober</option>
<option>November</option>
<option>Desember</option>

</select>

</div>

<div class="mb-3">

<label>Tahun</label>

<input
type="number"
name="tahun"
class="form-control"
value="<?= date('Y'); ?>"
required>

</div>

<p class="text-success">

Nominal IPL :
<b>Rp100.000</b>

</p>

</div>

<div class="modal-footer">

<button
type="button"
class="btn btn-secondary"
data-bs-dismiss="modal">

Batal

</button>

<button
type="submit"
name="simpan"
class="btn btn-success">

Simpan

</button>

</div>

</form>

</div>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>