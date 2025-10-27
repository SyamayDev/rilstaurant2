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
            // === PERUBAHAN DI SINI ===
            {
                extend: 'print',
                exportOptions: {
                    // Tentukan kolom yang ingin di-print (indeks dimulai dari 0)
                    // 0: ID, 1: Total, 2: Status, 3: Tanggal
                    // Kolom 4 (Aksi) akan diabaikan.
                    columns: [ 0, 1, 2, 3 ]
                }
            }
            // === AKHIR PERUBAHAN ===
        ]
    });

    // Date range filtering
    $.fn.dataTable.ext.search.push(
        function( settings, data, dataIndex ) {
            var minVal = $('#min').val();
            var maxVal = $('#max').val();
            var dateVal = data[3]; // Ambil data tanggal dari kolom ke-4 (indeks 3)

            // Cek format tanggal dari tabel. Jika 'YYYY-MM-DD', parsing langsung.
            // Jika formatnya 'DD/MM/YYYY' atau lainnya, Anda perlu mengubah parsing di bawah.
            var date = new Date(dateVal);
            
            // Konversi min/max ke Date object. Tambahkan 'T00:00:00' agar konsisten
            var min = minVal ? new Date(minVal + "T00:00:00") : null;
            var max = maxVal ? new Date(maxVal + "T23:59:59") : null; // Set ke akhir hari

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

    // === PERUBAHAN: Delete button handler (pakai modal) ===
    var idToDelete = null; // Variabel untuk menyimpan ID yang akan dihapus
    var rowToDelete = null; // Variabel untuk menyimpan baris yang akan dihapus

    $('#laporanTable').on('click', '.delete-btn', function(e) {
        e.preventDefault();
        idToDelete = $(this).data('id');
        rowToDelete = $(this).parents('tr'); // Simpan baris
        
        // Tampilkan modal konfirmasi
        $('#deleteConfirmModal').modal('show');
    });

    // Handle klik tombol "Hapus" di modal
    $('#confirmDeleteBtn').on('click', function() {
        if (idToDelete && rowToDelete) {
            // Sembunyikan modal
            $('#deleteConfirmModal').modal('hide');
            
            // Di sini Anda bisa menambahkan logika AJAX untuk menghapus data di server
            // $.post('url/ke/controller_hapus', { id: idToDelete }, function(response) { ... });

            // Hapus baris dari tabel
            table.row(rowToDelete).remove().draw();
            
            // Reset variabel
            idToDelete = null; 
            rowToDelete = null;
        }
    });
    // === AKHIR PERUBAHAN ===
});
</script>
<?php $this->load->view('_partials/footer_admin'); ?>
