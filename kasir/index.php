<?php
$title = 'Selamat Datang di Aplikasi Pengelolaan Laundry Asqia';
require 'koneksi.php';
require 'header.php';

setlocale(LC_ALL, 'id_id');
setlocale(LC_TIME, 'id_ID.utf8');

// Jumlah transaksi dalam 7 hari terakhir
$query = mysqli_query($conn, "
    SELECT COUNT(id_transaksi) AS jumlah_transaksi 
    FROM transaksi 
    WHERE tgl >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
");
$jumlah_transaksi = mysqli_fetch_assoc($query);

$query2 = mysqli_query($conn, "SELECT COUNT(id_pelanggan) as jumlah_pelanggan FROM pelanggan");
$jumlah_pelanggan = mysqli_fetch_assoc($query2);

$query3 = mysqli_query($conn, "SELECT COUNT(id_outlet) as jumlah_outlet FROM outlet");
$jumlah_outlet = mysqli_fetch_assoc($query3);

$query4 = mysqli_query($conn, "SELECT SUM(total_harga) as total_penghasilan FROM detail_transaksi 
    INNER JOIN transaksi ON transaksi.id_transaksi = detail_transaksi.id_transaksi 
    WHERE status_bayar = 'dibayar'");
$total_penghasilan = mysqli_fetch_assoc($query4);

$query5 = mysqli_query($conn, "SELECT SUM(total_harga) as penghasilan_tahun FROM detail_transaksi 
    INNER JOIN transaksi ON transaksi.id_transaksi = detail_transaksi.id_transaksi 
    WHERE status_bayar = 'dibayar' AND YEAR(tgl_pembayaran) = YEAR(NOW())");
$penghasilan_tahun = mysqli_fetch_assoc($query5);

$query6 = mysqli_query($conn, "SELECT SUM(total_harga) as penghasilan_bulan FROM detail_transaksi 
    INNER JOIN transaksi ON transaksi.id_transaksi = detail_transaksi.id_transaksi 
    WHERE status_bayar = 'dibayar' AND MONTH(tgl_pembayaran) = MONTH(NOW())");
$penghasilan_bulan = mysqli_fetch_assoc($query6);

$query7 = mysqli_query($conn, "SELECT SUM(total_harga) as penghasilan_minggu FROM detail_transaksi 
    INNER JOIN transaksi ON transaksi.id_transaksi = detail_transaksi.id_transaksi 
    WHERE status_bayar = 'dibayar' AND WEEK(tgl_pembayaran) = WEEK(NOW())");
$penghasilan_minggu = mysqli_fetch_assoc($query7);

// Hitung jumlah transaksi berdasarkan jenis paket dalam 1 minggu terakhir
$kiloan = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) AS total 
    FROM detail_transaksi d
    INNER JOIN paket_cuci p ON p.id_paket = d.id_paket
    INNER JOIN transaksi t ON t.id_transaksi = d.id_transaksi
    WHERE p.jenis_paket = 'kiloan'
    AND t.tgl >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
"));

$satuan = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) AS total 
    FROM detail_transaksi d
    INNER JOIN paket_cuci p ON p.id_paket = d.id_paket
    INNER JOIN transaksi t ON t.id_transaksi = d.id_transaksi
    WHERE p.jenis_paket = 'satuan'
    AND t.tgl >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
"));

$jumbo = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) AS total 
    FROM detail_transaksi d
    INNER JOIN paket_cuci p ON p.id_paket = d.id_paket
    INNER JOIN transaksi t ON t.id_transaksi = d.id_transaksi
    WHERE p.jenis_paket = 'jumbo'
    AND t.tgl >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
"));

// Hitung jumlah antar jemput dalam 1 minggu terakhir
$antar_gratis = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) AS total 
    FROM transaksi 
    WHERE antar_jemput = 'gratis' 
    AND tgl >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
"));

$antar_sendiri = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) AS total 
    FROM transaksi 
    WHERE antar_jemput = 'ambil_sendiri' 
    AND tgl >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
"));

// --- Data Grafik Mingguan ---
$query_chart = mysqli_query($conn, "
    SELECT DATE(tgl_pembayaran) AS tgl, SUM(total_harga) AS total
    FROM detail_transaksi
    INNER JOIN transaksi ON transaksi.id_transaksi = detail_transaksi.id_transaksi
    WHERE status_bayar = 'dibayar'
      AND tgl_pembayaran >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    GROUP BY DATE(tgl_pembayaran)
    ORDER BY tgl_pembayaran ASC
");

$hari_labels = [];
$hari_data = [];
$hari_indonesia = ['Sun' => 'Min', 'Mon' => 'Sen', 'Tue' => 'Sel', 'Wed' => 'Rab', 'Thu' => 'Kam', 'Fri' => 'Jum', 'Sat' => 'Sab'];

while ($row = mysqli_fetch_assoc($query_chart)) {
    $day = date('D', strtotime($row['tgl']));
    $hari_labels[] = $hari_indonesia[$day];
    $hari_data[] = (int) $row['total'];
}
?>


<div class="panel-header bg-secondary-gradient">
    <div class="page-inner py-5">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
            <div>
                <h1 class="text-white pb-2 fw-bold"><?= $title; ?></h1>
                <h2 class="text-white op-7 mb-2">Kasir Dashboard</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-inner mt--5">
    <div class="row">
        <!-- Pelanggan -->
        <div class="col-sm-6 col-md-4">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-info bubble-shadow-small">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                        <div class="col col-stats ml-3 ml-sm-0">
                            <div class="numbers">
                                <p class="card-category">Jumlah Pelanggan</p>
                                <h4 class="card-title"><?= $jumlah_pelanggan['jumlah_pelanggan']; ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transaksi -->
        <div class="col-sm-6 col-md-4">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-secondary bubble-shadow-small">
                                <i class="flaticon-success"></i>
                            </div>
                        </div>
                        <div class="col col-stats ml-3 ml-sm-0">
                            <div class="numbers">
                                <p class="card-category">Transaksi Minggu Ini</p>
                                <h4 class="card-title"><?= $jumlah_transaksi['jumlah_transaksi']; ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kiloan -->
        <div class="col-sm-6 col-md-4">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-primary bubble-shadow-small">
                                <i class="fas fa-weight-hanging"></i>
                            </div>
                        </div>
                        <div class="col col-stats ml-3 ml-sm-0">
                            <div class="numbers">
                                <p class="card-category">Kiloan</p>
                                <h4 class="card-title"><?= $kiloan['total']; ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <!-- Satuan -->
        <div class="col-sm-6 col-md-4">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-success bubble-shadow-small">
                                <i class="fas fa-tshirt"></i>
                            </div>
                        </div>
                        <div class="col col-stats ml-3 ml-sm-0">
                            <div class="numbers">
                                <p class="card-category">Satuan</p>
                                <h4 class="card-title"><?= $satuan['total']; ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jumbo -->
        <div class="col-sm-6 col-md-4">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-warning bubble-shadow-small">
                                <i class="fas fa-box"></i>
                            </div>
                        </div>
                        <div class="col col-stats ml-3 ml-sm-0">
                            <div class="numbers">
                                <p class="card-category">Jumbo</p>
                                <h4 class="card-title"><?= $jumbo['total']; ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Antar Gratis -->
        <div class="col-sm-6 col-md-4">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-info bubble-shadow-small">
                                <i class="fas fa-shuttle-van"></i>
                            </div>
                        </div>
                        <div class="col col-stats ml-3 ml-sm-0">
                            <div class="numbers">
                                <p class="card-category">Antar Gratis</p>
                                <h4 class="card-title"><?= $antar_gratis['total']; ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Ambil Sendiri -->
        <div class="col-sm-6 col-md-4">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-secondary bubble-shadow-small">
                                <i class="fas fa-store"></i>
                            </div>
                        </div>
                        <div class="col col-stats ml-3 ml-sm-0">
                            <div class="numbers">
                                <p class="card-category">Ambil Di Tempat</p>
                                <h4 class="card-title"><?= $antar_sendiri['total']; ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card card-dark bg-secondary2">
                <div class="card-body curves-shadow">
                    <h1><?= 'Rp ' . number_format($penghasilan_minggu['penghasilan_minggu']); ?></h1>
                    <h5 class="op-8">Penghasilan Minggu Ini</h5>
                    <hr>
                    <canvas id="grafikPenghasilan" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('grafikPenghasilan').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($hari_labels); ?>,
            datasets: [{
                label: 'Penghasilan (Rp)',
                data: <?= json_encode($hari_data); ?>,
                backgroundColor: 'rgba(255,255,255,0.5)',
                borderColor: '#fff',
                borderWidth: 2,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                x: {
                    ticks: { color: '#fff' },
                    grid: { display: false }
                },
                y: {
                    ticks: {
                        color: '#fff',
                        callback: (value) => 'Rp ' + value.toLocaleString('id-ID')
                    },
                    grid: { color: 'rgba(255,255,255,0.2)' }
                }
            }
        }
    });
</script>

<?php
require 'footer.php';
?>
