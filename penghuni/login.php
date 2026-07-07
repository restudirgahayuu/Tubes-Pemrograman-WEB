<?php
session_start();

if(isset($_SESSION['penghuni'])){
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Login Penghuni</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="../assets/css/style.css">

</head>

<body class="bg-light">

<div class="container">

<div class="row justify-content-center align-items-center vh-100">

<div class="col-md-4">

<div class="card shadow border-0">

<div class="card-body p-4">

<h2 class="text-center text-white mb-4">
🌾<br>Padi Residence
</h2>

<h5 class="text-center mb-4">

Login Penghuni

</h5>

<form action="proses_login.php" method="POST">

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

<button
type="submit"
name="login"
class="btn btn-success w-100">

Login

</button>

</form>

<div class="text-center mt-3">

<a href="../index.php">

</a>

</div>

</div>

</div>

</div>

</div>

</div>

</body>

</html>