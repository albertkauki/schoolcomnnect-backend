<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="font-inter bg-gradient-to-br from-primary/5 to-secondary/5 dark:bg-gray-900 min-h-screen">
    <!-- Header -->
    <header class="bg-white dark:bg-gray-800 shadow-sm">
        <div class="max-w-4xl mx-auto px-6 py-4 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-dark dark:text-white">Teacher Portal</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</p>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 bg-danger text-white rounded-lg hover:bg-danger-dark transition">
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                </button>
            </form>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-6 py-8">
        <div class="grid grid-cols-1 gap-6">
            <!-- Marks Entry Card -->
            <a href="{{ route('marks.entry') }}" class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow hover:shadow-lg transition">
                <div class="flex items-center">
                    <div class="bg-primary/10 dark:bg-primary/20 p-4 rounded-xl mr-6">
                        <i class="fas fa-pen-to-square text-primary text-3xl"></i>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-dark dark:text-white">Mark Entry</h2>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">Enter and manage student marks for your subjects</p>
                        <p class="text-sm text-primary font-medium mt-2">Click to open →</p>
                    </div>
                </div>
            </a>

            <!-- Quick Info -->
            <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow">
                <h3 class="text-lg font-bold text-dark dark:text-white mb-4">Welcome</h3>
                <p class="text-gray-600 dark:text-gray-400">Use the Mark Entry section above to enter student marks. Your session will expire after 2 hours of inactivity.</p>
            </div>
        </div>
    </main>
</body>
</html>
