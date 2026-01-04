<?php
session_start();
include 'koneksi.php';

/* ===============================
   LOGIN GUARD (FINAL FIX)
================================ */
if(!isset($_SESSION['user_id'])){
    header("Location: login_user.php");
    exit;
}

$user_id = (int)$_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Profil User | Toko Tas Wanita</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- BOOTSTRAP -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- SWEETALERT -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
.text-gold{color:#d4af37;}

.profile-bg{
    background:linear-gradient(180deg,#f8fafc,#eef2f7);
    padding:50px 0;
}

.card-profile{
    border:none;
    border-radius:20px;
    box-shadow:0 18px 45px rgba(0,0,0,.08);
    transition:.3s;
}
.card-profile:hover{
    transform:translateY(-4px);
}

.avatar{
    width:130px;
    height:130px;
    object-fit:cover;
    border-radius:50%;
    border:4px solid #d4af37;
    box-shadow:0 10px 30px rgba(0,0,0,.15);
}

.section-title{
    font-weight:700;
    font-size:.95rem;
    margin-bottom:14px;
}

.btn-soft-dark{
    background:#f1f3f5;
    border:none;
}
.btn-soft-dark:hover{
    background:#e2e6ea;
}
.btn-back{
    display:inline-flex;
    align-items:center;
    gap:8px;
    padding:8px 18px;
    border-radius:999px;
    font-size:.9rem;
}

</style>
</head>

<body>

<?php include 'header_user.php'; ?>

<?php
/* ===============================
   DATA USER
================================ */
$q = mysqli_query($conn,"SELECT * FROM user WHERE id='$user_id' LIMIT 1");
$user = mysqli_fetch_assoc($q);

if(!$user){
    header("Location: logout.php");
    exit;
}

/* ===============================
   UPDATE PROFIL
================================ */
if(isset($_POST['update_profil'])){
    $nama  = mysqli_real_escape_string($conn,$_POST['nama']);
    $email = mysqli_real_escape_string($conn,$_POST['email']);

    $cek = mysqli_query($conn,"
        SELECT id FROM user 
        WHERE email='$email' AND id!='$user_id'
    ");

    if(mysqli_num_rows($cek)>0){
        echo "<script>
            Swal.fire('Gagal','Email sudah digunakan','error');
        </script>";
    }else{

        $foto_sql = "";
        if(!empty($_FILES['foto']['name'])){
            $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
            $namaFile = 'user_'.$user_id.'_'.time().'.'.$ext;
            move_uploaded_file(
                $_FILES['foto']['tmp_name'],
                'uploads/profile/'.$namaFile
            );
            $foto_sql = ", foto='$namaFile'";
        }

        mysqli_query($conn,"
            UPDATE user SET 
            nama='$nama',
            email='$email'
            $foto_sql
            WHERE id='$user_id'
        ");

        $_SESSION['user_nama']  = $nama;
        $_SESSION['user_email'] = $email;

        echo "<script>
            Swal.fire('Berhasil','Profil diperbarui','success')
            .then(()=>location='profil_user.php');
        </script>";
    }
}

/* ===============================
   GANTI PASSWORD
================================ */
if(isset($_POST['ganti_password'])){
    if(!password_verify($_POST['password_lama'],$user['password'])){
        echo "<script>
            Swal.fire('Gagal','Password lama salah','error');
        </script>";
    }else{
        $hash = password_hash($_POST['password_baru'], PASSWORD_DEFAULT);
        mysqli_query($conn,"
            UPDATE user SET password='$hash'
            WHERE id='$user_id'
        ");

        echo "<script>
            Swal.fire('Berhasil','Password diubah','success')
            .then(()=>location='profil_user.php');
        </script>";
    }
}
?>

<div class="profile-bg">
<div class="container">
<div class="mb-3">
    <a href="javascript:history.back()" class="btn btn-outline-dark btn-back">
        ← Kembali
    </a>
</div>

<!-- HEADER -->
<div class="text-center mb-5">
    <img src="uploads/profile/<?= $user['foto'] ?? 'default.png'; ?>" class="avatar mb-3">
    <h3 class="fw-bold text-gold mb-1">Profil Akun</h3>
    <p class="text-muted small">Kelola informasi akun & keamanan</p>
</div>

<div class="row g-4 justify-content-center">

<!-- INFORMASI AKUN -->
<div class="col-md-5">
<div class="card card-profile p-4">
<h6 class="section-title">👤 Informasi Akun</h6>

<form method="post" enctype="multipart/form-data">
<div class="mb-3">
<label class="form-label small">Nama Lengkap</label>
<input type="text" name="nama"
value="<?= htmlspecialchars($user['nama']); ?>"
class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label small">Email</label>
<input type="email" name="email"
value="<?= htmlspecialchars($user['email']); ?>"
class="form-control" required>
</div>

<div class="mb-4">
<label class="form-label small">Foto Profil</label>
<input type="file" name="foto"
class="form-control" accept="image/*">
</div>

<button name="update_profil" class="btn btn-gold w-100">
💾 Simpan Perubahan
</button>
</form>
</div>
</div>

<!-- KEAMANAN -->
<div class="col-md-5">
<div class="card card-profile p-4">
<h6 class="section-title">🔐 Keamanan Akun</h6>

<form method="post">
<div class="mb-3">
<label class="form-label small">Password Lama</label>
<input type="password" name="password_lama"
class="form-control" required>
</div>

<div class="mb-4">
<label class="form-label small">Password Baru</label>
<input type="password" name="password_baru"
class="form-control" required>
</div>

<button name="ganti_password" class="btn btn-soft-dark w-100">
Ganti Password
</button>
</form>
</div>
</div>

</div>

<!-- ACTION -->
<div class="text-center mt-5 d-flex justify-content-center gap-3">
<a href="logout.php" class="btn btn-danger px-4">
🚪 Logout
</a>
<button class="btn btn-outline-danger px-4"
onclick="hapusAkun()">
🗑️ Hapus Akun
</button>
</div>

</div>
</div>

<script>
function hapusAkun(){
    Swal.fire({
        title:'Hapus Akun?',
        text:'Akun akan dinonaktifkan permanen',
        icon:'warning',
        showCancelButton:true,
        confirmButtonColor:'#d33',
        confirmButtonText:'Ya, Hapus'
    }).then((res)=>{
        if(res.isConfirmed){
            window.location='hapus_akun.php';
        }
    });
}
</script>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
