<?php
require_once "../../auth/cek_login_admin.php";

include("../../config/koneksi.php");

// =========================
// SIMPAN DATA
// =========================
if(isset($_POST['simpan'])){

    $nama = $_POST['nama'];
    $blok = strtoupper($_POST['blok']);
    $no_rumah = $_POST['no_rumah'];
    $no_hp = $_POST['no_hp'];

if (!preg_match('/^[0-9]{10,13}$/', $no_hp)) {

    echo "<script>
        alert('Nomor HP hanya boleh berisi angka.');
        history.back();
    </script>";

    exit;
}

// Username otomatis
$username = strtolower($blok.$no_rumah);

// Ambil nama depan
$nama_depan = strtolower(explode(" ", trim($nama))[0]);

// Password otomatis
$password = strtolower($nama_depan.$blok.$no_rumah);

// =========================
// VALIDASI DATA GANDA
// =========================

$cek = mysqli_query($conn,"
SELECT *
FROM penghuni
WHERE blok='$blok'
AND no_rumah='$no_rumah'
");

if(mysqli_num_rows($cek) > 0){

    echo "<script>

    alert('Penghuni dengan blok dan nomor rumah tersebut sudah terdaftar!');

    window.location='index.php';

    </script>";

    exit;

}

mysqli_query($conn,"
INSERT INTO penghuni
(nama,blok,no_rumah,no_hp,username,password)
VALUES
('$nama','$blok','$no_rumah','$no_hp','$username','$password')");

/* ==========================================
   OTOMATIS MEMBUAT TAGIHAN PERTAMA
========================================== */

$id_penghuni = mysqli_insert_id($conn);

$bulan = date("F");
$tahun = date("Y");
$nominal = 100000;
$jatuh_tempo = date("Y-m-10");

mysqli_query($conn,"
INSERT INTO tagihan
(
id_penghuni,
bulan,
tahun,
nominal,
jatuh_tempo,
status
)
VALUES
(
'$id_penghuni',
'$bulan',
'$tahun',
'$nominal',
'$jatuh_tempo',
'Belum Bayar'
)");

    echo "<script>
    alert('Data penghuni berhasil ditambahkan');
    window.location='index.php';
    </script>";

}

// =========================
// UPDATE DATA
// =========================
if(isset($_POST['update'])){

    $id         = $_POST['id_penghuni'];
    $nama       = $_POST['nama'];
    $blok       = strtoupper($_POST['blok']);
    $no_rumah   = $_POST['no_rumah'];
    $no_hp      = $_POST['no_hp'];

    // Username otomatis
    $username = strtolower($blok.$no_rumah);

    // Password otomatis
    $nama_depan = strtolower(explode(" ", trim($nama))[0]);
    $password = $nama_depan.strtolower($blok).$no_rumah;

    mysqli_query($conn,"UPDATE penghuni SET

        nama='$nama',
        blok='$blok',
        no_rumah='$no_rumah',
        no_hp='$no_hp',
        username='$username',
        password='$password'

        WHERE id_penghuni='$id'
    ");

    echo "<script>

    alert('Data berhasil diubah');

    window.location='index.php';

    </script>";

}

// =========================
// HAPUS DATA
// =========================
if(isset($_GET['hapus'])){

    $id = $_GET['hapus'];

    mysqli_query($conn,"DELETE FROM penghuni WHERE id_penghuni='$id'");

    echo "<script>
    alert('Data berhasil dihapus');
    window.location='index.php';
    </script>";

}

$query = mysqli_query($conn, "SELECT * FROM penghuni ORDER BY id_penghuni DESC");
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Data Penghuni</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../../assets/css/style.css">

</head>

<body>

<div class="d-flex">

    <!-- Sidebar -->

    <div class="sidebar">

        <h3 class="text-center text-white mb-4">
            🌾<br>Padi Residence
        </h3>

        <a href="../dashboard.php">
            <i class="bi bi-speedometer2"></i>
            Dashboard
        </a>

        <a href="index.php" class="active">
            <i class="bi bi-people-fill"></i>
            Data Penghuni
        </a>

        <a href="../tagihan/index.php">
            <i class="bi bi-cash-stack"></i>
            Data Tagihan
        </a>

        <a href="../pembayaran/index.php">
            <i class="bi bi-credit-card"></i>
            Verifikasi Pembayaran
        </a>

        <a href="../laporan/index.php">
            <i class="bi bi-file-earmark-text"></i>
            Laporan
        </a>

        <a href="../logout.php">
            <i class="bi bi-box-arrow-right"></i>
            Logout
        </a>

    </div>

    <!-- Content -->

    <div class="content">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h2>Data Penghuni</h2>

                <p class="text-muted">

                    Kelola seluruh data penghuni Perumahan Padi Residence.

                </p>

            </div>

            <button
                        class="btn btn-success"
                        data-bs-toggle="modal"
                        data-bs-target="#modalTambah">

                <i class="bi bi-plus-circle"></i>

                Tambah Penghuni

            </button>

        </div>

        <div class="card shadow border-0">

            <div class="card-body">

                <table class="table table-hover table-bordered align-middle">

                    <thead class="table-success">

                    <tr>

                        <th width="60">No</th>

                        <th>Nama</th>

                        <th>Blok</th>

                        <th>No Rumah</th>

                        <th>No HP</th>

                        <th width="170">Aksi</th>

                    </tr>

                    </thead>

<tbody>

<?php
$no = 1;

while($d = mysqli_fetch_assoc($query)){
?>

<tr>

    <td><?= $no++; ?></td>

    <td><?= $d['nama']; ?></td>

    <td><?= $d['blok']; ?></td>

    <td><?= $d['no_rumah']; ?></td>

    <td><?= $d['no_hp']; ?></td>

    <td>

        <button
        type="button"
        class="btn btn-warning btn-sm"
        data-bs-toggle="modal"
        data-bs-target="#edit<?= $d['id_penghuni']; ?>">

            <i class="bi bi-pencil-square"></i>

        </button>

        <a
        href="?hapus=<?= $d['id_penghuni']; ?>"
        class="btn btn-danger btn-sm"
        onclick="return confirm('Yakin ingin menghapus data ini?')">

            <i class="bi bi-trash"></i>

        </a>

    </td>

</tr>

<!-- Modal Edit -->

<div
class="modal fade"
id="edit<?= $d['id_penghuni']; ?>"
tabindex="-1">

<div class="modal-dialog">

<div class="modal-content">

<form method="POST">

<input
type="hidden"
name="id_penghuni"
value="<?= $d['id_penghuni']; ?>">

<div class="modal-header bg-warning">

<h5 class="modal-title">
Edit Penghuni
</h5>

<button
type="button"
class="btn-close"
data-bs-dismiss="modal">
</button>

</div>

<div class="modal-body">

<div class="mb-3">

<label>Nama</label>

<input
type="text"
name="nama"
class="form-control"
value="<?= $d['nama']; ?>"
required>

</div>

<div class="mb-3">

<label>Blok</label>

<input
type="text"
name="blok"
class="form-control"
value="<?= $d['blok']; ?>"
required>

</div>

<div class="mb-3">

<label>Nomor Rumah</label>

<input
type="text"
name="no_rumah"
class="form-control"
value="<?= $d['no_rumah']; ?>"
required>

</div>

<div class="mb-3">

<label>No HP</label>

<input
type="text"
name="no_hp"
class="form-control"
value="<?= $d['no_hp']; ?>"
maxlength="13"
pattern="[0-9]+"
inputmode="numeric"
oninput="this.value=this.value.replace(/[^0-9]/g,'')"
placeholder="08xxxxxxxxxx"
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
class="btn btn-warning"
name="update">

Update

</button>

</div>

</form>

</div>

</div>

</div>

<?php } ?>

</tbody>


                </table>

            </div>

        </div>

    </div>

</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <form method="POST">

                <div class="modal-header bg-success text-white">

                    <h5 class="modal-title">
                        Tambah Penghuni
                    </h5>

                    <button
                        type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Nama</label>

                        <input
                            type="text"
                            name="nama"
                            class="form-control"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Blok</label>

                        <input
                            type="text"
                            name="blok"
                            class="form-control"
                            placeholder="Contoh : C03"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nomor Rumah</label>

                        <input
                            type="text"
                            name="no_rumah"
                            class="form-control"
                            placeholder="Contoh : 17"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">No HP</label>

<input
type="text"
name="no_hp"
class="form-control"
maxlength="13"
pattern="[0-9]+"
inputmode="numeric"
oninput="this.value=this.value.replace(/[^0-9]/g,'')"
placeholder="08xxxxxxxxxx"
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
                        class="btn btn-success"
                        name="simpan">

                        Simpan

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>