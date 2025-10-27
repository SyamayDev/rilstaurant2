<?php $this->load->view('_partials/header_admin'); ?>

<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="qr-container text-center p-5 bg-white rounded shadow-sm">
                <img src="<?php echo base_url('assets/img/logo.png') ?>" alt="Logo" style="width: 100px; margin-bottom: 20px;">
                <h2 class="mb-3">Scan untuk Memesan</h2>
                <p class="text-muted mb-4">Arahkan kamera Anda ke QR code di bawah ini untuk melihat menu dan memesan.</p>
                <div id="qrcode" class="d-flex justify-content-center mb-4"></div>
                <button class="btn btn-success print-button" onclick="window.print()">Cetak QR Code</button>
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
    .print-button {
        display: none;
    }
}
</style>

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

<?php $this->load->view('_partials/footer_admin'); ?>