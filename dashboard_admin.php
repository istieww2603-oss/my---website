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

// ambil data
$res_total = mysqli_query($conn, "SELECT COUNT(*) AS total FROM user");
$total_warga = mysqli_fetch_assoc($res_total)['total'];

// Query untuk menghitung total print (ganti dari artikel)
$res_print = mysqli_query($conn, "SELECT COUNT(*) AS total FROM print_data");
$total_print = mysqli_fetch_assoc($res_print)['total'];

$res_admin = mysqli_query($conn, "SELECT COUNT(*) AS total FROM admin");
$total_admin = mysqli_fetch_assoc($res_admin)['total'];

$today = date("Y-m-d");
$res_today = mysqli_query($conn, "SELECT COUNT(*) AS total FROM user WHERE tanggal_datang = '$today'");
$pengunjung_today = mysqli_fetch_assoc($res_today)['total'];

// ambil data admin
$admin_username = $_SESSION['admin'];
$res_admin_data = mysqli_query($conn, "SELECT * FROM admin WHERE username = '$admin_username'");
$admin_data = mysqli_fetch_assoc($res_admin_data);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Admin - Dukcapil</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
  <style>
    :root {
      --lilac: #9b87f5;
      --lilac-dark: #8a77e0;
      --lilac-light: #b8a8ff;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
      background-color: #f8f9fa;
      margin-left: 260px;
    }

    /* SIDEBAR LILAC */
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
      display: flex;
      flex-direction: column;
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
      margin-bottom: 12px;
    }

    .profile h4 {
      font-size: 18px;
      font-weight: 600;
      margin-bottom: 5px;
    }

    .profile p {
      font-size: 13px;
      color: rgba(255,255,255,0.9);
    }

    .sidebar-nav {
      flex: 1;
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
      font-size: 14px;
    }

    .sidebar a:hover {
      background: rgba(255,255,255,0.2);
      transform: translateX(5px);
    }

    .sidebar a.active {
      background: rgba(255,255,255,0.25);
      font-weight: 500;
    }

    .sidebar i {
      font-size: 16px;
      width: 20px;
      text-align: center;
    }

    /* MAIN CONTENT */
    .content {
      padding: 30px;
    }

    /* HEADER */
    .dashboard-header {
      background: linear-gradient(135deg, var(--lilac), var(--lilac-dark));
      color: white;
      padding: 30px;
      border-radius: 15px;
      margin-bottom: 30px;
      text-align: center;
    }

    .dashboard-header h2 {
      font-size: 28px;
      font-weight: 600;
      margin-bottom: 8px;
    }

    .dashboard-header p {
      font-size: 16px;
      opacity: 0.9;
      margin-bottom: 0;
    }

    /* STATS GRID - LEBIH BESAR DAN SIMETRIS */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 20px;
      margin-bottom: 35px;
    }

    .stat-box {
      background: white;
      border-radius: 15px;
      padding: 25px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.08);
      text-align: center;
      transition: all 0.3s ease;
      border-top: 4px solid var(--lilac);
    }

    .stat-box:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }

    .stat-box lottie-player {
      width: 70px !important;
      height: 70px !important;
      margin: 0 auto 15px auto;
    }

    .stat-box h5 {
      font-size: 28px;
      font-weight: 700;
      color: var(--lilac);
      margin-bottom: 8px;
    }

    .stat-box p {
      font-size: 14px;
      color: #666;
      margin: 0;
      line-height: 1.4;
    }

    /* MENU CARDS - LEBIH SIMETRIS */
    .menu-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 25px;
    }

    .menu-card {
      background: white;
      border-radius: 15px;
      padding: 30px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.08);
      text-align: center;
      transition: all 0.3s ease;
      border-top: 4px solid var(--lilac);
    }

    .menu-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }

    .menu-card img {
      width: 120px;
      height: 120px;
      margin-bottom: 20px;
      object-fit: contain;
    }

    .menu-card h4 {
      font-size: 20px;
      font-weight: 600;
      margin-bottom: 12px;
      color: #333;
    }

    .menu-card p {
      font-size: 14px;
      color: #666;
      margin-bottom: 20px;
      line-height: 1.5;
    }

    .btn-custom {
      padding: 10px 25px;
      border-radius: 8px;
      font-size: 14px;
      font-weight: 500;
      text-decoration: none;
      display: inline-block;
      transition: all 0.3s ease;
      border: none;
      cursor: pointer;
    }

    .btn-primary {
      background: var(--lilac);
      color: white;
    }

    .btn-primary:hover {
      background: var(--lilac-dark);
      color: white;
      transform: translateY(-2px);
    }

    .btn-success {
      background: #dc7fc2ff;
      color: white;
    }

    .btn-success:hover {
      background: #efaadcff;
      color: white;
      transform: translateY(-2px);
    }

    /* RESPONSIVE */
    @media (max-width: 1200px) {
      .stats-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (max-width: 768px) {
      body {
        margin-left: 0;
      }
      
      .sidebar {
        width: 250px;
        transform: translateX(-100%);
      }
      
      .content {
        padding: 20px;
      }
      
      .stats-grid {
        grid-template-columns: 1fr;
      }
      
      .menu-grid {
        grid-template-columns: 1fr;
      }
      
      .dashboard-header {
        padding: 25px 20px;
      }
      
      .dashboard-header h2 {
        font-size: 24px;
      }
    }
  </style>
</head>
<body>

  <!-- SIDEBAR LILAC -->
  <div class="sidebar">
    <div class="profile">
      <img src="undraw4.png" alt="Admin Profile">
      <h4>Admin Dukcapil</h4>
      <p>admin@dukcapil.com</p>
    </div>
    
    <nav class="sidebar-nav">
      <a href="dashboard_admin.php" class="active">
        <i class="fas fa-home"></i>
        <span>Dashboard</span>
      </a>
      <a href="data_warga.php">
        <i class="fas fa-users"></i>
        <span>Data Warga</span>
      </a>
      <a href="print.php">
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
    <div class="dashboard-header">
      <h2>Selamat Datang, Admin</h2>
      <p>Kelola data dan informasi Dukcapil dengan mudah</p>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
      <div class="stat-box">
        <lottie-player src="https://assets4.lottiefiles.com/packages/lf20_t24tpvcu.json" background="transparent" speed="1" loop autoplay></lottie-player>
        <h5><?php echo $total_warga; ?></h5>
        <p>Total Warga</p>
      </div>
      
      
      
      <div class="stat-box">
        <lottie-player src="https://assets9.lottiefiles.com/packages/lf20_9ftr7j9r.json" background="transparent" speed="1" loop autoplay></lottie-player>
        <h5><?php echo $total_admin; ?></h5>
        <p>Total Admin</p>
      </div>
      
      <div class="stat-box">
        <lottie-player src="https://assets10.lottiefiles.com/packages/lf20_t24tpvcu.json" background="transparent" speed="1" loop autoplay></lottie-player>
        <h5><?php echo $pengunjung_today; ?></h5>
        <p>Pengunjung Hari Ini<br><small>(<?php echo date("d-m-Y"); ?>)</small></p>
      </div>
    </div>

    <!-- Menu Grid -->
    <div class="menu-grid">
      <div class="menu-card">
        <img src="undraw.png" alt="Data Warga">
        <h4>Data Warga</h4>
        <p>Kelola data warga (Tambah, Edit, Hapus, Lihat).</p>
        <a href="data_warga.php" class="btn-custom btn-primary">Kelola Data Warga</a>
      </div>
      
      <div class="menu-card">
        <img src="undraw11.png" alt="Print">
        <h4>Print Data</h4>
        <p>Cetak dan ekspor data warga dalam format yang rapi.</p>
        <a href="print.php" class="btn-custom btn-success">Cetak Data</a>
      </div>
    </div>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>