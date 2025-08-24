<?php
session_start();
require 'koneksi.php';

$query = "SELECT transaksi.*, pelanggan.nama_pelanggan, detail_transaksi.total_harga, transaksi.antar_jemput, paket_cuci.jenis_paket
          FROM transaksi 
          INNER JOIN pelanggan ON pelanggan.id_pelanggan = transaksi.id_pelanggan 
          INNER JOIN detail_transaksi ON detail_transaksi.id_transaksi = transaksi.id_transaksi 
          INNER JOIN paket_cuci ON paket_cuci.id_paket = detail_transaksi.id_paket
          WHERE transaksi.tgl >= DATE_SUB(NOW(), INTERVAL 7 DAY)
          ORDER BY transaksi.tgl DESC";

$data = mysqli_query($conn, $query);

setlocale(LC_ALL, 'id_ID.utf8');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi Mingguan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        @media print {
            .no-print {
                display: none;
            }
        }
        h2, h6 {
            text-align: center;
        }
        .report-header {
            margin-top: 20px;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="report-header">
        <h2>DATA LAPORAN TRANSAKSI LAUNDRY (7 HARI TERAKHIR)</h2>
        <h6><?= strftime('%A, %d %B %Y') ?></h6>
        <h6>Oleh : <?= $_SESSION['username'] ?? 'Admin'; ?></h6>
        <hr>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
        <tr>
            <th>#</th>
            <th>Kode Invoice</th>
            <th>Nama Pelanggan</th>
            <th>Tanggal</th>
            <th>Status</th>
            <th>Pembayaran</th>
            <th>Jenis Paket</th>
            <th>Antar Jemput</th>
            <th>Total</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $no = 1;
        $total_semua = 0;
        if (mysqli_num_rows($data) > 0) {
            while ($trans = mysqli_fetch_assoc($data)) {
                $total_semua += $trans['total_harga'];
                echo "<tr>
                    <td>{$no}</td>
                    <td>{$trans['kode_invoice']}</td>
                    <td>{$trans['nama_pelanggan']}</td>
                    <td>" . date('d-m-Y', strtotime($trans['tgl'])) . "</td>
                    <td>{$trans['status']}</td>
                    <td>{$trans['status_bayar']}</td>
                    <td>{$trans['jenis_paket']}</td>
                    <td>" . ($trans['antar_jemput'] ?? '-') . "</td>
                    <td>Rp " . number_format($trans['total_harga']) . "</td>
                </tr>";
                $no++;
            }
            echo "<tr>
                    <td colspan='8' class='text-right font-weight-bold'>Total Keseluruhan</td>
                    <td><strong>Rp " . number_format($total_semua) . "</strong></td>
                  </tr>";
        } else {
            echo "<tr><td colspan='9' class='text-center'>Tidak ada transaksi dalam 7 hari terakhir.</td></tr>";
        }
        ?>
        </tbody>
    </table>

    <div class="text-right no-print">
        <button class="btn btn-primary" onclick="window.print()">Cetak Laporan</button>
    </div>
</div>
</body>
</html>
