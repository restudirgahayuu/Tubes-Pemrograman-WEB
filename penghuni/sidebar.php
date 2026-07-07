<?php
$file = basename($_SERVER['PHP_SELF']);
?>

<div class="sidebar">

<div class="text-center py-4">

<h2 class="text-white">
🌾<br>Padi Residence
</h2>

</div>

<a href="dashboard.php" class="<?= $file=="dashboard.php"?"active":"" ?>">
<i class="bi bi-speedometer2"></i>
Dashboard
</a>

<a href="riwayat.php" class="<?= $file=="riwayat.php"?"active":"" ?>">
<i class="bi bi-clock-history"></i>
Riwayat Pembayaran
</a>

<a href="bayar.php" class="<?= $file=="bayar.php"?"active":"" ?>">
<i class="bi bi-credit-card"></i>
Bayar IPL
</a>

<a href="profil.php" class="<?= $file=="profil.php"?"active":"" ?>">
<i class="bi bi-person-circle"></i>
Profil
</a>

<a href="logout.php">
<i class="bi bi-box-arrow-right"></i>
Logout
</a>

</div>