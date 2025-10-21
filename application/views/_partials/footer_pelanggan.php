</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    #btnCart.shake {
        animation: shakeCart .4s ease-in-out forwards
    }

    @keyframes shakeCart {
        25% {
            transform: translateX(6px)
        }

        50% {
            transform: translateX(-4px)
        }

        75% {
            transform: translateX(2px)
        }

        100% {
            transform: translateX(0)
        }
    }
</style>
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