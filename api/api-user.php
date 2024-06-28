<?php
require_once '../config/koneksi.php';

header('Content-Type: application/json');

if (isset($_GET['email'])) {
    $email = $_GET['email'];
    $stmt = $mysqli->prepare("SELECT fullname FROM user WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->bind_result($fullname);
    $stmt->fetch();
    
    if ($fullname) {
        echo json_encode([
            'status' => 'success',
            'fullName' => $fullname
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Pengguna tidak ditemukan'
        ]);
    }
    $stmt->close();
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Email diperlukan'
    ]);
}
$mysqli->close();
?>