<?php $this->load->view('_partials/header_admin'); ?>
<div class="d-flex justify-content-between align-items-center">
    <h2>Dashboard</h2>
</div>

<div class="row mt-3">
    <div class="col-md-6">
        <div class="card p-3">
            <h5>Makanan Terbaik (Rating)</h5>
            <canvas id="topChart"></canvas>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card p-3">
            <h5>Penjualan (14 hari)</h5>
            <canvas id="salesChart"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const topLabels = <?= json_encode(array_map(function ($m) {
                            return $m->nama_menu;
                        }, $top_menus)) ?>;
    const topData = <?= json_encode(array_map(function ($m) {
                        return round($m->avg_rating ?: 0, 2);
                    }, $top_menus)) ?>;
    new Chart(document.getElementById('topChart'), {
        type: 'bar',
        data: {
            labels: topLabels,
            datasets: [{
                label: 'Rating',
                data: topData,
                backgroundColor: 'rgba(107,15,15,0.7)'
            }]
        }
    });

    const salesLabels = <?= json_encode(array_map(function ($s) {
                            return $s->tgl;
                        }, $sales)) ?>;
    const salesData = <?= json_encode(array_map(function ($s) {
                            return (float)$s->total_sum;
                        }, $sales)) ?>;
    new Chart(document.getElementById('salesChart'), {
        type: 'line',
        data: {
            labels: salesLabels,
            datasets: [{
                label: 'Total',
                data: salesData,
                borderColor: '#6b0f0f',
                backgroundColor: 'rgba(107,15,15,0.1)',
                fill: true
            }]
        }
    });
</script>

<?php $this->load->view('_partials/footer_admin'); ?>