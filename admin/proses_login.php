<?php

session_start();

include "../config/koneksi.php";

$username = $_POST['username'];
$password = $_POST['password'];

$query = mysqli_query($conn,"SELECT * FROM admin
WHERE username='$username'
AND password='$password'");

$data = mysqli_fetch_assoc($query);

if(mysqli_num_rows($query)>0){

$_SESSION['admin']=$data['nama'];

header("Location: dashboard.php");

}else{

header("Location: login.php?pesan=gagal");

}

?>