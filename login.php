<?php
session_start();
include 'koneksi.php';

/* ===============================
   JIKA ADMIN SUDAH LOGIN
================================ */
if(isset($_SESSION['admin'])){
    header("location:dashboard.php");
    exit;
}

/* ===============================
   PROSES LOGIN
================================ */
if(isset($_POST['login'])){
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5($_POST['password']);

    $q = mysqli_query($conn,"
        SELECT * FROM admin
        WHERE username='$username'
        AND password='$password'
        LIMIT 1
    ");

    if(mysqli_num_rows($q) === 1){
        $admin = mysqli_fetch_assoc($q);

        $_SESSION['admin']      = true;
        $_SESSION['admin_id']   = $admin['id'] ?? null;
        $_SESSION['admin_nama'] = $admin['username'];

        echo "
        <script>
        Swal.fire({
            icon: 'success',
            title: 'Login Berhasil',
            text: 'Selamat datang Admin',
            confirmButtonColor: '#d4af37',
            timer: 1500,
            showConfirmButton: false
        }).then(() => {
            window.location = 'dashboard.php';
        });
        </script>
        ";
    }else{
        echo "
        <script>
        Swal.fire({
            icon: 'error',
            title: 'Login Gagal',
            text: 'Username atau password salah',
            confirmButtonColor: '#d4af37'
        });
        </script>
        ";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login Admin | Toko Tas Wanita</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
/* ===============================
   ADMIN LOGIN – NAVY × GOLD
================================ */
body{
    min-height:100vh;
    background:
        radial-gradient(circle at top,#1c3d73,#0b1d3a 60%);
    display:flex;
    align-items:center;
    justify-content:center;
    font-family:'Segoe UI',sans-serif;
}

/* GLASS EFFECT */
.login-box{
    width:100%;
    max-width:380px;
    background:rgba(255,255,255,.96);
    backdrop-filter: blur(6px);
    border-radius:16px;
    padding:32px;
    box-shadow:0 25px 60px rgba(0,0,0,.35);
    animation: slideFade .9s ease;
}

.login-box h4{
    color:#0b1d3a;
}

/* BRAND */
.text-gold{color:#d4af37;}
.brand-icon{
    font-size:42px;
    color:#d4af37;
}

/* BUTTON */
.btn-gold{
    background:#d4af37;
    color:#0b1d3a;
    font-weight:600;
    border:none;
    transition:.2s ease;
}
.btn-gold:hover{
    background:#bfa233;
    transform:translateY(-1px);
}

/* INPUT */
.form-control{
    border-radius:10px;
    padding:10px 12px;
}
.form-control:focus{
    border-color:#d4af37;
    box-shadow:0 0 0 .15rem rgba(212,175,55,.35);
}

/* ANIMATION */
@keyframes slideFade{
    from{
        opacity:0;
        transform:translateY(30px) scale(.95);
    }
    to{
        opacity:1;
        transform:translateY(0) scale(1);
    }
}
</style>
</head>

<body>

<div class="login-box">

    <div class="text-center mb-3">
        <div class="brand-icon">🛡️</div>
        <h4 class="fw-bold mb-1">Admin Login</h4>
        <small class="text-muted">Toko Tas Wanita</small>
    </div>

    <form method="post" autocomplete="off">

        <div class="mb-3">
            <input type="text"
                   name="username"
                   class="form-control"
                   placeholder="Username"
                   autofocus
                   required>
        </div>

        <div class="mb-3">
            <input type="password"
                   name="password"
                   class="form-control"
                   placeholder="Password"
                   required>
        </div>

        <button name="login" class="btn btn-gold w-100 py-2">
            Masuk Admin
        </button>

    </form>

    <div class="text-center mt-4">
        <small class="text-muted">
            © <?= date('Y'); ?>
            <span class="text-gold fw-semibold">
                Toko Tas Wanita
            </span>
        </small>
    </div>

</div>

</body>
</html>
