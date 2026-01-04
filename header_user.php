<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* ===============================
   HITUNG KERANJANG
================================ */
$jumlahKeranjang = 0;
if(isset($_SESSION['keranjang']) && is_array($_SESSION['keranjang'])){
    foreach($_SESSION['keranjang'] as $qty){
        $jumlahKeranjang += (int)$qty;
    }
}

/* ===============================
   USER DATA
================================ */
$userNama = $_SESSION['user_nama'] ?? 'User';
$isLogin  = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Toko Tas Wanita</title>

<!-- BOOTSTRAP -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- SWEETALERT -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
body{background:#f8f9fa;}
.text-gold{color:#d4af37;}

.btn-gold{
    background:linear-gradient(135deg,#d4af37,#b8962e);
    color:#111;
    font-weight:600;
    border:none;
    border-radius:30px;
}
.btn-gold:hover{opacity:.9}

/* NAVBAR */
.navbar-premium{
    background:rgba(11,29,58,.92);
    backdrop-filter:blur(12px);
    box-shadow:0 10px 35px rgba(0,0,0,.35);
    padding:12px 0;
    z-index:9999;
}
.brand-gold{
    color:#d4af37 !important;
    font-weight:800;
    font-size:22px;
}

/* MENU */
.nav-link{
    color:#e5e7eb !important;
    font-weight:500;
    transition:.3s;
}
.nav-link:hover,
.nav-link.active{
    color:#d4af37 !important;
}

/* SEARCH */
.nav-search{
    background:rgba(255,255,255,.08);
    border:1px solid rgba(255,255,255,.2);
    color:#fff;
    border-radius:30px;
    padding:6px 16px;
    font-size:.9rem;
}
.nav-search::placeholder{color:#bbb;}
.nav-search:focus{
    outline:none;
    box-shadow:none;
    border-color:#d4af37;
}

/* CART */
.cart-wrap{position:relative;}
.badge-cart{
    position:absolute;
    top:-6px;right:-6px;
    background:#d4af37;
    color:#0b1d3a;
    font-size:11px;
    padding:3px 7px;
    border-radius:50%;
    font-weight:700;
}

/* DROPDOWN */
.dropdown-menu{
    border-radius:14px;
    padding:10px;
}
.dropdown-item{
    border-radius:8px;
    font-size:.9rem;
}
.dropdown-item:hover{
    background:rgba(212,175,55,.15);
    color:#d4af37;
}
</style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-premium fixed-top">
<div class="container">

    <!-- BRAND -->
<a class="navbar-brand brand-gold" href="index.php">👜 Tas Wanita</a>

<!-- TOGGLER -->
<button class="navbar-toggler" type="button"
        data-bs-toggle="collapse" data-bs-target="#navMenu">
    <span class="navbar-toggler-icon"></span>
</button>

<div class="collapse navbar-collapse" id="navMenu">

    <!-- LEFT MENU -->
    <ul class="navbar-nav ms-3 gap-lg-2">
        <li class="nav-item">
            <a class="nav-link active" href="index.php">Home</a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="index.php#produk">Produk</a>
        </li>

        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                Kategori
            </a>
            <ul class="dropdown-menu dropdown-menu-dark">
                <li><a class="dropdown-item" href="index.php?kategori=handbag">Handbag</a></li>
                <li><a class="dropdown-item" href="index.php?kategori=slingbag">Sling Bag</a></li>
                <li><a class="dropdown-item" href="index.php?kategori=backpack">Backpack</a></li>
            </ul>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="promo.php">Promo</a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="tentang.php">Tentang</a>
        </li>
    </ul>

    <!-- SEARCH -->
    <form action="index.php" method="get" class="d-none d-lg-block ms-4">
        <input type="text" name="q" class="nav-search"
               placeholder="Cari tas premium...">
    </form>

    <!-- RIGHT -->
    <div class="ms-auto d-flex align-items-center gap-2">

            <!-- CART (SweetAlert Login Check) -->
            <div class="dropdown cart-wrap">
                <a href="#" class="btn btn-gold btn-sm"
                   onclick="cekLoginKeranjang(event)">🛒</a>

                <?php if($jumlahKeranjang > 0){ ?>
                    <span class="badge-cart"><?= $jumlahKeranjang ?></span>
                <?php } ?>

                <?php if($isLogin){ ?>
                <div class="dropdown-menu dropdown-menu-end p-3">
                    <?php if($jumlahKeranjang > 0){ ?>
                        <small class="text-muted"><?= $jumlahKeranjang ?> item di keranjang</small>
                        <a href="keranjang.php" class="btn btn-dark btn-sm w-100 mt-2">
                            Lihat Keranjang
                        </a>
                    <?php } else { ?>
                        <small class="text-muted">Keranjang kosong</small>
                    <?php } ?>
                </div>
                <?php } ?>
            </div>

            <!-- USER -->
            <?php if($isLogin){ ?>
            <div class="dropdown">
                <a href="#" class="btn btn-outline-light btn-sm dropdown-toggle"
                   data-bs-toggle="dropdown">
                    👤 <?= htmlspecialchars($userNama) ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="profil_user.php">Profil Saya</a></li>
                    <li><a class="dropdown-item" href="pesanan_saya.php">Pesanan Saya</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="logout_user.php">Logout</a></li>
                </ul>
            </div>
            <?php } else { ?>
                <a href="login_user.php" class="btn btn-outline-light btn-sm">Login</a>
            <?php } ?>

        </div>

    </div>
</div>
</nav>

<div style="height:90px"></div>

<script>
function cekLoginKeranjang(e){
    e.preventDefault();

    const isLogin = <?= $isLogin ? 'true' : 'false' ?>;

    if(!isLogin){
        Swal.fire({
            icon: 'warning',
            title: 'Harus Login',
            text: 'Silakan login terlebih dahulu untuk membuka keranjang',
            confirmButtonText: 'Login',
            confirmButtonColor: '#d4af37'
        }).then((res)=>{
            if(res.isConfirmed){
                window.location = 'login_user.php';
            }
        });
    }else{
        window.location = 'keranjang.php';
    }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
