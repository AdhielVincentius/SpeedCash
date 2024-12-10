<?php
// Koneksi ke database
$host = "localhost";
$user = "root";
$password = "";
$db = "speedcash";

$conn = new mysqli($host, $user, $password, $db);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Inisialisasi pesan
$error_message = "";
$success_message = "";

// Proses ketika tombol Hapus ditekan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_barang = $_POST['id_barang'];

    // Periksa apakah ID Barang ada di database
    $check_sql = "SELECT * FROM barang WHERE ID_Barang = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("s", $id_barang);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Jika ID Barang ditemukan, hapus data
        $delete_sql = "DELETE FROM barang WHERE ID_Barang = ?";
        $stmt = $conn->prepare($delete_sql);
        $stmt->bind_param("s", $id_barang);

        if ($stmt->execute()) {
            $success_message = "Barang dengan ID $id_barang berhasil dihapus.";
            echo "<script>
                    setTimeout(function() {
                        window.location.href = 'Stok_Karyawan.php';
                    }, 2000); // Redirect setelah 2 detik
                  </script>";
        } else {
            $error_message = "Gagal menghapus barang. Silakan coba lagi.";
        }
    } else {
        // Jika ID Barang tidak ditemukan
        $error_message = "ID Barang tidak ditemukan.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hapus Barang</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: url('gudang.jpg') no-repeat center center;
            background-size: cover;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            width: 90%;
            max-width: 400px;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        h1 {
            margin-bottom: 20px;
            color: #333;
        }

        label {
            font-size: 16px;
            margin-bottom: 10px;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .buttons {
            display: flex;
            justify-content: space-between;
        }

        .button {
            padding: 10px 20px;
            font-size: 14px;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .button.cancel {
            background-color: red;
        }

        .button.delete {
            background-color: green;
        }

        .button:hover {
            opacity: 0.8;
        }

        .message {
            margin-top: 20px;
            font-size: 14px;
        }

        .error {
            color: red;
        }

        .success {
            color: green;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Hapus Barang</h1>
        <form method="POST" action="hapus_barang_karyawan.php">
            <label for="id_barang">Masukkan ID Barang yang Akan Dihapus:</label>
            <input type="text" id="id_barang" name="id_barang" required>
            <div class="buttons">
                <button type="button" class="button cancel" onclick="window.location.href='Stok_Karyawan.php'">Batal</button>
                <button type="submit" class="button delete">Hapus</button>
            </div>
        </form>

        <!-- Tampilkan pesan -->
        <?php if ($error_message): ?>
            <div class="message error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <?php if ($success_message): ?>
            <div class="message success"><?php echo $success_message; ?></div>
        <?php endif; ?>
    </div>
</body>

</html>
<?php $conn->close(); ?>