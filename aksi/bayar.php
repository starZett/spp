<?php
session_start();
include "../koneksi.php";
include "../function.php";

if($_POST){
    if($_POST['aksi']=='tambah'){ // Untuk Manajemen
        $id_siswa=$_POST['id_siswa'];
        $id_bayar_metode=$_POST['id_bayar_metode'];
        $keterangan=$_POST['keterangan'];
        $status_verifikasi="Belum Verifikasi";
        $nominal_bayar=$_POST['nominal_bayar'];
        $tanggal_bayar=$_POST['tanggal_bayar'];
        // Perintah Untuk Upload File
        $date= date('Y_m_d_H_i_s');
        $date= str_replace(".","", $date);
        $nama_file_bukti=$date."_".$_FILES['bukti']['name'];
        $posisi_file_bukti=$_FILES['bukti']['tmp_name'];
        $folder_file_bukti="../file/bukti_bayar/";
        // Penempatan File Ke Folder Bukti
        move_uploaded_file($posisi_file_bukti,$folder_file_bukti.$nama_file_bukti);
        $bukti=$nama_file_bukti;

        $sql="INSERT INTO bayar (id_bayar,id_siswa,id_bayar_metode,keterangan,bukti,status_verifikasi,nominal_bayar,tanggal_bayar,dibuat_pada,diubah_pada,dihapus_pada) VALUES(DEFAULT,'$id_siswa','$id_bayar_metode','$keterangan','$bukti','$status_verifikasi','$nominal_bayar','$tanggal_bayar',DEFAULT,DEFAULT,DEFAULT)";

        mysqli_query($koneksi,$sql);
        notifikasi($koneksi);

        // perintah alokasi pembayaran otomatis
        $sql_cari_id_bayar="SELECT id_bayar FROM bayar WHERE id_siswa=$id_siswa ORDER BY id_bayar DESC LIMIT 1";
        $query_cari_id_bayar=mysqli_query($koneksi,$sql_cari_id_bayar);
        $bayar=mysqli_fetch_array($query_cari_id_bayar);
        $id_bayar=$bayar['id_bayar'];
        // echo $id_bayar;
        $sql_tagihan="SELECT tagihan.*,biaya.deskripsi_biaya,biaya.jumlah_biaya,biaya.tanggal_jatuh_tempo FROM tagihan,biaya WHERE tagihan.id_biaya=biaya.id_biaya AND tagihan.total_terbayar<(biaya.jumlah_biaya-tagihan.potongan) AND tagihan.id_siswa=$id_siswa ORDER BY biaya.tanggal_jatuh_tempo ASC";
        // echo $sql_tagihan;
        $query_tagihan=mysqli_query($koneksi,$sql_tagihan);
        $alokasi_dana=$nominal_bayar;
        while($tagihan=mysqli_fetch_array($query_tagihan)){
            $id_tagihan=$tagihan['id_tagihan'];
            $jumlah_biaya=$tagihan['jumlah_biaya'];
            $potongan=$tagihan['potongan'];
            $total_terbayar=$tagihan['total_terbayar'];
        if($alokasi_dana>($jumlah_biaya-$total_terbayar-$potongan)){
            $dibayarkan=$jumlah_biaya-$total_terbayar-$potongan;
        } else {
            $dibayarkan=$alokasi_dana;
        }
        // INSERT KE TABEL BAYAR_ALOKASI
        $sql_alokasi="INSERT INTO bayar_alokasi(id_bayar_alokasi,id_bayar,id_tagihan,total_alokasi,dialokasikan_oleh,dibuat_pada,diubah_pada,dihapus_pada) VALUES(DEFAULT,$id_bayar,$id_tagihan,$dibayarkan,'Otomatis Oleh Sistem',DEFAULT,DEFAULT,DEFAULT)";
        mysqli_query($koneksi,$sql_alokasi);
        // UPDATE TAGIHAN
        $sql_update_tagihan="UPDATE tagihan SET total_terbayar=total_terbayar+$dibayarkan WHERE id_tagihan=$id_tagihan";
        mysqli_query($koneksi,$sql_update_tagihan);

        $alokasi_dana=$alokasi_dana-$dibayarkan;
        // echo "<br>Dibayarkan Untuk ".$tagihan ['deskripsi_biaya']." Sebesar ".number_format($dibayarkan);
        if($alokasi_dana<=0){
            break;
        }
        }
        // UBAH STATUS VERIFIKASI PEMBAYARAN (JIKA YG MEMBAYAR ADALAH PETUGAS MAKA STATUS SUDAH VERIFIKASI NAMUN JIKA YANG MEMBAYAR ADALAH SISWA/ORTU SISWA MAKA STATUS BELUM VERIFIKASI)
        $sql_ver="UPDATE bayar SET status_verifikasi='Sudah Verifikasi' WHERE id_bayar=$id_bayar";
        mysqli_query($koneksi,$sql_ver);

        header('location:../index.php?p=bayar');
    }

    else if($_POST['aksi']=='konfirmasi-bayar'){ // Konfirm Bayar
        $id_bayar=$_POST['id_bayar'];
        $id_siswa=$_POST['id_siswa'];
        $nominal_bayar=str_replace(",","",$_POST['nominal_bayar']);

        $sql_tagihan="SELECT tagihan.*,biaya.deskripsi_biaya,biaya.jumlah_biaya,biaya.tanggal_jatuh_tempo FROM tagihan,biaya WHERE tagihan.id_biaya=biaya.id_biaya AND tagihan.total_terbayar<(biaya.jumlah_biaya-tagihan.potongan) AND tagihan.id_siswa=$id_siswa ORDER BY biaya.tanggal_jatuh_tempo ASC";
        // echo $sql_tagihan;
        $query_tagihan=mysqli_query($koneksi,$sql_tagihan);
        $alokasi_dana=$nominal_bayar;
        while($tagihan=mysqli_fetch_array($query_tagihan)){
            $id_tagihan=$tagihan['id_tagihan'];
            $jumlah_biaya=$tagihan['jumlah_biaya'];
            $potongan=$tagihan['potongan'];
            $total_terbayar=$tagihan['total_terbayar'];
        if($alokasi_dana>($jumlah_biaya-$total_terbayar-$potongan)){
            $dibayarkan=$jumlah_biaya-$total_terbayar-$potongan;
        } else {
            $dibayarkan=$alokasi_dana;
        }
        // INSERT KE TABEL BAYAR_ALOKASI
        $sql_alokasi="INSERT INTO bayar_alokasi(id_bayar_alokasi,id_bayar,id_tagihan,total_alokasi,dialokasikan_oleh,dibuat_pada,diubah_pada,dihapus_pada) VALUES(DEFAULT,$id_bayar,$id_tagihan,$dibayarkan,'Otomatis Oleh Sistem',DEFAULT,DEFAULT,DEFAULT)";
        mysqli_query($koneksi,$sql_alokasi);
        // UPDATE TAGIHAN
        $sql_update_tagihan="UPDATE tagihan SET total_terbayar=total_terbayar+$dibayarkan WHERE id_tagihan=$id_tagihan";
        mysqli_query($koneksi,$sql_update_tagihan);

        $alokasi_dana=$alokasi_dana-$dibayarkan;
        // echo "<br>Dibayarkan Untuk ".$tagihan ['deskripsi_biaya']." Sebesar ".number_format($dibayarkan);
        if($alokasi_dana<=0){
            break;
        }
        }
        // UBAH STATUS VERIFIKASI PEMBAYARAN (JIKA YG MEMBAYAR ADALAH PETUGAS MAKA STATUS SUDAH VERIFIKASI NAMUN JIKA YANG MEMBAYAR ADALAH SISWA/ORTU SISWA MAKA STATUS BELUM VERIFIKASI)
        $sql_ver="UPDATE bayar SET status_verifikasi='Sudah Verifikasi' WHERE id_bayar=$id_bayar";
        mysqli_query($koneksi,$sql_ver);
        notifikasi($koneksi);

        header('location:../index.php?p=bayar');
        // echo "<br>ID Siswa : ".$id_siswa;
        // echo "<br>ID Bayar : ".$id_bayar;
    }

    else if($_POST['aksi']=='tambah-siswa'){ // Untuk Siswa
        $id_siswa=$_POST['id_siswa'];
        $id_bayar_metode=$_POST['id_bayar_metode'];
        $keterangan=$_POST['keterangan'];
        $status_verifikasi="Belum Verifikasi";
        $nominal_bayar=$_POST['nominal_bayar'];
        $tanggal_bayar=$_POST['tanggal_bayar'];
        // Perintah Untuk Upload File
        $date= date('Y_m_d_H_i_s');
        $date= str_replace(".","", $date);
        $nama_file_bukti=$date."_".$_FILES['bukti']['name'];
        $posisi_file_bukti=$_FILES['bukti']['tmp_name'];
        $folder_file_bukti="../file/bukti_bayar/";
        // Penempatan File Ke Folder Bukti
        move_uploaded_file($posisi_file_bukti,$folder_file_bukti.$nama_file_bukti);
        $bukti=$nama_file_bukti;

        $sql="INSERT INTO bayar (id_bayar,id_siswa,id_bayar_metode,keterangan,bukti,status_verifikasi,nominal_bayar,tanggal_bayar,dibuat_pada,diubah_pada,dihapus_pada) VALUES(DEFAULT,'$id_siswa','$id_bayar_metode','$keterangan','$bukti','$status_verifikasi','$nominal_bayar','$tanggal_bayar',DEFAULT,DEFAULT,DEFAULT)";

        mysqli_query($koneksi,$sql);
        notifikasi($koneksi);

        header("location:../index.php?p=riwayat");
    }
    else if($_POST['aksi']=='ubah'){
        $id_bayar=$_POST['id_bayar'];
        $id_siswa=$_POST['id_siswa'];
        $id_bayar_metode=$_POST['id_bayar_metode'];
        $keterangan=$_POST['keterangan'];
        $bukti=$_POST['bukti'];
        $status_verifikasi=$_POST['status_verifikasi'];
        $nominal_bayar=$_POST['nominal_bayar'];

        $sql="UPDATE bayar SET id_siswa='$id_siswa', id_bayar_metode='$id_bayar_metode', keterangan='$keterangan', bukti='$bukti', status_verifikasi='$status_verifikasi', nominal_bayar='$nominal_bayar' WHERE id_bayar=$id_bayar";

        mysqli_query($koneksi,$sql);
        notifikasi($koneksi);
        header('location:../index.php?p=bayar');
    }
}

if($_GET){
    if($_GET['aksi']=='hapus'){
        $id_bayar=$_GET['id_bayar'];
        // $sql="DELETE FROM siswa WHERE id_periode=$id_periode"; // Hard Delete
        $sql="UPDATE bayar SET dihapus_pada=now() WHERE id_bayar=$id_bayar"; // Soft Delete

        mysqli_query($koneksi,$sql);

        $sql2="UPDATE bayar_alokasi SET dihapus_pada=now() WHERE id_bayar=$id_bayar";
        mysqli_query($koneksi,$sql2);

        // Update Saldo Tagihan
        $sql3="SELECT * FROM bayar_alokasi WHERE id_bayar=$id_bayar";
        $query3=mysqli_query($koneksi,$sql3);
        while($row3=mysqli_fetch_array($query3)){
            $id_tagihan=$row3['id_tagihan'];
            $total_alokasi=$row3['total_alokasi'];
            $sql4="UPDATE tagihan SET total_terbayar=total_terbayar-$total_alokasi WHERE id_tagihan=$id_tagihan";
            mysqli_query($koneksi,$sql4);
        }
        notifikasi($koneksi);
        header('location:../index.php?p=bayar');
    }
    else if ($_GET['aksi']=='restore'){
        $id_bayar=$_GET['id_bayar'];
        $sql="UPDATE bayar SET dihapus_pada=NULL WHERE id_bayar=$id_bayar";
        mysqli_query($koneksi,$sql);
        notifikasi($koneksi);
        header('location:../index.php?p=bayar');
    }
    else if ($_GET['aksi']=='hapus-permanen'){
        $id_bayar=$_GET['id_bayar'];
        $sql="DELETE FROM bayar WHERE id_bayar=$id_bayar"; // Hard Delete
        mysqli_query($koneksi,$sql);
        notifikasi($koneksi);
        header('location:../index.php?p=bayar');
    }

}
