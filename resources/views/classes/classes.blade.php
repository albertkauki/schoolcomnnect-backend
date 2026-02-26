<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Management | EduCare Pro</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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

        /* Class Management Specific */
        .level-selector button.active {
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
        }
        .preview-card {
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
        }
        [x-cloak] { display: none !important; }
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
                            <h2 class="text-xl font-semibold text-dark dark:text-white">Class Management</h2>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Configure your school's streams and academic groups</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-6">
                        <!-- Dark Mode Toggle -->
                        <button id="darkModeToggle" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i id="darkModeIcon" class="fas fa-moon text-gray-600 dark:text-yellow-400"></i>
                        </button>
                        
                        <div class="relative">
                            <i class="fas fa-bell text-gray-500 dark:text-gray-300 text-xl hover:text-primary dark:hover:text-primary cursor-pointer"></i>
                            <span class="absolute -top-1 -right-1 bg-danger text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
                        </div>
                        
                        <div class="hidden md:flex items-center space-x-4">
                            <div class="bg-primary/10 dark:bg-primary/20 px-4 py-2 rounded-lg">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Total Classes</p>
                                <p class="text-sm font-medium text-dark dark:text-white">{{ $totalClasses }}</p>
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
            <main class="p-6" x-data="{ 
                level: 'O-Level', 
                form: 1, 
                stream: '', 
                combination: '',
                combinationName: '',
                get previewName() {
                    let name = 'Form ' + this.form;
                    if (this.level === 'A-Level' && this.combinationName) {
                        name += ' ' + this.combinationName;
                    }
                    if (this.stream) {
                        name += ' ' + this.stream.toUpperCase();
                    }
                    return name;
                }
            }">
                
                <!-- Stats Overview -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="stat-card bg-white dark:bg-gray-800 rounded-xl p-6 dashboard-card">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">Total Classes</p>
                                <h3 class="text-3xl font-bold text-dark dark:text-white mt-2">{{ $totalClasses }}</h3>
                                <p class="text-success text-xs mt-1"><i class="fas fa-school mr-1"></i> Active classes</p>
                            </div>
                            <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-xl">
                                <i class="fas fa-school text-info text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card bg-white dark:bg-gray-800 rounded-xl p-6 dashboard-card">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">O-Level Classes</p>
                                <h3 class="text-3xl font-bold text-dark dark:text-white mt-2">{{ $oLevelCount }}</h3>
                                <p class="text-success text-xs mt-1"><i class="fas fa-graduation-cap mr-1"></i> Forms 1-4</p>
                            </div>
                            <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-xl">
                                <i class="fas fa-user-graduate text-success text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card bg-white dark:bg-gray-800 rounded-xl p-6 dashboard-card">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">A-Level Classes</p>
                                <h3 class="text-3xl font-bold text-dark dark:text-white mt-2">{{ $aLevelCount }}</h3>
                                <p class="text-primary text-xs mt-1"><i class="fas fa-university mr-1"></i> Forms 5-6</p>
                            </div>
                            <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-xl">
                                <i class="fas fa-university text-primary text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card bg-white dark:bg-gray-800 rounded-xl p-6 dashboard-card">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">With Streams</p>
                                <h3 class="text-3xl font-bold text-dark dark:text-white mt-2">{{ $streamCount }}</h3>
                                <p class="text-info text-xs mt-1"><i class="fas fa-code-branch mr-1"></i> Streamed classes</p>
                            </div>
                            <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-xl">
                                <i class="fas fa-sitemap text-warning text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Alerts -->
                @if(session('success'))
                <div class="mb-6 bg-green-50 dark:bg-green-900/30 border-l-4 border-green-500 p-4 rounded-lg flex items-center shadow-sm">
                    <i class="fa-solid fa-circle-check text-green-500 mr-3"></i>
                    <p class="text-green-700 dark:text-green-300 font-medium">{{ session('success') }}</p>
                </div>
                @endif

                @if($errors->any())
                <div class="mb-6 bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 p-4 rounded-lg shadow-sm">
                    <div class="flex items-center mb-2">
                        <i class="fa-solid fa-triangle-exclamation text-red-500 mr-3"></i>
                        <p class="text-red-700 dark:text-red-300 font-bold">Validation Error</p>
                    </div>
                    <ul class="list-disc list-inside text-red-600 dark:text-red-300 text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                <!-- Class Management Content -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    
                    <!-- Create New Class -->
                    <div class="lg:col-span-4">
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 dashboard-card h-full">
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-lg font-semibold text-dark dark:text-white flex items-center">
                                    <i class="fas fa-layer-group text-primary mr-2"></i>
                                    Create New Class
                                </h2>
                                <span class="text-xs bg-primary/10 text-primary px-3 py-1 rounded-full">New</span>
                            </div>

                            <form action="{{ route('classes.store') }}" method="POST" class="space-y-6">
                                @csrf
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Academic Level</label>
                                    <div class="flex p-1 bg-gray-100 dark:bg-gray-700 rounded-xl level-selector">
                                        <button type="button" 
                                            @click="level = 'O-Level'; form = 1; combination = ''; combinationName = ''"
                                            class="flex-1 py-2.5 text-sm font-semibold rounded-lg transition-all duration-200"
                                            :class="level === 'O-Level' ? 'active' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'">
                                            O-Level
                                        </button>
                                        <button type="button" 
                                            @click="level = 'A-Level'; form = 5"
                                            class="flex-1 py-2.5 text-sm font-semibold rounded-lg transition-all duration-200"
                                            :class="level === 'A-Level' ? 'active' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'">
                                            A-Level
                                        </button>
                                    </div>
                                    <input type="hidden" name="level" :value="level">
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Form</label>
                                        <select name="form" x-model="form" 
                                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-primary focus:border-primary bg-white dark:bg-gray-700 text-dark dark:text-white transition-all">
                                            <template x-if="level === 'O-Level'">
                                                <template x-for="n in [1,2,3,4]">
                                                    <option :value="n" x-text="'Form ' + n"></option>
                                                </template>
                                            </template>
                                            <template x-if="level === 'A-Level'">
                                                <template x-for="n in [5,6]">
                                                    <option :value="n" x-text="'Form ' + n"></option>
                                                </template>
                                            </template>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Stream</label>
                                        <input type="text" name="stream" x-model="stream" placeholder="e.g. A, B, C"
                                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-primary focus:border-primary bg-white dark:bg-gray-700 text-dark dark:text-white uppercase">
                                    </div>
                                </div>

                                <div x-show="level === 'A-Level'" x-cloak x-transition:enter="transition ease-out duration-200" 
                                    x-transition:enter-start="opacity-0 transform -translate-y-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Combination</label>
                                    <select name="combination_id" 
                                        @change="combinationName = $el.options[$el.selectedIndex].text"
                                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-primary focus:border-primary bg-white dark:bg-gray-700 text-dark dark:text-white transition-all">
                                        <option value="">Select Combination</option>
                                        @foreach($combinations as $combo)
                                            <option value="{{ $combo->id }}">{{ $combo->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mt-6 p-4 preview-card rounded-xl text-white shadow-lg shadow-primary/20 relative overflow-hidden">
                                    <i class="fa-solid fa-signature absolute -right-4 -bottom-4 text-6xl text-white opacity-20"></i>
                                    <p class="text-xs font-medium text-white/90 uppercase tracking-widest mb-1">Generated Class Name</p>
                                    <h3 class="text-2xl font-bold" x-text="previewName"></h3>
                                </div>

                                <button type="submit" 
                                    class="w-full bg-gray-900 dark:bg-gray-800 text-white py-3.5 rounded-xl font-bold hover:bg-black dark:hover:bg-gray-900 transition-all shadow-lg shadow-gray-200 dark:shadow-gray-900 flex items-center justify-center">
                                    <i class="fas fa-plus-circle mr-2"></i>
                                    Create Class
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Existing Classes -->
                    <div class="lg:col-span-8">
                        <div class="bg-white dark:bg-gray-800 rounded-xl dashboard-card overflow-hidden h-full">
                            <div class="p-6 border-b dark:border-gray-700 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                <div>
                                    <h2 class="text-lg font-semibold text-dark dark:text-white">Existing Classes</h2>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage all classes in the system</p>
                                </div>
                                <div class="relative w-full sm:w-auto">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                        <i class="fa-solid fa-magnifying-glass text-sm"></i>
                                    </span>
                                    <input type="text" placeholder="Search class..." 
                                        class="pl-10 pr-4 py-2.5 w-full sm:w-64 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-primary focus:border-primary bg-white dark:bg-gray-700 text-dark dark:text-white">
                                </div>
                            </div>
                            
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead>
                                        <tr class="text-left text-gray-500 dark:text-gray-400 text-sm border-b dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                                            <th class="px-6 py-3.5 font-medium">Class Name</th>
                                            <th class="px-6 py-3.5 font-medium">Level</th>
                                            <th class="px-6 py-3.5 font-medium">Stream</th>
                                            <th class="px-6 py-3.5 font-medium text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                        @forelse($classes as $class)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                    <div class="w-10 h-10 rounded-lg bg-primary/10 dark:bg-primary/20 flex items-center justify-center text-primary font-bold mr-3">
                                                        <i class="fas fa-school"></i>
                                                    </div>
                                                    <div>
                                                        <span class="font-semibold text-dark dark:text-white block">{{ $class->name }}</span>
                                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                                            {{ $class->students_count ?? 0 }} students • {{ $class->subjects_count ?? 0 }} subjects
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                                    {{ $class->level == 'O-Level' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' : 'bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300' }}">
                                                    <i class="fas {{ $class->level == 'O-Level' ? 'fa-user-graduate' : 'fa-university' }} mr-1.5"></i>
                                                    {{ $class->level }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                @if($class->stream)
                                                    <span class="text-sm font-medium text-dark dark:text-white bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded">
                                                        {{ $class->stream }}
                                                    </span>
                                                @else
                                                    <span class="text-sm text-gray-400 dark:text-gray-500">—</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex justify-center space-x-2">
                                                    <button class="p-2 text-info hover:text-info-dark hover:bg-info/10 rounded-lg transition-all" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="p-2 text-primary hover:text-primary-dark hover:bg-primary/10 rounded-lg transition-all" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="p-2 text-danger hover:text-danger-dark hover:bg-danger/10 rounded-lg transition-all" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-20 text-center">
                                                <div class="flex flex-col items-center">
                                                    <i class="fa-solid fa-folder-open text-gray-200 dark:text-gray-600 text-5xl mb-4"></i>
                                                    <p class="text-gray-400 dark:text-gray-500 font-medium">No classes found</p>
                                                    <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Create your first class using the form</p>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            
                            @if($classes->hasPages())
                            <div class="p-6 border-t dark:border-gray-700">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Showing {{ $classes->firstItem() }} to {{ $classes->lastItem() }} of {{ $classes->total() }} classes
                                    </p>
                                    <div class="flex space-x-2">
                                        @if($classes->onFirstPage())
                                            <span class="px-3 py-1.5 bg-gray-100 dark:bg-gray-700 rounded text-gray-400 dark:text-gray-500 cursor-not-allowed">
                                                <i class="fas fa-chevron-left"></i>
                                            </span>
                                        @else
                                            <a href="{{ $classes->previousPageUrl() }}" 
                                                class="px-3 py-1.5 bg-primary/10 text-primary rounded hover:bg-primary/20 transition-colors">
                                                <i class="fas fa-chevron-left"></i>
                                            </a>
                                        @endif
                                        
                                        @if($classes->hasMorePages())
                                            <a href="{{ $classes->nextPageUrl() }}" 
                                                class="px-3 py-1.5 bg-primary/10 text-primary rounded hover:bg-primary/20 transition-colors">
                                                <i class="fas fa-chevron-right"></i>
                                            </a>
                                        @else
                                            <span class="px-3 py-1.5 bg-gray-100 dark:bg-gray-700 rounded text-gray-400 dark:text-gray-500 cursor-not-allowed">
                                                <i class="fas fa-chevron-right"></i>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </main>
            
            <!-- Footer -->
            <footer class="bg-white dark:bg-gray-800 border-t dark:border-gray-700 py-4 px-6">
                <div class="flex flex-col md:flex-row items-center justify-between">
                    <p class="text-gray-500 dark:text-gray-400 text-sm">© 2023 EduCare Pro School Management System. Class Management Module</p>
                    <div class="flex space-x-4 mt-2 md:mt-0">
                        <button class="text-gray-500 dark:text-gray-400 hover:text-primary text-sm flex items-center">
                            <i class="fas fa-download mr-1"></i>
                            Export List
                        </button>
                        <button class="text-gray-500 dark:text-gray-400 hover:text-primary text-sm flex items-center">
                            <i class="fas fa-print mr-1"></i>
                            Print Directory
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

            // Level selector active state
            const levelButtons = document.querySelectorAll('.level-selector button');
            levelButtons.forEach(button => {
                button.addEventListener('click', function() {
                    levelButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        });
    </script>
</body>
</html>
