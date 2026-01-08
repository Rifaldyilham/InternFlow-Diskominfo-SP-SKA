<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Admin InternFlow</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
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
        }
        
        body {
            background-color: #f5f7fa;
            color: #333;
            overflow-x: hidden;
        }
        
        .admin-container {
            display: flex;
            min-height: 100vh;
        }
        
        /* SIDEBAR */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--primary) 0%, #1a2938 100%);
            color: white;
            position: fixed;
            height: 100vh;
            padding: 25px 0;
            transition: all 0.3s ease;
            box-shadow: 5px 0 15px rgba(0, 0, 0, 0.1);
            z-index: 100;
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
        
        .logo-admin span:first-child {
            color: var(--accent);
        }
        
        .role-badge {
            background: rgba(148, 180, 193, 0.2);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            margin-top: 8px;
            display: inline-block;
            border: 1px solid rgba(148, 180, 193, 0.3);
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
        }
        
        .menu-item:hover, .menu-item.active {
            background: rgba(255, 255, 255, 0.1);
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
        }
        
        .menu-badge {
            background: var(--accent);
            color: var(--primary);
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 0.75rem;
            margin-left: auto;
            font-weight: bold;
        }
        
        /* MAIN CONTENT */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            padding: 0;
            transition: all 0.3s;
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
        }
        
        .header-left h1 {
            font-size: 1.5rem;
            color: var(--primary);
            font-weight: 600;
        }
        
        .header-left p {
            color: #666;
            font-size: 0.9rem;
        }
        
        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .notification {
            position: relative;
            cursor: pointer;
            font-size: 1.5rem;
            color: var(--primary);
        }
        
        .notification-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #ff4757;
            color: white;
            font-size: 0.7rem;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
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
        }
        
        .user-info h4 {
            font-size: 0.95rem;
            color: var(--primary);
        }
        
        .user-info p {
            font-size: 0.8rem;
            color: #666;
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
        
        .stat-card.border-blue { border-color: var(--primary); }
        .stat-card.border-green { border-color: #2ed573; }
        .stat-card.border-orange { border-color: #ffa502; }
        .stat-card.border-purple { border-color: #7158e2; }
        
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
        }
        
        .stat-icon.blue { background: rgba(33, 52, 72, 0.1); color: var(--primary); }
        .stat-icon.green { background: rgba(46, 213, 115, 0.1); color: #2ed573; }
        .stat-icon.orange { background: rgba(255, 165, 2, 0.1); color: #ffa502; }
        .stat-icon.purple { background: rgba(113, 88, 226, 0.1); color: #7158e2; }
        
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
        
        .stat-change {
            font-size: 0.85rem;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .stat-change.positive { color: #2ed573; }
        .stat-change.negative { color: #ff4757; }
        
        /* TABLES */
        .table-container {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-top: 30px;
        }
        
        .table-header {
            padding: 25px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .table-header h3 {
            color: var(--primary);
            font-size: 1.3rem;
        }
        
        .table-actions {
            display: flex;
            gap: 15px;
        }
        
        .btn {
            padding: 10px 20px;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background: #1a2938;
        }
        
        .btn-secondary {
            background: var(--accent);
            color: var(--primary);
        }
        
        .btn-secondary:hover {
            background: #7fa5b5;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        thead {
            background: #f8f9fa;
        }
        
        th {
            padding: 18px 25px;
            text-align: left;
            color: var(--primary);
            font-weight: 600;
            border-bottom: 2px solid #eee;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        td {
            padding: 18px 25px;
            border-bottom: 1px solid #eee;
            color: #555;
        }
        
        tr:hover {
            background: #f8fafc;
        }
        
        .status-badge {
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
        }
        
        .status-pending { background: #fff9e6; color: #f1c40f; }
        .status-approved { background: #e6fff3; color: #2ecc71; }
        .status-rejected { background: #ffe6e6; color: #e74c3c; }
        .status-active { background: #e6f2ff; color: #3498db; }
        
        .action-buttons {
            display: flex;
            gap: 8px;
        }
        
        .action-btn {
            width: 35px;
            height: 35px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
            color: white;
            font-size: 1rem;
        }
        
        .action-btn.view { background: var(--accent); }
        .action-btn.edit { background: #3498db; }
        .action-btn.delete { background: #e74c3c; }
        
        .action-btn:hover {
            opacity: 0.9;
            transform: scale(1.1);
        }
        
        /* RESPONSIVE */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .menu-toggle {
                display: block !important;
            }
        }
        
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--primary);
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- SIDEBAR -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo-admin">
                    <span>Intern</span><span>Flow</span>
                </div>
                <div class="role-badge">ADMIN KEPEGAWAIAN</div>
            </div>
            
            <ul class="sidebar-menu">
                <li class="menu-item active">
                    <i class='bx bx-home-alt'></i>
                    <span class="menu-text">Dashboard</span>
                </li>
                <li class="menu-item">
                    <i class='bx bx-user'></i>
                    <span class="menu-text">Manajemen Peserta</span>
                    <span class="menu-badge">12</span>
                </li>
                <li class="menu-item">
                    <i class='bx bx-briefcase'></i>
                    <span class="menu-text">Manajemen Bidang</span>
                </li>
                <li class="menu-item">
                    <i class='bx bx-file'></i>
                    <span class="menu-text">Verifikasi Berkas</span>
                    <span class="menu-badge">5</span>
                </li>
                <li class="menu-item">
                    <i class='bx bx-certification'></i>
                    <span class="menu-text">Sertifikat</span>
                </li>
            </ul>
            
            <div style="padding: 25px; margin-top: 30px; border-top: 1px solid rgba(255,255,255,0.1);">
                <div style="display: flex; align-items: center; gap: 10px; color: rgba(255,255,255,0.7); font-size: 0.9rem;">
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
                    <button class="menu-toggle">
                        <i class='bx bx-menu'></i>
                    </button>
                    <div>
                        <h1>@yield('title', 'Dashboard')</h1>
                        <p>@yield('subtitle', 'Sistem Monitoring Magang Diskominfo SP Surakarta')</p>
                    </div>
                </div>
                
                <div class="header-right">
                    <div class="notification">
                        <i class='bx bx-bell'></i>
                        <span class="notification-badge">3</span>
                    </div>
                    
                    <div class="user-profile">
                        <div class="avatar">AK</div>
                        <div class="user-info">
                            <h4>Admin Kepegawaian</h4>
                            <p>Diskominfo SP Surakarta</p>
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
        // Toggle sidebar untuk mobile
        document.querySelector('.menu-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
        });
        
        // Set menu item active
        document.querySelectorAll('.menu-item').forEach(item => {
            item.addEventListener('click', function() {
                document.querySelectorAll('.menu-item').forEach(i => i.classList.remove('active'));
                this.classList.add('active');
            });
        });
        
        // Logout confirmation
        document.querySelector('.bx-log-out').parentElement.addEventListener('click', function() {
            if(confirm('Apakah Anda yakin ingin keluar?')) {
                window.location.href = '/login';
            }
        });
    </script>
    
    @yield('scripts')
</body>
</html>