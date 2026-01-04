<?php
include 'koneksi.php';
$id=$_GET['id'];
$q=mysqli_query($conn,"SELECT * FROM produk WHERE id='$id'");
$p=mysqli_fetch_assoc($q);

if(isset($_POST['update'])){
    mysqli_query($conn,"UPDATE produk SET
    nama='$_POST[nama]',
    harga='$_POST[harga]',
    stok='$_POST[stok]',
    deskripsi='$_POST[deskripsi]',
    gambar='$_POST[gambar]'
    WHERE id='$id'");
    header("location:produk.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Produk</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<form method="post" class="container mt-4">
<h3>Edit Produk</h3>
<input name="nama" value="<?= $p['nama']; ?>" class="form-control mb-2">
<input name="harga" value="<?= $p['harga']; ?>" class="form-control mb-2">
<input name="stok" value="<?= $p['stok']; ?>" class="form-control mb-2">
<textarea name="deskripsi" class="form-control mb-2"><?= $p['deskripsi']; ?></textarea>
<input name="gambar" value="<?= $p['gambar']; ?>" class="form-control mb-2">
<button name="update" class="btn btn-warning">Update</button>
</form>

</body>
</html>
