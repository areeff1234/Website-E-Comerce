<footer class="bg-navy text-light mt-5 pt-5"
        style="animation: fadeUp .8s ease-in-out;">

<style>
.bg-navy{
    background:linear-gradient(135deg,#0b1d3a,#132f5e);
}
footer{
    color:#f1f5f9;
}
footer p,
footer small,
footer a{
    color:#e5e7eb;
}
footer a:hover{
    color:#d4af37;
}
footer hr{
    border-color:rgba(255,255,255,.25);
}
@keyframes fadeUp{
    from{opacity:0;transform:translateY(20px);}
    to{opacity:1;transform:translateY(0);}
}
</style>

<div class="container">

    <div class="row g-4">

        <div class="col-md-3">
            <h6 class="fw-bold text-gold mb-3">👜 Toko Tas Wanita</h6>
            <p class="small mb-2">
                Menyediakan koleksi tas wanita premium dengan desain
                elegan, modern, dan berkualitas tinggi.
            </p>
            <p class="small mb-0">
                <b>Alamat:</b> Purwokerto, Jawa Tengah<br>
                <b>Jam:</b> Senin – Sabtu (09.00 – 21.00)
            </p>
        </div>

        <div class="col-md-3">
            <h6 class="fw-bold text-gold mb-3">📞 Kontak</h6>
            <p class="small mb-1">📱 WhatsApp: 08xxxxxxxxxx</p>
            <p class="small mb-1">📧 Email: admin@tokotaswanita.com</p>
            <p class="small mb-0">🏦 Transfer: BCA 123456789</p>
        </div>

        <div class="col-md-3">
            <h6 class="fw-bold text-gold mb-3">🌐 Sosial Media</h6>
            <div class="d-flex flex-column gap-1 small">
                <a href="#">🔵 Facebook</a>
                <a href="#">🟣 Instagram</a>
                <a href="#">🟢 WhatsApp</a>
            </div>
        </div>

        <div class="col-md-3">
            <h6 class="fw-bold text-gold mb-3">🔐 Portal Akses</h6>
            <div class="d-grid gap-2">
                <a href="login_user.php" class="btn btn-outline-light btn-sm">
                    👤 Login User
                </a>
                <a href="login.php" class="btn btn-gold btn-sm">
                    🛡️ Login Admin
                </a>
            </div>
        </div>

    </div>

    <hr class="my-4">

    <div class="text-center pb-3">
        <p class="mb-1 fw-semibold text-gold">
            © <?= date('Y'); ?> Toko Tas Wanita
        </p>
        <small>
            Sistem Informasi E-Commerce • PHP & MySQL<br>
            Dibuat oleh <b class="text-gold">Nur Arif</b>
        </small>
    </div>

</div>
</footer>
