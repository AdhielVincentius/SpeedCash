<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";
$db = "speedcash";

$conn = new mysqli($host, $user, $password, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$errorMessage = "";

if (!isset($_SESSION['barang'])) {
    $_SESSION['barang'] = [];
}

if (!isset($_SESSION['totalHarga'])) {
    $_SESSION['totalHarga'] = 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ID_Barang = trim($_POST['ID_Barang']);
    $Jumlah = intval($_POST['Jumlah']);

    if (empty($ID_Barang)) {
        $errorMessage = "ID Barang harus diisi.";
    } elseif ($Jumlah <= 0) {
        $errorMessage = "Jumlah barang harus lebih dari 0.";
    } else {
        $sql = "SELECT Nama_Barang, Harga_Barang, Stok_Barang FROM barang WHERE ID_Barang = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $ID_Barang);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $Nama_Barang = $row['Nama_Barang'];
            $Harga_Satuan = $row['Harga_Barang'];
            $Stok = $row['Stok_Barang'];

            if ($Jumlah > $Stok) {
                $errorMessage = "Jumlah melebihi stok. Stok saat ini: $Stok";
            } else {
                $Harga_Total = $Harga_Satuan * $Jumlah;
                $barangDitemukan = false;

                foreach ($_SESSION['barang'] as &$item) {
                    if ($item['ID_Barang'] === $ID_Barang) {
                        $item['Jumlah'] += $Jumlah;
                        $item['Harga'] += $Harga_Total;
                        $barangDitemukan = true;
                        break;
                    }
                }

                if (!$barangDitemukan) {
                    $_SESSION['barang'][] = [
                        'ID_Barang' => $ID_Barang,
                        'Nama_Barang' => $Nama_Barang,
                        'Jumlah' => $Jumlah,
                        'Harga' => $Harga_Total
                    ];
                }

                $_SESSION['totalHarga'] += $Harga_Total;

                $newStok = $Stok - $Jumlah;
                $updateSql = "UPDATE barang SET Stok_Barang = ? WHERE ID_Barang = ?";
                $updateStmt = $conn->prepare($updateSql);
                $updateStmt->bind_param("is", $newStok, $ID_Barang);
                if (!$updateStmt->execute()) {
                    $errorMessage = "Gagal mengurangi stok barang.";
                }
                $updateStmt->close();
            }
        } else {
            $errorMessage = "ID Barang tidak ditemukan.";
        }
        $stmt->close();
    }
}

if (isset($_GET['hapus']) && ctype_digit($_GET['hapus'])) {
    $hapusID = $_GET['hapus'];

    foreach ($_SESSION['barang'] as $key => $barang) {
        if ($barang['ID_Barang'] == $hapusID) {
            $sql = "SELECT Stok_Barang FROM barang WHERE ID_Barang = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $hapusID);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $newStok = $row['Stok_Barang'] + $barang['Jumlah'];

                $updateSql = "UPDATE barang SET Stok_Barang = ? WHERE ID_Barang = ?";
                $updateStmt = $conn->prepare($updateSql);
                $updateStmt->bind_param("is", $newStok, $hapusID);
                $updateStmt->execute();
                $updateStmt->close();
            }

            $_SESSION['totalHarga'] -= $barang['Harga'];
            unset($_SESSION['barang'][$key]);
            $_SESSION['barang'] = array_values($_SESSION['barang']);
            break;
        }
    }
}

if (isset($_GET['batal']) && $_GET['batal'] === "true") {
    foreach ($_SESSION['barang'] as $barang) {
        $sql = "SELECT Stok_Barang FROM barang WHERE ID_Barang = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $barang['ID_Barang']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $newStok = $row['Stok_Barang'] + $barang['Jumlah'];

            $updateSql = "UPDATE barang SET Stok_Barang = ? WHERE ID_Barang = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("is", $newStok, $barang['ID_Barang']);
            $updateStmt->execute();
            $updateStmt->close();
        }
    }

    session_unset();
    session_destroy();
    header("Location: /home_karyawan.php");
    exit();
}

$conn->close();
?>



<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background: url('groceries.jpg') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 15px;
            width: 90%;
            max-width: 800px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .form-group input:focus {
            outline: none;
            border-color: #4CAF50;
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
        }

        .error-message {
            color: red;
            font-size: 16px;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f4f4f4;
        }

        .button {
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-green {
            background-color: #4CAF50;
            color: white;
        }

        .btn-red {
            background-color: #f44336;
            color: white;
        }

        .btn-red:hover {
            background-color: #d32f2f;
        }

        .btn-green:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Transaksi</h1>

        <!-- Notifikasi error jika ID Barang tidak ditemukan atau stok tidak mencukupi -->
        <?php if ($errorMessage): ?>
            <p class="error-message"><?= $errorMessage ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="ID_Barang">Masukkan ID Barang:</label>
                <input type="text" id="ID_Barang" name="ID_Barang" required>
            </div>
            <div class="form-group">
                <label for="Jumlah">Masukkan Jumlah:</label>
                <input type="number" id="Jumlah" name="Jumlah" min="1" required>
            </div>
            <button class="button btn-green" type="submit">Hitung</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID_Barang</th>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>Total Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['barang'] as $item): ?>
                    <tr>
                        <td><?= $item['ID_Barang'] ?></td>
                        <td><?= $item['Nama_Barang'] ?></td>
                        <td><?= $item['Jumlah'] ?></td>
                        <td>Rp <?= number_format($item['Harga'], 0, ',', '.') ?></td>
                        <td><a href="?hapus=<?= $item['ID_Barang'] ?>" class="btn-red">Hapus</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2>Total Keseluruhan: Rp <?= number_format($_SESSION['totalHarga'], 0, ',', '.') ?></h2>

        <button class="button btn-red" onclick="window.location.href='?batal=true'">Batal</button>
        <button class="button btn-green"
            <?php if (empty($_SESSION['barang'])): ?>
            disabled
            <?php endif; ?>
            onclick="window.location.href='metode_pembayaran.php'">Lanjutkan</button>

    </div>
</body>

</html>