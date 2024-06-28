<?php
// Konfigurasi koneksi ke database
$servername = "localhost";
$username = "root"; // Ganti dengan username database Anda
$password = ""; // Ganti dengan password database Anda
$dbname = "pweb_proj"; // Ganti dengan nama database Anda

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Memeriksa apakah data diterima
if (isset($_POST['original_email'], $_POST['full_name'], $_POST['new_email'], $_POST['password'])) {
    $originalEmail = $_POST['original_email'];
    $fullname = $_POST['full_name'];
    $newEmail = $_POST['new_email'];
    $password = $_POST['password']; // Tanpa hashing

    // SQL untuk melakukan update data pengguna
    $sql = "UPDATE `user` SET `fullname`=?, `email`=?, `password`=? WHERE `email`=?";

    // Persiapkan statement
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    // Bind parameter ke statement
    $stmt->bind_param("ssss", $fullname, $newEmail, $password, $originalEmail);

    // Eksekusi statement
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "Record updated successfully";
        } else {
            echo "No record updated. Please check the data.";
        }
    } else {
        echo "Error updating record: " . $stmt->error;
        error_log("Error: " . $stmt->error);
    }

    // Tutup statement
    $stmt->close();
} else {
    echo "Missing required parameters.";
}

// Tutup koneksi
$conn->close();
?>
