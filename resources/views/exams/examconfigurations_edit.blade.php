<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Exam Configuration | EduCare Pro</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .dashboard-card {
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }
    </style>
</head>
<body class="font-inter bg-gray-50 dark:bg-gray-900">
    <div class="flex h-screen">
        @include('components.sidebar')

        <div class="flex-1 overflow-y-auto">
            <header class="bg-white dark:bg-gray-800 shadow-sm py-4 px-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('examconfigurations.index') }}" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                            <i class="fas fa-arrow-left text-gray-600 dark:text-gray-300"></i>
                        </a>
                        <div>
                            <h2 class="text-xl font-semibold text-dark dark:text-white">Edit Exam Configuration</h2>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Update exam settings</p>
                        </div>
                    </div>
                </div>
            </header>

            <main class="p-6">
                @if ($errors->any())
                <div class="mb-6 rounded-xl border-l-4 border-red-500 bg-red-50 dark:bg-red-900/30 px-6 py-4 text-red-700 dark:text-red-300">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-exclamation-circle mr-3"></i>
                        <p class="font-medium">Please fix the errors below</p>
                    </div>
                    <ul class="list-disc list-inside text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 dashboard-card max-w-2xl">
                    <form action="{{ route('examconfigurations.update', $config->id) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
                            <input type="text" name="name" value="{{ old('name', $config->name) }}" required
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-dark dark:text-white">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Term</label>
                                <select name="term" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-dark dark:text-white">
                                    <option value="1" {{ old('term', $config->term) == 1 ? 'selected' : '' }}>Term 1</option>
                                    <option value="2" {{ old('term', $config->term) == 2 ? 'selected' : '' }}>Term 2</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Academic Year</label>
                                <input type="text" name="academic_year" value="{{ old('academic_year', $config->academic_year) }}" required
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-dark dark:text-white">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Weight (%)</label>
                            <input type="number" step="0.01" min="0" max="100" name="weight" value="{{ old('weight', $config->weight) }}" required
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-dark dark:text-white">
                        </div>
                        <label class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $config->is_active) ? 'checked' : '' }} class="rounded border-gray-300">
                            Active configuration
                        </label>
                        <div class="flex items-center gap-3">
                            <button type="submit"
                                    class="bg-primary text-white px-5 py-2.5 rounded-xl font-bold hover:bg-primary-dark transition-all shadow-lg shadow-primary/20">
                                <i class="fas fa-save mr-2"></i> Update Configuration
                            </button>
                            <a href="{{ route('examconfigurations.index') }}" class="px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
