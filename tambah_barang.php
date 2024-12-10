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

// Cek apakah form telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_barang = $_POST['id_barang'];
    $nama_barang = $_POST['nama_barang'];
    $harga_barang = $_POST['harga_barang'];
    $stok_barang = $_POST['stok_barang'];

    // Query untuk menambahkan data barang
    $sql = "INSERT INTO barang (ID_Barang, Nama_Barang, Harga_Barang, Stok_Barang) 
            VALUES ('$id_barang', '$nama_barang', '$harga_barang', '$stok_barang')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
            alert('Barang berhasil ditambahkan!');
            window.location.href='stok.php';
        </script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Barang</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: url('background_supermarket.jpg') no-repeat center center;
            background-size: cover;
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: black;
        }

        .container {
            width: 90%;
            max-width: 600px;
            background-color: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        h1 {
            text-align: center;
            font-size: 28px;
            margin-bottom: 20px;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }

        input {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .buttons {
            display: flex;
            justify-content: space-between;
        }

        .button {
            width: 48%;
            padding: 10px;
            font-size: 16px;
            font-weight: bold;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .button.batal {
            background-color: red;
        }

        .button.batal:hover {
            background-color: darkred;
        }

        .button.simpan {
            background-color: green;
        }

        .button.simpan:hover {
            background-color: darkgreen;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Tambah Barang</h1>
        <form method="POST" action="">
            <label for="id_barang">Masukkan ID Barang:</label>
            <input type="text" id="id_barang" name="id_barang" required>

            <label for="nama_barang">Masukkan Nama Barang:</label>
            <input type="text" id="nama_barang" name="nama_barang" required>

            <label for="harga_barang">Masukkan Harga Barang:</label>
            <input type="number" id="harga_barang" name="harga_barang" required>

            <label for="stok_barang">Masukkan Stok Barang:</label>
            <input type="number" id="stok_barang" name="stok_barang" required>

            <div class="buttons">
                <button type="button" class="button batal" onclick="window.location.href='stok.php'">Batal</button>
                <button type="submit" class="button simpan">Simpan</button>
            </div>
        </form>
    </div>
</body>

</html>