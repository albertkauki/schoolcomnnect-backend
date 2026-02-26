<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Management | EduCare Pro</title>
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

        /* Teacher Management Specific */
        .assignment-card {
            transition: all 0.2s ease;
        }
        .assignment-card:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
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
                            <h2 class="text-xl font-semibold text-dark dark:text-white">Teacher Management</h2>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Total Teachers: {{ $teachers->total() }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-6">
                        <!-- Dark Mode Toggle -->
                        <button id="darkModeToggle" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i id="darkModeIcon" class="fas fa-moon text-gray-600 dark:text-yellow-400"></i>
                        </button>
                        
                        <div class="relative">
                            <i class="fas fa-bell text-gray-500 dark:text-gray-300 text-xl hover:text-primary dark:hover:text-primary cursor-pointer"></i>
                            <span class="absolute -top-1 -right-1 bg-danger text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">2</span>
                        </div>
                        
                        <div class="hidden md:flex items-center space-x-4">
                            <div class="bg-primary/10 dark:bg-primary/20 px-4 py-2 rounded-lg">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Academic Year</p>
                                <p class="text-sm font-medium text-dark dark:text-white">2025-2026</p>
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
                assignments: [{ subject_id: '', school_class_id: '' }],
                addAssignment() {
                    if(this.assignments.length < 2) {
                        this.assignments.push({ subject_id: '', school_class_id: '' });
                    }
                },
                removeAssignment(index) {
                    this.assignments.splice(index, 1);
                }
            }">
                
                <!-- Stats Overview -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="stat-card bg-white dark:bg-gray-800 rounded-xl p-6 dashboard-card">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">Total Teachers</p>
                                <h3 class="text-3xl font-bold text-dark dark:text-white mt-2">{{ $teachers->total() }}</h3>
                                <p class="text-success text-xs mt-1"><i class="fas fa-check-circle mr-1"></i> All Active</p>
                            </div>
                            <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-xl">
                                <i class="fas fa-chalkboard-teacher text-success text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card bg-white dark:bg-gray-800 rounded-xl p-6 dashboard-card">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">Subjects Covered</p>
                                <h3 class="text-3xl font-bold text-dark dark:text-white mt-2">{{ $subjects->count() }}</h3>
                                <p class="text-primary text-xs mt-1"><i class="fas fa-book mr-1"></i> All subjects assigned</p>
                            </div>
                            <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-xl">
                                <i class="fas fa-clipboard-list text-primary text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card bg-white dark:bg-gray-800 rounded-xl p-6 dashboard-card">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">Classes Covered</p>
                                <h3 class="text-3xl font-bold text-dark dark:text-white mt-2">{{ $classes->count() }}</h3>
                                <p class="text-info text-xs mt-1"><i class="fas fa-users mr-1"></i> All classes assigned</p>
                            </div>
                            <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-xl">
                                <i class="fas fa-school text-info text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card bg-white dark:bg-gray-800 rounded-xl p-6 dashboard-card">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">Pending Assignments</p>
                                <h3 class="text-3xl font-bold text-dark dark:text-white mt-2">0</h3>
                                <p class="text-success text-xs mt-1"><i class="fas fa-check-circle mr-1"></i> All assigned</p>
                            </div>
                            <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-xl">
                                <i class="fas fa-tasks text-warning text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Teacher Management Content -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    
                    <!-- Register New Teacher -->
                    <div class="lg:col-span-5">
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 dashboard-card h-full">
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-lg font-semibold text-dark dark:text-white">Register New Teacher</h2>
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-user-plus text-primary"></i>
                                    <span class="text-xs bg-primary/10 text-primary px-2 py-1 rounded-full">New</span>
                                </div>
                            </div>
                            
                            <form action="{{ route('teachers.store') }}" method="POST" class="space-y-4">
                                @csrf
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">First Name</label>
                                        <input type="text" name="first_name" required 
                                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-primary focus:border-primary bg-white dark:bg-gray-700 text-dark dark:text-white">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Last Name</label>
                                        <input type="text" name="last_name" required 
                                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-primary focus:border-primary bg-white dark:bg-gray-700 text-dark dark:text-white">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Address</label>
                                    <input type="email" name="email" required 
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-primary focus:border-primary bg-white dark:bg-gray-700 text-dark dark:text-white">
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gender</label>
                                        <select name="gender" 
                                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-primary focus:border-primary bg-white dark:bg-gray-700 text-dark dark:text-white">
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Academic Year</label>
                                        <input type="text" name="academic_year" value="2025/2026" 
                                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-primary focus:border-primary bg-white dark:bg-gray-700 text-dark dark:text-white">
                                    </div>
                                </div>

                                <div class="mt-6 border-t dark:border-gray-700 pt-4">
                                    <div class="flex justify-between items-center mb-4">
                                        <div>
                                            <label class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider">Subject Assignments</label>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Maximum 2 subjects per teacher</p>
                                        </div>
                                        <button type="button" @click="addAssignment()" x-show="assignments.length < 2" 
                                            class="text-xs bg-primary/10 text-primary dark:text-primary-light px-3 py-1.5 rounded-lg border border-primary/20 hover:bg-primary/20 transition-colors">
                                            <i class="fas fa-plus mr-1"></i> Add Subject
                                        </button>
                                    </div>

                                    <template x-for="(assignment, index) in assignments" :key="index">
                                        <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg mb-3 border border-gray-200 dark:border-gray-600 relative assignment-card">
                                            <div class="grid grid-cols-1 gap-3">
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Subject</label>
                                                    <select :name="`assignments[${index}][subject_id]`" required 
                                                        class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-2 focus:ring-primary focus:border-primary bg-white dark:bg-gray-700 text-dark dark:text-white">
                                                        <option value="">-- Select Subject --</option>
                                                        @foreach($subjects as $subject)
                                                            <option value="{{ $subject->id }}">{{ $subject->name }} ({{ $subject->level }})</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div>
                                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Assign to Class</label>
                                                    <select :name="`assignments[${index}][school_class_id]`" required 
                                                        class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-2 focus:ring-primary focus:border-primary bg-white dark:bg-gray-700 text-dark dark:text-white">
                                                        <option value="">-- Select Class --</option>
                                                        @foreach($classes as $class)
                                                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <button type="button" @click="removeAssignment(index)" x-show="assignments.length > 1" 
                                                class="absolute -top-2 -right-2 bg-danger text-white rounded-full w-5 h-5 text-xs flex items-center justify-center shadow-lg hover:bg-danger-dark transition-colors">
                                                &times;
                                            </button>
                                        </div>
                                    </template>
                                </div>

                                <button type="submit" 
                                    class="w-full bg-primary text-white py-3 rounded-xl font-bold hover:bg-primary-dark transition-all shadow-lg shadow-primary/20 mt-6 flex items-center justify-center">
                                    <i class="fas fa-user-plus mr-2"></i> Register Teacher
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Teacher Directory -->
                    <div class="lg:col-span-7">
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 dashboard-card h-full">
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-lg font-semibold text-dark dark:text-white">Teacher Directory</h2>
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs bg-success/10 text-success px-3 py-1 rounded-full">{{ $teachers->total() }} Active</span>
                                    <button class="text-gray-500 dark:text-gray-400 hover:text-primary p-2">
                                        <i class="fas fa-download"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead>
                                        <tr class="text-left text-gray-500 dark:text-gray-400 text-sm border-b dark:border-gray-700">
                                            <th class="pb-3 font-medium pl-4">Teacher</th>
                                            <th class="pb-3 font-medium">Assignments</th>
                                            <th class="pb-3 font-medium">Status</th>
                                            <th class="pb-3 font-medium pr-4 text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                        @foreach($teachers as $teacher)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                            <td class="py-4 pl-4">
                                                <div class="flex items-center">
                                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary/20 to-secondary/20 flex items-center justify-center text-primary font-bold mr-3">
                                                        {{ substr($teacher->first_name, 0, 1) }}{{ substr($teacher->last_name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <div class="font-medium text-dark dark:text-white">{{ $teacher->first_name }} {{ $teacher->last_name }}</div>
                                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $teacher->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-4">
                                                <div class="space-y-1">
                                                    @foreach($teacher->subjects as $subject)
                                                        <div class="flex items-center text-sm">
                                                            <span class="font-semibold text-primary dark:text-primary-light">{{ $subject->name }}</span>
                                                            <i class="fas fa-arrow-right mx-2 text-gray-400 text-xs"></i>
                                                            <span class="text-gray-600 dark:text-gray-300 font-medium">
                                                                {{ \App\Models\SchoolClass::find($subject->pivot->school_class_id)->name ?? 'N/A' }}
                                                            </span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="py-4">
                                                @if($teacher->status == 'active')
                                                    <span class="px-3 py-1 bg-success/10 text-success rounded-full text-xs font-bold uppercase">
                                                        <i class="fas fa-check-circle mr-1"></i> Active
                                                    </span>
                                                @else
                                                    <span class="px-3 py-1 bg-warning/10 text-warning rounded-full text-xs font-bold uppercase">
                                                        <i class="fas fa-clock mr-1"></i> {{ ucfirst($teacher->status) }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="py-4 pr-4">
                                                <div class="flex justify-end space-x-2">
                                                    <button class="text-info hover:text-info-dark p-1">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="text-primary hover:text-primary-dark p-1">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="text-danger hover:text-danger-dark p-1">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            @if($teachers->hasPages())
                            <div class="mt-6 pt-4 border-t dark:border-gray-700">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Showing {{ $teachers->firstItem() }} to {{ $teachers->lastItem() }} of {{ $teachers->total() }} teachers
                                    </p>
                                    <div class="flex space-x-2">
                                        @if($teachers->onFirstPage())
                                            <span class="px-3 py-1 bg-gray-100 dark:bg-gray-700 rounded text-gray-400 cursor-not-allowed">
                                                <i class="fas fa-chevron-left"></i>
                                            </span>
                                        @else
                                            <a href="{{ $teachers->previousPageUrl() }}" class="px-3 py-1 bg-primary/10 text-primary rounded hover:bg-primary/20">
                                                <i class="fas fa-chevron-left"></i>
                                            </a>
                                        @endif
                                        
                                        @if($teachers->hasMorePages())
                                            <a href="{{ $teachers->nextPageUrl() }}" class="px-3 py-1 bg-primary/10 text-primary rounded hover:bg-primary/20">
                                                <i class="fas fa-chevron-right"></i>
                                            </a>
                                        @else
                                            <span class="px-3 py-1 bg-gray-100 dark:bg-gray-700 rounded text-gray-400 cursor-not-allowed">
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
                    <p class="text-gray-500 dark:text-gray-400 text-sm">© 2023 EduCare Pro School Management System. Teacher Management Module</p>
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
        });
    </script>
</body>
</html>
