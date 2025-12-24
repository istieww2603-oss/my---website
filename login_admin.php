<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM admin WHERE username='$username' AND password='$password'";
    $result = mysqli_query($koneksi, $sql);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['admin'] = $username;
        header("Location: dashboard_admin.php");
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login Admin - Dukcapil</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
      background: linear-gradient(135deg, #7c4dff 0%, #448aff 100%);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      position: relative;
      overflow: hidden;
    }

    body::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: 
        radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.2) 0%, transparent 50%),
        radial-gradient(circle at 40% 40%, rgba(120, 219, 255, 0.2) 0%, transparent 50%);
      background-size: cover;
    }

    .login-container {
      width: 100%;
      max-width: 420px;
      padding: 20px;
    }

    .login-card {
      background: rgba(255, 255, 255, 0.25);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border-radius: 20px;
      border: 1px solid rgba(255, 255, 255, 0.3);
      padding: 40px 30px;
      box-shadow: 
        0 20px 40px rgba(124, 77, 255, 0.3),
        0 0 0 1px rgba(255, 255, 255, 0.1);
      color: white;
      position: relative;
      overflow: hidden;
    }

    .login-card::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
      z-index: 0;
    }

    .login-header {
      text-align: center;
      margin-bottom: 30px;
      position: relative;
      z-index: 1;
    }

    .login-icon {
      width: 80px;
      height: 80px;
      background: rgba(255, 255, 255, 0.25);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 15px;
      font-size: 32px;
      border: 2px solid rgba(255, 255, 255, 0.3);
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .login-title {
      font-size: 28px;
      font-weight: 700;
      margin-bottom: 5px;
      text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .login-subtitle {
      font-size: 14px;
      opacity: 0.9;
      font-weight: 500;
    }

    .form-group {
      margin-bottom: 20px;
      position: relative;
      z-index: 1;
    }

    .form-label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      font-size: 14px;
      text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .input-group {
      position: relative;
    }

    .form-control {
      width: 100%;
      padding: 14px 45px 14px 15px;
      background: rgba(255, 255, 255, 0.15);
      border: 2px solid rgba(255, 255, 255, 0.3);
      border-radius: 12px;
      color: white;
      font-size: 15px;
      transition: all 0.3s ease;
      font-weight: 500;
    }

    .form-control::placeholder {
      color: rgba(255, 255, 255, 0.7);
      font-weight: 400;
    }

    .form-control:focus {
      background: rgba(255, 255, 255, 0.2);
      border-color: rgba(255, 255, 255, 0.6);
      box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.15);
      outline: none;
      transform: translateY(-1px);
    }

    .input-icon {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: rgba(255, 255, 255, 0.8);
      font-size: 16px;
    }

    .form-options {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 25px;
      font-size: 14px;
      position: relative;
      z-index: 1;
    }

    .remember-me {
      display: flex;
      align-items: center;
    }

    .remember-me input {
      margin-right: 8px;
      accent-color: rgba(255, 255, 255, 0.8);
    }

    .forgot-password {
      color: rgba(255, 255, 255, 0.95);
      text-decoration: none;
      transition: all 0.3s;
      font-weight: 500;
    }

    .forgot-password:hover {
      color: white;
      text-decoration: underline;
      transform: translateY(-1px);
    }

    .btn-login {
      width: 100%;
      padding: 14px;
      background: rgba(255, 255, 255, 0.25);
      border: 2px solid rgba(255, 255, 255, 0.4);
      border-radius: 12px;
      color: white;
      font-size: 16px;
      font-weight: 700;
      cursor: pointer;
      transition: all 0.3s ease;
      position: relative;
      z-index: 1;
      letter-spacing: 0.5px;
      text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .btn-login:hover {
      background: rgba(255, 255, 255, 0.35);
      border-color: rgba(255, 255, 255, 0.6);
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    .btn-login:active {
      transform: translateY(0);
    }

    .alert-danger {
      background: rgba(255, 100, 100, 0.25);
      border: 1px solid rgba(255, 150, 150, 0.4);
      color: #ffe6e6;
      padding: 12px 15px;
      border-radius: 10px;
      margin-bottom: 20px;
      font-size: 14px;
      position: relative;
      z-index: 1;
      font-weight: 500;
      text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    /* Efek partikel background */
    .particles {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 0;
    }

    .particle {
      position: absolute;
      background: rgba(255, 255, 255, 0.15);
      border-radius: 50%;
      animation: float 15s infinite linear;
    }

    /* Animasi */
    @keyframes float {
      0% { transform: translateY(0) translateX(0) rotate(0deg); }
      25% { transform: translateY(-30px) translateX(15px) rotate(90deg); }
      50% { transform: translateY(-60px) translateX(0) rotate(180deg); }
      75% { transform: translateY(-30px) translateX(-15px) rotate(270deg); }
      100% { transform: translateY(0) translateX(0) rotate(360deg); }
    }

    /* Glow effect */
    .glow {
      position: absolute;
      width: 200px;
      height: 200px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%);
      filter: blur(20px);
      z-index: 0;
    }

    .glow-1 {
      top: 10%;
      left: 10%;
      background: radial-gradient(circle, rgba(124, 77, 255, 0.3) 0%, transparent 70%);
    }

    .glow-2 {
      bottom: 10%;
      right: 10%;
      background: radial-gradient(circle, rgba(68, 138, 255, 0.3) 0%, transparent 70%);
    }

    /* Responsive */
    @media (max-width: 480px) {
      .login-container {
        padding: 15px;
      }
      
      .login-card {
        padding: 30px 20px;
      }
      
      .login-title {
        font-size: 24px;
      }
      
      .login-icon {
        width: 70px;
        height: 70px;
        font-size: 28px;
      }
    }
  </style>
</head>
<body>
  <!-- Glow effects -->
  <div class="glow glow-1"></div>
  <div class="glow glow-2"></div>
  
  <!-- Efek partikel background -->
  <div class="particles" id="particles"></div>
  
  <div class="login-container">
    <div class="login-card">
      <div class="login-header">
        <div class="login-icon">
          <i class="fas fa-user-shield"></i>
        </div>
        <h1 class="login-title">Login Admin</h1>
        <p class="login-subtitle">Sistem Administrasi Dukcapil</p>
      </div>
      
      <?php if (!empty($error)) echo "<div class='alert alert-danger'><i class='fas fa-exclamation-circle me-2'></i>$error</div>"; ?>
      
      <form method="POST">
        <div class="form-group">
          <label class="form-label">Username</label>
          <div class="input-group">
            <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
            <div class="input-icon">
              <i class="fas fa-user"></i>
            </div>
          </div>
        </div>
        
        <div class="form-group">
          <label class="form-label">Password</label>
          <div class="input-group">
            <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
            <div class="input-icon">
              <i class="fas fa-lock"></i>
            </div>
          </div>
        </div>
        
        <div class="form-options">
          <div class="remember-me">
            <input type="checkbox" id="remember">
            <label for="remember">Remember me</label>
          </div>
          <a href="#" class="forgot-password">Forgot Password?</a>
        </div>
        
        <button type="submit" class="btn-login">LOGIN</button>
      </form>
    </div>
  </div>

  <script>
    // Efek partikel background yang lebih banyak
    document.addEventListener('DOMContentLoaded', function() {
      const particlesContainer = document.getElementById('particles');
      const particleCount = 25;
      
      for (let i = 0; i < particleCount; i++) {
        const particle = document.createElement('div');
        particle.classList.add('particle');
        
        // Ukuran acak
        const size = Math.random() * 12 + 3;
        particle.style.width = `${size}px`;
        particle.style.height = `${size}px`;
        
        // Posisi acak
        particle.style.left = `${Math.random() * 100}%`;
        particle.style.top = `${Math.random() * 100}%`;
        
        // Opacity acak
        particle.style.opacity = Math.random() * 0.4 + 0.1;
        
        // Durasi animasi acak
        const duration = Math.random() * 25 + 15;
        particle.style.animationDuration = `${duration}s`;
        
        particlesContainer.appendChild(particle);
      }
    });
  </script>
</body>
</html>