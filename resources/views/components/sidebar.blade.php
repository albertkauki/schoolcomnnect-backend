<!-- Sidebar -->
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
        @php $user = auth()->user(); @endphp

        @if($user && ($user->role ?? '') === 'class_teacher')
            <a href="{{ route('dashboard') }}" class="sidebar-item active flex items-center px-6 py-3 text-dark dark:text-white">
                <i class="fas fa-tachometer-alt w-5 mr-3 text-primary"></i>
                <span class="sidebar-text">Dashboard</span>
            </a>

            <a href="{{ route('marks.entry') }}" class="sidebar-item flex items-center px-6 py-3 text-dark dark:text-white">
                <i class="fas fa-pen-to-square w-5 mr-3 text-gray-500 dark:text-gray-400"></i>
                <span class="sidebar-text">Mark Entry</span>
            </a>

            <a href="#" class="sidebar-item flex items-center px-6 py-3 text-dark dark:text-white">
                <i class="fas fa-user w-5 mr-3 text-gray-500 dark:text-gray-400"></i>
                <span class="sidebar-text">My Profile</span>
            </a>
        @else
            <a href="{{ route('dashboard') }}" class="sidebar-item active flex items-center px-6 py-3 text-dark dark:text-white">
                <i class="fas fa-tachometer-alt w-5 mr-3 text-primary"></i>
                <span class="sidebar-text">Dashboard</span>
            </a>

            <a href="#" class="sidebar-item flex items-center px-6 py-3 text-dark dark:text-white">
                <i class="fas fa-user-check w-5 mr-3 text-gray-500 dark:text-gray-400"></i>
                <span class="sidebar-text">Approvals</span>
                <span class="sidebar-badge ml-auto bg-danger text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
            </a>

            <a href="{{ route('showStudents') }}" class="sidebar-item flex items-center px-6 py-3 text-dark dark:text-white">
                <i class="fas fa-users w-5 mr-3 text-gray-500 dark:text-gray-400"></i>
                <span class="sidebar-text">Students</span>
            </a>

            <a href="{{ route('showTeachers') }}" class="sidebar-item flex items-center px-6 py-3 text-dark dark:text-white">
                <i class="fas fa-chalkboard-teacher w-5 mr-3 text-gray-500 dark:text-gray-400"></i>
                <span class="sidebar-text">Teachers</span>
            </a>

            <a href="{{ route('academic-rules.index') }}" class="sidebar-item flex items-center px-6 py-3 text-dark dark:text-white">
                <i class="fas fa-chalkboard-teacher w-5 mr-3 text-gray-500 dark:text-gray-400"></i>
                <span class="sidebar-text">Rules</span>
            </a>

            <a href="{{ route('showSubjects') }}" class="sidebar-item flex items-center px-6 py-3 text-dark dark:text-white">
                <i class="fas fa-book-open w-5 mr-3 text-gray-500 dark:text-gray-400"></i>
                <span class="sidebar-text">Subjects & Combs</span>
            </a>

            <a href="{{ route('showClasses') }}" class="sidebar-item flex items-center px-6 py-3 text-dark dark:text-white">
                <i class="fas fa-school w-5 mr-3 text-gray-500 dark:text-gray-400"></i>
                <span class="sidebar-text">Classes</span>
            </a>

            <a href="{{ route('examconfigurations.index') }}" class="sidebar-item flex items-center px-6 py-3 text-dark dark:text-white">
                <i class="fas fa-clipboard-list w-5 mr-3 text-gray-500 dark:text-gray-400"></i>
                <span class="sidebar-text">Exam Configs</span>
            </a>

            <a href="{{ route('marks.entry') }}" class="sidebar-item flex items-center px-6 py-3 text-dark dark:text-white">
                <i class="fas fa-pen-to-square w-5 mr-3 text-gray-500 dark:text-gray-400"></i>
                <span class="sidebar-text">Mark Entry</span>
            </a>
        @endif
        
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
