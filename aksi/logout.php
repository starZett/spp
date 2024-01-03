<?php
// Memulai SESSION
session_start();

// menghapus SESSION
session_destroy();

header("location:../login.php");
?>