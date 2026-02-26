<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduCare Pro | School Management Dashboard</title>
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
        
        /* Dark mode styles */
        .dark .sidebar-item:hover, .dark .sidebar-item.active {
            background: linear-gradient(90deg, rgba(79, 70, 229, 0.2) 0%, rgba(79, 70, 229, 0.1) 100%);
        }
        .dark .progress-bar {
            background-color: #374151;
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
                            <h2 class="text-xl font-semibold text-dark dark:text-white">School Dashboard</h2>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Today: <span id="current-date">April 15, 2023</span></p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-6">
                        <!-- Dark Mode Toggle -->
                        <button id="darkModeToggle" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i id="darkModeIcon" class="fas fa-moon text-gray-600 dark:text-yellow-400"></i>
                        </button>
                        
                        <div class="relative">
                            <i class="fas fa-bell text-gray-500 dark:text-gray-300 text-xl hover:text-primary dark:hover:text-primary cursor-pointer"></i>
                            <span class="absolute -top-1 -right-1 bg-danger text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">5</span>
                        </div>
                        
                        <div class="hidden md:flex items-center space-x-4">
                            <div class="bg-primary/10 dark:bg-primary/20 px-4 py-2 rounded-lg">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Academic Year</p>
                                <p class="text-sm font-medium text-dark dark:text-white">2023-2024</p>
                            </div>
                            <div class="bg-success/10 dark:bg-success/20 px-4 py-2 rounded-lg">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Term</p>
                                <p class="text-sm font-medium text-dark dark:text-white">Spring Term</p>
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
            
            <!-- Main Dashboard -->
            <main class="p-6">
                <!-- Stats Overview -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="stat-card bg-white dark:bg-gray-800 rounded-xl p-6 dashboard-card">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">Pending Approvals</p>
                                <h3 class="text-3xl font-bold text-dark dark:text-white mt-2">12</h3>
                                <p class="text-danger text-xs mt-1"><i class="fas fa-clock mr-1"></i> 5 high priority</p>
                            </div>
                            <div class="bg-red-50 dark:bg-red-900/20 p-4 rounded-xl">
                                <i class="fas fa-user-check text-danger text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card bg-white dark:bg-gray-800 rounded-xl p-6 dashboard-card">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">Students Needing Attention</p>
                                <h3 class="text-3xl font-bold text-dark dark:text-white mt-2">18</h3>
                                <p class="text-warning text-xs mt-1"><i class="fas fa-exclamation-circle mr-1"></i> 8 academic, 10 financial</p>
                            </div>
                            <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-xl">
                                <i class="fas fa-exclamation-triangle text-warning text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card bg-white dark:bg-gray-800 rounded-xl p-6 dashboard-card">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">Classes Needing Attention</p>
                                <h3 class="text-3xl font-bold text-dark dark:text-white mt-2">7</h3>
                                <p class="text-info text-xs mt-1"><i class="fas fa-chalkboard-teacher mr-1"></i> Low performance</p>
                            </div>
                            <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-xl">
                                <i class="fas fa-book text-info text-2xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card bg-white dark:bg-gray-800 rounded-xl p-6 dashboard-card">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">Subjects Needing Attention</p>
                                <h3 class="text-3xl font-bold text-dark dark:text-white mt-2">5</h3>
                                <p class="text-primary text-xs mt-1"><i class="fas fa-chart-line mr-1"></i> Below 65% average</p>
                            </div>
                            <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-xl">
                                <i class="fas fa-clipboard-list text-primary text-2xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Main Content Area -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    <!-- Left Column: Approval Requests -->
                    <div class="lg:col-span-2">
                        <!-- Pending Approval Requests -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl dashboard-card p-6 mb-6">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-lg font-semibold text-dark dark:text-white">Pending Approval Requests</h3>
                                <div class="flex space-x-2">
                                    <button class="text-primary text-sm font-medium px-3 py-1 bg-primary/10 dark:bg-primary/20 rounded-lg">View All</button>
                                    <button class="text-white text-sm font-medium px-3 py-1 bg-primary rounded-lg">+ Add New</button>
                                </div>
                            </div>
                            
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead>
                                        <tr class="text-left text-gray-500 dark:text-gray-400 text-sm border-b dark:border-gray-700">
                                            <th class="pb-3 font-medium">Request Type</th>
                                            <th class="pb-3 font-medium">Submitted By</th>
                                            <th class="pb-3 font-medium">Date</th>
                                            <th class="pb-3 font-medium">Priority</th>
                                            <th class="pb-3 font-medium">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                            <td class="py-4">
                                                <div class="flex items-center">
                                                    <div class="w-10 h-10 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center mr-3">
                                                        <i class="fas fa-user-plus text-danger"></i>
                                                    </div>
                                                    <div>
                                                        <p class="font-medium text-dark dark:text-white">New Student Admission</p>
                                                        <p class="text-gray-500 dark:text-gray-400 text-xs">Grade 11 - Science Stream</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-4 text-dark dark:text-white">Admissions Office</td>
                                            <td class="py-4 text-dark dark:text-white">Apr 14, 2023</td>
                                            <td class="py-4">
                                                <span class="bg-danger text-white text-xs px-3 py-1 rounded-full">High</span>
                                            </td>
                                            <td class="py-4">
                                                <div class="flex space-x-2">
                                                    <button class="text-success hover:text-success-dark">
                                                        <i class="fas fa-check-circle"></i>
                                                    </button>
                                                    <button class="text-danger hover:text-danger-dark">
                                                        <i class="fas fa-times-circle"></i>
                                                    </button>
                                                    <button class="text-info hover:text-info-dark">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        
                                        <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                            <td class="py-4">
                                                <div class="flex items-center">
                                                    <div class="w-10 h-10 rounded-lg bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center mr-3">
                                                        <i class="fas fa-file-invoice-dollar text-warning"></i>
                                                    </div>
                                                    <div>
                                                        <p class="font-medium text-dark dark:text-white">Fee Concession Request</p>
                                                        <p class="text-gray-500 dark:text-gray-400 text-xs">Student: Sarah Johnson</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-4 text-dark dark:text-white">Finance Dept</td>
                                            <td class="py-4 text-dark dark:text-white">Apr 13, 2023</td>
                                            <td class="py-4">
                                                <span class="bg-warning text-white text-xs px-3 py-1 rounded-full">Medium</span>
                                            </td>
                                            <td class="py-4">
                                                <div class="flex space-x-2">
                                                    <button class="text-success hover:text-success-dark">
                                                        <i class="fas fa-check-circle"></i>
                                                    </button>
                                                    <button class="text-danger hover:text-danger-dark">
                                                        <i class="fas fa-times-circle"></i>
                                                    </button>
                                                    <button class="text-info hover:text-info-dark">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        
                                        <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                            <td class="py-4">
                                                <div class="flex items-center">
                                                    <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center mr-3">
                                                        <i class="fas fa-book text-info"></i>
                                                    </div>
                                                    <div>
                                                        <p class="font-medium text-dark dark:text-white">New Subject Addition</p>
                                                        <p class="text-gray-500 dark:text-gray-400 text-xs">Computer Science - Grade 12</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-4 text-dark dark:text-white">Academic Head</td>
                                            <td class="py-4 text-dark dark:text-white">Apr 12, 2023</td>
                                            <td class="py-4">
                                                <span class="bg-info text-white text-xs px-3 py-1 rounded-full">Medium</span>
                                            </td>
                                            <td class="py-4">
                                                <div class="flex space-x-2">
                                                    <button class="text-success hover:text-success-dark">
                                                        <i class="fas fa-check-circle"></i>
                                                    </button>
                                                    <button class="text-danger hover:text-danger-dark">
                                                        <i class="fas fa-times-circle"></i>
                                                    </button>
                                                    <button class="text-info hover:text-info-dark">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                            <td class="py-4">
                                                <div class="flex items-center">
                                                    <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center mr-3">
                                                        <i class="fas fa-user-graduate text-success"></i>
                                                    </div>
                                                    <div>
                                                        <p class="font-medium text-dark dark:text-white">Leave of Absence</p>
                                                        <p class="text-gray-500 dark:text-gray-400 text-xs">Teacher: Mr. Robert Chen</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-4 text-dark dark:text-white">HR Department</td>
                                            <td class="py-4 text-dark dark:text-white">Apr 10, 2023</td>
                                            <td class="py-4">
                                                <span class="bg-success text-white text-xs px-3 py-1 rounded-full">Low</span>
                                            </td>
                                            <td class="py-4">
                                                <div class="flex space-x-2">
                                                    <button class="text-success hover:text-success-dark">
                                                        <i class="fas fa-check-circle"></i>
                                                    </button>
                                                    <button class="text-danger hover:text-danger-dark">
                                                        <i class="fas fa-times-circle"></i>
                                                    </button>
                                                    <button class="text-info hover:text-info-dark">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Students Needing Attention -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl dashboard-card p-6">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-lg font-semibold text-dark dark:text-white">Students Needing Attention</h3>
                                <div class="flex space-x-2">
                                    <button class="text-sm font-medium px-3 py-1 rounded-lg border border-primary text-primary hover:bg-primary hover:text-white">Academic</button>
                                    <button class="text-sm font-medium px-3 py-1 rounded-lg border border-danger text-danger hover:bg-danger hover:text-white">Financial</button>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Academic Attention -->
                                <div>
                                    <h4 class="font-medium text-dark dark:text-white mb-4 flex items-center">
                                        <i class="fas fa-graduation-cap text-warning mr-2"></i>
                                        Academic Issues
                                    </h4>
                                    <div class="space-y-4">
                                        <div class="p-4 border border-warning/30 rounded-lg bg-warning/5 dark:bg-warning/10">
                                            <div class="flex items-center justify-between mb-2">
                                                <div class="flex items-center">
                                                    <div class="w-8 h-8 rounded-full bg-warning/20 flex items-center justify-center text-warning mr-3">
                                                        <i class="fas fa-user text-sm"></i>
                                                    </div>
                                                    <p class="font-medium text-dark dark:text-white">Michael Rodriguez</p>
                                                </div>
                                                <span class="text-xs bg-warning text-white px-2 py-1 rounded">Grade 10</span>
                                            </div>
                                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">Math & Science grades below 50%</p>
                                            <div class="flex items-center justify-between">
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Last Test: 42% avg</p>
                                                <button class="text-xs text-primary hover:underline">View Details</button>
                                            </div>
                                        </div>
                                        
                                        <div class="p-4 border border-warning/30 rounded-lg bg-warning/5 dark:bg-warning/10">
                                            <div class="flex items-center justify-between mb-2">
                                                <div class="flex items-center">
                                                    <div class="w-8 h-8 rounded-full bg-warning/20 flex items-center justify-center text-warning mr-3">
                                                        <i class="fas fa-user text-sm"></i>
                                                    </div>
                                                    <p class="font-medium text-dark dark:text-white">Emma Wilson</p>
                                                </div>
                                                <span class="text-xs bg-warning text-white px-2 py-1 rounded">Grade 9</span>
                                            </div>
                                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">Attendance below 75% this month</p>
                                            <div class="flex items-center justify-between">
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Attendance: 68%</p>
                                                <button class="text-xs text-primary hover:underline">View Details</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Financial Attention -->
                                <div>
                                    <h4 class="font-medium text-dark dark:text-white mb-4 flex items-center">
                                        <i class="fas fa-money-check-alt text-danger mr-2"></i>
                                        Financial Issues
                                    </h4>
                                    <div class="space-y-4">
                                        <div class="p-4 border border-danger/30 rounded-lg bg-danger/5 dark:bg-danger/10">
                                            <div class="flex items-center justify-between mb-2">
                                                <div class="flex items-center">
                                                    <div class="w-8 h-8 rounded-full bg-danger/20 flex items-center justify-center text-danger mr-3">
                                                        <i class="fas fa-user text-sm"></i>
                                                    </div>
                                                    <p class="font-medium text-dark dark:text-white">David Chen</p>
                                                </div>
                                                <span class="text-xs bg-danger text-white px-2 py-1 rounded">Grade 11</span>
                                            </div>
                                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">Overdue fees: $450 (60 days)</p>
                                            <div class="flex items-center justify-between">
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Due since Feb 15</p>
                                                <button class="text-xs text-primary hover:underline">View Details</button>
                                            </div>
                                        </div>
                                        
                                        <div class="p-4 border border-danger/30 rounded-lg bg-danger/5 dark:bg-danger/10">
                                            <div class="flex items-center justify-between mb-2">
                                                <div class="flex items-center">
                                                    <div class="w-8 h-8 rounded-full bg-danger/20 flex items-center justify-center text-danger mr-3">
                                                        <i class="fas fa-user text-sm"></i>
                                                    </div>
                                                    <p class="font-medium text-dark dark:text-white">Sophia Martinez</p>
                                                </div>
                                                <span class="text-xs bg-danger text-white px-2 py-1 rounded">Grade 12</span>
                                            </div>
                                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">Partial payment pending: $320</p>
                                            <div class="flex items-center justify-between">
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Due in 5 days</p>
                                                <button class="text-xs text-primary hover:underline">View Details</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Column: Class & Subject Performance -->
                    <div class="lg:col-span-1">
                        <!-- Classes Needing Attention -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl dashboard-card p-6 mb-6">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-lg font-semibold text-dark dark:text-white">Classes Needing Attention</h3>
                                <span class="text-xs bg-info text-white px-3 py-1 rounded-full">7 Classes</span>
                            </div>
                            
                            <div class="space-y-5">
                                <div class="pb-4 border-b dark:border-gray-700">
                                    <div class="flex items-center justify-between mb-2">
                                        <p class="font-medium text-dark dark:text-white">Grade 10-B</p>
                                        <span class="text-xs text-info">Avg: 58%</span>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">Low performance in Science & Math</p>
                                    <div class="progress-bar">
                                        <div class="progress-fill bg-info" style="width: 58%"></div>
                                    </div>
                                    <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        <span>Below Target</span>
                                        <span>58%</span>
                                    </div>
                                </div>
                                
                                <div class="pb-4 border-b dark:border-gray-700">
                                    <div class="flex items-center justify-between mb-2">
                                        <p class="font-medium text-dark dark:text-white">Grade 9-C</p>
                                        <span class="text-xs text-warning">Avg: 62%</span>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">Attendance issues - 71% avg</p>
                                    <div class="progress-bar">
                                        <div class="progress-fill bg-warning" style="width: 62%"></div>
                                    </div>
                                    <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        <span>Needs Improvement</span>
                                        <span>62%</span>
                                    </div>
                                </div>
                                
                                <div class="pb-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <p class="font-medium text-dark dark:text-white">Grade 11-A</p>
                                        <span class="text-xs text-danger">Avg: 52%</span>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">High failure rate in Physics</p>
                                    <div class="progress-bar">
                                        <div class="progress-fill bg-danger" style="width: 52%"></div>
                                    </div>
                                    <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        <span>Critical</span>
                                        <span>52%</span>
                                    </div>
                                </div>
                            </div>
                            
                            <button class="w-full mt-4 py-2 text-center text-primary border border-primary rounded-lg hover:bg-primary hover:text-white transition-colors">
                                View All Classes Report
                            </button>
                        </div>
                        
                        <!-- Subjects Needing Attention -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl dashboard-card p-6">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-lg font-semibold text-dark dark:text-white">Subjects Needing Attention</h3>
                                <span class="text-xs bg-primary text-white px-3 py-1 rounded-full">5 Subjects</span>
                            </div>
                            
                            <div class="space-y-4">
                                <div class="p-3 bg-primary/5 dark:bg-primary/10 rounded-lg border border-primary/20">
                                    <div class="flex items-center justify-between mb-1">
                                        <p class="font-medium text-dark dark:text-white">Physics</p>
                                        <span class="text-xs text-danger">58% Avg</span>
                                    </div>
                                    <p class="text-xs text-gray-600 dark:text-gray-300 mb-2">Grades 10-12 performing below target</p>
                                    <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                                        <i class="fas fa-chart-line mr-1"></i>
                                        <span>Trend: Decreasing</span>
                                    </div>
                                </div>
                                
                                <div class="p-3 bg-warning/5 dark:bg-warning/10 rounded-lg border border-warning/20">
                                    <div class="flex items-center justify-between mb-1">
                                        <p class="font-medium text-dark dark:text-white">Mathematics</p>
                                        <span class="text-xs text-warning">63% Avg</span>
                                    </div>
                                    <p class="text-xs text-gray-600 dark:text-gray-300 mb-2">Algebra section particularly weak</p>
                                    <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                                        <i class="fas fa-chart-line mr-1"></i>
                                        <span>Trend: Stable</span>
                                    </div>
                                </div>
                                
                                <div class="p-3 bg-danger/5 dark:bg-danger/10 rounded-lg border border-danger/20">
                                    <div class="flex items-center justify-between mb-1">
                                        <p class="font-medium text-dark dark:text-white">Chemistry</p>
                                        <span class="text-xs text-danger">55% Avg</span>
                                    </div>
                                    <p class="text-xs text-gray-600 dark:text-gray-300 mb-2">Lowest performing subject this term</p>
                                    <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                                        <i class="fas fa-chart-line mr-1"></i>
                                        <span>Trend: Decreasing</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-6 p-4 bg-gradient-to-r from-primary/10 to-secondary/10 dark:from-primary/20 dark:to-secondary/20 rounded-lg">
                                <p class="text-sm font-medium text-dark dark:text-white mb-1">Recommendation</p>
                                <p class="text-xs text-gray-600 dark:text-gray-300">Schedule remedial classes for Physics & Chemistry in Grades 10-12</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Stats & Summary -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-gradient-to-r from-primary to-secondary rounded-xl p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm opacity-90">Total Pending Actions</p>
                                <h3 class="text-2xl font-bold mt-2">24</h3>
                            </div>
                            <i class="fas fa-tasks text-2xl opacity-80"></i>
                        </div>
                        <p class="text-xs opacity-80 mt-4">12 Approvals, 8 Students, 4 Classes</p>
                    </div>
                    
                    <div class="bg-gradient-to-r from-warning to-orange-500 rounded-xl p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm opacity-90">Critical Issues</p>
                                <h3 class="text-2xl font-bold mt-2">9</h3>
                            </div>
                            <i class="fas fa-exclamation-circle text-2xl opacity-80"></i>
                        </div>
                        <p class="text-xs opacity-80 mt-4">5 Academic, 3 Financial, 1 Behavioral</p>
                    </div>
                    
                    <div class="bg-gradient-to-r from-success to-emerald-500 rounded-xl p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm opacity-90">Resolved This Week</p>
                                <h3 class="text-2xl font-bold mt-2">18</h3>
                            </div>
                            <i class="fas fa-check-circle text-2xl opacity-80"></i>
                        </div>
                        <p class="text-xs opacity-80 mt-4">67% increase from last week</p>
                    </div>
                </div>
            </main>
            
            <!-- Footer -->
            <footer class="bg-white dark:bg-gray-800 border-t dark:border-gray-700 py-4 px-6">
                <div class="flex flex-col md:flex-row items-center justify-between">
                    <p class="text-gray-500 dark:text-gray-400 text-sm">© 2023 EduCare Pro School Management System. Version 2.1</p>
                    <div class="flex space-x-4 mt-2 md:mt-0">
                        <button class="text-gray-500 dark:text-gray-400 hover:text-primary text-sm flex items-center">
                            <i class="fas fa-download mr-1"></i>
                            Export Report
                        </button>
                        <button class="text-gray-500 dark:text-gray-400 hover:text-primary text-sm flex items-center">
                            <i class="fas fa-print mr-1"></i>
                            Print Summary
                        </button>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set current date
            const currentDate = new Date();
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            document.getElementById('current-date').textContent = currentDate.toLocaleDateString('en-US', options);
            
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
            
            // Approval action buttons
            const approveButtons = document.querySelectorAll('.fa-check-circle');
            const rejectButtons = document.querySelectorAll('.fa-times-circle');
            const viewButtons = document.querySelectorAll('.fa-eye');
            
            approveButtons.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const row = this.closest('tr');
                    row.style.opacity = '0.5';
                    setTimeout(() => {
                        row.innerHTML = `
                            <td colspan="5" class="py-8 text-center">
                                <i class="fas fa-check-circle text-success text-2xl mb-2"></i>
                                <p class="text-success font-medium">Request Approved</p>
                            </td>
                        `;
                    }, 300);
                });
            });
            
            rejectButtons.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const row = this.closest('tr');
                    row.style.opacity = '0.5';
                    setTimeout(() => {
                        row.innerHTML = `
                            <td colspan="5" class="py-8 text-center">
                                <i class="fas fa-times-circle text-danger text-2xl mb-2"></i>
                                <p class="text-danger font-medium">Request Rejected</p>
                            </td>
                        `;
                    }, 300);
                });
            });
            
            // Student attention buttons
            const academicBtn = document.querySelector('.border-primary');
            const financialBtn = document.querySelector('.border-danger');
            
            if (academicBtn && financialBtn) {
                academicBtn.addEventListener('click', function() {
                    this.classList.add('bg-primary', 'text-white');
                    financialBtn.classList.remove('bg-danger', 'text-white');
                });
                
                financialBtn.addEventListener('click', function() {
                    this.classList.add('bg-danger', 'text-white');
                    academicBtn.classList.remove('bg-primary', 'text-white');
                });
            }
            
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
