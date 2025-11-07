<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Restoran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: #fff6f5;
            color: #3b0a0a;
        }

        .brand {
            background: #6b0f0f;
            color: #f6e9d8;
        }

        .navbar-brand img {
            height: 40px;
            width: auto;
            margin-right: 2px;
            border-radius: 8px;
            background: #fff;
            padding: 2px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        @media(max-width:576px) {
            .navbar-brand img {
                height: 34px;
                margin-right: -15px;
                margin-left: -8px;
            }

            .navbar-brand span {
                font-size: 1rem;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar brand navbar-expand-lg p-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center text-light" href="<?= base_url() ?>">
                <img src="<?= base_url('assets/img/logo.webp') ?>" alt="Logo Rilstaurant" onerror="this.style.display='none'">
                <span class="fw-bold"></span>
            </a>
            <div class="ms-auto d-flex align-items-center">
                <a class="btn btn-light fw-semibold d-flex align-items-center me-2" href="<?= base_url('Katalog/riwayat') ?>">
                    <i class="bi bi-clock-history text-danger me-2"></i>
                    Riwayat
                </a>
                <a id="btnCart" class="btn btn-light fw-semibold d-flex align-items-center" href="<?= base_url('Katalog/keranjang') ?>">
                    <i class="bi bi-cart-plus-fill text-danger me-2"></i>
                    Keranjang <span id="cartCount" class="badge bg-danger ms-2">0</span>
                </a>
                <!-- guest flow: no login required -->
            </div>
        </div>
    </nav>
    <!-- No login modal for guest flow; orders stored per-device in Riwayat -->

    <div class="container my-4">