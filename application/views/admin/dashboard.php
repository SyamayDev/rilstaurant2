<?php $this->load->view('_partials/header_admin'); ?>
<div class="d-flex justify-content-between align-items-center">
    <h2>Dashboard</h2>
</div>

<div class="row mt-3">
    <div class="col-md-6">
        <div class="card p-3">
            <h5>Makanan Terbaik (Rating)</h5>
            <div style="height:280px;">
                <canvas id="topChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card p-3">
            <h5>Penjualan (14 hari)</h5>
            <div style="height:280px;">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-6">
        <div class="card p-3">
            <h5>Pesanan per Status</h5>
            <div style="height:280px;">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card p-3">
            <h5>Menu Terlaris (Jumlah Pesanan)</h5>
            <div style="height:280px;">
                <canvas id="bestSellerChart"></canvas>
            </div>
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
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    suggestedMax: Math.max(...topData, 5)
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
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
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    // suggest a max based on data to avoid excessive stretching
                    suggestedMax: Math.max(...salesData, 10)
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // --- New chart: Pesanan per Status ---
    const statusLabels = <?= isset($orders_by_status) ? json_encode(array_map(function ($o) {
                                return $o->status;
                            }, $orders_by_status)) : json_encode(['pending', 'diproses', 'dikirim', 'selesai']) ?>;
    const statusData = <?= isset($orders_by_status) ? json_encode(array_map(function ($o) {
                            return (int)$o->count;
                        }, $orders_by_status)) : json_encode([5, 12, 8, 20]) ?>;
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: statusLabels,
            datasets: [{
                data: statusData,
                backgroundColor: ['#ffc107', '#fd7e14', '#0dcaf0', '#198754']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // --- New chart: Menu Terlaris ---
    const bestLabels = <?= isset($top_sold_menus) ? json_encode(array_map(function ($m) {
                            return $m->nama_menu;
                        }, $top_sold_menus)) : json_encode(array_slice(array_map(function ($m) {
                            return $m->nama_menu;
                        }, $top_menus), 0, 5)) ?>;
    const bestData = <?= isset($top_sold_menus) ? json_encode(array_map(function ($m) {
                            return (int)$m->qty;
                        }, $top_sold_menus)) : json_encode(array_slice(array_map(function ($m) {
                            return (int)($m->jumlah_pesanan ?? 0);
                        }, $top_menus), 0, 5)) ?>;
    new Chart(document.getElementById('bestSellerChart'), {
        type: 'bar',
        data: {
            labels: bestLabels,
            datasets: [{
                label: 'Jumlah',
                data: bestData,
                backgroundColor: 'rgba(40,167,69,0.7)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>

<?php $this->load->view('_partials/footer_admin'); ?>