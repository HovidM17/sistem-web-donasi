<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "db_sistem_donasi_new";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>