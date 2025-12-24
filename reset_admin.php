<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "dukcapil2";

$conn = mysqli_connect($host, $user, $pass, $db);

// Reset admin
$new_password = password_hash('admin123', PASSWORD_DEFAULT);
$query = "UPDATE admin SET username='admin', nama_admin='Admin Dukcapil', password='$new_password' WHERE id_admin=1";

if (mysqli_query($conn, $query)) {
    echo "Admin berhasil direset!<br>";
    echo "Username: admin<br>";
    echo "Password: admin123<br>";
    echo "<a href='login_admin.php'>Login Sekarang</a>";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>