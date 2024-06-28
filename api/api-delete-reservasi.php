<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

try {
    // Ambil parameter id_reservasi dari URL
    if (!isset($_GET['id_reservasi'])) {
        throw new Exception("id_reservasi parameter is missing");
    }
    $id_reservasi = intval($_GET['id_reservasi']);

    // Koneksi ke database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "pweb_proj";

    // Buat koneksi
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check koneksi
    if ($conn->connect_error) {
        throw new Exception("Koneksi gagal: " . $conn->connect_error);
    }

    // Prepare and bind SQL statement to select reservasi
    $sqlSelect = "SELECT id_room FROM reservasi WHERE id_reservasi = ?";
    $stmtSelect = $conn->prepare($sqlSelect);
    if (!$stmtSelect) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    $stmtSelect->bind_param("i", $id_reservasi);
    
    if (!$stmtSelect->execute()) {
        throw new Exception("Execute failed: " . $stmtSelect->error);
    }
    
    $resultSelect = $stmtSelect->get_result();
    
    if ($resultSelect->num_rows > 0) {
        $row = $resultSelect->fetch_assoc();
        $id_room = $row["id_room"];
        
        // Prepare and bind SQL statement to delete reservasi
        $sqlDelete = "DELETE FROM reservasi WHERE id_reservasi = ?";
        $stmtDelete = $conn->prepare($sqlDelete);
        if (!$stmtDelete) {
            throw new Exception("Prepare delete failed: " . $conn->error);
        }
        $stmtDelete->bind_param("i", $id_reservasi);
        
        if ($stmtDelete->execute()) {
            // Setelah berhasil menghapus, update status kamar menjadi 'available'
            $sqlUpdate = "UPDATE room SET status = 'available' WHERE id_room = ?";
            $stmtUpdate = $conn->prepare($sqlUpdate);
            if (!$stmtUpdate) {
                throw new Exception("Prepare update failed: " . $conn->error);
            }
            $stmtUpdate->bind_param("i", $id_room);
            
            if ($stmtUpdate->execute()) {
                echo json_encode(array("status" => true, "message" => "Reservasi berhasil dihapus dan status kamar diperbarui"));
            } else {
                throw new Exception("Reservasi berhasil dihapus tetapi gagal memperbarui status kamar: " . $stmtUpdate->error);
            }
        } else {
            throw new Exception("Gagal menghapus reservasi: " . $stmtDelete->error);
        }
    } else {
        echo json_encode(array("status" => false, "message" => "Reservasi tidak ditemukan"));
    }

    // Tutup statement dan koneksi
    $stmtSelect->close();
    if (isset($stmtDelete)) $stmtDelete->close();
    if (isset($stmtUpdate)) $stmtUpdate->close();
    $conn->close();

} catch (Exception $e) {
    echo json_encode(array("status" => false, "message" => "An error occurred: " . $e->getMessage()));
}
?>