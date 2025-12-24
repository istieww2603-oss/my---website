<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Data Warga</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #9b87f5;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .card {
      border-radius: 12px;
      border: none;
      background: #ffffff;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }
    h3 {
      color: #6f42c1;
      font-weight: 600;
    }
    .form-control {
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      padding: 10px 15px;
      transition: all 0.3s ease;
    }
    .form-control:focus {
      border-color: #9f7aea;
      box-shadow: 0 0 0 0.2rem rgba(159, 122, 234, 0.25);
    }
    .form-label {
      font-weight: 500;
      color: #4a5568;
      margin-bottom: 8px;
    }
    .btn-success {
      background-color: #9f7aea;
      border: 1px solid #9f7aea;
      color: white;
      font-weight: 500;
      padding: 10px 25px;
      border-radius: 8px;
      transition: all 0.3s ease;
    }
    .btn-success:hover {
      background-color: #805ad5;
      border-color: #805ad5;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(159, 122, 234, 0.3);
    }
    .btn-secondary {
      background-color: #a0aec0;
      border: 1px solid #a0aec0;
      color: white;
      font-weight: 500;
      padding: 10px 25px;
      border-radius: 8px;
      transition: all 0.3s ease;
    }
    .btn-secondary:hover {
      background-color: #718096;
      border-color: #718096;
      transform: translateY(-2px);
    }
  </style>
</head>
<body>
<div class="container mt-4">
  <div class="card shadow-sm p-4">
    <h3 class="mb-4 text-center">➕ Tambah Data Warga</h3>
    <form method="POST" action="">
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Nama</label>
          <input type="text" name="nama" class="form-control" required>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">NIK</label>
          <input type="text" name="nik" class="form-control" required>
        </div>
      </div>
      
      <div class="mb-3">
        <label class="form-label">Alamat</label>
        <input type="text" name="alamat" class="form-control" required>
      </div>
      
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Tanggal Lahir</label>
          <input type="date" name="tgl_lahir" class="form-control" required>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Jenis Kelamin</label>
          <select name="jenis_kelamin" class="form-control" required>
            <option value="">-- Pilih --</option>
            <option value="L">Laki-laki</option>
            <option value="P">Perempuan</option>
          </select>
        </div>
      </div>
      
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Tanggal Datang</label>
          <input type="date" name="tanggal_datang" class="form-control" required>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Keperluan</label>
          <input type="text" name="keperluan" class="form-control" required>
        </div>
      </div>
      
      <div class="d-flex justify-content-between mt-4">
        <a href="data_warga.php" class="btn btn-secondary">Batal</a>
        <button type="submit" name="simpan" class="btn btn-success">Simpan Data</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
if (isset($_POST['simpan'])) {
  $nama = $_POST['nama'];
  $nik = $_POST['nik'];
  $alamat = $_POST['alamat'];
  $tgl_lahir = $_POST['tgl_lahir'];
  $jenis_kelamin = $_POST['jenis_kelamin'];
  $tanggal_datang = $_POST['tanggal_datang'];
  $keperluan = $_POST['keperluan'];

  $query = "INSERT INTO user (nama, nik, alamat, tgl_lahir, jenis_kelamin, tanggal_datang, keperluan) 
            VALUES ('$nama', '$nik', '$alamat', '$tgl_lahir', '$jenis_kelamin', '$tanggal_datang', '$keperluan')";
  if (mysqli_query($koneksi, $query)) {
    echo "<script>alert('Data berhasil ditambahkan!');window.location='data_warga.php';</script>";
  } else {
    echo "<script>alert('Error: " . mysqli_error($koneksi) . "');</script>";
  }
}
?>