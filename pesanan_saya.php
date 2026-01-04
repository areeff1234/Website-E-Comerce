<?php
session_start();
include 'koneksi.php';
include 'header_user.php';

/* ===============================
   AUTH GUARD
================================ */
if(!isset($_SESSION['user_id'])){
    header("Location: login_user.php");
    exit;
}

$user_id = (int)$_SESSION['user_id'];

/* ===============================
   AMBIL DATA PESANAN USER
================================ */
$q = mysqli_query($conn,"
    SELECT *
    FROM pesanan
    WHERE user_id='$user_id'
    ORDER BY id DESC
");
?>

<style>
.order-card{
    border-radius:18px;
    box-shadow:0 12px 35px rgba(0,0,0,.08);
}
.order-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
}
.order-box{
    background:#0b1d3a;
    color:#fff;
    border-radius:14px;
}
.text-gold{color:#d4af37;}


</style>

<div class="container my-5">
<h4 class="fw-bold text-gold mb-4">📦 Pesanan Saya</h4>

<?php if(mysqli_num_rows($q)==0){ ?>
<div class="alert alert-info">
    Kamu belum memiliki pesanan.
</div>
<?php } ?>

<?php while($p=mysqli_fetch_assoc($q)){ ?>

<?php
// Badge status
$status = $p['status'];
$badge =
    $status=='Selesai' ? 'success' :
    ($status=='Diproses' || $status=='Dikirim' ? 'primary' : 'warning');
?>

<div class="card order-card p-4 mb-4">

<div class="order-header mb-2">
    <div>
        <b>Pesanan #<?= $p['id']; ?></b><br>
        <small class="text-muted">
            <?= date('d M Y', strtotime($p['tanggal'])); ?>
        </small>
    </div>

    <div class="text-end">
        <div class="fw-bold text-gold fs-5">
            Rp <?= number_format($p['total']); ?>
        </div>
        <span class="badge bg-<?= $badge; ?>">
            <?= $status; ?>
        </span>
    </div>
</div>

<hr>

<!-- DETAIL PRODUK -->
<?php
$d = mysqli_query($conn,"
    SELECT d.*, pr.nama, pr.id AS pid
    FROM pesanan_detail d
    JOIN produk pr ON d.produk_id = pr.id
    WHERE d.pesanan_id='{$p['id']}'
");

while($it=mysqli_fetch_assoc($d)){
?>
<div class="d-flex justify-content-between align-items-center mb-2">
    <span><?= htmlspecialchars($it['nama']); ?> × <?= $it['qty']; ?></span>

    <?php if($status=='Selesai'){ ?>
        <a href="detail.php?id=<?= $it['pid']; ?>#review"
           class="btn btn-sm btn-outline-warning">
            ⭐ Review
        </a>
    <?php } else { ?>
        <span class="small text-muted">
            Review tersedia setelah selesai
        </span>
    <?php } ?>
</div>
<?php } ?>

<hr>

<div class="d-flex justify-content-between small text-muted">
    <span>📍 Alamat</span>
    <span><?= nl2br(htmlspecialchars($p['alamat'])); ?></span>
</div>

<div class="d-flex justify-content-between small text-muted">
    <span>🚚 Ongkir</span>
    <span>Rp <?= number_format($p['ongkir']); ?></span>
</div>

</div>
<?php } ?>

</div>

<?php include 'footer.php'; ?>
