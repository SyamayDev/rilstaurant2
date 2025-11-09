<?php $settings = get_website_settings(); ?>
</div>
</div>
<style>
    body {
        background: #f8f9fa;
        /* Warna latar abu-abu muda */
    }

    .sidebar {
        background: #6b0f0f;
        /* Warna sidebar merah marun */
    }

    .content {
        min-height: 100vh;
    }

    /* make tables horizontally scrollable on small screens */
    .table-responsive-horizontal {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Sidebar toggle: slide-in on small screens
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    const sidebarClose = document.getElementById('sidebarClose');
    if (sidebarToggle) sidebarToggle.addEventListener('click', () => {
        if (window.innerWidth <= 768) sidebar.classList.toggle('show');
    });
    if (sidebarClose) sidebarClose.addEventListener('click', () => {
        if (window.innerWidth <= 768) sidebar.classList.remove('show');
    });
    // close sidebar when clicking outside on mobile
    document.addEventListener('click', (e) => {
        if (window.innerWidth <= 768 && sidebar.classList.contains('show')) {
            if (!sidebar.contains(e.target) && !document.getElementById('sidebarToggle').contains(e.target)) {
                sidebar.classList.remove('show');
            }
        }
    });
</script>
<?php if ($this->session->flashdata('success')): ?>
    <script>
        Swal.fire('Sukses', '<?= $this->session->flashdata('success') ?>', 'success')
    </script>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
    <script>
        Swal.fire('Error', '<?= $this->session->flashdata('error') ?>', 'error')
    </script>
<?php endif; ?>


</body>

</html>