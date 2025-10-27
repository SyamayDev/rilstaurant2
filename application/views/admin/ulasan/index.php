<?php $this->load->view('_partials/header_admin'); ?>
<h2>Moderasi Ulasan</h2>
<ul class="nav nav-tabs mb-3">
    <li class="nav-item"><a class="nav-link <?= ($_SERVER['REQUEST_URI'] ?? '') === '/admin_ulasan' ? 'active' : '' ?>" href="<?= base_url('admin_ulasan/index/all') ?>">Semua</a></li>
    <li class="nav-item"><a class="nav-link" href="<?= base_url('admin_ulasan/index/pending') ?>">Pending</a></li>
    <li class="nav-item"><a class="nav-link" href="<?= base_url('admin_ulasan/index/disetujui') ?>">Disetujui</a></li>
    <li class="nav-item"><a class="nav-link" href="<?= base_url('admin_ulasan/index/ditolak') ?>">Ditolak</a></li>
</ul>

<div class="table-responsive">
    <table id="ulasanTable" class="table table-bordered">
        <thead>
            <tr>
                <th>Menu</th>
                <th>Pelanggan</th>
                <th>Rating</th>
                <th>Komentar</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ulasan as $u): ?>
                <?php
                $menu = $this->db->get_where('menu', ['id_menu' => $u->id_menu])->row();
                ?>
                <tr id="row-<?= $u->id_ulasan ?>">
                    <td><?= $menu ? $menu->nama_menu : '-' ?></td>
                    <td><?= $u->nama_pelanggan ?></td>
                    <td><?= $u->rating ?></td>
                    <td><?= $u->komentar ?></td>
                    <td id="status-<?= $u->id_ulasan ?>"><?= $u->status_ulasan ?></td>
                    <td>
                        <?php if ($u->status_ulasan !== 'disetujui'): ?><button class="btn btn-sm btn-success approve" data-id="<?= $u->id_ulasan ?>">Setujui</button><?php endif; ?>
                        <?php if ($u->status_ulasan !== 'ditolak'): ?><button class="btn btn-sm btn-warning reject" data-id="<?= $u->id_ulasan ?>">Tolak</button><?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php $this->load->view('_partials/footer_admin'); ?>

<script>
$(document).ready(function() {
    $('#ulasanTable').DataTable();
});

    document.querySelectorAll('.approve').forEach(b => b.addEventListener('click', async () => {
        const id = b.dataset.id;
        const res = await fetch('<?= base_url('admin_ulasan/set_status') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'id=' + id + '&status=disetujui'
        });
        const j = await res.json();
        if (j.success) {
            document.getElementById('status-' + id).innerText = 'disetujui';
        }
    }));
    document.querySelectorAll('.reject').forEach(b => b.addEventListener('click', async () => {
        const id = b.dataset.id;
        const res = await fetch('<?= base_url('admin_ulasan/set_status') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'id=' + id + '&status=ditolak'
        });
        const j = await res.json();
        if (j.success) {
            document.getElementById('status-' + id).innerText = 'ditolak';
        }
    }));
</script>