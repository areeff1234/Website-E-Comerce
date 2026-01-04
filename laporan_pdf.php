<?php
require('fpdf/fpdf.php');
include 'koneksi.php';

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',14);
$pdf->Cell(190,10,'LAPORAN PENJUALAN',0,1,'C');

$pdf->SetFont('Arial','',10);
$q=mysqli_query($conn,"SELECT * FROM pesanan");
while($p=mysqli_fetch_assoc($q)){
    $pdf->Cell(0,8,
    "ID: $p[id] | Tgl: $p[tanggal] | Total: Rp ".number_format($p['total']),
    0,1);
}

$pdf->Output();
