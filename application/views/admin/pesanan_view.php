<?php $this->load->view('_partials/header_admin'); ?>
<div class="card">
    <div class="card-body">
        <h3>Detail Pesanan #<?= $pesanan->id_pesanan ?></h3>
        <div class="mb-3">
            <strong>Pelanggan:</strong> <?php $pel = $this->db->get_where('pelanggan', ['id_pelanggan' => $pesanan->id_pelanggan])->row();
                                        echo htmlspecialchars($pel->nama ?? '-', ENT_QUOTES, 'UTF-8'); ?>
            &nbsp; <strong>Meja:</strong> <?= htmlspecialchars($pel->no_meja ?? '-', ENT_QUOTES, 'UTF-8') ?>
        </div>
        <div class="mb-3">
            <table class="table">
                <thead>
                    <tr>
                        <th>Menu</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($detail as $d): ?>
                        <tr>
                            <td><?= htmlspecialchars($d->nama_menu, ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= (int)$d->jumlah ?></td>
                            <td>Rp <?= number_format($d->subtotal, 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <div><strong>Tanggal:</strong> <?= $pesanan->tanggal ?></div>
            <div class="fw-bold">Total: Rp <?= number_format($pesanan->total, 0, ',', '.') ?></div>
        </div>

        <hr>
        <form method="post" action="<?= base_url('pesanan/update_status/' . $pesanan->id_pesanan) ?>">
            <div class="row g-2 align-items-center">
                <div class="col-auto">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <?php
                        // DB enum supports: pending, diproses, dikirim, selesai, batal
                        $options = [
                            'pending' => 'Pending',
                            'diproses' => 'Diproses / Dimasak',
                            'dikirim' => 'Dikirim / Diantar',
                            'selesai' => 'Selesai',
                            'batal' => 'Batal'
                        ];
                        foreach ($options as $val => $label): ?>
                            <option value="<?= $val ?>" <?= ($pesanan->status == $val) ? 'selected' : '' ?>><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="">
                    <button class="btn btn-danger">Perbarui Status</button>
                </div>
            </div>
        </form>

        <div class="mt-3">
            <a href="<?= base_url('pesanan') ?>" class="btn btn-outline-secondary">Kembali</a>
            <?php
            // show receipt button when status is 'diproses' (dimasak) or 'selesai'
            $st = strtolower((string)$pesanan->status);
            if (in_array($st, ['diproses', 'dimasak', 'selesai'])): ?>
                <a href="<?= base_url('pesanan/receipt/' . $pesanan->id_pesanan) ?>" target="_blank" class="btn btn-success ms-2">Cetak Struk</a>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $this->load->view('_partials/footer_admin'); ?>