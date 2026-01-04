<?php
include 'koneksi.php';
include 'header_user.php';
?>

<style>
.btn-back{
    display:inline-flex;
    align-items:center;
    gap:8px;
    font-size:.9rem;
    padding:8px 18px;
    border-radius:999px;
}

/* ===============================
   PROMO PREMIUM STYLE
================================ */
.promo-title{
    display:flex;
    align-items:center;
    gap:12px;
}
.promo-title span{
    font-size:1.6rem;
}
.promo-empty{
    border-radius:16px;
}

/* KUNCI CARD */
.product-card{
    display:flex;
    flex-direction:column;
    border-radius:18px;
    overflow:hidden;
    box-shadow:0 8px 24px rgba(0,0,0,.08);
    transition:.35s;
}
.product-card:hover{
    transform:translateY(-6px);
    box-shadow:0 16px 40px rgba(0,0,0,.18);
}

/* KUNCI GAMBAR */
.product-img{
    height:220px;
    background:#f4f4f4;
    overflow:hidden;
    position:relative;
}
.product-img img{
    width:100%;
    height:100%;
    object-fit:cover;
    transition:.4s;
}
.product-card:hover img{
    transform:scale(1.08);
}

/* BADGE DISKON */
.badge-diskon{
    position:absolute;
    top:12px;
    left:12px;
    background:linear-gradient(135deg,#dc3545,#b02a37);
    color:#fff;
    padding:6px 12px;
    font-size:.75rem;
    font-weight:700;
    border-radius:999px;
    z-index:2;
}

/* BODY */
.product-body{
    padding:16px;
    display:flex;
    flex-direction:column;
    height:100%;
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
    font-size:1rem;
}
</style>

<div class="container my-5">
    <div class="mb-3">
    <a href="javascript:history.back()" class="btn btn-outline-dark btn-back">
        ← Kembali
    </a>
</div>


<div class="promo-title mb-4">
    <span>🔥</span>
    <h4 class="fw-bold text-gold mb-0">Promo Spesial</h4>
</div>

<div class="row g-4">

<?php
$q = mysqli_query($conn,"
    SELECT * FROM produk
    WHERE diskon > 0
    ORDER BY diskon DESC
");

if(mysqli_num_rows($q)==0){
    echo "
    <div class='col-12'>
        <div class='alert alert-info promo-empty text-center'>
            Belum ada produk promo saat ini.
        </div>
    </div>";
}

while($p = mysqli_fetch_assoc($q)){

    $imgPath = __DIR__.'/img/'.$p['gambar'];
    $img = (!empty($p['gambar']) && file_exists($imgPath))
        ? 'img/'.$p['gambar']
        : 'https://via.placeholder.com/300x200?text=No+Image';

    $harga_final = $p['harga'] - ($p['harga'] * $p['diskon'] / 100);
?>

<div class="col-6 col-md-3">
<div class="product-card h-100">

    <div class="product-img">
        <span class="badge-diskon">-<?= $p['diskon']; ?>%</span>
        <img src="<?= $img; ?>" alt="<?= htmlspecialchars($p['nama']); ?>">
    </div>

    <div class="product-body">
        <h6 class="product-title">
            <?= htmlspecialchars($p['nama']); ?>
        </h6>

        <div class="price-old">
            Rp <?= number_format($p['harga']); ?>
        </div>

        <div class="price-final mb-3">
            Rp <?= number_format($harga_final); ?>
        </div>

        <a href="detail.php?id=<?= (int)$p['id']; ?>"
           class="btn btn-gold btn-sm mt-auto">
           Lihat Produk
        </a>
    </div>

</div>
</div>

<?php } ?>

</div>
</div>

<?php include 'footer.php'; ?>
