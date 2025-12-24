<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login_admin.php");
    exit;
}

$host = "localhost";
$user = "root";
$pass = "";
$db   = "dukcapil2";

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Ambil data warga untuk dicetak
$query = "SELECT * FROM user ORDER BY nama";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Print Data Warga - Dukcapil</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --lilac: #9b87f5;
      --lilac-dark: #8a77e0;
    }

    body {
      background-color: #f8f9fa;
      margin-left: 260px;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      width: 260px;
      height: 100%;
      background: var(--lilac);
      color: white;
      padding: 25px 20px;
      box-shadow: 2px 0 15px rgba(0,0,0,0.1);
    }

    .profile {
      text-align: center;
      margin-bottom: 30px;
      padding-bottom: 20px;
      border-bottom: 1px solid rgba(255,255,255,0.3);
    }

    .profile img {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      border: 3px solid white;
      object-fit: cover;
    }

    .sidebar a {
      display: flex;
      align-items: center;
      gap: 12px;
      color: white;
      text-decoration: none;
      margin: 10px 0;
      padding: 12px 15px;
      border-radius: 10px;
      transition: all 0.3s ease;
    }

    .sidebar a:hover {
      background: rgba(255,255,255,0.2);
    }

    .sidebar a.active {
      background: rgba(255,255,255,0.25);
    }

    .content {
      padding: 30px;
    }

    .print-header {
      background: white;
      padding: 25px;
      border-radius: 15px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.08);
      margin-bottom: 25px;
      text-align: center;
      border-left: 5px solid var(--lilac);
    }

    .print-controls {
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 3px 10px rgba(0,0,0,0.08);
      margin-bottom: 25px;
      display: flex;
      gap: 15px;
      align-items: center;
      flex-wrap: wrap;
    }

    .btn-print {
      background: var(--lilac);
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .btn-print:hover {
      background: var(--lilac-dark);
      transform: translateY(-2px);
    }

    .table-container {
      background: white;
      border-radius: 15px;
      padding: 25px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.08);
      margin-bottom: 30px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th {
      background: var(--lilac);
      color: white;
      padding: 12px;
      text-align: left;
    }

    td {
      padding: 12px;
      border-bottom: 1px solid #eee;
    }

    tr:hover {
      background: #f8f9fa;
    }

    @media print {
      body {
        margin-left: 0 !important;
      }
      .sidebar, .print-controls {
        display: none !important;
      }
      .content {
        padding: 0 !important;
      }
      .print-header {
        box-shadow: none !important;
        border: 1px solid #000 !important;
      }
      table {
        border: 1px solid #000 !important;
      }
      th, td {
        border: 1px solid #000 !important;
      }
    }
  </style>
</head>
<body>

  <!-- SIDEBAR -->
  <div class="sidebar">
    <div class="profile">
      <img src="undraw4.png" alt="Admin Profile">
      <h4>Admin Dukcapil</h4>
      <p>admin@dukcapil.com</p>
    </div>
    
    <nav>
      <a href="dashboard_admin.php">
        <i class="fas fa-home"></i>
        <span>Dashboard</span>
      </a>
      <a href="data_warga.php">
        <i class="fas fa-users"></i>
        <span>Data Warga</span>
      </a>
      <a href="print.php" class="active">
        <i class="fas fa-print"></i>
        <span>Print Data</span>
      </a>
      <a href="account.php">
        <i class="fas fa-cog"></i>
        <span>My Account</span>
      </a>
      <a href="logout.php">
        <i class="fas fa-sign-out-alt"></i>
        <span>Logout</span>
      </a>
    </nav>
  </div>

  <!-- KONTEN UTAMA -->
  <div class="content">
    
    <!-- Header -->
    <div class="print-header">
      <h2><i class="fas fa-print"></i> Print Data Warga</h2>
      <p class="mb-0">Cetak data warga dalam format yang rapi dan profesional</p>
    </div>

    <!-- Kontrol Print -->
    <div class="print-controls">
      <button onclick="window.print()" class="btn-print">
        <i class="fas fa-print"></i> Print Sekarang
      </button>
      <button onclick="exportToPDF()" class="btn-print">
        <i class="fas fa-file-pdf"></i> Export PDF
      </button>
      <button onclick="exportToExcel()" class="btn-print">
        <i class="fas fa-file-excel"></i> Export Excel
      </button>
      <span class="text-muted ms-auto">
        Total Data: <strong><?php echo mysqli_num_rows($result); ?></strong> warga
      </span>
    </div>

    <!-- Tabel Data -->
    <div class="table-container">
      <table>
        <thead>
          <tr>
            <th>No</th>
            <th>Nama</th>
            <th>NIK</th>
            <th>Alamat</th>
            <th>Tanggal Lahir</th>
            <th>Tanggal Datang</th>
            <th>Keperluan</th>
          </tr>
        </thead>
        <tbody>
          <?php if(mysqli_num_rows($result) > 0): ?>
            <?php $no = 1; ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
              <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo htmlspecialchars($row['nama']); ?></td>
                <td><?php echo htmlspecialchars($row['nik']); ?></td>
                <td><?php echo htmlspecialchars($row['alamat']); ?></td>
                <td>
                  <?php 
                  // CEK TANGGAL LAHIR - FIXED VERSION
                  if (!empty($row['tgl_lahir']) && $row['tgl_lahir'] != '0000-00-00') {
                      echo date('d/m/Y', strtotime($row['tgl_lahir']));
                  } else {
                      echo '-';
                  }
                  ?>
                </td>
                <td>
                  <?php 
                  // CEK TANGGAL DATANG - FIXED VERSION  
                  if (!empty($row['tanggal_datang']) && $row['tanggal_datang'] != '0000-00-00') {
                      echo date('d/m/Y', strtotime($row['tanggal_datang']));
                  } else {
                      echo '-';
                  }
                  ?>
                </td>
                <td><?php echo htmlspecialchars($row['keperluan']); ?></td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="7" class="text-center py-4">Tidak ada data warga</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Informasi Cetak -->
    <div class="print-header">
      <small class="text-muted">
        <i class="fas fa-info-circle"></i> 
        Dokumen dicetak pada: <?php echo date('d/m/Y H:i:s'); ?> 
        oleh Admin Dukcapil
      </small>
    </div>

  </div>

  <script>
    function exportToPDF() {
      alert('Fitur export PDF akan segera tersedia!');
    }

    function exportToExcel() {
      alert('Fitur export Excel akan segera tersedia!');
    }

    // Auto print jika parameter print=true
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('print') === 'true') {
      window.print();
    }
  </script>

</body>
</html>