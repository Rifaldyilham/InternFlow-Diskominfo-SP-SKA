<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>@yield('title') - Admin Bidang InternFlow</title>
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        :root {
            --sidebar-width: 260px;
            --header-height: 70px;
            --primary: #213448;
            --secondary: #547792;
            --accent: #94B4C1;
            --background: #EAE0CF;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }
        
        body {
            background-color: #f8fafc;
            color: #333;
            overflow-x: hidden;
        }
        
        .dashboard-container {
            display: flex;
            min-height: 100vh;
            position: relative;
        }
        
        /* SIDEBAR */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--primary) 0%, #1a2938 100%);
            color: white;
            position: fixed;
            height: 100vh;
            padding: 25px 0;
            transition: transform 0.3s ease;
            box-shadow: 5px 0 15px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            left: 0;
            top: 0;
            overflow-y: auto;
        }
        
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
        
        .sidebar-header {
            padding: 0 25px 30px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 25px;
        }
        
        .logo-admin {
            font-size: 1.8rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .logo-admin span:first-child { color: var(--accent); }
        
        .user-badge {
            background: rgba(148, 180, 193, 0.2);
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            margin-top: 10px;
            display: inline-block;
            border: 1px solid rgba(16, 185, 129, 0.5);
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 0;
        }
        
        .menu-item {
            padding: 15px 25px;
            display: flex;
            align-items: center;
            gap: 15px;
            cursor: pointer;
            transition: all 0.3s;
            color: rgba(255, 255, 255, 0.8);
            border-left: 4px solid transparent;
            text-decoration: none;
        }
        
        .menu-item:hover, .menu-item.active {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            border-left-color: var(--accent);
        }
        
        .menu-item i {
            font-size: 1.3rem;
            width: 25px;
        }
        
        .menu-text {
            font-size: 1rem;
            font-weight: 500;
            flex-grow: 1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .menu-badge {
            background: var(--accent);
            color: var(--primary);
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: bold;
            min-width: 20px;
            text-align: center;
            flex-shrink: 0;
        }
        
        /* MAIN CONTENT */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            padding: 0;
            transition: margin-left 0.3s ease;
            width: 100%;
            min-height: 100vh;
        }
        
        /* HEADER */
        .header {
            height: var(--header-height);
            background: white;
            padding: 0 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 99;
            min-height: 70px;
        }
        
        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
            flex: 1;
            min-width: 0;
        }
        
        .header-left h1 {
            font-size: 1.5rem;
            color: var(--primary);
            font-weight: 600;
            line-height: 1.3;
            margin: 0;
        }
        
        .header-left p {
            color: #666;
            font-size: 0.9rem;
            margin: 0;
        }
        
        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .notification {
            font-size: 1.5rem;
            color: var(--primary);
            cursor: pointer;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            transition: background 0.3s;
        }
        
        .notification:hover {
            background: rgba(0, 0, 0, 0.05);
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #ef4444;
            color: white;
            font-size: 0.7rem;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid white;
        }
        
        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 8px;
            transition: background 0.3s;
            flex-shrink: 0;
        }
        
        .user-profile:hover {
            background: rgba(0, 0, 0, 0.05);
        }
        
        .avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
            flex-shrink: 0;
        }
        
        .user-info {
            min-width: 0;
        }
        
        .user-info h4 {
            font-size: 1rem;
            font-weight: 600;
            color: var(--primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin: 0;
        }
        
        .user-info p {
            font-size: 0.8rem;
            color: #666;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        /* CONTENT AREA */
        .content-wrapper {
            padding: 30px;
            min-height: calc(100vh - var(--header-height));
        }
        
        /* STATS CARDS */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            border-top: 5px solid;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .stat-card.border-primary { border-color: var(--primary); }
        .stat-card.border-secondary { border-color: var(--secondary); }
        .stat-card.border-accent { border-color: var(--accent); }
        .stat-card.border-warning { border-color: #f59e0b; }
        
        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }
        
        .stat-icon.primary { background: rgba(26, 58, 95, 0.1); color: var(--primary); }
        .stat-icon.secondary { background: rgba(59, 130, 246, 0.1); color: var(--secondary); }
        .stat-icon.accent { background: rgba(16, 185, 129, 0.1); color: var(--accent); }
        .stat-icon.warning { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
        
        .stat-value {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--primary);
            line-height: 1;
        }
        
        .stat-label {
            color: #666;
            font-size: 0.95rem;
            margin-top: 8px;
        }
        
        /* BUTTONS */
        .btn {
            padding: 12px 24px;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-align: center;
            justify-content: center;
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background: #0f2842;
        }
        
        .btn-secondary {
            background: var(--secondary);
            color: white;
        }
        
        .btn-secondary:hover {
            background: #2563eb;
        }
        
        .btn-accent {
            background: var(--accent);
            color: white;
        }
        
        .btn-accent:hover {
            background: #0da271;
        }
        
        /* FORMS */
        .form-card {
            background: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }
        
        .form-section {
            margin-bottom: 30px;
            padding-bottom: 30px;
            border-bottom: 1px solid #eee;
        }
        
        .form-section:last-child {
            border-bottom: none;
        }
        
        .form-section h3 {
            color: var(--primary);
            margin-bottom: 20px;
            font-size: 1.3rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }
        
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s;
        }
        
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            outline: none;
            border-color: var(--secondary);
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        /* TABLES */
        .table-container {
            overflow-x: auto;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            -webkit-overflow-scrolling: touch;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px;
        }
        
        .data-table th {
            background: #f8fafc;
            padding: 15px;
            text-align: left;
            color: var(--primary);
            font-weight: 600;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .data-table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
            color: #666;
        }
        
        .data-table tr:hover {
            background: #f8fafc;
        }
        
        /* STATUS BADGES */
        .status-badge {
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
            white-space: nowrap;
        }
        
        .status-active { background: #e6fff3; color: #10b981; }
        .status-pending { background: #fff9e6; color: #f59e0b; }
        .status-completed { background: #e6f2ff; color: #3b82f6; }
        .status-inactive { background: #f0f0f0; color: #666; }
        
        /* MENU TOGGLE BUTTON */
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--primary);
            cursor: pointer;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.3s;
            flex-shrink: 0;
        }
        
        .menu-toggle:hover {
            background: rgba(0, 0, 0, 0.05);
        }
        
        /* RESPONSIVE BREAKPOINTS */
        
        /* Tablet (768px - 1023px) */
        @media (max-width: 1023px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .sidebar-overlay.active {
                display: block;
            }
            
            .main-content {
                margin-left: 0 !important;
            }
            
            .menu-toggle {
                display: flex;
            }
            
            .header {
                padding: 0 20px;
            }
            
            .content-wrapper {
                padding: 20px;
            }
            
            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 20px;
            }
            
            .stat-card {
                padding: 20px;
            }
            
            .stat-value {
                font-size: 1.8rem;
            }
            
            .stat-icon {
                width: 40px;
                height: 40px;
                font-size: 1.3rem;
            }
            
            .form-card {
                padding: 20px;
            }
            
            .form-row {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .btn {
                padding: 10px 20px;
                font-size: 0.85rem;
            }
        }
        
        /* Mobile (576px - 767px) */
        @media (max-width: 767px) {
            .header {
                padding: 0 15px;
                height: 60px;
                min-height: 60px;
            }
            
            .header-left h1 {
                font-size: 1.2rem;
            }
            
            .header-left p {
                font-size: 0.8rem;
                display: none;
            }
            
            .user-info {
                display: none;
            }
            
            .user-profile {
                padding: 5px;
            }
            
            .avatar {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }
            
            .notification {
                width: 35px;
                height: 35px;
                font-size: 1.3rem;
            }
            
            .notification-badge {
                width: 18px;
                height: 18px;
                font-size: 0.65rem;
                top: -3px;
                right: -3px;
            }
            
            .content-wrapper {
                padding: 15px;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
            }
            
            .stat-card {
                padding: 15px;
            }
            
            .stat-value {
                font-size: 1.5rem;
            }
            
            .stat-label {
                font-size: 0.85rem;
            }
            
            .menu-badge {
                padding: 2px 8px;
                font-size: 0.7rem;
                min-width: 18px;
            }
            
            .menu-item {
                padding: 12px 20px;
                gap: 12px;
            }
            
            .menu-item i {
                font-size: 1.2rem;
            }
            
            .menu-text {
                font-size: 0.9rem;
            }
            
            .logo-admin {
                font-size: 1.5rem;
            }
            
            .user-badge {
                padding: 6px 12px;
                font-size: 0.75rem;
            }
        }
        
        /* Small Mobile (≤ 575px) */
        @media (max-width: 575px) {
            .header {
                padding: 0 12px;
            }
            
            .menu-toggle {
                width: 35px;
                height: 35px;
                font-size: 1.3rem;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }
            
            .stat-card {
                padding: 15px;
            }
            
            .stat-header {
                flex-direction: row;
                align-items: center;
            }
            
            .stat-icon {
                order: -1;
                margin-right: 15px;
            }
            
            .content-wrapper {
                padding: 12px;
            }
            
            .form-card {
                padding: 15px;
                margin-bottom: 20px;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
            
            .header-right {
                gap: 10px;
            }
            
            .sidebar {
                width: 100%;
                max-width: 300px;
            }
        }
    </style>
</head>
<body x-data="{ sidebarOpen: false, isMobile: window.innerWidth < 1024 }" 
      @resize.window="isMobile = window.innerWidth < 1024">
    
    <!-- Overlay untuk mobile -->
    <div class="sidebar-overlay" x-show="sidebarOpen && isMobile" 
         @click="sidebarOpen = false" 
         x-transition.opacity></div>
    
    <div class="dashboard-container">
        <!-- SIDEBAR -->
        <aside class="sidebar" :class="sidebarOpen || !isMobile ? 'active' : ''">
            <div class="sidebar-header">
                <div class="logo-admin">
                    <span>Intern</span><span>Flow</span>
                </div>
                <div class="user-badge">ADMIN BIDANG</div>
            </div>
            
            <ul class="sidebar-menu">
                <a href="/admin-bidang/dashboard" class="menu-item active">
                    <i class='bx bx-home-alt'></i>
                    <span class="menu-text">Dashboard</span>
                </a>
                <a href="/admin-bidang/mentor" class="menu-item">
                    <i class='bx bx-group'></i>
                    <span class="menu-text">Penetapan Mentor</span>
                </a>
            </ul>
            
            <div style="padding: 25px; margin-top: auto; border-top: 1px solid rgba(255,255,255,0.1);">
                <div style="display: flex; align-items: center; gap: 10px; color: rgba(255,255,255,0.7); font-size: 0.9rem; cursor: pointer; padding: 10px; border-radius: 8px; transition: background 0.3s;" 
                     onclick="confirmLogout()"
                     onmouseover="this.style.background='rgba(255,255,255,0.1)'"
                     onmouseout="this.style.background='transparent'">
                    <i class='bx bx-log-out'></i>
                    <span>Keluar</span>
                </div>
            </div>
        </aside>
        
        <!-- MAIN CONTENT -->
        <main class="main-content" :style="isMobile ? 'margin-left: 0' : 'margin-left: var(--sidebar-width)'">
            <!-- HEADER -->
            <header class="header">
                <div class="header-left">
                    <button class="menu-toggle" @click="sidebarOpen = !sidebarOpen">
                        <i class='bx bx-menu'></i>
                    </button>
                    <div style="min-width: 0;">
                        <h1>@yield('title', 'Dashboard Admin Bidang')</h1>
                        <p>@yield('subtitle', 'Sistem Monitoring Magang Diskominfo SP Surakarta')</p>
                    </div>
                </div>
                
                <div class="header-right">
                    <div style="position: relative;">
                        <div class="notification" onclick="showNotifications()">
                            <i class='bx bx-bell'></i>
                            <span class="notification-badge">3</span>
                        </div>
                    </div>
                    
                    <div class="user-profile" onclick="window.location.href='/admin-bidang/profil'">
                        <div class="avatar">AB</div>
                        <div class="user-info">
                            <h4>Dr. Budi Setiawan, M.Si.</h4>
                            <p>Admin Bidang Informatika</p>
                        </div>
                        <i class='bx bx-chevron-down'></i>
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
        // Set active menu based on current URL
        function setActiveMenu() {
            const currentPath = window.location.pathname;
            document.querySelectorAll('.menu-item').forEach(item => {
                item.classList.remove('active');
                if (item.getAttribute('href') === currentPath) {
                    item.classList.add('active');
                }
            });
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.querySelector('.sidebar');
            const menuToggle = document.querySelector('.menu-toggle');
            const overlay = document.querySelector('.sidebar-overlay');
            const isMobile = window.innerWidth < 1024;
            
            if (isMobile && 
                sidebar.classList.contains('active') && 
                !sidebar.contains(event.target) && 
                !menuToggle.contains(event.target) &&
                overlay && overlay.classList.contains('active')) {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            }
        });

        // Toggle sidebar
        document.querySelector('.menu-toggle')?.addEventListener('click', function() {
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            
            sidebar.classList.toggle('active');
            if (overlay) {
                overlay.classList.toggle('active');
            }
        });
        
        // Set menu item active on click
        document.querySelectorAll('.menu-item').forEach(item => {
            item.addEventListener('click', function() {
                if (window.innerWidth < 1024) {
                    document.querySelector('.sidebar').classList.remove('active');
                    document.querySelector('.sidebar-overlay').classList.remove('active');
                }
                document.querySelectorAll('.menu-item').forEach(i => i.classList.remove('active'));
                this.classList.add('active');
            });
        });
        
        // Logout confirmation
        function confirmLogout() {
            if(confirm('Apakah Anda yakin ingin keluar?')) {
                window.location.href = '/login';
            }
        }
        
        // Show notifications
        function showNotifications() {
            const notifications = [
                '5 peserta baru perlu penempatan bidang',
                '3 peserta mendekati akhir masa magang',
                'Permintaan tambahan mentor untuk bidang Statistik'
            ];
            
            alert('Notifikasi:\n' + notifications.map((n, i) => `${i+1}. ${n}`).join('\n'));
        }
        
        // Update badge counts
        function updateBadgeCounts() {
            // In real app, fetch from API
            document.getElementById('pesertaCount').textContent = '15';
        }
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            updateBadgeCounts();
            setActiveMenu();
            
            // Auto close sidebar on mobile if open
            if (window.innerWidth < 1024) {
                document.querySelector('.sidebar').classList.remove('active');
                document.querySelector('.sidebar-overlay').classList.remove('active');
            }
        });
    </script>
    
    @yield('scripts')
</body>
</html>