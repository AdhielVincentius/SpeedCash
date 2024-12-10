<?php
// Koneksi ke database
$host = "localhost";
$user = "root";
$password = "";
$db = "speedcash";

$conn = new mysqli($host, $user, $password, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari form
if (isset($_POST['ID_User']) && isset($_POST['Password'])) {
    $ID_User = $_POST['ID_User'];
    $Password = $_POST['Password'];

    // Query untuk memeriksa ID_User, Password, dan Role
    $sql = "SELECT Role FROM users WHERE ID_User = ? AND Password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $ID_User, $Password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $role = $row['Role'];

        // Periksa Role pengguna
        if ($role === "owner") {
            // Jika Role adalah owner, arahkan ke dashboard owner
            header("Location: stok_barang.php");
        } elseif ($role === "kasir") {
            // Jika Role adalah kasir, arahkan ke dashboard karyawan
            header("Location: home_karyawan.php");
        } else {
            // Jika Role tidak dikenali
            echo "<h1>Login Gagal!</h1>";
            echo "<p>Role tidak valid.</p>";
        }
        exit();
    } else {
        // Jika login gagal
        echo "<h1>Login Gagal!</h1>";
        echo "<p>ID_User atau Password salah.</p>";
    }

    $stmt->close();
} else {
    echo "<h1>Error!</h1>";
    echo "<p>Form tidak diisi dengan benar.</p>";
}

$conn->close();
