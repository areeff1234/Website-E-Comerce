<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if(!isset($_SESSION['admin'])){
    header("location:login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Admin Panel</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
.bg-navy{background:#0b1d3a;}
.text-gold{color:#d4af37;}
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-navy navbar-dark">
<div class="container">

<a class="navbar-brand text-gold fw-bold" href="dashboard.php">
🛡️ Admin Panel
</a>

<div class="ms-auto d-flex gap-2">
    <a href="dashboard.php" class="btn btn-outline-light btn-sm">Dashboard</a>
    <a href="produk.php" class="btn btn-outline-light btn-sm">Produk</a>
    <a href="pesanan.php" class="btn btn-outline-light btn-sm">Pesanan</a>
    <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
</div>

</div>
</nav>
