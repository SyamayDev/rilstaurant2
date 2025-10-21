<?php

/**
 * Simple printable receipt view for a pesanan
 * Expects: $pesanan, $pelanggan, $detail
 */
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Struk Pembayaran #<?= htmlspecialchars($pesanan->id_pesanan) ?></title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #222;
        }

        .receipt {
            width: 320px;
            margin: 0 auto;
            padding: 12px;
        }

        .logo {
            text-align: center;
            margin-bottom: 8px;
        }

        .logo img {
            max-width: 180px;
        }

        .meta {
            font-size: 12px;
            margin-bottom: 8px;
        }

        .items {
            width: 100%;
            border-top: 1px dashed #ccc;
            border-bottom: 1px dashed #ccc;
            margin: 8px 0;
        }

        .items .row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            font-size: 13px;
        }

        .total {
            font-weight: 700;
            text-align: right;
            margin-top: 8px;
            font-size: 16px;
        }

        .small {
            font-size: 11px;
            color: #666;
        }

        @media print {
            body {
                margin: 0;
            }

            .receipt {
                box-shadow: none;
            }
        }
    </style>
</head>

<body>
    <div class="receipt">
        <div class="meta">
            <div>No. Pesanan: <strong>#<?= htmlspecialchars($pesanan->id_pesanan) ?></strong></div>
            <div>Meja: <?= htmlspecialchars($pelanggan->no_meja ?? '-') ?> • Pelanggan: <?= htmlspecialchars($pelanggan->nama ?? '-') ?></div>
            <div>Tanggal: <?= htmlspecialchars($pesanan->tanggal) ?></div>
        </div>

        <div class="items">
            <?php foreach ($detail as $d): ?>
                <div class="row">
                    <div style="flex:1"><?= htmlspecialchars($d->nama_menu) ?> x<?= (int)$d->jumlah ?></div>
                    <div style="text-align:right">Rp <?= number_format($d->subtotal, 0, ',', '.') ?></div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="total">Total: Rp <?= number_format($pesanan->total, 0, ',', '.') ?></div>

        <div class="small" style="margin-top:12px; margin-bottom: 20px; text-align:center">
            Terima kasih telah memesan!<br>
            Silakan simpan struk ini sebagai bukti pembayaran.
        </div>

        <div class="logo">
            <?php if (file_exists(FCPATH . 'assets/img/logo.png')): ?>
                <img width="100px" height="100px" src="<?= base_url('assets/img/logo.png') ?>" alt="logo">
            <?php else: ?>
                <h2>Rilstaurant</h2>
            <?php endif; ?>
        </div>
    </div>
    <script>
        // Auto print when opened from admin panel
        window.addEventListener('load', function() {
            window.print();
        });
    </script>
</body>

</html>