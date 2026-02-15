<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InternFlow - Sistem Monitoring Magang Diskominfo SP Surakarta</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo-internflow.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#213448',
                        'secondary': '#547792',
                        'accent': '#94B4C1',
                        'background': '#EAE0CF',
                    }
                }
            }
        }

    </script>
    <style>
        html {
            scroll-behavior: smooth;

        }

        body {
            font-family: 'Poppins', sans-serif;
        }

        html, body {
            max-width: 100%;
            overflow-x: hidden;
        }


        .hero-bg {
            background: linear-gradient(135deg, rgba(33, 52, 72, 0.95) 0%, rgba(84, 119, 146, 0.9) 100%), url('https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80');
            background-size: cover;
            background-position: center;
        }

        .stat-card:hover {
            transform: translateY(-10px);
            transition: transform 0.3s ease;
        }

        .btn-primary {
            background-color: #213448;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #1a2938;
            transform: scale(1.05);
        }

        .alur-item {
            position: relative;
            padding-left: 50px;
        }

        .alur-item:before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 40px;
            height: 40px;
            background: #213448;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        .alur-item:nth-child(1):before {
            content: '1';
            background: #213448;
        }

        .alur-item:nth-child(2):before {
            content: '2';
            background: #547792;
        }

        .alur-item:nth-child(3):before {
            content: '3';
            background: #94B4C1;
        }

        .alur-item:nth-child(4):before {
            content: '4';
            background: #2ecc71;
        }

        .alur-item:nth-child(5):before {
            content: '5';
            background: #f39c12;
        }

        .alur-item:not(:last-child):after {
            content: '';
            position: absolute;
            left: 20px;
            top: 40px;
            width: 2px;
            height: calc(100% - 20px);
            background: linear-gradient(to bottom, #213448, transparent);
        }

        .map-container {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border: 2px solid rgba(148, 180, 193, 0.3);
        }

        .logo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            gap: 30px;
            align-items: center;
            margin-top: 20px;
        }

        @media (max-width: 640px) {
            .logo-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
            }
        }

        .gov-logo {
            opacity: 0.9;
            transition: opacity 0.3s ease;
            height: 50px;
            object-fit: contain;
        }

        .gov-logo:hover {
            opacity: 1;
        }

                @media (max-width: 640px) {
            .alur-item {
                padding-left: 40px;
            }

            .alur-item:before {
                width: 32px;
                height: 32px;
                font-size: 14px;
            }

            .alur-item:not(:last-child):after {
                left: 16px;
            }
        }


    </style>
</head>

<body class="bg-background text-gray-800">
    <!-- Navigation Bar -->
    <nav x-data="{ isOpen: false }"
        class="fixed w-full bg-primary text-white shadow-xl z-50 transition-all duration-300">
        <div class="container mx-auto px-4 sm:px-6 py-4">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="/" class="text-2xl font-bold tracking-tight">
                        <span class="text-accent">Intern</span><span class="text-white">Flow</span>
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex space-x-8">
                    <a href="#home" class="hover:text-accent transition">Beranda</a>
                    <a href="#alur" class="hover:text-accent transition">Alur Magang</a>
                    <a href="#contact" class="hover:text-accent transition">Kontak</a>
                </div>

                <!-- Login Button & Mobile Menu Toggle -->
                <div class="flex items-center space-x-4">
                    <a href="/login"
                        class="hidden md:inline-block bg-accent text-primary font-semibold px-6 py-2 rounded-lg hover:bg-opacity-90 transition shadow-md">
                        Login / Daftar
                    </a>
                    <button @click="isOpen = !isOpen" class="md:hidden focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path :class="{'hidden': isOpen, 'inline-flex': !isOpen }" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': !isOpen, 'inline-flex': isOpen }" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div x-show="isOpen" x-transition class="md:hidden mt-4 pb-4 border-t border-gray-700">
                <div class="flex flex-col space-y-4 mt-4">
                    <a href="#home" @click="isOpen = false" class="hover:text-accent transition">Beranda</a>
                    <a href="#alur" @click="isOpen = false" class="hover:text-accent transition">Alur Magang</a>
                    <a href="#contact" @click="isOpen = false" class="hover:text-accent transition">Kontak</a>
                    <a href="/login" @click="isOpen = false"
                        class="bg-accent text-primary font-semibold px-6 py-2 rounded-lg hover:bg-opacity-90 transition text-center mt-2">
                        Login / Daftar
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-bg text-white pt-24 md:pt-32 pb-20">
        <div class="container mx-auto px-6 text-center">
            <h1 class="text-3xl sm:text-4xl md:text-6xl font-bold mb-6 leading-tight">
                Selamat Datang di <span class="text-accent">InternFlow</span>
            </h1>
            <p class="text-base sm:text-lg md:text-2xl mb-8 sm:mb-10 max-w-3xl mx-auto opacity-90 leading-relaxed">
                Sistem Digital <span class="font-semibold">Monitoring & Administrasi Magang</span> <br> Dinas
                Komunikasi, Informatika, Statistik dan Persandian
                Kota Surakarta
            </p>
        <div class="flex flex-col sm:flex-row justify-center gap-7">
            <a href="/register"
                class="bg-accent text-primary font-semibold px-8 py-4 rounded-xl text-lg shadow-lg hover:shadow-2xl hover:scale-105 transition duration-300">
                Mulai Magang Sekarang
            </a>

            <a href="#alur"
                class="border-2 border-accent text-accent font-semibold px-8 py-4 rounded-xl text-lg hover:bg-accent hover:text-primary transition duration-300">
                Lihat Alur Magang
            </a>
        </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="w-full bg-scroll md:bg-fixed"
    style="background: url('https://diskominfosp.surakarta.go.id/fe/assets/img/nav-bar.jpg'); background-size: cover; background-position: center;">
      <div class="container mx-auto px-4 sm:px-6 py-12 sm:py-16">
        <!-- Alur Magang Section -->
        <section id="alur" class="mb-20 scroll-mt-24">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-primary mb-4">Alur Magang di <span
                        class="text-secondary">Diskominfo SP Surakarta</span></h2>
                <p class="text-gray-600 max-w-3xl mx-auto">Ikuti langkah-langkah berikut untuk memulai perjalanan magang
                    Anda bersama kami.</p>
            </div>

            <div class="max-w-4xl mx-auto bg-white rounded-3xl shadow-xl p-8 md:p-12 border border-accent/50">
                <div class="space-y-10">
                    <!-- Step 1 -->
                    <div class="alur-item">
                        <h3 class="text-xl font-bold text-primary mb-3">1. Pengajuan Online</h3>
                        <p class="text-gray-600 mb-4">Calon peserta mendaftar akun lalu mengisi formulir pengajuan
                            online dan mengunggah berkas administrasi melalui portal InternFlow.</p>
                        <div class="bg-background p-4 rounded-xl">
                            <p class="text-sm font-medium text-primary">Berkas yang diperlukan:</p>
                            <ul class="text-sm text-gray-600 mt-2 space-y-1">
                                <li class="flex items-center"><span class="w-2 h-2 bg-accent rounded-full mr-2"></span>
                                    CV/Resume terbaru</li>
                                <li class="flex items-center"><span class="w-2 h-2 bg-accent rounded-full mr-2"></span>
                                    Surat penempatan magang dari BKPSDM</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="alur-item">
                        <h3 class="text-xl font-bold text-primary mb-3">2. Verifikasi Administrasi</h3>
                        <p class="text-gray-600 mb-4">Tim Admin Kepegawaian memverifikasi kelengkapan dan kesesuaian
                            berkas pendaftaran.</p>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                            <div class="flex-1 bg-green-50 p-3 rounded-lg border border-green-200">
                                <p class="text-sm font-medium text-green-700">Diterima</p>
                                <p class="text-xs text-green-600">Melanjutkan ke proses seleksi bidang</p>
                            </div>
                            <div class="flex-1 bg-yellow-50 p-3 rounded-lg border border-yellow-200">
                                <p class="text-sm font-medium text-yellow-700">Menunggu</p>
                                <p class="text-xs text-yellow-600">Diminta menunggu dalam proses seleksi</p>
                            </div>
                            <div class="flex-1 bg-red-50 p-3 rounded-lg border border-red-200">
                                <p class="text-sm font-medium text-red-700">Ditolak</p>
                                <p class="text-xs text-red-600">Diminta untuk merevisi berkas pendaftaran</p>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="alur-item">
                        <h3 class="text-xl font-bold text-primary mb-3">3. Penempatan Bidang</h3>
                        <p class="text-gray-600 mb-4">Admin Bidang menempatkan peserta ke bidang sesuai kompetensi dan
                            ketersediaan kuota, kemudian menetapkan mentor pembimbing.</p>
                        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                            <div class="bg-secondary/10 p-3 rounded-lg text-center">
                                <p class="text-sm font-medium text-secondary"> Teknologi & Informatika</p>
                            </div>
                            <div class="bg-secondary/10 p-3 rounded-lg text-center">
                                <p class="text-sm font-medium text-secondary"> Statistika</p>
                            </div>
                            <div class="bg-secondary/10 p-3 rounded-lg text-center">
                                <p class="text-sm font-medium text-secondary"> Komunikasi Publik dan Media</p>
                            </div>
                            <div class="bg-secondary/10 p-3 rounded-lg text-center">
                                <p class="text-sm font-medium text-secondary"> Sekretariat</p>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div class="alur-item">
                        <h3 class="text-xl font-bold text-primary mb-3">4. Pelaksanaan Magang</h3>
                        <p class="text-gray-600 mb-4">Peserta menjalani magang dengan panduan mentor, mengisi logbook
                            harian, dan melaksanakan tugas sesuai bidang.</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                            <div class="bg-secondary/10 p-3 rounded-lg text-center">
                                <p class="text-sm font-medium text-secondary"> Logbook Harian</p>
                            </div>
                            <div class="bg-secondary/10 p-3 rounded-lg text-center">
                                <p class="text-sm font-medium text-secondary"> Absensi Digital</p>
                            </div>
                        </div>
                    </div>

                    <!-- Step 5 -->
                    <div class="alur-item">
                        <h3 class="text-xl font-bold text-primary mb-3">5. Evaluasi & Sertifikasi</h3>
                        <p class="text-gray-600 mb-4">Di akhir periode, mentor memberikan penilaian dan Admin
                            Kepegawaian menerbitkan sertifikat digital yang dapat diunduh langsung.</p>
                        <div class="bg-gradient-to-r from-primary/10 to-secondary/10 p-4 rounded-xl mt-4">
                            <div class="flex items-center gap-4">
                                <div>
                                    <p class="font-medium text-primary">Sertifikat Digital Magang</p>
                                    <p class="text-sm text-gray-600">Terverifikasi, Dapat diunduh kapan saja melalui
                                        akun peserta</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-12 text-center">
                    <a href="/register"
                        class="inline-block bg-primary text-white font-bold px-8 py-3 rounded-xl text-lg hover:shadow-xl hover:scale-105 transition duration-300">
                        Daftar Sekarang & Ikuti Alur Ini
                    </a>
                </div>
            </div>
        </section>
        </div>
    </main>

<!-- Main Footer -->
<footer id="contact" class="bg-primary text-white pt-12 pb-8">
    <div class="container mx-auto px-4 sm:px-6">
        <!-- Main Footer Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-12">
            <!-- Brand -->
            <div>
                <a href="/" class="text-2xl font-bold">
                    <span class="text-accent">Intern</span><span>Flow</span>
                </a>
                <p class="mt-4 text-gray-300 text-sm leading-relaxed">
                    Sistem Monitoring Magang Digital Dinas Komunikasi, Informatika, Statistik dan Persandian Kota Surakarta. Platform terintegrasi untuk mengelola kegiatan magang secara efektif dan transparan.
                </p>
            </div>

            <!-- Logo Pemerintah -->
            <div>
                <h4 class="font-bold text-lg mb-6 text-accent">Mitra Pemerintah</h4>
                <div class="logo-grid">
                    <img src="https://diskominfosp.surakarta.go.id/public/fe/assets/img/pemkot.png"
                        class="gov-logo">

                    <img src="https://diskominfosp.surakarta.go.id/public/fe/assets/img/ppidkotasolologo.png"
                        class="gov-logo">

                    <img src="https://diskominfosp.surakarta.go.id/public/fe/assets/img/solodatalogo.png"
                        class="gov-logo">

                    <img src="https://diskominfosp.surakarta.go.id/public/displayFileFe/setting/4cdb646e-0344-4342-abed-d19dc8ea2aaf.png"
                        class="gov-logo">
                </div>
            </div>

            <!-- Kontak -->
            <div>
                <h4 class="font-bold text-lg mb-6 text-accent">Kontak</h4>
                <ul class="space-y-4 text-gray-300 text-sm">
                    <li class="flex items-start gap-3">
                        <i class="bi bi-envelope text-accent mt-1"></i>
                        <span>diskominfosp@surakarta.go.id</span>
                    </li>

                    <li class="flex items-start gap-3">
                        <i class="bi bi-telephone text-accent mt-1"></i>
                        <span>(0271) 2931667</span>
                    </li>

                    <li class="flex items-start gap-3">
                        <i class="bi bi-clock text-accent mt-1"></i>
                        <span>Senin - Jumat: 08:00 - 16:00 WIB</span>
                    </li>
                </ul>
            </div>

            <!-- Lokasi -->
            <div>
                <h4 class="font-bold text-lg mb-6 text-accent">Lokasi Kantor</h4>

                <div class="rounded-xl overflow-hidden shadow-lg border border-accent/30">
                    <iframe
                        class="w-full h-48 sm:h-56 md:h-64"
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3955.0568719019525!2d110.82868719999999!3d-7.568779!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a166036a73eb1%3A0x7f6987f2e325554e!2sDinas%20Komunikasi%20Informatika%20Statistik%20Dan%20Persandian%20Kota%20Surakarta!5e0!3m2!1sid!2sid"
                        width="100%" height="180" style="border:0;" loading="lazy">
                    </iframe>
                </div>

                <p class="text-gray-300 text-sm mt-3 leading-relaxed">
                    Gedung Bale Upakari Lantai 3. Jl. Jenderal Sudirman No. 2, Komplek Balaikota Surakarta Kode Pos 57133
                </p>
            </div>
        </div>
    </div>
</footer>

<!-- Bottom Footer -->
<div class="bg-primary text-white relative">
    <!-- Background overlay dengan gambar batik dan warna biru transparan -->
    <div class="absolute inset-0">
        <div class="relative h-full w-full">
            <!-- Background gambar batik -->
            <div 
                class="absolute inset-0 bg-cover bg-center opacity-30"
                style="background-image: url('https://diskominfosp.surakarta.go.id/fe/assets/img/nav-bar.jpg');"
            ></div>
            <!-- Overlay warna biru transparan -->
            <div class="absolute inset-0 bg-primary/90"></div>
        </div>
    </div>
    
    <!-- Konten footer bottom -->
    <div class="container mx-auto px-6 pt-1 pb-8 relative z-10">
        <div class=" pt-8 text-center">
            <p class="text-gray-100 text-sm">
                © {{ date('Y') }} InternFlow — Diskominfo SP Surakarta. Hak Cipta Dilindungi.
            </p>

            <p class="text-gray-300 text-xs mt-2">
                Dibangun dengan Laravel • Bagian dari transformasi digital
            </p>

            <div class="mt-5 relative flex items-center justify-center text-sm">
                <span class="absolute left-0 text-[3px] sm:text-[4px] text-white/10 select-none tracking-widest">
                    Dibuat oleh: Rifaldy Ilham Nasrulloh • Nayla Amira • Yanti Aisyah
                </span>
                <div class="flex flex-wrap justify-center gap-6 text-sm">
                    <a href="https://surakarta.go.id" class="text-gray-200 hover:text-accent transition hover:underline">
                        surakarta.go.id
                    </a>
                    <a href="https://diskominfosp.surakarta.go.id/" class="text-gray-200 hover:text-accent transition hover:underline">
                        diskominfo.surakarta.go.id
                    </a>
                    <a href="https://ppid.surakarta.go.id" class="text-gray-200 hover:text-accent transition hover:underline">
                        ppid.surakarta.go.id
                    </a>
                    <a href="https://solodata.surakarta.go.id" class="text-gray-200 hover:text-accent transition hover:underline">
                        solodata.surakarta.go.id
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
</footer>

    <!-- Scroll to Top Button -->
    <div x-data="{ show: false }" x-init="window.addEventListener('scroll', () => { show = window.scrollY > 300 })"
        x-show="show" x-transition class="fixed bottom-6 right-6 z-50">

        <button @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
            class="bg-primary text-white p-3 rounded-full border shadow-xl border border-accent/50 hover:bg-secondary transition">
            <i class="bi bi-arrow-up text-xl"></i>
        </button>

    </div>


    <!-- Load Alpine.js for Interactivity -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>

</html>
