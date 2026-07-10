<?php
require_once "../auth/cek_login_penghuni.php";

include("../config/koneksi.php");

$id_penghuni = $_SESSION['id_penghuni'];

$data = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT *
FROM penghuni
WHERE id_penghuni='$id_penghuni'
"));

$query = mysqli_query($conn,"
SELECT
tagihan.*,
pembayaran.tanggal_upload,
pembayaran.bukti,
pembayaran.status_verifikasi,
pembayaran.tanggal_verifikasi
FROM tagihan
INNER JOIN pembayaran
ON tagihan.id_tagihan=pembayaran.id_tagihan
WHERE tagihan.id_penghuni='$id_penghuni'
ORDER BY pembayaran.id_pembayaran DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">

<title>Riwayat Pembayaran</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<link rel="stylesheet" href="../assets/css/penghuni.css">

</head>

<body>

<div>

<?php include "sidebar.php"; ?>


<div class="content">

<h1 class="mb-1">
Riwayat Pembayaran
</h1>

<p class="text-muted">
Riwayat pembayaran IPL Anda.
</p>

<div class="card shadow">

<div class="card-body">

<table class="table table-bordered align-middle">

<thead>

<tr>

<th>No</th>

<th>Bulan</th>

<th>Tahun</th>

<th>Nominal</th>

<th>Tanggal Upload</th>

<th>Status</th>

<th>Bukti</th>

</tr>

</thead>

<tbody>

<?php

$no=1;

if(mysqli_num_rows($query)>0){

while($d=mysqli_fetch_assoc($query)){

?>

<tr>

<td><?= $no++; ?></td>

<td><?= $d['bulan']; ?></td>

<td><?= $d['tahun']; ?></td>

<td>
Rp <?= number_format($d['nominal'],0,',','.'); ?>
</td>

<td>
<?= $d['tanggal_upload']; ?>
</td>

<td>

<?php

if($d['status_verifikasi']=="Pending"){

echo '<span class="badge bg-warning text-dark">Menunggu</span>';

}elseif($d['status_verifikasi']=="Diterima"){

echo '<span class="badge bg-success">Terverifikasi</span>';

}else{

echo '<span class="badge bg-danger">Ditolak</span>';

}

?>

</td>

<td>

<a
target="_blank"
href="../uploads/<?= $d['bukti']; ?>"
class="btn btn-info btn-sm">

<i class="bi bi-image"></i>

Lihat

</a>

</td>

</tr>

<?php

}

}else{

?>

<tr>

<td colspan="7" class="text-center">

Belum ada riwayat pembayaran.

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>

</div>

</div>

</body>

</html>