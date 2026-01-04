<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'koneksi.php';
session_start();
if(!isset($_SESSION['admin'])) header("location:login.php");

if(isset($_POST['simpan'])){

    $nama   = mysqli_real_escape_string($conn, $_POST['nama']);
    $harga  = (int)$_POST['harga'];
    $diskon = (int)$_POST['diskon']; // 🔥 DISKON
    $stok   = (int)$_POST['stok'];
    $desk   = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    if(isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0){

        $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        $nama_baru = 'produk_' . time() . '_' . rand(100,999) . '.' . $ext;

        $folder = __DIR__ . "/img/";
        if(!is_dir($folder)) mkdir($folder, 0777, true);

        if(in_array($ext, ['jpg','jpeg','png','webp'])){
            move_uploaded_file($_FILES['gambar']['tmp_name'], $folder.$nama_baru);

            $sql = "INSERT INTO produk 
                    (nama, harga, diskon, stok, deskripsi, gambar)
                    VALUES
                    ('$nama','$harga','$diskon','$stok','$desk','$nama_baru')";

            if(mysqli_query($conn, $sql)){
                echo "<script>
                    alert('Produk berhasil ditambahkan');
                    window.location='produk.php';
                </script>";
            }else{
                die('Query error: '.mysqli_error($conn));
            }
        }else{
            echo "<script>alert('Format gambar harus JPG / PNG / WEBP');</script>";
        }
    }else{
        echo "<script>alert('Upload gambar gagal');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tambah Produk</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background: linear-gradient(135deg,#eef2ff,#f8fafc);
}
.card-premium{
    max-width:700px;
    margin:auto;
    border-radius:20px;
    box-shadow:0 25px 60px rgba(0,0,0,.12);
}
.preview-img{
    width:100%;
    height:260px;
    object-fit:cover;
    border-radius:14px;
    border:2px dashed #ddd;
}
.label-soft{
    font-weight:600;
    font-size:.9rem;
}
</style>
</head>

<body>

<div class="container py-5">
<div class="card card-premium p-4">

<h4 class="fw-bold mb-1">👜 Tambah Produk</h4>
<p class="text-muted mb-4">Lengkapi data produk dengan detail & diskon</p>

<form method="post" enctype="multipart/form-data">

<div class="mb-3">
    <label class="label-soft">Nama Produk</label>
    <input type="text" name="nama" class="form-control" required>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="label-soft">Harga (Rp)</label>
        <input type="number" name="harga" id="harga" class="form-control" required oninput="hitungHarga()">
    </div>

    <div class="col-md-4 mb-3">
        <label class="label-soft">Diskon (%)</label>
        <input type="number" name="diskon" id="diskon" class="form-control" value="0" oninput="hitungHarga()">
    </div>

    <div class="col-md-4 mb-3">
        <label class="label-soft">Stok</label>
        <input type="number" name="stok" class="form-control" required>
    </div>
</div>

<div class="mb-3">
    <label class="label-soft">Harga Setelah Diskon</label>
    <input type="text" id="harga_final" class="form-control" readonly>
</div>

<div class="mb-3">
    <label class="label-soft">Deskripsi</label>
    <textarea name="deskripsi" class="form-control" rows="4"></textarea>
</div>

<div class="mb-3">
    <label class="label-soft">Gambar Produk</label>
    <input type="file" name="gambar" class="form-control" accept="image/*"
           onchange="previewImage(this)" required>
</div>

<img id="preview" class="preview-img mb-4"
     src="https://via.placeholder.com/600x300?text=Preview+Gambar">

<div class="d-flex gap-2">
    <button name="simpan" class="btn btn-primary px-4">
        💾 Simpan Produk
    </button>
    <a href="produk.php" class="btn btn-outline-secondary">
        ↩ Batal
    </a>
</div>

</form>

</div>
</div>

<script>
function previewImage(input){
    const preview = document.getElementById('preview');
    const reader = new FileReader();
    reader.onload = e => preview.src = e.target.result;
    reader.readAsDataURL(input.files[0]);
}

function hitungHarga(){
    const harga  = document.getElementById('harga').value;
    const diskon = document.getElementById('diskon').value;
    const final  = harga - (harga * diskon / 100);
    document.getElementById('harga_final').value =
        isNaN(final) ? '' : 'Rp ' + final.toLocaleString();
}
</script>

</body>
</html>
