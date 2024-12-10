<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Metode Pembayaran</title>
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

        h1 {
            font-size: 36px;
            margin-bottom: 20px;
        }

        .options {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }

        .option {
            width: 45%;
            background-color: #fff;
            border: 2px solid #ddd;
            border-radius: 15px;
            padding: 20px;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .option:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
        }

        .option img {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
        }

        .option label {
            font-size: 20px;
            font-weight: bold;
        }

        .back-button {
            margin-top: 30px;
            padding: 10px 20px;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            background-color: #f44336;
            color: white;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .back-button:hover {
            background-color: #d32f2f;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Pilih Metode Pembayaran</h1>
        <div class="options">
            <!-- Opsi Cash -->
            <div class="option" onclick="setPaymentMethod('cash')">
                <img src="cash.png" alt="Cash">
                <label>Cash</label>
            </div>

            <!-- Opsi E-Money -->
            <div class="option" onclick="setPaymentMethod('cashless')">
                <img src="qris.png" alt="E-Money">
                <label>Cashless</label>
            </div>
        </div>
        <!-- Tombol Kembali -->
        <button class="back-button" onclick="window.location.href='transaksi.php'">Kembali</button>
    </div>

    <script>
        function setPaymentMethod(method) {
            // Simpan metode pembayaran dalam session menggunakan query string
            window.location.href = 'nota_pembayaran.php?metode=' + method;
        }
    </script>
</body>

</html>