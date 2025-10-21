<?php $this->load->view('_partials/header_admin'); ?>
<h2>Laporan Penjualan</h2>
<canvas id="salesChart" width="400" height="120"></canvas>
<div class="table-responsive-horizontal">
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Total</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pesanan as $p): ?>
                <tr>
                    <td><?= $p->id_pesanan ?></td>
                    <td><?= $p->total ?></td>
                    <td><?= $p->status ?></td>
                    <td><?= $p->tanggal ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart');
    const data = {
        labels: <?= json_encode(array_map(function ($p) {
                    return $p->tanggal;
                }, $pesanan)) ?>,
        datasets: [{
            label: 'Total',
            data: <?= json_encode(array_map(function ($p) {
                        return (float)$p->total;
                    }, $pesanan)) ?>,
            backgroundColor: 'rgba(107,15,15,0.6)'
        }]
    };
    new Chart(ctx, {
        type: 'bar',
        data
    });
</script>
<?php $this->load->view('_partials/footer_admin'); ?>