<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Academic Rules | EduCare Pro</title>
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
        
        /* Dark mode styles */
        .dark .sidebar-item:hover, .dark .sidebar-item.active {
            background: linear-gradient(90deg, rgba(79, 70, 229, 0.2) 0%, rgba(79, 70, 229, 0.1) 100%);
        }
        .dark .dashboard-card {
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.3), 0 1px 2px 0 rgba(0, 0, 0, 0.2);
        }
        
        /* Collapsible sidebar */
        .sidebar-collapsed {
            width: 80px !important;
        }
        .sidebar-collapsed .sidebar-text {
            display: none;
        }
        .sidebar-collapsed .logo-text {
            display: none;
        }
        .sidebar-collapsed .admin-info {
            display: none;
        }
        .sidebar-collapsed .sidebar-badge {
            display: none;
        }
        .sidebar-collapsed .nav-heading {
            display: none;
        }
        .main-content-expanded {
            margin-left: 80px;
        }
        
        /* Transition for smooth sidebar collapse */
        #sidebar {
            transition: width 0.3s ease;
        }
        #mainContent {
            transition: margin-left 0.3s ease;
        }

        /* Academic Rules Specific */
        .grade-a { background: linear-gradient(135deg, #10b981, #059669); }
        .grade-b { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
        .grade-c { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .grade-d { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
        .grade-e { background: linear-gradient(135deg, #ec4899, #db2777); }
        .grade-f { background: linear-gradient(135deg, #ef4444, #dc2626); }
        .grade-s { background: linear-gradient(135deg, #6b7280, #4b5563); }
        
        .division-i { background: linear-gradient(135deg, #10b981, #059669); }
        .division-ii { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
        .division-iii { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .division-iv { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
        .division-0 { background: linear-gradient(135deg, #ef4444, #dc2626); }
        
        .editable-input {
            width: 70px;
            padding: 4px 8px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            text-align: center;
            background: transparent;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        .editable-input:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }
        .dark .editable-input { 
            border-color: #4b5563; 
            color: white;
            background: rgba(255, 255, 255, 0.05);
        }
        .dark .editable-input:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .grade-badge {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .division-badge {
            padding: 4px 12px;
            border-radius: 20px;
            color: white;
            font-weight: 600;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
    </style>
</head>
<body class="font-inter bg-gray-50 dark:bg-gray-900">
    <div class="flex h-screen">
        @include('components.sidebar')
        
        <!-- Main Content -->
        <div id="mainContent" class="flex-1 overflow-y-auto">
            <!-- Topbar -->
            <header class="bg-white dark:bg-gray-800 shadow-sm py-4 px-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <button id="sidebarToggle" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i class="fas fa-bars text-gray-600 dark:text-gray-300"></i>
                        </button>
                        
                        <div>
                            <h2 class="text-xl font-semibold text-dark dark:text-white">Academic Rules</h2>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Dynamic NECTA Grading Configuration</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-6">
                        <!-- Dark Mode Toggle -->
                        <button id="darkModeToggle" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i id="darkModeIcon" class="fas fa-moon text-gray-600 dark:text-yellow-400"></i>
                        </button>
                        
                        <div class="relative">
                            <i class="fas fa-bell text-gray-500 dark:text-gray-300 text-xl hover:text-primary dark:hover:text-primary cursor-pointer"></i>
                            <span class="absolute -top-1 -right-1 bg-danger text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">1</span>
                        </div>
                        
                        <div class="hidden md:flex items-center space-x-4">
                            <div class="bg-primary/10 dark:bg-primary/20 px-4 py-2 rounded-lg">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Academic Year</p>
                                <p class="text-sm font-medium text-dark dark:text-white">2023-2024</p>
                            </div>
                        </div>
                        
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
            
            <!-- Main Content -->
            <main class="p-6">
                <!-- Stats Overview -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="stat-card bg-white dark:bg-gray-800 rounded-xl p-6 dashboard-card">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">O-Level Grades</p>
                                <h3 class="text-3xl font-bold text-dark dark:text-white mt-2">{{ $oLevelGrades->count() }}</h3>
                                <p class="text-primary text-xs mt-1"><i class="fas fa-graduation-cap mr-1"></i> A to F</p>
                            </div>
                            <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-xl">
                                <i class="fas fa-chart-bar text-info text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card bg-white dark:bg-gray-800 rounded-xl p-6 dashboard-card">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">A-Level Grades</p>
                                <h3 class="text-3xl font-bold text-dark dark:text-white mt-2">{{ $aLevelGrades->count() }}</h3>
                                <p class="text-secondary text-xs mt-1"><i class="fas fa-university mr-1"></i> A to S</p>
                            </div>
                            <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-xl">
                                <i class="fas fa-chart-line text-primary text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card bg-white dark:bg-gray-800 rounded-xl p-6 dashboard-card">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">O-Level Divisions</p>
                                <h3 class="text-3xl font-bold text-dark dark:text-white mt-2">{{ $oLevelDivisions->count() }}</h3>
                                <p class="text-success text-xs mt-1"><i class="fas fa-layer-group mr-1"></i> I to 0</p>
                            </div>
                            <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-xl">
                                <i class="fas fa-sort-amount-down text-success text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card bg-white dark:bg-gray-800 rounded-xl p-6 dashboard-card">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">A-Level Divisions</p>
                                <h3 class="text-3xl font-bold text-dark dark:text-white mt-2">{{ $aLevelDivisions->count() }}</h3>
                                <p class="text-warning text-xs mt-1"><i class="fas fa-sort-numeric-up mr-1"></i> I to IV</p>
                            </div>
                            <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-xl">
                                <i class="fas fa-list-ol text-warning text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Bar -->
                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-dark dark:text-white">Grading System Configuration</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Edit values directly and save changes</p>
                    </div>
                    <button id="saveAllBtn" class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary-dark transition-all shadow-lg shadow-primary/20 flex items-center">
                        <i class="fas fa-save mr-2"></i>Save All Changes
                    </button>
                </div>
                
                <!-- Grading Configuration -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    
                    <!-- O-Level Section -->
                    <div class="space-y-6">
                        <!-- O-Level Grades -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 dashboard-card border-t-4 border-primary">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-lg font-bold text-dark dark:text-white flex items-center">
                                    <i class="fas fa-graduation-cap text-primary mr-2"></i>
                                    O-Level (Form 1–4)
                                </h3>
                                <span class="text-xs bg-primary/10 text-primary px-3 py-1 rounded-full">Grades</span>
                            </div>
                            
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead>
                                        <tr class="text-left text-gray-500 dark:text-gray-400 text-sm border-b dark:border-gray-700">
                                            <th class="pb-3 font-medium">Grade</th>
                                            <th class="pb-3 font-medium">Score Range (%)</th>
                                            <th class="pb-3 font-medium text-center">Points</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($oLevelGrades as $grade)
                                        <tr class="border-b last:border-0 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                            <td class="py-4">
                                                <div class="grade-badge grade-{{ strtolower($grade->grade) }}">
                                                    {{ $grade->grade }}
                                                </div>
                                            </td>
                                            <td class="py-4">
                                                <div class="flex items-center space-x-2">
                                                    <input type="number" 
                                                        class="editable-input grade-input" 
                                                        data-id="{{ $grade->id }}" 
                                                        data-field="min_score" 
                                                        value="{{ $grade->min_score }}"
                                                        min="0" max="100">
                                                    <span class="text-gray-400">to</span>
                                                    <input type="number" 
                                                        class="editable-input grade-input" 
                                                        data-id="{{ $grade->id }}" 
                                                        data-field="max_score" 
                                                        value="{{ $grade->max_score }}"
                                                        min="0" max="100">
                                                    <span class="text-gray-500 dark:text-gray-400 text-sm">%</span>
                                                </div>
                                            </td>
                                            <td class="py-4 text-center">
                                                <input type="number" 
                                                    class="editable-input grade-input w-16" 
                                                    data-id="{{ $grade->id }}" 
                                                    data-field="points" 
                                                    value="{{ $grade->points }}"
                                                    min="0" max="20">
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- O-Level Divisions -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 dashboard-card">
                            <div class="flex items-center justify-between mb-6">
                                <h4 class="font-bold text-dark dark:text-white flex items-center">
                                    <i class="fas fa-layer-group text-success mr-2"></i>
                                    O-Level Divisions
                                </h4>
                                <span class="text-xs bg-success/10 text-success px-3 py-1 rounded-full">Divisions</span>
                            </div>
                            
                            <table class="w-full">
                                <tbody>
                                    @foreach($oLevelDivisions as $div)
                                    <tr class="border-b last:border-0 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                        <td class="py-4">
                                            <span class="division-badge division-{{ strtolower($div->division) }}">
                                                <i class="fas fa-trophy"></i>
                                                Division {{ $div->division }}
                                            </span>
                                        </td>
                                        <td class="py-4">
                                            <div class="flex items-center space-x-2">
                                                <input type="number" 
                                                    class="editable-input div-input" 
                                                    data-id="{{ $div->id }}" 
                                                    data-field="min_points" 
                                                    value="{{ $div->min_points }}"
                                                    min="0" max="100">
                                                <span class="text-gray-400">to</span>
                                                <input type="number" 
                                                    class="editable-input div-input" 
                                                    data-id="{{ $div->id }}" 
                                                    data-field="max_points" 
                                                    value="{{ $div->max_points }}"
                                                    min="0" max="100">
                                                <span class="text-gray-500 dark:text-gray-400 text-sm">points</span>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- A-Level Section -->
                    <div class="space-y-6">
                        <!-- A-Level Grades -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 dashboard-card border-t-4 border-secondary">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-lg font-bold text-dark dark:text-white flex items-center">
                                    <i class="fas fa-university text-secondary mr-2"></i>
                                    A-Level (Form 5–6)
                                </h3>
                                <span class="text-xs bg-secondary/10 text-secondary px-3 py-1 rounded-full">Grades</span>
                            </div>
                            
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead>
                                        <tr class="text-left text-gray-500 dark:text-gray-400 text-sm border-b dark:border-gray-700">
                                            <th class="pb-3 font-medium">Grade</th>
                                            <th class="pb-3 font-medium">Score Range (%)</th>
                                            <th class="pb-3 font-medium text-center">Points</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($aLevelGrades as $grade)
                                        <tr class="border-b last:border-0 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                            <td class="py-4">
                                                <div class="grade-badge grade-{{ strtolower($grade->grade) }}">
                                                    {{ $grade->grade }}
                                                </div>
                                            </td>
                                            <td class="py-4">
                                                <div class="flex items-center space-x-2">
                                                    <input type="number" 
                                                        class="editable-input grade-input" 
                                                        data-id="{{ $grade->id }}" 
                                                        data-field="min_score" 
                                                        value="{{ $grade->min_score }}"
                                                        min="0" max="100">
                                                    <span class="text-gray-400">to</span>
                                                    <input type="number" 
                                                        class="editable-input grade-input" 
                                                        data-id="{{ $grade->id }}" 
                                                        data-field="max_score" 
                                                        value="{{ $grade->max_score }}"
                                                        min="0" max="100">
                                                    <span class="text-gray-500 dark:text-gray-400 text-sm">%</span>
                                                </div>
                                            </td>
                                            <td class="py-4 text-center">
                                                <input type="number" 
                                                    class="editable-input grade-input w-16" 
                                                    data-id="{{ $grade->id }}" 
                                                    data-field="points" 
                                                    value="{{ $grade->points }}"
                                                    min="0" max="20">
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- A-Level Divisions -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 dashboard-card">
                            <div class="flex items-center justify-between mb-6">
                                <h4 class="font-bold text-dark dark:text-white flex items-center">
                                    <i class="fas fa-sort-numeric-up text-warning mr-2"></i>
                                    A-Level Divisions
                                </h4>
                                <span class="text-xs bg-warning/10 text-warning px-3 py-1 rounded-full">Divisions</span>
                            </div>
                            
                            <table class="w-full">
                                <tbody>
                                    @foreach($aLevelDivisions as $div)
                                    <tr class="border-b last:border-0 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                        <td class="py-4">
                                            <span class="division-badge division-{{ strtolower($div->division) }}">
                                                <i class="fas fa-medal"></i>
                                                Division {{ $div->division }}
                                            </span>
                                        </td>
                                        <td class="py-4">
                                            <div class="flex items-center space-x-2">
                                                <input type="number" 
                                                    class="editable-input div-input" 
                                                    data-id="{{ $div->id }}" 
                                                    data-field="min_points" 
                                                    value="{{ $div->min_points }}"
                                                    min="0" max="100">
                                                <span class="text-gray-400">to</span>
                                                <input type="number" 
                                                    class="editable-input div-input" 
                                                    data-id="{{ $div->id }}" 
                                                    data-field="max_points" 
                                                    value="{{ $div->max_points }}"
                                                    min="0" max="100">
                                                <span class="text-gray-500 dark:text-gray-400 text-sm">points</span>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
            
            <!-- Footer -->
            <footer class="bg-white dark:bg-gray-800 border-t dark:border-gray-700 py-4 px-6">
                <div class="flex flex-col md:flex-row items-center justify-between">
                    <p class="text-gray-500 dark:text-gray-400 text-sm">© 2023 EduCare Pro School Management System. Academic Rules Module</p>
                    <div class="flex space-x-4 mt-2 md:mt-0">
                        <button class="text-gray-500 dark:text-gray-400 hover:text-primary text-sm flex items-center">
                            <i class="fas fa-history mr-1"></i>
                            Reset to Defaults
                        </button>
                        <button class="text-gray-500 dark:text-gray-400 hover:text-primary text-sm flex items-center">
                            <i class="fas fa-file-export mr-1"></i>
                            Export Rules
                        </button>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar interaction
            const sidebarItems = document.querySelectorAll('.sidebar-item');
            sidebarItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    const href = this.getAttribute('href');
                    if (href === '#' || !href) {
                        e.preventDefault();
                    }
                    sidebarItems.forEach(i => {
                        i.classList.remove('active');
                        i.classList.remove('text-primary');
                        const icon = i.querySelector('i');
                        if (icon) {
                            icon.classList.remove('text-primary');
                            icon.classList.add('text-gray-500', 'dark:text-gray-400');
                        }
                    });
                    
                    this.classList.add('active');
                    this.classList.add('text-primary', 'dark:text-primary');
                    const icon = this.querySelector('i');
                    if (icon) {
                        icon.classList.add('text-primary');
                        icon.classList.remove('text-gray-500', 'dark:text-gray-400');
                    }
                });
            });
            
            // Dark Mode Toggle
            const darkModeToggle = document.getElementById('darkModeToggle');
            const darkModeIcon = document.getElementById('darkModeIcon');
            
            // Check for saved theme or prefer-color-scheme
            const savedTheme = localStorage.getItem('theme') || 
                (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            
            if (savedTheme === 'dark') {
                document.documentElement.classList.add('dark');
                darkModeIcon.classList.remove('fa-moon');
                darkModeIcon.classList.add('fa-sun');
            }
            
            darkModeToggle.addEventListener('click', function() {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                    darkModeIcon.classList.remove('fa-sun');
                    darkModeIcon.classList.add('fa-moon');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                    darkModeIcon.classList.remove('fa-moon');
                    darkModeIcon.classList.add('fa-sun');
                }
            });
            
            // Sidebar Collapse Toggle
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            let sidebarCollapsed = false;
            
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
                
                // Update sidebar items
                sidebarItems.forEach(item => {
                    if (sidebarCollapsed) {
                        item.classList.add('justify-center', 'px-4');
                    } else {
                        item.classList.remove('justify-center', 'px-4');
                    }
                });
            });
            
            // Add hover effects to cards
            const cards = document.querySelectorAll('.dashboard-card');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                });
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Save All Changes Button
            const saveAllBtn = document.getElementById('saveAllBtn');
            saveAllBtn.addEventListener('click', async function() {
                const btn = this;
                const originalText = btn.innerHTML;
                
                // Gather all grade inputs
                const grades = Array.from(document.querySelectorAll('.grade-input')).map(input => ({
                    id: input.dataset.id,
                    field: input.dataset.field,
                    value: input.value
                }));

                // Gather all division inputs
                const divisions = Array.from(document.querySelectorAll('.div-input')).map(input => ({
                    id: input.dataset.id,
                    field: input.dataset.field,
                    value: input.value
                }));

                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Saving...';

                try {
                    const response = await fetch("{{ route('academic-rules.store') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ grades, divisions })
                    });

                    if (response.ok) {
                        btn.classList.replace('bg-primary', 'bg-success');
                        btn.innerHTML = '<i class="fas fa-check mr-2"></i> Success!';
                        setTimeout(() => {
                            btn.classList.replace('bg-success', 'bg-primary');
                            btn.innerHTML = originalText;
                            btn.disabled = false;
                        }, 2000);
                    } else {
                        throw new Error('Failed to save');
                    }
                } catch (error) {
                    btn.classList.replace('bg-primary', 'bg-danger');
                    btn.innerHTML = '<i class="fas fa-exclamation-circle mr-2"></i> Failed!';
                    setTimeout(() => {
                        btn.classList.replace('bg-danger', 'bg-primary');
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    }, 2000);
                    console.error('Error saving academic rules:', error);
                }
            });

            // Input validation
            document.querySelectorAll('.editable-input').forEach(input => {
                input.addEventListener('change', function() {
                    const value = parseInt(this.value);
                    const min = parseInt(this.min) || 0;
                    const max = parseInt(this.max) || 100;
                    
                    if (value < min) this.value = min;
                    if (value > max) this.value = max;
                });
            });
        });
    </script>
</body>
</html>