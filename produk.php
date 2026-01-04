<?php
session_start();
include 'koneksi.php';

/* ===============================
   PROTEKSI ADMIN
================================ */
if(!isset($_SESSION['admin'])){
    header("location:login.php");
    exit;
}

include 'header_admin.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Admin • Produk</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
:root{
    --navy:#0b1d3a;
    --navy-soft:#12284c;
    --gold:#d4af37;
}

body{
    background:linear-gradient(135deg,#eef2ff,#f8fafc);
    min-height:100vh;
}

.text-gold{color:var(--gold)}

.admin-header{
    background:white;
    border-radius:20px;
    padding:22px 26px;
    box-shadow:0 15px 40px rgba(0,0,0,.08);
}

.glass-card{
    background:rgba(255,255,255,.88);
    backdrop-filter:blur(12px);
    border-radius:22px;
    box-shadow:0 25px 60px rgba(0,0,0,.15);
}

.table{
    border-collapse:separate;
    border-spacing:0 12px;
}

.table tbody tr{
    background:#fff;
    border-radius:16px;
    box-shadow:0 10px 25px rgba(0,0,0,.06);
    transition:.3s;
}

.table tbody tr:hover{
    transform:translateY(-3px);
    box-shadow:0 20px 40px rgba(0,0,0,.15);
}

.table td{
    vertical-align:middle;
    border:none;
}

.thumb{
    width:60px;
    height:60px;
    object-fit:cover;
    border-radius:14px;
    border:1px solid #eee;
}

.badge-stock{
    padding:6px 14px;
    border-radius:999px;
    font-size:.8rem;
}

.btn-icon{
    padding:7px 11px;
    border-radius:12px;
}
</style>
</head>

<body>

<div class="container my-5">

<!-- HEADER -->
<div class="admin-header mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h5 class="fw-bold mb-1 text-gold">👜 Manajemen Produk</h5>
        <small class="text-muted">Kelola produk toko secara profesional</small>
    </div>

    <a href="produk_tambah.php" class="btn btn-warning rounded-pill px-4">
        <i class="bi bi-plus-circle"></i> Tambah Produk
    </a>
</div>

<!-- TABLE -->
<div class="glass-card p-4">

<table class="table align-middle mb-0">
<thead class="text-muted">
<tr>
    <th>#</th>
    <th>Produk</th>
    <th>Harga</th>
    <th>Diskon</th>
    <th>Stok</th>
    <th class="text-center">Aksi</th>
</tr>
</thead>

<tbody>
<?php
$no=1;
$q = mysqli_query($conn,"SELECT * FROM produk ORDER BY id DESC");
while($p=mysqli_fetch_assoc($q)){

    $imgPath = __DIR__."/img/".$p['gambar'];
    $gambar = (!empty($p['gambar']) && file_exists($imgPath))
        ? "img/".$p['gambar']
        : "https://via.placeholder.com/80x80?text=No+Image";

    if($p['stok'] <= 0){
        $stok = "<span class='badge bg-danger badge-stock'>Habis</span>";
    }elseif($p['stok'] < 5){
        $stok = "<span class='badge bg-warning text-dark badge-stock'>{$p['stok']} pcs</span>";
    }else{
        $stok = "<span class='badge bg-success badge-stock'>{$p['stok']} pcs</span>";
    }
?>
<tr>
<td class="fw-semibold"><?= $no++; ?></td>

<td>
<div class="d-flex align-items-center gap-3">
    <img src="<?= $gambar ?>" class="thumb">
    <div>
        <div class="fw-semibold"><?= htmlspecialchars($p['nama']); ?></div>
        <small class="text-muted">ID #<?= $p['id']; ?></small>
    </div>
</div>
</td>

<td class="fw-semibold">
    Rp <?= number_format($p['harga']); ?>
</td>

<td>
<?php if($p['diskon']>0){ ?>
    <span class="badge bg-warning text-dark"><?= $p['diskon']; ?>%</span>
<?php }else{ ?>
    <span class="text-muted small">—</span>
<?php } ?>
</td>

<td><?= $stok; ?></td>

<td class="text-center">
    <a href="produk_edit.php?id=<?= $p['id']; ?>"
       class="btn btn-warning btn-sm btn-icon">
       <i class="bi bi-pencil"></i>
    </a>

    <button onclick="hapusProduk(<?= $p['id']; ?>)"
       class="btn btn-danger btn-sm btn-icon">
       <i class="bi bi-trash"></i>
    </button>
</td>
</tr>
<?php } ?>
</tbody>
</table>

</div>
</div>

<script>
function hapusProduk(id){
    Swal.fire({
        title:'Hapus produk?',
        text:'Data tidak bisa dikembalikan',
        icon:'warning',
        showCancelButton:true,
        confirmButtonText:'Hapus',
        cancelButtonText:'Batal',
        confirmButtonColor:'#d33'
    }).then(res=>{
        if(res.isConfirmed){
            location='produk_hapus.php?id='+id;
        }
    })
}
</script>

</body>
</html>
