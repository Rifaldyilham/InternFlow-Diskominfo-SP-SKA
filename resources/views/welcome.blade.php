<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InternFlow - Sistem Monitoring Magang Diskominfo SP Surakarta</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo-internflow.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
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
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        html {
            scroll-behavior: smooth;
        }
        body {
            font-family: 'Poppins', sans-serif;
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
        .alur-item:nth-child(1):before { content: '1'; background: #213448; }
        .alur-item:nth-child(2):before { content: '2'; background: #547792; }
        .alur-item:nth-child(3):before { content: '3'; background: #94B4C1; }
        .alur-item:nth-child(4):before { content: '4'; background: #2ecc71; }
        .alur-item:nth-child(5):before { content: '5'; background: #f39c12; }
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
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border: 2px solid rgba(148, 180, 193, 0.3);
        }
        .logo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            gap: 20px;
            align-items: center;
            margin-top: 20px;
        }
        .gov-logo {
            filter: grayscale(100%) brightness(0) invert(1);
            opacity: 0.8;
            transition: all 0.3s ease;
            height: 50px;
            object-fit: contain;
        }
        .gov-logo:hover {
            filter: grayscale(0) brightness(1) invert(0);
            opacity: 1;
        }
    </style>
</head>
<body class="bg-background text-gray-800">
    <!-- Navigation Bar -->
    <nav x-data="{ isOpen: false }" class="fixed w-full bg-primary text-white shadow-lg z-50 transition-all duration-300">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="/" class="text-2xl font-bold tracking-tight">
                        <span class="text-accent">Intern</span><span class="text-white">Flow</span>
                    </a>
                    <span class="ml-2 text-sm bg-secondary/30 px-2 py-1 rounded">v1.0</span>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex space-x-8">
                    <a href="#home" class="hover:text-accent transition">Beranda</a>
                    <a href="#alur" class="hover:text-accent transition">Alur Magang</a>
                    <a href="#contact" class="hover:text-accent transition">Kontak</a>
                </div>

                <!-- Login Button & Mobile Menu Toggle -->
                <div class="flex items-center space-x-4">
                    <a href="/login" class="hidden md:inline-block bg-accent text-primary font-semibold px-6 py-2 rounded-lg hover:bg-opacity-90 transition shadow-md">
                        Login / Daftar
                    </a>
                    <button @click="isOpen = !isOpen" class="md:hidden focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path :class="{'hidden': isOpen, 'inline-flex': !isOpen }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            <path :class="{'hidden': !isOpen, 'inline-flex': isOpen }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
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
                    <a href="/login" @click="isOpen = false" class="bg-accent text-primary font-semibold px-6 py-2 rounded-lg hover:bg-opacity-90 transition text-center mt-2">
                        Login / Daftar
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-bg text-white pt-24 md:pt-32 pb-20">
        <div class="container mx-auto px-6 text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight animate-pulse">
                Selamat Datang di <span class="text-accent">InternFlow</span>
            </h1>
            <p class="text-xl md:text-2xl mb-10 max-w-3xl mx-auto opacity-90">
                Sistem Digital <span class="font-semibold">Monitoring & Administrasi Magang</span> <br> Dinas Komunikasi dan Informatika Kota Surakarta.
            </p>
            <div class="flex flex-col md:flex-row justify-center gap-6 mb-16">
                <a href="/register" class="bg-accent text-primary font-bold px-8 py-4 rounded-xl text-lg hover:shadow-2xl hover:scale-105 transition duration-300 shadow-lg">
                    Mulai Magang Sekarang
                </a>
                <a href="#alur" class="border-2 border-accent text-accent font-bold px-8 py-4 rounded-xl text-lg hover:bg-accent hover:text-primary transition duration-300">
                    Lihat Alur Magang
                </a>
            </div>
            <!-- Stats Mini Preview -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-4xl mx-auto">
                <div class="bg-white/10 backdrop-blur-sm p-4 rounded-2xl border border-accent/30">
                    <div class="text-3xl font-bold text-accent" x-data="{ count: 0 }" x-init="() => { let start = 0; const end = 124; const duration = 2000; const step = (end - start) / (duration / 16); const counter = setInterval(() => { start += step; if (start >= end) { start = end; clearInterval(counter); } $el.textContent = Math.floor(start); }, 16); }">124</div>
                    <div class="text-sm">Peserta Aktif</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm p-4 rounded-2xl border border-accent/30">
                    <div class="text-3xl font-bold text-accent" x-data="{ count: 0 }" x-init="() => { let start = 0; const end = 18; const duration = 2000; const step = (end - start) / (duration / 16); const counter = setInterval(() => { start += step; if (start >= end) { start = end; clearInterval(counter); } $el.textContent = Math.floor(start); }, 16); }">18</div>
                    <div class="text-sm">Bidang Tersedia</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm p-4 rounded-2xl border border-accent/30">
                    <div class="text-3xl font-bold text-accent" x-data="{ count: 0 }" x-init="() => { let start = 0; const end = 42; const duration = 2000; const step = (end - start) / (duration / 16); const counter = setInterval(() => { start += step; if (start >= end) { start = end; clearInterval(counter); } $el.textContent = Math.floor(start); }, 16); }">42</div>
                    <div class="text-sm">Mentor Berpengalaman</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm p-4 rounded-2xl border border-accent/30">
                    <div class="text-3xl font-bold text-accent" x-data="{ count: 0 }" x-init="() => { let start = 0; const end = 890; const duration = 2000; const step = (end - start) / (duration / 16); const counter = setInterval(() => { start += step; if (start >= end) { start = end; clearInterval(counter); } $el.textContent = Math.floor(start); }, 16); }">890</div>
                    <div class="text-sm">Alumni Sukses</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="container mx-auto px-6 py-16">
        <!-- Alur Magang Section -->
        <section id="alur" class="mb-20 scroll-mt-24">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-primary mb-4">Alur Magang di <span class="text-secondary">Diskominfo SP Surakarta</span></h2>
                <p class="text-gray-600 max-w-3xl mx-auto">Ikuti langkah-langkah berikut untuk memulai perjalanan magang Anda bersama kami.</p>
            </div>
            
            <div class="max-w-4xl mx-auto bg-white rounded-3xl shadow-xl p-8 md:p-12 border border-accent/20">
                <div class="space-y-10">
                    <!-- Step 1 -->
                    <div class="alur-item">
                        <h3 class="text-xl font-bold text-primary mb-3">1. Pendaftaran Online</h3>
                        <p class="text-gray-600 mb-4">Calon peserta mengisi formulir pendaftaran online dan mengunggah berkas administrasi melalui portal InternFlow.</p>
                        <div class="bg-background p-4 rounded-xl">
                            <p class="text-sm font-medium text-primary">📋 Berkas yang diperlukan:</p>
                            <ul class="text-sm text-gray-600 mt-2 space-y-1">
                                <li class="flex items-center"><span class="w-2 h-2 bg-accent rounded-full mr-2"></span> CV/Resume terbaru</li>
                                <li class="flex items-center"><span class="w-2 h-2 bg-accent rounded-full mr-2"></span> Surat penempatan magang dari BKPSDM</li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Step 2 -->
                    <div class="alur-item">
                        <h3 class="text-xl font-bold text-primary mb-3">2. Verifikasi Administrasi</h3>
                        <p class="text-gray-600 mb-4">Tim Admin Kepegawaian memverifikasi kelengkapan dan kesesuaian berkas pendaftaran dalam waktu 3-5 hari kerja.</p>
                        <div class="flex items-center gap-4 mt-4">
                            <div class="flex-1 bg-green-50 p-3 rounded-lg border border-green-200">
                                <p class="text-sm font-medium text-green-700">Diterima</p>
                                <p class="text-xs text-green-600">Melanjutkan ke proses seleksi bidang</p>
                            </div>
                            <div class="flex-1 bg-yellow-50 p-3 rounded-lg border border-yellow-200">
                                <p class="text-sm font-medium text-yellow-700">Menunggu</p>
                                <p class="text-xs text-yellow-600">Diminta menunggu dalam proses seleksi bidang</p>
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
                        <p class="text-gray-600 mb-4">Admin Bidang menempatkan peserta ke bidang sesuai kompetensi dan ketersediaan kuota, kemudian menetapkan mentor pembimbing.</p>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
                            <div class="bg-secondary/10 p-3 rounded-lg text-center">
                                <p class="text-sm font-medium text-secondary">📊 Statistik</p>
                            </div>
                            <div class="bg-secondary/10 p-3 rounded-lg text-center">
                                <p class="text-sm font-medium text-secondary">💻 Informatika</p>
                            </div>
                            <div class="bg-secondary/10 p-3 rounded-lg text-center">
                                <p class="text-sm font-medium text-secondary">📑 Kesekretariatan</p>
                            </div>
                            <div class="bg-secondary/10 p-3 rounded-lg text-center">
                                <p class="text-sm font-medium text-secondary">🎯 E-Goverment</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Step 4 -->
                    <div class="alur-item">
                        <h3 class="text-xl font-bold text-primary mb-3">4. Pelaksanaan Magang</h3>
                        <p class="text-gray-600 mb-4">Peserta menjalani magang dengan panduan mentor, mengisi logbook harian, dan melaksanakan tugas sesuai bidang.</p>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                            <div class="bg-accent/10 p-3 rounded-lg text-center">
                                <p class="text-xs font-medium text-accent">📝 Logbook Harian</p>
                            </div>
                            <div class="bg-accent/10 p-3 rounded-lg text-center">
                                <p class="text-xs font-medium text-accent">✅ Absensi Digital</p>
                            </div>
                            <div class="bg-accent/10 p-3 rounded-lg text-center">
                                <p class="text-xs font-medium text-accent">📋 Tugas & Project</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Step 5 -->
                    <div class="alur-item">
                        <h3 class="text-xl font-bold text-primary mb-3">5. Evaluasi & Sertifikasi</h3>
                        <p class="text-gray-600 mb-4">Di akhir periode, mentor memberikan penilaian dan Admin Kepegawaian menerbitkan sertifikat digital yang dapat diunduh langsung.</p>
                        <div class="bg-gradient-to-r from-primary/10 to-secondary/10 p-4 rounded-xl mt-4">
                            <div class="flex items-center gap-4">
                                <div class="text-3xl">🏆</div>
                                <div>
                                    <p class="font-medium text-primary">Sertifikat Digital InternFlow</p>
                                    <p class="text-sm text-gray-600">Terverifikasi, memiliki QR code, dan dapat diunduh kapan saja melalui akun peserta</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-12 text-center">
                    <a href="/register" class="inline-block bg-primary text-white font-bold px-8 py-3 rounded-xl text-lg hover:shadow-xl hover:scale-105 transition duration-300">
                        Daftar Sekarang & Ikuti Alur Ini
                    </a>
                    <p class="text-gray-500 text-sm mt-4">Durasi magang: Minimal 1 bulan, maksimal 6 bulan</p>
                </div>
            </div>
        </section>
        <!-- CTA Section -->
        <section class="bg-gradient-to-r from-primary to-secondary text-white rounded-3xl p-12 text-center mb-20 shadow-2xl">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">Siap Memulai Perjalanan Magang Digital?</h2>
            <p class="text-xl mb-10 max-w-2xl mx-auto opacity-90">Bergabunglah dengan ratusan peserta dan institusi yang telah merasakan kemudahan <strong>InternFlow</strong>.</p>
            <a href="/register" class="inline-block bg-background text-primary font-bold px-10 py-4 rounded-xl text-lg hover:shadow-2xl hover:scale-105 transition duration-300 shadow-lg">
                Daftar Sebagai Peserta Magang
            </a>
        </section>
    </main>

    <!-- Footer -->
    <footer id="contact" class="bg-primary text-white pt-12 pb-8">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-4 gap-12">
                <div>
                    <a href="/" class="text-2xl font-bold">
                        <span class="text-accent">Intern</span><span class="text-white">Flow</span>
                    </a>
                    <p class="mt-4 text-gray-300 text-sm">Sistem Monitoring Magang Digital Dinas Komunikasi dan Informatika Kota Surakarta.</p>
                    
                    <!-- Logo Pemerintah -->
                    <div class="logo-grid mt-6">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/4/4b/Lambang_Kota_Surakarta.png/200px-Lambang_Kota_Surakarta.png" alt="Pemkot Surakarta" class="gov-logo" title="Pemerintah Kota Surakarta">
                        <img src="https://ppid.surakarta.go.id/assets/img/logo-ppid.png" alt="PPID Surakarta" class="gov-logo" title="PPID Kota Surakarta">
                        <img src="https://diskominfo.surakarta.go.id/assets/images/logo-diskominfo.png" alt="Diskominfo SP Surakarta" class="gov-logo" title="Diskominfo SP Surakarta">
                        <img src="https://solodata.surakarta.go.id/assets/img/logo-solodata.png" alt="SoloData" class="gov-logo" title="SoloData - Open Data Surakarta">
                    </div>
                </div>
                
                <div>
                    <h4 class="font-bold text-lg mb-6 text-accent">Tautan Cepat</h4>
                    <ul class="space-y-3">
                        <li><a href="#home" class="hover:text-accent transition">Beranda</a></li>
                        <li><a href="#alur" class="hover:text-accent transition">Alur Magang</a></li>
                        <li><a href="/login" class="hover:text-accent transition">Login</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-bold text-lg mb-6 text-accent">Kontak & Lokasi</h4>
                    <ul class="space-y-3 text-gray-300">
                        <li class="flex items-start"><span class="mr-3">📧</span> info.magang@diskominfo.surakarta.go.id</li>
                        <li class="flex items-start"><span class="mr-3">📞</span> (0271) 1234567</li>
                        <li class="flex items-start"><span class="mr-3">🕒</span> Senin - Jumat: 08:00 - 16:00 WIB</li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-bold text-lg mb-6 text-accent">Lokasi Kantor</h4>
                    <div class="map-container">
                        <!-- Google Maps Embed -->
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3955.0568719019525!2d110.82868719999999!3d-7.568779!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a166036a73eb1%3A0x7f6987f2e325554e!2sDinas%20Komunikasi%20Informatika%20Statistik%20Dan%20Persandian%20Kota%20Surakarta!5e0!3m2!1sid!2sid!4v1767716260186!5m2!1sid!2sid"
                            width="100%" 
                            height="200" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                    <p class="text-gray-300 text-xs mt-3">Diskominfo SP Surakarta terletak di pusat kota, mudah diakses dari berbagai wilayah.</p>
                </div>
            </div>
            
            <hr class="border-gray-700 my-8">
            
            <div class="text-center text-gray-400 text-sm">
                <p>© {{ date('Y') }} InternFlow - Dinas Komunikasi dan Informatika Kota Surakarta. Hak Cipta Dilindungi.</p>
                <p class="mt-2">Dibangun dengan Laravel & Tailwind CSS. Bagian dari transformasi digital Pemerintah Kota Surakarta.</p>
                <div class="mt-4 flex justify-center space-x-6">
                    <a href="https://surakarta.go.id" class="text-gray-400 hover:text-accent transition">surakarta.go.id</a>
                    <a href="https://diskominfo.surakarta.go.id" class="text-gray-400 hover:text-accent transition">diskominfo.surakarta.go.id</a>
                    <a href="https://ppid.surakarta.go.id" class="text-gray-400 hover:text-accent transition">ppid.surakarta.go.id</a>
                    <a href="https://solodata.surakarta.go.id" class="text-gray-400 hover:text-accent transition">solodata.surakarta.go.id</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Load Alpine.js for Interactivity -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>