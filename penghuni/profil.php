<?php
session_start();

if (!isset($_SESSION['penghuni'])) {
    header("Location: login.php");
    exit;
}

include("../config/koneksi.php");

$id_penghuni = $_SESSION['id_penghuni'];

$query = mysqli_query($conn,"
SELECT *
FROM penghuni
WHERE id_penghuni='$id_penghuni'
");

$data = mysqli_fetch_assoc($query);


/* ==================================================
   UPDATE PROFIL
================================================== */

if(isset($_POST['update'])){

    $nama       = mysqli_real_escape_string($conn,$_POST['nama']);
    $no_hp      = mysqli_real_escape_string($conn,$_POST['no_hp']);
    $username   = mysqli_real_escape_string($conn,$_POST['username']);

    $foto = $data['foto'];

    if($_FILES['foto']['name']!=""){

        if(!is_dir("../uploads/profil")){
            mkdir("../uploads/profil",0777,true);
        }

        $foto = time()."_".$_FILES['foto']['name'];

        move_uploaded_file(
            $_FILES['foto']['tmp_name'],
            "../uploads/profil/".$foto
        );
    }

    mysqli_query($conn,"
    UPDATE penghuni
    SET
        nama='$nama',
        no_hp='$no_hp',
        username='$username',
        foto='$foto'
    WHERE id_penghuni='$id_penghuni'
    ");

    echo "
    <script>
    alert('Profil berhasil diperbarui');
    window.location='profil.php';
    </script>
    ";

    exit;
}



/* ==================================================
   GANTI PASSWORD
================================================== */

if(isset($_POST['ubah_password'])){

    $password_lama = mysqli_real_escape_string($conn,$_POST['password_lama']);
    $password_baru = mysqli_real_escape_string($conn,$_POST['password_baru']);
    $konfirmasi    = mysqli_real_escape_string($conn,$_POST['konfirmasi_password']);

    $cek = mysqli_query($conn,"
    SELECT password
    FROM penghuni
    WHERE id_penghuni='$id_penghuni'
    ");

    $hasil = mysqli_fetch_assoc($cek);

    if($password_lama != $hasil['password']){

        echo "
        <script>
        alert('Password lama salah!');
        window.location='profil.php';
        </script>
        ";

        exit;

    }

    if($password_baru != $konfirmasi){

        echo "
        <script>
        alert('Konfirmasi password tidak sama!');
        window.location='profil.php';
        </script>
        ";

        exit;

    }

    mysqli_query($conn,"
    UPDATE penghuni
    SET password='$password_baru'
    WHERE id_penghuni='$id_penghuni'
    ");

    echo "
    <script>
    alert('Password berhasil diubah');
    window.location='profil.php';
    </script>
    ";

    exit;

}
?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Profil Penghuni</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<link rel="stylesheet" href="../assets/css/penghuni.css">

<style>


.foto-profil{

width:160px;

height:160px;

border-radius:50%;

object-fit:cover;

border:5px solid #198754;

}

.info td{

padding:12px;

font-size:16px;

}

.info th{

padding:12px;

width:180px;

color:#198754;

}

</style>

</head>

<body>

<div>

<?php include "sidebar.php"; ?>

<div class="content">

<div class="container-fluid">

<div class="card shadow-lg">

<div class="card-body">

<div class="text-center">

<?php if($data['foto']==""){ ?>

<img
src="https://cdn-icons-png.flaticon.com/512/149/149071.png"
class="foto-profil">

<?php }else{ ?>

<img
src="../uploads/profil/<?= $data['foto']; ?>"
class="foto-profil">

<?php } ?>

<h3 class="mt-3">

<?= $data['nama']; ?>

</h3>

<p class="text-muted">

Penghuni Padi Residence

</p>

<hr>

<table class="table table-borderless info">

<tr>

<th>

<i class="bi bi-person-fill"></i>

Nama

</th>

<td>

<?= $data['nama']; ?>

</td>

</tr>

<tr>

<th>

<i class="bi bi-house-door-fill"></i>

Blok

</th>

<td>

<?= $data['blok']; ?>

</td>

</tr>

<tr>

<th>

<i class="bi bi-123"></i>

No Rumah

</th>

<td>

<?= $data['no_rumah']; ?>

</td>

</tr>

<tr>

<th>

<i class="bi bi-telephone-fill"></i>

No HP

</th>

<td>

<?= $data['no_hp']; ?>

</td>

</tr>

<tr>

<th>

<i class="bi bi-person-badge-fill"></i>

Username

</th>

<td>

<?= $data['username']; ?>

</td>

</tr>

</table>

<div class="mt-4">

<button
class="btn btn-success"
data-bs-toggle="modal"
data-bs-target="#editProfil">

<i class="bi bi-pencil-square"></i>

Edit Profil

</button>

<button
class="btn btn-warning"
data-bs-toggle="modal"
data-bs-target="#gantiPassword">

<i class="bi bi-key-fill"></i>

Ganti Password

</button>

</div>

</div>

</div>

</div>

<!-- =======================
     MODAL EDIT PROFIL
======================= -->

<div class="modal fade" id="editProfil" tabindex="-1">

<div class="modal-dialog modal-lg">

<div class="modal-content">

<form method="POST" enctype="multipart/form-data">

<div class="modal-header bg-success text-white">

<h5 class="modal-title">

<i class="bi bi-pencil-square"></i>

Edit Profil

</h5>

<button
type="button"
class="btn-close btn-close-white"
data-bs-dismiss="modal">

</button>

</div>

<div class="modal-body">

<div class="row">

<div class="col-md-4 text-center">

<?php if($data['foto']==""){ ?>

<img
src="https://cdn-icons-png.flaticon.com/512/149/149071.png"
class="foto-profil mb-3">

<?php }else{ ?>

<img
src="../uploads/profil/<?= $data['foto']; ?>"
class="foto-profil mb-3">

<?php } ?>

<input
type="file"
name="foto"
class="form-control">

</div>

<div class="col-md-8">

<div class="mb-3">

<label class="form-label">

Nama Lengkap

</label>

<input
type="text"
name="nama"
class="form-control"
value="<?= $data['nama']; ?>"
required>

</div>

<div class="mb-3">

<label class="form-label">

No HP

</label>

<input
type="text"
name="no_hp"
class="form-control"
value="<?= $data['no_hp']; ?>"
required>

</div>

<div class="mb-3">

<label class="form-label">

Username

</label>

<input
type="text"
name="username"
class="form-control"
value="<?= $data['username']; ?>"
required>

</div>

<div class="row">

<div class="col-md-6">

<label class="form-label">

Blok

</label>

<input
type="text"
class="form-control"
value="<?= $data['blok']; ?>"
readonly>

</div>

<div class="col-md-6">

<label class="form-label">

No Rumah

</label>

<input
type="text"
class="form-control"
value="<?= $data['no_rumah']; ?>"
readonly>

</div>

</div>

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
name="update"
class="btn btn-success">

<i class="bi bi-check-circle"></i>

Simpan Perubahan

</button>

</div>

</form>

</div>

</div>

</div>

<!-- =======================
     MODAL GANTI PASSWORD
======================= -->

<div class="modal fade" id="gantiPassword" tabindex="-1">

<div class="modal-dialog">

<div class="modal-content">

<form method="POST">

<div class="modal-header bg-warning">

<h5 class="modal-title">

<i class="bi bi-key-fill"></i>

Ganti Password

</h5>

<button
type="button"
class="btn-close"
data-bs-dismiss="modal">

</button>

</div>

<div class="modal-body">

<div class="mb-3">

<label class="form-label">

Password Lama

</label>

<input
type="password"
name="password_lama"
class="form-control"
required>

</div>

<div class="mb-3">

<label class="form-label">

Password Baru

</label>

<input
type="password"
name="password_baru"
class="form-control"
required>

</div>

<div class="mb-3">

<label class="form-label">

Konfirmasi Password Baru

</label>

<input
type="password"
name="konfirmasi_password"
class="form-control"
required>

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
name="ubah_password"
class="btn btn-warning">

<i class="bi bi-save"></i>

Simpan Password

</button>

</div>

</form>

</div>

</div>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>