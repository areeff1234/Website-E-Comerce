<?php
session_start();
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
include 'koneksi.php';
include 'header_user.php';

/* ===============================
   LOGIN GUARD
================================ */
if(!isset($_SESSION['user_id'])){
    echo "<script>location='login_user.php';</script>";
    exit;
}

/* ===============================
   VALIDASI KERANJANG
================================ */
if(empty($_SESSION['keranjang'])){
    echo "<div class='container mt-5'>
            <div class='alert alert-warning'>Keranjang kosong.</div>
            <a href='index.php' class='btn btn-dark mt-3'>Belanja Sekarang</a>
          </div>";
    include 'footer.php';
    exit;
}

/* ===============================
   ONGKIR
================================ */
$daftar_ongkir = [
    "Jakarta"=>15000,
    "Bandung"=>20000,
    "Surabaya"=>25000,
    "Lainnya"=>30000
];

/* ===============================
   HITUNG PRODUK
================================ */
$subtotal = 0;
$items = [];

foreach($_SESSION['keranjang'] as $id=>$qty){
    $id  = (int)$id;
    $qty = (int)$qty;

    $q = mysqli_query($conn,"SELECT * FROM produk WHERE id='$id' LIMIT 1");
    if(!$p = mysqli_fetch_assoc($q)) continue;

    if($qty > $p['stok']){
        $qty = $p['stok'];
        $_SESSION['keranjang'][$id] = $qty;
    }

    $harga = $p['diskon'] > 0
        ? $p['harga'] - ($p['harga'] * $p['diskon']/100)
        : $p['harga'];

    $subtotal += $harga * $qty;

    $items[] = [
        'id'=>$id,
        'qty'=>$qty,
        'harga'=>$harga
    ];
}

/* ===============================
   PROSES CHECKOUT
================================ */
if(isset($_POST['checkout'])){

    $alamat = trim($_POST['alamat'] ?? '');
    $kota   = $_POST['kota'] ?? '';

    if(!$alamat || !$kota){
        echo "<script>
            Swal.fire('Lengkapi Data','Alamat dan kota wajib diisi','warning');
        </script>";
    } elseif(!isset($daftar_ongkir[$kota])){
        echo "<script>
            Swal.fire('Error','Kota tidak valid','error');
        </script>";
    } else {

        $ongkir = $daftar_ongkir[$kota];
        $total  = $subtotal + $ongkir;
        $alamat = mysqli_real_escape_string($conn,$alamat);

        mysqli_query($conn,"
            INSERT INTO pesanan
            (user_id, tanggal, total, status, alamat, ongkir)
            VALUES
            ('{$_SESSION['user_id']}', CURDATE(), '$total', 'Menunggu', '$alamat', '$ongkir')
        ");

        $pesanan_id = mysqli_insert_id($conn);

        foreach($items as $it){
            mysqli_query($conn,"
                INSERT INTO pesanan_detail
                (pesanan_id, produk_id, qty, harga)
                VALUES
                ('$pesanan_id','{$it['id']}','{$it['qty']}','{$it['harga']}')
            ");

            mysqli_query($conn,"
                UPDATE produk
                SET stok = stok - {$it['qty']}
                WHERE id = {$it['id']}
            ");
        }

        unset($_SESSION['keranjang']);

        echo "<script>
            Swal.fire({
                icon:'success',
                title:'Pesanan Berhasil!',
                text:'Pesanan kamu berhasil dibuat',
                confirmButtonColor:'#d4af37'
            }).then(()=>{
                window.location = 'pesanan_saya.php';
            });
        </script>";
    }
}

?>

<style>
/* CHECKOUT PREMIUM */
.checkout-card{
    border-radius:22px;
    box-shadow:0 18px 45px rgba(0,0,0,.08);
}
.btn-back{
    display:inline-flex;
    align-items:center;
    gap:8px;
    padding:8px 18px;
    border-radius:999px;
    font-size:.9rem;
}
</style>

<div class="container my-5">

<!-- TOMBOL KEMBALI -->
<div class="mb-3">
    <a href="javascript:history.back()" class="btn btn-outline-dark btn-back">
        ← Kembali
    </a>
</div>

<h4 class="fw-bold text-gold mb-4">🧾 Checkout</h4>

<div class="row justify-content-center">
<div class="col-md-7">

<div class="card checkout-card p-4">

<form method="post">

<h6 class="fw-bold mb-2">📍 Alamat Pengiriman</h6>
<textarea name="alamat" class="form-control mb-3"
placeholder="Alamat lengkap pengiriman" required></textarea>

<h6 class="fw-bold mb-2">🚚 Pilih Kota</h6>
<select name="kota" class="form-select mb-4" required>
<option value="">Pilih Kota</option>
<?php foreach($daftar_ongkir as $k=>$v){ ?>
<option value="<?= $k ?>">
    <?= $k ?> (Rp <?= number_format($v) ?>)
</option>
<?php } ?>
</select>

<div class="d-flex justify-content-between mb-2">
    <span>Subtotal</span>
    <b>Rp <?= number_format($subtotal); ?></b>
</div>

<div class="d-flex justify-content-between mb-3">
    <span>Ongkir</span>
    <span class="text-muted">Sesuai kota</span>
</div>

<button name="checkout" class="btn btn-gold w-100 py-2">
Bayar Sekarang
</button>

</form>

</div>
</div>
</div>
</div>

<?php include 'footer.php'; ?>
