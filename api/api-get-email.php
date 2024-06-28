    <?php
    $server = "localhost";
    $user = "root";
    $pass = "";
    $database = "pweb_proj";
    $conn = mysqli_connect($server, $user, $pass, $database);

    if (mysqli_connect_errno()) {
        die("Koneksi gagal: " . mysqli_connect_error());
    }

    // Set header response berupa JSON
    header('Content-Type: application/json');

    // Ambil email dari parameter GET request
    $email = $_GET['email'] ?? '';

    if (empty($email)) {
        // Jika email kosong, kirim respons error
        echo json_encode(array('status' => 'error', 'message' => 'Email tidak boleh kosong'));
        exit;
    }

    try {
        // Query untuk mendapatkan data pengguna berdasarkan email
        $query = "SELECT fullname, email, password FROM user WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Ambil data pengguna
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            echo json_encode(array('status' => 'success', 'data' => $user));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Pengguna tidak ditemukan'));
        }
    } catch (Exception $e) {
        // Tangani kesalahan koneksi atau query
        echo json_encode(array('status' => 'error', 'message' => 'Koneksi database gagal: ' . $e->getMessage()));
    }

    // Tutup koneksi database
    $stmt->close();
    $conn->close();
    ?>
