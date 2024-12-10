<?php
session_start();

// Koneksi ke database
$host = "localhost"; // Ganti dengan host database Anda
$username = "root"; // Ganti dengan username database Anda
$password = ""; // Ganti dengan password database Anda
$dbname = "speedcash"; // Ganti dengan nama database Anda

$conn = new mysqli($host, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['cetak'])) {
    // Ambil data transaksi dari session
    $transaksi = $_SESSION['barang'];
    $metode_pembayaran = $_SESSION['metode_pembayaran'];

    // Format nota
    $nota = "Nota Pembayaran\n";
    $nota .= "Tanggal: " . date("d-m-Y") . "\n";
    $nota .= "------------------------------------\n";
    $nota .= "Nama Barang\tJumlah\tHarga\tSubtotal\n";
    $nota .= "------------------------------------\n";

    $total = 0;
    foreach ($transaksi as $item) {
        // Ambil harga barang berdasarkan ID_Barang dari database
        $id_barang = $item['ID_Barang'];
        $query = "SELECT Harga_Barang FROM barang WHERE ID_Barang = '$id_barang'";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $harga_barang = $row['Harga_Barang'];
        } else {
            $harga_barang = 0; // Jika tidak ditemukan, set harga 0
        }

        $subtotal = $harga_barang * $item['Jumlah'];
        $total += $subtotal;
        $nota .= "{$item['Nama_Barang']}\t{$item['Jumlah']}\t" . number_format($harga_barang, 0, ',', '.') . "\t" . number_format($subtotal, 0, ',', '.') . "\n";
    }

    $nota .= "------------------------------------\n";
    $nota .= "Metode Pembayaran: $metode_pembayaran\n";
    $nota .= "Total: Rp " . number_format($total, 0, ',', '.') . "\n";

    // Simpan nota ke file
    $filename = "nota_" . date("YmdHis") . ".txt";
    file_put_contents($filename, $nota);

    // Unduh file
    header("Content-Disposition: attachment; filename=$filename");
    header("Content-Type: text/plain");
    readfile($filename);
    unlink($filename); // Hapus file setelah diunduh
    exit;
}

if (isset($_POST['selesai'])) {
    session_destroy(); // Hapus data session
    echo "<script>alert('Transaksi berhasil!'); window.location.href='home_karyawan.php';</script>";
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Pembayaran</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: url('groceries.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        .container {
            background: rgba(255, 255, 255, 0.9);
            margin: 50px auto;
            padding: 20px;
            border-radius: 15px;
            max-width: 800px;
            text-align: center;
        }

        h1 {
            font-size: 36px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th,
        table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        table th {
            background: #f4f4f4;
        }

        .buttons {
            display: flex;
            justify-content: space-around;
        }

        .button {
            padding: 10px 20px;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .button-back {
            background-color: #f44336;
            color: white;
        }

        .button-back:hover {
            background-color: #d32f2f;
        }

        .button-cetak {
            background-color: #4caf50;
            color: white;
        }

        .button-cetak:hover {
            background-color: #45a049;
        }

        .button-selesai {
            background-color: #2196f3;
            color: white;
        }

        .button-selesai:hover {
            background-color: #1e88e5;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Nota Pembayaran</h1>
        <p><strong>Tanggal:</strong> <?php echo date("d/m/Y"); ?></p>
        <table>
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Ambil data transaksi dari session
                if (isset($_SESSION['barang'])) {
                    $transaksi = $_SESSION['barang'];
                    $total = 0;

                    foreach ($transaksi as $item) {
                        // Ambil harga barang berdasarkan ID_Barang dari database
                        $id_barang = $item['ID_Barang'];
                        $query = "SELECT Harga_Barang FROM barang WHERE ID_Barang = '$id_barang'";
                        $result = $conn->query($query);

                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $harga_barang = $row['Harga_Barang'];
                        } else {
                            $harga_barang = 0; // Jika tidak ditemukan, set harga 0
                        }

                        $subtotal = $harga_barang * $item['Jumlah'];
                        $total += $subtotal;

                        echo "<tr>
                            <td>{$item['Nama_Barang']}</td>
                            <td>{$item['Jumlah']}</td>
                            <td>Rp " . number_format($harga_barang, 0, ',', '.') . "</td>
                            <td>Rp " . number_format($subtotal, 0, ',', '.') . "</td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Tidak ada barang dalam transaksi.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <p><strong>Metode Pembayaran:</strong> <?php echo htmlspecialchars($_SESSION['metode_pembayaran'] ?? "Cash"); ?></p>
        <p><strong>Total:</strong> Rp <?php echo number_format($total, 0, ',', '.'); ?></p>

        <form method="POST" class="buttons">
            <button class="button button-back" type="button" onclick="window.location.href='metode_pembayaran.php'">Kembali</button>
            <button class="button button-cetak" name="cetak">Cetak Nota</button>
            <button class="button button-selesai" name="selesai">Selesai</button>
        </form>
    </div>
</body>

</html>

<?php
// Menutup koneksi database
$conn->close();
?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Pembayaran</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: url('groceries.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        .container {
            background: rgba(255, 255, 255, 0.9);
            margin: 50px auto;
            padding: 20px;
            border-radius: 15px;
            max-width: 800px;
            text-align: center;
        }

        h1 {
            font-size: 36px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th,
        table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        table th {
            background: #f4f4f4;
        }

        .buttons {
            display: flex;
            justify-content: space-around;
        }

        .button {
            padding: 10px 20px;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .button-back {
            background-color: #f44336;
            color: white;
        }

        .button-back:hover {
            background-color: #d32f2f;
        }

        .button-cetak {
            background-color: #4caf50;
            color: white;
        }

        .button-cetak:hover {
            background-color: #45a049;
        }

        .button-selesai {
            background-color: #2196f3;
            color: white;
        }

        .button-selesai:hover {
            background-color: #1e88e5;
        }
    </style>


</html>