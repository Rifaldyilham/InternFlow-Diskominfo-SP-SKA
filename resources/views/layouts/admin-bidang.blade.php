<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Admin Bidang InternFlow</title>
    
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

<body x-data="{ sidebarOpen: false, overlayOpen: false }">
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
                <div class="user-badge">ADMIN BIDANG</div>
            </div>

            <!-- Menu Navigasi -->
            <ul class="sidebar-menu">
                <a href="/admin-bidang/mentor" class="menu-item" :class="isActive('/admin-bidang/mentor') ? 'active' : ''">
                    <i class='bx bx-group'></i>
                    <span class="menu-text">Manajemen Mentor</span>
                </a>
                <a href="/admin-bidang/penempatan" class="menu-item" :class="isActive('/admin-bidang/penempatan') ? 'active' : ''">
                    <i class='bx bx-user'></i>
                    <span class="menu-text">Penempatan Peserta</span>
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
                        <h1>@yield('title', 'Dashboard Admin Bidang')</h1>
                        <p>@yield('subtitle', 'Sistem Monitoring Magang Diskominfo SP Surakarta')</p>
                    </div>
                </div>

                <div class="header-right">
                    <div class="user-profile" onclick="window.location.href='/admin-bidang/profil'">
                        <div class="avatar" id="userAvatar">AB</div>
                        <div class="user-info">
                            <h4 id="userName">Dr. Budi Setiawan, M.Si.</h4>
                            <p>Admin Bidang Informatika</p>
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
                init() {
                    this.updateUserName();
                    this.setupEventListeners();
                },
                
                isActive(path) {
                    const currentPath = window.location.pathname;
                    if (path === '/admin-bidang/dashboard' && (currentPath === '/' || currentPath === '')) {
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
        function confirmLogout() {
            if (confirm('Apakah Anda yakin ingin keluar?')) {
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
                const homeItem = document.querySelector('.menu-item[href="/admin-bidang/dashboard"]');
                if (homeItem) homeItem.classList.add('active');
            }
            
            // Update avatar dengan inisial
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