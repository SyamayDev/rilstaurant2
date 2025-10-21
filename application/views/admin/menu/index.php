<?php $this->load->view('_partials/header_admin'); ?>
<h2>Kelola Menu</h2>
<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
<?php endif; ?>
<div class="mb-3">
    <a href="<?= base_url('menu/add') ?>" class="btn btn-danger">Tambah Menu</a>
</div>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($menus as $m): ?>
                        <tr>
                            <td style="width:120px"><img src="<?= base_url('assets/uploads/' . ($m->gambar ?? 'no-image.png')) ?>" style="max-width:100px"></td>
                            <td><?= $m->nama_menu ?></td>
                            <td><?= number_format($m->harga, 0, ',', '.') ?></td>
                            <td><?= isset($m->nama_kategori) && $m->nama_kategori ? $m->nama_kategori : (isset($m->kategori) ? $m->kategori : '-') ?></td>
                            <td><?= $m->stok ?></td>
                            <td>
                                <a href="<?= base_url('menu/edit/' . $m->id_menu) ?>" class="btn btn-sm btn-secondary">Edit</a>
                                <a href="<?= base_url('menu/delete/' . $m->id_menu) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus menu?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $this->load->view('_partials/footer_admin'); ?>