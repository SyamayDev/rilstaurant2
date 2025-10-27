<?php $this->load->view('_partials/header_pelanggan'); ?>

<?php
// helper to escape
function h($s)
{
    return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8');
}

// Map known status values to step index (1..4)
$status_map = [
    'pending' => 1,
    'pembayaran' => 1,
    'processing' => 1,
    'diproses' => 2,
    'dimasak' => 2,
    'cooking' => 2,
    'diantar' => 3,
    'delivering' => 3,
    'delivered' => 3,
    'selesai' => 4,
    'done' => 4,
    'completed' => 4
];

$current_status = strtolower((string)($pesanan->status ?? 'pending'));
$current_step = isset($status_map[$current_status]) ? $status_map[$current_status] : 1;
// Cancellation states
$cancel_states = ['batal', 'cancel', 'canceled'];
$is_cancelled = in_array($current_status, $cancel_states, true);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Status Pesanan #<?php echo h($pesanan->id_pesanan); ?> - Rilstaurant</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        :root {
            --primary-color: #A11D2A;
            --secondary-color: #4A4A4A;
            --success-color: #3E8E41;
            --background-color: #FDF8F7;
            --line-color: #E0E0E0;
            --card-shadow: 0 10px 25px rgba(0, 0, 0, .08)
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--background-color);
            margin: 0;
            box-sizing: border-box;
        }

        .order-card {
            background: #fff;
            max-width: 900px;
            margin: 0 auto;
            padding: 28px;
            border-radius: 14px;
            box-shadow: var(--card-shadow);
            border-top: 6px solid var(--primary-color)
        }

        .card-header {
            text-align: center;
            margin-bottom: 30px
        }

        .card-header h2 {
            margin: 0;
            color: var(--primary-color);
            font-weight: 600
        }

        .card-header p {
            margin: 6px 0 0;
            color: #777
        }

        .tracker-wrapper {
            position: relative;
            width: 100%;
            display: flex;
            align-items: center
        }

        .progress-bar {
            position: absolute;
            top: 50%;
            left: 0;
            transform: translateY(-50%);
            height: 6px;
            width: 100%;
            background: var(--line-color);
            z-index: 1
        }

        .progress-bar-fill {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            background: var(--primary-color);
            width: 0%;
            transition: width .6s cubic-bezier(.65, 0, .35, 1)
        }

        .steps-container {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 2
        }

        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            width: 100px
        }

        .icon {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: #fff;
            border: 3px solid var(--line-color);
            color: var(--line-color);
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 20px;
            transition: all .4s ease;
            position: relative;
            z-index: 3
        }

        .status-text {
            margin-top: 10px;
            font-size: 14px;
            color: #999;
            font-weight: 500;
            transition: color .4s
        }

        .step.complete .icon {
            background: var(--success-color);
            border-color: var(--success-color);
            color: #fff
        }

        .step.complete .status-text {
            color: var(--success-color)
        }

        .step.active .icon {
            background: #fff;
            border-color: var(--primary-color);
            color: var(--primary-color);
            /* Menerapkan animasi pulse */
            animation: pulse 1.5s infinite cubic-bezier(0.65, 0, 0.35, 1);
        }

        .step.active .status-text {
            color: var(--primary-color);
            font-weight: 600
        }

        .details {
            margin-top: 24px
        }

        .list-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f1f1f1
        }

        .controls {
            text-align: center;
            margin-top: 28px;
            padding-top: 18px;
            border-top: 1px solid #f0f0f0
        }

        .controls a {
            display: inline-block;
            margin: 6px;
            padding: 10px 18px;
            border-radius: 8px;
            text-decoration: none
        }

        /* Keyframe untuk animasi pulse (besar-kecil) */
        @keyframes pulse {
            0% {
                transform: scale(1.05);
            }

            50% {
                transform: scale(1.18);
            }

            100% {
                transform: scale(1.05);
            }
        }
        @media(max-width:576px) {
            .navbar-brand img {
                height: 34px;
                margin-right: -15px;
                margin-left: -8px;
            }

        }

        @media(max-width:600px) {
            .step {
                width: 70px
            }

            .icon {
                width: 46px;
                height: 46px
            }
        }
    </style>
</head>

<body>

    <div class="order-card">
        <?php if ($is_cancelled): ?>
            <div style="text-align:center; padding:40px 10px;">
                <div style="width:110px;height:110px;border-radius:50%;background:#fff;border:6px solid #f44336;color:#f44336;margin:0 auto;display:flex;align-items:center;justify-content:center;font-size:44px;">
                    &times;
                </div>
                <h2 style="color:#f44336;margin-top:18px;">Pesanan Dibatalkan</h2>
                <p class="text-muted">Pesanan Anda telah dibatalkan. Jika ini tidak sesuai, silakan hubungi kasir.</p>
                <div style="margin-top:18px;"><a href="<?= base_url('/') ?>" class="btn btn-outline-secondary">Kembali ke Katalog</a></div>
            </div>
        <?php else: ?>
            <div class="card-header">
                <h2>Status Pesanan Anda</h2>
                <p>Nomor pesanan: #<?php echo h($pesanan->id_pesanan); ?> • Meja: <?php echo h($pelanggan->no_meja ?? '-'); ?> • <?php echo h($pelanggan->nama ?? '-'); ?></p>
            </div>

            <div class="tracker-wrapper">
                <div class="progress-bar">
                    <div class="progress-bar-fill" id="progressBarFill"></div>
                </div>
                <div class="steps-container" id="stepsContainer">
                    <div class="step" data-step="1">
                        <div class="icon"><i class="fas fa-clipboard-list"></i></div>
                        <p class="status-text">Pembayaran</p>
                    </div>
                    <div class="step" data-step="2">
                        <div class="icon"><i class="fas fa-fire-burner"></i></div>
                        <p class="status-text">Dimasak</p>
                    </div>
                    <div class="step" data-step="3">
                        <div class="icon"><i class="fas fa-bell-concierge"></i></div>
                        <p class="status-text">Diantar</p>
                    </div>
                    <div class="step" data-step="4">
                        <div class="icon"><i class="fas fa-check-circle"></i></div>
                        <p class="status-text">Selesai</p>
                    </div>
                </div>
            </div>

            <div class="details">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="fw-semibold">Rincian Pesanan</div>
                    <div class="text-muted">Tanggal: <?php echo h($pesanan->tanggal); ?></div>
                </div>
                <?php foreach ($detail as $d) : ?>
                    <div class="list-item">
                        <div>
                            <div class="fw-semibold"><?php echo h($d->nama_menu); ?></div>
                            <small class="text-muted">Jumlah: <?php echo (int)$d->jumlah; ?></small>
                        </div>
                        <div class="fw-bold">Rp <?php echo number_format($d->subtotal, 0, ',', '.'); ?></div>
                    </div>
                <?php endforeach; ?>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">Total</div>
                    <div class="fw-bold fs-5">Rp <?php echo number_format($pesanan->total, 0, ',', '.'); ?></div>
                </div>
            </div>

            <div class="controls">
                <?php if ($current_status === 'pending') : ?>
                    <a href="<?= base_url('pesanan/batalkan/' . h($pesanan->id_pesanan)) ?>" class="btn btn-danger me-2" onclick="return confirm('Anda yakin ingin membatalkan pesanan ini?')">Batalkan Pesanan</a>
                <?php else : ?>
                    <button class="btn btn-danger me-2" onclick="alert('Pesanan sudah diproses, tidak bisa membatalkan pesanan.')">Batalkan Pesanan</button>
                <?php endif; ?>
                <a href="<?= base_url('/') ?>" class="btn btn-outline-secondary me-2">Kembali ke Katalog</a>
                <div class="text-muted mt-2">Silakan ke kasir untuk melakukan pembayaran. Petugas kasir/admin akan memperbarui status pesanan.</div>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // client-side
        (function() {
            var currentStep = <?php echo (int)$current_step; ?>;
            var steps = document.querySelectorAll('.step');
            var progressFill = document.getElementById('progressBarFill');

            function applyStep(step) {
                steps.forEach(function(s) {
                    var idx = parseInt(s.getAttribute('data-step'));
                    if (idx < step) {
                        s.classList.add('complete');
                        s.classList.remove('active');
                    } else if (idx === step) {
                        s.classList.add('active');
                        s.classList.remove('complete');
                    } else {
                        s.classList.remove('active');
                        s.classList.remove('complete');
                    }
                });
                var total = steps.length;
                var pct = ((step - 1) / (total - 1)) * 100;
                progressFill.style.width = pct + '%';
            }

            applyStep(currentStep);

            // Clear cart if server requested
            var clearCart = <?php echo json_encode(!empty($clear_cart)); ?>;
            if (clearCart) {
                localStorage.removeItem('cart');
                try {
                    document.getElementById('cartCount').innerText = '0';
                } catch (e) {}
            }

            // If this is a recent order, store its id in local device history (localStorage.orders)
            var isRecent = <?php echo json_encode(!empty($is_recent)); ?>;
            var recentId = <?php echo json_encode(!empty($recent_order_id) ? $recent_order_id : null); ?>;
            if (isRecent && recentId) {
                try {
                    var arr = JSON.parse(localStorage.getItem('orders') || '[]');
                    // avoid duplicates
                    if (arr.indexOf(recentId) === -1) arr.push(recentId);
                    localStorage.setItem('orders', JSON.stringify(arr));
                } catch (e) {}
            }

            // Setup periodic polling: update status every 10s
            setInterval(function() {
                fetch('<?php echo base_url('pesanan/status/'); ?>' + <?php echo (int)$pesanan->id_pesanan; ?>)
                    .then(function(r) {
                        return r.json();
                    })
                    .then(function(j) {
                        if (j && j.status) {
                            var s = (j.status || '').toString().toLowerCase();
                            var map = {
                                pending: 1,
                                pembayaran: 1,
                                processing: 1,
                                diproses: 2,
                                dimasak: 2,
                                cooking: 2,
                                diantar: 3,
                                dikirim: 3,
                                delivering: 3,
                                delivered: 3,
                                selesai: 4,
                                done: 4,
                                completed: 4
                            };
                            applyStep(map[s] || 1);
                        }
                    }).catch(function() {});
            }, 10000);
        })();
    </script>

    <?php $this->load->view('_partials/footer_pelanggan'); ?>