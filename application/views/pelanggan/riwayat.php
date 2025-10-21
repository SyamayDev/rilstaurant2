<?php $this->load->view('_partials/header_pelanggan'); ?>

<div class="container mt-4">
    <h3>Riwayat Pesanan (Perangkat Ini)</h3>
    <p class="text-muted">Menampilkan pesanan yang dibuat dari perangkat ini. Tidak perlu login.</p>

    <div class="d-flex justify-content-end mb-3">
        <button id="clearHistory" class="btn btn-sm btn-outline-danger">Bersihkan Riwayat</button>
    </div>
    <div id="historyList"></div>
</div>

<script>
    function formatRupiah(num) {
        return 'Rp ' + Number(num).toLocaleString('id-ID');
    }

    async function loadHistory() {
        const orders = JSON.parse(localStorage.getItem('orders') || '[]');
        const el = document.getElementById('historyList');
        if (!orders.length) {
            el.innerHTML = '<p class="text-muted">Belum ada riwayat pesanan.</p>';
            return;
        }
        let html = '';
        for (let id of orders.slice().reverse()) {
            try {
                const res = await fetch('<?= base_url('pesanan/status/') ?>' + id);
                const j = await res.json();
                const status = (j && j.status) ? j.status : 'unknown';
                const badgeClass = ['selesai', 'done', 'completed'].indexOf((status || '').toLowerCase()) !== -1 ? 'bg-success' : (['dimasak', 'diproses', 'dikirim', 'diantar'].indexOf((status || '').toLowerCase()) !== -1 ? 'bg-warning text-dark' : 'bg-secondary');
                html += `<div class="card mb-2 shadow-sm"><div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-semibold">Pesanan #${id}</div>
                        <small class="text-muted">Status: <span class="badge ${badgeClass}">${status}</span></small>
                    </div>
                    <div>
                        <a href="<?= base_url('pesanan/saya') ?>/${id}" class="btn btn-sm btn-outline-primary me-2">Lihat</a>
                    </div>
                </div></div>`;
            } catch (e) {
                // skip
            }
        }
        el.innerHTML = html;
    }

    document.getElementById('clearHistory').addEventListener('click', function() {
        if (!confirm('Hapus semua riwayat pesanan di perangkat ini?')) return;
        localStorage.removeItem('orders');
        loadHistory();
    });

    loadHistory();
</script>

<?php $this->load->view('_partials/footer_pelanggan'); ?>