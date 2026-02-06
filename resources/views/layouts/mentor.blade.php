<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Mentor InternFlow</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Tailwind CSS -->
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
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Boxicons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    
    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    <!-- Custom Layout CSS -->
    <link rel="stylesheet" href="{{ asset('css/layouts/layouts.css') }}">
    
    @yield('styles')
</head>

<body x-data="app">
    <!-- OVERLAY UNTUK MOBILE -->
    <div class="sidebar-overlay" :class="overlayOpen ? 'active' : ''" 
         @click="sidebarOpen = false; overlayOpen = false;"></div>

    <div class="dashboard-container">
        <!-- SIDEBAR -->
        <aside class="sidebar" :class="sidebarOpen ? 'active' : ''">
            <div class="sidebar-header">
                <div class="logo-peserta">
                    <span>Intern</span><span>Flow</span>
                </div>
                <div class="user-badge">MENTOR/PEMBIMBING</div>
            </div>

            <!-- Menu Navigasi -->
            <ul class="sidebar-menu">
                <a href="/mentor/bimbingan" class="menu-item" :class="isActive('/mentor/bimbingan') ? 'active' : ''">
                    <i class='bx bx-group'></i>
                    <span class="menu-text">Daftar Bimbingan</span>
                </a>
                <a href="/mentor/verifikasi" class="menu-item" :class="isActive('/mentor/verifikasi') ? 'active' : ''">
                    <i class='bx bx-check-circle'></i>
                    <span class="menu-text">Verifikasi</span>
                </a>
                <a href="/mentor/penilaian" class="menu-item" :class="isActive('/mentor/penilaian') ? 'active' : ''">
                    <i class='bx bx-star'></i>
                    <span class="menu-text">Input Penilaian</span>
                </a>
            </ul>

            <!-- Footer Sidebar -->
            <div class="sidebar-footer">
                <div class="logout-btn" onclick="confirmLogout()">
                    <i class='bx bx-log-out'></i>
                    <span>Keluar</span>
                </div>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main-content">
            <!-- HEADER -->
            <header class="header">
                <div class="header-left">
                    <button class="menu-toggle" @click="sidebarOpen = true; overlayOpen = true">
                        <i class='bx bx-menu'></i>
                    </button>
                    <div>
                        <h1>@yield('title', 'Dashboard Mentor')</h1>
                        <p>@yield('subtitle', 'Sistem Monitoring Magang Diskominfo SP Surakarta')</p>
                    </div>
                </div>

                <div class="header-right">
                    <div class="user-profile">
                        <div class="avatar" id="userAvatar">
                            {{-- Avatar akan diisi oleh JavaScript --}}
                        </div>
                        <div class="user-info">
                            <h4 id="userName">{{ Auth::check() ? Auth::user()->name : 'Nama Mentor' }}</h4>
                            <p>Mentor/Pembimbing</p>
                        </div>
                    </div>
                </div>
            </header>

            <!-- CONTENT -->
            <div class="content-wrapper">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        // Inisialisasi Alpine.js
        document.addEventListener('alpine:init', () => {
            Alpine.data('app', () => ({
                sidebarOpen: false,
                overlayOpen: false,

                init() {
                    this.updateUserName();
                    this.setupEventListeners();
                },
                
                isActive(path) {
                    const currentPath = window.location.pathname;
                    if (path === '/mentor/dashboard' && (currentPath === '/' || currentPath === '')) {
                        return true;
                    }
                    return currentPath.startsWith(path);
                },
                
                updateUserName() {
                    // Nama user dari Laravel Auth
                    const userName = document.getElementById('userName').textContent;
                    const userInitials = this.getInitials(userName);
                    
                    document.getElementById('userAvatar').textContent = userInitials;
                },
                
                getInitials(name) {
                    return name
                        .split(' ')
                        .map(word => word.charAt(0).toUpperCase())
                        .join('')
                        .substring(0, 2);
                },
                
                setupEventListeners() {
                    // Handle overlay click untuk menutup sidebar
                    const overlay = document.querySelector('.sidebar-overlay');
                    if (overlay) {
                        overlay.addEventListener('click', () => {
                            this.sidebarOpen = false;
                            this.overlayOpen = false;
                        });
                    }
                    
                    // Handle resize window untuk desktop
                    window.addEventListener('resize', () => {
                        if (window.innerWidth > 992) {
                            this.sidebarOpen = false;
                            this.overlayOpen = false;
                        }
                    });
                }
            }));
        });

        // Fungsi logout
         // Fungsi logout
        function confirmLogout() {
    Swal.fire({
        title: 'Logout?',
        text: "Anda akan keluar dari sistem Mentor.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#213448',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Keluar',
        cancelButtonText: 'Batal',
        width: '360px',
        padding: '1rem'
    }).then((result) => {
        if (result.isConfirmed) {
            // Gunakan form POST untuk logout Laravel
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ route('logout') }}";
            
            // CSRF token
            const token = document.querySelector('meta[name="csrf-token"]').content;
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = token;
            form.appendChild(csrfInput);
            
            document.body.appendChild(form);
            form.submit();
        }
    });
}

        // Tutup sidebar saat link diklik di mobile
        document.querySelectorAll('.menu-item').forEach(item => {
            item.addEventListener('click', function() {
                if (window.innerWidth <= 992) {
                    const sidebar = document.querySelector('.sidebar');
                    const overlay = document.querySelector('.sidebar-overlay');
                    sidebar.classList.remove('active');
                    if (overlay) {
                        overlay.classList.remove('active');
                    }
                }
            });
        });

        // Inisialisasi saat DOM ready
        document.addEventListener('DOMContentLoaded', function() {
            // Set active menu berdasarkan URL
            const currentPath = window.location.pathname;
            document.querySelectorAll('.menu-item').forEach(item => {
                const href = item.getAttribute('href');
                if (currentPath === href || (href !== '/' && currentPath.startsWith(href))) {
                    item.classList.add('active');
                }
            });
            
            // Fallback untuk home
            if (currentPath === '/' || currentPath === '') {
                const homeItem = document.querySelector('.menu-item[href="/mentor/dashboard"]');
                if (homeItem) homeItem.classList.add('active');
            }
            
            // Update avatar dengan inisial dari nama user yang login
            const userName = document.getElementById('userName').textContent;
            const avatar = document.getElementById('userAvatar');
            
            function getInitials(name) {
                return name
                    .split(' ')
                    .map(word => word.charAt(0).toUpperCase())
                    .join('')
                    .substring(0, 2);
            }
            
            if (avatar) {
                avatar.textContent = getInitials(userName);
            }
        });
    </script>

    @yield('scripts')
</body>
</html>
