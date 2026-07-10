<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin'])) {

    echo "<script>
        alert('Silakan login sebagai Admin terlebih dahulu!');
        window.location='../admin/login.php';
    </script>";

    exit;
}
?>