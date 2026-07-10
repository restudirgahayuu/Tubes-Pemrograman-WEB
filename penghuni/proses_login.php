<?php
session_start();
include("../config/koneksi.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM penghuni WHERE username='$username' LIMIT 1";
    $query = mysqli_query($conn, $sql);

    if(mysqli_num_rows($query) == 1){

        $data = mysqli_fetch_assoc($query);

        if($password == $data['password']){

            // SESSION
            $_SESSION['penghuni'] = true;
            $_SESSION['id_penghuni'] = $data['id_penghuni'];
            $_SESSION['nama'] = $data['nama'];

            // COOKIE INGAT SAYA
            if(isset($_POST['ingat_saya'])){

                setcookie(
                    "username",
                    $username,
                    time() + (86400 * 7), // Berlaku 7 hari
                    "/"
                );

            }else{

                // Hapus cookie jika tidak dicentang
                setcookie(
                    "username",
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

    }else{

        header("Location: login.php?pesan=gagal");
        exit;

    }

}else{

    header("Location: login.php");
    exit;

}
?>