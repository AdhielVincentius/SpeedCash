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

// Proses ketika tombol Simpan ditekan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_barang = $_POST['id_barang'];
    $harga_baru = $_POST['harga_barang'];

    // Validasi input harga
    if (!is_numeric($harga_baru) || $harga_baru <= 0) {
        $error_message = "Harga harus berupa angka positif.";
    } else {
        // Periksa apakah ID Barang ada di database
        $check_sql = "SELECT * FROM barang WHERE ID_Barang = ?";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("s", $id_barang);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Jika ID Barang ditemukan, lakukan update
            $update_sql = "UPDATE barang SET Harga_Barang = ? WHERE ID_Barang = ?";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("is", $harga_baru, $id_barang);

            if ($stmt->execute()) {
                $success_message = "Harga berhasil diperbarui untuk ID Barang: $id_barang.";
            } else {
                $error_message = "Gagal menyimpan perubahan. Silakan coba lagi.";
            }
        } else {
            // Jika ID Barang tidak ditemukan
            $error_message = "ID Barang tidak ditemukan.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Harga Barang</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: url('background_supermarket.jpg') no-repeat center center;
            background-size: cover;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            width: 90%;
            max-width: 500px;
            background-color: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        h1 {
            font-size: 36px;
            margin-bottom: 20px;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label {
            font-size: 18px;
            margin-bottom: 10px;
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .buttons {
            display: flex;
            justify-content: space-between;
            width: 100%;
        }

        .button {
            padding: 10px 20px;
            font-size: 16px;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .button.cancel {
            background-color: red;
        }

        .button.save {
            background-color: green;
        }

        .button:hover {
            opacity: 0.8;
        }

        .message {
            margin-top: 20px;
            font-size: 16px;
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
        <h1>Update Harga Barang</h1>
        <form method="POST" action="">
            <label for="id_barang">Masukkan ID Barang:</label>
            <input type="text" name="id_barang" id="id_barang" required>

            <label for="harga_barang">Masukkan Harga Barang Baru:</label>
            <input type="number" name="harga_barang" id="harga_barang" required>

            <div class="buttons">
                <button type="button" class="button cancel" onclick="window.location.href='stok.php'">Batal</button>
                <button type="submit" class="button save">Simpan</button>
            </div>
        </form>

        <!-- Notifikasi Pesan -->
        <?php if ($error_message): ?>
            <div class="message error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <?php if ($success_message): ?>
            <div class="message success"><?php echo $success_message; ?></div>
        <?php endif; ?>
    </div>
</body>

</html>