<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Taya Global Chain - Supply Chain Risk Intelligence')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    
    <style>
        :root {
            --primary-bg: #0f172a;
            --secondary-bg: #1e293b;
            --accent-color: #3b82f6;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --danger: #ef4444;
            --warning: #f59e0b;
            --success: #10b981;
            --sidebar-width: 260px;
        }

        body {
            background-color: var(--primary-bg);
            color: var(--text-main);
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            overflow-x: hidden;
        }

        /* Sidebar Styles */
        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background-color: var(--secondary-bg);
            border-right: 1px solid rgba(255,255,255,0.05);
            z-index: 1000;
            transition: all 0.3s;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .sidebar-header h4 {
            color: var(--accent-color);
            font-weight: 700;
            margin: 0;
            font-size: 1.25rem;
        }

        .nav-item {
            margin-bottom: 0.25rem;
        }

        .nav-link {
            color: var(--text-muted);
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--text-main);
            background: rgba(59, 130, 246, 0.1);
            border-left-color: var(--accent-color);
        }

        .nav-link i {
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }

        /* Main Content Styles */
        #main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s;
        }

        .top-navbar {
            background-color: var(--secondary-bg);
            padding: 1rem 2rem;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .content-wrapper {
            padding: 2rem;
        }

        /* Card Styles */
        .glass-card {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: 1rem;
            padding: 1.5rem;
            height: 100%;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: var(--primary-bg);
        }
        ::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #475569;
        }

        @media (max-width: 768px) {
            #sidebar {
                transform: translateX(-100%);
            }
            #sidebar.show {
                transform: translateX(0);
            }
            #main-content {
                margin-left: 0;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Sidebar -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <h4 class="d-flex align-items-center gap-2">
                <i class="fa-solid fa-globe"></i> TayaChain
            </h4>
        </div>
        <ul class="nav flex-column mt-3">
            <li class="nav-item">
                <a class="nav-link {{ request()->is('/') && !request()->has('anchor') ? 'active' : '' }}" href="{{ url('/') }}">
                    <i class="fa-solid fa-house"></i> Dashboard
                </a>
            </li>
            <li class="nav-item mt-3 mb-1 px-4">
                <small class="text-uppercase text-light opacity-75 fw-bold" style="font-size: 0.7rem;">Analytics</small>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/#map') }}">
                    <i class="fa-solid fa-earth-americas"></i> Global Country
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/#riskChartCard') }}">
                    <i class="fa-solid fa-shield-halved"></i> Risk Scoring
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/#map') }}">
                    <i class="fa-solid fa-cloud-bolt"></i> Weather Monitor
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/#currencyWidget') }}">
                    <i class="fa-solid fa-money-bill-trend-up"></i> Currency Impact
                </a>
            </li>
            <li class="nav-item mt-3 mb-1 px-4">
                <small class="text-uppercase text-light opacity-75 fw-bold" style="font-size: 0.7rem;">Intelligence</small>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/#newsWidget') }}">
                    <i class="fa-solid fa-newspaper"></i> News Feed
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/#map') }}">
                    <i class="fa-solid fa-ship"></i> Ports & Logistics
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/#riskChartCard') }}">
                    <i class="fa-solid fa-chart-line"></i> Data Visualization
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('compare') ? 'active' : '' }}" href="{{ route('compare') }}">
                    <i class="fa-solid fa-scale-balanced"></i> Compare Countries
                </a>
            </li>
            <li class="nav-item mt-3 mb-1 px-4">
                <small class="text-uppercase text-light opacity-75 fw-bold" style="font-size: 0.7rem;">User</small>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/#watchlist') }}">
                    <i class="fa-solid fa-star"></i> My Watchlist
                </a>
            </li>

            @if(auth()->check() && auth()->user()->role === 'admin')
            <li class="nav-item mt-3 mb-1 px-4">
                <small class="text-uppercase text-light opacity-75 fw-bold" style="font-size: 0.7rem;">Admin</small>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('admin*') ? 'active' : '' }}" href="{{ route('admin.index') }}">
                    <i class="fa-solid fa-screwdriver-wrench"></i> Control Panel
                </a>
            </li>
            @endif

            <li class="nav-item mt-3">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a class="nav-link" href="#" onclick="event.preventDefault(); this.closest('form').submit();">
                        <i class="fa-solid fa-right-from-bracket"></i> Logout
                    </a>
                </form>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div id="main-content">
        <!-- Top Navbar -->
        <header class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-link text-white d-md-none" id="sidebarToggle">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h5 class="mb-0 fw-bold">@yield('page_title', 'Overview')</h5>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="text-end d-none d-md-block">
                    <div class="fw-bold fs-6">{{ auth()->user()->name ?? 'User' }}</div>
                    <div class="text-light opacity-75" style="font-size: 0.8rem;">{{ auth()->user()->email ?? '' }}</div>
                </div>
                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <i class="fa-solid fa-user"></i>
                </div>
            </div>
        </header>

        <!-- Dynamic Content -->
        <main class="content-wrapper">
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <script>
        // Sidebar Toggle for Mobile
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });

        // Smooth scroll to element helper
        function scrollToHash(hash) {
            if (!hash) return;
            const target = document.querySelector(hash);
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        // Intercept hash link clicks
        document.addEventListener('click', function(e) {
            const anchor = e.target.closest('a');
            if (!anchor) return;
            
            const href = anchor.getAttribute('href');
            if (!href) return;
            
            // Check if it is a hash link for the current page
            const currentUrl = window.location.pathname;
            const hashIndex = href.indexOf('#');
            
            if (hashIndex !== -1) {
                const linkPath = href.substring(0, hashIndex);
                const hash = href.substring(hashIndex);
                
                // If it points to the current path or if current path is '/' and linkPath is empty/URL root
                const isCurrentPage = linkPath === '' || 
                                     linkPath === '/' && currentUrl === '/' || 
                                     window.location.origin + linkPath === window.location.origin + currentUrl;
                                     
                if (isCurrentPage) {
                    const target = document.querySelector(hash);
                    if (target) {
                        e.preventDefault();
                        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        history.pushState(null, null, hash);
                    }
                }
            }
        });

        // Handle scroll on initial page load (with a delay for ajax content rendering)
        window.addEventListener('DOMContentLoaded', () => {
            if (window.location.hash) {
                setTimeout(() => {
                    scrollToHash(window.location.hash);
                }, 800); // 800ms delay to ensure weather/charts/news are loaded
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
