<?php
// Koneksi ke database
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

// Memeriksa apakah semua data yang diperlukan ada dalam $_POST
if (isset($_POST['post_fullname'], $_POST['post_id_room'], $_POST['post_noHp'], $_POST['post_tglCheckin'], $_POST['post_tglCheckout'])) {
    // Mengambil data dari $_POST
    $fullname = $_POST['post_fullname'];
    $id_room = $_POST['post_id_room'];
    $noHP = $_POST['post_noHp'];
    $tgl_checkin = $_POST['post_tglCheckin'];
    $tgl_checkout = $_POST['post_tglCheckout'];

    // Sanitasi input
    $fullname = mysqli_real_escape_string($conn, $fullname);
    $id_room = mysqli_real_escape_string($conn, $id_room);
    $noHP = mysqli_real_escape_string($conn, $noHP);
    $tgl_checkin = date('Y-m-d', strtotime(str_replace('/', '-', $tgl_checkin)));
    $tgl_checkout = date('Y-m-d', strtotime(str_replace('/', '-', $tgl_checkout)));

    // Mulai transaksi
    $conn->begin_transaction();

    try {
        // Mengambil harga dan gambar dari tabel room
        $query = "SELECT harga, gambar FROM room WHERE id_room = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id_room);
        $stmt->execute();
        $result = $stmt->get_result();
       
        if ($result->num_rows > 0) {
            $room_data = $result->fetch_assoc();
            $harga = $room_data['harga'];
            $gambar = $room_data['gambar'];

            // Query untuk memasukkan data ke dalam tabel reservasi
            $sql = "INSERT INTO reservasi (fullname, id_room, noHP, tgl_checkin, tgl_checkout, harga, gambar) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssds", $fullname, $id_room, $noHP, $tgl_checkin, $tgl_checkout, $harga, $gambar);
            
            // Menjalankan statement
            if ($stmt->execute()) {
                $last_id = $stmt->insert_id; // Mengambil ID terakhir yang di-generate

                // Update status kamar menjadi 'booked'
                $update_sql = "UPDATE room SET status = 'booked' WHERE id_room = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("i", $id_room);
                
                if ($update_stmt->execute()) {
                    // Commit transaksi jika semua operasi berhasil
                    $conn->commit();

                    echo json_encode([
                        "status" => "success",
                        "message" => "Reservasi berhasil disimpan dengan ID: " . $last_id,
                        "id_reservasi" => $last_id,
                        "harga" => $harga,
                        "gambar" => $gambar
                    ]);
                } else {
                    throw new Exception("Gagal memperbarui status kamar");
                }
                
                $update_stmt->close();
            } else {
                throw new Exception("Gagal menyimpan reservasi");
            }
            $stmt->close();
        } else {
            throw new Exception("Room tidak ditemukan");
        }
    } catch (Exception $e) {
        // Rollback transaksi jika terjadi error
        $conn->rollback();
        echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()]);
    }
} else {
    // Jika data tidak lengkap
    echo json_encode(["status" => "error", "message" => "Data yang dikirim tidak lengkap."]);
}

// Menutup koneksi
$conn->close();

// Set header content type ke JSON
header('Content-Type: application/json');
?>