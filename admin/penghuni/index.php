<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

include("../../config/koneksi.php");

// =========================
// SIMPAN DATA
// =========================
if(isset($_POST['simpan'])){

    $nama      = $_POST['nama'];
    $blok      = $_POST['blok'];
    $no_rumah  = $_POST['no_rumah'];
    $no_hp     = $_POST['no_hp'];
    $username  = $_POST['username'];
    $password  = $_POST['password'];

    mysqli_query($conn,"INSERT INTO penghuni
    (nama,blok,no_rumah,no_hp,username,password)
    VALUES
    ('$nama','$blok','$no_rumah','$no_hp','$username','$password')");

    echo "<script>
    alert('Data berhasil ditambahkan');
    window.location='index.php';
    </script>";

}

$query = mysqli_query($conn, "SELECT * FROM penghuni ORDER BY id_penghuni DESC");
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Data Penghuni</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../../assets/css/style.css">

</head>

<body>

<div class="d-flex">

    <!-- Sidebar -->

    <div class="sidebar">

        <h3 class="text-center text-white mb-4">
            🌾<br>Padi Residence
        </h3>

        <a href="../dashboard.php">
            <i class="bi bi-speedometer2"></i>
            Dashboard
        </a>

        <a href="index.php" class="active">
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

        <a href="../laporan/index.php">
            <i class="bi bi-file-earmark-text"></i>
            Laporan
        </a>

        <a href="../logout.php">
            <i class="bi bi-box-arrow-right"></i>
            Logout
        </a>

    </div>

    <!-- Content -->

    <div class="content">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h2>Data Penghuni</h2>

                <p class="text-muted">

                    Kelola seluruh data penghuni Perumahan Padi Residence.

                </p>

            </div>

            <button
                        class="btn btn-success"
                        data-bs-toggle="modal"
                        data-bs-target="#modalTambah">

                <i class="bi bi-plus-circle"></i>

                Tambah Penghuni

</button>


            </button>

        </div>

        <div class="card shadow border-0">

            <div class="card-body">

                <input
                        type="text"
                        class="form-control mb-3"
                        placeholder="Cari nama penghuni...">

                <table class="table table-hover table-bordered align-middle">

                    <thead class="table-success">

                    <tr>

                        <th width="60">No</th>

                        <th>Nama</th>

                        <th>Blok</th>

                        <th>No Rumah</th>

                        <th>No HP</th>

                        <th>Username</th>

                        <th width="170">Aksi</th>

                    </tr>

                    </thead>

                    <tbody>

                    <?php

                    $no = 1;

                    while($d = mysqli_fetch_assoc($query)){

                    ?>

                        <tr>

                            <td><?= $no++; ?></td>

                            <td><?= $d['nama']; ?></td>

                            <td><?= $d['blok']; ?></td>

                            <td><?= $d['no_rumah']; ?></td>

                            <td><?= $d['no_hp']; ?></td>

                            <td><?= $d['username']; ?></td>

                            <td>

                                <button class="btn btn-warning btn-sm">

                                    <i class="bi bi-pencil-square"></i>

                                </button>

                                <button class="btn btn-danger btn-sm">

                                    <i class="bi bi-trash"></i>

                                </button>

                            </td>

                        </tr>

                    <?php } ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<!-- Modal Tambah -->

<div
class="modal fade"
id="modalTambah">

<div class="modal-dialog">

<div class="modal-content">

<form method="POST">

<div class="modal-header bg-success text-white">

<h5>Tambah Penghuni</h5>

<button
type="button"
class="btn-close"
data-bs-dismiss="modal">

</button>

</div>

<div class="modal-body">

<div class="mb-3">

<label>Nama</label>

<input
type="text"
name="nama"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Blok</label>

<input
type="text"
name="blok"
class="form-control"
placeholder="Contoh : C03"
required>

</div>

<div class="mb-3">

<label>Nomor Rumah</label>

<input
type="text"
name="no_rumah"
class="form-control"
required>

</div>

<div class="mb-3">

<label>No HP</label>

<input
type="text"
name="no_hp"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Username</label>

<input
type="text"
name="username"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Password</label>

<input
type="password"
name="password"
class="form-control"
required>

</div>

</div>

<div class="modal-footer">

<button
class="btn btn-secondary"
data-bs-dismiss="modal"
type="button">

Batal

</button>

<button
class="btn btn-success"
name="simpan">

Simpan

</button>

</div>

</form>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>