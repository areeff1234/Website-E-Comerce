<?php
session_start();
include 'koneksi.php';

/* ===============================
   ADMIN GUARD (STRICT)
================================ */
if(!isset($_SESSION['admin'])){
    header("location:login_user.php");
    exit;
}

include 'header_admin.php';

/* ===============================
   UPDATE STATUS PESANAN
================================ */
if(isset($_POST['update_status'])){
    $id     = (int)$_POST['id'];
    $status = mysqli_real_escape_string($conn,$_POST['status']);

    mysqli_query($conn,"
        UPDATE pesanan
        SET status='$status'
        WHERE id='$id'
    ");

    echo "<script>
        Swal.fire({
            icon:'success',
            title:'Status diperbarui',
            confirmButtonColor:'#d4af37'
        }).then(()=>location='pesanan.php');
    </script>";
}

/* ===============================
   AMBIL SEMUA PESANAN
================================ */
$q = mysqli_query($conn,"
    SELECT p.*, u.nama AS nama_user
    FROM pesanan p
    LEFT JOIN user u ON p.user_id = u.id
    ORDER BY p.id DESC
");
?>

<style>
:root{
    --navy:#0b1d3a;
    --navy-soft:#12284c;
    --gold:#d4af37;
}

.text-gold{color:var(--gold)}

.order-card{
    background:linear-gradient(180deg,var(--navy),var(--navy-soft));
    color:#fff;
    border-radius:22px;
    box-shadow:0 18px 45px rgba(0,0,0,.25);
}

.badge-status{
    padding:6px 16px;
    border-radius:30px;
    font-size:.8rem;
    font-weight:600;
}

.badge-menunggu{background:#f59e0b;color:#111}
.badge-proses{background:#3b82f6}
.badge-selesai{background:#22c55e}

.order-row{
    display:flex;
    justify-content:space-between;
    font-size:.9rem;
}
</style>

<div class="container my-5">
<h4 class="fw-bold text-gold mb-4">📦 Manajemen Pesanan</h4>

<?php if(mysqli_num_rows($q)==0){ ?>
<div class="alert alert-warning">Belum ada pesanan.</div>
<?php } ?>

<?php while($p=mysqli_fetch_assoc($q)){ ?>

<?php
$cls =
    $p['status']=='Selesai' ? 'badge-selesai' :
    ($p['status']=='Diproses' || $p['status']=='Dikirim'
        ? 'badge-proses'
        : 'badge-menunggu');
?>

<div class="card order-card p-4 mb-4">

<div class="d-flex justify-content-between mb-2">
    <div>
        <b>Pesanan #<?= $p['id'] ?></b><br>
        <small><?= $p['tanggal'] ?></small>
        <div class="small">👤 <?= htmlspecialchars($p['nama_user'] ?? '-') ?></div>
    </div>
    <div class="text-end">
        <div class="fw-bold text-gold fs-5">
            Rp <?= number_format($p['total']) ?>
        </div>
        <span class="badge badge-status <?= $cls ?>">
            <?= $p['status'] ?>
        </span>
    </div>
</div>

<hr>

<?php
$qd = mysqli_query($conn,"
    SELECT d.*, pr.nama
    FROM pesanan_detail d
    JOIN produk pr ON d.produk_id = pr.id
    WHERE d.pesanan_id='{$p['id']}'
");
while($d=mysqli_fetch_assoc($qd)){
?>
<div class="order-row mb-1">
    <span><?= htmlspecialchars($d['nama']) ?> × <?= $d['qty'] ?></span>
    <span>Rp <?= number_format($d['harga'] * $d['qty']) ?></span>
</div>
<?php } ?>

<hr>

<form method="post" class="d-flex gap-2">
<input type="hidden" name="id" value="<?= $p['id'] ?>">

<select name="status" class="form-select form-select-sm" required>
<option <?= $p['status']=='Menunggu'?'selected':'' ?>>Menunggu</option>
<option <?= $p['status']=='Menunggu Pembayaran'?'selected':'' ?>>Menunggu Pembayaran</option>
<option <?= $p['status']=='Menunggu Pengiriman'?'selected':'' ?>>Menunggu Pengiriman</option>
<option <?= $p['status']=='Diproses'?'selected':'' ?>>Diproses</option>
<option <?= $p['status']=='Dikirim'?'selected':'' ?>>Dikirim</option>
<option <?= $p['status']=='Selesai'?'selected':'' ?>>Selesai</option>
</select>

<button name="update_status" class="btn btn-warning btn-sm">
Update Status
</button>
</form>

</div>
<?php } ?>
</div>

<?php include 'footer.php'; ?>
