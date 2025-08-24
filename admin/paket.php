<?php
$title = 'Kelolah Data Paket';
require 'koneksi.php';

// Eksekusi query dan simpan hasil ke $data
$query = "SELECT * FROM paket_cuci";
$data = mysqli_query($conn, $query); // ← WAJIB ditambahkan

require 'header.php';
?>

<div class="panel-header bg-primary-gradient">
    <div class="page-inner py-5">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
            <div>
                <h2 class="text-white pb-2 fw-bold">Dashboard</h2>
            </div>
        </div>
        <?php if (isset($_SESSION['msg']) && $_SESSION['msg'] <> '') { ?>
            <div class="alert alert-success" role="alert" id="msg">
                <?= $_SESSION['msg']; ?>
            </div>
        <?php }
        $_SESSION['msg'] = ''; ?>
    </div>
</div>

<div class="page-inner mt--5">
    <div class="row"><!-- diperbaiki dari <diva> -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title"><?= $title; ?></h4>
                        <a href="tambah_paket.php" class="btn btn-primary btn-round ml-auto">
                            <i class="fa fa-plus"></i>
                            Tambah Paket
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="basic-datatables" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 7%">#</th>
                                    <th>Nama Paket</th>
                                    <th>Jenis</th>
                                    <th>Harga</th>
                                    <th style="width: 10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                if (mysqli_num_rows($data) > 0) {
                                    while ($paket = mysqli_fetch_assoc($data)) {
                                ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= htmlspecialchars($paket['nama_paket']); ?></td>
                                            <td><?= htmlspecialchars($paket['jenis_paket']); ?></td>
                                            <td><?= 'Rp ' . number_format($paket['harga'], 0, ',', '.'); ?></td>
                                            <td>
                                                <div class="form-button-action">
                                                    <a href="edit_paket.php?id=<?= $paket['id_paket']; ?>" type="button" data-toggle="tooltip" title="Edit" class="btn btn-link btn-primary btn-lg">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <!-- Hapus jika diperlukan -->
                                                    <!--
                                                    <a href="hapus_paket.php?id=<?= $paket['id_paket']; ?>" onclick="return confirm('Yakin hapus data?');" type="button" data-toggle="tooltip" title="Hapus" class="btn btn-link btn-danger">
                                                        <i class="fa fa-times"></i>
                                                    </a>
                                                    -->
                                                </div>
                                            </td>
                                        </tr>
                                <?php }
                                } else {
                                    echo '<tr><td colspan="5" class="text-center">Data tidak tersedia.</td></tr>';
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
