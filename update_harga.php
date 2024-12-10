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

// Query untuk mengambil data stok barang
$sql = "SELECT ID_Barang, Nama_Barang, Harga_Barang, Stok_Barang FROM barang";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stok Barang</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            background: url('gudang.jpg') no-repeat center center;
            background-size: cover;
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            color: black;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            background-color: white;
            padding: 20px;
            margin-top: 50px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        h1 {
            font-size: 36px;
            color: black;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 12px;
            text-align: center;
            border: 1px solid black;
        }

        th {
            background-color: #f4f4f4;
            font-size: 18px;
        }

        td {
            font-size: 16px;
        }

        .button {
            margin: 10px;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .button:hover {
            background-color: #218838;
        }

        .logout-button {
            position: absolute;
            /* Membuat tombol berada di posisi tetap */
            top: 10px;
            /* Jarak dari atas layar */
            right: 10px;
            /* Jarak dari kanan layar */
            padding: 10px 20px;
            background-color: red;
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 16px;
        }

        .logout-button:hover {
            background-color: darkred;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Update Harga</h1>
        <table>
            <thead>
                <tr>
                    <th>ID_Barang</th>
                    <th>Nama Barang</th>
                    <th>Harga</th>
                    <th>Stok</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['ID_Barang'] . "</td>";
                        echo "<td>" . $row['Nama_Barang'] . "</td>";
                        echo "<td>Rp " . number_format($row['Harga_Barang'], 0, ',', '.') . "</td>";
                        echo "<td>" . $row['Stok_Barang'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Tidak ada data barang.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <button class="button" onclick="window.location.href='ubah_harga.php'">Update Harga</button>
    </div>
    <button class="logout-button" onclick="window.location.href='stok_barang.php'">Kembali</button>

</body>

</html>

<?php
$conn->close();
?>