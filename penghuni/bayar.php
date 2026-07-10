<?php
require_once "../auth/cek_login_penghuni.php";

include("../config/koneksi.php");

$id_penghuni = $_SESSION['id_penghuni'];

/* ==========================================
   PROSES PEMBAYARAN
========================================== */

if (isset($_POST['bayar'])) {

    $id_tagihan     = mysqli_real_escape_string($conn, $_POST['id_tagihan']);
    $nama_pengirim  = mysqli_real_escape_string($conn, trim($_POST['nama_pengirim']));
    $blok_rumah     = mysqli_real_escape_string($conn, trim($_POST['blok_rumah']));
    $tanggal_bayar  = mysqli_real_escape_string($conn, $_POST['tanggal_bayar']);
    $nominal_bayar  = mysqli_real_escape_string($conn, $_POST['nominal_bayar']);
    $metode         = mysqli_real_escape_string($conn, $_POST['metode_pembayaran']);
    $keterangan     = mysqli_real_escape_string($conn, trim($_POST['keterangan']));

    /* ===============================
       VALIDASI FILE
    =============================== */

    if ($_FILES['bukti']['name'] == "") {

        echo "
        <script>
        alert('Silakan upload bukti pembayaran.');
        window.location='bayar.php';
        </script>
        ";

        exit;
    }

    $folder = "../uploads/";

    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    $nama_file = time() . "_" . basename($_FILES['bukti']['name']);

    $tmp       = $_FILES['bukti']['tmp_name'];
    $ukuran    = $_FILES['bukti']['size'];

    $ekstensi  = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));

    $allowed = [
        "jpg",
        "jpeg",
        "png"
    ];

    if (!in_array($ekstensi, $allowed)) {

        echo "
        <script>
        alert('Bukti pembayaran harus berupa JPG, JPEG atau PNG.');
        window.location='bayar.php';
        </script>
        ";

        exit;
    }

    if ($ukuran > 2 * 1024 * 1024) {

        echo "
        <script>
        alert('Ukuran file maksimal 2 MB.');
        window.location='bayar.php';
        </script>
        ";

        exit;
    }

    move_uploaded_file($tmp, $folder . $nama_file);

    /* ===============================
       SIMPAN PEMBAYARAN
    =============================== */

    mysqli_query($conn, "

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

    /* ===============================
       UPDATE STATUS TAGIHAN
    =============================== */

    mysqli_query($conn, "

    UPDATE tagihan
    SET status='Menunggu Verifikasi'
    WHERE id_tagihan='$id_tagihan'

    ");

    echo "
    <script>
    alert('Pembayaran berhasil dikirim.');
    window.location='bayar.php';
    </script>
    ";

    exit;
}

/* ==========================================
   DATA TAGIHAN BELUM LUNAS
========================================== */

$query = mysqli_query($conn, "

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

<?php include "sidebar.php"; ?>

<div class="content">

<div class="container-fluid">

<h2 class="mb-1">
Pembayaran IPL
</h2>

<p class="text-muted mb-4">
Silakan lakukan pembayaran IPL kemudian kirim bukti pembayaran.
</p>

<div class="card shadow">

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover align-middle">

<thead class="table-success">

<tr>

<th width="60">No</th>

<th>Bulan</th>

<th>Tahun</th>

<th>Nominal</th>

<th>Status</th>

<th width="140">Aksi</th>

</tr>

</thead>

<tbody>

<?php

$no = 1;

if(mysqli_num_rows($query) > 0){

while($d = mysqli_fetch_assoc($query)){

?>
<tr>

<td><?= $no++; ?></td>

<td><?= $d['bulan']; ?></td>

<td><?= $d['tahun']; ?></td>

<td>
Rp <?= number_format($d['nominal'],0,',','.'); ?>
</td>

<td>

<span class="badge bg-danger">
<?= $d['status']; ?>
</span>

</td>

<td>

<button
type="button"
class="btn btn-success btn-sm"
data-bs-toggle="modal"
data-bs-target="#bayar<?= $d['id_tagihan']; ?>">

<i class="bi bi-credit-card"></i>

Bayar

</button>

</td>

</tr>

<!-- ===========================
     MODAL PEMBAYARAN
=========================== -->

<div
class="modal fade"
id="bayar<?= $d['id_tagihan']; ?>"
tabindex="-1">

<div class="modal-dialog modal-lg">

<div class="modal-content">

<form
method="POST"
enctype="multipart/form-data">

<input
type="hidden"
name="id_tagihan"
value="<?= $d['id_tagihan']; ?>">

<div class="modal-header bg-success text-white">

<h5 class="modal-title">

<i class="bi bi-credit-card"></i>

Pembayaran IPL

</h5>

<button
type="button"
class="btn-close btn-close-white"
data-bs-dismiss="modal">
</button>

</div>

<div class="modal-body">

<div class="row">

<div class="col-md-6 mb-3">

<label class="form-label">

Nama Pengirim

</label>

<input
type="text"
name="nama_pengirim"
class="form-control"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Blok Rumah

</label>

<input
type="text"
name="blok_rumah"
class="form-control"
value="<?= $d['blok'] ?? ''; ?>"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Tanggal Bayar

</label>

<input
type="date"
name="tanggal_bayar"
class="form-control"
required>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Nominal Bayar

</label>

<input
type="number"
name="nominal_bayar"
class="form-control"
value="<?= $d['nominal']; ?>"
readonly>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Metode Pembayaran

</label>

<select
name="metode_pembayaran"
class="form-select">

<option value="Transfer Bank">
Transfer Bank
</option>

<option value="QRIS">
QRIS
</option>

<option value="Dana">
Dana
</option>

<option value="OVO">
OVO
</option>

</select>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Bukti Transfer

</label>

<input
type="file"
name="bukti"
class="form-control"
accept=".jpg,.jpeg,.png"
required>

</div>

<div class="col-12">

<label class="form-label">

Keterangan

</label>

<textarea
name="keterangan"
rows="3"
class="form-control"
placeholder="Contoh : Pembayaran IPL Bulan September"></textarea>

</div>

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

<i class="bi bi-upload"></i>

Upload Bukti

</button>

</div>

</form>

</div>

</div>

</div>

<?php
}

}else{
?>

<tr>

<td
colspan="6"
class="text-center">

Tidak ada tagihan yang harus dibayar.

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

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
