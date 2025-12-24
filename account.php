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

// PROSES UPDATE PROFILE
if (isset($_POST['update_profile'])) {
    $nama_admin = mysqli_real_escape_string($conn, $_POST['nama_admin']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    
    $admin_username = $_SESSION['admin'];
    
    $query = "UPDATE admin SET nama_admin='$nama_admin', username='$username' WHERE username='$admin_username'";
    
    if (mysqli_query($conn, $query)) {
        $success = "Profile berhasil diupdate!";
        if ($username != $admin_username) {
            $_SESSION['admin'] = $username;
        }
        header("Location: account.php");
        exit;
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}

// PROSES GANTI PASSWORD
if (isset($_POST['change_password'])) {
    $current_pass = mysqli_real_escape_string($conn, $_POST['current_password']);
    $new_pass = mysqli_real_escape_string($conn, $_POST['new_password']);
    $confirm_pass = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    
    if ($new_pass !== $confirm_pass) {
        $error_password = "Password baru tidak cocok!";
    } else {
        $admin_username = $_SESSION['admin'];
        
        $check_query = "SELECT password FROM admin WHERE username='$admin_username'";
        $result = mysqli_query($conn, $check_query);
        $admin = mysqli_fetch_assoc($result);
        
        if (password_verify($current_pass, $admin['password'])) {
            $hashed_password = password_hash($new_pass, PASSWORD_DEFAULT);
            $query = "UPDATE admin SET password='$hashed_password' WHERE username='$admin_username'";
            
            if (mysqli_query($conn, $query)) {
                $success_password = "Password berhasil diubah!";
            } else {
                $error_password = "Error: " . mysqli_error($conn);
            }
        } else {
            $error_password = "Password lama salah!";
        }
    }
}

// ambil data statistik
$res_total = mysqli_query($conn, "SELECT COUNT(*) AS total FROM user");
$total_warga = mysqli_fetch_assoc($res_total)['total'];

$res_admin = mysqli_query($conn, "SELECT COUNT(*) AS total FROM admin");
$total_admin = mysqli_fetch_assoc($res_admin)['total'];

// ambil data admin yang sedang login
$admin_username = $_SESSION['admin'];
$res_admin_data = mysqli_query($conn, "SELECT * FROM admin WHERE username = '$admin_username'");
$admin_data = mysqli_fetch_assoc($res_admin_data);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>My Account - Dukcapil</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --lilac: #9b87f5;
      --lilac-dark: #8a77e0;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
      background-color: #f8f9fc;
      margin-left: 260px;
    }

    /* SIDEBAR WARNA LILAC */
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
    
    /* CONTENT AREA - MY ACCOUNT (TETAP SAMA) */
    .content {
      padding: 30px;
    }
    
    .dashboard-header {
      margin-bottom: 30px;
    }
    
    .dashboard-header h2 {
      font-weight: bold;
      color: #6f42c1;
    }
    
    .account-profile {
      background: #fff;
      border-radius: 15px;
      padding: 30px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.08);
      margin-bottom: 30px;
    }
    
    .profile-header {
      display: flex;
      align-items: center;
      margin-bottom: 25px;
    }
    
    .profile-avatar {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      background: linear-gradient(135deg, #6f42c1 0%, #9f7aea 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 40px;
      margin-right: 25px;
    }
    
    .profile-info h2 {
      color: #6f42c1;
      margin-bottom: 5px;
    }
    
    .profile-info p {
      color: #666;
      margin: 0;
    }
    
    .profile-stats {
      display: flex;
      justify-content: space-around;
      border-top: 1px solid #eee;
      padding-top: 20px;
    }
    
    .stat {
      text-align: center;
    }
    
    .stat-value {
      font-size: 24px;
      font-weight: bold;
      color: #6f42c1;
    }
    
    .stat-label {
      font-size: 14px;
      color: #666;
    }
    
    .settings-card {
      background: #fff;
      border-radius: 15px;
      padding: 25px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.08);
      margin-bottom: 25px;
      height: 100%;
      border: none;
    }
    
    .settings-card h3 {
      color: #6f42c1;
      margin-bottom: 20px;
      padding-bottom: 10px;
      border-bottom: 1px solid #eee;
      display: flex;
      align-items: center;
      font-size: 1.2rem;
    }
    
    .settings-card h3 i {
      margin-right: 10px;
    }
    
    .settings-item {
      display: flex;
      align-items: center;
      padding: 12px 0;
      border-bottom: 1px solid #f5f5f5;
    }
    
    .settings-item:last-child {
      border-bottom: none;
    }
    
    .settings-item i {
      margin-right: 15px;
      color: #6f42c1;
      font-size: 18px;
      width: 20px;
      text-align: center;
    }
    
    .btn-outline-custom {
      background: transparent;
      color: #6f42c1;
      border: 2px solid #6f42c1;
      border-radius: 8px;
      padding: 6px 15px;
      font-size: 0.875rem;
      transition: all 0.3s ease;
    }
    
    .btn-outline-custom:hover {
      background: #6f42c1;
      color: white;
    }
    
    .btn-primary-custom {
      background: #6f42c1;
      border: 1px solid #6f42c1;
      color: white;
      font-weight: 500;
      padding: 10px 25px;
      border-radius: 8px;
      transition: all 0.3s ease;
    }
    
    .btn-primary-custom:hover {
      background: #5a32a3;
      border-color: #5a32a3;
    }
    
    .modal-content {
      border-radius: 15px;
      border: none;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    
    .modal-header {
      background: #6f42c1;
      color: white;
      border-radius: 15px 15px 0 0;
      border: none;
    }
    
    .form-control {
      border-radius: 10px;
      padding: 12px;
      border: 1px solid #e2e8f0;
    }
    
    .form-control:focus {
      border-color: #6f42c1;
      box-shadow: 0 0 0 0.2rem rgba(111, 66, 193, 0.25);
    }
    
    .alert {
      border-radius: 10px;
      border: none;
    }
    
    .level-badge {
      background: #e9d8fd;
      color: #6f42c1;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 0.8rem;
      font-weight: 600;
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
      body {
        margin-left: 0;
      }
      
      .sidebar {
        width: 260px;
        transform: translateX(-100%);
      }
      
      .content {
        padding: 20px;
      }
    }
  </style>
</head>
<body>

  <!-- SIDEBAR WARNA LILAC -->
  <div class="sidebar">
    <div class="profile">
      <img src="undraw4.png" alt="Admin Profile">
      <h4>Admin Dukcapil</h4>
      <p>admin@dukcapil.com</p>
    </div>
    
    <nav class="sidebar-nav">
      <a href="dashboard_admin.php">
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
      <a href="account.php" class="active">
        <i class="fas fa-cog"></i>
        <span>My Account</span>
      </a>
      <a href="logout.php">
        <i class="fas fa-sign-out-alt"></i>
        <span>Logout</span>
      </a>
    </nav>
  </div>

  <!-- KONTEN UTAMA MY ACCOUNT (TETAP SAMA) -->
  <div class="content">
    <div class="dashboard-header">
      <h2>My Account</h2>
      <p>Kelola profil dan pengaturan akun Anda</p>
    </div>

    <!-- Notifikasi -->
    <?php if (isset($success)): ?>
      <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    <?php if (isset($error)): ?>
      <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <?php if (isset($success_password)): ?>
      <div class="alert alert-success"><?php echo $success_password; ?></div>
    <?php endif; ?>
    <?php if (isset($error_password)): ?>
      <div class="alert alert-danger"><?php echo $error_password; ?></div>
    <?php endif; ?>

    <!-- Profile Section -->
    <div class="account-profile">
      <div class="profile-header">
        <div class="profile-avatar">
          <i class="fas fa-user"></i>
        </div>
        <div class="profile-info">
          <h2><?php echo htmlspecialchars($admin_data['nama_admin'] ?? 'Admin Dukcapil'); ?></h2>
          <p><?php echo htmlspecialchars($admin_data['username'] ?? 'admin'); ?></p>
          <span class="level-badge">Level: <?php echo htmlspecialchars($admin_data['level'] ?? 'superAdmin'); ?></span>
        </div>
      </div>
      <div class="profile-stats">
        <div class="stat">
          <div class="stat-value"><?php echo $total_warga; ?></div>
          <div class="stat-label">Total Warga</div>
        </div>
        
        <div class="stat">
          <div class="stat-value"><?php echo $total_admin; ?></div>
          <div class="stat-label">Total Admin</div>
        </div>
      </div>
    </div>

    <!-- Settings Section -->
    <div class="row">
      <div class="col-md-4">
        <div class="settings-card">
          <h3><i class="fas fa-user-cog"></i> Account</h3>
          <div class="settings-item">
            <i class="fas fa-user-edit"></i>
            <span>Edit Profile</span>
            <button class="btn btn-sm btn-outline-custom ms-auto" data-bs-toggle="modal" data-bs-target="#editProfileModal">
              Edit
            </button>
          </div>
          <div class="settings-item">
            <i class="fas fa-key"></i>
            <span>Change Password</span>
            <button class="btn btn-sm btn-outline-custom ms-auto" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
              Ganti
            </button>
          </div>
        </div>
      </div>
      
      <div class="col-md-4">
        <div class="settings-card">
          <h3><i class="fas fa-bell"></i> Notifications</h3>
          <div class="settings-item">
            <i class="fas fa-envelope"></i>
            <span>Email Notifications</span>
            <div class="form-check form-switch ms-auto">
              <input class="form-check-input" type="checkbox" checked style="transform: scale(1.2);">
            </div>
          </div>
          <div class="settings-item">
            <i class="fas fa-mobile-alt"></i>
            <span>App Notifications</span>
            <div class="form-check form-switch ms-auto">
              <input class="form-check-input" type="checkbox" checked style="transform: scale(1.2);">
            </div>
          </div>
        </div>
      </div>
      
      <div class="col-md-4">
        <div class="settings-card">
          <h3><i class="fas fa-cog"></i> More</h3>
          <div class="settings-item">
            <i class="fas fa-language"></i>
            <span>Language</span>
          </div>
          <div class="settings-item">
            <i class="fas fa-chart-bar"></i>
            <span>Stats</span>
          </div>
          <div class="settings-item">
            <i class="fas fa-share-alt"></i>
            <span>Bagikan</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Edit Profile -->
  <div class="modal fade" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Profile</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form method="POST">
            <div class="mb-3">
              <label class="form-label">Nama Admin</label>
              <input type="text" class="form-control" name="nama_admin" 
                     value="<?php echo htmlspecialchars($admin_data['nama_admin'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Username</label>
              <input type="text" class="form-control" name="username" 
                     value="<?php echo htmlspecialchars($admin_data['username'] ?? ''); ?>" required>
            </div>
            <button type="submit" name="update_profile" class="btn btn-primary-custom w-100">Update Profile</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Change Password -->
  <div class="modal fade" id="changePasswordModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Ganti Password</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form method="POST">
            <div class="mb-3">
              <label class="form-label">Password Lama</label>
              <input type="password" class="form-control" name="current_password" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Password Baru</label>
              <input type="password" class="form-control" name="new_password" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Konfirmasi Password Baru</label>
              <input type="password" class="form-control" name="confirm_password" required>
            </div>
            <button type="submit" name="change_password" class="btn btn-primary-custom w-100">Ganti Password</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    setTimeout(function() {
      const alerts = document.querySelectorAll('.alert');
      alerts.forEach(alert => {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
      });
    }, 5000);
  </script>

</body>
</html>