<?php
session_start();
include 'koneksi.php';
include 'header_admin.php';

/* ===============================
   PROTEKSI ADMIN
================================ */
if(!isset($_SESSION['admin'])){
    header("location:login.php");
    exit;
}

/* ===============================
   STATISTIK DATA
================================ */

// Total Pesanan
$total_pesanan = mysqli_num_rows(
    mysqli_query($conn,"SELECT id FROM pesanan")
);

// Pesanan Menunggu
$menunggu = mysqli_num_rows(
    mysqli_query($conn,"SELECT id FROM pesanan WHERE status='Menunggu'")
);

// Pesanan Selesai
$selesai = mysqli_num_rows(
    mysqli_query($conn,"SELECT id FROM pesanan WHERE status='Selesai'")
);

// Total Pendapatan
$q_total = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT SUM(total) AS total FROM pesanan WHERE status='Selesai'")
);
$total_pendapatan = $q_total['total'] ?? 0;

// Total User
$total_user = mysqli_num_rows(
    mysqli_query($conn,"SELECT id FROM user")
);

/* ===============================
   DATA GRAFIK PENJUALAN
================================ */
$q_grafik = mysqli_query($conn,"
    SELECT tanggal, SUM(total) AS total_harian
    FROM pesanan
    WHERE status='Selesai'
    GROUP BY tanggal
    ORDER BY tanggal ASC
");

$tanggal = [];
$penjualan = [];
while($g = mysqli_fetch_assoc($q_grafik)){
    $tanggal[]   = $g['tanggal'];
    $penjualan[] = $g['total_harian'];
}
?>

<div class="container mt-4">

<h4 class="fw-bold text-gold mb-4">📊 Dashboard Admin</h4>

<!-- STAT CARD -->
<div class="row g-4 mb-4">

    <div class="col-md-3">
        <div class="card p-4 text-center">
            <small class="text-muted">Total Pesanan</small>
            <h3 class="fw-bold"><?= $total_pesanan; ?></h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card p-4 text-center">
            <small class="text-muted">Pesanan Menunggu</small>
            <h3 class="fw-bold text-warning"><?= $menunggu; ?></h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card p-4 text-center">
            <small class="text-muted">Pesanan Selesai</small>
            <h3 class="fw-bold text-success"><?= $selesai; ?></h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card p-4 text-center">
            <small class="text-muted">Total User</small>
            <h3 class="fw-bold"><?= $total_user; ?></h3>
        </div>
    </div>

</div>

<!-- PENDAPATAN -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card p-4 text-center">
            <small class="text-muted">Total Pendapatan</small>
            <h2 class="fw-bold text-gold">
                Rp <?= number_format($total_pendapatan); ?>
            </h2>
        </div>
    </div>
</div>

<!-- GRAFIK -->
<div class="card p-4">
    <h6 class="fw-bold mb-3">📈 Grafik Penjualan</h6>
    <canvas id="grafikPenjualan" height="90"></canvas>
</div>

</div>

<!-- CHART JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('grafikPenjualan');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($tanggal); ?>,
        datasets: [{
            label: 'Total Penjualan',
            data: <?= json_encode($penjualan); ?>,
            borderColor: '#d4af37',
            backgroundColor: 'rgba(212,175,55,0.25)',
            tension: 0.4,
            fill: true,
            pointRadius: 4
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: true
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>

<?php include 'footer.php'; ?>
