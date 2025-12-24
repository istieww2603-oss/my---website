<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Terima Kasih</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #d8bfd8, #e6e6fa);
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }
    .card {
      border-radius: 20px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      text-align: center;
      padding: 30px;
      animation: fadeIn 1s ease-in-out;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to   { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
  <div class="card">
    <h2>✅ Terima Kasih!</h2>
    <p>Data Anda berhasil dikirim.</p>
    <a href="index.php" class="btn btn-primary mt-3">Kembali ke Halaman Utama</a>
  </div>
</body>
</html>
