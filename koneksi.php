<?php
$host = "localhost";
$user = "root"; // default XAMPP
$pass = "";
$db   = "dukcapil2";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>

