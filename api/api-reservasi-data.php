<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);

// Tangkap semua output yang mungkin terjadi
ob_start();

// Konfigurasi database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pweb_proj";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Query untuk mengambil data reservasi
$sql = "SELECT r.id_reservasi, r.fullname, r.noHP, r.id_room, r.tgl_checkin, r.tgl_checkout, rm.kategori, rm.gambar, rm.harga
        FROM reservasi r
        JOIN room rm ON r.id_room = rm.id_room";
$result = $conn->query($sql);

$reservasi = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $reservasi[] = [
            'id_reservasi' => $row['id_reservasi'],
            'fullname' => $row['fullname'],
            'noHP' => $row['noHP'],
            'id_room' => $row['id_room'],
            'tgl_checkin' => $row['tgl_checkin'],
            'tgl_checkout' => $row['tgl_checkout'],
            'kategori' => $row['kategori'],
            'gambar' => $row['gambar'],
            'harga' => $row['harga']
        ];
    }
}

// Bersihkan output buffer
$output = ob_get_clean();

// Jika ada output, log-kan dan jangan kirimkan ke klien
if (!empty($output)) {
    error_log("Unexpected output: " . $output);
}

// Mengirim respons JSON
echo json_encode(["reservasi" => $reservasi]);

// Menutup koneksi
$conn->close();
?>