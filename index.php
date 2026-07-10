<?php
include "config/koneksi.php";

// ===========================
// STATISTIK
// ===========================

$total_penghuni = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM penghuni
"));

$total_rumah = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM penghuni
"));

$total_lunas = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM tagihan
WHERE status='Lunas'
"));

$pemasukan = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT SUM(nominal) AS total
FROM tagihan
WHERE status='Lunas'
"));
?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>

Padi Residence | Sistem Informasi Pembayaran IPL

</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<link
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
rel="stylesheet">

<style>

/* ===================================================
   RESET
=================================================== */

*{

margin:0;

padding:0;

box-sizing:border-box;

scroll-behavior:smooth;

}

body{

font-family:'Segoe UI',sans-serif;

background:#f5f7fb;

overflow-x:hidden;

}

/* ===================================================
   NAVBAR
=================================================== */

.navbar{

background:#198754;

padding:18px 0;

}

.navbar-brand{

font-size:24px;

font-weight:bold;

color:#fff !important;

}

.navbar-brand img{

width:42px;

margin-right:10px;

}

.navbar-nav .nav-link{

color:#fff !important;

font-size:18px;

margin-left:18px;

transition:.3s;

}

.navbar-nav .nav-link:hover{

color:#ffd54f !important;

}

/* ===================================================
   HERO
=================================================== */

.hero{

background:linear-gradient(135deg,#198754,#2ec49c);

padding:120px 0;

color:white;

}

.hero h1{

font-size:62px;

font-weight:800;

line-height:1.2;

}

.hero p{

font-size:21px;

margin-top:25px;

line-height:1.8;

}

.hero img{

width:90%;

max-width:420px;

}

.btn-login{

padding:14px 35px;

border-radius:50px;

font-size:18px;

font-weight:600;

margin-right:15px;

margin-top:20px;

}

/* ===================================================
   STATISTIK
=================================================== */

.card{

border:none;

border-radius:22px;

transition:.3s;

}

.card:hover{

transform:translateY(-8px);

box-shadow:0 15px 35px rgba(0,0,0,.12);

}

.card h1{

font-weight:bold;

}

.card h4{

font-weight:bold;

}

/* ===================================================
   FITUR
=================================================== */

#fitur{

padding:90px 0;

background:#fff;

}

#fitur .card{

border-radius:20px;

transition:.3s;

}

#fitur .card:hover{

transform:translateY(-10px);

}

#fitur i{

font-size:60px;

}

/* ===================================================
   TENTANG
=================================================== */

#tentang{

padding:90px 0;

background:#f8f9fa;

}

#tentang p{

font-size:19px;

line-height:1.9;

}

#tentang i{

color:#198754;

}

/* ===================================================
   FOOTER
=================================================== */

.footer{

background:#198754;

color:#fff;

padding:50px 0 20px;

}

.footer a{

color:#fff;

text-decoration:none;

}

.footer a:hover{

color:#ffd54f;

}

/* ===================================================
   BACK TO TOP
=================================================== */

.back-top{

position:fixed;

right:25px;

bottom:25px;

width:55px;

height:55px;

background:#198754;

color:#fff;

display:flex;

align-items:center;

justify-content:center;

border-radius:50%;

font-size:28px;

text-decoration:none;

box-shadow:0 10px 20px rgba(0,0,0,.2);

transition:.3s;

z-index:999;

}

.back-top:hover{

background:#146c43;

color:#fff;

transform:translateY(-5px);

}

/* ===================================================
   RESPONSIVE
=================================================== */

@media(max-width:992px){

.hero{

text-align:center;

padding:80px 0;

}

.hero h1{

font-size:42px;

}

.hero img{

margin-top:40px;

}

}

@media(max-width:576px){

.hero h1{

font-size:34px;

}

.hero p{

font-size:17px;

}

.btn-login{

width:100%;

margin-bottom:15px;

}

}

</style>

</head>
<body id="beranda">

<!-- ==========================
     NAVBAR
========================== -->

<nav class="navbar navbar-expand-lg navbar-dark sticky-top shadow">

<div class="container">

<a class="navbar-brand" href="#beranda">

Padi Residence

</a>

<button
class="navbar-toggler"
type="button"
data-bs-toggle="collapse"
data-bs-target="#menu">

<span class="navbar-toggler-icon"></span>

</button>

<div
class="collapse navbar-collapse"
id="menu">

<ul class="navbar-nav ms-auto">

<li class="nav-item">

<a class="nav-link" href="#beranda">

Beranda

</a>

</li>

<li class="nav-item">

</a>

</li>

<li class="nav-item">

<a class="nav-link" href="#tentang">

Tentang

</a>

</li>

<li class="nav-item dropdown">

<ul class="dropdown-menu dropdown-menu-end">

<li>

<a
class="dropdown-item"
href="admin/login.php">

<i class="bi bi-person-gear"></i>

Login Admin

</a>

</li>

<li>

<a
class="dropdown-item"
href="penghuni/login.php">

<i class="bi bi-house-door"></i>

Login Penghuni

</a>

</li>

</ul>

</li>

</ul>

</div>

</div>

</nav>

<!-- ==========================
     HERO
========================== -->

<section class="hero">

<div class="container">

<div class="row align-items-center">

<div class="col-lg-6">

<h1>

Sistem Informasi

<br>

Pembayaran IPL

</h1>

<p>

Kelola pembayaran Iuran Pengelolaan Lingkungan
Perumahan <strong>Padi Residence</strong>
secara mudah, cepat, aman, dan transparan.

</p>

<a
href="penghuni/login.php"
class="btn btn-light btn-login">

<i class="bi bi-house-door"></i>

Login Penghuni

</a>

<a
href="admin/login.php"
class="btn btn-dark btn-login">

<i class="bi bi-person-gear"></i>

Login Admin

</a>

</div>

<div class="col-lg-6 text-center">

</div>

</div>

</div>

</section>

<!-- ==========================
     TENTANG
========================== -->

<section id="tentang">

<div class="container">

<div class="row g-5">

<div class="col-lg-8">

<h2 class="fw-bold mb-4">

Tentang Padi Residence

</h2>

<p class="text-muted fs-5">

Sistem Informasi Pembayaran IPL Padi Residence merupakan aplikasi berbasis web yang dirancang untuk memudahkan proses pembayaran Iuran Pengelolaan Lingkungan (IPL) secara online.

</p>

<p class="text-muted fs-5">

Melalui sistem ini penghuni dapat melihat tagihan, mengunggah bukti pembayaran, melihat riwayat pembayaran, mengelola profil, sementara admin dapat mengelola penghuni, tagihan, verifikasi pembayaran hingga laporan pembayaran.

</p>

</div>

<div class="col-lg-4">

<h2 class="fw-bold mb-4">

Kontak

</h2>

<p class="fs-5">

<i class="bi bi-geo-alt-fill me-2"></i>

Perumahan Padi Residence

</p>

<p class="fs-5">

<i class="bi bi-envelope-fill me-2"></i>

admin@padiresidence.com

</p>

<p class="fs-5">

<i class="bi bi-telephone-fill me-2"></i>

0812-3456-7890

</p>

</div>

</div>

<hr class="my-5">

<div class="text-center">

<p class="mb-0 fs-5">

© <?= date('Y'); ?>

<strong>Padi Residence</strong>

|

Sistem Informasi Pembayaran IPL

</p>

</div>

</div>

</section>
