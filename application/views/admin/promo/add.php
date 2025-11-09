<?php $this->load->view('_partials/header_admin'); ?>

<div class="container mt-4">
    <h1 class="mb-4">Tambah Promo Baru</h1>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger" role="alert">
            <?= $this->session->flashdata('error') ?>
        </div>
    <?php endif; ?>

    <?= validation_errors('<div class="alert alert-danger">', '</div>'); ?>

    <?= form_open_multipart('admin_promo/add'); ?>
        <div class="mb-3">
            <label for="nama_promo" class="form-label">Nama Promo</label>
            <input type="text" class="form-control" id="nama_promo" name="nama_promo" value="<?= set_value('nama_promo') ?>" required>
        </div>

        <div class="mb-3">
            <label for="deskripsi_promo" class="form-label">Deskripsi Promo</label>
            <textarea class="form-control" id="deskripsi_promo" name="deskripsi_promo" rows="3"><?= set_value('deskripsi_promo') ?></textarea>
        </div>

        <div class="mb-3">
            <label for="harga_paket" class="form-label">Harga Paket</label>
            <input type="number" class="form-control" id="harga_paket" name="harga_paket" value="<?= set_value('harga_paket') ?>" required>
        </div>

        <div class="mb-3">
            <label for="gambar_promo" class="form-label">Gambar Promo</label>
            <input type="file" class="form-control" id="gambar_promo" name="gambar_promo">
            <small class="form-text text-muted">Ukuran maksimal 2MB. Format: JPG, PNG, JPEG, GIF, WEBP.</small>
        </div>

        <div class="mb-3">
            <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
            <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" value="<?= set_value('tanggal_mulai') ?>" required>
        </div>

        <div class="mb-3">
            <label for="tanggal_berakhir" class="form-label">Tanggal Berakhir</label>
            <input type="date" class="form-control" id="tanggal_berakhir" name="tanggal_berakhir" value="<?= set_value('tanggal_berakhir') ?>" required>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="status" name="status" value="1" <?= set_checkbox('status', '1', TRUE) ?>>
            <label class="form-check-label" for="status">Aktif</label>
        </div>

        <h4 class="mt-4">Pilih Item Menu untuk Promo</h4>
        <div id="menu-items-container">
            <?php if (set_value('menu_items')): ?>
                <?php foreach (set_value('menu_items') as $key => $menu_id): ?>
                    <div class="row mb-2 menu-item-row">
                        <div class="col-md-6">
                            <select class="form-select menu-select" name="menu_items[]" required>
                                <option value="">Pilih Menu</option>
                                <?php foreach ($menus as $menu): ?>
                                    <option value="<?= $menu->id_menu ?>" <?= ($menu->id_menu == $menu_id) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($menu->nama_menu) ?> (Rp <?= number_format($menu->harga, 0, ',', '.') ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="number" class="form-control quantity-input" name="quantities[]" value="<?= set_value('quantities['.$key.']') ?>" min="1" required>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger remove-menu-item">Hapus</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="row mb-2 menu-item-row">
                    <div class="col-md-6">
                        <select class="form-select menu-select" name="menu_items[]" required>
                            <option value="">Pilih Menu</option>
                            <?php foreach ($menus as $menu): ?>
                                <option value="<?= $menu->id_menu ?>">
                                    <?= htmlspecialchars($menu->nama_menu) ?> (Rp <?= number_format($menu->harga, 0, ',', '.') ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="number" class="form-control quantity-input" name="quantities[]" value="1" min="1" required>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger remove-menu-item">Hapus</button>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <button type="button" class="btn btn-success mt-2" id="add-menu-item">Tambah Item Menu</button>

        <button type="submit" class="btn btn-primary mt-4">Simpan Promo</button>
        <a href="<?= base_url('admin_promo') ?>" class="btn btn-secondary mt-4">Batal</a>
    <?= form_close(); ?>
</div>

<?php $this->load->view('_partials/footer_admin'); ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const menuItemsContainer = document.getElementById('menu-items-container');
        const addMenuItemButton = document.getElementById('add-menu-item');

        addMenuItemButton.addEventListener('click', function() {
            const newMenuItemRow = document.createElement('div');
            newMenuItemRow.classList.add('row', 'mb-2', 'menu-item-row');
            newMenuItemRow.innerHTML = `
                <div class="col-md-6">
                    <select class="form-select menu-select" name="menu_items[]" required>
                        <option value="">Pilih Menu</option>
                        <?php foreach ($menus as $menu): ?>
                            <option value="<?= $menu->id_menu ?>">
                                <?= htmlspecialchars($menu->nama_menu) ?> (Rp <?= number_format($menu->harga, 0, ',', '.') ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="number" class="form-control quantity-input" name="quantities[]" value="1" min="1" required>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger remove-menu-item">Hapus</button>
                </div>
            `;
            menuItemsContainer.appendChild(newMenuItemRow);
        });

        menuItemsContainer.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-menu-item')) {
                if (menuItemsContainer.children.length > 1) { // Ensure at least one item remains
                    e.target.closest('.menu-item-row').remove();
                } else {
                    alert('Minimal harus ada satu item menu.');
                }
            }
        });
    });
</script>
