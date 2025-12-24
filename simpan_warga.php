
<?php
include 'koneksi.php';

$nik            = $_POST['nik'];
$nama           = $_POST['nama'];
$alamat         = $_POST['alamat'];
$tanggal_lahir  = $_POST['tanggal_lahir'];
$jenis_kelamin  = $_POST['jenis_kelamin'];
$tanggal_datang = $_POST['tanggal_datang'];
$keperluan      = $_POST['keperluan'];

$query = "INSERT INTO user (nik, nama, alamat, tanggal_lahir, jenis_kelamin, tanggal_datang, keperluan) 
          VALUES ('$nik', '$nama', '$alamat', '$tanggal_lahir', '$jenis_kelamin', '$tanggal_datang', '$keperluan')";

if (mysqli_query($koneksi, $query)) {
    echo "Data berhasil disimpan!";
} else {
    echo "Error: " . mysqli_error($koneksi);
}
?>
