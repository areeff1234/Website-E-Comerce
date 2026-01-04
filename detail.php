<?php
session_start();
include 'koneksi.php';
include 'header_user.php';

/* ===============================
   VALIDASI ID PRODUK
================================ */
if(!isset($_GET['id'])){
    header("Location:index.php");
    exit;
}

$id = (int)$_GET['id'];
$q  = mysqli_query($conn,"SELECT * FROM produk WHERE id='$id' LIMIT 1");
$p  = mysqli_fetch_assoc($q);

if(!$p){
    echo "<div class='container mt-5'>
            <div class='alert alert-danger'>Produk tidak ditemukan.</div>
            <a href='index.php' class='btn btn-dark mt-3'>← Kembali</a>
          </div>";
    include 'footer.php';
    exit;
}

/* ===============================
   HITUNG HARGA (SINKRON)
================================ */
$harga_awal  = $p['harga'];
$diskon      = (int)($p['diskon'] ?? 0);
$harga_final = $diskon > 0
    ? $harga_awal - ($harga_awal * $diskon / 100)
    : $harga_awal;

/* ===============================
   RATING PRODUK
================================ */
$rq = mysqli_query($conn,"
    SELECT AVG(rating) AS rata, COUNT(*) AS total
    FROM review
    WHERE produk_id='$id'
");
$r = mysqli_fetch_assoc($rq);

$rata_rating  = $r['rata'] ? round($r['rata'],1) : 0;
$total_review = (int)$r['total'];

/* ===============================
   CEK SUDAH BELI
================================ */
$sudah_beli = false;
if(isset($_SESSION['user_id'])){
    $uid = (int)$_SESSION['user_id'];
    $cek = mysqli_query($conn,"
        SELECT p.id
        FROM pesanan p
        JOIN pesanan_detail d ON p.id = d.pesanan_id
        WHERE p.user_id='$uid'
          AND d.produk_id='$id'
          AND p.status='Selesai'
        LIMIT 1
    ");
    if(mysqli_num_rows($cek)>0){
        $sudah_beli = true;
    }
}

/* ===============================
   PATH GAMBAR
================================ */
$imgPath = __DIR__."/img/".$p['gambar'];
$img = (!empty($p['gambar']) && file_exists($imgPath))
    ? "img/".$p['gambar']
    : "https://via.placeholder.com/500x500?text=No+Image";
?>

<style>
.detail-card{border-radius:22px;box-shadow:0 18px 45px rgba(0,0,0,.08)}
.price-final{color:#d4af37;font-weight:800}
.rating{color:#f4c150;font-size:1.1rem}
.badge-stock{padding:6px 16px;border-radius:30px;font-size:.85rem}
.review-card{border-radius:18px;box-shadow:0 10px 30px rgba(0,0,0,.08)}
.form-review{position:sticky;top:110px}
</style>

<div class="container my-5">

<a href="index.php" class="btn btn-outline-dark mb-4">← Kembali</a>

<div class="row g-5">

<div class="col-md-5">
    <div class="detail-card p-3">
        <img src="<?= $img ?>" class="img-fluid rounded-4">
    </div>
</div>

<div class="col-md-7">
    <h3 class="fw-bold"><?= htmlspecialchars($p['nama']); ?></h3>

    <div class="rating mb-2">
        <?php for($i=1;$i<=5;$i++){ echo $i<=round($rata_rating)?"★":"☆"; } ?>
        <span class="text-muted ms-2">
            <?= $rata_rating ?>/5 (<?= $total_review ?> ulasan)
        </span>
    </div>

    <?php if($diskon>0){ ?>
        <div class="text-muted text-decoration-line-through">
            Rp <?= number_format($harga_awal); ?>
        </div>
    <?php } ?>

    <h4 class="price-final mb-3">
        Rp <?= number_format($harga_final); ?>
    </h4>

    <p><?= nl2br(htmlspecialchars($p['keterangan'] ?? '-')); ?></p>

    <span class="badge-stock text-light <?= $p['stok']>0?'bg-success':'bg-danger'; ?>">
        Stok: <?= $p['stok']; ?>
    </span>

    <div class="d-flex gap-3 mt-4">
        <button onclick="addCart()" class="btn btn-gold px-4"
            <?= $p['stok']<=0?'disabled':''; ?>>
            + Keranjang
        </button>

        <button onclick="buyNow()" class="btn btn-outline-dark px-4"
            <?= $p['stok']<=0?'disabled':''; ?>>
            Beli Sekarang
        </button>
    </div>
</div>
</div>

<hr class="my-5">

<div class="row g-4">

<div class="col-md-8">
<h5 class="fw-bold text-gold mb-3">Ulasan Pembeli</h5>

<?php
$qr = mysqli_query($conn,"
    SELECT * FROM review
    WHERE produk_id='$id'
    ORDER BY id DESC
");
if(mysqli_num_rows($qr)==0){
    echo "<p class='text-muted'>Belum ada ulasan.</p>";
}
while($rv=mysqli_fetch_assoc($qr)){
?>
<div class="card review-card p-3 mb-3">
    <div class="rating mb-1">
        <?php for($i=1;$i<=5;$i++){ echo $i<=$rv['rating']?"★":"☆"; } ?>
    </div>
    <p class="mb-1"><?= nl2br(htmlspecialchars($rv['komentar'])); ?></p>
    <small class="text-muted"><?= $rv['tanggal']; ?></small>
</div>
<?php } ?>
</div>

<div class="col-md-4">
<div class="card review-card p-4 form-review">
<h6 class="fw-bold mb-3">Tulis Ulasan</h6>

<?php if(!isset($_SESSION['user_id'])){ ?>
    <div class="alert alert-info">Login untuk memberi ulasan.</div>
<?php } elseif($sudah_beli){ ?>
<form method="post" action="review.php?id=<?= $id ?>">
<select name="rating" class="form-select mb-2" required>
<option value="">Pilih Rating</option>
<option value="5">⭐⭐⭐⭐⭐</option>
<option value="4">⭐⭐⭐⭐</option>
<option value="3">⭐⭐⭐</option>
<option value="2">⭐⭐</option>
<option value="1">⭐</option>
</select>
<textarea name="komentar" class="form-control mb-3" required></textarea>
<button class="btn btn-dark w-100">Kirim Ulasan</button>
</form>
<?php } else { ?>
<div class="alert alert-warning">Ulasan hanya untuk pembeli.</div>
<?php } ?>
</div>
</div>

</div>
</div>

<script>
function addCart(){
<?php if(!isset($_SESSION['user_id'])){ ?>
Swal.fire({
    icon:'warning',
    title:'Harus Login',
    text:'Login terlebih dahulu',
    confirmButtonColor:'#d4af37'
}).then(()=>location='login_user.php');
<?php } else { ?>
location='keranjang.php?add=<?= $p['id']; ?>';
<?php } ?>
}

function buyNow(){
<?php if(!isset($_SESSION['user_id'])){ ?>
Swal.fire({
    icon:'warning',
    title:'Harus Login',
    text:'Login terlebih dahulu',
    confirmButtonColor:'#d4af37'
}).then(()=>location='login_user.php');
<?php } else { ?>
location='keranjang.php?add=<?= $p['id']; ?>&direct=1';
<?php } ?>
}
</script>

<?php include 'footer.php'; ?>
