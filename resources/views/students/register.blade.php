<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Student | EduCare Pro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#4f46e5',
                        secondary: '#7c3aed',
                        warning: '#f59e0b',
                        danger: '#ef4444',
                        success: '#10b981',
                        info: '#3b82f6',
                        light: '#f8fafc',
                        dark: '#1e293b'
                    },
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <style>
        .section-card {
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }
        .form-input {
            border: 2px solid #e5e7eb;
            transition: all 0.2s ease;
        }
        .form-input:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
            outline: none;
        }
    </style>
</head>
<body class="font-inter bg-gray-50 dark:bg-gray-900">
    <div class="flex h-screen">
        @include('components.sidebar')

        <div class="flex-1 overflow-y-auto">
            <header class="bg-white dark:bg-gray-800 shadow-sm py-4 px-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('showStudents') }}" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i class="fas fa-arrow-left text-gray-600 dark:text-gray-300"></i>
                        </a>
                        <div>
                            <h2 class="text-xl font-semibold text-dark dark:text-white">Register Student</h2>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Create a new student profile</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3">
                        <button type="button" id="darkModeToggle" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i id="darkModeIcon" class="fas fa-moon text-gray-600 dark:text-yellow-400"></i>
                        </button>
                        <a href="{{ route('showStudents') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            Cancel
                        </a>
                    </div>
                </div>
            </header>

<main class="p-6">
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 section-card">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-dark dark:text-white">Student Identity</h3>
                    <span class="text-xs text-gray-500 dark:text-gray-400">Required fields marked *</span>
                </div>

                @if (session('success'))
                    <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                    </div>
                @endif

                <form id="studentForm" method="POST" action="{{ route('students.store') }}" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-2 gap-6 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-200 dark:border-gray-700">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Registration No.</label>
                            <input type="text" disabled placeholder="Generated Automatically" class="form-input w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 cursor-not-allowed italic">
                            <p class="text-xs text-gray-500 mt-1">Format: ARC/FORM/STREAM/00X/2026</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Admission Date</label>
                            <input type="date" name="admission_date" value="{{ old('admission_date', date('Y-m-d')) }}" class="form-input w-full px-4 py-3 rounded-lg bg-transparent">
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">First Name *</label>
                            <input type="text" name="first_name" required value="{{ old('first_name') }}" class="form-input w-full px-4 py-3 rounded-lg bg-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Middle Name</label>
                            <input type="text" name="middle_name" value="{{ old('middle_name') }}" class="form-input w-full px-4 py-3 rounded-lg bg-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Last Name *</label>
                            <input type="text" name="last_name" required value="{{ old('last_name') }}" class="form-input w-full px-4 py-3 rounded-lg bg-transparent">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Gender *</label>
                            <select name="gender" required class="form-input w-full px-4 py-3 rounded-lg bg-transparent">
                                <option value="">Select Gender</option>
                                <option value="Male" {{ old('gender') === 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender') === 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date of Birth *</label>
                            <input type="date" name="date_of_birth" required value="{{ old('date_of_birth') }}" class="form-input w-full px-4 py-3 rounded-lg bg-transparent">
                        </div>
                    </div>

                    <div class="p-4 bg-indigo-50 dark:bg-indigo-900/20 rounded-xl border border-indigo-100 dark:border-indigo-800">
                        <label class="block text-sm font-bold text-indigo-700 dark:text-indigo-300 mb-2">Assign to Class *</label>
                        <select name="school_class_id" required class="form-input w-full px-4 py-3 rounded-lg bg-white dark:bg-gray-800 border-indigo-200">
                            <option value="">-- Choose Level, Form and Stream --</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ old('school_class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }} ({{ $class->level }})
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-indigo-500 mt-2">
                            <i class="fas fa-info-circle mr-1"></i> 
                            Class selection automatically determines the student's Form, Level, and Combination.
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">PREM Number (Optional)</label>
                            <input type="text" name="prem_number" placeholder="Primary School ID" value="{{ old('prem_number') }}" class="form-input w-full px-4 py-3 rounded-lg bg-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">NECTA Index (Optional)</label>
                            <input type="text" name="necta_index_number" placeholder="SXXXX/XXXX/XXXX" value="{{ old('necta_index_number') }}" class="form-input w-full px-4 py-3 rounded-lg bg-transparent">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Parent/Guardian Name *</label>
                            <input type="text" name="parent_name" required value="{{ old('parent_name') }}" class="form-input w-full px-4 py-3 rounded-lg bg-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Parent Phone Number *</label>
                            <input type="tel" name="parent_phone" required placeholder="07xxxxxxxx" value="{{ old('parent_phone') }}" class="form-input w-full px-4 py-3 rounded-lg bg-transparent">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <aside class="col-span-4 space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 section-card">
                <h4 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">Quick Help</h4>
                <ul class="space-y-4">
                    <li class="flex text-sm text-gray-600 dark:text-gray-300">
                        <i class="fas fa-id-card text-indigo-500 mr-3 mt-1"></i>
                        <span>Reg numbers are tied to the **Class Year**. Ensure the PC date is correct (2026).</span>
                    </li>
                    <li class="flex text-sm text-gray-600 dark:text-gray-300">
                        <i class="fas fa-phone-alt text-indigo-500 mr-3 mt-1"></i>
                        <span>SMS alerts for attendance will be sent to the Parent Phone provided.</span>
                    </li>
                </ul>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 section-card">
                <h4 class="text-lg font-semibold text-dark dark:text-white mb-4">Registration Status</h4>
                <div class="flex items-center space-x-2 mb-6">
                    <span class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></span>
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-300">System Ready for Admission</span>
                </div>
                
                <div class="space-y-4">
                    <label class="flex items-center p-3 rounded-lg border border-gray-100 dark:border-gray-700 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <input type="checkbox" name="add_another" value="1" form="studentForm" {{ old('add_another') ? 'checked' : '' }} class="h-5 w-5 rounded border-gray-300 text-primary focus:ring-primary">
                        <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">Add another student after saving</span>
                    </label>

                    <button type="submit" form="studentForm" class="w-full bg-primary text-white py-4 rounded-xl font-bold hover:bg-primary/90 transition-all shadow-lg shadow-indigo-200 dark:shadow-none flex items-center justify-center">
                        <i class="fas fa-user-plus mr-2"></i> Confirm Registration
                    </button>
                </div>
            </div>
        </aside>
    </div>
</main>
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

        });
    </script>
</body>
</html>
