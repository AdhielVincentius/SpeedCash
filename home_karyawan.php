<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Karyawan</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            background: url('market.jpg') no-repeat center center;
            background-size: cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            text-align: center;
        }

        .button {
            display: block;
            margin: 25px auto;
            padding: 15px 30px;
            font-size: 20px;
            background-color: yellow;
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            font-weight: bold;
        }

        .button:hover {
            background-color: #ddd;
        }

        .logout-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color: red;
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
        }

        .logout-button:hover {
            background-color: darkred;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 style="font-family:'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;color: white; font-size: 40px; text-shadow: 2px 2px 4px #000;">SpeedCash</h1>
        <button class="button" onclick="window.location.href='Transaksi.php'">Transaksi</button>
        <button class="button" onclick="window.location.href='Stok_Karyawan.php'">Stok Barang</button>
    </div>
    <button class="logout-button" onclick="window.location.href='index.html'">Keluar</button>
</body>

</html>