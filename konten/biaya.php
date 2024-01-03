<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        Biaya
                    </h1>
                </div>
                <!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="#">Data Utama</a>
                        </li>
                        <li class="breadcrumb-item active">Biaya</li>
                    </ol>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->

            <div class="card">
                <div class="card-header">
                    <h5>
                        Data Biaya
                    </h5>
                </div>
                <div class="card-body">
                    <button class="btn bg-info mb-2" data-toggle="modal" data-target="#modalRecycleBin"> <i class="fas fa-recycle"></i> Recycle Bin</button>
                    <table id="example1" class="table table-hover">
                        <thead class="bg-blue">
                            <th>ID Biaya</th>
                            <th>Periode </th>
                            <th>Tingkat </th>
                            <th>Jurusan</th>
                            <th>Deksripsi Biaya</th>
                            <th>Jumlah Biaya</th>
                            <th>Tanggal Jatuh Tempo</th>
                            <th>Aksi</th>
                        </thead>
                        <?php
                        $sql = "SELECT biaya.*,periode.periode,jurusan.jurusan FROM biaya,periode,jurusan WHERE biaya.dihapus_pada IS NULL AND biaya.id_periode=periode.id_periode AND biaya.id_jurusan=jurusan.id_jurusan";
                        $query = mysqli_query($koneksi, $sql);
                        while ($kolom = mysqli_fetch_array($query)) {
                        ?>
                            <tr>
                                <td><?= $kolom['id_biaya']; ?></td>
                                <td><?= $kolom['periode']; ?></td>
                                <td><?= $kolom['tingkat']; ?></td>
                                <td><?= $kolom['jurusan']; ?></td>
                                <td><?= $kolom['deskripsi_biaya']; ?></td>
                                <td><?= number_format($kolom['jumlah_biaya']); ?></td>
                                <td><?= $kolom['tanggal_jatuh_tempo']; ?></td>
                                <td>
                                    <!-- tombol edit-->
                                    <a href="aksi/biaya.php" data-toggle="modal" data-target="#modalUbah<?= $kolom['id_biaya']; ?>">
                                        <i class="fas fa-edit"></i></a>
                                        &nbsp;| &nbsp;
                                    <!-- tombl hapus -->
                                    <a onclick="return confirm('Yakin akan menghapus data ini?')" href="aksi/biaya.php?aksi=hapus&id_biaya=<?= $kolom['id_biaya']; ?>">
                                        <i class="fas fa-trash"></i>
                                </td>
                            </tr>
                            <!-- modal ubah periode -->
                            <div class="modal fade" id="modalUbah<?= $kolom['id_biaya']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Ubah Biaya</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="aksi/biaya.php" method="post">
                                                <input type="hidden" name="aksi" value="ubah">
                                                <input type="hidden" name="id_biaya" value="<?=
                                                                                            $kolom['id_biaya']; ?>">

                                                <label for="id_periode">Periode</label>
                                                <select name="id_periode" class="form-control" required>
                                                    <option value="">-- Pilih Periode --</option>
                                                    <?php
                                                    $sql_periode = "SELECT * FROM periode WHERE id_periode IS NOT NULL";
                                                    $query_periode = mysqli_query($koneksi, $sql_periode);
                                                    while ($periode = mysqli_fetch_array($query_periode)) {
                                                        if ($kolom['id_periode'] == $periode['id_periode']) {
                                                            echo "<option value='$periode[id_periode]' selected>$periode[periode]</option>";
                                                        } else {
                                                            echo "<option value='$periode[id_periode]'>$periode[periode]</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>

                                                <label for="tingkat">Tingkat </label>
                                                <select class="form-control" name="tingkat" required>
                                                    <option value="<? $kolom['tingkat']; ?>"><? $kolom['tingkat']; ?></option>
                                                    <option value="10">10</option>
                                                    <option value="11">11</option>
                                                    <option value="12">12</option>
                                                </select>

                                                <label for="id_jurusan">Jurusan</label>
                                                <select name="id_jurusan" class="form-control" required>
                                                    <option value="">-- Pilih Jurusan --</option>
                                                    <?php
                                                    $sql_jurusan = "SELECT * FROM jurusan WHERE dihapus_pada IS NULL ORDER BY jurusan ASC";
                                                    $query_jurusan = mysqli_query($koneksi, $sql_jurusan);
                                                    while ($jurusan = mysqli_fetch_array($query_jurusan)) {
                                                        if($kolom['id_jurusan']==$jurusan['id_jurusan']){
                                                        echo "<option value='$jurusan[id_jurusan]'selected>$jurusan[jurusan]</option>";
                                                    } else {
                                                        echo "<option value='$jurusan[id_jurusan]'>$jurusan[jurusan]</option>";
                                                    }
                                                }
                                                    ?>

                                                </select>


                                                <label for="deskripsi_biaya">Deskripsi Biaya</label>
                                                <input type="text" name="deskripsi_biaya" value="<?= $kolom['deskripsi_biaya']; ?>" class="form-control" required>

                                                <label for="jumlah_biaya">Jumlah Biaya</label>
                                                <input type="number" name="jumlah_biaya" value="<?= $kolom['jumlah_biaya']; ?>" class="form-control" required>

                                                <label for="tanggal_jatuh_tempo">Tanggal Jatuh Tempo</label>
                                                <input type="date" name="tanggal_jatuh_tempo" value="<?= $kolom['tanggal_jatuh_tempo']; ?>" class="form-control" required>

                                                <br>
                                                <button type="sumbit" class="btn btn-block bg-blue"><i class="fas fa-save"></i></button>

                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn bg-blue" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        } // Akhir While 
                        ?>
                    </table>
                    <button type="button" class="btn bg-info btn-block mt-3" data-toggle="modal" data-target="#modalTambah">
                        <i class="fas fa-plus"></i>Tambah Biaya Baru</button>
                </div>
            </div>
            <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- modal tambah periode -->
<div class="modal fade" id="modalTambah" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Biaya</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="aksi/biaya.php" method="post">
                    <input type="hidden" name="aksi" value="tambah">
                    <label for="id_periode">Periode</label>
                    <select name="id_periode" class="form-control" required>
                        <option value="">-- Pilih Periode --</option>
                        <?php
                        $sql = "SELECT * FROM periode WHERE id_periode IS NOT NULL";
                        $query = mysqli_query($koneksi, $sql);
                        while ($periode = mysqli_fetch_array($query)) {
                            echo "<option value='$periode[id_periode]'>$periode[periode]</option>";
                        }
                        ?>
                    </select>

                    <label for="tingkat">Tingkat </label>
                    <select class="form-control" name="tingkat" required>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>

                    </select>

                    <label for="id_jurusan">Jurusan</label>
                    <select name="id_jurusan" class="form-control" required>
                        <option value="">-- Pilih Jurusan --</option>
                        <?php
                        $sql_jurusan = "SELECT * FROM jurusan WHERE dihapus_pada IS NULL ORDER BY jurusan ASC";
                        $query_jurusan = mysqli_query($koneksi, $sql_jurusan);
                        while ($jurusan = mysqli_fetch_array($query_jurusan)) {
                            echo "<option value='$jurusan[id_jurusan]'>$jurusan[jurusan]</option>";
                        }
                        ?>

                    </select>
                    <label for="deskripsi_biaya">Deskripsi Biaya </label>
                    <input type="text" name="deskripsi_biaya" class="form-control" required>

                    <label for="jumlah_biaya"> Jumlah Biaya </label>
                    <input type="number" name="jumlah_biaya" class="form-control" required>

                    <label for="tanggal_jatuh_tempo">Tanggal Jatuh Tempo </label>
                    <input type="date" name="tanggal_jatuh_tempo" class="form-control" required>

                    <br>
                    <button type="sumbit" class="btn btn-block bg-blue"><i class="fas fa-save"></i></button>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-blue" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Recycle Bin -->
<div class="modal fade" id="modalRecycleBin" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Data Penghapusan Sementara</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-hover">
                    <thead class="bg-blue">
                        <th>ID Biaya</th>
                        <th>Biaya </th>
                        <th>Di Hapus Pada</th>
                        <th>Aksi</th>
                    </thead>
                    <?php
                    $sql = "SELECT * FROM biaya WHERE dihapus_pada IS NOT NULL";
                    $query = mysqli_query($koneksi, $sql);
                    while ($kolom = mysqli_fetch_array($query)) {
                    ?>
                        <tr>
                            <td><?= $kolom['id_biaya']; ?></td>
                            <td><?= $kolom['jumlah_biaya']; ?></td>
                            <td><?= $kolom['dihapus_pada']; ?></td>
                            <td>
                                <a onclick="return confirm('Yakin akan Mengembalikan Data Ini?')" href="aksi/biaya.php?aksi=restore&id_biaya=<?= $kolom['id_biaya']; ?>">
                                    <i class="fas fa-trash-restore"></i>

                                    <a onclick="return confirm('Yakin akan menghapus data ini secara permanent ?')" href="aksi/biaya.php?aksi=hapus-permanen&id_biaya=<?= $kolom['id_biaya']; ?>">
                                        <i class="fas fa-eraser"></i>
                            </td>
                        </tr>
                    <?php
                    } // Akhir While 
                    ?>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-blue" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>