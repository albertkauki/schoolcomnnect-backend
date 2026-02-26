<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Configuration | EduCare Pro</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .dashboard-card {
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="font-inter bg-gray-50 dark:bg-gray-900">
    <div class="flex h-screen">
        @include('components.sidebar')

        <div class="flex-1 overflow-y-auto">
            <header class="bg-white dark:bg-gray-800 shadow-sm py-4 px-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-dark dark:text-white">Exam Configuration</h2>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Create and manage exam weight settings</p>
                    </div>
                </div>
            </header>

            <main class="p-6 space-y-6">
                @if (session('success'))
                <div class="rounded-xl border-l-4 border-green-500 bg-green-50 dark:bg-green-900/30 px-6 py-4 text-green-700 dark:text-green-300 flex items-center">
                    <i class="fas fa-check-circle mr-3"></i>
                    <div>
                        <p class="font-medium">{{ session('success') }}</p>
                        <p class="text-sm mt-1 opacity-90">Configuration saved</p>
                    </div>
                </div>
                @endif

                @if ($errors->any())
                <div class="rounded-xl border-l-4 border-red-500 bg-red-50 dark:bg-red-900/30 px-6 py-4 text-red-700 dark:text-red-300">
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

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-1">
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 dashboard-card">
                            <h3 class="text-lg font-semibold text-dark dark:text-white mb-4">New Configuration</h3>
                            <form action="{{ route('examconfigurations.store') }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
                                    <input type="text" name="name" value="{{ old('name') }}" required
                                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-dark dark:text-white">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Term</label>
                                        <select name="term" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-dark dark:text-white">
                                            <option value="">Select</option>
                                            <option value="1" {{ old('term') == 1 ? 'selected' : '' }}>Term 1</option>
                                            <option value="2" {{ old('term') == 2 ? 'selected' : '' }}>Term 2</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Academic Year</label>
                                        <input type="text" name="academic_year" value="{{ old('academic_year', date('Y')) }}" required
                                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-dark dark:text-white">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Weight (%)</label>
                                    <input type="number" step="0.01" min="0" max="100" name="weight" value="{{ old('weight') }}" required
                                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-dark dark:text-white">
                                </div>
                                <label class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }} class="rounded border-gray-300">
                                    Active configuration
                                </label>
                                <button type="submit"
                                        class="w-full bg-primary text-white py-3 rounded-xl font-bold hover:bg-primary-dark transition-all shadow-lg shadow-primary/20">
                                    <i class="fas fa-save mr-2"></i> Save Configuration
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="lg:col-span-2">
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 dashboard-card">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-dark dark:text-white">Existing Configurations</h3>
                                <span class="text-xs bg-primary/10 text-primary px-3 py-1 rounded-full">{{ $configs->total() }} Total</span>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead>
                                        <tr class="text-left text-gray-500 dark:text-gray-400 text-sm border-b dark:border-gray-700">
                                            <th class="pb-3 font-medium">Name</th>
                                            <th class="pb-3 font-medium">Term</th>
                                            <th class="pb-3 font-medium">Year</th>
                                            <th class="pb-3 font-medium">Weight</th>
                                            <th class="pb-3 font-medium">Status</th>
                                            <th class="pb-3 font-medium text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                        @forelse($configs as $config)
                                            <tr>
                                                <td class="py-3 font-medium text-dark dark:text-white">{{ $config->name }}</td>
                                                <td class="py-3 text-gray-600 dark:text-gray-300">
                                                    @php
                                                        $termLabels = [1 => 'Term 1', 2 => 'Term 2'];
                                                    @endphp
                                                    {{ $termLabels[$config->term] ?? $config->term }}
                                                </td>
                                                <td class="py-3 text-gray-600 dark:text-gray-300">{{ $config->academic_year }}</td>
                                                <td class="py-3 text-gray-600 dark:text-gray-300">{{ number_format($config->weight, 2) }}%</td>
                                                <td class="py-3">
                                                    @if($config->is_active)
                                                        <span class="px-2.5 py-1 bg-success/10 text-success rounded-full text-xs font-semibold">Active</span>
                                                    @else
                                                        <span class="px-2.5 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-full text-xs font-semibold">Inactive</span>
                                                    @endif
                                                </td>
                                                <td class="py-3 text-right">
                                                    <div class="flex items-center justify-end gap-2">
                                                        @if($config->is_active)
                                                            <form method="POST" action="{{ route('examconfigurations.activate', $config->id) }}">
                                                                @csrf
                                                                <input type="hidden" name="mode" value="deactivate">
                                                                <button type="submit" class="px-3 py-1.5 text-xs bg-warning/10 text-warning rounded-lg hover:bg-warning/20">
                                                                    <i class="fas fa-toggle-off mr-1"></i>Deactivate
                                                                </button>
                                                            </form>
                                                        @else
                                                            @if($activeCount > 0)
                                                                <button type="button"
                                                                        class="px-3 py-1.5 text-xs bg-success/10 text-success rounded-lg hover:bg-success/20"
                                                                        onclick="openActivateModal({{ $config->id }}, '{{ addslashes($config->name) }}')">
                                                                    <i class="fas fa-toggle-on mr-1"></i>Activate
                                                                </button>
                                                            @else
                                                                <form method="POST" action="{{ route('examconfigurations.activate', $config->id) }}">
                                                                    @csrf
                                                                    <input type="hidden" name="mode" value="single">
                                                                    <button type="submit" class="px-3 py-1.5 text-xs bg-success/10 text-success rounded-lg hover:bg-success/20">
                                                                        <i class="fas fa-toggle-on mr-1"></i>Activate
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        @endif
                                                        <a href="{{ route('examconfigurations.edit', $config->id) }}" class="px-3 py-1.5 text-xs bg-primary/10 text-primary rounded-lg hover:bg-primary/20">
                                                            <i class="fas fa-edit mr-1"></i>Edit
                                                        </a>
                                                        <form method="POST" action="{{ route('examconfigurations.destroy', $config->id) }}" onsubmit="return confirm('Delete this configuration?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="px-3 py-1.5 text-xs bg-danger/10 text-danger rounded-lg hover:bg-danger/20">
                                                                <i class="fas fa-trash mr-1"></i>Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="py-10 text-center text-gray-500 dark:text-gray-400">
                                                    No configurations yet. Create the first one.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            @if($configs->hasPages())
                            <div class="mt-4">
                                {{ $configs->links() }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Activate Confirmation Modal -->
    <div id="activateModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-dark dark:text-white mb-2">Another configuration is already active</h3>
                <p id="activateModalText" class="text-gray-600 dark:text-gray-300 text-sm mb-6"></p>
                <form id="activateModalForm" method="POST">
                    @csrf
                    <input type="hidden" name="mode" id="activateModalMode" value="single">
                    <div class="flex flex-col gap-3">
                        <button type="button" onclick="submitActivateModal('single')" class="w-full px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark">
                            Deactivate others and activate this
                        </button>
                        <button type="button" onclick="submitActivateModal('both')" class="w-full px-4 py-2 bg-success/10 text-success rounded-lg hover:bg-success/20">
                            Keep both active
                        </button>
                        <button type="button" onclick="closeActivateModal()" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openActivateModal(configId, configName) {
            const modal = document.getElementById('activateModal');
            const form = document.getElementById('activateModalForm');
            const text = document.getElementById('activateModalText');
            form.action = `/exam-configurations/${configId}/activate`;
            text.textContent = `You already have an active configuration. Do you want to activate "${configName}" as well?`;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeActivateModal() {
            const modal = document.getElementById('activateModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function submitActivateModal(mode) {
            document.getElementById('activateModalMode').value = mode;
            document.getElementById('activateModalForm').submit();
        }
    </script>
</body>
</html>
