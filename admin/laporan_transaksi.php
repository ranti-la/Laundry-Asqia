<?php

require 'koneksi.php';

// Ambil daftar tahun dari database
$years = [];
$resultYears = mysqli_query($conn, "SELECT DISTINCT YEAR(tgl) as tahun FROM transaksi ORDER BY tahun DESC");
while ($row = mysqli_fetch_assoc($resultYears)) {
    $years[] = $row['tahun'];
}

// Ambil filter periode dan tahun dari URL
$periode = isset($_GET['periode']) ? $_GET['periode'] : 'minggu';
$tahunDipilih = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

// Query berdasarkan periode
switch ($periode) {
    case 'tahun':
        $query = "SELECT transaksi.*, pelanggan.nama_pelanggan, detail_transaksi.total_harga 
                  FROM transaksi 
                  INNER JOIN pelanggan ON pelanggan.id_pelanggan = transaksi.id_pelanggan 
                  INNER JOIN detail_transaksi ON detail_transaksi.id_transaksi = transaksi.id_transaksi
                  WHERE YEAR(transaksi.tgl) = $tahunDipilih
                  ORDER BY transaksi.tgl DESC";
        $judul = "Laporan Transaksi Tahun " . $tahunDipilih;
        break;

    case 'bulan':
        $query = "SELECT transaksi.*, pelanggan.nama_pelanggan, detail_transaksi.total_harga 
                  FROM transaksi 
                  INNER JOIN pelanggan ON pelanggan.id_pelanggan = transaksi.id_pelanggan 
                  INNER JOIN detail_transaksi ON detail_transaksi.id_transaksi = transaksi.id_transaksi
                  WHERE MONTH(transaksi.tgl) = MONTH(CURDATE()) 
                  AND YEAR(transaksi.tgl) = $tahunDipilih
                  ORDER BY transaksi.tgl DESC";
        $judul = "Laporan Transaksi Bulan " . date('F') . " $tahunDipilih";
        break;

    default: // minggu
        $query = "SELECT transaksi.*, pelanggan.nama_pelanggan, detail_transaksi.total_harga 
                  FROM transaksi 
                  INNER JOIN pelanggan ON pelanggan.id_pelanggan = transaksi.id_pelanggan 
                  INNER JOIN detail_transaksi ON detail_transaksi.id_transaksi = transaksi.id_transaksi
                  WHERE transaksi.tgl >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                  ORDER BY transaksi.tgl DESC";
        $judul = "Laporan Transaksi 7 Hari Terakhir";
        break;
}

$data = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $judul; ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center"><?= $judul; ?></h2>
        <p class="text-center"><?= date('d-m-Y H:i'); ?></p>

        <!-- Filter Periode dan Tahun -->
        <div class="mb-3 no-print">
            <form method="GET" class="form-inline">
                <label for="periode" class="mr-2">Periode:</label>
                <select name="periode" id="periode" class="form-control mr-2" onchange="this.form.submit()">
                    <option value="minggu" <?= ($periode == 'minggu') ? 'selected' : ''; ?>>Mingguan</option>
                    <option value="bulan" <?= ($periode == 'bulan') ? 'selected' : ''; ?>>Bulanan</option>
                    <option value="tahun" <?= ($periode == 'tahun') ? 'selected' : ''; ?>>Tahunan</option>
                </select>

                <?php if ($periode == 'tahun' || $periode == 'bulan'): ?>
                <label for="tahun" class="mr-2">Tahun:</label>
                <select name="tahun" id="tahun" class="form-control mr-2" onchange="this.form.submit()">
                    <?php foreach ($years as $year): ?>
                        <option value="<?= $year; ?>" <?= ($tahunDipilih == $year) ? 'selected' : ''; ?>><?= $year; ?></option>
                    <?php endforeach; ?>
                </select>
                <?php endif; ?>

                <button type="submit" class="btn btn-secondary">Tampilkan</button>
            </form>
        </div>

        <!-- Tabel Transaksi -->
        <table class="table table-bordered table-striped mt-3">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>Kode Invoice</th>
                    <th>Nama Pelanggan</th>
                    <th>Tanggal</th>
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
                        echo "<tr>
                                <td>{$no}</td>
                                <td>{$trans['kode_invoice']}</td>
                                <td>{$trans['nama_pelanggan']}</td>
                                <td>" . date('d-m-Y', strtotime($trans['tgl'])) . "</td>
                                <td>{$trans['status']}</td>
                                <td>{$trans['status_bayar']}</td>
                                <td>Rp " . number_format($trans['total_harga']) . "</td>
                              </tr>";
                        $no++;
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center'>Tidak ada transaksi</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <button class="btn btn-primary no-print" onclick="window.print()">Cetak</button>
    </div>
</body>
</html>
