<?php
require 'koneksi.php';

$query = "SELECT t.*, p.nama_pelanggan, d.total_harga, o.nama_outlet, pk.jenis_paket, pk.nama_paket 
          FROM transaksi t
          INNER JOIN pelanggan p ON p.id_pelanggan = t.id_pelanggan
          INNER JOIN detail_transaksi d ON d.id_transaksi = t.id_transaksi
          INNER JOIN outlet o ON o.id_outlet = t.outlet_id
          INNER JOIN paket_cuci pk ON pk.id_paket = d.id_paket
          WHERE pk.jenis_paket = 'satuan'
          ORDER BY t.tgl DESC";

$data = mysqli_query($conn, $query);
?>

<?php include 'riwayat_template.php'; ?>
