<?php
session_start();

/* ===============================
   SET FLASH SEBELUM DESTROY
================================ */
$_SESSION['flash'] = [
    'type'  => 'success',
    'title' => 'Logout Berhasil',
    'msg'   => 'Anda telah keluar dari akun'
];

/* ===============================
   HAPUS SESSION USER SAJA
================================ */
unset($_SESSION['user']);
unset($_SESSION['user_id']);
unset($_SESSION['user_nama']);
unset($_SESSION['user_email']);

/* ===============================
   OPTIONAL: KOSONGKAN KERANJANG
================================ */
unset($_SESSION['keranjang']);

/* ===============================
   REDIRECT
================================ */
header("Location: index.php");
exit;
