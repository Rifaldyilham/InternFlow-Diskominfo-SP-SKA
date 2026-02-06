<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Admin InternFlow</title>
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
                <div class="user-badge">ADMIN KEPEGAWAIAN</div>
            </div>

            <!-- Menu Navigasi -->
            <ul class="sidebar-menu">
                <a href="/admin/verifikasi-berkas" class="menu-item" :class="isActive('/admin/verifikasi-berkas') ? 'active' : ''">
                    <i class='bx bx-file'></i>
                    <span class="menu-text">Verifikasi Berkas</span>
                </a>
                <a href="/admin/manajemen-akun" class="menu-item" :class="isActive('/admin/manajemen-akun') ? 'active' : ''">
                    <i class='bx bx-user'></i>
                    <span class="menu-text">Manajemen Akun</span>
                </a>
                <a href="/admin/manajemen-bidang" class="menu-item" :class="isActive('/admin/manajemen-bidang') ? 'active' : ''">
                    <i class='bx bx-briefcase'></i>
                    <span class="menu-text">Manajemen Bidang</span>
                </a>
                <a href="/admin/sertifikat" class="menu-item" :class="isActive('/admin/sertifikat') ? 'active' : ''">
                    <i class='bx bx-certification'></i>
                    <span class="menu-text">Sertifikat</span>
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
                        <h1>@yield('title', 'Dashboard Admin')</h1>
                        <p>@yield('subtitle', 'Sistem Monitoring Magang Diskominfo SP Surakarta')</p>
                    </div>
                </div>

                <div class="header-right">
                    <div class="user-profile">
                        <div class="avatar" id="userAvatar">
                            {{-- Avatar akan diisi oleh JavaScript --}}
                        </div>
                        <div class="user-info">
                            <h4 id="userName">{{ Auth::check() ? Auth::user()->name : 'Admin Kepegawaian' }}</h4>
                            <p>Admin Kepegawaian</p>
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
function confirmLogout() {
    Swal.fire({
        title: 'Logout?',
        text: "Anda akan keluar dari sistem admin.",
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
        
document.addEventListener('alpine:init', () => {
    Alpine.data('app', () => ({
        sidebarOpen: false,
        overlayOpen: false,

        isActive(path) {
            return window.location.pathname.startsWith(path);
        },

        init() {
            this.updateUserAvatar();
        },

        updateUserAvatar() {
            const nameEl = document.getElementById('userName');
            const avatarEl = document.getElementById('userAvatar');
            if (!nameEl || !avatarEl) return;

            const initials = nameEl.textContent
                .split(' ')
                .map(word => word.charAt(0).toUpperCase())
                .join('')
                .substring(0, 2);

            avatarEl.textContent = initials;
        }
    }))
})
</script>


    @yield('scripts')
</body>
</html>