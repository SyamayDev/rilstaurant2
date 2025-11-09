<?php $this->load->view('_partials/header_admin'); ?>

<style>
    .settings-container {
        background-color: #f9f9f9;
        padding: 2rem;
        border-radius: 15px;
    }

    .settings-section {
        background-color: #fff;
        padding: 2rem;
        border-radius: 10px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .settings-section h3 {
        margin-bottom: 1.5rem;
        font-weight: 600;
        border-bottom: 2px solid #6b0f0f;
        padding-bottom: 0.5rem;
        color: #6b0f0f;
    }

    .form-group label {
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .form-group .form-control,
    .form-group .form-control-color {
        border-radius: 8px;
    }

    .img-preview {
        margin-top: 1rem;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .color-input-group {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
</style>

<div class="settings-container">
    <h1 class="text-center mb-4">Pengaturan Website</h1>

    <?php echo form_open_multipart('admin_settings/update'); ?>

    <!-- Bagian Branding & Tampilan -->
    <div class="settings-section">
        <h3>Branding & Tampilan</h3>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="logo">Logo</label>
                    <input type="file" name="logo" class="form-control">
                    <img src="<?= base_url('assets/img/' . $settings->logo) ?>" width="100" class="img-preview">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="all_categories_icon">Ikon "Semua Kategori"</label>
                    <input type="file" name="all_categories_icon" class="form-control">
                    <img src="<?= base_url('assets/img/' . $settings->all_categories_icon) ?>" width="100" class="img-preview">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="banner1">Banner 1</label>
                    <input type="file" name="banner1" class="form-control">
                    <img src="<?= base_url('assets/img/' . $settings->banner1) ?>" width="200" class="img-preview">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="banner2">Banner 2</label>
                    <input type="file" name="banner2" class="form-control">
                    <img src="<?= base_url('assets/img/' . $settings->banner2) ?>" width="200" class="img-preview">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="banner3">Banner 3</label>
                    <input type="file" name="banner3" class="form-control">
                    <img src="<?= base_url('assets/img/' . $settings->banner3) ?>" width="200" class="img-preview">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="background_image">Gambar Latar Belakang</label>
                    <input type="file" name="background_image" class="form-control">
                    <?php if (!empty($settings->background_image)): ?>
                        <img src="<?= base_url('assets/img/' . $settings->background_image) ?>" width="200" class="img-preview">
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Bagian Informasi Restoran -->
    <div class="settings-section">
        <h3>Informasi Restoran</h3>
        <div class="form-group mb-3">
            <label for="alamat">Alamat</label>
            <textarea name="alamat" class="form-control"><?= $settings->alamat ?></textarea>
        </div>
        <div class="form-group mb-3">
            <label for="google_maps_link">Link Google Maps (Embed)</label>
            <textarea name="google_maps_link" class="form-control"><?= $settings->google_maps_link ?></textarea>
        </div>

        <h5>Jam Operasional</h5>
        <div class="row">
            <?php
            $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
            foreach ($days as $day) {
                $db_field = 'jam_' . strtolower($day);
            ?>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="<?= $db_field ?>"><?= $day ?></label>
                        <input type="text" name="<?= $db_field ?>" class="form-control" value="<?= htmlspecialchars($settings->$db_field ?? 'Tutup') ?>" placeholder="Contoh: 10:00 - 22:00">
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="form-group mb-3">
            <label for="jumlah_meja">Jumlah Meja</label>
            <input type="number" name="jumlah_meja" class="form-control" value="<?= htmlspecialchars($settings->jumlah_meja ?? '20') ?>" placeholder="Contoh: 25">
            <small class="form-text text-muted">
                Jumlah total meja yang tersedia di restoran. Ini akan menentukan nomor meja yang bisa dipilih pelanggan.
            </small>
        </div>
    </div>

    <div class="text-center">
        <button type="submit" class="btn btn-danger btn-lg">Simpan Perubahan</button>
    </div>

    <?php echo form_close(); ?>
</div>

<?php $this->load->view('_partials/footer_admin'); ?>