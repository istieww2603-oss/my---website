<?php
// registrasi.php (gabungan: form + proses insert + deteksi tabel/kolom + SweetAlert)
include 'koneksi.php'; // pastikan file ini meng-set $koneksi (mysqli)

// helper: cek tabel yang tersedia
$preferredTables = ['warga','user','users'];
$foundTable = null;
foreach ($preferredTables as $t) {
    $t_esc = mysqli_real_escape_string($koneksi, $t);
    $res = mysqli_query($koneksi, "SHOW TABLES LIKE '$t_esc'");
    if ($res && mysqli_num_rows($res) > 0) { $foundTable = $t; break; }
}

$alertScript = ''; // nanti berisi SweetAlert js jika ada pesan

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // jika tidak ada tabel apa pun yang cocok, langsung beri pesan
    if (!$foundTable) {
        $alertScript = "<script>Swal.fire({icon:'error',title:'Tabel tidak ditemukan',text:'Tidak ada tabel warga/user di database. Buat tabel dulu.'});</script>";
    } else {
        // ambil kolom tabel yang dipilih
        $colsRes = mysqli_query($koneksi, "SHOW COLUMNS FROM `".mysqli_real_escape_string($koneksi,$foundTable)."`");
        $cols = [];
        while ($r = mysqli_fetch_assoc($colsRes)) $cols[] = $r['Field'];

        // Peta nama form => kemungkinan nama kolom di DB
        $maps = [
            'nama' => ['nama','name'],
            'nik' => ['nik','no_nik','no_nik_ktp'],
            'alamat' => ['alamat','address'],
            'tgl_lahir' => ['tgl_lahir','tanggal_lahir','birth_date'],
            'jenis_kelamin' => ['jenis_kelamin','jk','gender'],
            'tanggal_datang' => ['tanggal_datang','tanggal_datang','date_arrival'],
            'keperluan' => ['keperluan','purpose','keperluan_datang']
        ];

        // Temukan kolom yang benar-benar ada di DB
        $colFor = [];
        foreach ($maps as $formName => $possibles) {
            foreach ($possibles as $p) {
                if (in_array($p, $cols)) { $colFor[$formName] = $p; break; }
            }
        }

        // Kumpulkan data dari $_POST sesuai kolom yang ada
        $insertCols = [];
        $insertVals = [];
        $requiredMissing = [];
        // daftar field yang kita harapkan user isi di form
        $expectedFormFields = ['nama','nik','alamat','tgl_lahir','jenis_kelamin','tanggal_datang','keperluan'];
        foreach ($expectedFormFields as $f) {
            if (isset($colFor[$f])) {
                // pastikan value tersedia
                if (!isset($_POST[$f]) || trim($_POST[$f]) === '') {
                    $requiredMissing[] = $f;
                } else {
                    $insertCols[] = "`".$colFor[$f]."`";
                    $insertVals[] = trim($_POST[$f]);
                }
            }
        }

        if (!empty($requiredMissing)) {
            $alertScript = "<script>Swal.fire({icon:'error',title:'Field wajib',text:'Field berikut wajib diisi: ".implode(', ',$requiredMissing)."'});</script>";
        } elseif (empty($insertCols)) {
            $alertScript = "<script>Swal.fire({icon:'error',title:'Kolom tidak cocok',text:'Struktur tabel tidak memiliki kolom yang cocok. Periksa struktur tabel di database.'});</script>";
        } else {
            // siapkan prepared statement dinamis
            $colsSql = implode(',', $insertCols);
            $placeholders = implode(',', array_fill(0, count($insertVals), '?'));
            $sql = "INSERT INTO `".mysqli_real_escape_string($koneksi,$foundTable)."` ($colsSql) VALUES ($placeholders)";
            $stmt = mysqli_prepare($koneksi, $sql);
            if (!$stmt) {
                $alertScript = "<script>Swal.fire({icon:'error',title:'Gagal prepare',text:'".mysqli_error($koneksi)."'});</script>";
            } else {
                // semua parameter sebagai string (s) — ok untuk tanggal juga
                $types = str_repeat('s', count($insertVals));
                // bind params butuh references
                $refs = [];
                foreach ($insertVals as $k => $v) $refs[$k] = &$insertVals[$k];
                array_unshift($refs, $types);
                array_unshift($refs, $stmt);
                // bind
                if (!call_user_func_array('mysqli_stmt_bind_param', $refs)) {
                    $alertScript = "<script>Swal.fire({icon:'error',title:'Gagal bind',text:'".mysqli_error($koneksi)."'});</script>";
                } else {
                    // execute
                    if (mysqli_stmt_execute($stmt)) {
                        $alertScript = "
                          <script>
                            Swal.fire({
                              icon: 'success',
                              title: 'Terima Kasih!',
                              text: 'Data registrasi Anda berhasil dikirim.',
                              confirmButtonColor: '#9f7aea'
                            }).then(() => { window.location = 'index.php'; });
                          </script>";
                    } else {
                        $err = mysqli_stmt_error($stmt);
                        $alertScript = "<script>Swal.fire({icon:'error',title:'Gagal menyimpan',text:".json_encode($err)."});</script>";
                    }
                }
                mysqli_stmt_close($stmt);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Registrasi Warga</title>
  <!-- Bootstrap + SweetAlert2 + Font -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    body {
      background: #e9d8fd;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Poppins', sans-serif;
      padding: 20px;
      margin: 0;
    }

    .register-wrapper {
      display: flex;
      width: 100%;
      max-width: 1000px;
      background: white;
      border-radius: 20px;
      box-shadow: 0 20px 40px rgba(0,0,0,0.1);
      overflow: hidden;
      min-height: 600px;
    }

    .illustration-side {
      flex: 1;
      background: linear-gradient(135deg, #9f7aea 0%, #6b46c1 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px;
      position: relative;
      overflow: hidden;
    }

    /* Background pattern */
    .illustration-side::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: 
        radial-gradient(circle at 20% 80%, rgba(255,255,255,0.1) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(255,255,255,0.1) 0%, transparent 50%),
        radial-gradient(circle at 40% 40%, rgba(255,255,255,0.05) 0%, transparent 50%);
      animation: floatBackground 20s ease-in-out infinite;
    }

    @keyframes floatBackground {
      0%, 100% { transform: translate(0, 0) scale(1); }
      25% { transform: translate(-10px, -10px) scale(1.02); }
      50% { transform: translate(10px, 5px) scale(1.01); }
      75% { transform: translate(-5px, 10px) scale(1.02); }
    }

    .human-illustration {
      width: 100%;
      max-width: 350px;
      height: 350px;
      position: relative;
      z-index: 2;
      animation: float 6s ease-in-out infinite;
    }

    /* SVG Human Illustration - Style Undraw */
    .human-svg {
      width: 100%;
      height: 100%;
      filter: drop-shadow(0 10px 20px rgba(0,0,0,0.1));
    }

    /* Animasi untuk bagian-bagian SVG */
    .head { animation: nod 4s ease-in-out infinite; transform-origin: center bottom; }
    .arm-right { animation: wave 3s ease-in-out infinite; transform-origin: shoulder; }
    .body { animation: breathe 5s ease-in-out infinite; }
    .document { animation: float 5s ease-in-out infinite; }

    @keyframes float {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-15px); }
    }

    @keyframes nod {
      0%, 100% { transform: rotate(0deg); }
      25% { transform: rotate(2deg); }
      75% { transform: rotate(-2deg); }
    }

    @keyframes wave {
      0%, 100% { transform: rotate(0deg); }
      25% { transform: rotate(10deg); }
      75% { transform: rotate(-5deg); }
    }

    @keyframes breathe {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.02); }
    }

    .illustration-text {
      position: absolute;
      bottom: 30px;
      left: 0;
      right: 0;
      text-align: center;
      color: white;
      z-index: 2;
    }

    .illustration-text h3 {
      font-weight: 600;
      margin-bottom: 10px;
      font-size: 1.5rem;
    }

    .illustration-text p {
      opacity: 0.9;
      font-size: 0.9rem;
    }

    .form-side {
      flex: 1;
      padding: 40px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      background: white;
    }

    .form-header {
      text-align: center;
      margin-bottom: 30px;
    }

    .form-header h1 {
      color: #6b46c1;
      font-weight: 700;
      font-size: 2rem;
      margin-bottom: 8px;
    }

    .form-header p {
      color: #666;
      font-size: 0.95rem;
      margin-bottom: 0;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-label {
      font-weight: 600;
      color: #6b46c1;
      margin-bottom: 6px;
      font-size: 0.9rem;
    }

    .form-control {
      border: 2px solid #e9ecef;
      border-radius: 10px;
      padding: 10px 14px;
      font-size: 0.9rem;
      transition: all 0.3s ease;
      background: #f8f9fc;
      color: #4a5568;
    }

    .form-control:focus {
      border-color: #9f7aea;
      box-shadow: 0 0 0 0.2rem rgba(159, 122, 234, 0.25);
      background: white;
      color: #4a5568;
    }

    .form-control::placeholder {
      color: rgba(107, 70, 193, 0.6);
    }

    .form-select {
      border: 2px solid #e9ecef;
      border-radius: 10px;
      padding: 10px 14px;
      font-size: 0.9rem;
      background: #f8f9fc;
      color: #4a5568;
    }

    .form-select:focus {
      border-color: #9f7aea;
      box-shadow: 0 0 0 0.2rem rgba(159, 122, 234, 0.25);
      background: white;
    }

    .form-select option {
      background: white;
      color: #4a5568;
    }

    .btn-register {
      background: #9f7aea;
      border: 2px solid #9f7aea;
      color: white;
      padding: 12px 30px;
      border-radius: 10px;
      font-weight: 600;
      font-size: 1rem;
      transition: all 0.3s ease;
      width: 100%;
      margin-top: 10px;
    }

    .btn-register:hover {
      background: #805ad5;
      border-color: #805ad5;
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(159, 122, 234, 0.3);
    }

    .terms-check {
      margin: 20px 0;
      text-align: center;
    }

    .terms-check .form-check-input {
      margin-right: 8px;
      transform: scale(1.1);
      border: 2px solid #9f7aea;
    }

    .terms-check .form-check-input:checked {
      background-color: #9f7aea;
      border-color: #9f7aea;
    }

    .terms-check .form-check-label {
      color: #6b46c1;
      font-size: 0.85rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .register-wrapper {
        flex-direction: column;
        max-width: 95%;
      }
      
      .illustration-side {
        padding: 30px 20px;
        min-height: 250px;
      }
      
      .human-illustration {
        max-width: 200px;
        height: 200px;
      }
      
      .form-side {
        padding: 30px 20px;
      }
    }
  </style>
</head>
<body>

<div class="register-wrapper">
  <!-- Side kiri untuk ilustrasi manusia -->
  <div class="illustration-side">
    <div class="human-illustration">
      <svg class="human-svg" viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg">
        <!-- Background circle -->
        <circle cx="200" cy="200" r="180" fill="rgba(255,255,255,0.1)" />
        
        <!-- Human figure -->
        <g class="body">
          <!-- Body -->
          <ellipse cx="200" cy="240" rx="40" ry="60" fill="white" />
          
          <!-- Head -->
          <circle class="head" cx="200" cy="140" r="35" fill="white" />
          
          <!-- Arms -->
          <path class="arm-right" d="M240 220 Q260 200 250 180" stroke="white" stroke-width="12" stroke-linecap="round" fill="none" />
          <path d="M160 220 Q140 200 150 180" stroke="white" stroke-width="12" stroke-linecap="round" fill="none" />
          
          <!-- Legs -->
          <path d="M200 300 L180 360" stroke="white" stroke-width="12" stroke-linecap="round" />
          <path d="M200 300 L220 360" stroke="white" stroke-width="12" stroke-linecap="round" />
        </g>
        
        <!-- Document in hand -->
        <g class="document">
          <rect x="250" y="160" width="60" height="80" rx="5" fill="white" />
          <line x1="260" y1="180" x2="290" y2="180" stroke="#9f7aea" stroke-width="2" />
          <line x1="260" y1="190" x2="290" y2="190" stroke="#9f7aea" stroke-width="2" />
          <line x1="260" y1="200" x2="280" y2="200" stroke="#9f7aea" stroke-width="2" />
        </g>
        
        <!-- Face details -->
        <circle cx="190" cy="135" r="3" fill="#6b46c1" />
        <circle cx="210" cy="135" r="3" fill="#6b46c1" />
        <path d="M190 155 Q200 165 210 155" stroke="#6b46c1" stroke-width="2" fill="none" />
      </svg>
    </div>
    <div class="illustration-text">
      <h3>Selamat Datang!</h3>
      <p>Daftarkan data Anda dengan mudah dan cepat</p>
    </div>
  </div>

  <!-- Side kanan untuk form -->
  <div class="form-side">
    <div class="form-header">
      <h1>REGISTER</h1>
      <p>Isi data dengan lengkap. Data akan diproses oleh petugas.</p>
    </div>

    <!-- tampilkan alert script jika ada -->
    <?php if ($alertScript) echo $alertScript; ?>

    <form method="post" novalidate>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="nama" class="form-control" placeholder="Masukkan nama lengkap" required>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="form-label">NIK</label>
            <input type="text" name="nik" class="form-control" placeholder="Masukkan NIK" required>
          </div>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Alamat</label>
        <textarea name="alamat" class="form-control" rows="2" placeholder="Masukkan alamat lengkap" required></textarea>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label class="form-label">Tanggal Lahir</label>
            <input type="date" name="tgl_lahir" class="form-control" required>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="form-label">Jenis Kelamin</label>
            <select name="jenis_kelamin" class="form-select" required>
              <option value="">-- Pilih Jenis Kelamin --</option>
              <option value="Laki-Laki">Laki-Laki</option>
              <option value="Perempuan">Perempuan</option>
            </select>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label class="form-label">Tanggal Datang</label>
            <input type="date" name="tanggal_datang" class="form-control" required>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="form-label">Keperluan</label>
            <input type="text" name="keperluan" class="form-control" placeholder="Keperluan datang" required>
          </div>
        </div>
      </div>

      <div class="terms-check">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="termsCheck" required>
          <label class="form-check-label" for="termsCheck">
            Saya menyetujui Syarat dan Ketentuan
          </label>
        </div>
      </div>

      <button type="submit" class="btn-register">KIRIM REGISTRASI</button>
    </form>
  </div>
</div>

</body>
</html>