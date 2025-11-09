<?php $this->load->view('_partials/header_admin'); ?>

<div class="container mt-4">
    <h1 class="mb-4">Kirim Email ke Pelanggan</h1>

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

    <?= validation_errors('<div class="alert alert-danger">', '</div>'); ?>

    <?= form_open('admin_email/send'); ?>
        <div class="mb-3">
            <label for="recipients" class="form-label">Penerima Email</label>
            <select class="form-select" id="recipients" name="recipients[]" multiple required>
                <?php if (!empty($customers)): ?>
                    <?php foreach ($customers as $customer): ?>
                        <?php if (!empty($customer->email)): ?>
                            <option value="<?= $customer->id_pelanggan ?>"><?= htmlspecialchars($customer->nama) ?> (<?= htmlspecialchars($customer->email) ?>)</option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="" disabled>Tidak ada pelanggan dengan email terdaftar.</option>
                <?php endif; ?>
            </select>
            <small class="form-text text-muted">Pilih satu atau lebih pelanggan yang akan menerima email.</small>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="send_to_all" name="send_to_all" value="1">
            <label class="form-check-label" for="send_to_all">Kirim ke Semua Pelanggan</label>
        </div>

        <div class="mb-3">
            <label for="subject" class="form-label">Subjek Email</label>
            <input type="text" class="form-control" id="subject" name="subject" value="<?= set_value('subject') ?>" required>
        </div>

        <div class="mb-3">
            <label for="message" class="form-label">Isi Email</label>
            <textarea class="form-control" id="message" name="message" rows="10" required><?= set_value('message') ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Kirim Email</button>
    <?= form_close(); ?>
</div>

<?php $this->load->view('_partials/footer_admin'); ?>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- CKEditor CDN -->
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script>
    $(document).ready(function() {
        $('#recipients').select2({
            placeholder: "Pilih pelanggan",
            allowClear: true
        });

        // Initialize CKEditor
        CKEDITOR.replace('message', {
            height: 300, // Set the height of the editor
            filebrowserUploadUrl: '<?= base_url('admin_email/upload_ckeditor_image') ?>', // Optional: for image uploads
            filebrowserUploadMethod: 'form'
        });

        // Handle "Send to All" checkbox
        $('#send_to_all').on('change', function() {
            if ($(this).is(':checked')) {
                $('#recipients').prop('disabled', true);
                $('#recipients').val(null).trigger('change'); // Clear selected options
            } else {
                $('#recipients').prop('disabled', false);
            }
        });
    });
</script>
