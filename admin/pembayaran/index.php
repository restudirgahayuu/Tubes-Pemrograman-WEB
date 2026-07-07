<?php
session_start();

if(!isset($_SESSION['admin'])){
    header("Location: ../login.php");
    exit;
}

include("../../config/koneksi.php");

if(isset($_GET['verifikasi'])){

    $id = $_GET['verifikasi'];

    mysqli_query($conn,"
    UPDATE pembayaran
    SET
    status_verifikasi='Diterima',
    tanggal_verifikasi=NOW()
    WHERE id_pembayaran='$id'
    ");

    mysqli_query($conn,"
    UPDATE tagihan
    SET status='Lunas'
    WHERE id_tagihan=(
        SELECT id_tagihan
        FROM pembayaran
        WHERE id_pembayaran='$id'
    )
    ");

    echo "<script>
    alert('Pembayaran berhasil diverifikasi');
    window.location='index.php';
    </script>";
}

$query=mysqli_query($conn,"
SELECT
pembayaran.*,
penghuni.nama,
penghuni.blok,
penghuni.no_rumah,
tagihan.bulan,
tagihan.tahun,
tagihan.nominal

FROM pembayaran

JOIN tagihan
ON pembayaran.id_tagihan=tagihan.id_tagihan

JOIN penghuni
ON tagihan.id_penghuni=penghuni.id_penghuni

ORDER BY pembayaran.id_pembayaran DESC
");
?>

<!DOCTYPE html>

<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Verifikasi Pembayaran</title>

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

<a href="../tagihan/index.php">
<i class="bi bi-cash-stack"></i>
Data Tagihan
</a>

<a href="index.php" class="active">
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

<div class="content">

<h2>Verifikasi Pembayaran</h2>

<p class="text-muted">
Kelola pembayaran penghuni.
</p>

<div class="card shadow border-0">

<div class="card-body">

<table class="table table-bordered table-hover align-middle">

<thead class="table-success">

<tr>

<th>No</th>

<th>Nama Penghuni</th>

<th>Tagihan</th>

<th>Nominal</th>

<th>Bukti</th>

<th>Status</th>

<th width="180">Aksi</th>

</tr>

</thead>

<tbody>

<?php

$no=1;

while($d=mysqli_fetch_assoc($query)){

?>

<tr>

<td><?= $no++; ?></td>

<td>

<?= $d['nama']; ?><br>

<small><?= $d['blok']; ?> No.<?= $d['no_rumah']; ?></small>

</td>

<td>

<?= $d['bulan']; ?>

<?= $d['tahun']; ?>

</td>

<td>

Rp <?= number_format($d['nominal'],0,',','.'); ?>

</td>

<td>

<a
href="../../uploads/<?= $d['bukti']; ?>"
target="_blank"
class="btn btn-info btn-sm">

<i class="bi bi-image"></i>

Lihat

</a>

</td>

<td>

<?php

if($d['status_verifikasi']=="Diterima"){

echo "<span class='badge bg-success'>Terverifikasi</span>";

}else{

echo "<span class='badge bg-warning text-dark'>Menunggu</span>";

}

?>

</td>

<td>

<?php if($d['status_verifikasi']=="Pending"){ ?>

<a
href="?verifikasi=<?= $d['id_pembayaran']; ?>"
class="btn btn-success btn-sm"
onclick="return confirm('Verifikasi pembayaran ini?')">

<i class="bi bi-check-circle"></i>

Verifikasi

</a>

<?php }else{ ?>

<button
class="btn btn-secondary btn-sm"
disabled>

<i class="bi bi-check2-all"></i>

Selesai

</button>

<?php } ?>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>