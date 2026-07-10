<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Stable Solar Energy')</title>
    <link rel="icon" type="image/png" href="{{ asset('stable/images/logo.png') }}">

    {{-- Tailwind/App assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="/assets/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="/assets/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet" href="/assets/css/fa-all.min.css">
    <style>
        /* Clean Professional Theme Variables */
        :root {
            --primary-blue: #4A90E2;
            --primary-dark: #2c3e50;
            --primary-light: #f8f9fa;
            --sidebar-bg: #ffffff;
            --text-primary: #2d3436;
            --text-secondary: #636e72;
            --border-color: #e1e8ed;
            --hover-bg: #f5f7fa;
        }

        html {
            overflow: hidden;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: var(--primary-light);
            color: var(--text-primary);
            font-size: 13px;
        }

        .h-screen {
            height: 100vh;
        }

        .flex {
            display: flex;
        }

        .flex-col {
            flex-direction: column;
        }

        .overflow-hidden {
            overflow: hidden;
        }

        /* DataTables pagination fix (Tailwind preflight strips Bootstrap styles) */
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            display: inline-block;
            padding: 5px 12px;
            margin: 0 2px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            background: #fff;
            color: #374151 !important;
            font-size: 13px;
            cursor: pointer;
            text-decoration: none !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #f3f4f6;
            border-color: #d1d5db;
            color: #111827 !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #4f46e5;
            border-color: #4f46e5;
            color: #fff !important;
            font-weight: 600;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
            opacity: 0.4;
            cursor: default;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
            background: #fff;
            border-color: #e5e7eb;
        }
        .dataTables_wrapper .dataTables_paginate {
            padding-top: 12px;
            text-align: right;
        }
        .dataTables_wrapper .dataTables_info {
            padding-top: 12px;
            font-size: 13px;
            color: #6b7280;
        }

        /* Toast Notification Styles */
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }

            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

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

        .toast-enter {
            animation: slideInRight 0.3s ease-out;
        }

        .toast-exit {
            animation: slideOutRight 0.3s ease-in;
        }

        .alert-enter {
            animation: fadeIn 0.3s ease-out;
        }

        .progress-bar {
            animation: progress 5s linear;
        }

        @keyframes progress {
            from {
                width: 100%;
            }

            to {
                width: 0%;
            }
        }

        /* Clean Header */
        header {
            background: white;
            border-bottom: 1px solid var(--border-color);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        header h1 {
            color: var(--text-primary);
            font-weight: 600;
            font-size: 0.95rem;
            margin: 0;
        }

        /* Header button styles */
        .header-btn {
            background: transparent;
            border: none;
            color: var(--text-secondary);
            transition: all 0.2s ease;
        }

        .header-btn:hover {
            background: var(--hover-bg);
            color: var(--primary-blue);
        }

        /* Clean Sidebar */
        #sidebar {
            background: var(--sidebar-bg);
            border-right: 1px solid var(--border-color);
            box-shadow: 1px 0 3px rgba(0, 0, 0, 0.02);
        }

        #sidebar .border-slate-800 {
            border-color: var(--border-color);
        }

        #sidebar a {
            color: var(--text-primary);
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        #sidebar a:hover {
            background: var(--hover-bg);
            color: var(--primary-blue);
        }

        #sidebar a.active {
            background: #E8F4FD;
            color: var(--primary-blue);
            border-left: 3px solid var(--primary-blue);
        }

        #sidebar svg {
            color: var(--text-secondary);
            width: 16px;
            height: 16px;
        }

        #sidebar a:hover svg,
        #sidebar a.active svg {
            color: var(--primary-blue);
        }

        /* Ecommerce submenu — dark panel */
        #submenu-ecommerce {
            background: #0f172a;
            border-radius: 8px;
            padding: 4px;
            margin-top: 4px;
        }
        #submenu-ecommerce a {
            color: #94a3b8;
            border-left: none;
            background: transparent;
        }
        #submenu-ecommerce a:hover {
            background: rgba(255,255,255,0.07);
            color: #e2e8f0;
        }
        #submenu-ecommerce a.ec-active {
            background: rgba(99,102,241,0.25);
            color: white;
        }
        #submenu-ecommerce svg {
            color: #64748b;
        }
        #submenu-ecommerce a:hover svg,
        #submenu-ecommerce a.ec-active svg {
            color: #a5b4fc;
        }

        /* Main content area */
        main {
            background: #f5f7fa;
        }

        /* Profile Menu */
        #profileMenu {
            background: white;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        #profileMenu a:hover,
        #profileMenu button:hover {
            background: var(--hover-bg);
            color: var(--primary-blue);
        }

        /* Alert and Toast styling */
        .border-l-4.border-green-500 {
            background: rgba(16, 185, 129, 0.05);
            border-left-color: #10b981;
        }

        .border-l-4.border-red-500 {
            background: rgba(239, 68, 68, 0.05);
            border-left-color: #ef4444;
        }

        .border-l-4.border-yellow-500 {
            background: rgba(245, 158, 11, 0.05);
            border-left-color: #f59e0b;
        }

        .border-l-4.border-blue-500 {
            background: rgba(59, 130, 246, 0.05);
            border-left-color: #3b82f6;
        }

        /* Modal backdrop */
        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5);
        }

        /* Container classes */
        .container-fluid {
            padding: 1.5rem;
        }

        /* Sidebar logo area */
        #sidebar .logo-area {
            height: auto;
            padding: 0.875rem 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        #sidebar .logo-area img {
            width: 28px;
            height: 28px;
        }

        /* Sidebar nav spacing */
        #sidebar nav {
            /* padding: 0.75rem 0.5rem; */
        }

        #sidebar nav a {
            padding: 0.5rem 0.875rem;
            border-left: 3px solid transparent;
            margin-bottom: 0.25rem;
            border-radius: 6px;
        }

        #sidebar nav svg {
            margin-right: 0.625rem;
        }

        /* Footer text in sidebar */
        #sidebar .sidebar-footer {
            padding: 0.75rem;
            font-size: 11px;
            text-align: center;
            border-top: 1px solid var(--border-color);
            color: var(--text-secondary);
        }

        @media (max-width: 767px) {
            header h1 {
                font-size: 0.875rem;
            }

            #sidebar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100%;
                z-index: 40;
            }

            .container-fluid {
                padding: 1rem;
            }
        }
    </style>
    {{-- Page-specific CSS --}}
    @yield('css')
</head>
<?php $roll_id = Auth::user()->role_id; ?>

<body class="h-screen text-gray-900">
    <!-- Toast Container (Top Right) -->
    <div id="toastContainer" class="fixed top-4 right-4 z-50 space-y-3 max-w-sm w-full pointer-events-none">
        <!-- Toasts will be inserted here via JS -->
    </div>

    <!-- Alert Container (Top Center) -->
    @include('layouts.partials.adminAlertPartial')

    <div class="h-screen flex flex-col">
        <!-- Top bar -->
        @include('layouts.partials.adminTopBarNav')

        <!-- Main container -->
        <div class="flex-1 flex min-w-0 overflow-hidden">
            <!-- Mobile sidebar backdrop -->
            <div id="sidebarBackdrop" class="fixed inset-0 bg-black bg-opacity-40 z-30 hidden md:hidden"></div>

            <!-- Sidebar -->
            <aside id="sidebar"
                class="z-30 w-56 flex-shrink-0 flex flex-col transition-transform duration-200 -translate-x-full md:translate-x-0 overflow-y-auto">
                <div class="logo-area flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        <div
                            class="w-7 h-7 rounded-full overflow-hidden bg-gray-100 flex items-center justify-center flex-shrink-0">
                            <img src="{{ asset('stable/images/logo.png') }}" alt="Logo"
                                class="w-full h-full object-cover">
                        </div>
                        <span class="text-xs font-bold hidden sm:inline">Stable Solar Energy</span>
                    </a>
                </div>

                <nav class="flex-1 space-y-0.5">
                    <!-- Dashboard -->
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center gap-2.5 {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <svg class="flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 12l8.954-8.955a1.125 1.125 0 011.592 0L21.75 12M4.5 9.75V21h4.5v-4.5h4.5V21h4.5V9.75" />
                        </svg>
                        <span class="text-xs font-semibold">Dashboard</span>
                    </a>
                  
                    @if (in_array($roll_id, ['1', '2']))
                        @include('layouts.masterPartialsLayout.AdminMaster')
                    @elseif ($roll_id == 4)
                        @include('layouts.masterPartialsLayout.channelPartnerMaster')
                    @elseif (session('cp_type') == '3')
                        @include('layouts.masterPartialsLayout.warehouseMaster')
                    @endif
                </nav>

                <div class="sidebar-footer">
                    <p>© {{ date('Y') }} Stable Solar Energy</p>
                </div>
            </aside>

            <!-- Content -->
            <main class="flex-1 overflow-y-auto">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script src="/assets/js/jquery-3.6.0.min.js"></script>
    <script src="/assets/js/sweetalert2.all.min.js"></script>
    <script src="/assets/js/jquery.dataTables.min.js"></script>
    <script src="/assets/js/dataTables.bootstrap5.min.js"></script>
    <script src="/assets/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/dataTables.buttons.min.js"></script>
    <script src="/assets/js/buttons.bootstrap5.min.js"></script>
    <script src="/assets/js/jszip.min.js"></script>
    <script src="/assets/js/pdfmake.min.js"></script>
    <script src="/assets/js/vfs_fonts.min.js"></script>
    <script src="/assets/js/buttons.html5.min.js"></script>
    <script src="/assets/js/buttons.print.min.js"></script>
    <script src="/assets/js/select2.min.js"></script>

    <script>
        document.documentElement.style.overflow = 'hidden';
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const profileBtn = document.getElementById('profileBtn');
        const profileMenu = document.getElementById('profileMenu');

        const sidebarBackdrop = document.getElementById('sidebarBackdrop');

        sidebarToggle?.addEventListener('click', () => {
            const isHidden = sidebar.classList.contains('-translate-x-full');
            sidebar.classList.toggle('-translate-x-full');
            if (isHidden) {
                sidebarBackdrop?.classList.remove('hidden');
            } else {
                sidebarBackdrop?.classList.add('hidden');
            }
        });

        sidebarBackdrop?.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            sidebarBackdrop.classList.add('hidden');
        });

        profileBtn?.addEventListener('click', () => {
            profileMenu.classList.toggle('hidden');
        });

        document.addEventListener('click', (e) => {
            if (!profileBtn?.contains(e.target) && !profileMenu?.contains(e.target)) {
                profileMenu?.classList.add('hidden');
            }
        });

        document.querySelectorAll('[data-toggle]').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-toggle');
                const panel = document.getElementById(id);
                const arrow = document.querySelector(`[data-arrow="${id}"]`);
                panel?.classList.toggle('hidden');
                arrow?.classList.toggle('rotate-180');
            });
        });

        window.addEventListener('load', () => {
            document.querySelectorAll('[data-submenu]').forEach(btn => {
                const submenu = btn.getAttribute('data-submenu');
                const panel = document.getElementById(`submenu-${submenu}`);
                if (!panel?.classList.contains('hidden')) {
                    btn.classList.add('active');
                    const arrow = btn.querySelector('[data-arrow]');
                    if (arrow) arrow.classList.add('rotate-180');
                }
            });

            document.querySelectorAll('#alertContainer > div').forEach(alert => {
                setTimeout(() => {
                    alert.style.animation = 'slideOutRight 0.3s ease-in';
                    setTimeout(() => alert.remove(), 300);
                }, 5000);
            });
        });

        window.showNotification = function (type, title, message, duration = 5000) {
            const container = document.getElementById('toastContainer');

            const icons = {
                success: '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                error: '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>',
                warning: '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>',
                info: '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>'
            };

            const colors = {
                success: { bg: 'bg-green-50', border: 'border-green-500', text: 'text-green-900', icon: 'text-green-600', progress: 'bg-green-500' },
                error: { bg: 'bg-red-50', border: 'border-red-500', text: 'text-red-900', icon: 'text-red-600', progress: 'bg-red-500' },
                warning: { bg: 'bg-yellow-50', border: 'border-yellow-500', text: 'text-yellow-900', icon: 'text-yellow-600', progress: 'bg-yellow-500' },
                info: { bg: 'bg-blue-50', border: 'border-blue-500', text: 'text-blue-900', icon: 'text-blue-600', progress: 'bg-blue-500' }
            };

            const color = colors[type] || colors.info;

            const toast = document.createElement('div');
            toast.className = `toast-enter ${color.bg} rounded-lg shadow-lg border-l-4 ${color.border} p-3 pointer-events-auto`;
            toast.innerHTML = `
                <div class="flex items-start gap-2">
                    <div class="flex-shrink-0 ${color.icon}">${icons[type] || icons.info}</div>
                    <div class="flex-1">
                        <h4 class="text-xs font-semibold ${color.text}">${title}</h4>
                        <p class="text-xs ${color.text} mt-0.5">${message}</p>
                    </div>
                    <button onclick="this.closest('div').parentElement.remove()" class="flex-shrink-0 ${color.icon} hover:opacity-70">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="mt-1 h-1 bg-gray-200 rounded-full overflow-hidden">
                    <div class="progress-bar h-full ${color.progress}"></div>
                </div>
            `;

            container.appendChild(toast);

            setTimeout(() => {
                toast.classList.add('toast-exit');
                setTimeout(() => toast.remove(), 300);
            }, duration);
        };
    </script>

    @yield('js')
</body>

</html>