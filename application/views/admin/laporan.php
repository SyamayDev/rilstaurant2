<?php $this->load->view('_partials/header_admin'); ?>
<h2>Laporan Penjualan</h2>
<div class="row mb-3">
    <div class="col-md-3">
        <label for="min">Dari Tanggal</label>
        <input type="date" id="min" name="min" class="form-control">
    </div>
    <div class="col-md-3">
        <label for="max">Sampai Tanggal</label>
        <input type="date" id="max" name="max" class="form-control">
    </div>
</div>
<canvas id="salesChart" width="400" height="120"></canvas>
<div class="table-responsive-horizontal">
    <table id="laporanTable" class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Total</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pesanan as $p): ?>
                <tr>
                    <td><?= $p->id_pesanan ?></td>
                    <td><?= $p->total ?></td>
                    <td><?= $p->status ?></td>
                    <td><?= $p->tanggal ?></td>
                    <td>
                        <a href="#" class="btn btn-sm btn-danger delete-btn" data-id="<?= $p->id_pesanan ?>">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- TAMBAHAN: Modal Konfirmasi Hapus -->
<!-- Pastikan Anda sudah memuat CSS & JS Bootstrap agar ini berfungsi -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Anda yakin ingin menghapus data ini?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Hapus</button>
      </div>
    </div>
  </div>
</div>

<!-- Asumsi jQuery sudah dimuat di header -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- 
    TAMBAHAN: Script DataTables & Buttons yang diperlukan.
    Pastikan ini ada. Tombol print tidak akan muncul tanpanya.
-->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<!-- Anda mungkin juga butuh CSS untuk buttons: 
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css"> 
-->

<script>
document.addEventListener('DOMContentLoaded', function() {
    var table = $('#laporanTable').DataTable({
        responsive: true,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'print',
                exportOptions: {
                    columns: [ 0, 1, 2, 3 ] // Kolom yang akan dicetak
                },
                
                customize: function ( win ) {
                    
                    // 1. Hapus judul default jika ada
                    $(win.document.body).find('h1').remove();

                    // 2. Buat KOP SURAT
                    var kopSurat = `
                        <div style="display: flex; align-items: center; border-bottom: 3px solid #000; padding-bottom: 15px; margin-bottom: 30px;">
                            <div>
                                <img src="<?= base_url('assets/img/logo.png') ?>" 
                                     style="height:60px; width:auto; border-radius:6px; margin-right:20px; background:#fff; padding:3px; box-shadow:0 2px 6px rgba(0,0,0,.12)" 
                                     onerror="this.style.display='none'">
                            </div>
                            <div style="line-height: 1.4;">
                                <h2 style="margin: 0; font-size: 1.8rem; font-weight: bold; color: #6b0f0f;">Rilstaurant</h2>
                                <h3 style="margin: 0; font-size: 1.4rem; font-weight: 500;">Laporan Penjualan</h3>
                                <div style="font-size: 0.9rem; color: #333;">Jl. Kuliner Nusantara No. 1, Medan, Indonesia</div>
                            </div>
                        </div>
                    `;
                    
                    // 3. Tambahkan KOP SURAT ke bagian ATAS body jendela cetak
                    $(win.document.body).prepend(kopSurat);

                    // 4. Buat TANDA TANGAN (Footer)
                    
                    const today = new Date();
                    const tgl = today.toLocaleDateString('id-ID', { 
                        day: '2-digit', 
                        month: 'long', 
                        year: 'numeric' 
                    });

                    // === PERUBAHAN DI SINI (baris <div> terakhir) ===
                    var ttd = `
                        <div style="width: 100%; margin-top: 50px; page-break-inside: avoid;">
                            <div style="float: right; width: 280px; text-align: center;">
                                <div>Medan, ${tgl}</div>
                                <div style="margin-top: 5px;">Hormat kami,</div>
                                <br><br><br><br>
                                <div>( Pemilik / Manajer )</div>
                            </div>
                            <div style="clear: both;"></div>
                        </div>
                    `;
                    // === AKHIR PERUBAHAN ===

                    // 5. Tambahkan TANDA TANGAN ke bagian BAWAH body jendela cetak
                    $(win.document.body).append(ttd);

                    // 6. Styling Tambahan
                    $(win.document.body).css('font-size', '10pt');
                    $(win.document.body).find('table')
                        .addClass('compact')
                        .css('font-size', 'inherit');
                }
            }
        ]
    });

    // Date range filtering
    $.fn.dataTable.ext.search.push(
        function( settings, data, dataIndex ) {
            var minVal = $('#min').val();
            var maxVal = $('#max').val();
            var dateVal = data[3]; 
            var date = new Date(dateVal);
            var min = minVal ? new Date(minVal + "T00:00:00") : null;
            var max = maxVal ? new Date(maxVal + "T23:59:59") : null; 

            if (
                ( min === null && max === null ) ||
                ( min === null && date <= max ) ||
                ( min <= date && max === null ) ||
                ( min <= date && date <= max )
            ) {
                return true;
            }
            return false;
        }
    );

    // Refilter the table
    $('#min, #max').on('change', function () {
        table.draw();
    });

    // --- Chart.js ---
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

    // --- Delete button handler (pakai modal) ---
    var idToDelete = null; 
    var rowToDelete = null; 

    $('#laporanTable').on('click', '.delete-btn', function(e) {
        e.preventDefault();
        idToDelete = $(this).data('id');
        rowToDelete = $(this).parents('tr'); 
        
        $('#deleteConfirmModal').modal('show');
    });

    // Handle klik tombol "Hapus" di modal
    $('#confirmDeleteBtn').on('click', function() {
        if (idToDelete && rowToDelete) {
            $('#deleteConfirmModal').modal('hide');
            
            // $.post('url/ke/controller_hapus', { id: idToDelete }, function(response) { ... });

            table.row(rowToDelete).remove().draw();
            
            idToDelete = null; 
            rowToDelete = null;
        }
    });
});
</script>
<?php $this->load->view('_partials/footer_admin'); ?>
