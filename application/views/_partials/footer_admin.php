    </div>
    </div>
    <style>
        body {
            background: linear-gradient(180deg, #f7eaea 0%, #fffaf8 100%);
        }

        .sidebar {
            background: linear-gradient(180deg, #6b0f0f 0%, #4e0b0b 100%);
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