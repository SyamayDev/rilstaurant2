<?php $this->load->view('_partials/header_pelanggan'); ?>

<!-- GLOBAL BACKGROUND -->
<style>
    body {
        background: url('<?= base_url('assets/img/batik.png') ?>') repeat fixed;
        background-size: 200px auto;
        position: relative;
    }

    body::before {
        content: "";
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.3);
        pointer-events: none;
        z-index: -1;
    }
</style>

<!-- HERO SECTION -->
<section class="mb-4">
    <div class="p-4 rounded" style="background:linear-gradient(90deg,rgba(107,15,15,0.9), rgba(163,43,43,0.9)); color:#fff;">
        <div class="container d-flex align-items-center justify-content-between flex-column flex-md-row">
            <div class="text-center text-md-start">
                <div class="d-flex align-items-center justify-content-center justify-content-md-start mb-3">
                    <img src="<?= base_url('assets/img/logo.png') ?>" alt="Rilstaurant Logo"
                        style="height:70px; width:auto; margin-right:10px; border-radius:12px; background:#fff; padding:4px; box-shadow:0 2px 10px rgba(0,0,0,0.3);">
                    <h1 class="fw-bold mb-0" style="text-shadow:2px 2px 6px rgba(0,0,0,0.4)">Rilstaurant</h1>
                </div>
                <p class="lead mb-2">Menu pilihan, cepat & lezat. Pesan sekarang atau kunjungi restoran kami.</p>
            </div>
        </div>
    </div>
</section>

<!-- MENU SECTION -->
<section id="menu" class="pb-5">
    <div class="container">
        <h4 class="fw-bold mb-3 text-center text-md-start text-white">Menu Pilihan</h4>
        <!-- category tiles (gabungan dengan filter) -->
        <div class="mb-3 text-center">
            <div class="d-flex justify-content-center flex-wrap gap-3 mt-3">
                <div class="text-center kategori-tile" style="width:110px;cursor:pointer" data-kategori-id="all">
                    <div style="width:110px; height:110px; overflow:hidden; border-radius:50%; position:relative; background:#fff; display:flex; align-items:center; justify-content:center;">
                        <img src="<?= base_url('assets/img/all-categories.png') ?>" alt="Semua" style="max-width:100%; max-height:100%; object-fit:cover"> <!-- Ganti dengan gambar untuk 'Semua' jika ada -->
                        <div class="small" style="position:absolute; bottom:0; left:0; right:0; background:rgba(0,0,0,0.5); color:white; padding:5px 0; backdrop-filter:blur(5px);">Semua</div>
                    </div>
                </div>
                <?php foreach ($categories as $c): ?>
                    <div class="text-center kategori-tile" style="width:110px;cursor:pointer; margin-bottom: 40px;" data-kategori-id="<?= $c->id_kategori ?>">
                        <div style="width:110px; height:110px; overflow:hidden; border-radius:50%; position:relative; background:#fff; display:flex; align-items:center; justify-content:center;">
                            <img src="<?= base_url('assets/uploads/' . ($c->gambar ?? 'no-image.png')) ?>" alt="<?= $c->nama_kategori ?>" style="max-width:100%; max-height:100%; object-fit:cover">
                            <div class="small" style="position:absolute; bottom:0; left:0; right:0; background:rgba(0,0,0,0.5); color:white; padding:5px 0; backdrop-filter:blur(5px);"><?= $c->nama_kategori ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="row gy-3" id="menu-list">
            <?php foreach ($menus as $m): ?>
                <div class="col-6 col-md-4 col-lg-3 menu-item" data-kategori-id="<?= isset($m->kategori_id) ? $m->kategori_id : (isset($m->kategori) ? $m->kategori : 'all') ?>">
                    <div class="card h-100 animate-card border-0 shadow-sm">
                        <div class="ratio ratio-4x3">
                            <img src="<?= base_url('assets/uploads/' . ($m->gambar ?? 'no-image.png')) ?>" class="card-img-top object-fit-cover menu-image"
                                data-id="<?= $m->id_menu ?>"
                                data-nama="<?= htmlspecialchars($m->nama_menu ?? '', ENT_QUOTES) ?>"
                                data-harga="<?= isset($m->harga) ? $m->harga : 0 ?>"
                                data-deskripsi="<?= htmlspecialchars($m->deskripsi ?? '', ENT_QUOTES) ?>"
                                data-detail="<?= htmlspecialchars($m->detail_lengkap ?? ($m->deskripsi ?? ''), ENT_QUOTES) ?>"
                                data-gambar="<?= $m->gambar ?? 'no-image.png' ?>"
                                data-avg-rating="<?= isset($m->avg_rating) ? $m->avg_rating : 0 ?>"
                                data-jumlah-ulasan="<?= isset($m->jumlah_ulasan) ? $m->jumlah_ulasan : 0 ?>"
                                alt="<?= htmlspecialchars($m->nama_menu ?? '', ENT_QUOTES) ?>" style="cursor:pointer">
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title mb-1"><?= isset($m->nama_menu) ? $m->nama_menu : '' ?></h6>
                            <p class="text-muted small mb-2"><?= isset($m->deskripsi) ? word_limiter($m->deskripsi, 12) : '' ?></p>
                            <small class="text-warning fw-bold rating-display"><?= round(isset($m->avg_rating) ? $m->avg_rating : 0, 1) ?>/5 ⭐ (<?= isset($m->jumlah_ulasan) ? $m->jumlah_ulasan : 0 ?> Ulasan)</small>
                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                <div class="fw-bold text-danger">Rp <?= number_format($m->harga, 0, ',', '.') ?></div>
                                <div class="d-flex align-items-center">
                                    <div class="input-group input-group-sm qty-control me-2" data-id="<?= $m->id_menu ?>" style="width:80px">
                                        <button class="btn btn-outline-secondary btn-minus" type="button">−</button>
                                        <input type="text" class="form-control text-center qty-value" value="1" readonly style="width:20px; padding: 5px;">
                                        <button class="btn btn-outline-secondary btn-plus" type="button">+</button>
                                    </div>
                                    <button class="btn btn-sm btn-danger add-to-cart" data-id="<?= $m->id_menu ?>" data-name="<?= htmlspecialchars($m->nama_menu) ?>" data-price="<?= $m->harga ?>">
                                        <i class="bi bi-cart-plus-fill"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- PROMO BANNERS -->
<section class="container mb-5">
    <div class="row g-3">
        <!-- Satu banner besar -->
        <div class="col-12">
            <div style="background:url('<?= base_url('assets/img/banner1.png') ?>') center/cover no-repeat; border-radius:15px; min-height:180px; position:relative; overflow:hidden;">
                <div style="position:absolute; inset:0; background:rgba(0,0,0,0.45); display:flex; align-items:center; justify-content:center; color:white; text-align:center; padding:20px;">
                    <div>
                        <h3 class="fw-bold mb-2">Diskon 25% untuk Menu Spesial Hari Ini!</h3>
                        <p class="mb-0">Nikmati rasa terbaik dari chef kami hanya hari ini.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dua banner kecil -->
        <div class="col-12 col-md-6">
            <div style="background:url('<?= base_url('assets/img/banner2.png') ?>') center/cover no-repeat; border-radius:15px; min-height:150px; position:relative; overflow:hidden;">
                <div style="position:absolute; inset:0; background:rgba(0,0,0,0.45); display:flex; align-items:center; justify-content:center; color:white; text-align:center; padding:20px;">
                    <div>
                        <h5 class="fw-bold mb-1">Gratis Minuman Dingin</h5>
                        <small>Untuk setiap pembelian lebih dari Rp50.000</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6">
            <div style="background:url('<?= base_url('assets/img/banner3.png') ?>') center/cover no-repeat; border-radius:15px; min-height:150px; position:relative; overflow:hidden;">
                <div style="position:absolute; inset:0; background:rgba(0,0,0,0.45); display:flex; align-items:center; justify-content:center; color:white; text-align:center; padding:20px;">
                    <div>
                        <h5 class="fw-bold mb-1">Paket Keluarga Hemat</h5>
                        <small>Untuk 4 orang hanya Rp99.000</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- LOKASI SECTION -->
<section class="container mb-5">
    <h4 class="fw-bold mb-3 text-center text-md-start text-white">Lokasi Kami</h4>
    <div class="row">
        <div class="col-md-6">
            <div style="background: rgba(0,0,0,0.5); padding: 20px; border-radius: 10px; backdrop-filter: blur(5px); height: 302px;">
                <h5 class="text-white">Alamat</h5>
                <p class="text-white">Jl. Contoh No. 123, Jakarta Selatan, Indonesia</p>
                <h5 class="text-white">Jam Operasional</h5>
                <ul class="text-white">
                    <li>Senin: 10:00 - 22:00</li>
                    <li>Selasa: 10:00 - 22:00</li>
                    <li>Rabu: 10:00 - 22:00</li>
                    <li>Kamis: 10:00 - 22:00</li>
                    <li>Jumat: 10:00 - 22:00</li>
                    <li>Sabtu: 09:00 - 23:00</li>
                    <li>Minggu: 09:00 - 23:00</li>
                </ul>
            </div>
        </div>
        <div class="col-md-6 d-flex" style="align-items:stretch;">
            <div style="width:100%; border-radius:12px; overflow:hidden;">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.305080678!2d106.829518414771!3d-6.223590995495!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f3e2b0000001%3A0x4a948d0c1d0f7f!2sMonas%2C%20Jakarta!5e0!3m2!1sen!2sid!4v1697788999999999!5m2!1sen!2sid" width="100%" height="300" style="border:0; display:block;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
</section>

<!-- ULASAN PELANGGAN SECTION -->
<section class="container mb-5">
    <h4 class="fw-bold mb-4 text-center text-white">Ulasan dari Pelanggan Kami</h4>
    <div class="row gy-4">
        <?php if (!empty($ulasan_pelanggan)):
            foreach ($ulasan_pelanggan as $ulasan):
        ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm" style="background:rgba(255,255,255,0.9); backdrop-filter:blur(5px);">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; font-size: 1.2rem;">
                                    <?= substr($ulasan->nama_pelanggan, 0, 1) ?>
                                </div>
                                <div class="ms-3">
                                    <h6 class="card-title mb-0 fw-bold"><?= htmlspecialchars($ulasan->nama_pelanggan) ?></h6>
                                    <small class="text-muted">Mengulas <?= htmlspecialchars($ulasan->nama_menu) ?></small>
                                </div>
                            </div>
                            <div class="mb-2">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="bi <?= $i <= $ulasan->rating ? 'bi-star-fill' : 'bi-star' ?> text-warning"></i>
                                <?php endfor; ?>
                            </div>
                            <p class="card-text text-muted">"<?= htmlspecialchars($ulasan->komentar) ?>"</p>
                        </div>
                    </div>
                </div>
            <?php
            endforeach;
        else:
            ?>
            <div class="col-12 text-center">
                <p class="text-white">Belum ada ulasan.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- STYLES -->
<style>
    .animate-card {
        transition: transform .18s ease, box-shadow .18s ease
    }

    .animate-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 8px 30px rgba(107, 15, 15, 0.15)
    }

    .object-fit-cover {
        object-fit: cover
    }

    /* Responsive adjustments */
    .menu-item .card {
        background-clip: padding-box;
    }

    .card .card-title {
        font-size: 1rem;
    }

    .card .card-body p.text-muted {
        font-size: .85rem;
        line-height: 1.2;
        max-height: 3.6rem;
        /* ~3 lines */
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Ensure card images keep aspect ratio and don't overflow on small screens */
    .ratio-4x3 {
        aspect-ratio: 4 / 3;
        overflow: hidden;
    }

    .menu-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    /* Stack price and controls on very small screens */
    @media (max-width: 480px) {
        .card-body .mt-auto.d-flex {
            flex-direction: column-reverse;
            gap: .5rem;
            align-items: stretch;
        }

        .card-body .fw-bold.text-danger {
            text-align: left;
            font-size: 1rem;
        }

        .card-body .d-flex.align-items-center {
            justify-content: space-between;
        }

        .qty-control {
            width: 100% !important;
            max-width: none !important;
            display: flex;
        }

        .qty-control .btn,
        .qty-control .form-control {
            height: 30px;
            width: 20px;
        }

        .add-to-cart {
            width: 35%;
            margin-top: 4px;
        }
    }

    #btnCart:hover {
        transform: scale(1.05);
        transition: .2s
    }

    @media(max-width:576px) {
        .display-5 {
            font-size: 1.9rem
        }

        .lead {
            font-size: 1rem
        }

        h3 {
            font-size: 1.2rem
        }

        h5 {
            font-size: 1rem
        }

        img[alt="Rilstaurant Logo"] {
            height: 55px !important;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {

        // --- Inisialisasi Modal (hanya sekali) ---
        const modalHtml = `
    <div class="modal fade" id="menuDetailModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="md-title">Detail Menu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-md-5">
                            <img id="md-image" src="" class="img-fluid rounded shadow-sm" alt="Detail Menu">
                        </div>
                        <div class="col-md-7">
                            <h3 id="md-name" class="fw-bold"></h3>
                            <p class="text-danger h4 fw-bold mb-3" id="md-price"></p>
                            <p id="md-rating" class="text-warning fw-bold mb-3"></p>
                            <p id="md-detail" class="text-muted"></p>
                            <hr>
                            <h6 class="mb-2">Tinggalkan Ulasan</h6>
                            <div id="review-form">
                                <div class="mb-2">
                                    <label class="small">Rating:</label>
                                    <div id="star-rate" style="font-size:24px; color: #ffc107; cursor:pointer;">
                                        <span data-index="1">★</span><span data-index="2">★</span><span data-index="3">★</span><span data-index="4">★</span><span data-index="5">★</span>
                                    </div>
                                    <input type="hidden" id="review-rating" value="5">
                                </div>
                                <div class="mb-2">
                                    <label class="small">Nama Anda:</label>
                                    <input type="text" id="review-name" class="form-control" placeholder="Masukkan nama Anda">
                                </div>
                                <div class="mb-2">
                                    <textarea id="review-comment" class="form-control" rows="3" placeholder="Bagaimana menurut Anda?"></textarea>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button id="submit-review" class="btn btn-sm btn-outline-dark">Kirim Ulasan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button class="btn btn-danger" id="md-add-cart"><i class="bi bi-cart-plus-fill me-2"></i>Tambah ke Keranjang</button>
                </div>
            </div>
        </div>
    </div>`;
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        const menuModal = new bootstrap.Modal(document.getElementById('menuDetailModal'));


        // --- Fungsi Bantuan (Helpers) ---
        const updateCartCount = () => {
            const cart = JSON.parse(localStorage.getItem('cart') || '[]');
            const totalItems = cart.reduce((sum, item) => sum + item.jumlah, 0);
            const cartCountEl = document.getElementById('cartCount');
            if (cartCountEl) {
                cartCountEl.innerText = totalItems;
            }
        };

        const addToCart = (id, name, price, qty = 1) => {
            let cart = JSON.parse(localStorage.getItem('cart') || '[]');
            const existingItem = cart.find(item => item.id_menu == id);

            if (existingItem) {
                existingItem.jumlah += qty;
            } else {
                cart.push({
                    id_menu: id,
                    nama_menu: name,
                    harga: price,
                    jumlah: qty
                });
            }

            localStorage.setItem('cart', JSON.stringify(cart));
            updateCartCount();

            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Ditambahkan ke keranjang',
                showConfirmButton: false,
                timer: 1200
            });
        };

        const paintStars = (container, rating) => {
            container.querySelectorAll('span').forEach((star, i) => {
                star.textContent = i < rating ? '★' : '☆';
            });
        };


        // --- Event Listener Utama (Delegation) ---
        document.body.addEventListener('click', async (e) => {
            const target = e.target;

            // 1. Klik Tombol Plus/Minus pada Card
            if (target.closest('.btn-plus, .btn-minus')) {
                const qtyControl = target.closest('.qty-control');
                const input = qtyControl.querySelector('.qty-value');
                let value = parseInt(input.value);
                if (target.closest('.btn-plus')) {
                    value = Math.min(99, value + 1);
                } else {
                    value = Math.max(1, value - 1);
                }
                input.value = value;
            }

            // 2. Klik Tombol "Add to Cart" pada Card
            if (target.closest('.add-to-cart')) {
                const btn = target.closest('.add-to-cart');
                const id = btn.dataset.id;
                const card = btn.closest('.card');
                const qtyInput = card.querySelector('.qty-value');
                const qty = parseInt(qtyInput.value);

                addToCart(btn.dataset.id, btn.dataset.name, parseFloat(btn.dataset.price), qty);

                // Animasi 'terbang' ke keranjang
                const img = card.querySelector('img');
                const cartBtn = document.getElementById('btnCart');
                if (img && cartBtn) {
                    const imgRect = img.getBoundingClientRect();
                    const cartRect = cartBtn.getBoundingClientRect();
                    const flyImg = img.cloneNode();
                    flyImg.style.cssText = `position:fixed; left:${imgRect.left}px; top:${imgRect.top}px; width:${imgRect.width}px; height:${imgRect.height}px; z-index:9999; transition: all 0.7s cubic-bezier(0.5, 0, 0.75, 0); border-radius: 10px;`;
                    document.body.appendChild(flyImg);
                    requestAnimationFrame(() => {
                        flyImg.style.left = `${cartRect.left + 10}px`;
                        flyImg.style.top = `${cartRect.top + 10}px`;
                        flyImg.style.width = '30px';
                        flyImg.style.height = '30px';
                        flyImg.style.opacity = '0.5';
                        flyImg.style.transform = 'rotate(360deg)';
                    });
                    setTimeout(() => flyImg.remove(), 700);
                }
            }

            // 3. Klik Gambar Menu untuk Membuka Modal
            if (target.closest('.menu-image')) {
                const img = target.closest('.menu-image');
                const id = img.dataset.id;
                try {
                    const response = await fetch(`<?= base_url('katalog/detail/') ?>${id}`);
                    if (!response.ok) throw new Error('Network response was not ok.');
                    const data = await response.json();

                    // Populate modal
                    document.getElementById('md-image').src = `<?= base_url('assets/uploads/') ?>${data.gambar || 'no-image.png'}`;
                    document.getElementById('md-title').innerText = data.nama_menu;
                    document.getElementById('md-name').innerText = data.nama_menu;
                    document.getElementById('md-price').innerText = 'Rp ' + Number(data.harga).toLocaleString('id-ID');
                    document.getElementById('md-rating').innerText = `${parseFloat(data.avg_rating || 0).toFixed(1)}/5 ⭐ (${data.jumlah_ulasan || 0} Ulasan)`;
                    document.getElementById('md-detail').innerHTML = data.detail_lengkap || data.deskripsi;

                    const addCartBtn = document.getElementById('md-add-cart');
                    addCartBtn.dataset.id = data.id_menu;
                    addCartBtn.dataset.name = data.nama_menu;
                    addCartBtn.dataset.price = data.harga;

                    // Reset form ulasan
                    document.getElementById('review-comment').value = '';
                    document.getElementById('review-rating').value = '5';
                    paintStars(document.getElementById('star-rate'), 5);

                    menuModal.show();
                } catch (error) {
                    console.error("Gagal mengambil detail menu:", error);
                    Swal.fire('Error', 'Gagal memuat detail menu. Coba lagi nanti.', 'error');
                }
            }

            // 4. Klik Filter Kategori (menggunakan data-kategori-id)
            if (target.closest('.kategori-tile')) {
                const tile = target.closest('.kategori-tile');
                // read and trim category id (handle possible padded DB values)
                let kategoriId = tile.dataset.kategoriId;
                if (!kategoriId) kategoriId = tile.getAttribute('data-kategori-id');
                kategoriId = kategoriId ? String(kategoriId).trim() : 'all';
                // update active visual state
                document.querySelectorAll('.kategori-tile').forEach(t => t.classList.remove('active'));
                tile.classList.add('active');
                document.querySelectorAll('.menu-item').forEach(item => {
                    let itemKat = item.dataset.kategoriId;
                    if (!itemKat) itemKat = item.getAttribute('data-kategori-id');
                    itemKat = itemKat ? String(itemKat).trim() : '';
                    item.style.display = (kategoriId === 'all' || itemKat === kategoriId) ? '' : 'none';
                });
            }

            // 5. Klik Tombol "Add to Cart" di dalam Modal
            if (target.id === 'md-add-cart') {
                addToCart(target.dataset.id, target.dataset.name, parseFloat(target.dataset.price), 1);
                menuModal.hide();
            }

            // 6. Klik Bintang Rating di Modal
            if (target.closest('#star-rate span')) {
                const rating = target.dataset.index;
                document.getElementById('review-rating').value = rating;
                paintStars(target.parentElement, rating);
            }

            // 7. Klik Tombol "Kirim Ulasan" di Modal
            if (target.id === 'submit-review') {
                const btn = target;
                const id_menu = document.getElementById('md-add-cart').dataset.id;
                const rating = document.getElementById('review-rating').value;
                const komentar = document.getElementById('review-comment').value;
                const nama_pelanggan = document.getElementById('review-name').value;

                btn.disabled = true;
                btn.innerHTML = 'Mengirim...';

                try {
                    const response = await fetch('<?= base_url('ulasan/ajax_submit') ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams({
                            id_menu,
                            rating,
                            komentar,
                            nama_pelanggan
                        })
                    });
                    const result = await response.json();

                    if (result.success) {
                        Swal.fire('Berhasil!', result.message || 'Ulasan Anda telah dikirim.', 'success');
                        document.getElementById('review-form').innerHTML = '<p class="text-white">Terima kasih atas ulasan Anda!</p>';
                    } else {
                        Swal.fire('Gagal', result.message || 'Terjadi kesalahan.', 'error');
                    }
                } catch (error) {
                    Swal.fire('Error', 'Gagal mengirim ulasan. Periksa koneksi Anda.', 'error');
                } finally {
                    btn.disabled = false;
                    btn.innerHTML = 'Kirim Ulasan';
                }
            }
        });

        // --- Inisialisasi Awal ---
        updateCartCount();
    });
</script>

<?php $this->load->view('_partials/footer_pelanggan'); ?>