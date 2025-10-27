<?php $this->load->view('_partials/header_admin'); ?>
<h2>Pesanan</h2>
<div class="table-responsive-horizontal">
    <table id="pesananTable" class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th style="display:none;">ID</th>
                <th>No</th>
                <th>Pelanggan</th>
                <th>Meja</th>
                <th>Total</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;
            foreach ($pesanan as $p):
                $pel = $this->db->get_where('pelanggan', ['id_pelanggan' => $p->id_pelanggan])->row();
            ?>
                <tr>
                    <td style="display:none;"><?= $p->id_pesanan ?></td>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($pel->nama ?? ('#' . $p->id_pelanggan), ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($pel->no_meja ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                    <td>Rp <?= number_format($p->total, 0, ',', '.') ?></td>
                    <td>
                        <?php
                        $badgeClass = in_array(strtolower($p->status), ['selesai', 'done', 'completed'])
                            ? 'bg-success'
                            : (in_array(strtolower($p->status), ['dimasak', 'cooking'])
                                ? 'bg-warning text-dark'
                                : 'bg-secondary');
                        ?>
                        <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($p->status, ENT_QUOTES, 'UTF-8') ?></span>
                    </td>
                    <td><?= $p->tanggal ?></td>
                    <td>
                        <a href="<?= base_url('pesanan/view/' . $p->id_pesanan) ?>" class="btn btn-sm btn-outline-primary">Lihat</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>

    </table>
</div>
<?php $this->load->view('_partials/footer_admin'); ?>

<script>
    $(document).ready(function() {
        $('#pesananTable').DataTable({
            "order": [
                [0, "desc"]
            ] // Kolom pertama (ID) urut menurun
        });
    });
</script>