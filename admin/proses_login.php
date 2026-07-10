<?php
session_start();

include "../config/koneksi.php";

$username = trim($_POST['username']);
$password = trim($_POST['password']);

$query = mysqli_query($conn,"
SELECT * FROM admin
WHERE username='$username'
AND password='$password'
");

if(mysqli_num_rows($query) > 0){

    $data = mysqli_fetch_assoc($query);

    // SESSION
    $_SESSION['admin'] = $data['nama'];
    $_SESSION['id_admin'] = $data['id_admin'];

    // COOKIE "INGAT SAYA"
    if(isset($_POST['ingat_saya'])){

        setcookie(
            "admin_username",
            $username,
            time() + (86400 * 7), // 7 hari
            "/"
        );

    }else{

        // Hapus cookie jika tidak dicentang
        setcookie(
            "admin_username",
            "",
            time() - 3600,
            "/"
        );

    }

    header("Location: dashboard.php");
    exit;

}else{

    header("Location: login.php?pesan=gagal");
    exit;

}
?>