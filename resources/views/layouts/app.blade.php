<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'EduCare Pro')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .sidebar-item:hover, .sidebar-item.active {
            background: linear-gradient(90deg, rgba(79, 70, 229, 0.1) 0%, rgba(79, 70, 229, 0.05) 100%);
            border-left: 4px solid #4f46e5;
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .dashboard-card {
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }
        .progress-bar {
            height: 8px;
            border-radius: 4px;
            background-color: #e5e7eb;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            border-radius: 4px;
            transition: width 0.5s ease;
        }

        .dark .sidebar-item:hover, .dark .sidebar-item.active {
            background: linear-gradient(90deg, rgba(79, 70, 229, 0.2) 0%, rgba(79, 70, 229, 0.1) 100%);
        }
        .dark .progress-bar {
            background-color: #374151;
        }
        .dark .dashboard-card {
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.3), 0 1px 2px 0 rgba(0, 0, 0, 0.2);
        }

        .sidebar-collapsed {
            width: 80px !important;
        }
        .sidebar-collapsed .sidebar-text,
        .sidebar-collapsed .logo-text,
        .sidebar-collapsed .admin-info,
        .sidebar-collapsed .sidebar-badge,
        .sidebar-collapsed .nav-heading {
            display: none;
        }
        .main-content-expanded {
            margin-left: 80px;
        }

        #sidebar {
            transition: width 0.3s ease;
        }
        #mainContent {
            transition: margin-left 0.3s ease;
        }
    </style>
    @stack('styles')
</head>
<body class="font-inter bg-gray-50 dark:bg-gray-900">
    <div class="flex h-screen">
        <div id="sidebar" class="w-64 bg-white dark:bg-gray-800 shadow-lg h-screen overflow-y-auto flex flex-col">
            <div class="p-6">
                <div class="flex items-center space-x-3">
                    <div class="bg-gradient-to-br from-primary to-secondary p-2 rounded-xl">
                        <i class="fas fa-school text-white text-2xl"></i>
                    </div>
                    <div class="logo-text">
                        <h1 class="text-xl font-bold text-dark dark:text-white">EduCare Pro</h1>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Intelligent School Management</p>
                    </div>
                </div>
            </div>

            <nav class="mt-8">
                <div class="px-4 mb-4">
                    <h3 class="nav-heading text-xs uppercase text-gray-500 dark:text-gray-400 font-semibold tracking-wide">Navigation</h3>
                </div>

                @php
                    $isDashboard = request()->routeIs('showDashboard') || request()->is('/');
                @endphp
                <a href="{{ route('showDashboard') }}" class="sidebar-item flex items-center px-6 py-3 text-dark dark:text-white {{ $isDashboard ? 'active text-primary' : '' }}">
                    <i class="fas fa-tachometer-alt w-5 mr-3 {{ $isDashboard ? 'text-primary' : 'text-gray-500 dark:text-gray-400' }}"></i>
                    <span class="sidebar-text">Dashboard</span>
                </a>

                <a href="#" class="sidebar-item flex items-center px-6 py-3 text-dark dark:text-white">
                    <i class="fas fa-user-check w-5 mr-3 text-gray-500 dark:text-gray-400"></i>
                    <span class="sidebar-text">Approvals</span>
                    <span class="sidebar-badge ml-auto bg-danger text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
                </a>

                <a href="#" class="sidebar-item flex items-center px-6 py-3 text-dark dark:text-white">
                    <i class="fas fa-exclamation-triangle w-5 mr-3 text-gray-500 dark:text-gray-400"></i>
                    <span class="sidebar-text">Attention Needed</span>
                    <span class="sidebar-badge ml-auto bg-warning text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">8</span>
                </a>

                @php $isStudents = request()->routeIs('showStudents'); @endphp
                <a href="{{ route('showStudents') }}" class="sidebar-item flex items-center px-6 py-3 text-dark dark:text-white {{ $isStudents ? 'active text-primary' : '' }}">
                    <i class="fas fa-users w-5 mr-3 {{ $isStudents ? 'text-primary' : 'text-gray-500 dark:text-gray-400' }}"></i>
                    <span class="sidebar-text">Students</span>
                </a>

                @php $isTeachers = request()->routeIs('showTeachers'); @endphp
                <a href="{{ route('showTeachers') }}" class="sidebar-item flex items-center px-6 py-3 text-dark dark:text-white {{ $isTeachers ? 'active text-primary' : '' }}">
                    <i class="fas fa-chalkboard-teacher w-5 mr-3 {{ $isTeachers ? 'text-primary' : 'text-gray-500 dark:text-gray-400' }}"></i>
                    <span class="sidebar-text">Teachers</span>
                </a>

                @php $isRules = request()->routeIs('showRules'); @endphp
                <a href="{{ route('showRules') }}" class="sidebar-item flex items-center px-6 py-3 text-dark dark:text-white {{ $isRules ? 'active text-primary' : '' }}">
                    <i class="fas fa-gavel w-5 mr-3 {{ $isRules ? 'text-primary' : 'text-gray-500 dark:text-gray-400' }}"></i>
                    <span class="sidebar-text">Rules</span>
                </a>

                <a href="#" class="sidebar-item flex items-center px-6 py-3 text-dark dark:text-white">
                    <i class="fas fa-book-open w-5 mr-3 text-gray-500 dark:text-gray-400"></i>
                    <span class="sidebar-text">Subjects & Classes</span>
                </a>

                <div class="px-4 mt-8 mb-4">
                    <h3 class="nav-heading text-xs uppercase text-gray-500 dark:text-gray-400 font-semibold tracking-wide">Management</h3>
                </div>

                <a href="#" class="sidebar-item flex items-center px-6 py-3 text-dark dark:text-white">
                    <i class="fas fa-file-invoice w-5 mr-3 text-gray-500 dark:text-gray-400"></i>
                    <span class="sidebar-text">Financial</span>
                </a>

                <a href="#" class="sidebar-item flex items-center px-6 py-3 text-dark dark:text-white">
                    <i class="fas fa-chart-line w-5 mr-3 text-gray-500 dark:text-gray-400"></i>
                    <span class="sidebar-text">Academic Reports</span>
                </a>

                <a href="#" class="sidebar-item flex items-center px-6 py-3 text-dark dark:text-white">
                    <i class="fas fa-cog w-5 mr-3 text-gray-500 dark:text-gray-400"></i>
                    <span class="sidebar-text">Settings</span>
                </a>
            </nav>

            <div class="mt-auto w-full p-6 border-t dark:border-gray-700">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-white font-bold">
                        AD
                    </div>
                    <div class="admin-info ml-3">
                        <p class="text-sm font-medium text-dark dark:text-white">Admin User</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">School Administrator</p>
                    </div>
                </div>
            </div>
        </div>

        <div id="mainContent" class="flex-1 overflow-y-auto">
            <header class="bg-white dark:bg-gray-800 shadow-sm py-4 px-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <button id="sidebarToggle" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i class="fas fa-bars text-gray-600 dark:text-gray-300"></i>
                        </button>

                        <div>
                            <h2 class="text-xl font-semibold text-dark dark:text-white">@yield('page_title')</h2>
                            @hasSection('page_subtitle')
                                <p class="text-gray-500 dark:text-gray-400 text-sm">@yield('page_subtitle')</p>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center space-x-6">
                        <button id="darkModeToggle" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i id="darkModeIcon" class="fas fa-moon text-gray-600 dark:text-yellow-400"></i>
                        </button>

                        <div class="relative">
                            <i class="fas fa-bell text-gray-500 dark:text-gray-300 text-xl hover:text-primary dark:hover:text-primary cursor-pointer"></i>
                            @hasSection('notification_count')
                                <span class="absolute -top-1 -right-1 bg-danger text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">@yield('notification_count')</span>
                            @endif
                        </div>

                        @hasSection('topbar_stats')
                            @yield('topbar_stats')
                        @endif

                        @hasSection('topbar_actions')
                            @yield('topbar_actions')
                        @endif

                        <div class="flex items-center space-x-3 border-l dark:border-gray-700 pl-6">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-white font-bold">
                                AD
                            </div>
                            <div class="hidden md:block">
                                <p class="text-sm font-medium text-dark dark:text-white">Administrator</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">admin@educare.edu</p>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            @yield('content')
        </div>
    </div>

    @stack('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const darkModeToggle = document.getElementById('darkModeToggle');
            const darkModeIcon = document.getElementById('darkModeIcon');
            const savedTheme = localStorage.getItem('theme') ||
                (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');

            if (savedTheme === 'dark') {
                document.documentElement.classList.add('dark');
                if (darkModeIcon) {
                    darkModeIcon.classList.remove('fa-moon');
                    darkModeIcon.classList.add('fa-sun');
                }
            }

            if (darkModeToggle) {
                darkModeToggle.addEventListener('click', function() {
                    if (document.documentElement.classList.contains('dark')) {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('theme', 'light');
                        if (darkModeIcon) {
                            darkModeIcon.classList.remove('fa-sun');
                            darkModeIcon.classList.add('fa-moon');
                        }
                    } else {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('theme', 'dark');
                        if (darkModeIcon) {
                            darkModeIcon.classList.remove('fa-moon');
                            darkModeIcon.classList.add('fa-sun');
                        }
                    }
                });
            }

            const sidebarItems = document.querySelectorAll('.sidebar-item');
            sidebarItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    const href = this.getAttribute('href');
                    if (href === '#' || !href) {
                        e.preventDefault();
                    }
                    sidebarItems.forEach(i => {
                        i.classList.remove('active', 'text-primary');
                        const icon = i.querySelector('i');
                        if (icon) {
                            icon.classList.remove('text-primary');
                            icon.classList.add('text-gray-500', 'dark:text-gray-400');
                        }
                    });

                    this.classList.add('active', 'text-primary');
                    const icon = this.querySelector('i');
                    if (icon) {
                        icon.classList.add('text-primary');
                        icon.classList.remove('text-gray-500', 'dark:text-gray-400');
                    }
                });
            });

            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            let sidebarCollapsed = false;

            if (sidebarToggle && sidebar && mainContent) {
                sidebarToggle.addEventListener('click', function() {
                    sidebarCollapsed = !sidebarCollapsed;

                    if (sidebarCollapsed) {
                        sidebar.classList.add('sidebar-collapsed');
                        mainContent.classList.add('main-content-expanded');
                        sidebarToggle.innerHTML = '<i class="fas fa-chevron-right text-gray-600 dark:text-gray-300"></i>';
                    } else {
                        sidebar.classList.remove('sidebar-collapsed');
                        mainContent.classList.remove('main-content-expanded');
                        sidebarToggle.innerHTML = '<i class="fas fa-bars text-gray-600 dark:text-gray-300"></i>';
                    }

                    sidebarItems.forEach(item => {
                        if (sidebarCollapsed) {
                            item.classList.add('justify-center', 'px-4');
                        } else {
                            item.classList.remove('justify-center', 'px-4');
                        }
                    });
                });
            }
        });
    </script>
</body>
</html>
