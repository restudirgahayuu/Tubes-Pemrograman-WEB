<?php
require_once "../../auth/cek_login_admin.php";

include("../../config/koneksi.php");

//=============================
// FILTER
//=============================

$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : "";
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : "";

//=============================
// QUERY STATISTIK
//=============================

$total_penghuni = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM penghuni
"));

$total_lunas = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM tagihan
WHERE status='Lunas'
"));

$total_belum = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM tagihan
WHERE status='Belum Bayar'
"));

$total_verifikasi = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM tagihan
WHERE status='Menunggu Verifikasi'
"));

$total_pemasukan = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT SUM(nominal) AS total
FROM tagihan
WHERE status='Lunas'
"));

//=============================
// QUERY LAPORAN
//=============================

$sql="SELECT
tagihan.*,
penghuni.nama,
penghuni.blok,
penghuni.no_rumah

FROM tagihan

JOIN penghuni
ON tagihan.id_penghuni=penghuni.id_penghuni

WHERE 1=1 ";

if($bulan!=""){
$sql.=" AND bulan='$bulan'";
}

if($tahun!=""){
$sql.=" AND tahun='$tahun'";
}

$sql.=" ORDER BY tagihan.id_tagihan DESC";

$query=mysqli_query($conn,$sql);

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1">

<title>Laporan Pembayaran</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<link rel="stylesheet"
href="../../assets/css/style.css">

<style>

@media print{

.sidebar,
.btn,
form{
display:none;
}

.content{
margin-left:0;
}

}

</style>

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

<a href="../pembayaran/index.php">
<i class="bi bi-credit-card"></i>
Verifikasi Pembayaran
</a>

<a href="index.php" class="active">
<i class="bi bi-file-earmark-text"></i>
Laporan
</a>

<a href="../logout.php">
<i class="bi bi-box-arrow-right"></i>
Logout
</a>

</div>

<div class="content">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h2>Laporan Pembayaran IPL</h2>

<p class="text-muted">
Rekap seluruh pembayaran penghuni.
</p>

</div>

<button
class="btn btn-success"
onclick="window.print()">

<i class="bi bi-printer"></i>

Cetak Laporan

</button>

</div>
<div class="row mb-4">

<div class="col-md-3">

<div class="card shadow border-0">

<div class="card-body text-center">

<h6>Total Penghuni</h6>

<h2 class="text-primary">

<?= $total_penghuni['total']; ?>

</h2>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card shadow border-0">

<div class="card-body text-center">

<h6>Tagihan Lunas</h6>

<h2 class="text-success">

<?= $total_lunas['total']; ?>

</h2>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card shadow border-0">

<div class="card-body text-center">

<h6>Menunggu Verifikasi</h6>

<h2 class="text-warning">

<?= $total_verifikasi['total']; ?>

</h2>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card shadow border-0">

<div class="card-body text-center">

<h6>Belum Bayar</h6>

<h2 class="text-danger">

<?= $total_belum['total']; ?>

</h2>

</div>

</div>

</div>

</div>

<div class="card shadow border-0 mb-4">

<div class="card-body">

<form method="GET">

<div class="row g-3 align-items-end">

    <div class="col-md-3">

        <label class="form-label">Bulan</label>

        <select name="bulan" class="form-control">

            <option value="">Semua Bulan</option>

            <option value="Januari">Januari</option>
            <option value="Februari">Februari</option>
            <option value="Maret">Maret</option>
            <option value="April">April</option>
            <option value="Mei">Mei</option>
            <option value="Juni">Juni</option>
            <option value="Juli">Juli</option>
            <option value="Agustus">Agustus</option>
            <option value="September">September</option>
            <option value="Oktober">Oktober</option>
            <option value="November">November</option>
            <option value="Desember">Desember</option>

        </select>

    </div>

    <div class="col-md-3">

        <label class="form-label">Tahun</label>

        <input
            type="text"
            name="tahun"
            class="form-control"
            placeholder="2026"
            maxlength="4"
            oninput="this.value=this.value.replace(/[^0-9]/g,'')"
            value="<?= isset($_GET['tahun']) ? $_GET['tahun'] : ''; ?>">

    </div>

    <div class="col-md-4 d-flex align-items-end">

        <button class="btn btn-success me-2">
            <i class="bi bi-funnel"></i>
            Filter
        </button>

        <a href="index.php" class="btn btn-secondary">
            Reset
        </a>

    </div>

</div>

</form>

</div>

</div>

<div class="card shadow border-0">

<div class="card-header bg-success text-white">

<h5 class="mb-0">

Data Laporan Pembayaran

</h5>

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover align-middle">

<thead class="table-success">

<tr>

<th>No</th>

<th>Nama</th>

<th>Blok</th>

<th>Bulan</th>

<th>Tahun</th>

<th>Nominal</th>

<th>Status</th>

</tr>

</thead>

<tbody>

<?php

$no=1;

while($data=mysqli_fetch_assoc($query)){

?>
<tr>

<td><?= $no++; ?></td>

<td><?= $data['nama']; ?></td>

<td>

<?= $data['blok']; ?>

No.<?= $data['no_rumah']; ?>

</td>

<td><?= $data['bulan']; ?></td>

<td><?= $data['tahun']; ?></td>

<td>

Rp <?= number_format($data['nominal'],0,',','.'); ?>

</td>

<td>

<?php

if($data['status']=="Lunas"){

?>

<span class="badge bg-success">

Lunas

</span>

<?php

}elseif($data['status']=="Menunggu Verifikasi"){

?>

<span class="badge bg-warning text-dark">

Menunggu Verifikasi

</span>

<?php

}else{

?>

<span class="badge bg-danger">

Belum Bayar

</span>

<?php

}

?>

</td>

</tr>

<?php

}

?>

</tbody>

<tfoot class="table-light">

<tr>

<th colspan="5" class="text-end">

Total Pemasukan

</th>

<th colspan="2" class="text-success">

Rp <?= number_format($total_pemasukan['total'],0,',','.'); ?>

</th>

</tr>

</tfoot>

</table>

</div>

</div>

</div>

<div class="text-center mt-5">

<hr>

<p>

<b>Padi Residence</b>

<br>

Laporan Pembayaran IPL

<br>

Dicetak pada :

<?= date('d F Y'); ?>

</p>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>