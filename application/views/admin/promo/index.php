<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800"><?= $title ?></h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <a href="<?= base_url('admin_promo/add') ?>" class="btn btn-primary btn-sm">Tambah Promo Baru</a>
        </div>
        <div class="card-body">
            <?php if ($this->session->flashdata('success')) : ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $this->session->flashdata('success') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('error')) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $this->session->flashdata('error') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Promo</th>
                            <th>Deskripsi</th>
                            <th>Harga Paket</th>
                            <th>Gambar</th>
                            <th>Menu Items</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Berakhir</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        foreach ($promos as $promo) : ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $promo->nama_promo ?></td>
                                <td><?= character_limiter($promo->deskripsi_promo, 50) ?></td>
                                <td>Rp. <?= number_format($promo->harga_paket, 0, ',', '.') ?></td>
                                <td>
                                    <?php if ($promo->gambar_promo) : ?>
                                        <img src="<?= base_url('assets/uploads/' . $promo->gambar_promo) ?>" alt="<?= $promo->nama_promo ?>" width="100">
                                    <?php else : ?>
                                        Tidak ada gambar
                                    <?php endif; ?>
                                </td>
                                <td><?= $promo->menu_items_names ?></td>
                                <td><?= $promo->tanggal_mulai ?></td>
                                <td><?= $promo->tanggal_berakhir ?></td>
                                <td>
                                    <?php if ($promo->status == 'active') : ?>
                                        <span class="badge badge-success">Active</span>
                                    <?php else : ?>
                                        <span class="badge badge-danger">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= base_url('admin_promo/edit/' . $promo->id_promo) ?>" class="btn btn-info btn-sm">Edit</a>
                                    <a href="<?= base_url('admin_promo/delete/' . $promo->id_promo) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus promo ini?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>