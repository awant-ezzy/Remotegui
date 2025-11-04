<?php
require_once('config.php'); // Load konfigurasi

$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

// Fungsi untuk menambahkan akun ke database
function addAccountToDatabase($username, $password, $port) {
    global $conn;
    $username = $conn->real_escape_string($username);
    $password = password_hash($password, PASSWORD_BCRYPT); // Hash password
    $port = (int)$port; // Pastikan port adalah integer

    $sql = "INSERT INTO accounts (username, password, port) VALUES ('$username', '$password', $port)";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fungsi untuk menghapus akun dari database
function deleteAccountFromDatabase($username) {
    global $conn;
    $username = $conn->real_escape_string($username);

    $sql = "DELETE FROM accounts WHERE username = '$username'";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fungsi untuk mendapatkan informasi akun dari database
function getAccountFromDatabase($username) {
    global $conn;
    $username = $conn->real_escape_string($username);

    $sql = "SELECT * FROM accounts WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

// Fungsi untuk menutup koneksi database (penting!)
function closeDatabaseConnection() {
    global $conn;
    $conn->close();
}
?>
