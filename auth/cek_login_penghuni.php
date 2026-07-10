<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['penghuni'])) {

    echo "<script>
        alert('Silakan login terlebih dahulu!');
        window.location='../penghuni/login.php';
    </script>";

    exit;
}
?>