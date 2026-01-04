<?php
session_start();
include 'koneksi.php';

/* ===============================
   JIKA SUDAH LOGIN
================================ */
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$alert = null;

/* ===============================
   REGISTER USER
================================ */
if (isset($_POST['register'])) {
    $nama  = trim(mysqli_real_escape_string($conn, $_POST['nama']));
    $email = trim(mysqli_real_escape_string($conn, $_POST['email']));
    $pass  = $_POST['password'];

    if ($nama == '' || $email == '' || $pass == '') {
        $alert = "field";
    } else {
        $cek = mysqli_query($conn, "SELECT id FROM user WHERE email='$email'");
        if (mysqli_num_rows($cek) > 0) {
            $alert = "email";
        } else {
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            mysqli_query($conn, "
                INSERT INTO user (nama,email,password)
                VALUES ('$nama','$email','$hash')
            ");
            $alert = "register_ok";
        }
    }
}

/* ===============================
   LOGIN USER
================================ */
if (isset($_POST['login'])) {
    $email = trim(mysqli_real_escape_string($conn, $_POST['email']));
    $pass  = $_POST['password'];

    $q = mysqli_query($conn, "SELECT * FROM user WHERE email='$email' LIMIT 1");
    if (mysqli_num_rows($q) === 1) {
        $user = mysqli_fetch_assoc($q);

        if (password_verify($pass, $user['password'])) {

            /* ===============================
               SESSION WAJIB (KUNCI SINKRON)
            ================================ */
            $_SESSION['user']       = true;
            $_SESSION['user_id']    = $user['id'];
            $_SESSION['user_nama']  = $user['nama'];
            $_SESSION['user_email'] = $user['email'];

            $alert = "login_ok";
        } else {
            $alert = "login_fail";
        }
    } else {
        $alert = "login_fail";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Portal User | Toko Tas Wanita</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
body{
    min-height:100vh;
    background:linear-gradient(135deg,#0b1d3a,#132f5e);
    display:flex;
    align-items:center;
    justify-content:center;
    font-family:'Segoe UI',sans-serif;
}
.portal-box{
    width:100%;
    max-width:420px;
    background:#fff;
    border-radius:16px;
    padding:30px;
    box-shadow:0 18px 45px rgba(0,0,0,.25);
}
.text-gold{color:#d4af37;}
.btn-gold{
    background:#d4af37;
    color:#0b1d3a;
    font-weight:600;
    border:none;
}
.btn-gold:hover{background:#bfa233;}
.nav-pills .nav-link.active{
    background:#d4af37;
    color:#0b1d3a;
    font-weight:600;
}
</style>
</head>

<body>

<div class="portal-box">

<h4 class="text-center fw-bold mb-1">👤 Portal User</h4>
<p class="text-center text-muted mb-4">Toko Tas Wanita</p>

<ul class="nav nav-pills nav-justified mb-3">
    <li class="nav-item">
        <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#login">
            Login
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#register">
            Register
        </button>
    </li>
</ul>

<div class="tab-content">

<!-- LOGIN -->
<div class="tab-pane fade show active" id="login">
<form method="post">
    <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
    <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
    <button name="login" class="btn btn-gold w-100">Login</button>
</form>
</div>

<!-- REGISTER -->
<div class="tab-pane fade" id="register">
<form method="post">
    <input type="text" name="nama" class="form-control mb-3" placeholder="Nama Lengkap" required>
    <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
    <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
    <button name="register" class="btn btn-outline-dark w-100">
        Register
    </button>
</form>
</div>

</div>
</div>

<?php if(isset($alert)): ?>
<script>
Swal.fire({
    icon: '<?= in_array($alert,["login_ok","register_ok"]) ? "success":"error" ?>',
    title: '<?= 
        $alert=="login_ok" ? "Login Berhasil" :
        ($alert=="register_ok" ? "Registrasi Berhasil" : "Gagal") ?>',
    text: '<?= 
        $alert=="login_ok" ? "Selamat datang ".$_SESSION['user_nama'] :
        ($alert=="register_ok" ? "Silakan login menggunakan akun Anda" :
        "Email atau password salah / tidak valid") ?>',
    confirmButtonColor:'#d4af37'
}).then(()=>{
    <?php if($alert=="login_ok"): ?>
        window.location='index.php';
    <?php endif; ?>
});
</script>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
