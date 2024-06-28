<?php
// Konfigurasi database
$host = "localhost";
$username = "root";
$password = "";
$database = "pweb_proj";

// Koneksi ke database
$conn = new mysqli($host, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil email dari parameter GET
$email = isset($_GET['email']) ? $_GET['email'] : '';

if (empty($email)) {
    die("Email is required");
}

// Prepared statement untuk mencegah SQL injection
$stmt = $conn->prepare("SELECT profil FROM user WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $imageData = $row['profil'];
    
    // Jika data gambar tersedia
    if ($imageData) {
        // Atur header untuk mengirim gambar
        header("Content-Type: image/jpeg");
        echo $imageData;
    } else {
        echo "No image found for this user";
    }
} else {
    echo "User not found";
}

$stmt->close();
$conn->close();
?>