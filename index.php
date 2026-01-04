<?php
include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Toko Tas Wanita</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
.text-gold{color:#d4af37;}
/* ===============================
   HERO / BANNER (FINAL)
================================ */
.hero-slider,
.hero-parallax{
    position:relative;
    height:75vh;
    min-height:420px;
    max-height:720px;
    border-radius:18px;
    overflow:hidden;
    background:#000;
    margin:20px auto;
}

/* SLIDE */
.hero-slide{
    position:absolute;
    inset:0;
    opacity:0;
    transition:opacity 1s ease-in-out;
}

.hero-slide.active{
    opacity:1;
    z-index:2;
}

/* VIDEO */
.hero-video{
    position:absolute;
    inset:0;
    width:100%;
    height:100%;
    object-fit:cover;
    z-index:0;
}

/* OVERLAY */
.hero-overlay{
    position:absolute;
    inset:0;
    background:linear-gradient(
        rgba(0,0,0,.45),
        rgba(0,0,0,.75)
    );
    z-index:1;
}

/* CONTENT */
.hero-content{
    position:absolute;
    inset:0;
    z-index:2;
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
    text-align:center;
    padding:20px;
}

.hero-title{
    font-size:3rem;
    font-weight:800;
    line-height:1.2;
}

.hero-title span{
    color:#d4af37;
}

.hero-content p{
    font-size:1.05rem;
    opacity:.9;
}

/* BUTTON */
.btn-gold{
    background:linear-gradient(135deg,#d4af37,#b8962e);
    color:#111;
    font-weight:600;
    border-radius:30px;
    border:none;
}

/* ===============================
   PRODUCT CARD
================================ */
.product-card{
    background:#fff;
    border-radius:16px;
    overflow:hidden;
    box-shadow:0 6px 18px rgba(0,0,0,.08);
    transition:.35s;
}

.product-card:hover{
    transform:translateY(-6px);
    box-shadow:0 14px 35px rgba(0,0,0,.18);
}

.product-img{
    height:200px;
    background:#f4f4f4;
    position:relative;
}

.product-img img{
    width:100%;
    height:100%;
    object-fit:cover;
    transition:.5s;
}

.product-card:hover img{
    transform:scale(1.08);
}

.badge-diskon{
    position:absolute;
    top:10px;
    left:10px;
    background:#dc3545;
    color:#fff;
    padding:6px 10px;
    font-size:.75rem;
    border-radius:12px;
    font-weight:700;
}

.product-body{
    padding:14px;
}

.product-title{
    font-size:.9rem;
    font-weight:600;
    min-height:38px;
}

.price-old{
    text-decoration:line-through;
    color:#999;
    font-size:.85rem;
}

.price-final{
    color:#d4af37;
    font-weight:800;
}

.rating{
    color:#f4c150;
    font-size:.8rem;
}

/* ===============================
   RESPONSIVE
================================ */
@media(max-width:768px){
    .hero-slider,
    .hero-parallax{
        height:55vh;
        min-height:360px;
        border-radius:14px;
    }

    .hero-title{
        font-size:2rem;
    }

    .hero-content p{
        font-size:.95rem;
    }

    .product-img{
        height:160px;
    }
}
.review-card{
    background:#fff;
    border-radius:16px;
    padding:16px;
    box-shadow:0 10px 30px rgba(0,0,0,.08);
}
.review-card .rating{
    font-size:.9rem;
}
.review-product{
    font-size:.8rem;
    color:#666;
}

</style>
</head>

<body>

<?php include 'header_user.php'; ?>

   <!-- HERO MULTI BANNER -->
<section class="hero-slider">

    <!-- SLIDE 1 -->
    <div class="hero-slide active">
        <video class="hero-video" autoplay muted loop playsinline>
            <source src="banner1.mp4" type="video/mp4">
        </video>
        <div class="hero-overlay"></div>
        <div class="hero-content text-center text-light">
            <h1 class="hero-title">
                Koleksi Tas Wanita <span>Premium</span>
            </h1>
            <p>Elegan • Modern • Eksklusif</p>
            <a href="#produk" class="btn btn-gold mt-3">
                👜 Jelajahi Koleksi
            </a>
        </div>
    </div>

    <!-- SLIDE 2 -->
    <div class="hero-slide">
        <video class="hero-video" autoplay muted loop playsinline>
            <source src="banner2.mp4" type="video/mp4">
        </video>
        <div class="hero-overlay"></div>
        <div class="hero-content text-center text-light">
            <h1 class="hero-title">
                Tas Wanita <span>Elegan</span>
            </h1>
            <p>Untuk Kerja • Kuliah • Hangout</p>
            <a href="#produk" class="btn btn-gold mt-3">
                ✨ Lihat Produk
            </a>
        </div>
    </div>

    <!-- SLIDE 3 (opsional) -->
    <div class="hero-slide">
        <video class="hero-video" autoplay muted loop playsinline>
            <source src="banner3.mp4" type="video/mp4">
        </video>
        <div class="hero-overlay"></div>
        <div class="hero-content text-center text-light">
            <h1 class="hero-title">
                Gaya Mewah <span>Setiap Hari</span>
            </h1>
            <p>Kualitas Premium • Harga Terbaik</p>
            <a href="#produk" class="btn btn-gold mt-3">
                💎 Belanja Sekarang
            </a>
        </div>
    </div>

</section>

<!-- PRODUK -->
<div class="container mt-5" id="produk">
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold text-gold mb-0">Produk Terbaru</h4>
    <span class="text-muted small">Marketplace Style</span>
</div>

<div class="row g-4">
<?php
$qReview = mysqli_query($conn,"
    SELECT r.*, p.nama AS nama_produk, p.id AS pid
    FROM review r
    JOIN produk p ON r.produk_id = p.id
    ORDER BY r.id DESC
    LIMIT 6
");

$q = mysqli_query($conn,"
    SELECT p.*,
    IFNULL(AVG(r.rating),0) AS rating_avg,
    COUNT(r.id) AS total_review
    FROM produk p
    LEFT JOIN review r ON p.id = r.produk_id
    GROUP BY p.id
    ORDER BY p.id DESC
");

if(mysqli_num_rows($q)==0){
    echo "<div class='col-12'>
        <div class='alert alert-warning text-center'>
            Produk belum tersedia
        </div>
    </div>";
}

while($p=mysqli_fetch_assoc($q)){

    $imgPath = __DIR__."/img/".$p['gambar'];
    $img = (!empty($p['gambar']) && file_exists($imgPath))
        ? "img/".$p['gambar']
        : "https://via.placeholder.com/300x200?text=No+Image";

    $harga_awal = $p['harga'];
    $diskon = (int)$p['diskon'];
    $harga_final = $diskon > 0
        ? $harga_awal - ($harga_awal * $diskon / 100)
        : $harga_awal;
?>
<div class="col-6 col-md-3">
<div class="product-card h-100">

<div class="product-img">
    <?php if($diskon > 0){ ?>
        <span class="badge-diskon">-<?= $diskon ?>%</span>
    <?php } ?>
    <img src="<?= $img; ?>" alt="<?= htmlspecialchars($p['nama']); ?>">
</div>

<div class="product-body d-flex flex-column">
    <h6 class="product-title"><?= htmlspecialchars($p['nama']); ?></h6>

    <!-- RATING -->
    <div class="rating mb-1">
        <?php
        for($i=1;$i<=5;$i++){
            echo $i <= round($p['rating_avg']) ? "★" : "☆";
        }
        ?>
        <small class="text-muted">(<?= $p['total_review']; ?>)</small>
    </div>

    <!-- HARGA -->
    <?php if($diskon > 0){ ?>
        <div class="price-old">Rp <?= number_format($harga_awal); ?></div>
        <div class="price-final">Rp <?= number_format($harga_final); ?></div>
    <?php }else{ ?>
        <div class="price-final">Rp <?= number_format($harga_awal); ?></div>
    <?php } ?>

    <div class="mt-auto d-grid gap-2">
        <a href="detail.php?id=<?= (int)$p['id']; ?>" class="btn btn-outline-dark btn-sm">
            Detail
        </a>
        <a href="keranjang.php?add=<?= (int)$p['id']; ?>" class="btn btn-gold btn-sm">
            + Keranjang
        </a>
    </div>
</div>

</div>
</div>
<?php } ?>
</div>
</div>
<!-- REVIEW PELANGGAN -->
<div class="container mt-5">
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold text-gold mb-0">💬 Apa Kata Pembeli Kami</h4>
    <span class="text-muted small">Review asli dari pelanggan</span>
</div>

<div class="row g-4">

<?php
if(mysqli_num_rows($qReview)==0){
    echo "<div class='col-12 text-muted'>Belum ada review.</div>";
}

while($rv=mysqli_fetch_assoc($qReview)){
?>
<div class="col-md-4">
<div class="review-card h-100">

<div class="rating mb-2">
<?php
for($i=1;$i<=5;$i++){
    echo $i <= $rv['rating'] ? "★" : "☆";
}
?>
</div>

<p class="mb-2">
“<?= htmlspecialchars($rv['komentar']); ?>”
</p>

<div class="review-product">
👜 <a href="detail.php?id=<?= $rv['pid']; ?>">
<?= htmlspecialchars($rv['nama_produk']); ?>
</a>
</div>

</div>
</div>
<?php } ?>

</div>
</div>

<script>
const slides = document.querySelectorAll('.hero-slide');
let index = 0;

setInterval(() => {
    slides[index].classList.remove('active');
    index = (index + 1) % slides.length;
    slides[index].classList.add('active');
}, 6000); // ganti slide tiap 6 detik
</script>

<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
