<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Disdukcapil - Pilih Akses</title>
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
      background: linear-gradient(135deg, #a789eaff, #c98cd7ff 100%);
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

    .welcome-container {
      width: 100%;
      max-width: 450px;
      padding: 20px;
    }

    .welcome-card {
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
      text-align: center;
      animation: fadeInUp 0.8s ease-out;
    }

    .welcome-card::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
      z-index: 0;
    }

    .logo-container {
      margin-bottom: 25px;
      position: relative;
      z-index: 1;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .logo {
      width: 100px;
      height: 100px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto;
    }

    .logo img {
      width: 90px;
      height: 90px;
      object-fit: contain;
      /* Logo tetap original tanpa filter */
    }

    .welcome-header {
      margin-bottom: 25px;
      position: relative;
      z-index: 1;
    }

    .welcome-title {
      font-size: 32px;
      font-weight: 700;
      margin-bottom: 8px;
      text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .welcome-subtitle {
      font-size: 16px;
      opacity: 0.9;
      font-weight: 500;
      line-height: 1.5;
    }

    .btn-access {
      width: 100%;
      padding: 14px;
      background: rgba(255, 255, 255, 0.2);
      border: 2px solid rgba(255, 255, 255, 0.3);
      border-radius: 12px;
      color: white;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      position: relative;
      z-index: 1;
      text-decoration: none;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      margin-bottom: 15px;
      text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .btn-access:hover {
      background: rgba(255, 255, 255, 0.3);
      border-color: rgba(255, 255, 255, 0.5);
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
      color: white;
      text-decoration: none;
    }

    .btn-access:active {
      transform: translateY(0);
    }

    .btn-warga {
      background: rgba(40, 167, 69, 0.25);
      border-color: rgba(40, 167, 69, 0.4);
    }

    .btn-warga:hover {
      background: rgba(40, 167, 69, 0.35);
    }

    .btn-admin {
      background: rgba(13, 110, 253, 0.25);
      border-color: rgba(13, 110, 253, 0.4);
    }

    .btn-admin:hover {
      background: rgba(13, 110, 253, 0.35);
    }

    .btn-icon {
      font-size: 18px;
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

    /* Animasi */
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes float {
      0% { transform: translateY(0) translateX(0) rotate(0deg); }
      25% { transform: translateY(-30px) translateX(15px) rotate(90deg); }
      50% { transform: translateY(-60px) translateX(0) rotate(180deg); }
      75% { transform: translateY(-30px) translateX(-15px) rotate(270deg); }
      100% { transform: translateY(0) translateX(0) rotate(360deg); }
    }

    /* Responsive */
    @media (max-width: 480px) {
      .welcome-container {
        padding: 15px;
      }
      
      .welcome-card {
        padding: 30px 20px;
      }
      
      .welcome-title {
        font-size: 28px;
      }
      
      .logo {
        width: 90px;
        height: 90px;
      }
      
      .logo img {
        width: 80px;
        height: 80px;
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
  
  <div class="welcome-container">
    <div class="welcome-card">
      <div class="logo-container">
        <div class="logo">
          <!-- Logo Garuda tetap original -->
          <img src="garuda.png" alt="Logo Garuda Indonesia">
        </div>
      </div>
      
      <div class="welcome-header">
        <h1 class="welcome-title">Welcome!!</h1>
        <p class="welcome-subtitle">Silakan pilih jenis akses</p>
      </div>
      
      <div class="access-buttons">
        <a href="registrasi.php" class="btn-access btn-warga">
          <i class="fas fa-user-plus btn-icon"></i>
          Daftar Sebagai Warga
        </a>
        
        <a href="login_admin.php" class="btn-access btn-admin">
          <i class="fas fa-user-shield btn-icon"></i>
          Masuk Sebagai Admin
        </a>
      </div>
    </div>
  </div>

  <script>
    // Efek partikel background
    document.addEventListener('DOMContentLoaded', function() {
      const particlesContainer = document.getElementById('particles');
      const particleCount = 20;
      
      for (let i = 0; i < particleCount; i++) {
        const particle = document.createElement('div');
        particle.classList.add('particle');
        
        // Ukuran acak
        const size = Math.random() * 10 + 5;
        particle.style.width = `${size}px`;
        particle.style.height = `${size}px`;
        
        // Posisi acak
        particle.style.left = `${Math.random() * 100}%`;
        particle.style.top = `${Math.random() * 100}%`;
        
        // Opacity acak
        particle.style.opacity = Math.random() * 0.4 + 0.1;
        
        // Durasi animasi acak
        const duration = Math.random() * 20 + 15;
        particle.style.animationDuration = `${duration}s`;
        
        particlesContainer.appendChild(particle);
      }
    });
  </script>
</body>
</html>