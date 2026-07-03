<?php
session_start();

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

include("../config/koneksi.php");

$total_penghuni = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM penghuni"));

$total_tagihan = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM tagihan"));

$sudah_bayar = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM tagihan WHERE status='Lunas'"));

$belum_bayar = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM tagihan WHERE status='Belum Bayar'"));

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Dashboard Admin</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>

<div class="d-flex">

    <!-- Sidebar -->

    <div class="sidebar">

        <h3 class="text-center mb-4">
            🌾
            <br>
            Padi Residence
        </h3>

        <a href="dashboard.php">
            <i class="bi bi-speedometer2"></i>
            Dashboard
        </a>

        <a href="penghuni/index.php">
            <i class="bi bi-people-fill"></i>
            Data Penghuni
        </a>

        <a href="tagihan/index.php">
            <i class="bi bi-cash-stack"></i>
            Data Tagihan
        </a>

        <a href="pembayaran/index.php">
            <i class="bi bi-credit-card"></i>
            Verifikasi Pembayaran
        </a>

        <a href="laporan/index.php">
            <i class="bi bi-file-earmark-bar-graph"></i>
            Laporan
        </a>

        <a href="logout.php">
            <i class="bi bi-box-arrow-right"></i>
            Logout
        </a>

    </div>

    <!-- Content -->

    <div class="content">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h2>Dashboard Admin</h2>

                <p class="text-muted">

                    Selamat datang,
                    <b><?php echo $_SESSION['admin']; ?></b>

                </p>

            </div>

        </div>

        <div class="row">

            <div class="col-md-3">

                <div class="card shadow-sm border-0 card-dashboard">

                    <div class="card-body">

                        <i class="bi bi-people-fill fs-1 text-success"></i>

                        <h3><?php echo $total_penghuni; ?></h3>

                        <p>Total Penghuni</p>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card shadow-sm border-0 card-dashboard">

                    <div class="card-body">

                        <i class="bi bi-cash-stack fs-1 text-primary"></i>

                        <h3><?php echo $total_tagihan; ?></h3>

                        <p>Total Tagihan</p>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card shadow-sm border-0 card-dashboard">

                    <div class="card-body">

                        <i class="bi bi-check-circle-fill fs-1 text-success"></i>

                        <h3><?php echo $sudah_bayar; ?></h3>

                        <p>Sudah Bayar</p>

                    </div>

                </div>

            </div>

            <div class="col-md-3">

                <div class="card shadow-sm border-0 card-dashboard">

                    <div class="card-body">

                        <i class="bi bi-x-circle-fill fs-1 text-danger"></i>

                        <h3><?php echo $belum_bayar; ?></h3>

                        <p>Belum Bayar</p>

                    </div>

                </div>

            </div>

        </div>

        <div class="card mt-4 shadow-sm border-0">

            <div class="card-body">

                <h4>

                    Selamat Datang di Sistem Informasi IPL
                    Perumahan Padi Residence

                </h4>

                <hr>

                <p>

                    Gunakan menu di sebelah kiri untuk mengelola data penghuni,
                    tagihan IPL, pembayaran, dan laporan.

                </p>

            </div>

        </div>

    </div>

</div>

</body>
</html>