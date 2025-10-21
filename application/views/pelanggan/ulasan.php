<?php $this->load->view('_partials/header_pelanggan'); ?>
<h3>Ulasan</h3>
<form method="post" action="<?= base_url('ulasan/submit') ?>">
    <input type="hidden" name="id_menu" value="<?= $this->input->get('id_menu') ?: '' ?>">
    <input type="hidden" name="id_pesanan" value="<?= $this->input->get('id_pesanan') ?: '' ?>">
    <div class="mb-3"><input name="nama" class="form-control" placeholder="Nama"></div>
    <div class="mb-3">
        <select name="rating" class="form-control">
            <option value="">Pilih Rating</option>
            <?php for ($i = 5; $i >= 1; $i--): ?>
                <option value="<?= $i ?>"><?= $i ?> ⭐</option>
            <?php endfor; ?>
        </select>
    </div>
    <div class="mb-3"><textarea name="komentar" class="form-control" placeholder="Komentar"></textarea></div>
    <button class="btn btn-danger">Kirim</button>
</form>
<?php $this->load->view('_partials/footer_pelanggan'); ?>