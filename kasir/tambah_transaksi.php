<?php
$title = 'Tambah Transaksi';
require 'koneksi.php';

$tgl = date('Y-m-d H:i:s');
$seminggu = mktime(0, 0, 0, date("n"), date("j") + 7, date("Y"));
$batas_waktu = date("Y-m-d H:i:s", $seminggu);

$kode = "CLN" . date('Ymdsi');
$id_outlet = $_SESSION['outlet_id'];
$id_user = $_SESSION['user_id'];
$id_pelanggan = mysqli_real_escape_string($conn, $_GET['id']);

// Ambil data outlet
$query = "SELECT nama_outlet FROM outlet WHERE id_outlet = '$id_outlet'";
$outlet = mysqli_fetch_assoc(mysqli_query($conn, $query));

// Ambil data pelanggan
$query2 = "SELECT nama_pelanggan FROM pelanggan WHERE id_pelanggan = '$id_pelanggan'";
$pelanggan = mysqli_fetch_assoc(mysqli_query($conn, $query2));

// Ambil paket untuk outlet terkait
$query3 = "SELECT * FROM paket_cuci WHERE outlet_id = '$id_outlet'";
$paket = mysqli_query($conn, $query3);

// Proses simpan transaksi
if (isset($_POST['btn-simpan'])) {
    $kode_invoice = mysqli_real_escape_string($conn, $_POST['kode_invoice']);
    $diskon = mysqli_real_escape_string($conn, $_POST['diskon']);
    $id_paket = mysqli_real_escape_string($conn, $_POST['id_paket']);
    $qty = mysqli_real_escape_string($conn, $_POST['qty']);
    $antar_jemput = mysqli_real_escape_string($conn, $_POST['antar_jemput']); // Tambahan field antar jemput

    // Simpan ke tabel transaksi
    $query4 = "INSERT INTO transaksi (outlet_id, kode_invoice, id_pelanggan, tgl, batas_waktu, diskon, status, status_bayar, antar_jemput, id_user)
               VALUES ('$id_outlet', '$kode_invoice', '$id_pelanggan', '$tgl', '$batas_waktu', '$diskon', 'baru', 'belum', '$antar_jemput', '$id_user')";
    $insert = mysqli_query($conn, $query4);

    if ($insert) {
        // Ambil ID transaksi baru
        $query6 = mysqli_query($conn, "SELECT id_transaksi FROM transaksi WHERE kode_invoice = '$kode_invoice'");
        $transaksi = mysqli_fetch_assoc($query6);
        $id_transaksi = $transaksi['id_transaksi'];

        // Ambil harga paket
        $query5 = mysqli_query($conn, "SELECT harga FROM paket_cuci WHERE id_paket = $id_paket");
        $paket_harga = mysqli_fetch_assoc($query5);
        $total = $paket_harga['harga'] * $qty;

        // Simpan ke detail transaksi
        $query_detail = "INSERT INTO detail_transaksi (id_transaksi, id_paket, qty, total_harga)
                         VALUES ('$id_transaksi', '$id_paket', '$qty', '$total')";
        $insert_detail = mysqli_query($conn, $query_detail);

        if ($insert_detail) {
            header('Location: transaksi_sukses.php?id=' . $id_transaksi);
            exit;
        } else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Gagal menambahkan detail transaksi!</div>";
            header('Location: tambah_transaksi.php');
        }
    }
}

require 'header.php';
?>

<!-- HTML FORM START -->
<div class="content">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Tambah Transaksi</h4>
            <?php if (!empty($_SESSION['msg'])): ?>
            <div class="alert alert-success" role="alert" id="msg">
                <?= $_SESSION['msg']; unset($_SESSION['msg']); ?>
            </div>
            <?php endif; ?>
        </div>

        <div class="row">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title"><?= $title; ?></div>
                    </div>
                    <form action="" method="POST">
                        <div class="card-body">
                            <!-- KODE INVOICE -->
                            <div class="form-group">
                                <label>Kode Invoice</label>
                                <input type="text" name="kode_invoice" class="form-control" value="<?= $kode; ?>" readonly>
                            </div>

                            <!-- PELANGGAN -->
                            <div class="form-group">
                                <label>Pelanggan</label>
                                <input type="text" class="form-control" value="<?= $pelanggan['nama_pelanggan']; ?>" readonly>
                            </div>

                            <!-- PILIH PAKET -->
                            <div class="form-group">
                                <label>Pilih Paket</label>
                                <select name="id_paket" class="form-control" required>
                                    <option value="" disabled selected>-- Pilih Paket --</option>
                                    <?php while ($key = mysqli_fetch_array($paket)) : ?>
                                    <option value="<?= $key['id_paket']; ?>">
                                        <?= $key['nama_paket']; ?> (<?= $key['jenis_paket']; ?> - Rp<?= number_format($key['harga'], 0, ',', '.'); ?>)
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <!-- JUMLAH -->
                            <div class="form-group">
                                <label>Jumlah</label>
                                <input type="number" name="qty" class="form-control" required>
                            </div>

                            <!-- DISKON -->
                            <div class="form-group">
                                <label>Diskon (%)</label>
                                <input type="number" name="diskon" class="form-control" value="0">
                            </div>

                            <!-- ANTAR JEMPUT -->
                            <div class="form-group">
                                <label>Antar Jemput</label>
                                <select name="antar_jemput" class="form-control" required>
                                    <option value="gratis">Gratis</option>
                                    <option value="ambil_sendiri">Ambil Sendiri</option>
                                </select>
                            </div>
                        </div>

                        <!-- SUBMIT BUTTON -->
                        <div class="card-action">
                            <button type="submit" name="btn-simpan" class="btn btn-success">Submit</button>
                            <a href="javascript:void(0)" onclick="window.history.back();" class="btn btn-danger">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require 'footer.php'; ?>
