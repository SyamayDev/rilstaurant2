<?php $this->load->view('_partials/header_pelanggan'); ?>

<div class="container mt-4">
    <h3 class="mb-4">Keranjang</h3>
    <div id="cart-list"></div>

    <div class="mt-4 d-flex justify-content-between align-items-center border-top pt-3">
        <div class="fw-bold fs-5">Total: <span id="totalPrice">Rp 0</span></div>
        <button class="btn btn-danger px-4" id="openCheckout">Checkout</button>
    </div>
</div>

<!-- Checkout Modal -->
<div class="modal fade" id="checkoutModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="post" id="modalCheckoutForm" action="<?= base_url('pesanan/checkout') ?>">
            <input type="hidden" name="cart" id="cart-input">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Checkout Pemesanan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Pemesan</label>
                        <input class="form-control" name="nama" placeholder="Nama" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">No Meja</label>
                        <select class="form-select" name="no_meja" id="select-meja" required>
                            <option value="">Pilih nomor meja...</option>
                        </select>
                        <div class="form-text">Pilih meja yang tersedia. Meja yang sedang dipakai tidak akan muncul.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email (Opsional)</label>
                        <input class="form-control" name="email" placeholder="Email (untuk info diskon)">
                    </div>
                    <div class="fw-bold text-end mt-3">
                        Total: <span id="modalTotalPrice">Rp 0</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-danger" id="submitOrder">Pesan Sekarang</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function formatRupiah(num) {
        return 'Rp ' + num.toLocaleString('id-ID');
    }

    function render() {
        const cart = JSON.parse(localStorage.getItem('cart') || '[]');
        const el = document.getElementById('cart-list');
        if (!cart.length) {
            el.innerHTML = '<p class="text-muted">Keranjang masih kosong</p>';
        } else {
            let html = '<div class="list-group shadow-sm">';
            cart.forEach((c, idx) => {
                html += `
                <div class="list-group-item d-flex align-items-center justify-content-between">
                    
                    <div>
                        <div class="fw-semibold">${c.nama_menu}</div>
                        <small class="text-muted">Rp ${Number(c.harga).toLocaleString('id-ID')}</small>
                    </div>
                    <div class="d-flex align-items-center">
                        <button class="btn btn-sm btn-outline-secondary me-2 qty-decr" data-idx="${idx}">−</button>
                        <span class="mx-1" id="qty-${idx}">${c.jumlah}</span>
                        <button class="btn btn-sm btn-outline-secondary ms-2 qty-incr" data-idx="${idx}">+</button>
                        <button class="btn btn-sm btn-danger ms-3 remove-item" data-idx="${idx}">×</button>
                    </div>
                </div>`;
            });
            html += '</div>';
            el.innerHTML = html;

            // Event handlers
            document.querySelectorAll('.qty-incr').forEach(b => b.addEventListener('click', (e) => {
                const i = e.currentTarget.dataset.idx;
                let cart = JSON.parse(localStorage.getItem('cart') || '[]');
                cart[i].jumlah++;
                localStorage.setItem('cart', JSON.stringify(cart));
                render();
                updateCartCount();
            }));
            document.querySelectorAll('.qty-decr').forEach(b => b.addEventListener('click', (e) => {
                const i = e.currentTarget.dataset.idx;
                let cart = JSON.parse(localStorage.getItem('cart') || '[]');
                if (cart[i].jumlah > 1) cart[i].jumlah--;
                else cart.splice(i, 1);
                localStorage.setItem('cart', JSON.stringify(cart));
                render();
                updateCartCount();
            }));
            document.querySelectorAll('.remove-item').forEach(b => b.addEventListener('click', (e) => {
                const i = e.currentTarget.dataset.idx;
                let cart = JSON.parse(localStorage.getItem('cart') || '[]');
                cart.splice(i, 1);
                localStorage.setItem('cart', JSON.stringify(cart));
                render();
                updateCartCount();
            }));
        }

        const total = (JSON.parse(localStorage.getItem('cart') || '[]')).reduce((s, c) => s + (c.jumlah * c.harga), 0);
        document.getElementById('totalPrice').innerText = formatRupiah(total);
        document.getElementById('modalTotalPrice').innerText = formatRupiah(total);
    }

    render();

    // Tombol buka modal checkout
    document.getElementById('openCheckout').addEventListener('click', () => {
        const cart = JSON.parse(localStorage.getItem('cart') || '[]');
        if (!cart.length) {
            Swal.fire('Kosong', 'Keranjang masih kosong, tambahkan menu terlebih dahulu.', 'error');
            return;
        }
        document.getElementById('cart-input').value = JSON.stringify(cart);
        document.getElementById('modalTotalPrice').innerText = document.getElementById('totalPrice').innerText;

        const modal = new bootstrap.Modal(document.getElementById('checkoutModal'));
        modal.show();
    });

    // AJAX submit checkout: send form via fetch, handle JSON response
    document.getElementById('modalCheckoutForm').addEventListener('submit', async (ev) => {
        ev.preventDefault();
        const cart = JSON.parse(localStorage.getItem('cart') || '[]');
        if (!cart.length) {
            Swal.fire('Kosong', 'Keranjang kosong.', 'error');
            return;
        }

        const form = ev.currentTarget;
        // ensure hidden cart input is up-to-date
        document.getElementById('cart-input').value = JSON.stringify(cart);

        const fd = new FormData(form);
        try {
            const res = await fetch(form.action, {
                method: 'POST',
                body: fd,
                credentials: 'same-origin',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            // Be defensive: only call res.json() when Content-Type is JSON
            const ct = res.headers.get('content-type') || '';
            let data = null;
            if (ct.indexOf('application/json') !== -1) {
                data = await res.json();
            } else {
                // server returned HTML or plain text (likely a redirect or error page)
                const txt = await res.text();
                console.error('Expected JSON but got:', txt);
                Swal.fire('Gagal', 'Gagal menghubungi server (non-JSON response)', 'error');
                return;
            }

            if (data && data.success) {
                // save to device history
                try {
                    const orders = JSON.parse(localStorage.getItem('orders') || '[]');
                    if (orders.indexOf(data.order_id) === -1) orders.push(data.order_id);
                    localStorage.setItem('orders', JSON.stringify(orders));
                } catch (e) {}

                // clear cart and update UI
                localStorage.removeItem('cart');
                render();
                if (typeof updateCartCount === 'function') try {
                    updateCartCount();
                } catch (e) {}

                // hide modal
                try {
                    bootstrap.Modal.getInstance(document.getElementById('checkoutModal')).hide();
                } catch (e) {}

                Swal.fire('Berhasil', data.message || 'Pesanan dibuat', 'success').then(() => {
                    // redirect to order status page
                    window.location.href = '<?= base_url('pesanan/saya') ?>/' + data.order_id;
                });
                return;
            }
            Swal.fire('Gagal', (data && data.message) ? data.message : 'Terjadi kesalahan', 'error');
        } catch (err) {
            console.error(err);
            Swal.fire('Gagal', 'Gagal menghubungi server', 'error');
        }
    });

    // Populate meja dropdown: fetch occupied tables and show free ones
    (function() {
        async function loadMeja() {
            try {
                const res = await fetch('<?= base_url('pesanan/occupied_tables') ?>');
                const settingsRes = await fetch('<?= base_url('api/settings/get_jumlah_meja') ?>');
                if (!res.ok || !settingsRes.ok) throw new Error('Gagal mengambil data meja.');
                const occupied = await res.json();
                const settings = await settingsRes.json();
                const totalMeja = settings.jumlah_meja || 20;
                const sel = document.getElementById('select-meja');
                sel.innerHTML = '<option value="">Pilih nomor meja...</option>';
                for (let i = 1; i <= totalMeja; i++) {
                    if (occupied.indexOf(String(i)) !== -1 || occupied.indexOf(i) !== -1) continue;
                    const opt = document.createElement('option');
                    opt.value = i;
                    opt.text = 'Meja ' + i;
                    sel.appendChild(opt);
                }
            } catch (e) {
                console.error("Gagal memuat nomor meja:", e);
                // Jika gagal, biarkan select dengan opsi default
            }
        }
        loadMeja();
        // also reload when modal opens
        document.getElementById('openCheckout').addEventListener('click', loadMeja);
    })();
</script>

<?php $this->load->view('_partials/footer_pelanggan'); ?>