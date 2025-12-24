<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Tambah Warga</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <div class="card shadow p-4">
    <h3 class="mb-4">Form Registrasi Warga</h3>
    <form method="POST" action="simpan_warga.php">
      <div class="mb-3">
        <label>NIK</label>
        <input type="text" name="nik" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="nama" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Alamat</label>
        <textarea name="alamat" class="form-control"></textarea>
      </div>
      <div class="mb-3">
        <label>Tanggal Lahir</label>
        <input type="date" name="tanggal_lahir" class="form-control">
      </div>
      <div class="mb-3">
        <label>Jenis Kelamin</label>
        <select name="jenis_kelamin" class="form-select">
          <option value="L">Laki-Laki</option>
          <option value="P">Perempuan</option>
        </select>
      </div>
      <div class="mb-3">
        <label>Tanggal Datang</label>
        <input type="date" name="tanggal_datang" class="form-control">
      </div>
      <div class="mb-3">
        <label>Keperluan</label>
        <input type="text" name="keperluan" class="form-control">
      </div>
      <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
  </div>
</div>
</body>
</html>
