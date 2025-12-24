<?php
include 'koneksi.php';
$result = mysqli_query($koneksi, "SELECT * FROM user ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Warga</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color:  #9b87f5;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .card {
      border-radius: 12px;
      border: none;
      background: #ffffff;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }
    .table th {
      background-color: #6f42c1;
      color: white;
      border: none;
      font-weight: 500;
      padding: 15px 12px;
    }
    .table td {
      border-color: #f0f0f0;
      padding: 12px 10px;
      vertical-align: middle;
    }
    .btn-edit {
      background-color: #e9d8fd;
      border: 1px solid #d6bcfa;
      color: #6b46c1;
      font-weight: 500;
      transition: all 0.3s ease;
    }
    .btn-edit:hover {
      background-color: #d6bcfa;
      border-color: #b794f4;
      color: #553c9a;
      transform: translateY(-1px);
    }
    .btn-delete {
      background-color: #fed7d7;
      border: 1px solid #feb2b2;
      color: #cb408dff;
      font-weight: 500;
      transition: all 0.3s ease;
    }
    .btn-delete:hover {
      background-color: #feb2b2;
      border-color: #fc8181;
      color: #be4a76ff;
      transform: translateY(-1px);
    }
    .btn-success {
      background-color: #9f7aea;
      border: 1px solid #9f7aea;
      color: white;
      font-weight: 500;
      transition: all 0.3s ease;
    }
    .btn-success:hover {
      background-color: #805ad5;
      border-color: #805ad5;
      transform: translateY(-1px);
    }
    .btn-secondary {
      background-color: #8f8592ff;
      border: 1px solid #a0aec0;
      color: white;
      font-weight: 500;
      transition: all 0.3s ease;
    }
    .btn-secondary:hover {
      background-color: #718096;
      border-color: #718096;
      transform: translateY(-1px);
    }
    .table-hover tbody tr:hover {
      background-color: #f7fafc;
    }
    h3 {
      color: #6f42c1;
      font-weight: 600;
    }
    .table-responsive {
      border-radius: 8px;
      overflow: hidden;
    }
  </style>
</head>
<body>
<div class="container mt-4">
  <div class="card shadow-sm p-4">
    <h3 class="mb-4 text-center">📋 Data Warga</h3>
    <div class="mb-3 d-flex justify-content-between">
      <a href="dashboard_admin.php" class="btn btn-secondary">⬅ Kembali</a>
      <a href="tambah_warga.php" class="btn btn-success">+ Tambah Warga</a>
    </div>
    <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>NIK</th>
            <th>Alamat</th>
            <th>Tanggal Lahir</th>
            <th>Tanggal Datang</th>
            <th>Keperluan</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = mysqli_fetch_assoc($result)) { ?>
          <tr>
            <td><?= $row['id']; ?></td>
            <td><?= $row['nama']; ?></td>
            <td><?= $row['nik']; ?></td>
            <td><?= $row['alamat']; ?></td>
            <td>
              <?php 
              if (!empty($row['tgl_lahir']) && $row['tgl_lahir'] != '0000-00-00') {
                  echo date('d/m/Y', strtotime($row['tgl_lahir']));
              } else {
                  echo '-';
              }
              ?>
            </td>
            <td><?= $row['tanggal_datang']; ?></td>
            <td><?= $row['keperluan']; ?></td>
            <td>
              <a href="edit_warga.php?id=<?= $row['id']; ?>" class="btn btn-edit btn-sm">Edit</a>
              <a href="hapus_warga.php?id=<?= $row['id']; ?>" class="btn btn-delete btn-sm"
                 onclick="return confirm('Yakin hapus data ini?')">Hapus</a>
            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>