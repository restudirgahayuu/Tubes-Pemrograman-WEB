<?php
require_once "../auth/cek_login_penghuni.php";

include("../config/koneksi.php");

$id_penghuni = $_SESSION['id_penghuni'];

/* ===========================================
   AMBIL DATA PENGHUNI
=========================================== */

$query = mysqli_query($conn, "
SELECT *
FROM penghuni
WHERE id_penghuni='$id_penghuni'
");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    session_destroy();
    header("Location: login.php");
    exit;
}


/* ===========================================
   UPDATE PROFIL
=========================================== */

if (isset($_POST['update'])) {

    $nama      = mysqli_real_escape_string($conn, trim($_POST['nama']));
    $no_hp     = mysqli_real_escape_string($conn, trim($_POST['no_hp']));
    $username  = mysqli_real_escape_string($conn, trim($_POST['username']));

    // Validasi Nomor HP
if (!preg_match('/^[0-9]{10,13}$/', $no_hp)) {

    echo "<script>
        alert('Nomor HP hanya boleh berisi angka (10-13 digit).');
        history.back();
    </script>";

    exit;
}

    $foto = $data['foto'];

    if (
        isset($_FILES['foto']) &&
        $_FILES['foto']['name'] != ""
    ) {

        $folder = "../uploads/profil/";

        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        $namaFile = time() . "_" . basename($_FILES['foto']['name']);

        $tmp = $_FILES['foto']['tmp_name'];

        $ukuran = $_FILES['foto']['size'];

        $ext = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));

        $allowed = ["jpg", "jpeg", "png"];

        if (!in_array($ext, $allowed)) {

            echo "
            <script>
            alert('Foto harus JPG, JPEG atau PNG.');
            window.location='profil.php';
            </script>
            ";

            exit;
        }

        if ($ukuran > 2 * 1024 * 1024) {

            echo "
            <script>
            alert('Ukuran foto maksimal 2 MB.');
            window.location='profil.php';
            </script>
            ";

            exit;
        }

        move_uploaded_file($tmp, $folder . $namaFile);

        $foto = $namaFile;
    }

    mysqli_query($conn, "
    UPDATE penghuni
    SET
        nama='$nama',
        no_hp='$no_hp',
        username='$username',
        foto='$foto'
    WHERE id_penghuni='$id_penghuni'
    ");

    echo "
    <script>
    alert('Profil berhasil diperbarui.');
    window.location='profil.php';
    </script>
    ";

    exit;
}


/* ===========================================
   GANTI PASSWORD
=========================================== */

if (isset($_POST['ubah_password'])) {

    $password_lama = trim($_POST['password_lama']);
    $password_baru = trim($_POST['password_baru']);
    $konfirmasi    = trim($_POST['konfirmasi_password']);

    $cek = mysqli_query($conn, "
    SELECT password
    FROM penghuni
    WHERE id_penghuni='$id_penghuni'
    ");

    $hasil = mysqli_fetch_assoc($cek);

    if ($password_lama != $hasil['password']) {

        echo "
        <script>
        alert('Password lama salah.');
        window.location='profil.php';
        </script>
        ";

        exit;
    }

    if ($password_baru != $konfirmasi) {

        echo "
        <script>
        alert('Konfirmasi password tidak sama.');
        window.location='profil.php';
        </script>
        ";

        exit;
    }

    if (strlen($password_baru) < 6) {

        echo "
        <script>
        alert('Password minimal 6 karakter.');
        window.location='profil.php';
        </script>
        ";

        exit;
    }

    mysqli_query($conn, "
    UPDATE penghuni
    SET password='$password_baru'
    WHERE id_penghuni='$id_penghuni'
    ");

    echo "
    <script>
    alert('Password berhasil diubah.');
    window.location='profil.php';
    </script>
    ";

    exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Profil Penghuni</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../assets/css/penghuni.css">

    <style>

        .foto-profil{
            width:160px;
            height:160px;
            border-radius:50%;
            object-fit:cover;
            border:5px solid #198754;
            background:#fff;
        }

        .card-profile{
            border-radius:20px;
        }

        .info th{
            width:180px;
            color:#198754;
            white-space:nowrap;
        }

        .info th,
        .info td{
            padding:12px;
            vertical-align:middle;
        }

        @media(max-width:768px){

            .foto-profil{
                width:120px;
                height:120px;
            }

            .info th{
                width:120px;
                font-size:14px;
            }

            .info td{
                font-size:14px;
            }

        }

    </style>

</head>

<body>

<?php include "sidebar.php"; ?>

<div class="content container-fluid">

<div class="row justify-content-center">

<div class="col-lg-8">

<div class="card shadow-lg card-profile">

<div class="card-body">

<div class="text-center mb-4">

<?php if(empty($data['foto'])){ ?>

<img
src="https://cdn-icons-png.flaticon.com/512/149/149071.png"
class="foto-profil img-fluid">

<?php }else{ ?>

<img
src="../uploads/profil/<?= $data['foto']; ?>"
class="foto-profil img-fluid">

<?php } ?>

<h3 class="mt-3 mb-1">

<?= htmlspecialchars($data['nama']); ?>

</h3>

<p class="text-muted">

Penghuni Padi Residence

</p>

</div>

<hr>

<div class="table-responsive">

<table class="table table-borderless info">

<tbody>

<tr>

<th>

<i class="bi bi-person-fill"></i>

Nama

</th>

<td>

<?= htmlspecialchars($data['nama']); ?>

</td>

</tr>

<tr>

<th>

<i class="bi bi-house-door-fill"></i>

Blok

</th>

<td>

<?= htmlspecialchars($data['blok']); ?>

</td>

</tr>

<tr>

<th>

<i class="bi bi-123"></i>

No Rumah

</th>

<td>

<?= htmlspecialchars($data['no_rumah']); ?>

</td>

</tr>

<tr>

<th>

<i class="bi bi-telephone-fill"></i>

No HP

</th>

<td>

<?= htmlspecialchars($data['no_hp']); ?>

</td>

</tr>

<tr>

<th>

<i class="bi bi-person-badge-fill"></i>

Username

</th>

<td>

<?= htmlspecialchars($data['username']); ?>

</td>

</tr>

</tbody>

</table>

</div>

<div class="text-center mt-4">

<button
class="btn btn-success me-2 mb-2"
data-bs-toggle="modal"
data-bs-target="#editProfil">

<i class="bi bi-pencil-square"></i>

Edit Profil

</button>

<button
class="btn btn-warning mb-2"
data-bs-toggle="modal"
data-bs-target="#gantiPassword">

<i class="bi bi-key-fill"></i>

Ganti Password

</button>

</div>

</div>

</div>

</div>

</div>
<!-- ==================================================
     MODAL EDIT PROFIL
================================================== -->

<div class="modal fade" id="editProfil" tabindex="-1">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <form method="POST" enctype="multipart/form-data">

                <div class="modal-header bg-success text-white">

                    <h5 class="modal-title">

                        <i class="bi bi-pencil-square"></i>

                        Edit Profil

                    </h5>

                    <button
                        type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="row g-4">

                        <!-- FOTO -->

                        <div class="col-md-4 text-center">

                            <?php if(empty($data['foto'])){ ?>

                                <img
                                    src="https://cdn-icons-png.flaticon.com/512/149/149071.png"
                                    class="foto-profil img-fluid mb-3">

                            <?php }else{ ?>

                                <img
                                    src="../uploads/profil/<?= $data['foto']; ?>"
                                    class="foto-profil img-fluid mb-3">

                            <?php } ?>

                            <label class="form-label fw-semibold">

                                Foto Profil

                            </label>

                            <input
                                type="file"
                                name="foto"
                                class="form-control"
                                accept=".jpg,.jpeg,.png">

                            <small class="text-muted">

                                JPG, JPEG, PNG (Maks. 2 MB)

                            </small>

                        </div>

                        <!-- FORM -->

                        <div class="col-md-8">

                            <div class="mb-3">

                                <label class="form-label">

                                    Nama Lengkap

                                </label>

                                <input
                                    type="text"
                                    name="nama"
                                    class="form-control"
                                    value="<?= htmlspecialchars($data['nama']); ?>"
                                    required>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">

                                    Nomor HP

                                </label>

                                <input
                                    type="text"
                                    name="no_hp"
                                    class="form-control"
                                    value="<?= $data['no_hp']; ?>"
                                    placeholder="08xxxxxxxxxx"
                                    maxlength="13"
                                    pattern="[0-9]{10,13}"
                                    inputmode="numeric"
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                                    required>
                            </div>

                            <div class="mb-3">

                                <label class="form-label">

                                    Username

                                </label>

                                <input
                                    type="text"
                                    name="username"
                                    class="form-control"
                                    value="<?= htmlspecialchars($data['username']); ?>"
                                    required>

                            </div>

                            <div class="row">

                                <div class="col-md-6 mb-3">

                                    <label class="form-label">

                                        Blok Rumah

                                    </label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        value="<?= htmlspecialchars($data['blok']); ?>"
                                        readonly>

                                </div>

                                <div class="col-md-6 mb-3">

                                    <label class="form-label">

                                        Nomor Rumah

                                    </label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        value="<?= htmlspecialchars($data['no_rumah']); ?>"
                                        readonly>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Batal

                    </button>

                    <button
                        type="submit"
                        name="update"
                        class="btn btn-success">

                        <i class="bi bi-check-circle"></i>

                        Simpan Perubahan

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>
<!-- ==================================================
     MODAL GANTI PASSWORD
================================================== -->

<div class="modal fade" id="gantiPassword" tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <form method="POST">

                <div class="modal-header bg-warning">

                    <h5 class="modal-title">

                        <i class="bi bi-key-fill"></i>

                        Ganti Password

                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label">

                            Password Lama

                        </label>

                        <input
                            type="password"
                            name="password_lama"
                            class="form-control"
                            placeholder="Masukkan Password Lama"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">

                            Password Baru

                        </label>

                        <input
                            type="password"
                            name="password_baru"
                            class="form-control"
                            placeholder="Minimal 6 karakter"
                            minlength="6"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">

                            Konfirmasi Password Baru

                        </label>

                        <input
                            type="password"
                            name="konfirmasi_password"
                            class="form-control"
                            placeholder="Ulangi Password Baru"
                            minlength="6"
                            required>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Batal

                    </button>

                    <button
                        type="submit"
                        name="ubah_password"
                        class="btn btn-warning">

                        <i class="bi bi-save"></i>

                        Simpan Password

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
