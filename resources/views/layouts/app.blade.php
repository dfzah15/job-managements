<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Job Management</title>

    <!-- ============================================ -->
    <!-- CSS EXTERNAL -->
    <!-- ============================================ -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">

    <!-- ============================================ -->
    <!-- PWA / MOBILE APP -->
    <!-- ============================================ -->
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Job Management">
    <meta name="theme-color" content="#2c3e50">
    <link rel="manifest" href="/manifest.json">
    <link rel="icon" type="image/png" sizes="192x192" href="/images/icons/icon-192x192.png">

    <!-- ============================================ -->
    <!-- STYLE CUSTOM -->
    <!-- ============================================ -->
    <style>
        /* ===== ROOT VARIABLES ===== */
        :root {
            --sidebar-width: 280px;
            --sidebar-bg: #2c3e50;
            --sidebar-header-bg: #1a2632;
            --sidebar-hover: #34495e;
            --sidebar-active: #3498db;
            --navbar-bg: #ffffff;
            --body-bg: #f8f9fa;
            --text-light: #b3c2d1;
        }

        /* ===== BODY ===== */
        body {
            background-color: var(--body-bg);
            overflow-x: hidden;
        }

        /* ===== WRAPPER ===== */
        .wrapper {
            display: flex;
            width: 100%;
            align-items: stretch;
        }

        /* ============================================ */
        /* SIDEBAR */
        /* ============================================ */
        #sidebar {
            min-width: var(--sidebar-width);
            max-width: var(--sidebar-width);
            background: var(--sidebar-bg);
            color: #fff;
            transition: all 0.3s ease;
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            display: flex;
            flex-direction: column;
        }

        #sidebar.active {
            margin-left: calc(-1 * var(--sidebar-width));
        }

        /* -- Sidebar Header -- */
        #sidebar .sidebar-header {
            padding: 20px;
            background: var(--sidebar-header-bg);
            border-bottom: 1px solid #3d5166;
        }

        #sidebar .sidebar-header h3 {
            font-size: 1.2rem;
            margin: 0;
        }

        #sidebar .sidebar-header small {
            opacity: 0.7;
        }

        /* -- Sidebar Menu -- */
        #sidebar .components-wrapper {
            flex: 1;
            overflow-y: auto;
            padding: 10px 0 20px 0;
        }

        #sidebar .components-wrapper::-webkit-scrollbar {
            width: 5px;
        }

        #sidebar .components-wrapper::-webkit-scrollbar-track {
            background: transparent;
        }

        #sidebar .components-wrapper::-webkit-scrollbar-thumb {
            background: #4a5a6a;
            border-radius: 10px;
        }

        #sidebar .components-wrapper::-webkit-scrollbar-thumb:hover {
            background: #5a6a7a;
        }

        #sidebar ul.components {
            padding: 0;
            margin: 0;
            list-style: none;
        }

        #sidebar ul li {
            position: relative;
        }

        #sidebar ul li a {
            padding: 10px 20px;
            font-size: 0.9rem;
            display: block;
            color: var(--text-light);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        #sidebar ul li a:hover {
            color: #fff;
            background: var(--sidebar-hover);
            border-left-color: var(--sidebar-active);
        }

        #sidebar ul li a i {
            margin-right: 10px;
            width: 22px;
            text-align: center;
            font-size: 1.1rem;
        }

        #sidebar ul li.active > a {
            color: #fff;
            background: var(--sidebar-active);
            border-left-color: #fff;
        }

        #sidebar ul li.active > a:hover {
            background: #2980b9;
        }

        /* -- Sidebar Dropdown -- */
        #sidebar ul li a.dropdown-toggle {
            position: relative;
            cursor: pointer;
        }

        #sidebar ul li a.dropdown-toggle::after {
            content: '\f282';
            font-family: 'bootstrap-icons';
            position: absolute;
            right: 20px;
            transition: transform 0.3s ease;
        }

        #sidebar ul li a.dropdown-toggle[aria-expanded="true"]::after {
            transform: rotate(180deg);
        }

        #sidebar ul ul {
            background: rgba(255, 255, 255, 0.05);
            padding-left: 0;
            list-style: none;
        }

        #sidebar ul ul li a {
            padding: 8px 20px 8px 50px;
            font-size: 0.82rem;
        }

        #sidebar ul ul li a i {
            width: 20px;
            font-size: 0.9rem;
        }

        #sidebar ul ul li.active > a {
            background: rgba(52, 152, 219, 0.3);
            color: #fff;
            border-left-color: var(--sidebar-active);
        }

        #sidebar ul ul li a:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        /* -- Jobdesk Sub Menu (Level 3) -- */
        #sidebar ul ul ul {
            padding-left: 15px;
            background: rgba(0, 0, 0, 0.15);
            display: none;
        }

        #sidebar ul ul ul.show {
            display: block;
        }

        #sidebar ul ul ul li a {
            padding: 6px 20px 6px 65px;
            font-size: 0.78rem;
        }

        #sidebar ul ul ul li a i {
            width: 18px;
            font-size: 0.8rem;
        }

        #sidebar ul ul ul li.active > a {
            background: rgba(52, 152, 219, 0.3);
            color: #fff;
            border-left-color: var(--sidebar-active);
        }

        /* -- Sidebar Footer / Logo -- */
        #sidebar .sidebar-footer {
            padding: 15px 20px;
            border-top: 1px solid #3d5166;
            text-align: center;
        }

        #sidebar .sidebar-footer img {
            max-height: 35px;
            opacity: 0.6;
        }

        /* ============================================ */
        /* CONTENT */
        /* ============================================ */
        #content {
            width: 100%;
            margin-left: var(--sidebar-width);
            transition: all 0.3s ease;
            min-height: 100vh;
            padding: 0;
        }

        #content.active {
            margin-left: 0;
        }

        /* ============================================ */
        /* NAVBAR */
        /* ============================================ */
        .navbar-custom {
            background: var(--navbar-bg);
            padding: 12px 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .navbar-custom .sidebar-toggle {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--sidebar-bg);
            cursor: pointer;
            padding: 0;
            transition: color 0.3s;
        }

        .navbar-custom .sidebar-toggle:hover {
            color: var(--sidebar-active);
        }

        .navbar-custom .brand-title {
            font-weight: 600;
            font-size: 1.1rem;
            color: #2c3e50;
        }

        /* ============================================ */
        /* PAGE CONTENT */
        /* ============================================ */
        .page-content {
            padding: 20px;
        }

        /* ============================================ */
        /* RESPONSIVE */
        /* ============================================ */
        @media (max-width: 768px) {
            #sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
            }

            #sidebar.active {
                margin-left: 0;
            }

            #content {
                margin-left: 0;
            }

            #content.active {
                margin-left: var(--sidebar-width);
            }

            .navbar-custom .sidebar-toggle {
                font-size: 1.2rem;
            }

            .navbar-custom .brand-title {
                font-size: 0.95rem;
            }
        }

        /* ============================================ */
        /* AUTH PAGES (Login, Register) */
        /* ============================================ */
        .auth-page .wrapper {
            display: block;
        }

        .auth-page #sidebar {
            display: none;
        }

        .auth-page #content {
            margin-left: 0;
        }

        /* ============================================ */
        /* ALERT CUSTOM */
        /* ============================================ */
        .alert {
            border-radius: 8px;
            border-left: 4px solid transparent;
        }

        .alert-success {
            border-left-color: #28a745;
        }

        .alert-danger {
            border-left-color: #dc3545;
        }

        .alert-warning {
            border-left-color: #ffc107;
        }

        .alert-info {
            border-left-color: #17a2b8;
        }

        /* ============================================ */
        /* ANIMASI */
        /* ============================================ */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .page-content .alert {
            animation: fadeIn 0.3s ease;
        }
    </style>

    @stack('styles')
</head>


<body class="@if(Route::is('login') || Route::is('register') || Route::is('password.*')) auth-page @endif">

    <div class="wrapper">

        <!-- ============================================ -->
        <!-- SIDEBAR -->
        <!-- ============================================ -->
        @auth
        @if(!Route::is('login') && !Route::is('register') && !Route::is('password.*'))
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3><i class="bi bi-clipboard-check"></i> Job Management</h3>
                <small class="text-muted">Multi Jobdesk System</small>
            </div>

            <div class="components-wrapper">
                <ul class="components">

                    <!-- ============================================ -->
                    <!-- DASHBOARD -->
                    <!-- ============================================ -->
                    <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <a href="{{ route('dashboard') }}">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>

                    <!-- ============================================ -->
                    <!-- JOBDESK MENU (Dinamis) -->
                    <!-- ============================================ -->
                    @php
                        $jobdesks = auth()->user()->getSidebarJobdesks();
                    @endphp

                    @if($jobdesks->count() > 0)
                    <li class="nav-item dropdown-toggle">
                        <a href="#jobdeskSubmenu" data-bs-toggle="collapse"
                           aria-expanded="{{ request()->routeIs('checklist.*') || request()->routeIs('laporan.*') || request()->routeIs('laporan-eksekusi.*') || request()->routeIs('maintenance.*') || request()->routeIs('layout.*') ? 'true' : 'false' }}"
                           class="dropdown-toggle">
                            <i class="bi bi-grid"></i> Jobdesk
                        </a>
                        <ul class="collapse list-unstyled {{ request()->routeIs('checklist.*') || request()->routeIs('laporan.*') || request()->routeIs('laporan-eksekusi.*') || request()->routeIs('maintenance.*') || request()->routeIs('layout.*') ? 'show' : '' }}"
                            id="jobdeskSubmenu">
                            @foreach($jobdesks as $jobdesk)
                            @php
                                $isJobdeskActive = request()->segment(1) == $jobdesk->slug;
                            @endphp
                            <li>
                                <a href="#jobdesk-{{ $jobdesk->slug }}" data-bs-toggle="collapse"
                                   aria-expanded="{{ $isJobdeskActive ? 'true' : 'false' }}"
                                   class="dropdown-toggle {{ $isJobdeskActive ? 'active' : '' }}">
                                    <i class="{{ $jobdesk->icon }}"></i> {{ $jobdesk->name }}
                                </a>
                                <ul class="collapse list-unstyled {{ $isJobdeskActive ? 'show' : '' }}"
                                    id="jobdesk-{{ $jobdesk->slug }}">
                                    <li class="{{ request()->routeIs('checklist.*') && request()->segment(1) == $jobdesk->slug ? 'active' : '' }}">
                                        <a href="{{ route('jobdesk.checklist.index', $jobdesk->slug) }}">
                                            <i class="bi bi-list-check"></i> Checklist
                                        </a>
                                    </li>
                                    <li class="{{ request()->routeIs('laporan.*') && request()->segment(1) == $jobdesk->slug ? 'active' : '' }}">
                                        <a href="{{ route('jobdesk.laporan.index', $jobdesk->slug) }}">
                                            <i class="bi bi-file-earmark-text"></i> Laporan
                                        </a>
                                    </li>
                                    <li class="{{ request()->routeIs('laporan-eksekusi.*') && request()->segment(1) == $jobdesk->slug ? 'active' : '' }}">
                                        <a href="{{ route('jobdesk.laporan-eksekusi.index', $jobdesk->slug) }}">
                                            <i class="bi bi-play-circle"></i> Eksekusi
                                        </a>
                                    </li>
                                    <li class="{{ request()->routeIs('maintenance.*') && request()->segment(1) == $jobdesk->slug ? 'active' : '' }}">
                                        <a href="{{ route('jobdesk.maintenance.index', $jobdesk->slug) }}">
                                            <i class="bi bi-calendar-check"></i> Maintenance
                                        </a>
                                    </li>
                                    <!-- ============================================ -->
                                    <!-- LAYOUT & WIRING MENU (PERBAIKAN DI SINI) -->
                                    <!-- ============================================ -->
                                    <li class="{{ request()->routeIs('layout.*') && request()->segment(1) == $jobdesk->slug ? 'active' : '' }}">
                                        <a href="{{ route('jobdesk.layout.index', $jobdesk->slug) }}">
                                            <i class="bi bi-diagram-3"></i> Layout & Wiring
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            @endforeach
                        </ul>
                    </li>
                    @endif

                    <!-- ============================================ -->
                    <!-- MASTER DATA -->
                    <!-- ============================================ -->
                    <li class="nav-item dropdown-toggle">
                        <a href="#masterSubmenu" data-bs-toggle="collapse"
                           aria-expanded="{{ request()->routeIs('lokasi.*') || request()->routeIs('inventaris.*') ? 'true' : 'false' }}"
                           class="dropdown-toggle">
                            <i class="bi bi-database"></i> Master Data
                        </a>
                        <ul class="collapse list-unstyled {{ request()->routeIs('lokasi.*') || request()->routeIs('inventaris.*') ? 'show' : '' }}"
                            id="masterSubmenu">
                            <li class="{{ request()->routeIs('lokasi.*') ? 'active' : '' }}">
                                <a href="{{ route('lokasi.index') }}">
                                    <i class="bi bi-geo-alt"></i> Lokasi
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('inventaris.*') ? 'active' : '' }}">
                                <a href="{{ route('inventaris.index') }}">
                                    <i class="bi bi-box"></i> Inventaris
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- ============================================ -->
                    <!-- MANAJEMEN USER (Admin Only) -->
                    <!-- ============================================ -->
                    @if(auth()->user() && auth()->user()->role == 'admin')
                    <li class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <a href="{{ route('users.index') }}">
                            <i class="bi bi-people"></i> Manajemen User
                        </a>
                    </li>
                    @endif

                    <!-- ============================================ -->
                    <!-- ACTIVITY LOG (Admin Only) -->
                    <!-- ============================================ -->
                    @if(auth()->user() && auth()->user()->role == 'admin')
                    <li class="{{ request()->routeIs('activity.*') ? 'active' : '' }}">
                        <a href="{{ route('activity.index') }}">
                            <i class="bi bi-clock-history"></i> Activity Log
                        </a>
                    </li>
                    @endif

                    <!-- ============================================ -->
                    <!-- PROFILE -->
                    <!-- ============================================ -->
                    <li class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
                        <a href="{{ route('profile.index') }}">
                            <i class="bi bi-person-circle"></i> Profile
                        </a>
                    </li>

                    <!-- ============================================ -->
                    <!-- LOGOUT -->
                    <!-- ============================================ -->
                    <li>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>

                </ul>
            </div>

            <!-- ============================================ -->
            <!-- SIDEBAR FOOTER -->
            <!-- ============================================ -->
            <div class="sidebar-footer">
                <small class="text-muted">v2.0 - Multi Jobdesk</small>
            </div>
        </nav>
        @endif
        @endauth

        <!-- ============================================ -->
        <!-- CONTENT -->
        <!-- ============================================ -->
        <div id="content">

            <!-- ============================================ -->
            <!-- NAVBAR -->
            <!-- ============================================ -->
            @auth
            @if(!Route::is('login') && !Route::is('register') && !Route::is('password.*'))
            <nav class="navbar-custom d-flex justify-content-between align-items-center">

                <!-- Left: Toggle & Title -->
                <div class="d-flex align-items-center">
                    <button type="button" id="sidebarToggle" class="sidebar-toggle me-2">
                        <i class="bi bi-list"></i>
                    </button>
                    <span class="brand-title">@yield('title')</span>
                </div>

                <!-- Right: User Info -->
                <div class="d-flex align-items-center">

                    <!-- Jobdesk Badge (if in jobdesk route) -->
                    @if(request()->segment(1) && in_array(request()->segment(1), ['cctv', 'it', 'mechanical', 'electrical', 'plumbing', 'genset']))
                    <span class="me-3 d-none d-sm-inline">
                        <span class="badge bg-primary">
                            <i class="bi bi-briefcase"></i>
                            {{ ucfirst(request()->segment(1)) }}
                        </span>
                    </span>
                    @endif

                    <!-- Role Badge -->
                    <span class="me-3 d-none d-sm-inline">
                        <span class="badge bg-{{ auth()->user()->role == 'admin' ? 'danger' : (auth()->user()->role == 'teknisi' ? 'warning' : (auth()->user()->role == 'manajer' ? 'primary' : 'info')) }}">
                            {{ ucfirst(auth()->user()->role) }}
                        </span>
                    </span>

                    <!-- Name -->
                    <span class="me-3 d-none d-md-inline">{{ auth()->user()->name }}</span>

                    <!-- Avatar -->
                    @php
                        $avatarUser = auth()->user();
                        $fotoUser = $avatarUser->foto;
                        $hasFotoUser = $fotoUser && file_exists(public_path('storage/' . $fotoUser));
                        $defaultAvatarExists = file_exists(public_path('images/default-avatar.svg'));
                        $avatarUrl = $hasFotoUser ? asset('storage/' . $fotoUser) : ($defaultAvatarExists ? asset('images/default-avatar.svg') : '');
                    @endphp

                    <img src="{{ $avatarUrl }}"
                         alt="Avatar"
                         class="rounded-circle"
                         width="40"
                         height="40"
                         style="object-fit: cover; border: 2px solid #ddd; background: #f0f0f0;"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                    <div class="rounded-circle d-none align-items-center justify-content-center"
                         style="width: 40px; height: 40px; background: #4A90D9; color: white; font-weight: bold; font-size: 18px;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>

                </div>

            </nav>
            @endif
            @endauth

            <!-- ============================================ -->
            <!-- PAGE CONTENT -->
            <!-- ============================================ -->
            <!-- <div class="page-content">


                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle"></i> {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @yield('content')
            </div> -->

        </div>
        <!-- /content -->

    </div>
    <!-- /wrapper -->

    <!-- ============================================ -->
    <!-- SCRIPTS -->
    <!-- ============================================ -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // ============================================
        // SIDEBAR TOGGLE
        // ============================================
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('content').classList.toggle('active');
        });

        // ============================================
        // AUTO CLOSE SIDEBAR ON MOBILE
        // ============================================
        document.addEventListener('click', function(event) {
            if (window.innerWidth <= 768) {
                const sidebar = document.getElementById('sidebar');
                const toggle = document.getElementById('sidebarToggle');
                if (sidebar && !sidebar.contains(event.target) && !toggle.contains(event.target)) {
                    sidebar.classList.remove('active');
                    document.getElementById('content').classList.remove('active');
                }
            }
        });

        // ============================================
        // ALERT AUTO DISMISS
        // ============================================
        window.setTimeout(function() {
            document.querySelectorAll('.alert:not(.alert-kendala-solusi)').forEach(function(alert) {
                alert.classList.remove('show');
                setTimeout(function() {
                    alert.remove();
                }, 300);
            });
        }, 5000);

        // ============================================
        // CONFIRM DELETE
        // ============================================
        document.querySelectorAll('.delete-confirm').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                    form.submit();
                }
            });
        });

        // ============================================
        // SIDEBAR DROPDOWN - Simpan state per jobdesk
        // ============================================
        document.addEventListener('DOMContentLoaded', function() {
            // Expand submenu yang aktif (level 2 - Master Data, Transaksi, dll)
            document.querySelectorAll('#sidebar ul ul').forEach(function(submenu) {
                if (submenu.querySelector('li.active')) {
                    submenu.classList.add('show');
                    const toggle = submenu.previousElementSibling;
                    if (toggle && toggle.classList.contains('dropdown-toggle')) {
                        toggle.setAttribute('aria-expanded', 'true');
                    }
                }
            });

            // Expand jobdesk submenu yang aktif (level 3 - per jobdesk)
            document.querySelectorAll('#sidebar ul ul ul').forEach(function(submenu) {
                if (submenu.querySelector('li.active')) {
                    submenu.classList.add('show');
                    
                    // Cari toggle parent (level 2 - nama jobdesk)
                    const parentLi = submenu.closest('li');
                    if (parentLi) {
                        const parentToggle = parentLi.querySelector('a.dropdown-toggle');
                        if (parentToggle) {
                            parentToggle.setAttribute('aria-expanded', 'true');
                            parentToggle.classList.add('active');
                        }
                    }
                    
                    // Expand parent submenu (level 2 - Jobdesk)
                    const parentUl = submenu.closest('ul');
                    if (parentUl && parentUl.id !== 'jobdeskSubmenu') {
                        parentUl.classList.add('show');
                        const grandParentToggle = parentUl.previousElementSibling;
                        if (grandParentToggle && grandParentToggle.classList.contains('dropdown-toggle')) {
                            grandParentToggle.setAttribute('aria-expanded', 'true');
                        }
                    }
                }
            });
        });

        // ============================================
        // SERVICE WORKER - NONAKTIFKAN SEMENTARA
        // ============================================
        // Hapus atau comment bagian ini untuk menonaktifkan Service Worker
        // if ('serviceWorker' in navigator) {
        //     window.addEventListener('load', function() {
        //         navigator.serviceWorker.register('/sw.js')
        //             .then(function(registration) {
        //                 console.log('ServiceWorker registered successfully');
        //             })
        //             .catch(function(err) {
        //                 console.log('ServiceWorker registration failed: ', err);
        //             });
        //     });
        // }

        // ============================================
        // PWA - Safe Area untuk standalone mode
        // ============================================
        if (window.matchMedia('(display-mode: standalone)').matches) {
            const navbar = document.querySelector('.navbar-custom');
            if (navbar) {
                navbar.style.paddingTop = 'env(safe-area-inset-top)';
            }
        }
    </script>

    @stack('scripts')
    <x-ui-alert />
</body>
</html>