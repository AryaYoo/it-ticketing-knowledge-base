<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $appSettings['app_name'] ?? config('app.name', 'MasTolongMas') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ isset($appSettings['app_logo']) ? asset($appSettings['app_logo']) : asset('assets/img/logo.svg') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Scripts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            /* Light Theme (Default) - Rebranded to Green #007C3C */
            --primary-rgb: 0, 124, 60;
            --primary-color: #007C3C;
            --primary-hover: #006631;
            --bg-color: #f3f4f6;
            --card-bg: #ffffff;
            --text-main: #1f2937;
            --text-muted: #6b7280;
        }

        [data-theme="dark"] {
            --primary-rgb: 0, 168, 82;
            --primary-color: #00a852;
            --primary-hover: #00c862;
            --bg-color: #0b0f1a;
            --card-bg: #161c2d;
            --text-main: #f9fafb;
            --text-muted: #d1d5db;
            /* Lightened for better contrast */
            --border-color: rgba(255, 255, 255, 0.1);
            --footer-bg: #0b0f1a;
        }

        :root {
            --footer-bg: #ffffff;
        }

        [data-theme="dark"] .card,
        [data-theme="dark"] h1,
        [data-theme="dark"] h2,
        [data-theme="dark"] h3,
        [data-theme="dark"] h4,
        [data-theme="dark"] h5,
        [data-theme="dark"] h6,
        [data-theme="dark"] p,
        [data-theme="dark"] span:not(.badge):not(.text-primary):not(.text-danger) {
            color: var(--text-main) !important;
        }

        [data-theme="dark"] .text-secondary,
        [data-theme="dark"] .text-muted {
            color: var(--text-muted) !important;
        }

        [data-theme="colorblind"] {
            /* High Contrast / Colorblind Safe (Blue/Orange) */
            --primary-color: #0077b6;
            /* High contrast blue */
            --primary-hover: #023e8a;
            --bg-color: #ffffff;
            --card-bg: #ffffff;
            --text-main: #000000;
            --text-muted: #333333;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            -webkit-font-smoothing: antialiased;
        }

        .navbar {
            background-color: var(--card-bg) !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            border-bottom: 2px solid var(--primary-color);
            padding-top: 0.6rem;
            padding-bottom: 0.6rem;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            padding: 0;
            margin: 0;
        }

        .brand-logo {
            height: 40px;
            width: auto;
            transition: transform 0.3s ease;
            display: block;
        }

        .navbar-brand:hover .brand-logo {
            transform: scale(1.03);
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.75rem;
            flex-shrink: 0;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: rgba(var(--bs-primary-rgb), 0.2);
            border-radius: 20px;
        }

        .last-child-mb-0> :last-child {
            margin-bottom: 0 !important;
        }

        .dropdown-menu {
            background-color: var(--card-bg);
            color: var(--text-main);
        }

        .dropdown-item:hover {
            background-color: rgba(var(--primary-rgb), 0.1);
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            color: var(--text-main);
            font-weight: 700;
            /* Bolder headings by default */
            letter-spacing: -0.025em;
            /* Tighter tracking for headings */
        }

        h1 {
            font-size: calc(1.5rem + 1.5vw);
        }

        h2 {
            font-size: calc(1.3rem + 1vw);
        }

        h3 {
            font-size: calc(1.1rem + 0.5vw);
        }

        h4 {
            font-size: 1.1rem;
        }

        h5 {
            font-size: 1rem;
        }

        @media (max-width: 768px) {
            .container {
                padding-left: 1.5rem;
                padding-right: 1.5rem;
            }

            .card-header {
                padding: 1.25rem 1.5rem;
            }

            .card-body {
                padding: 1.5rem;
            }

            .section-spacing {
                margin-bottom: 2.5rem;
            }
        }

        small,
        .small {
            font-size: 0.875em;
        }

        .nav-link {
            font-weight: 500;
            color: var(--text-main) !important;
            transition: all 0.2s;
        }

        .nav-link:hover {
            color: var(--primary-color) !important;
            background-color: rgba(var(--primary-rgb), 0.05);
        }

        .active-nav-item {
            background-color: rgba(var(--primary-rgb), 0.1) !important;
            color: var(--primary-color) !important;
            font-weight: 700 !important;
        }

        [data-theme="dark"] .active-nav-item {
            background-color: rgba(var(--primary-rgb), 0.2) !important;
            color: #4ade80 !important;
            /* Brighter green for dark mode accessibility */
        }

        .text-muted {
            color: var(--text-muted) !important;
        }

        [data-theme="dark"] label,
        [data-theme="dark"] .form-label,
        [data-theme="dark"] .text-muted {
            color: var(--text-muted) !important;
        }

        [data-theme="dark"] .section-spacing p.text-muted,
        [data-theme="dark"] .card-body p.text-muted {
            color: #d1d5db !important;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            background-color: var(--card-bg);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        [data-theme="dark"] .card {
            border-color: var(--border-color);
        }

        .card:hover {
            /* transform: translateY(-2px); */
            /* box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); */
        }

        .card-header {
            background-color: transparent;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1.5rem 2rem;
            /* Increased padding */
            font-weight: 700;
            color: var(--primary-color);
            font-size: 1.25rem;
            letter-spacing: -0.02em;
        }

        [data-theme="dark"] .card-header {
            border-bottom-color: var(--border-color);
        }

        [data-theme="dark"] .border,
        [data-theme="dark"] .border-top,
        [data-theme="dark"] .border-bottom,
        [data-theme="dark"] .border-start,
        [data-theme="dark"] .border-end {
            border-color: var(--border-color) !important;
        }

        .card-body {
            padding: 2rem;
            /* Consistent large padding */
        }

        .btn {
            border-radius: 8px;
            font-weight: 500;
            padding: 0.5rem 1.25rem;
            transition: all 0.2s;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
        }

        [data-theme="dark"] .btn-outline-secondary {
            color: var(--text-muted);
            border-color: var(--border-color);
        }

        [data-theme="dark"] .btn-outline-secondary:hover {
            background-color: rgba(255, 255, 255, 0.05);
            color: var(--text-main);
            border-color: var(--text-muted);
        }

        footer {
            background-color: var(--card-bg);
            border-top: 2px solid var(--primary-color);
            color: var(--text-muted);
            font-size: 0.8rem;
        }

        [data-theme="dark"] footer {
            background-color: var(--card-bg);
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            border: 1px solid #d1d5db;
            padding: 0.625rem 0.875rem;
            background-color: var(--bg-color);
            color: var(--text-main);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .badge {
            padding: 0.6em 1em;
            /* Slightly roomier badges */
            border-radius: 8px;
            font-weight: 600;
            letter-spacing: 0.025em;
            text-transform: uppercase;
            font-size: 0.75rem;
        }

        /* Spacing Utilities */
        .section-spacing {
            margin-bottom: 4rem;
            /* Standard large gap between major sections */
        }

        .content-gap {
            gap: 2rem;
            /* Gap for flex/grid containers */
        }

        /* Premium Utilities */
        .hover-lift {
            transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .hover-lift:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
        }

        .transition-base {
            transition: all 0.2s ease-in-out;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%) !important;
        }

        .text-primary {
            color: var(--primary-color) !important;
        }

        .bg-primary {
            background-color: var(--primary-color) !important;
        }
    </style>
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light sticky-top">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ isset($appSettings['app_logo']) ? asset($appSettings['app_logo']) : asset('assets/img/logo.svg') }}" alt="Logo" class="brand-logo">
                </a>
                <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse mt-3 mt-md-0" id="navbarSupportedContent">
                    <hr class="d-md-none text-secondary opacity-25 my-2">

                    <!-- Navigation Links -->
                    <ul class="navbar-nav ms-auto align-items-md-center gap-1">
                        @auth
                            <li class="nav-item">
                                @php
                                    $isDashboardActive = request()->routeIs('dashboard') ||
                                        request()->routeIs('home') ||
                                        request()->routeIs('tickets.*') ||
                                        request()->routeIs('users.*') ||
                                        request()->routeIs('categories.*') ||
                                        request()->routeIs('announcements.*') ||
                                        request()->routeIs('ip-mappings.*') ||
                                        request()->routeIs('admin.settings.*') ||
                                        request()->routeIs('activity_logs.*');
                                @endphp
                                <a class="nav-link px-3 py-2 rounded-2 {{ $isDashboardActive ? 'active-nav-item' : '' }}"
                                    href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                            </li>
                            @if(in_array(auth()->user()->role, ['admin', 'staff']))
                                <li class="nav-item">
                                    <a class="nav-link px-3 py-2 rounded-2 {{ request()->routeIs('inventaris.*') ? 'active-nav-item' : '' }}"
                                        href="{{ route('inventaris.index') }}">{{ __('Inventaris') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link px-3 py-2 rounded-2 {{ request()->routeIs('maintenances.*') ? 'active-nav-item' : '' }}"
                                        href="{{ route('maintenances.index') }}">{{ __('Jadwal Maintenance') }}</a>
                                </li>
                            @endif
                            <li class="nav-item">
                                <a class="nav-link px-3 py-2 rounded-2 {{ request()->routeIs('forum.*') ? 'active-nav-item' : '' }}"
                                    href="{{ route('forum.index') }}">{{ __('Community Forum') }}</a>
                            </li>
                            @if(auth()->user()->role === 'admin')
                                <li class="nav-item">
                                    <a class="nav-link px-2 py-2 rounded-2 {{ request()->routeIs('admin.settings.*') ? 'active-nav-item' : '' }}" 
                                       href="{{ route('admin.settings.index') }}" title="{{ __('Application Settings') }}">
                                        <i class="bi bi-gear-fill" style="font-size: 1.1rem;"></i>
                                    </a>
                                </li>
                            @endif
                        @endauth

                        {{-- Utilities: Language & Theme --}}
                        <li class="nav-item d-flex align-items-center gap-1 mt-2 mt-md-0 ms-md-2">
                            {{-- Language --}}
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light border-0 px-2 py-1 dropdown-toggle d-flex align-items-center"
                                    id="bd-language" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-globe2 me-1" style="font-size: 0.85rem;"></i>
                                    <span class="fw-bold text-uppercase" style="font-size: 0.7rem;">{{ app()->getLocale() }}</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" aria-labelledby="bd-language">
                                    <li>
                                        <a class="dropdown-item small {{ app()->getLocale() == 'id' ? 'active' : '' }}"
                                            href="{{ route('lang.switch', 'id') }}">
                                            <i class="bi bi-check2 me-1 {{ app()->getLocale() == 'id' ? '' : 'invisible' }}"></i> Bahasa Indonesia
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item small {{ app()->getLocale() == 'en' ? 'active' : '' }}"
                                            href="{{ route('lang.switch', 'en') }}">
                                            <i class="bi bi-check2 me-1 {{ app()->getLocale() == 'en' ? '' : 'invisible' }}"></i> English
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            {{-- Theme --}}
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light border-0 px-2 py-1 dropdown-toggle d-flex align-items-center"
                                    id="bd-theme" type="button" aria-expanded="false" data-bs-toggle="dropdown">
                                    <i class="bi bi-sun theme-icon-active" style="font-size: 0.85rem;"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" aria-labelledby="bd-theme">
                                    <li>
                                        <button type="button" class="dropdown-item small d-flex align-items-center active"
                                            data-bs-theme-value="light">
                                            <i class="bi bi-sun me-2"></i> {{ __('Light') }}
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="dropdown-item small d-flex align-items-center"
                                            data-bs-theme-value="dark">
                                            <i class="bi bi-moon me-2"></i> {{ __('Dark') }}
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="dropdown-item small d-flex align-items-center"
                                            data-bs-theme-value="colorblind">
                                            <i class="bi bi-eye me-2"></i> {{ __('Colorblind') }}
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        {{-- Authentication --}}
                        @guest
                            <div class="d-flex flex-column flex-md-row gap-2 mt-2 mt-md-0 ms-md-2">
                                @if (Route::has('login'))
                                    <li class="nav-item">
                                        <a class="nav-link btn btn-light border-0 px-3 btn-sm"
                                            href="{{ route('login') }}">{{ __('Login') }}</a>
                                    </li>
                                @endif
                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link btn btn-primary text-white px-3 btn-sm shadow-sm"
                                            href="{{ route('register') }}"
                                            style="color: white !important;">{{ __('Register') }}</a>
                                    </li>
                                @endif
                            </div>
                        @else
                            <li class="nav-item dropdown mt-2 mt-md-0 ms-md-1">
                                <a id="navbarDropdown"
                                    class="nav-link dropdown-toggle btn btn-light border-0 px-2 py-1 d-flex align-items-center gap-2"
                                    href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false" v-pre>
                                    <span class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                    <span class="fw-semibold small d-none d-lg-inline">{{ Auth::user()->name }}</span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-2"
                                    aria-labelledby="navbarDropdown" style="min-width: 200px;">
                                    <div class="px-3 py-2 mb-1">
                                        <div class="fw-bold small">{{ Auth::user()->name }}</div>
                                        <div class="text-muted" style="font-size: 0.7rem;">{{ Auth::user()->email }}</div>
                                    </div>
                                    <hr class="my-1 opacity-10">
                                    <a class="dropdown-item small rounded-2 py-2" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right me-2"></i> {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-3" style="min-height: calc(100vh - 200px);">
            @yield('content')
        </main>

        <footer class="py-3 mt-auto">
            <div class="container">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <img src="{{ isset($appSettings['app_logo']) ? asset($appSettings['app_logo']) : asset('assets/img/logo.svg') }}" alt="Logo" style="height: 24px; opacity: 0.6;">
                        <span class="text-muted" style="font-size: 0.75rem;">&copy; {{ date('Y') }} {{ $appSettings['app_name'] ?? config('app.name', 'MasTolongMas') }}</span>
                    </div>
                    <div class="text-muted" style="font-size: 0.75rem;">
                        {{ __('Created by') }} <span class="fw-semibold text-primary">IT Staff RSIA IBI Surabaya</span>
                    </div>
                </div>
            </div>
        </footer>

        <!-- Toast Container -->
        <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1100;" id="toastContainer">
            <!-- Toasts will be injected here -->
        </div>

        @auth
            <script>
                // Theme Switcher Logic
                (function () {
                    const storedTheme = localStorage.getItem('theme') || 'light';
                    document.documentElement.setAttribute('data-theme', storedTheme);

                    // Update active state in dropdown
                    document.addEventListener('DOMContentLoaded', () => {
                        const toggles = document.querySelectorAll('[data-bs-theme-value]');
                        toggles.forEach(toggle => {
                            if (toggle.getAttribute('data-bs-theme-value') === storedTheme) {
                                toggle.classList.add('active');
                                toggle.setAttribute('aria-pressed', 'true');
                            } else {
                                toggle.classList.remove('active');
                                toggle.setAttribute('aria-pressed', 'false');
                            }

                            toggle.addEventListener('click', () => {
                                const theme = toggle.getAttribute('data-bs-theme-value');
                                document.documentElement.setAttribute('data-theme', theme);
                                localStorage.setItem('theme', theme);

                                // Reset active states
                                toggles.forEach(t => {
                                    t.classList.remove('active');
                                    t.setAttribute('aria-pressed', 'false');
                                });
                                toggle.classList.add('active');
                                toggle.setAttribute('aria-pressed', 'true');
                            });
                        });
                    });
                })();

                document.addEventListener('DOMContentLoaded', function () {
                    let lastCheck = new Date().toISOString();
                    // Set initial check time to now to avoid fetching old logs on page load

                    const userRole = "{{ auth()->user()->role }}";
                    const notificationSound = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3');

                    // Request permission on load
                    if (Notification.permission !== "granted" && Notification.permission !== "denied") {
                        Notification.requestPermission();
                    }

                    function checkActivity() {
                        fetch("{{ route('activity.check') }}?last_check=" + lastCheck)
                            .then(response => response.json())
                            .then(data => {
                                if (data.length > 0) {
                                    // Update lastCheck to the most recent log time
                                    lastCheck = new Date().toISOString(); // Or use the latest log timestamp

                                    data.forEach(log => {
                                        // Filter logic
                                        let showNotification = false;
                                        let title = 'Notification';
                                        let message = log.description;

                                        if (log.type === 'forum_post') {
                                            // Don't notify self
                                            if (log.user_id != {{ auth()->id() }}) {
                                                showNotification = true;
                                                title = 'New Forum Post';
                                            }
                                        } else if (log.type === 'ticket_created') {
                                            // Notify Staff only
                                            if (userRole === 'staff') {
                                                showNotification = true;
                                                title = 'New Ticket';
                                            }
                                        } else if (log.type === 'ticket_resolved' || log.type === 'ticket_escalated') {
                                            // General notification or specific
                                            if (userRole === 'admin') {
                                                showNotification = true;
                                                title = 'Ticket Update';
                                            }
                                        }

                                        if (showNotification) {
                                            showToast(title, message, log.created_at);
                                            sendPushNotification(title, message);
                                        }
                                    });
                                }
                            })
                            .catch(error => console.error('Error checking activity:', error));
                    }

                    function showToast(title, message, time) {
                        const toastContainer = document.getElementById('toastContainer');
                        const toastHtml = `
                                                <div class="toast border-0 shadow-lg mb-2" role="alert" aria-live="assertive" aria-atomic="true" style="border-radius: 12px; overflow: hidden;">
                                                    <div class="toast-header bg-primary text-white border-0 py-2">
                                                        <strong class="me-auto">${title}</strong>
                                                        <small>{{ __('Just now') }}</small>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                                                    </div>
                                                    <div class="toast-body p-3">
                                                        ${message}
                                                    </div>
                                                </div>
                                            `;
                        toastContainer.insertAdjacentHTML('beforeend', toastHtml);

                        // Initialize and show the toast
                        const toastElement = toastContainer.lastElementChild;
                        const toast = new bootstrap.Toast(toastElement, { delay: 10000 });
                        toast.show();
                    }

                    function sendPushNotification(title, message) {
                        if (Notification.permission === "granted") {
                            const notification = new Notification(title, {
                                body: message,
                                icon: "{{ asset('assets/img/logo.svg') }}"
                            });

                            notification.onclick = function () {
                                window.focus();
                                this.close();
                            };

                            // Play sound
                            notificationSound.play().catch(e => console.log('Sound blocked by browser policy until interaction.'));
                        }
                    }

                    // Poll every 15 seconds
                    setInterval(checkActivity, 15000);
                });
            </script>
        @endauth

        <!-- Global SweetAlert2 Handler -->
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Success Message
                @if (session('status') || session('success') || session('resent') || session('message'))
                    Swal.fire({
                        icon: 'success',
                        title: "{{ __('Success!') }}",
                        text: "{{ session('status') ?: session('success') ?: session('resent') ?: session('message') }}",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                @endif

                // Error Message
                @if (session('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "{{ session('error') }}",
                    });
                @endif

                // Delete Confirmation
                document.querySelectorAll('.confirm-delete').forEach(button => {
                    button.addEventListener('click', function (e) {
                        e.preventDefault();
                        const form = this.closest('form');
                        const message = this.dataset.confirm || "{{ __('Are you sure you want to delete this data?') }}";

                        Swal.fire({
                            title: "{{ __('Confirm Delete') }}",
                            text: message,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: "{{ __('Ya, Hapus!') }}",
                            cancelButtonText: "{{ __('Cancel') }}",
                            reverseButtons: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    });
                });
            });
        </script>
    </div>
</body>

</html>
