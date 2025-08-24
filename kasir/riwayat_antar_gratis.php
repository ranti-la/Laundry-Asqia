<?php
require 'koneksi.php';

// Query transaksi antar jemput gratis (7 hari terakhir)
$query = "SELECT t.*, p.nama_pelanggan, d.total_harga, o.nama_outlet
          FROM transaksi t
          INNER JOIN pelanggan p ON p.id_pelanggan = t.id_pelanggan
          INNER JOIN detail_transaksi d ON d.id_transaksi = t.id_transaksi
          INNER JOIN outlet o ON o.id_outlet = t.outlet_id
          WHERE t.antar_jemput = 'gratis'
            AND t.tgl >= DATE_SUB(NOW(), INTERVAL 7 DAY)
          ORDER BY t.tgl DESC";

$data = mysqli_query($conn, $query);
?>

<?php include 'riwayat_template.php'; ?>
