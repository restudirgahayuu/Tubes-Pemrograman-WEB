<?php
session_start();

if(!isset($_SESSION['penghuni'])){
    header("Location: login.php");
    exit;
}

include("../config/koneksi.php");

$id_penghuni = $_SESSION['id_penghuni'];

if(isset($_POST['bayar'])){

$id_tagihan=$_POST['id_tagihan'];

$nama_pengirim=$_POST['nama_pengirim'];

$blok_rumah=$_POST['blok_rumah'];

$tanggal_bayar=$_POST['tanggal_bayar'];

$nominal_bayar=$_POST['nominal_bayar'];

$metode=$_POST['metode_pembayaran'];

$keterangan=$_POST['keterangan'];

$nama_file=time()."_".$_FILES['bukti']['name'];

$tmp=$_FILES['bukti']['tmp_name'];

if(!is_dir("../uploads")){
mkdir("../uploads");
}

move_uploaded_file($tmp,"../uploads/".$nama_file);

mysqli_query($conn,"
INSERT INTO pembayaran
(
id_tagihan,
nama_pengirim,
blok_rumah,
nominal_bayar,
metode_pembayaran,
tanggal_upload,
bukti,
keterangan,
status_verifikasi
)

VALUES

(
'$id_tagihan',
'$nama_pengirim',
'$blok_rumah',
'$nominal_bayar',
'$metode',
'$tanggal_bayar',
'$nama_file',
'$keterangan',
'Pending'
)
");

mysqli_query($conn,"
UPDATE tagihan
SET status='Menunggu Verifikasi'
WHERE id_tagihan='$id_tagihan'
");

echo "<script>

alert('Pembayaran berhasil dikirim');

window.location='bayar.php';

</script>";

}

$query = mysqli_query($conn,"
SELECT *
FROM tagihan
WHERE id_penghuni='$id_penghuni'
AND status='Belum Bayar'
ORDER BY id_tagihan DESC
");
?>

<!DOCTYPE html>

<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Pembayaran IPL</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<link rel="stylesheet" href="../assets/css/penghuni.css">

</head>

<body>

<div>

<?php include "sidebar.php"; ?>

<div class="content">

<h2>Pembayaran IPL</h2>

<p class="text-muted">
Silakan lakukan pembayaran IPL kemudian kirim bukti pembayaran.
</p>

<div class="card shadow border-0">

<div class="card-body">

<table class="table table-bordered table-hover">

<thead class="table-success">

<tr>

<th>No</th>

<th>Bulan</th>

<th>Tahun</th>

<th>Nominal</th>

<th>Status</th>

<th width="150">Aksi</th>

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

<td><?= $d['status']; ?></td>

<td>

<button
class="btn btn-success btn-sm"
data-bs-toggle="modal"
data-bs-target="#bayar<?= $d['id_tagihan']; ?>">

<i class="bi bi-credit-card"></i>

Bayar

</button>

</td>

</tr>

<!-- Modal Bayar IPL -->

<div
class="modal fade"
id="bayar<?= $d['id_tagihan']; ?>">

<div class="modal-dialog">

<div class="modal-content">

<form
method="POST"
enctype="multipart/form-data">

<input
type="hidden"
name="id_tagihan"
value="<?= $d['id_tagihan']; ?>">

<div class="modal-header bg-success text-white">

<h5>Pembayaran IPL</h5>

<button
type="button"
class="btn-close btn-close-white"
data-bs-dismiss="modal">

</button>

</div>

<div class="modal-body">

<div class="mb-3">
<label>Nama Pengirim</label>
<input type="text" name="nama_pengirim" class="form-control" required>
</div>

<div class="mb-3">
<label>Blok Rumah</label>
<input type="text" name="blok_rumah" class="form-control" value="<?= $d['blok'] ?? ''; ?>" required>
</div>

<div class="mb-3">
<label>Tanggal Bayar</label>
<input type="date" name="tanggal_bayar" class="form-control" required>
</div>

<div class="mb-3">
<label>Nominal Bayar</label>
<input type="number" name="nominal_bayar" class="form-control" value="<?= $d['nominal']; ?>" required>
</div>

<div class="mb-3">
<label>Metode Pembayaran</label>
<select name="metode_pembayaran" class="form-control">
<option value="Transfer Bank">Transfer Bank</option>
<option value="QRIS">QRIS</option>
<option value="Dana">Dana</option>
<option value="OVO">OVO</option>
</select>
</div>

<div class="mb-3">
<label>Bukti Transfer</label>
<input type="file" name="bukti" class="form-control" required>
</div>

<div class="mb-3">
<label>Keterangan</label>
<textarea name="keterangan" class="form-control" rows="3"></textarea>
</div>

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
name="bayar"
class="btn btn-success">

Upload

</button>

</div>

</form>

</div>

</div>

</div>

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