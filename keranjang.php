<?php
session_start();
include 'koneksi.php';

/* ===============================
   LOGIN GUARD (SWEETALERT AMAN)
================================ */
if(!isset($_SESSION['user_id'])){
    echo "<script>window.location='login_user.php';</script>";
    exit;
}

/* ===============================
   INIT KERANJANG
================================ */
if(!isset($_SESSION['keranjang']) || !is_array($_SESSION['keranjang'])){
    $_SESSION['keranjang'] = [];
}

/* ===============================
   TAMBAH PRODUK
================================ */
if(isset($_GET['add'])){
    $id = (int)$_GET['add'];

    $q = mysqli_query($conn,"SELECT stok FROM produk WHERE id='$id' LIMIT 1");
    if($p = mysqli_fetch_assoc($q)){
        if($p['stok'] > 0){
            $_SESSION['keranjang'][$id] =
                ($_SESSION['keranjang'][$id] ?? 0) + 1;

            // batas maksimal stok
            $_SESSION['keranjang'][$id] =
                min($_SESSION['keranjang'][$id], $p['stok']);
        }
    }
    echo "<script>window.location='keranjang.php';</script>";
    exit;
}

/* ===============================
   HAPUS ITEM
================================ */
if(isset($_GET['hapus'])){
    unset($_SESSION['keranjang'][(int)$_GET['hapus']]);
    echo "<script>window.location='keranjang.php';</script>";
    exit;
}

/* ===============================
   UPDATE QTY
================================ */
if(isset($_POST['update'])){
    foreach($_POST['qty'] as $id=>$qty){
        $id  = (int)$id;
        $qty = (int)$qty;

        if($qty <= 0){
            unset($_SESSION['keranjang'][$id]);
        }else{
            $q = mysqli_query($conn,"SELECT stok FROM produk WHERE id='$id' LIMIT 1");
            if($p = mysqli_fetch_assoc($q)){
                $_SESSION['keranjang'][$id] = min($qty, $p['stok']);
            }
        }
    }
    echo "<script>window.location='keranjang.php';</script>";
    exit;
}
?>

<?php include 'header_user.php'; ?>

<div class="container mt-4">
<h4 class="fw-bold text-gold mb-3">🛒 Keranjang Belanja</h4>

<?php if(empty($_SESSION['keranjang'])){ ?>

<div class="alert alert-warning">
    Keranjang masih kosong.
</div>
<a href="index.php" class="btn btn-dark">
    Belanja Sekarang
</a>

<?php } else { ?>

<form method="post">
<table class="table align-middle">
<thead class="table-light">
<tr>
    <th>Produk</th>
    <th width="120">Harga</th>
    <th width="120">Qty</th>
    <th width="150">Subtotal</th>
    <th width="80">Aksi</th>
</tr>
</thead>

<tbody>
<?php
$total = 0;

foreach($_SESSION['keranjang'] as $id=>$qty){
    $id = (int)$id;
    $q  = mysqli_query($conn,"SELECT * FROM produk WHERE id='$id' LIMIT 1");

    if(!$p = mysqli_fetch_assoc($q)){
        unset($_SESSION['keranjang'][$id]);
        continue;
    }

    /* HITUNG HARGA FINAL (SINKRON DISKON) */
    $harga = $p['diskon'] > 0
        ? $p['harga'] - ($p['harga'] * $p['diskon'] / 100)
        : $p['harga'];

    $subtotal = $harga * $qty;
    $total   += $subtotal;
?>
<tr>
<td>
    <div class="d-flex align-items-center gap-3">
        <img src="img/<?= htmlspecialchars($p['gambar']); ?>"
             width="60" height="60"
             style="object-fit:cover;border-radius:8px;">
        <div>
            <b><?= htmlspecialchars($p['nama']); ?></b>
            <div class="small text-muted">
                Stok: <?= (int)$p['stok']; ?>
            </div>
        </div>
    </div>
</td>

<td>
    <?php if($p['diskon']>0){ ?>
        <div class="small text-muted text-decoration-line-through">
            Rp <?= number_format($p['harga']); ?>
        </div>
    <?php } ?>
    <b>Rp <?= number_format($harga); ?></b>
</td>

<td>
    <input type="number"
           name="qty[<?= $id; ?>]"
           value="<?= $qty; ?>"
           min="1"
           max="<?= (int)$p['stok']; ?>"
           class="form-control form-control-sm">
</td>

<td class="fw-semibold">
    Rp <?= number_format($subtotal); ?>
</td>

<td>
    <button type="button"
        onclick="hapusItem(<?= $id; ?>)"
        class="btn btn-sm btn-danger">
        ✕
    </button>
</td>
</tr>
<?php } ?>
</tbody>

<tfoot>
<tr>
    <th colspan="3" class="text-end">Total</th>
    <th class="text-gold fs-5">
        Rp <?= number_format($total); ?>
    </th>
    <th></th>
</tr>
</tfoot>
</table>

<div class="d-flex justify-content-between mt-3">
    <a href="index.php" class="btn btn-outline-dark">
        ← Lanjut Belanja
    </a>

    <div class="d-flex gap-2">
        <button name="update" class="btn btn-secondary">
            Update Qty
        </button>

        <!-- SINKRON KE PEMBAYARAN -->
        <a href="checkout.php" class="btn btn-gold">
    Checkout →
</a>

    </div>
</div>
</form>

<?php } ?>
</div>

<script>
function hapusItem(id){
    Swal.fire({
        icon:'warning',
        title:'Hapus Produk?',
        text:'Produk akan dihapus dari keranjang',
        showCancelButton:true,
        confirmButtonText:'Ya, hapus',
        cancelButtonText:'Batal',
        confirmButtonColor:'#d33'
    }).then((res)=>{
        if(res.isConfirmed){
            window.location='keranjang.php?hapus='+id;
        }
    });
}
</script>

<?php include 'footer.php'; ?>
