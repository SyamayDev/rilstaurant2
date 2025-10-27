<?php $this->load->view('_partials/header_admin'); ?>
<h2>Kelola Kategori</h2>
<div class="mb-3">
    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#createKategoriModal">Tambah Kategori</button>
</div>
<div class="card">
    <div class="card-body">
        <table id="kategoriTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>Gambar</th>
                    <th></th>Nama</th>
                    <th>Slug</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $c): ?>
                    <tr>
                        <td style="width:120px"><img src="<?= base_url('assets/uploads/' . ($c->gambar ?? 'no-image.png')) ?>" style="max-width:80px;border-radius:8px"></td>
                        <td><?= $c->nama_kategori ?></td>
                        <td><?= $c->slug ?></td>
                        <td>
                            <button class="btn btn-sm btn-secondary edit-kategori" data-id="<?= $c->id_kategori ?>">Edit</button>
                            <a href="<?= base_url('kategori/delete/' . $c->id_kategori) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus kategori?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createKategoriModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="<?= base_url('kategori/create') ?>" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kategori</h5><button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2"><input name="nama_kategori" class="form-control" placeholder="Nama Kategori"></div>
                    <div class="mb-2"><input type="file" name="gambar" accept="image/*" class="form-control"></div>
                </div>
                <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button class="btn btn-danger">Simpan</button></div>
            </form>
        </div>
    </div>
</div>

<!-- Edit modal (populated via JS) -->
<div class="modal fade" id="editKategoriModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editKategoriForm" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Kategori</h5><button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_kategori" id="edit-id">
                    <div class="mb-2"><input id="edit-nama" name="nama_kategori" class="form-control"></div>
                    <div class="mb-2"><input type="file" name="gambar" accept="image/*" class="form-control"></div>
                </div>
                <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button class="btn btn-danger">Simpan</button></div>
            </form>
        </div>
    </div>
</div>

<?php $this->load->view('_partials/footer_admin'); ?>

<script>
$(document).ready(function() {
    $('#kategoriTable').DataTable();
});

    document.querySelectorAll('.edit-kategori').forEach(b => b.addEventListener('click', async () => {
        const id = b.dataset.id;
        const res = await fetch('<?= base_url('kategori/edit/') ?>' + id);
        const data = await res.json();
        document.getElementById('edit-id').value = data.id_kategori;
        document.getElementById('edit-nama').value = data.nama_kategori;
        document.getElementById('editKategoriForm').action = '<?= base_url('kategori/update/') ?>' + id;
        new bootstrap.Modal(document.getElementById('editKategoriModal')).show();
    }));
</script>