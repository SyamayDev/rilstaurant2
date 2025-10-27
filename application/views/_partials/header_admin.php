<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - Restoran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <style>
        .sidebar {
            background: #6b0f0f;
            color: #fff;
            min-height: 100vh
        }

        .content {
            background: #fff6f5
        }
    </style>
</head>

<body>
    <style>
        /* responsive sidebar */
        #sidebar {
            width: 240px;
            transition: transform .25s ease;
        }

        #mainContent {
            flex: 1 1 auto;
        }

        @media (max-width: 768px) {
            #sidebar {
                position: fixed;
                z-index: 1050;
                top: 0;
                left: 0;
                height: 500vh;
                transform: translateX(-110%);
            }

            #sidebar.show {
                transform: translateX(0%);
            }

            #mainContent {
                margin-left: 0;
            }
        }
    </style>
    <div class="d-flex">
        <div class="sidebar p-3" id="sidebar">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                    <img src="<?= base_url('assets/img/logo.png') ?>" alt="logo" style="height:42px;width:auto;border-radius:6px;margin-right:8px;background:#fff;padding:3px;box-shadow:0 2px 6px rgba(0,0,0,.12)" onerror="this.style.display='none'">
                    <h4 class="m-0">Admin</h4>
                </div>
                <button class="btn btn-sm btn-outline-light d-md-none" id="sidebarClose">✕</button>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link text-white" href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="<?= base_url('menu') ?>">Menu</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="<?= base_url('kategori') ?>">Kategori</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="<?= base_url('pesanan') ?>">Pesanan</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="<?= base_url('admin_ulasan') ?>">Ulasan</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="<?= base_url('laporan') ?>">Laporan</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="<?= base_url('admin/qr_code') ?>">QR Code</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="<?= base_url('admin/logout') ?>">Logout</a></li>
            </ul>
            <div class="mt-auto pt-3 border-top" style="font-size:13px;">
                <div class="d-flex align-items-center">
                    <img src="<?= base_url('assets/img/logo.png') ?>" alt="logo" style="height:36px;width:auto;border-radius:6px;margin-right:8px;background:#fff;padding:3px;box-shadow:0 2px 6px rgba(0,0,0,.08)" onerror="this.style.display='none'">
                    <div>
                        <div class="fw-semibold">Rilstaurant</div>
                        <div class="text-white">Admin Panel</div>
                    </div>
                </div>
                <div class="text-white mt-2">&copy; <?= date('Y') ?> Rilstaurant</div>
            </div>
        </div>
        <div class="content p-4 flex-grow-1" id="mainContent">
            <nav class="navbar bg-transparent mb-3">
                <div class="container-fluid p-0">
                    <button class="btn btn-outline-danger d-md-none" id="sidebarToggle" aria-label="Toggle sidebar">
                        ☰
                    </button>
                    <div class="ms-auto d-flex align-items-center">
                        <!-- small header area, can add admin name, notifications -->
                    </div>
                </div>
            </nav>
            <script>
                // mark active link in sidebar
                (function() {
                    try {
                        const links = document.querySelectorAll('#sidebar .nav-link');
                        const path = window.location.pathname.toLowerCase();
                        links.forEach(a => {
                            if (a.getAttribute('href') && path.indexOf(new URL(a.href).pathname.toLowerCase()) !== -1) {
                                a.classList.add('fw-bold');
                                a.style.background = 'rgba(255,255,255,0.04)';
                            }
                        });
                    } catch (e) {}
                })();
            </script>