<?php
// Ambil parameter
$type = isset($_GET['type']) ? $_GET['type'] : 'kiloan';
$title = "Riwayat Laundry " . ucfirst($type);

require 'koneksi.php';

// Filter query berdasarkan type
$where = "1=1"; // default
if (in_array($type, ['kiloan', 'satuan', 'jumbo'])) {
    $where = "pc.jenis_paket = '$type'";
} elseif ($type == 'antar_gratis') {
    $where = "t.antar_jemput = 'gratis'";
    $title = "Riwayat Jasa Antar Jemput Gratis";
} elseif ($type == 'antar_bayar') {
    $where = "t.antar_jemput = 'ambil_sendiri'";
    $title = "Riwayat Ambil Di Tempat";
}

// Query transaksi minggu ini
$query = "
    SELECT 
        t.*, 
        p.nama_pelanggan, 
        d.total_harga, 
        o.nama_outlet,
        pc.jenis_paket,
        t.antar_jemput,
        DATE(t.tgl_pembayaran) AS tanggal_pembayaran
    FROM transaksi t
    INNER JOIN pelanggan p ON p.id_pelanggan = t.id_pelanggan
    INNER JOIN detail_transaksi d ON d.id_transaksi = t.id_transaksi
    INNER JOIN outlet o ON o.id_outlet = t.outlet_id
    INNER JOIN paket_cuci pc ON pc.id_paket = d.id_paket
    WHERE WEEK(t.tgl_pembayaran) = WEEK(NOW())
      AND $where
    ORDER BY t.tgl_pembayaran ASC
";
$data = mysqli_query($conn, $query);

require 'header.php';
?>

<div class="panel-header bg-primary-gradient">
    <div class="page-inner py-5">
        <h2 class="text-white pb-2 fw-bold">Dashboard</h2>
    </div>
</div>

<div class="page-inner mt--5">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title"><?= $title; ?></h4>
                        <!-- <a href="cetak.php?type=<?= $type ?>" target="_blank" class="btn btn-primary btn-round ml-auto">
                            <i class="fas fa-print"></i>
                            Cetak Laporan
                        </a> -->
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="basic-datatables" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tanggal</th>
                                    <th>Kode</th>
                                    <th>Nama Pelanggan</th>
                                    <th>Jenis Paket</th>
                                    <th>Antar Jemput</th>
                                    <th>Status</th>
                                    <th>Pembayaran</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                if (mysqli_num_rows($data) > 0) {
                                    while ($trans = mysqli_fetch_assoc($data)) {
                                ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= date('d-m-Y', strtotime($trans['tanggal_pembayaran'])); ?></td>
                                            <td><?= $trans['kode_invoice']; ?></td>
                                            <td><?= $trans['nama_pelanggan']; ?></td>
                                            <td><?= ucfirst($trans['jenis_paket']); ?></td>
                                            <td><?= ucfirst($trans['antar_jemput']); ?></td>
                                            <td><?= $trans['status']; ?></td>
                                            <td><?= $trans['status_bayar']; ?></td>
                                            <td><?= 'Rp ' . number_format($trans['total_harga']); ?></td>
                                        </tr>
                                <?php }
                                } else {
                                    echo "<tr><td colspan='9' class='text-center'>Tidak ada data</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require 'footer.php'; ?>
