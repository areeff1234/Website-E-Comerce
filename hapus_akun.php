<?php
session_start();
include 'koneksi.php';

$user_id = $_SESSION['user_id'];

mysqli_query($conn,"
    UPDATE user SET status='hapus'
    WHERE id='$user_id'
");

session_destroy();
header("Location: index.php");
exit;
