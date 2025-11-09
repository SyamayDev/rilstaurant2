<?php $this->load->view('_partials/header_admin'); ?>

<div class="container mt-4">
    <h1 class="mb-4">Manajemen Promo</h1>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success" role="alert">
            <?= $this->session->flashdata('success') ?>
        </div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger" role="alert">
            <?= $this->session->flashdata('error') ?>
        </div>
    <?php endif; ?>

    <a href="<?= base_url('admin_promo/add') ?>" class="btn btn-primary mb-3">Tambah Promo Baru</a>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Promo</th>
                    <th>Deskripsi</th>
                    <th>Harga Paket</th>
                    <th>Gambar</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Berakhir</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($promos)): ?>
                    <?php foreach ($promos as $promo): ?>
                        <tr>
                            <td><?= $promo->id_promo ?></td>
                            <td><?= htmlspecialchars($promo->nama_promo) ?></td>
                            <td><?= word_limiter(htmlspecialchars($promo->deskripsi_promo), 10) ?></td>
                            <td>Rp <?= number_format($promo->harga_paket, 0, ',', '.') ?></td>
                            <td>
                                <?php if ($promo->gambar_promo): ?>
                                    <img src="<?= base_url('assets/uploads/' . $promo->gambar_promo) ?>" alt="<?= htmlspecialchars($promo->nama_promo) ?>" width="100">
                                <?php else: ?>
                                    Tidak ada gambar
                                <?php endif; ?>
                            </td>
                            <td><?= $promo->tanggal_mulai ?></td>
                            <td><?= $promo->tanggal_berakhir ?></td>
                            <td>
                                <span class="badge bg-<?= ($promo->status == 'active') ? 'success' : 'danger' ?>">
                                    <?= $promo->status ?>
                                </span>
                            </td>
                            <td>
                                <a href="<?= base_url('admin_promo/edit/' . $promo->id_promo) ?>" class="btn btn-sm btn-warning">Edit</a>
                                <a href="<?= base_url('admin_promo/delete/' . $promo->id_promo) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Anda yakin ingin menghapus promo ini?')">Hapus</a>
                                <a href="<?= base_url('admin_promo/toggle_status/' . $promo->id_promo) ?>" class="btn btn-sm btn-info">
                                    <?= ($promo->status == 'active') ? 'Nonaktifkan' : 'Aktifkan' ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center">Tidak ada promo yang tersedia.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php $this->load->view('_partials/footer_admin'); ?>
