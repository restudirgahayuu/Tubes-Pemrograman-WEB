<?php
session_start();

if(!isset($_SESSION['penghuni'])){
    header("Location: login.php");
    exit;
}

include("../config/koneksi.php");

$id_penghuni = $_SESSION['id_penghuni'];

$data = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT * FROM penghuni
WHERE id_penghuni='$id_penghuni'
"));

$total = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM tagihan
WHERE id_penghuni='$id_penghuni'
AND status='Belum Bayar'
"));

$lunas = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS lunas
FROM tagihan
WHERE id_penghuni='$id_penghuni'
AND status='Lunas'
"));

$belum = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS belum
FROM tagihan
WHERE id_penghuni='$id_penghuni'
AND status!='Lunas'
"));

$query = mysqli_query($conn,"
SELECT *
FROM tagihan
WHERE id_penghuni='$id_penghuni'
ORDER BY id_tagihan DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">

<title>Dashboard Penghuni</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<link rel="stylesheet" href="../assets/css/penghuni.css">

</head>

<body>

<div>

<?php include "sidebar.php"; ?>

<div class="content">

<h2>Dashboard Penghuni</h2>

<p class="text-muted">
Selamat datang,
<b><?= $data['nama']; ?></b>
</p>

<div class="row mt-4">

<div class="col-md-4">

<div class="card border-0 shadow">

<div class="card-body text-center">

<h6>Total Tagihan</h6>

<h2 class="text-primary">

<?= $total['total']; ?>

</h2>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card border-0 shadow">

<div class="card-body text-center">

<h6>Sudah Lunas</h6>

<h2 class="text-success">

<?= $lunas['lunas']; ?>

</h2>

</div>

</div>

</div>

<div class="col-md-4">

<div class="card border-0 shadow">

<div class="card-body text-center">

<h6>Belum Lunas</h6>

<h2 class="text-danger">

<?= $belum['belum']; ?>

</h2>

</div>

</div>

</div>

</div>

<div class="card shadow border-0 mt-4">

<div class="card-header bg-success text-white">

Tagihan Saya

</div>

<div class="card-body">

<table class="table table-bordered table-hover">

<thead class="table-success">

<tr>

<th>No</th>

<th>Bulan</th>

<th>Tahun</th>

<th>Nominal</th>

<th>Jatuh Tempo</th>

<th>Status</th>

</tr>

</thead>

<tbody>

<?php

$no=1;

while($d=mysqli_fetch_assoc($query)){

?>

<tr>

<td><?= $no++; ?></td>

<td><?= $d['bulan']; ?></td>

<td><?= $d['tahun']; ?></td>

<td>Rp <?= number_format($d['nominal'],0,',','.'); ?></td>

<td><?= $d['jatuh_tempo']; ?></td>

<td>

<?php

if($d['status']=="Lunas"){

echo "<span class='badge bg-success'>Lunas</span>";

}elseif($d['status']=="Menunggu Verifikasi"){

echo "<span class='badge bg-warning text-dark'>Menunggu Verifikasi</span>";

}else{

echo "<span class='badge bg-danger'>Belum Bayar</span>";

}

?>

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