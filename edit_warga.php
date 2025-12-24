<?php
include 'koneksi.php';
$id = $_GET['id'];
$result = mysqli_query($koneksi, "SELECT * FROM user WHERE id=$id");
$data = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Data Warga</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <div class="card shadow-lg p-4">
    <h3 class="mb-4 text-center">✏ Edit Data Warga</h3>
    <form method="POST">
      <input type="hidden" name="id" value="<?= $data['id']; ?>">
      <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="nama" value="<?= $data['nama']; ?>" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>NIK</label>
        <input type="text" name="nik" value="<?= $data['nik']; ?>" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Alamat</label>
        <input type="text" name="alamat" value="<?= $data['alamat']; ?>" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Tanggal Lahir</label>
        <input type="date" name="tgl_lahir" value="<?= $data['tgl_lahir']; ?>" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Jenis Kelamin</label>
        <select name="jenis_kelamin" class="form-control" required>
          <option value="Laki-laki" <?= $data['jenis_kelamin']=="Laki-laki"?"selected":""; ?>>Laki-laki</option>
          <option value="Perempuan" <?= $data['jenis_kelamin']=="Perempuan"?"selected":""; ?>>Perempuan</option>
        </select>
      </div>
      <div class="mb-3">
        <label>Tanggal Datang</label>
        <input type="date" name="tanggal_datang" value="<?= $data['tanggal_datang']; ?>" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Keperluan</label>
        <input type="text" name="keperluan" value="<?= $data['keperluan']; ?>" class="form-control" required>
      </div>
      <button type="submit" name="update" class="btn btn-primary">Update</button>
      <a href="data_warga.php" class="btn btn-secondary">Kembali</a>
    </form>
  </div>
</div>
</body>
</html>

<?php
if (isset($_POST['update'])) {
  $id = $_POST['id'];
  $nama = $_POST['nama'];
  $nik = $_POST['nik'];
  $alamat = $_POST['alamat'];
  $tgl_lahir = $_POST['tgl_lahir'];
  $jenis_kelamin = $_POST['jenis_kelamin'];
  $tanggal_datang = $_POST['tanggal_datang'];
  $keperluan = $_POST['keperluan'];

  $query = "UPDATE user SET nama='$nama', nik='$nik', alamat='$alamat', tgl_lahir='$tgl_lahir', 
            jenis_kelamin='$jenis_kelamin', tanggal_datang='$tanggal_datang', keperluan='$keperluan' WHERE id=$id";
  if (mysqli_query($koneksi, $query)) {
    echo "<script>alert('Data berhasil diupdate!');window.location='data_warga.php';</script>";
  } else {
    echo "Error: " . mysqli_error($koneksi);
  }
}
?>
