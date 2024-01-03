<?php

$server='localhost';
$user='root';
$password='';
$db='db_spp';

$koneksi=mysqli_connect($server,$user,$password,$db); //KONEKSI SECARA PROSEDURAL

$mysqli= new mysqli($server,$user,$password,$db);
$mysqli->select_db($db);
$mysqli->query("SET NAMES 'utf8'");
$database=$db;

if($koneksi){
    // echo "Koneksi Sukses";
} else {
    echo "Koneksi Gagal";
    echo "<br>".mysqli_connect_error();
}
?>