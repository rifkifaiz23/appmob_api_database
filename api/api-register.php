<?php
$server = "localhost";
$user = "root";
$pass = "";
$database = "pweb_proj";
$koneksi = mysqli_connect($server, $user, $pass, $database);

if (mysqli_connect_errno()) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Periksa apakah data POST ada
if (!isset($_POST["post_fullname"]) || !isset($_POST["post_email"]) || !isset($_POST["post_password"])) {
    echo json_encode([
        'response' => false,
        'message' => 'Data tidak lengkap'
    ]);
    exit;
}

$fullname = $_POST["post_fullname"];
$email = $_POST["post_email"];
$password = $_POST["post_password"];

// Periksa apakah nilai tidak kosong
if (empty($fullname) || empty($email) || empty($password)) {
    echo json_encode([
        'response' => false,
        'message' => 'Data tidak boleh kosong'
    ]);
    exit;
}

// Gunakan prepared statement
$check_query = "SELECT * FROM user WHERE email = ?";
$check_stmt = mysqli_prepare($koneksi, $check_query);
mysqli_stmt_bind_param($check_stmt, "s", $email);
mysqli_stmt_execute($check_stmt);
$check_result = mysqli_stmt_get_result($check_stmt);

if (mysqli_num_rows($check_result) > 0) {
    echo json_encode([
        'response' => false,
        'message' => 'Email sudah terdaftar'
    ]);
} else {
    $insert_query = "INSERT INTO user (fullname, email, password) VALUES (?, ?, ?)";
    $insert_stmt = mysqli_prepare($koneksi, $insert_query);
    mysqli_stmt_bind_param($insert_stmt, "sss", $fullname, $email, $password);
    
    if (mysqli_stmt_execute($insert_stmt)) {
        echo json_encode([
            'response' => true,
            'message' => 'Registrasi berhasil',
            'payload' => [
                "fullname" => $fullname,
                "email" => $email
            ]
        ]);
    } else {
        echo json_encode([
            'response' => false,
            'message' => 'Registrasi gagal: ' . mysqli_error($koneksi)
        ]);
    }
}

header('Content-Type: application/json');
?>