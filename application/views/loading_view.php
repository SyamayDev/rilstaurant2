<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RIlstaurant - Grand Reveal</title>
    
    <!-- Impor font premium untuk kesan yang lebih mewah --><link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=Inter:wght@300;400&display=swap" rel="stylesheet">

    <style>
        /* Reset dasar */
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #000000; /* Mulai dengan hitam pekat */
            color: #ffffff;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden; /* Penting untuk mencegah scroll saat animasi */
            position: relative; /* Untuk latar belakang gradien */
            /* Transisi dihapus, diganti animasi loop */
            animation: pulseBackground 10s ease-in-out infinite; /* Latar belakang "bernapas" */
        }

        /*
         * Kontainer utama loader: Semua elemen berada di sini.
         * 'perspective' untuk efek 3D pada partikel dan logo.
        */
        .loader-wrapper {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            perspective: 1000px; /* Jarak pandang untuk efek 3D */
        }

        /*
         * Kontainer untuk logo: Ini adalah pusat dari segalanya.
         * 'z-index' tinggi agar selalu di depan partikel.
         * 'position: relative' untuk menampung efek shimmer.
        */
        .logo-main-container {
            position: relative;
            width: 200px; /* Ukuran logo, disesuaikan */
            height: 200px;
            margin-bottom: 2rem;
            z-index: 15; /* Dinaikkan agar di atas gelombang cahaya */
            /* Dihapus: opacity, transform, dan animasi fade-in agar langsung terlihat */
        }

        .logo-image {
            width: 100%;
            height: 100%;
            display: block;
            border-radius: 20px;
            filter: drop-shadow(0 0 20px rgba(249, 115, 22, 0.4)); /* Bayangan glow emas */
            /* Ditambahkan: Animasi pendaran (glow) pada logo */
            animation: pulseGlow 4s ease-in-out infinite;
        }

        /*
         * EFEK SHIMMER PADA LOGO (DIHAPUS)
         * Ini adalah efek yang Anda tidak suka karena terpotong.
         * Kita akan menggantinya dengan gelombang cahaya fullscreen.
        */
        
        /*
         * Teks status: Muncul lebih lambat, lebih halus.
        */
        .status-text {
            font-family: 'Playfair Display', serif; /* Font brand untuk teks status */
            font-size: 1.5rem; /* Ukuran lebih besar */
            color: #F97316; /* Warna emas/oranye Anda */
            letter-spacing: 3px;
            text-transform: uppercase;
            text-shadow: 0 0 15px rgba(249, 115, 22, 0.4); /* Glow teks */
            /* Dihapus: opacity, transform, dan animasi fade-in. */
            /* Animasi pulseText dimodifikasi dan dimulai langsung */
            animation: pulseText 3s infinite ease-in-out;
            position: relative; /* Ditambahkan agar z-index berfungsi */
            z-index: 15; /* Pastikan teks di atas gelombang cahaya */
        }

        /*
         * PARTIKEL EMAS (Ribuan titik cahaya yang melayang)
         * Ini adalah elemen terpisah yang akan saya buat dengan CSS.
         * Kita buat 5 lapisan partikel untuk kedalaman.
        */
        .particles {
            position: absolute;
            width: 100%;
            height: 100%;
            pointer-events: none; /* Tidak bisa diklik */
            z-index: 5; /* Di belakang logo, di atas latar */
        }

        /* Partikel individu */
        .particle {
            position: absolute;
            background-color: #ffd700; /* Warna emas */
            border-radius: 50%; /* Bulat sempurna */
            opacity: 0; /* Mulai tersembunyi */
            animation: particleAnimation 10s infinite ease-in-out; /* Animasi utama partikel */
            filter: blur(1px); /* Efek blur halus untuk kesan "dust" */
        }

        /* Kita akan menggunakan SASS/JS untuk mengisi ini dengan banyak partikel,
         * tapi untuk demo, kita akan buat beberapa secara manual atau dengan loop CSS */
        
        /* Contoh beberapa partikel manual (akan digenerate JS untuk ribuan) */
        .particle:nth-child(1) { width: 3px; height: 3px; top: 10%; left: 20%; animation-delay: 0.5s; animation-duration: 12s; }
        .particle:nth-child(2) { width: 2px; height: 2px; top: 80%; left: 70%; animation-delay: 1.2s; animation-duration: 9s; }
        .particle:nth-child(3) { width: 4px; height: 4px; top: 30%; left: 50%; animation-delay: 2s; animation-duration: 15s; }
        .particle:nth-child(4) { width: 2px; height: 2px; top: 60%; left: 10%; animation-delay: 0.8s; animation-duration: 11s; }
        .particle:nth-child(5) { width: 3px; height: 3px; top: 45%; left: 90%; animation-delay: 2.5s; animation-duration: 13s; }
        /* ...dan seterusnya, untuk ribuan partikel ... */


        /*
         * BARU: Gelombang Cahaya Latar Belakang (Aurora)
         * Menggantikan shimmer pada logo.
        */
        .light-wave {
            position: absolute;
            top: 0;
            left: -100%; /* Mulai di luar layar */
            width: 100%;
            height: 100%;
            /* Gradien vertikal tipis berwarna emas, bukan putih */
            background: linear-gradient(
                90deg, 
                rgba(255, 215, 0, 0) 0%, 
                rgba(255, 215, 0, 0.1) 45%, /* Cahaya sangat halus */
                rgba(255, 215, 0, 0.15) 50%, /* Pusat cahaya */
                rgba(255, 215, 0, 0.1) 55%,
                rgba(255, 215, 0, 0) 100%
            );
            filter: blur(50px); /* Sangat blur agar lembut */
            z-index: 10; /* Di belakang logo (15), di depan partikel (5) */
            animation: lightWaveSweep 6s ease-in-out infinite;
            animation-delay: 1s; /* Mulai setelah 1 detik */
            pointer-events: none;
        }


        /*
         * Efek Lens Flare (kilatan cahaya sesekali)
         * Akan muncul dan memudar secara acak.
        */
        .lens-flare {
            position: absolute;
            width: 150px;
            height: 150px;
            background: radial-gradient(circle, rgba(255, 215, 0, 0.6) 0%, rgba(255, 215, 0, 0) 70%);
            border-radius: 50%;
            filter: blur(10px);
            opacity: 0;
            animation: flareAppear 10s infinite ease-in-out; /* Animasi flare */
            z-index: 20; /* Di atas partikel & gelombang, setara logo */
            pointer-events: none;
        }

        .lens-flare:nth-child(1) { top: 10%; left: 10%; animation-delay: 3s; animation-duration: 8s; }
        .lens-flare:nth-child(2) { top: 70%; left: 80%; animation-delay: 6s; animation-duration: 12s; }


        /* === KEYFRAMES (Animasi KUNCI) === */

        /* Latar belakang berubah dari hitam ke maroon */
        @keyframes bgFadeIn {
            0% { background-color: #000000; }
            100% { background-color: #4c1111; }
        }

        /* BARU: Latar belakang "bernapas" */
        @keyframes pulseBackground {
            0%, 100% { background-color: #000000; }
            50% { background-color: #4c1111; }
        }

        /* Logo muncul dengan skala dan fade-in (DIHAPUS) */
        /* @keyframes logoFadeInScale ... */

        /* BARU: Pendaran (glow) pada logo */
        @keyframes pulseGlow {
            0%, 100% { filter: drop-shadow(0 0 20px rgba(249, 115, 22, 0.4)); }
            50% { filter: drop-shadow(0 0 35px rgba(249, 115, 22, 0.8)); } /* Brighter, wider glow */
        }

        /* Shimmer emas yang bergerak melintasi logo (DIHAPUS) */
        /* @keyframes logoShimmer ... */
        
        /* Teks status muncul (DIHAPUS) */
        /* @keyframes textFadeIn ... */

        /* Teks status berdenyut (DIMODIFIKASI) */
        @keyframes pulseText {
            0%, 100% { opacity: 0.7; text-shadow: 0 0 15px rgba(249, 115, 22, 0.4); }
            50% { opacity: 1; text-shadow: 0 0 25px rgba(249, 115, 22, 0.8); } /* Brighter glow */
        }

        /* BARU: Animasi gelombang cahaya fullscreen */
        @keyframes lightWaveSweep {
            0% { 
                transform: translateX(-100%) skewX(-20deg); 
                opacity: 0.5;
            }
            50% { 
                transform: translateX(200%) skewX(-20deg); 
                opacity: 1;
            }
            100% { 
                transform: translateX(-100%) skewX(-20deg); 
                opacity: 0.5;
            }
        }

        /* Animasi partikel melayang */
        @keyframes particleAnimation {
            0% {
                opacity: 0;
                transform: translateZ(0) translateY(0) translateX(0) scale(0.5);
            }
            10% {
                opacity: 0.8;
            }
            50% {
                transform: translateZ(50px) translateY(calc(var(--randY) * 1px)) translateX(calc(var(--randX) * 1px)) scale(1);
            }
            90% {
                opacity: 0.2;
            }
            100% {
                opacity: 0;
                transform: translateZ(100px) translateY(0) translateX(0) scale(0);
            }
        }

        /* Animasi kilatan lensa (lens flare) */
        @keyframes flareAppear {
            0%, 20%, 80%, 100% { opacity: 0; transform: scale(0.5); }
            50% { opacity: 0.7; transform: scale(1); }
        }

    </style>
</head>
<body>

    <div class="loader-wrapper">
        <!-- Lensa Flare 1 --><div class="lens-flare"></div>
        <!-- Lensa Flare 2 --><div class="lens-flare"></div>

        <!-- Kontainer Partikel (akan diisi via JS) --><div class="particles" id="particles-container"></div>
        
        <!-- BARU: Gelombang Cahaya Fullscreen --><div class="light-wave"></div>

        <!-- Kontainer Logo Utama --><div class="logo-main-container">
            <img src="<?= base_url('assets/img/logo.webp') ?>" alt="Logo RIlstaurant" class="logo-image">
        </div>

        <!-- Teks Status --><p class="status-text">Menyiapkan Pengalaman Kuliner Terbaik...</p>
    </div>

    <script>
        // --- Generasi Partikel Emas via JavaScript ---
        const particlesContainer = document.getElementById('particles-container');
        const numberOfParticles = 200; // Jumlah partikel DITINGKATKAN

        for (let i = 0; i < numberOfParticles; i++) {
            const particle = document.createElement('div');
            particle.classList.add('particle');
            
            // Randomisasi ukuran partikel
            const size = Math.random() * 3 + 1; // Ukuran 1px hingga 4px
            particle.style.width = `${size}px`;
            particle.style.height = `${size}px`;

            // Randomisasi posisi awal
            particle.style.left = `${Math.random() * 100}%`;
            particle.style.top = `${Math.random() * 100}%`;

            // Randomisasi delay dan durasi animasi
            particle.style.animationDelay = `${Math.random() * 10}s`;
            particle.style.animationDuration = `${Math.random() * 10 + 8}s`; // Durasi 8-18 detik

            // Randomisasi untuk pergerakan di keyframe 'particleAnimation'
            // Kita gunakan variabel CSS kustom untuk ini
            particle.style.setProperty('--randX', (Math.random() - 0.5) * 400); // -200px sampai 200px
            particle.style.setProperty('--randY', (Math.random() - 0.5) * 400); // -200px sampai 200px
            
            particlesContainer.appendChild(particle);
        }

        // --- Blok 'window.addEventListener' DIHAPUS untuk membuat loop abadi ---
        
        const images = <?= json_encode($images) ?>;
        let loadedCount = 0;

        function onImageLoad() {
            loadedCount++;
            if (loadedCount === images.length) {
                window.location.href = '<?= base_url('katalog') ?>';
            }
        }

        images.forEach(src => {
            const img = new Image();
            img.onload = onImageLoad;
            img.onerror = onImageLoad; // Count failed images as loaded to avoid getting stuck
            img.src = src;
        });

        // Fallback redirect in case of errors or no images
        setTimeout(() => {
            window.location.href = '<?= base_url('katalog') ?>';
        }, 10000); // 10 seconds
    </script>
</body>
</html>
