<?php
$id_bayar = $_GET['id_bayar'];
$sql = "SELECT bayar.*,siswa.nama,siswa.kelas FROM bayar,siswa WHERE id_bayar=$id_bayar AND bayar.id_siswa=siswa.id_siswa";
$query = mysqli_query($koneksi, $sql);
$bayar = mysqli_fetch_array($query);
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Detail Pembayaran Siswa</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php?p=riwayat">Riwayat Tagihan & Pembayaran</a></li>
                        <li class="breadcrumb-item active">Detail Pembayaran Siswa</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h5>Informasi Pembayaran Siswa</h5>
                </div>
                <div class="card-body">
                    <form action="aksi/bayar.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="aksi" value="konfirmasi-bayar">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="id_bayar">No. Pembayaran</label>
                                <input type="text" name="id_bayar" class="form-control" readonly value="<?= $bayar['id_bayar']; ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="nama">Siswa</label>
                                <input type="text" name="nama" class="form-control" readonly value="<?= $bayar['nama'] . " [" . $bayar['kelas'] . "]"; ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label for="id_bayar_metode">Metode Pembayaran</label>
                                <select name="id_bayar_metode" class="form-control" readonly>
                                    <?php
                                    $id_bayar_metode = $bayar['id_bayar_metode'];
                                    $sql_metode = "SELECT * FROM bayar_metode WHERE dihapus_pada IS NULL AND id_bayar_metode=$id_bayar_metode";
                                    $query_metode = mysqli_query($koneksi, $sql_metode);
                                    while ($bayar_metode = mysqli_fetch_array($query_metode)) {
                                        echo "<option value='$bayar_metode[id_bayar_metode]'>$bayar_metode[metode] - ($bayar_metode[nomor_rekening])</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="keterangan">Keterangan Pembayaran</label>
                                <textarea class="form-control" name="keterangan" rows="3" placeholder="Isi dengan keterangan pembayaran seperti nama bank, nama pengirim, dll" readonly><?= $bayar['keterangan']; ?></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="tanggal_bayar">Tanggal Pembayaran</label>
                                <input type="date" name="tanggal_bayar" class="form-control" readonly value="<?= $bayar['tanggal_bayar']; ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="nominal_bayar">Nominal Pembayaran</label>
                                <input type="text" name="nominal_bayar" class="form-control text-right" readonly value="<?= number_format($bayar['nominal_bayar']); ?>">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5>Data Alokasi</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tagihan</th>
                                <th>Alokasi Pembayaran</th>
                                <th> Di Alokasikan Oleh</th>
                            </tr>
                            <?php
                            $sql = "SELECT bayar_alokasi.*,biaya.deskripsi_biaya FROM bayar_alokasi,tagihan,biaya WHERE bayar_alokasi.id_tagihan=tagihan.id_tagihan AND tagihan.id_biaya=biaya.id_biaya AND bayar_alokasi.id_bayar=$id_bayar";
                            // echo $sql;
                            $query = mysqli_query($koneksi, $sql);
                            while ($bayar_alokasi = mysqli_fetch_array($query)) {
                                echo "
                                    <tr>
                                    <td>$bayar_alokasi[id_bayar_alokasi]</td>
                                    <td>$bayar_alokasi[deskripsi_biaya]</td>
                                    <td>" . number_format($bayar_alokasi['total_alokasi']) . "</td>
                                    <td>$bayar_alokasi[dialokasikan_oleh]</td>
                                    </tr>
                                    ";
                            }
                            ?>
                        </thead>
                    </table>
                </div>
            </div>

        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->