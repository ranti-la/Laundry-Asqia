<?php
$title = 'Laporan Minggu Ini';
require 'koneksi.php';

// Ambil semua data transaksi minggu ini
$query = "
    SELECT 
        transaksi.*, 
        pelanggan.nama_pelanggan, 
        detail_transaksi.total_harga, 
        paket_cuci.jenis_paket,
        transaksi.antar_jemput,
        outlet.nama_outlet, 
        DATE(transaksi.tgl_pembayaran) AS tanggal_pembayaran,
        DAYNAME(transaksi.tgl_pembayaran) AS hari
    FROM transaksi 
    INNER JOIN pelanggan ON pelanggan.id_pelanggan = transaksi.id_pelanggan 
    INNER JOIN detail_transaksi ON detail_transaksi.id_transaksi = transaksi.id_transaksi 
    INNER JOIN paket_cuci ON paket_cuci.id_paket = detail_transaksi.id_paket
    INNER JOIN outlet ON outlet.id_outlet = transaksi.outlet_id
    WHERE WEEK(transaksi.tgl_pembayaran, 1) = WEEK(CURDATE(), 1) 
        AND YEAR(transaksi.tgl_pembayaran) = YEAR(CURDATE())
    ORDER BY transaksi.tgl_pembayaran ASC
";

$data = mysqli_query($conn, $query);

// Simpan data dalam array berdasarkan hari
$transaksi_per_hari = [];
while ($row = mysqli_fetch_assoc($data)) {
    $hari = date('l', strtotime($row['tanggal_pembayaran']));
    $transaksi_per_hari[$hari][] = $row;
}

require 'header.php';
?>

<!-- Tampilan -->
<div class="page-inner mt--5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h4 class="card-title"><?= $title; ?></h4>
                    <a href="cetak.php" target="_blank" class="btn btn-primary btn-round ml-auto">
                        <i class="fas fa-print"></i>
                        Cetak Laporan
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <?php
                        $hari_indonesia = [
                            'Monday' => 'Senin',
                            'Tuesday' => 'Selasa',
                            'Wednesday' => 'Rabu',
                            'Thursday' => 'Kamis',
                            'Friday' => 'Jumat',
                            'Saturday' => 'Sabtu',
                            'Sunday' => 'Minggu',
                        ];

                        foreach ($hari_indonesia as $hari_eng => $hari_indo) {
                            if (isset($transaksi_per_hari[$hari_eng])) {
                        ?>
                                <h4 class="mt-4 mb-2"><?= $hari_indo; ?></h4>
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width: 7%">#</th>
                                            <th>Tanggal</th>
                                            <th>Kode</th>
                                            <th>Nama Pelanggan</th>
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
                                        foreach ($transaksi_per_hari[$hari_eng] as $trans) {
                                        ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td><?= date('d-m-Y', strtotime($trans['tanggal_pembayaran'])); ?></td>
                                                <td><?= $trans['kode_invoice']; ?></td>
                                                <td><?= $trans['nama_pelanggan']; ?></td>
                                                <td><?= $trans['status']; ?></td>
                                                <td><?= $trans['status_bayar']; ?></td>
                                                <td><?= $trans['jenis_paket']; ?></td>
                                                <td><?= $trans['antar_jemput'] ?? '-'; ?></td>
                                                <td><?= 'Rp ' . number_format($trans['total_harga']); ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                        <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require 'footer.php'; ?>
