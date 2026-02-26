<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Students Management | EduCare Pro</title>
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

        /* Student Management Specific */
        .student-status-active {
            background: linear-gradient(90deg, rgba(16, 185, 129, 0.2) 0%, rgba(16, 185, 129, 0.1) 100%);
            color: #10b981;
        }
        .student-status-inactive {
            background: linear-gradient(90deg, rgba(239, 68, 68, 0.2) 0%, rgba(239, 68, 68, 0.1) 100%);
            color: #ef4444;
        }
        .student-status-graduated {
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.2) 0%, rgba(59, 130, 246, 0.1) 100%);
            color: #3b82f6;
        }
        
        /* Modal animations - FIXED */
        .modal-overlay {
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }
        
        .modal-enter {
            animation: modalEnter 0.3s ease-out;
        }
        
        @keyframes modalEnter {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        /* Table row hover effects */
        .student-row:hover {
            background: linear-gradient(90deg, rgba(79, 70, 229, 0.05) 0%, rgba(79, 70, 229, 0.02) 100%);
        }
        .dark .student-row:hover {
            background: linear-gradient(90deg, rgba(79, 70, 229, 0.1) 0%, rgba(79, 70, 229, 0.05) 100%);
        }

        /* Custom pagination styling */
        .custom-pagination .pagination {
            display: flex;
            list-style: none;
            gap: 0.5rem;
            margin: 0;
            padding: 0;
        }
        
        .custom-pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            color: white;
            border: none;
        }
        
        .custom-pagination .page-link {
            padding: 0.5rem 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            color: #4b5563;
            transition: all 0.2s;
        }
        
        .custom-pagination .page-link:hover {
            background-color: #f3f4f6;
            border-color: #d1d5db;
        }
        
        .dark .custom-pagination .page-link {
            border-color: #4b5563;
            color: #d1d5db;
            background-color: #374151;
        }
        
        .dark .custom-pagination .page-link:hover {
            background-color: #4b5563;
            border-color: #6b7280;
        }

        /* Level badges */
        .level-badge-o {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
        }
        
        .level-badge-a {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            color: white;
        }

        /* Subject badges */
        .subject-badge-core {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        
        .subject-badge-elective {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        /* Tab styles */
        .tab-button {
            position: relative;
            transition: all 0.3s ease;
        }
        
        .tab-button.active {
            color: #4f46e5;
        }
        
        .tab-button.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #4f46e5 0%, #6366f1 100%);
            border-radius: 3px 3px 0 0;
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
                            <h2 class="text-xl font-semibold text-dark dark:text-white">Students Management</h2>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Total Students: <span class="font-medium text-primary">{{ $students->total() }}</span></p>
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
                                <p class="text-xs text-gray-500 dark:text-gray-400">Active Students</p>
                                <p class="text-sm font-medium text-dark dark:text-white">{{ $activeStudentsCount ?? 0 }}</p>
                            </div>
                            <div class="bg-success/10 dark:bg-success/20 px-4 py-2 rounded-lg">
                                <p class="text-xs text-gray-500 dark:text-gray-400">New This Month</p>
                                <p class="text-sm font-medium text-dark dark:text-white">{{ $newStudentsCount ?? 0 }}</p>
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
                <!-- Alerts -->
                @if (session('success'))
                <div class="mb-6 rounded-xl border-l-4 border-green-500 bg-green-50 dark:bg-green-900/30 px-6 py-4 text-green-700 dark:text-green-300 flex items-center">
                    <i class="fas fa-check-circle mr-3"></i>
                    <div>
                        <p class="font-medium">{{ session('success') }}</p>
                        <p class="text-sm mt-1 opacity-90">Changes saved successfully</p>
                    </div>
                </div>
                @endif

                @if (session('error'))
                <div class="mb-6 rounded-xl border-l-4 border-red-500 bg-red-50 dark:bg-red-900/30 px-6 py-4 text-red-700 dark:text-red-300 flex items-center">
                    <i class="fas fa-exclamation-circle mr-3"></i>
                    <div>
                        <p class="font-medium">{{ session('error') }}</p>
                        <p class="text-sm mt-1 opacity-90">Please check your input</p>
                    </div>
                </div>
                @endif
                
                <!-- Quick Actions & Stats -->
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Add New Student Card -->
                    <div class="bg-gradient-to-br from-primary to-secondary rounded-xl p-6 text-white dashboard-card">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-lg font-semibold">Add New Student</h3>
                                <p class="text-sm opacity-90 mt-1">Enroll a new student to the system</p>
                            </div>
                            <i class="fas fa-user-graduate text-2xl opacity-80"></i>
                        </div>
                        <a id="addStudentBtn" href="{{ route('students.register') }}" 
                           class="w-full bg-white/20 hover:bg-white/30 backdrop-blur-sm py-3 rounded-lg flex items-center justify-center space-x-2 transition-all hover:scale-[1.02]">
                            <i class="fas fa-plus"></i>
                            <span>New Student Registration</span>
                        </a>
                    </div>
                    
                    <!-- Stats Cards -->
                    <div class="stat-card bg-white dark:bg-gray-800 rounded-xl p-6 dashboard-card transition-all">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">O-Level Students</p>
                                <h3 class="text-3xl font-bold text-dark dark:text-white mt-2">{{ $oLevelCount ?? 0 }}</h3>
                                <p class="text-info text-xs mt-1 flex items-center">
                                    <i class="fas fa-user-graduate mr-1"></i>
                                    Forms 1-4
                                </p>
                            </div>
                            <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-xl">
                                <i class="fas fa-school text-info text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card bg-white dark:bg-gray-800 rounded-xl p-6 dashboard-card transition-all">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">A-Level Students</p>
                                <h3 class="text-3xl font-bold text-dark dark:text-white mt-2">{{ $aLevelCount ?? 0 }}</h3>
                                <p class="text-secondary text-xs mt-1 flex items-center">
                                    <i class="fas fa-university mr-1"></i>
                                    Forms 5-6
                                </p>
                            </div>
                            <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-xl">
                                <i class="fas fa-university text-secondary text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card bg-white dark:bg-gray-800 rounded-xl p-6 dashboard-card transition-all">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">Pending Actions</p>
                                <h3 class="text-3xl font-bold text-dark dark:text-white mt-2">{{ $pendingActionsCount ?? 0 }}</h3>
                                <p class="text-warning text-xs mt-1 flex items-center">
                                    <i class="fas fa-clock mr-1"></i>
                                    Admissions & Transfers
                                </p>
                            </div>
                            <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-xl">
                                <i class="fas fa-tasks text-warning text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Filters & Search Bar -->
                <div class="bg-white dark:bg-gray-800 rounded-xl dashboard-card p-6 mb-6">
                    <form method="GET" action="{{ route('showStudents') }}" class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex-1">
                            <div class="relative">
                                <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search students by name or registration number..." 
                                       class="w-full pl-12 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-transparent focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-dark dark:text-white">
                            </div>
                        </div>
                        
                        <div class="flex flex-wrap gap-3">
                            <select name="current_form" class="px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-transparent focus:outline-none focus:ring-2 focus:ring-primary text-dark dark:text-white">
                                <option value="">All Forms</option>
                                @for($i = 1; $i <= 6; $i++)
                                    <option value="{{ $i }}" {{ request('current_form') == $i ? 'selected' : '' }}>Form {{ $i }}</option>
                                @endfor
                            </select>
                            
                            <select name="stream" class="px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-transparent focus:outline-none focus:ring-2 focus:ring-primary text-dark dark:text-white">
                                <option value="">All Streams</option>
                                <option value="Science" {{ request('stream') === 'Science' ? 'selected' : '' }}>Science</option>
                                <option value="Commerce" {{ request('stream') === 'Commerce' ? 'selected' : '' }}>Commerce</option>
                                <option value="Arts" {{ request('stream') === 'Arts' ? 'selected' : '' }}>Arts</option>
                            </select>

                            <select name="gender" class="px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-transparent focus:outline-none focus:ring-2 focus:ring-primary text-dark dark:text-white">
                                <option value="">All Genders</option>
                                <option value="Male" {{ request('gender') === 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ request('gender') === 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                            
                            <button type="submit" class="px-4 py-2.5 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors flex items-center">
                                <i class="fas fa-filter mr-2"></i>Filter
                            </button>
                            <a href="{{ route('showStudents') }}" class="px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center">
                                <i class="fas fa-times mr-2"></i>Clear
                            </a>
                        </div>
                    </form>
                </div>
                
                <!-- Students Table -->
                <div class="bg-white dark:bg-gray-800 rounded-xl dashboard-card overflow-hidden">
                    <div class="px-6 py-4 border-b dark:border-gray-700 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-dark dark:text-white">Student Records</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage all student records in the system</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                @if ($students->count() > 0)
                                    Showing {{ $students->firstItem() }}-{{ $students->lastItem() }} of {{ $students->total() }} students
                                @else
                                    Showing 0 of 0 students
                                @endif
                            </span>
                            <button class="px-3 py-1.5 text-xs bg-primary/10 text-primary rounded-lg hover:bg-primary/20 transition-colors">
                                <i class="fas fa-download mr-1"></i>Export
                            </button>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="text-left text-gray-500 dark:text-gray-400 text-sm border-b dark:border-gray-700">
                                    <th class="py-4 px-6 font-medium">
                                        <div class="flex items-center">
                                            <input type="checkbox" class="rounded border-gray-300 dark:border-gray-600">
                                            <span class="ml-3">Student Name</span>
                                        </div>
                                    </th>
                                    <th class="py-4 px-6 font-medium">Registration No.</th>
                                    <th class="py-4 px-6 font-medium">Class / Room</th>
                                    <th class="py-4 px-6 font-medium">Level</th>
                                    <th class="py-4 px-6 font-medium text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($students as $student)
                                    <tr class="student-row border-b dark:border-gray-700 transition-colors">
                                        <td class="py-4 px-6">
                                            <div class="flex items-center">
                                                <input type="checkbox" class="rounded border-gray-300 dark:border-gray-600">
                                                <div class="ml-4 flex items-center">
                                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary/20 to-secondary/20 flex items-center justify-center text-primary font-bold mr-3">
                                                        {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <p class="font-medium text-dark dark:text-white">{{ $student->first_name }} {{ $student->last_name }}</p>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center">
                                                            <i class="fas fa-{{ $student->gender == 'Male' ? 'mars' : 'venus' }} mr-1"></i>
                                                            {{ $student->gender }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6">
                                            <span class="font-mono text-sm px-3 py-1.5 bg-gray-100 dark:bg-gray-900 rounded-lg text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700">
                                                {{ $student->registration_number }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="text-sm">
                                                <span class="font-semibold text-primary dark:text-indigo-400">
                                                    {{ $student->schoolClass->name }}
                                                </span>
                                                <p class="text-xs text-gray-400 flex items-center mt-1">
                                                    <i class="fas fa-door-open mr-1"></i>
                                                    {{ $student->schoolClass->stream ?? 'No Stream' }}
                                                </p>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6">
                                            <span class="px-3 py-1.5 rounded-full text-xs font-medium {{ $student->schoolClass->level === 'A-Level' ? 'level-badge-a' : 'level-badge-o' }}">
                                                <i class="fas {{ $student->schoolClass->level === 'A-Level' ? 'fa-university' : 'fa-school' }} mr-1.5"></i>
                                                {{ $student->schoolClass->level }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-6 text-right">
                                            <div class="flex items-center justify-end space-x-2">
                                                <button type="button" 
                                                        onclick="openStudentModal({{ $student->id }}, '{{ $student->first_name }} {{ $student->last_name }}', '{{ $student->registration_number }}', '{{ $student->schoolClass->name }}', '{{ $student->schoolClass->level }}', '{{ $student->gender }}')" 
                                                        class="px-3 py-2 text-sm bg-info/10 text-info hover:bg-info/20 rounded-lg transition-colors font-medium flex items-center"
                                                        title="View Details">
                                                    <i class="fas fa-eye mr-1.5"></i> Details
                                                </button>
                                                <a href="{{ route('students.edit', $student->id) }}" 
                                                   class="px-3 py-2 text-sm bg-primary/10 text-primary hover:bg-primary/20 rounded-lg transition-colors flex items-center"
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button onclick="confirmDelete({{ $student->id }})"
                                                        class="px-3 py-2 text-sm bg-danger/10 text-danger hover:bg-danger/20 rounded-lg transition-colors flex items-center"
                                                        title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-20 text-center">
                                            <div class="flex flex-col items-center">
                                                <i class="fas fa-user-slash text-gray-200 dark:text-gray-600 text-5xl mb-4"></i>
                                                <p class="text-gray-500 dark:text-gray-400 font-medium">No student records found</p>
                                                <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Try adjusting your filters or add new students</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($students->hasPages())
                    <div class="px-6 py-4 border-t dark:border-gray-700 flex items-center justify-between bg-gray-50 dark:bg-gray-800/50">
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Page {{ $students->currentPage() }} of {{ $students->lastPage() }}
                        </div>
                        <div class="custom-pagination">
                            {{ $students->links() }}
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Quick Stats Summary -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                    <div class="bg-white dark:bg-gray-800 rounded-xl dashboard-card p-6">
                        <h4 class="font-semibold text-dark dark:text-white mb-4 flex items-center">
                            <i class="fas fa-chart-pie text-primary mr-2"></i>
                            Level Distribution
                        </h4>
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-600 dark:text-gray-400">O-Level Students</span>
                                    <span class="font-medium text-dark dark:text-white">{{ $oLevelCount ?? 0 }} ({{ $oLevelPercent ?? 0 }}%)</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div class="bg-info rounded-full h-2" style="width: {{ $oLevelPercent ?? 0 }}%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-600 dark:text-gray-400">A-Level Students</span>
                                    <span class="font-medium text-dark dark:text-white">{{ $aLevelCount ?? 0 }} ({{ $aLevelPercent ?? 0 }}%)</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div class="bg-secondary rounded-full h-2" style="width: {{ $aLevelPercent ?? 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 rounded-xl dashboard-card p-6">
                        <h4 class="font-semibold text-dark dark:text-white mb-4 flex items-center">
                            <i class="fas fa-venus-mars text-warning mr-2"></i>
                            Gender Distribution
                        </h4>
                        <div class="flex items-center justify-center h-40">
                            <div class="relative w-32 h-32">
                                <!-- Pie Chart Visualization -->
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-dark dark:text-white">{{ $students->total() }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">Total</div>
                                    </div>
                                </div>
                                <svg class="w-full h-full" viewBox="0 0 100 100">
                                    <!-- Male Students -->
                                    <circle cx="50" cy="50" r="40" fill="transparent" 
                                            stroke="#3b82f6" stroke-width="20" stroke-dasharray="251.2" stroke-dashoffset="{{ 251.2 - ($malePercent ?? 50) * 2.512 }}"
                                            transform="rotate(-90 50 50)" />
                                    <!-- Female Students -->
                                    <circle cx="50" cy="50" r="40" fill="transparent" 
                                            stroke="#ec4899" stroke-width="20" stroke-dasharray="251.2" stroke-dashoffset="{{ 251.2 - ($femalePercent ?? 50) * 2.512 - ($malePercent ?? 50) * 2.512 }}"
                                            transform="rotate(-90 50 50)" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex justify-center space-x-6 mt-4">
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full bg-blue-500 mr-2"></div>
                                <span class="text-sm">Male ({{ $malePercent ?? 50 }}%)</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full bg-pink-500 mr-2"></div>
                                <span class="text-sm">Female ({{ $femalePercent ?? 50 }}%)</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 rounded-xl dashboard-card p-6">
                        <h4 class="font-semibold text-dark dark:text-white mb-4 flex items-center">
                            <i class="fas fa-history text-success mr-2"></i>
                            Recent Activities
                        </h4>
                        <div class="space-y-4">
                            @forelse($recentActivities ?? [] as $activity)
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full {{ $activity['bg'] }} flex items-center justify-center {{ $activity['color'] }} mr-3">
                                    <i class="fas {{ $activity['icon'] }} text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-dark dark:text-white">{{ $activity['title'] }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $activity['description'] }}</p>
                                </div>
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $activity['time'] }}</span>
                            </div>
                            @empty
                            <div class="text-center py-4">
                                <p class="text-gray-500 dark:text-gray-400 text-sm">No recent activities</p>
                            </div>
                            @endforelse
                            <a href="#" class="w-full mt-2 text-center text-primary text-sm hover:underline flex items-center justify-center">
                                View All Activities
                                <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </main>
            
            <!-- Footer -->
            <footer class="bg-white dark:bg-gray-800 border-t dark:border-gray-700 py-4 px-6">
                <div class="flex flex-col md:flex-row items-center justify-between">
                    <p class="text-gray-500 dark:text-gray-400 text-sm">© 2023 EduCare Pro School Management System. Student Management Module</p>
                    <div class="flex space-x-4 mt-2 md:mt-0">
                        <button class="text-gray-500 dark:text-gray-400 hover:text-primary text-sm flex items-center">
                            <i class="fas fa-question-circle mr-1"></i>
                            Help & Support
                        </button>
                        <button class="text-gray-500 dark:text-gray-400 hover:text-primary text-sm flex items-center">
                            <i class="fas fa-cog mr-1"></i>
                            Settings
                        </button>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 z-50 p-4">
        <div class="modal-overlay absolute inset-0" onclick="closeDeleteModal()"></div>
        <div class="relative flex items-center justify-center min-h-screen">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-md w-full modal-enter relative z-10">
                <div class="p-6 text-center">
                    <div class="w-16 h-16 mx-auto rounded-full bg-danger/20 flex items-center justify-center mb-4">
                        <i class="fas fa-exclamation-triangle text-danger text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-dark dark:text-white mb-2">Confirm Deletion</h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-6">Are you sure you want to delete this student? This action cannot be undone.</p>
                    <div class="flex justify-center space-x-3">
                        <button type="button" onclick="closeDeleteModal()" 
                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            Cancel
                        </button>
                        <form id="deleteForm" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="px-4 py-2 bg-danger text-white rounded-lg hover:bg-danger-dark transition-colors">
                                Delete Student
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Details Modal - REDESIGNED -->
    <div id="studentModal" class="hidden fixed inset-0 z-50 p-4">
        <div class="modal-overlay absolute inset-0" onclick="closeStudentModal()"></div>
        <div class="relative flex items-center justify-center min-h-screen">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-5xl w-full max-h-[90vh] overflow-hidden modal-enter relative z-10">
                
                <!-- Header -->
                <div class="sticky top-0 px-6 py-5 border-b dark:border-gray-700 bg-gradient-to-r from-primary/10 to-secondary/10 dark:from-primary/20 dark:to-secondary/20 flex items-center justify-between z-10">
                    <div class="flex items-center space-x-4">
                        <div class="w-14 h-14 rounded-full bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-white font-bold text-xl shadow-lg" id="studentAvatarLarge">
                            
                        </div>
                        <div>
                            <h3 id="studentModalTitle" class="text-2xl font-bold text-dark dark:text-white"></h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center mt-1">
                                <i class="fas fa-id-card mr-2"></i>
                                <span id="studentModalRegNumber" class="font-mono"></span>
                            </p>
                        </div>
                    </div>
                    <button type="button" onclick="closeStudentModal()" class="p-2 rounded-lg text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-white/50 dark:hover:bg-gray-700 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Tabs Navigation -->
                <div class="sticky top-[88px] bg-white dark:bg-gray-800 border-b dark:border-gray-700 px-6 z-10">
                    <div class="flex space-x-8">
                        <button onclick="switchTab('overview')" class="tab-button active py-4 px-2 text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-primary transition-colors" data-tab="overview">
                            <i class="fas fa-info-circle mr-2"></i>Overview
                        </button>
                        <button onclick="switchTab('subjects')" class="tab-button py-4 px-2 text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-primary transition-colors" data-tab="subjects">
                            <i class="fas fa-book mr-2"></i>Subjects & Courses
                        </button>
                        <button onclick="switchTab('performance')" class="tab-button py-4 px-2 text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-primary transition-colors" data-tab="performance">
                            <i class="fas fa-chart-line mr-2"></i>Performance
                        </button>
                    </div>
                </div>

                <!-- Tab Content -->
                <div class="overflow-y-auto" style="max-height: calc(90vh - 176px);">
                    
                    <!-- Overview Tab -->
                    <div id="overviewTab" class="tab-content p-6">
                        <input type="hidden" id="studentModalStudentId">

                        <!-- Info Cards Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl p-5 border border-blue-200 dark:border-blue-800">
                                <div class="flex items-center justify-between mb-3">
                                    <p class="text-xs font-semibold text-blue-600 dark:text-blue-400 uppercase tracking-wide">Class & Stream</p>
                                    <i class="fas fa-chalkboard-teacher text-blue-500 text-lg"></i>
                                </div>
                                <p id="studentClass" class="text-xl font-bold text-dark dark:text-white"></p>
                                <div id="studentLevel" class="mt-2"></div>
                            </div>

                            <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-xl p-5 border border-purple-200 dark:border-purple-800">
                                <div class="flex items-center justify-between mb-3">
                                    <p class="text-xs font-semibold text-purple-600 dark:text-purple-400 uppercase tracking-wide">Gender</p>
                                    <i class="fas fa-venus-mars text-purple-500 text-lg"></i>
                                </div>
                                <p id="studentGender" class="text-xl font-bold text-dark dark:text-white"></p>
                            </div>

                            <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl p-5 border border-green-200 dark:border-green-800">
                                <div class="flex items-center justify-between mb-3">
                                    <p class="text-xs font-semibold text-green-600 dark:text-green-400 uppercase tracking-wide">Status</p>
                                    <i class="fas fa-check-circle text-green-500 text-lg"></i>
                                </div>
                                <p class="text-xl font-bold text-dark dark:text-white">Active</p>
                            </div>
                        </div>

                        <!-- Personal Information -->
                        <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-6 mb-6">
                            <h4 class="text-lg font-semibold text-dark dark:text-white mb-4 flex items-center">
                                <i class="fas fa-user-circle text-primary mr-2"></i>
                                Personal Information
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">First Name</p>
                                    <p id="studentFirstName" class="text-base font-medium text-dark dark:text-white"></p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Last Name</p>
                                    <p id="studentLastName" class="text-base font-medium text-dark dark:text-white"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Stats -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Total Subjects</p>
                                        <p id="totalSubjectsCount" class="text-2xl font-bold text-dark dark:text-white mt-1">0</p>
                                    </div>
                                    <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/30 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-book text-indigo-600 dark:text-indigo-400 text-xl"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Core Subjects</p>
                                        <p id="coreSubjectsCount" class="text-2xl font-bold text-dark dark:text-white mt-1">0</p>
                                    </div>
                                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Electives</p>
                                        <p id="electiveSubjectsCount" class="text-2xl font-bold text-dark dark:text-white mt-1">0</p>
                                    </div>
                                    <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900/30 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-star text-amber-600 dark:text-amber-400 text-xl"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Subjects Tab -->
                    <div id="subjectsTab" class="tab-content hidden p-6">
                        <!-- Action Buttons -->
                        <div class="flex flex-wrap gap-3 mb-6">
                            <form id="coreSubjectsForm" method="POST" class="inline">
                                @csrf
                                <input type="hidden" id="coreStudentId" name="student_id">
                                <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-green-500 to-green-600 text-white hover:from-green-600 hover:to-green-700 rounded-lg transition-all font-medium flex items-center gap-2 shadow-md hover:shadow-lg">
                                    <i class="fas fa-sync-alt"></i> Sync All Core Subjects
                                </button>
                            </form>
                            <button type="button" onclick="refreshSubjects()" class="px-5 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors font-medium flex items-center gap-2">
                                <i class="fas fa-refresh"></i> Refresh
                            </button>
                        </div>

                        <!-- Subjects Grid -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Core Subjects -->
                            <div class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/10 dark:to-emerald-900/10 rounded-xl p-6 border-2 border-green-200 dark:border-green-800">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="text-lg font-bold text-dark dark:text-white flex items-center">
                                        <i class="fas fa-book-open text-green-600 dark:text-green-400 mr-2"></i>
                                        Core Subjects
                                    </h4>
                                    <span id="coreCountBadge" class="px-3 py-1 bg-green-600 text-white text-xs font-bold rounded-full">0</span>
                                </div>
                                <div id="coreSubjectsList" class="space-y-2">
                                    <div class="flex items-center justify-center py-8">
                                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-green-600"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Elective Subjects -->
                            <div class="bg-gradient-to-br from-amber-50 to-orange-50 dark:from-amber-900/10 dark:to-orange-900/10 rounded-xl p-6 border-2 border-amber-200 dark:border-amber-800">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="text-lg font-bold text-dark dark:text-white flex items-center">
                                        <i class="fas fa-star text-amber-600 dark:text-amber-400 mr-2"></i>
                                        Elective Subjects
                                    </h4>
                                    <span id="electiveCountBadge" class="px-3 py-1 bg-amber-600 text-white text-xs font-bold rounded-full">0</span>
                                </div>
                                <div id="electiveSubjectsList" class="space-y-2">
                                    <div class="flex items-center justify-center py-8">
                                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-amber-600"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Available Electives -->
                        <div class="mt-6 bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                            <h4 class="text-lg font-bold text-dark dark:text-white mb-4 flex items-center">
                                <i class="fas fa-plus-circle text-primary mr-2"></i>
                                Available Electives to Add
                            </h4>
                            <div id="availableElectivesList" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                <div class="flex items-center justify-center py-8 col-span-full">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Performance Tab -->
                    <div id="performanceTab" class="tab-content hidden p-6">
                        <div class="text-center py-16">
                            <i class="fas fa-chart-line text-gray-300 dark:text-gray-600 text-6xl mb-4"></i>
                            <p class="text-gray-500 dark:text-gray-400 text-lg font-medium">Performance analytics coming soon</p>
                            <p class="text-gray-400 dark:text-gray-500 text-sm mt-2">Track student grades, attendance, and progress here</p>
                        </div>
                    </div>

                </div>

                <!-- Footer -->
                <div class="sticky bottom-0 px-6 py-4 border-t dark:border-gray-700 bg-gray-50 dark:bg-gray-800/80 backdrop-blur-sm flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <a href="#" id="editStudentLink" class="px-4 py-2 bg-primary/10 text-primary hover:bg-primary/20 rounded-lg transition-colors font-medium flex items-center gap-2">
                            <i class="fas fa-edit"></i> Edit Student
                        </a>
                        <button type="button" onclick="printStudentDetails()" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors font-medium flex items-center gap-2">
                            <i class="fas fa-print"></i> Print
                        </button>
                    </div>
                    <button type="button" onclick="closeStudentModal()" class="px-5 py-2 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors font-medium">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

<!-- REPLACE THE SCRIPT SECTION IN YOUR BLADE FILE WITH THIS -->

<script>
    // Global variables
    let currentStudentId = null;
    let currentTab = 'overview';

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize dark mode
        const darkModeToggle = document.getElementById('darkModeToggle');
        const darkModeIcon = document.getElementById('darkModeIcon');
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
        
        // Sidebar collapse
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
        });
        
        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            if (e.ctrlKey && e.key === 'n') {
                e.preventDefault();
                const addStudentBtn = document.getElementById('addStudentBtn');
                if (addStudentBtn) {
                    window.location.href = addStudentBtn.href;
                }
            }
            if (e.key === 'Escape') {
                closeStudentModal();
                closeDeleteModal();
            }
        });

        // Core Subject Form Handler - FIXED
        document.getElementById('coreSubjectsForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const studentId = document.getElementById('coreStudentId').value;
            
            const form = new FormData();
            form.append('student_id', studentId);
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalHTML = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Syncing...';
            submitBtn.disabled = true;
            
            fetch('{{ route("subjects.syncCore") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
                    'Accept': 'application/json' // Important: Tell server we expect JSON
                },
                body: form
            })
            .then(response => {
                // FIXED: Check if response is ok first
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.message || 'Failed to sync core subjects');
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Sync response:', data); // Debug log
                
                // FIXED: Check for success in response
                if (data.success) {
                    loadAllSubjects(studentId);
                    loadSubjectCounts(studentId);
                    showNotification(data.message || 'Core subjects synced successfully!', 'success');
                } else {
                    throw new Error(data.message || 'Sync failed');
                }
            })
            .catch(error => {
                console.error('Sync error:', error);
                showNotification(error.message || 'Error syncing core subjects', 'error');
            })
            .finally(() => {
                // Reset button state
                submitBtn.innerHTML = originalHTML;
                submitBtn.disabled = false;
            });
        });
    });

    // Tab Switching Function
    function switchTab(tabName) {
        currentTab = tabName;
        
        // Update tab buttons
        document.querySelectorAll('.tab-button').forEach(btn => {
            btn.classList.remove('active');
        });
        document.querySelector(`[data-tab="${tabName}"]`).classList.add('active');
        
        // Update tab content
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });
        document.getElementById(`${tabName}Tab`).classList.remove('hidden');
        
        // Load data if needed
        if (tabName === 'subjects' && currentStudentId) {
            loadAllSubjects(currentStudentId);
        }
    }

    // Open Student Modal
    function openStudentModal(studentId, fullName, regNumber, className, level, gender) {
        currentStudentId = studentId;
        
        // Reset to overview tab
        switchTab('overview');
        
        // Set basic info
        document.getElementById('studentModalStudentId').value = studentId;
        document.getElementById('coreStudentId').value = studentId;
        document.getElementById('studentModalTitle').textContent = fullName;
        document.getElementById('studentModalRegNumber').textContent = regNumber;
        
        // Set avatar
        const initials = fullName.split(' ').map(n => n[0]).join('');
        document.getElementById('studentAvatarLarge').textContent = initials;
        
        // Set personal info
        const names = fullName.split(' ');
        document.getElementById('studentFirstName').textContent = names[0];
        document.getElementById('studentLastName').textContent = names.slice(1).join(' ');
        document.getElementById('studentGender').textContent = gender;
        document.getElementById('studentClass').textContent = className;
        
        // Set level badge
        const levelEl = document.getElementById('studentLevel');
        const isALevel = level.includes('A-Level');
        const levelClass = isALevel ? 'level-badge-a' : 'level-badge-o';
        const icon = isALevel ? 'fa-university' : 'fa-school';
        levelEl.innerHTML = `<span class="px-3 py-1.5 rounded-full text-xs font-medium ${levelClass}">
            <i class="fas ${icon} mr-1.5"></i>${level}
        </span>`;
        
        // Set edit link
        document.getElementById('editStudentLink').href = `/students/${studentId}/edit`;
        
        // Load subjects for overview counts
        loadSubjectCounts(studentId);
        
        // Show modal
        document.getElementById('studentModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    // Close Student Modal
    function closeStudentModal() {
        document.getElementById('studentModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
        currentStudentId = null;
    }

    // Load Subject Counts for Overview - FIXED
    function loadSubjectCounts(studentId) {
        fetch(`/students/${studentId}/all-subjects`)
            .then(response => {
                if (!response.ok) throw new Error('Failed to fetch subjects');
                return response.json();
            })
            .then(data => {
                console.log('All subjects loaded:', data); // Debug log
                
                const total = data.length;
                const core = data.filter(s => s.category === 'core').length;
                const elective = data.filter(s => s.category === 'elective').length;
                
                document.getElementById('totalSubjectsCount').textContent = total;
                document.getElementById('coreSubjectsCount').textContent = core;
                document.getElementById('electiveSubjectsCount').textContent = elective;
            })
            .catch(error => {
                console.error('Error loading subject counts:', error);
                // Set to 0 on error instead of showing error
                document.getElementById('totalSubjectsCount').textContent = '0';
                document.getElementById('coreSubjectsCount').textContent = '0';
                document.getElementById('electiveSubjectsCount').textContent = '0';
            });
    }

    // Load All Subjects for Subjects Tab - FIXED
    function loadAllSubjects(studentId) {
        // Show loading state
        document.getElementById('coreSubjectsList').innerHTML = '<div class="flex items-center justify-center py-8"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-green-600"></div></div>';
        document.getElementById('electiveSubjectsList').innerHTML = '<div class="flex items-center justify-center py-8"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-amber-600"></div></div>';
        document.getElementById('availableElectivesList').innerHTML = '<div class="flex items-center justify-center py-8 col-span-full"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div></div>';
        
        // Load all assigned subjects
        fetch(`/students/${studentId}/all-subjects`)
            .then(response => {
                if (!response.ok) throw new Error('Failed to fetch subjects');
                return response.json();
            })
            .then(data => {
                console.log('Subjects loaded:', data); // Debug log
                
                const coreSubjects = data.filter(s => s.category === 'core');
                const electiveSubjects = data.filter(s => s.category === 'elective');
                
                // Update counts
                document.getElementById('coreCountBadge').textContent = coreSubjects.length;
                document.getElementById('electiveCountBadge').textContent = electiveSubjects.length;
                
                // Render core subjects
                renderCoreSubjects(coreSubjects);
                
                // Render elective subjects
                renderElectiveSubjects(electiveSubjects, studentId);
            })
            .catch(error => {
                console.error('Error loading subjects:', error);
                document.getElementById('coreSubjectsList').innerHTML = '<p class="text-red-500 text-sm text-center py-4">Error loading subjects</p>';
                document.getElementById('electiveSubjectsList').innerHTML = '<p class="text-red-500 text-sm text-center py-4">Error loading subjects</p>';
            });
        
        // Load available electives
        fetch(`/students/${studentId}/available-electives`)
            .then(response => {
                if (!response.ok) throw new Error('Failed to fetch available electives');
                return response.json();
            })
            .then(data => {
                console.log('Available electives loaded:', data); // Debug log
                renderAvailableElectives(data, studentId);
            })
            .catch(error => {
                console.error('Error loading available electives:', error);
                document.getElementById('availableElectivesList').innerHTML = '<p class="text-red-500 text-sm text-center py-4 col-span-full">Error loading available electives</p>';
            });
    }

    // Render Core Subjects
    function renderCoreSubjects(coreSubjects) {
        const coreList = document.getElementById('coreSubjectsList');
        coreList.innerHTML = '';
        
        if (coreSubjects.length === 0) {
            coreList.innerHTML = '<p class="text-gray-500 dark:text-gray-400 text-sm text-center py-4 italic">No core subjects assigned</p>';
        } else {
            coreSubjects.forEach(subject => {
                const item = document.createElement('div');
                item.className = 'flex items-center justify-between p-3 bg-white dark:bg-gray-800 rounded-lg border border-green-200 dark:border-green-800 hover:shadow-md transition-all';
                item.innerHTML = `
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center mr-3">
                            <i class="fas fa-book text-green-600 dark:text-green-400 text-sm"></i>
                        </div>
                        <span class="font-medium text-sm text-dark dark:text-white">${subject.name}</span>
                    </div>
                    <span class="px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 text-xs font-medium rounded">Core</span>
                `;
                coreList.appendChild(item);
            });
        }
    }

    // Render Elective Subjects
    function renderElectiveSubjects(electiveSubjects, studentId) {
        const electiveList = document.getElementById('electiveSubjectsList');
        electiveList.innerHTML = '';
        
        if (electiveSubjects.length === 0) {
            electiveList.innerHTML = '<p class="text-gray-500 dark:text-gray-400 text-sm text-center py-4 italic">No elective subjects assigned</p>';
        } else {
            electiveSubjects.forEach(subject => {
                const item = document.createElement('div');
                item.className = 'flex items-center justify-between p-3 bg-white dark:bg-gray-800 rounded-lg border border-amber-200 dark:border-amber-800 hover:shadow-md transition-all';
                item.innerHTML = `
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center mr-3">
                            <i class="fas fa-star text-amber-600 dark:text-amber-400 text-sm"></i>
                        </div>
                        <span class="font-medium text-sm text-dark dark:text-white">${subject.name}</span>
                    </div>
                    <button type="button" onclick="detachElective(${studentId}, ${subject.id})" 
                            class="px-3 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 rounded-lg hover:bg-red-200 dark:hover:bg-red-900/50 transition-colors text-xs font-medium flex items-center gap-1">
                        <i class="fas fa-times"></i> Remove
                    </button>
                `;
                electiveList.appendChild(item);
            });
        }
    }

    // Render Available Electives
    function renderAvailableElectives(data, studentId) {
        const availableList = document.getElementById('availableElectivesList');
        availableList.innerHTML = '';
        
        if (data.length === 0) {
            availableList.innerHTML = '<p class="text-gray-500 dark:text-gray-400 text-sm text-center py-4 col-span-full italic">All available electives have been assigned</p>';
        } else {
            data.forEach(subject => {
                const item = document.createElement('div');
                item.className = 'flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-primary dark:hover:border-primary hover:shadow-md transition-all';
                item.innerHTML = `
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center mr-3">
                            <i class="fas fa-plus text-primary text-sm"></i>
                        </div>
                        <span class="font-medium text-sm text-dark dark:text-white">${subject.name}</span>
                    </div>
                    <button type="button" onclick="attachElective(${studentId}, ${subject.id})" 
                            class="px-3 py-1.5 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors text-xs font-medium flex items-center gap-1">
                        <i class="fas fa-plus"></i> Add
                    </button>
                `;
                availableList.appendChild(item);
            });
        }
    }

    // Refresh Subjects
    function refreshSubjects() {
        if (currentStudentId) {
            loadAllSubjects(currentStudentId);
            loadSubjectCounts(currentStudentId);
            showNotification('Subjects refreshed', 'success');
        }
    }

    // Attach Elective - FIXED
    function attachElective(studentId, subjectId) {
        const form = new FormData();
        form.append('student_id', studentId);
        form.append('subject_id', subjectId);
        
        fetch('{{ route("subjects.attachElective") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: form
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || 'Failed to add elective');
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Attach response:', data);
            
            if (data.success) {
                loadAllSubjects(studentId);
                loadSubjectCounts(studentId);
                showNotification(data.message || 'Elective added successfully!', 'success');
            } else {
                throw new Error(data.message || 'Failed to add elective');
            }
        })
        .catch(error => {
            console.error('Attach error:', error);
            showNotification(error.message || 'Error adding elective', 'error');
        });
    }

    // Detach Elective - FIXED
    function detachElective(studentId, subjectId) {
        const form = new FormData();
        form.append('student_id', studentId);
        form.append('subject_id', subjectId);
        
        fetch('{{ route("subjects.detachElective") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: form
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || 'Failed to remove elective');
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Detach response:', data);
            
            if (data.success) {
                loadAllSubjects(studentId);
                loadSubjectCounts(studentId);
                showNotification(data.message || 'Elective removed successfully!', 'success');
            } else {
                throw new Error(data.message || 'Failed to remove elective');
            }
        })
        .catch(error => {
            console.error('Detach error:', error);
            showNotification(error.message || 'Error removing elective', 'error');
        });
    }

    // Delete Modal Functions
    function confirmDelete(studentId) {
        document.getElementById('deleteForm').action = `/students/${studentId}`;
        document.getElementById('deleteModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Print Student Details
    function printStudentDetails() {
        window.print();
    }

    // Notification Function - IMPROVED
    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `fixed top-6 right-6 px-6 py-4 rounded-xl text-white font-medium z-[60] shadow-2xl flex items-center space-x-3 transform transition-all ${type === 'success' ? 'bg-gradient-to-r from-green-500 to-green-600' : 'bg-gradient-to-r from-red-500 to-red-600'}`;
        notification.style.animation = 'slideInRight 0.3s ease-out';
        notification.innerHTML = `
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} text-xl"></i>
            <span>${message}</span>
        `;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = 'slideOutRight 0.3s ease-in';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    // Add animation keyframes
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideInRight {
            from {
                transform: translateX(400px);
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
                transform: translateX(400px);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);
</script>
</body>
</html>