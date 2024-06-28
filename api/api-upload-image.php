<?php
header('Content-Type: application/json');

// Pengaturan koneksi database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pweb_proj";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Koneksi database gagal: ' . $conn->connect_error]));
}

// Menambahkan debugging untuk memeriksa variabel $_FILES dan $_POST
file_put_contents('debug_log.txt', print_r($_FILES, true));
file_put_contents('debug_log.txt', print_r($_POST, true), FILE_APPEND);

// Memeriksa apakah ada file yang diunggah
if (isset($_FILES['image']) && isset($_POST['email'])) {
    $email = $_POST['email'];
    $file = $_FILES['image'];
    $uploadDir = 'uploads/';
    $uploadFile = $uploadDir . basename($file['name']);
    
    // Memindahkan file yang diunggah ke direktori yang diinginkan
    if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
        // Memperbarui database dengan path gambar yang baru
        $imagePath = $uploadFile;
        $stmt = $conn->prepare("UPDATE `user` SET `profil` = ? WHERE `email` = ?");
        $stmt->bind_param('ss', $imagePath, $email);
        
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Gambar berhasil diunggah dan profil pengguna diperbarui', 'imagePath' => $imagePath]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui profil pengguna: ' . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal memindahkan file yang diunggah']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Permintaan tidak valid']);
}

$conn->close();
?>
