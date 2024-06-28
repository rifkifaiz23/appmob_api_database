<?php
// Set header content type ke JSON
header('Content-Type: application/json');

// Konfigurasi database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pweb_proj";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Koneksi gagal: " . $conn->connect_error]));
}

// Mengambil data dari JSON body
$data = json_decode(file_get_contents('php://input'), true);

// Memeriksa apakah id_reservasi ada dalam data JSON
if (isset($data['id_reservasi'])) {
    // Mengambil id_reservasi dari JSON body
    $id_reservasi = mysqli_real_escape_string($conn, $data['id_reservasi']);

    // Query untuk mendapatkan data asli dari database
    $sql_select = "SELECT fullname, noHP, tgl_checkin, tgl_checkout FROM reservasi WHERE id_reservasi = ?";
    $stmt_select = $conn->prepare($sql_select);
    $stmt_select->bind_param("i", $id_reservasi);
    $stmt_select->execute();
    $result = $stmt_select->get_result();
    $originalData = $result->fetch_assoc();

    // Memeriksa apakah data asli ditemukan
    if ($originalData) {
        // Mengambil data dari JSON body atau menggunakan nilai asli jika tidak diubah
        $fullname = isset($data['fullname']) ? mysqli_real_escape_string($conn, $data['fullname']) : $originalData['fullname'];
        $noHP = isset($data['noHP']) ? mysqli_real_escape_string($conn, $data['noHP']) : $originalData['noHP'];
        $tgl_checkin = isset($data['tgl_checkin']) ? date('Y-m-d', strtotime(str_replace('/', '-', $data['tgl_checkin']))) : $originalData['tgl_checkin'];
        $tgl_checkout = isset($data['tgl_checkout']) ? date('Y-m-d', strtotime(str_replace('/', '-', $data['tgl_checkout']))) : $originalData['tgl_checkout'];

        // Mulai transaksi
        $conn->begin_transaction();

        try {
            // Query untuk update data reservasi
            $sql_update = "UPDATE reservasi SET fullname = ?, noHP = ?, tgl_checkin = ?, tgl_checkout = ? WHERE id_reservasi = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("ssssi", $fullname, $noHP, $tgl_checkin, $tgl_checkout, $id_reservasi);

            // Menjalankan statement update
            if ($stmt_update->execute()) {
                // Commit transaksi jika berhasil
                $conn->commit();

                echo json_encode([
                    "status" => "success",
                    "message" => "Reservasi berhasil diperbarui"
                ]);
            } else {
                throw new Exception("Gagal memperbarui reservasi");
            }

            $stmt_update->close();
        } catch (Exception $e) {
            // Rollback transaksi jika terjadi error
            $conn->rollback();
            echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Data reservasi tidak ditemukan."]);
    }
} else {
    // Jika id_reservasi tidak ada dalam data JSON
    echo json_encode(["status" => "error", "message" => "ID reservasi tidak ditemukan."]);
}

// Menutup koneksi
$conn->close();
?>