<?php $this->load->view('_partials/header_admin'); ?>

<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="qr-container text-center p-5 bg-white rounded shadow-sm">
                <img src="<?php echo base_url('assets/img/logo.png') ?>" alt="Logo" style="width: 100px; margin-bottom: 20px;">
                <h2 class="mb-3">Scan untuk Memesan</h2>
                <p class="text-muted mb-4">Arahkan kamera Anda ke QR code di bawah ini untuk melihat menu dan memesan.</p>
                <div id="qrcode" class="d-flex justify-content-center mb-4"></div>

                <button class="btn btn-success print-button me-2" onclick="window.print()">
                    <i class="bi bi-printer-fill"></i> Cetak
                </button>
                
                <button class="btn btn-primary print-button" id="download-button">
                    <i class="bi bi-download"></i> Unduh
                </button>

                </div>
        </div>
    </div>
</div>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    .qr-container, .qr-container * {
        visibility: visible;
    }
    .qr-container {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        border: none;
        box-shadow: none;
        margin: 0;
        padding: 0;
    }
    /* Ini akan menyembunyikan KEDUA tombol saat mencetak */
    .print-button {
        display: none;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script type="text/javascript">
    
    // 1. Buat QR Code (Kode Anda yang sudah ada)
    new QRCode(document.getElementById("qrcode"), {
        text: "<?php echo site_url('katalog'); ?>",
        width: 256,
        height: 256,
        colorDark : "#000000",
        colorLight : "#ffffff",
        correctLevel : QRCode.CorrectLevel.H
    });

    // === TAMBAHAN JAVASCRIPT DI SINI ===

    // 2. Tambahkan fungsi untuk tombol "Unduh"
    document.getElementById('download-button').addEventListener('click', function() {
        let qrImageSrc = '';

        // Library qrcode.js biasanya membuat tag <img> di dalam div #qrcode
        let img = document.querySelector('#qrcode img');
        
        if (img) {
            qrImageSrc = img.src;
        } else {
            // Sebagai cadangan, jika ia membuat <canvas>
            let canvas = document.querySelector('#qrcode canvas');
            if (canvas) {
                qrImageSrc = canvas.toDataURL('image/png');
            } else {
                alert('Tidak dapat menemukan QR code untuk diunduh.');
                return;
            }
        }

        // Buat link sementara untuk memicu unduhan
        let downloadLink = document.createElement('a');
        downloadLink.href = qrImageSrc;
        downloadLink.download = 'qr_code_rilstaurant.png'; // Ini akan menjadi nama file
        
        document.body.appendChild(downloadLink);
        downloadLink.click();
        document.body.removeChild(downloadLink);
    });

    // === AKHIR TAMBAHAN ===
</script>

<?php $this->load->view('_partials/footer_admin'); ?>