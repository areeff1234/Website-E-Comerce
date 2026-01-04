<?php
session_start();
include 'koneksi.php';

/* ===============================
   LOGIN GUARD
================================ */
if(!isset($_SESSION['user_id'])){
    header("Location: login_user.php");
    exit;
}

$user_id = (int)$_SESSION['user_id'];

/* ===============================
   VALIDASI PRODUK
================================ */
if(!isset($_GET['id'])){
    header("Location: index.php");
    exit;
}

$produk_id = (int)$_GET['id'];

/* ===============================
   CEK PRODUK ADA
================================ */
$cekProduk = mysqli_query($conn,"
    SELECT id FROM produk
    WHERE id='$produk_id'
    LIMIT 1
");
if(mysqli_num_rows($cekProduk)==0){
    header("Location: index.php");
    exit;
}

/* ===============================
   CEK SUDAH BELI & STATUS SELESAI
================================ */
$cekBeli = mysqli_query($conn,"
    SELECT p.id
    FROM pesanan p
    JOIN pesanan_detail d ON p.id = d.pesanan_id
    WHERE p.user_id='$user_id'
      AND d.produk_id='$produk_id'
      AND p.status='Selesai'
    LIMIT 1
");

if(mysqli_num_rows($cekBeli)==0){
    // user tidak berhak review
    header("Location: detail.php?id=$produk_id");
    exit;
}

/* ===============================
   CEK SUDAH PERNAH REVIEW
================================ */
$cekReview = mysqli_query($conn,"
    SELECT id FROM review
    WHERE produk_id='$produk_id'
      AND user_id='$user_id'
    LIMIT 1
");

if(mysqli_num_rows($cekReview)>0){
    // cegah double review
    header("Location: detail.php?id=$produk_id#review");
    exit;
}

/* ===============================
   SIMPAN REVIEW
================================ */
if($_SERVER['REQUEST_METHOD']==='POST'){

    $rating   = (int)($_POST['rating'] ?? 0);
    $komentar = trim($_POST['komentar'] ?? '');

    if($rating < 1 || $rating > 5 || $komentar==''){
        header("Location: detail.php?id=$produk_id#review");
        exit;
    }

    $komentar = mysqli_real_escape_string($conn,$komentar);

    mysqli_query($conn,"
        INSERT INTO review
        (produk_id, user_id, rating, komentar, tanggal)
        VALUES
        ('$produk_id','$user_id','$rating','$komentar',CURDATE())
    ");

    header("Location: detail.php?id=$produk_id#review");
    exit;
}

// fallback keamanan
header("Location: detail.php?id=$produk_id");
exit;
