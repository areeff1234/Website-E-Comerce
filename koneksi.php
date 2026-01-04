<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "ecommerce_tas";

$conn = mysqli_connect($host, $user, $pass, $db);

if(!$conn){
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Charset wajib
mysqli_set_charset($conn, "utf8mb4");
?>
