<?php
session_start();

if(isset($_SESSION['admin'])){
    header("Location: dashboard.php");
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

<body>

<div class="login-box">

<div class="card shadow-lg border-0">

<div class="card-body p-5">

<h2 class="text-center mb-3 text-success">
🌾 Padi Residence
</h2>

<h5 class="text-center text-muted mb-4">
Login Admin IPL
</h5>

<?php

if(isset($_GET['pesan'])){

echo "<div class='alert alert-danger text-center'>
Username atau Password salah!
</div>";

}

?>

<form action="proses_login.php" method="POST">

<div class="mb-3">

<label class="form-label">Username</label>

<input
type="text"
name="username"
class="form-control"
required>

</div>

<div class="mb-4">

<label class="form-label">Password</label>

<input
type="password"
name="password"
class="form-control"
required>

</div>

<button
class="btn btn-success w-100"
type="submit">

LOGIN

</button>

</form>

</div>

</div>

</div>

</body>
</html>