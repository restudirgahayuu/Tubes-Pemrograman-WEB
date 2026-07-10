<?php
session_start();

if(isset($_SESSION['admin'])){
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Login Admin</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="../assets/css/style.css">

</head>

<body class="bg-light">

<div class="container">

<div class="row justify-content-center align-items-center vh-100">

<div class="col-md-6 col-lg-5">

<div class="card shadow-lg border-0 rounded-4">

<div class="card-body p-5">

<h2 class="text-center text-success mb-2">
🌾 Padi Residence
</h2>

<h5 class="text-center text-muted mb-4">
Login Admin IPL
</h5>

<?php
if(isset($_GET['pesan'])){
?>

<div class="alert alert-danger text-center">
Username atau Password salah!
</div>

<?php
}
?>

<form action="proses_login.php" method="POST">

<div class="mb-3">

<label class="form-label">
Username
</label>

<input
type="text"
name="username"
class="form-control"
placeholder="Masukkan Username"
value="<?= isset($_COOKIE['admin_username']) ? $_COOKIE['admin_username'] : ''; ?>"
required>

</div>

<div class="mb-3">

<label class="form-label">
Password
</label>

<input
type="password"
name="password"
class="form-control"
placeholder="Masukkan Password"
required>

</div>

<div class="form-check mb-4">

<input
class="form-check-input"
type="checkbox"
name="ingat_saya"
id="ingat_saya">

<label
class="form-check-label"
for="ingat_saya">

Ingat Saya

</label>

</div>

<button
type="submit"
class="btn btn-success w-100">

LOGIN

</button>

</form>

</div>

</div>

</div>

</div>

</div>

</body>
</html>