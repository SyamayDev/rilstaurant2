<?php $this->load->view('_partials/header_admin'); ?>
<h2>Kelola Menu</h2>
<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
<?php endif; ?>
<?php if ($this->session->flashdata('info')): ?>
    <div class="alert alert-info"><?= $this->session->flashdata('info') ?></div>
<?php endif; ?>
<div class="row">
    <div class="col-md-6">
        <h5>Tambah Menu</h5>
        <?php if (!$this->db->field_exists('kategori', 'menu')): ?>
            <div class="alert alert-warning">Kolom <strong>menu.kategori</strong> tidak ditemukan di database. Pilih kategori tidak akan tersimpan sampai kolom ini dibuat.
                <form method="post" action="<?= base_url('menu/add_kategori_column') ?>" class="d-inline">
                    <button class="btn btn-sm btn-outline-danger ms-2">Buat Kolom kategori</button>
                </form>
            </div>
        <?php endif; ?>
        <form method="post" action="<?= base_url('menu/create') ?>" enctype="multipart/form-data">
            <div class="mb-2"><input name="nama_menu" class="form-control" placeholder="Nama"></div>
            <div class="mb-2"><input name="harga" class="form-control" placeholder="Harga"></div>
            <div class="mb-2">
                <select name="kategori" class="form-control">
                    <option value="">-- Pilih Kategori --</option>
                    <?php if (!empty($categories)): foreach ($categories as $c): ?>
                            <option value="<?= $c->id_kategori ?>"><?= $c->nama_kategori ?></option>
                    <?php endforeach;
                    endif; ?>
                </select>
            </div>
            <div class="mb-2"><input name="stok" class="form-control" placeholder="Stok"></div>
            <div class="mb-2"><textarea name="deskripsi" class="form-control" placeholder="Deskripsi"></textarea></div>
            <div class="mb-2"><textarea name="detail_lengkap" class="form-control" placeholder="Detail Lengkap"></textarea></div>
            <div class="mb-2"><input type="file" name="gambar" accept="image/*" class="form-control"></div>
            <button class="btn btn-light text-danger">Simpan</button>
        </form>
    </div>
    <div class="col-md-6">
        <h5>Daftar Menu</h5>
        <div class="table-responsive-horizontal">
            <table id="menuTable" class="table table-bordered">
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
                            <td style="width:100px"><img src="<?= base_url('assets/uploads/' . ($m->gambar ?? 'no-image.png')) ?>" style="max-width:80px" /></td>
                            <td><?= $m->nama_menu ?></td>
                            <td><?= number_format($m->harga, 0, ',', '.') ?></td>
                            <td><?= $m->kategori ?></td>
                            <td><?= $m->stok ?></td>
                            <td>
                                <button class="btn btn-sm btn-secondary edit-btn" data-id="<?= $m->id_menu ?>">Edit</button>
                                <a href="<?= base_url('menu/delete/' . $m->id_menu) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus menu?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- CKEditor 5 -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
$(document).ready(function() {
    const $table = $('#menuTable');
    if ($table.length > 0) {
        console.log('✅ DataTable siap diinisialisasi');
        $table.DataTable({
            responsive: true,
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "›",
                    previous: "‹"
                }
            }
        });
    } else {
        console.warn('⚠️ Tabel #menuTable belum dimuat.');
    }
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.edit-btn').forEach(b => b.addEventListener('click', async () => {
        const id = b.getAttribute('data-id');
        const res = await fetch('<?= base_url('menu/edit/') ?>' + id);
        const data = await res.json();
        document.getElementById('edit-id').value = data.id_menu;
        document.getElementById('edit-nama').value = data.nama_menu;
        document.getElementById('edit-harga').value = data.harga;
        document.getElementById('edit-kategori').value = data.kategori;
        document.getElementById('edit-stok').value = data.stok;
        document.getElementById('edit-deskripsi').value = data.deskripsi;
        document.getElementById('edit-detail').value = data.detail_lengkap || '';
        const form = document.getElementById('editForm');
        form.action = '<?= base_url('menu/update/') ?>' + id;
        new bootstrap.Modal(document.getElementById('editModal')).show();
    }));

    const createTextarea = document.querySelector('textarea[name="detail_lengkap"]');
    if (createTextarea) {
        ClassicEditor.create(createTextarea).catch(e => console.error(e));
    }
    // edit modal editor will be created on demand when modal shown
    let editEditor;
    document.getElementById('editModal').addEventListener('shown.bs.modal', function() {
        const el = document.getElementById('edit-detail');
        if (!el) return;
        if (!editEditor) {
            ClassicEditor.create(el).then(ed => {
                editEditor = ed;
            }).catch(e => console.error(e));
        } else {
            // sync textarea value into editor
            editEditor.setData(el.value || '');
        }
    });
});
</script>
<?php $this->load->view('_partials/footer_admin'); ?>