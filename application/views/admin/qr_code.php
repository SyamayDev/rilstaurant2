<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak QR Code</title>
    <style>
        body {
            font-family: sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f4f4f4;
            margin: 0;
        }
        .qr-container {
            text-align: center;
            padding: 40px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .qr-container img {
            width: 100px;
            margin-bottom: 20px;
        }
        #qrcode {
            margin: 20px auto;
        }
        #qrcode img {
            margin: 0 auto;
            width: 256px;
            height: 256px;
        }
        h2 {
            margin-bottom: 10px;
            color: #333;
        }
        p {
            color: #666;
            margin-bottom: 30px;
        }
        .print-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .print-button:hover {
            background-color: #45a049;
        }
        @media print {
            body {
                background-color: #fff;
            }
            .print-button {
                display: none;
            }
            .qr-container {
                box-shadow: none;
                border: 2px solid #000;
            }
        }
    </style>
</head>
<body>

    <div class="qr-container">
        <img src="<?php echo base_url('assets/img/logo.png') ?>" alt="Logo">
        <h2>Scan untuk Memesan</h2>
        <p>Arahkan kamera Anda ke QR code di bawah ini untuk melihat menu dan memesan.</p>
        <div id="qrcode"></div>
        <button class="print-button" onclick="window.print()">Cetak QR Code</button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <script type="text/javascript">
        new QRCode(document.getElementById("qrcode"), {
            text: "<?php echo site_url('katalog'); ?>",
            width: 256,
            height: 256,
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });
    </script>

</body>
</html>
