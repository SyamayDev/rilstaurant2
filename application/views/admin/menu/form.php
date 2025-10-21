<?php $this->load->view('_partials/header_admin'); ?>
<h2><?= isset($menu) ? 'Edit Menu' : 'Tambah Menu' ?></h2>
<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
<?php endif; ?>
<form method="post" action="<?= isset($menu) ? base_url('menu/update/' . $menu->id_menu) : base_url('menu/create') ?>" enctype="multipart/form-data">
    <div class="mb-2"><input name="nama_menu" class="form-control" placeholder="Nama" value="<?= isset($menu) ? $menu->nama_menu : '' ?>"></div>
    <div class="mb-2"><input name="harga" class="form-control" placeholder="Harga" value="<?= isset($menu) ? $menu->harga : '' ?>"></div>
    <div class="mb-2">
        <select name="kategori" class="form-control">
            <option value="">-- Pilih Kategori --</option>
            <?php if (!empty($categories)): foreach ($categories as $c): ?>
                    <option value="<?= $c->id_kategori ?>" <?= isset($menu) && $menu->kategori == $c->id_kategori ? 'selected' : '' ?>><?= $c->nama_kategori ?></option>
            <?php endforeach;
            endif; ?>
        </select>
    </div>
    <div class="mb-2"><input name="stok" class="form-control" placeholder="Stok" value="<?= isset($menu) ? $menu->stok : '' ?>"></div>
    <div class="mb-2"><textarea name="deskripsi" class="form-control" placeholder="Deskripsi"><?= isset($menu) ? $menu->deskripsi : '' ?></textarea></div>
    <div class="mb-2"><textarea name="detail_lengkap" class="form-control" placeholder="Detail Lengkap"><?= isset($menu) ? $menu->detail_lengkap : '' ?></textarea></div>
    <div class="mb-2"><input type="file" name="gambar" accept="image/*" class="form-control"></div>
    <button class="btn btn-danger"><?= isset($menu) ? 'Perbarui' : 'Simpan' ?></button>
</form>
<?php $this->load->view('_partials/footer_admin'); ?>
<script src="https://cdn.ckeditor.com/ckeditor5/35.3.0/classic/ckeditor.js"></script>
<script>
    (function() {
        var el = document.querySelector('textarea[name="detail_lengkap"]');
        if (!el) return;
        ClassicEditor.create(el).catch(function(err) {
            console.error(err);
        });
    })();
</script>