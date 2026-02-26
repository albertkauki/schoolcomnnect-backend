<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduCare Pro | Subject & Combination Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .sidebar-item:hover, .sidebar-item.active {
            background: linear-gradient(90deg, rgba(79, 70, 229, 0.1) 0%, rgba(79, 70, 229, 0.05) 100%);
            border-left: 4px solid #4f46e5;
        }
        .dashboard-card {
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }
        
        /* Tab styling */
        .tab-button {
            position: relative;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            color: #6b7280;
            border-bottom: 2px solid transparent;
            transition: all 0.2s ease;
        }
        .tab-button.active {
            color: #4f46e5;
            border-bottom-color: #4f46e5;
        }
        .tab-button.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 2px;
            background-color: #4f46e5;
        }
        
        /* Subject chip styling */
        .subject-chip {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
        }
        .principal-chip {
            background-color: #e0e7ff;
            color: #3730a3;
            border: 1px solid #c7d2fe;
        }
        .subsidiary-chip {
            background-color: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
        }
        
        /* Dark mode adjustments */
        .dark .principal-chip {
            background-color: rgba(79, 70, 229, 0.2);
            color: #c7d2fe;
            border-color: rgba(79, 70, 229, 0.4);
        }
        .dark .subsidiary-chip {
            background-color: rgba(34, 197, 94, 0.2);
            color: #bbf7d0;
            border-color: rgba(34, 197, 94, 0.4);
        }
        .dark .dashboard-card {
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.3), 0 1px 2px 0 rgba(0, 0, 0, 0.2);
        }

        /* Collapsible sidebar */
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
        
        /* Form styling */
        .form-section {
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .form-section h3 {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 1.25rem;
            color: #111827;
        }
        .dark .form-section h3 {
            color: #f9fafb;
        }
        
        /* Combination card */
        .combination-card {
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.2s ease;
        }
        .combination-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        /* Loading animation */
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .loading {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
</head>
<body class="font-inter bg-gray-50 dark:bg-gray-900">
    @php
        $subjects = $subjects ?? collect();
        $combinations = $combinations ?? collect();
        $aLevelSubjects = $aLevelSubjects ?? collect();
        $subjectsCount = method_exists($subjects, 'total') ? $subjects->total() : $subjects->count();
        $combinationsCount = method_exists($combinations, 'total') ? $combinations->total() : $combinations->count();
    @endphp
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
                            <h2 class="text-xl font-semibold text-dark dark:text-white">Subject & Combination Management</h2>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Manage subjects and A-Level combinations</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-6">
                        <!-- Dark Mode Toggle -->
                        <button id="darkModeToggle" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i id="darkModeIcon" class="fas fa-moon text-gray-600 dark:text-yellow-400"></i>
                        </button>
                        
                        <div class="hidden md:flex items-center space-x-4">
                            <div class="bg-primary/10 dark:bg-primary/20 px-4 py-2 rounded-lg">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Total Subjects</p>
                                <p class="text-sm font-medium text-dark dark:text-white">{{ $subjectsCount }}</p>
                            </div>
                            <div class="bg-success/10 dark:bg-success/20 px-4 py-2 rounded-lg">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Total Combinations</p>
                                <p class="text-sm font-medium text-dark dark:text-white">{{ $combinationsCount }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-3 border-l dark:border-gray-700 pl-6">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-white font-bold">
                                AD
                            </div>
                            <div class="hidden md:block">
                                <p class="text-sm font-medium text-dark dark:text-white">Academic Admin</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">admin@educare.edu</p>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Main Content -->
            <main class="p-6">
                @if (session('success'))
                    <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Left Column: Data Entry (30%) -->
                    <div class="lg:col-span-1">
                        <!-- Form A: Register Subject -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl dashboard-card form-section">
                            <h3 class="flex items-center">
                                <i class="fas fa-book-medical text-primary mr-2"></i>
                                Register New Subject
                            </h3>
                            
                            <form id="subjectForm" method="POST" action="{{ route('subjects.store') }}">
                                @csrf
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Subject Name *
                                        </label>
                                        <input type="text" id="subjectName" name="name"
                                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white"
                                               placeholder="e.g., Physics" required>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Unique Code *
                                        </label>
                                        <input type="text" id="subjectCode" name="subject_code"
                                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white"
                                               placeholder="e.g., PHY-OL" required>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Format: XXX-YY where XXX is subject abbreviation</p>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Academic Level *
                                        </label>
                                        <select id="subjectLevel" name="level"
                                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white"
                                                required>
                                            <option value="">Select Level</option>
                                            <option value="O-Level">O-Level</option>
                                            <option value="A-Level">A-Level</option>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Category *
                                        </label>
                                        <select id="subjectCategory" name="category"
                                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white"
                                                required>
                                            <option value="">Select Category</option>
                                            <option value="core">Core</option>
                                            <option value="elective">Elective</option>
                                        </select>
                                    </div>
                                    
                                    <div class="pt-4">
                                        <button type="submit" 
                                                class="w-full bg-primary hover:bg-primary-dark text-white font-medium py-2.5 px-4 rounded-lg transition-colors flex items-center justify-center">
                                            <i class="fas fa-plus-circle mr-2"></i>
                                            Register Subject
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Form B: Combination Builder -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl dashboard-card form-section">
                            <h3 class="flex items-center">
                                <i class="fas fa-layer-group text-secondary mr-2"></i>
                                Build A-Level Combination
                            </h3>
                            
                            <form id="combinationForm" method="POST" action="{{ route('combinations.store') }}">
                                @csrf
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Combination Name *
                                        </label>
                                        <input type="text" id="combinationName" name="name"
                                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white"
                                               placeholder="e.g., PCM, HGL, PCB" required>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Typically 3 letters representing principal subjects</p>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Principal Subjects (Select 3) *
                                        </label>
                                        <div class="space-y-3">
                                            <select id="principal1" name="principal_ids[]"
                                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white"
                                                    required>
                                                <option value="">Select 1st Principal</option>
                                                @foreach ($aLevelSubjects as $subject)
                                                    <option value="{{ $subject->id }}">{{ $subject->name }} ({{ $subject->subject_code }})</option>
                                                @endforeach
                                            </select>
                                            
                                            <select id="principal2" name="principal_ids[]"
                                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white"
                                                    required>
                                                <option value="">Select 2nd Principal</option>
                                                @foreach ($aLevelSubjects as $subject)
                                                    <option value="{{ $subject->id }}">{{ $subject->name }} ({{ $subject->subject_code }})</option>
                                                @endforeach
                                            </select>
                                            
                                            <select id="principal3" name="principal_ids[]"
                                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white"
                                                    required>
                                                <option value="">Select 3rd Principal</option>
                                                @foreach ($aLevelSubjects as $subject)
                                                    <option value="{{ $subject->id }}">{{ $subject->name }} ({{ $subject->subject_code }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Subsidiary Subjects
                                        </label>
                                        <div class="space-y-2">
                                            @forelse ($aLevelSubjects as $subject)
                                                <label class="flex items-center">
                                                    <input type="checkbox" name="subsidiary_ids[]" value="{{ $subject->id }}"
                                                           class="rounded border-gray-300 text-primary focus:ring-primary dark:border-gray-600 dark:bg-gray-700">
                                                    <span class="ml-2 text-gray-700 dark:text-gray-300">{{ $subject->name }}</span>
                                                </label>
                                            @empty
                                                <p class="text-sm text-gray-500 dark:text-gray-400">No A-Level subjects available.</p>
                                            @endforelse
                                        </div>
                                    </div>
                                    
                                    <div class="pt-4">
                                        <button type="submit" 
                                                class="w-full bg-secondary hover:bg-secondary-dark text-white font-medium py-2.5 px-4 rounded-lg transition-colors flex items-center justify-center">
                                            <i class="fas fa-save mr-2"></i>
                                            Save Combination
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Right Column: Display & Management (70%) -->
                    <div class="lg:col-span-2">
                        <div class="bg-white dark:bg-gray-800 rounded-xl dashboard-card">
                            <!-- Tab Navigation -->
                            <div class="border-b dark:border-gray-700">
                                <div class="flex space-x-1">
                                    <button id="tabSubjects" class="tab-button active" data-tab="subjects">
                                        <i class="fas fa-book-open mr-2"></i>
                                        Subject Registry
                                    </button>
                                    <button id="tabCombinations" class="tab-button" data-tab="combinations">
                                        <i class="fas fa-project-diagram mr-2"></i>
                                        Combination Registry
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Tab Content -->
                            <div class="p-6">
                                <!-- Search and Filter Bar -->
                                <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
                                    <div class="w-full md:w-1/3 mb-4 md:mb-0">
                                        <div class="relative">
                                            <input type="text" id="searchInput" 
                                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white"
                                                   placeholder="Search...">
                                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                                        </div>
                                    </div>
                                    <div class="flex space-x-2">
                                        <select id="filterLevel" 
                                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white">
                                            <option value="">All Levels</option>
                                            <option value="A-Level">A-Level</option>
                                            <option value="O-Level">O-Level</option>
                                        </select>
                                        <select id="filterCategory" 
                                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white">
                                            <option value="">All Categories</option>
                                            <option value="Science">Science</option>
                                            <option value="Mathematics">Mathematics</option>
                                            <option value="Arts">Arts</option>
                                            <option value="Languages">Languages</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- Subjects Tab Content -->
                                <div id="subjectsContent" class="tab-content">
                                    <div class="overflow-x-auto">
                                        <table class="w-full">
                                            <thead>
                                                <tr class="text-left text-gray-500 dark:text-gray-400 text-sm border-b dark:border-gray-700">
                                                    <th class="pb-3 font-medium">Subject Name</th>
                                                    <th class="pb-3 font-medium">Code</th>
                                                    <th class="pb-3 font-medium">Level</th>
                                                    <th class="pb-3 font-medium">Category</th>
                                                    <th class="pb-3 font-medium">Status</th>
                                                    <th class="pb-3 font-medium">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($subjects as $subject)
                                                    <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                                        <td class="py-4 font-medium text-dark dark:text-white">{{ $subject->name }}</td>
                                                        <td class="py-4">
                                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                                {{ $subject->subject_code }}
                                                            </span>
                                                        </td>
                                                        <td class="py-4">
                                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                                                {{ $subject->level === 'A-Level' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' }}">
                                                                {{ $subject->level }}
                                                            </span>
                                                        </td>
                                                        <td class="py-4 text-dark dark:text-white">{{ $subject->category }}</td>
                                                        <td class="py-4">
                                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                                                <i class="fas fa-circle text-xs mr-1"></i>
                                                                {{ $subject->status ?? 'Active' }}
                                                            </span>
                                                        </td>
                                                        <td class="py-4">
                                                            <div class="flex space-x-2 text-gray-400">
                                                                <button class="cursor-not-allowed" title="Edit disabled">
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                                <button class="cursor-not-allowed" title="Delete disabled">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="6" class="py-8 text-center text-gray-500 dark:text-gray-400">
                                                            <i class="fas fa-inbox text-3xl mb-2"></i>
                                                            <p>No subjects found</p>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <div class="mt-6 flex items-center justify-between">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            Showing {{ $subjectsCount }} subjects
                                        </p>
                                        <div class="flex space-x-2">
                                            <button id="prevSubjects" class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded-lg disabled:opacity-50" disabled>
                                                <i class="fas fa-chevron-left"></i>
                                            </button>
                                            <button id="nextSubjects" class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded-lg disabled:opacity-50" disabled>
                                                <i class="fas fa-chevron-right"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Combinations Tab Content -->
                                <div id="combinationsContent" class="tab-content hidden">
                                    <div id="combinationsContainer" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @forelse ($combinations as $combination)
                                            @php
                                                $principals = $combination->subjects->where('pivot.type', 'principal');
                                                $subsidiaries = $combination->subjects->where('pivot.type', 'subsidiary');
                                            @endphp
                                            <div class="combination-card bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600">
                                                <div class="flex justify-between items-start mb-4">
                                                    <div>
                                                        <h4 class="font-bold text-lg text-dark dark:text-white">{{ $combination->name }}</h4>
                                                        <span class="text-sm text-green-600 dark:text-green-400">Active</span>
                                                    </div>
                                                    <div class="flex space-x-2 text-gray-400">
                                                        <button class="cursor-not-allowed" title="Edit disabled">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button class="cursor-not-allowed" title="Delete disabled">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                
                                                <div class="mb-4">
                                                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">Principal Subjects:</p>
                                                    <div class="flex flex-wrap">
                                                        @forelse ($principals as $subject)
                                                            <span class="subject-chip principal-chip">
                                                                <i class="fas fa-star text-xs mr-1"></i>
                                                                {{ $subject->name }}
                                                            </span>
                                                        @empty
                                                            <p class="text-sm text-gray-500 dark:text-gray-400 italic">No principal subjects</p>
                                                        @endforelse
                                                    </div>
                                                </div>
                                                
                                                @if ($subsidiaries->count() > 0)
                                                    <div>
                                                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">Subsidiary Subjects:</p>
                                                        <div class="flex flex-wrap">
                                                            @foreach ($subsidiaries as $subject)
                                                                <span class="subject-chip subsidiary-chip">
                                                                    <i class="fas fa-check-circle text-xs mr-1"></i>
                                                                    {{ $subject->name }}
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @else
                                                    <p class="text-sm text-gray-500 dark:text-gray-400 italic">No subsidiary subjects</p>
                                                @endif
                                            </div>
                                        @empty
                                            <div class="col-span-2 py-8 text-center text-gray-500 dark:text-gray-400">
                                                <i class="fas fa-inbox text-3xl mb-2"></i>
                                                <p>No combinations found</p>
                                            </div>
                                        @endforelse
                                    </div>
                                    
                                    <div class="mt-6 flex items-center justify-between">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            Showing {{ $combinationsCount }} combinations
                                        </p>
                                        <div class="flex space-x-2">
                                            <button id="prevCombinations" class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded-lg disabled:opacity-50" disabled>
                                                <i class="fas fa-chevron-left"></i>
                                            </button>
                                            <button id="nextCombinations" class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded-lg disabled:opacity-50" disabled>
                                                <i class="fas fa-chevron-right"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal for Editing Subject -->
    <div id="editSubjectModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-dark dark:text-white">Edit Subject</h3>
                    <button id="closeSubjectModal" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form id="editSubjectForm">
                    <input type="hidden" id="editSubjectId">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Subject Name *
                            </label>
                            <input type="text" id="editSubjectName" 
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white"
                                   required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Unique Code *
                            </label>
                            <input type="text" id="editSubjectCode" 
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white"
                                   required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Academic Level *
                            </label>
                            <select id="editSubjectLevel" 
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white"
                                    required>
                                <option value="O-Level">O-Level</option>
                                <option value="A-Level">A-Level</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Category *
                            </label>
                            <select id="editSubjectCategory" 
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white"
                                    required>
                                <option value="core">Core</option>
                                <option value="elective">Elective</option>


                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Status *
                            </label>
                            <select id="editSubjectStatus" 
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white"
                                    required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="flex space-x-3 mt-8">
                        <button type="button" id="cancelSubjectEdit" 
                                class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="flex-1 bg-primary hover:bg-primary-dark text-white font-medium py-2 px-4 rounded-lg transition-colors">
                            Update Subject
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal for Editing Combination -->
    <div id="editCombinationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-dark dark:text-white">Edit Combination</h3>
                    <button id="closeCombinationModal" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form id="editCombinationForm">
                    <input type="hidden" id="editCombinationId">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Combination Name *
                            </label>
                            <input type="text" id="editCombinationName" 
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white"
                                   required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Principal Subjects (Select 3) *
                            </label>
                            <div class="space-y-3">
                                <select id="editPrincipal1" 
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white"
                                        required>
                                    <option value="">Select 1st Principal</option>
                                </select>
                                
                                <select id="editPrincipal2" 
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white"
                                        required>
                                    <option value="">Select 2nd Principal</option>
                                </select>
                                
                                <select id="editPrincipal3" 
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white"
                                        required>
                                    <option value="">Select 3rd Principal</option>
                                </select>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Subsidiary Subjects
                            </label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" id="editGeneralStudies" 
                                           class="rounded border-gray-300 text-primary focus:ring-primary dark:border-gray-600 dark:bg-gray-700">
                                    <span class="ml-2 text-gray-700 dark:text-gray-300">General Studies</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" id="editBam" 
                                           class="rounded border-gray-300 text-primary focus:ring-primary dark:border-gray-600 dark:bg-gray-700">
                                    <span class="ml-2 text-gray-700 dark:text-gray-300">BAM (Basic Applied Mathematics)</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" id="editComputerStudies" 
                                           class="rounded border-gray-300 text-primary focus:ring-primary dark:border-gray-600 dark:bg-gray-700">
                                    <span class="ml-2 text-gray-700 dark:text-gray-300">Computer Studies</span>
                                </label>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Status *
                            </label>
                            <select id="editCombinationStatus" 
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-700 dark:text-white"
                                    required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="flex space-x-3 mt-8">
                        <button type="button" id="cancelCombinationEdit" 
                                class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="flex-1 bg-secondary hover:bg-secondary-dark text-white font-medium py-2 px-4 rounded-lg transition-colors">
                            Update Combination
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const darkModeToggle = document.getElementById('darkModeToggle');
            const darkModeIcon = document.getElementById('darkModeIcon');
            const savedTheme = localStorage.getItem('theme') ||
                (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');

            if (savedTheme === 'dark') {
                document.documentElement.classList.add('dark');
                darkModeIcon.classList.remove('fa-moon');
                darkModeIcon.classList.add('fa-sun');
            }

            if (darkModeToggle) {
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
            }

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
                });
            }

            const tabSubjects = document.getElementById('tabSubjects');
            const tabCombinations = document.getElementById('tabCombinations');
            const subjectsContent = document.getElementById('subjectsContent');
            const combinationsContent = document.getElementById('combinationsContent');

            if (tabSubjects && tabCombinations && subjectsContent && combinationsContent) {
                tabSubjects.addEventListener('click', function() {
                    tabSubjects.classList.add('active');
                    tabCombinations.classList.remove('active');
                    subjectsContent.classList.remove('hidden');
                    combinationsContent.classList.add('hidden');
                });

                tabCombinations.addEventListener('click', function() {
                    tabCombinations.classList.add('active');
                    tabSubjects.classList.remove('active');
                    combinationsContent.classList.remove('hidden');
                    subjectsContent.classList.add('hidden');
                });
            }
        });
    </script>
</body>
</html>
