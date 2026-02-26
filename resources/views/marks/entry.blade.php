<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mark Entry | EduCare Pro</title>
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
 

        <div class="flex-1 overflow-y-auto">
            <header class="bg-white dark:bg-gray-800 shadow-sm py-4 px-6">
                <div>
                    <h2 class="text-xl font-semibold text-dark dark:text-white">Mark Entry</h2>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Select exam, class, and subject to enter marks</p>
                </div>
            </header>

            <main class="p-6 space-y-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 dashboard-card">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Select Exam</label>
                            <select id="examSelect" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-dark dark:text-white">
                                <option value="">Choose Exam</option>
                                @foreach($exams as $exam)
                                    <option value="{{ $exam->id }}">{{ $exam->name }} ({{ $exam->academic_year }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Select Subject</label>
                            <select id="subjectSelect" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-dark dark:text-white">
                                <option value="">Choose Subject</option>
                                @foreach($teacherSubjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }} ({{ $subject->level }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Select Class</label>
                            <select id="classSelect" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-dark dark:text-white">
                                <option value="">Choose Class</option>
                            </select>
                        </div>
                    </div>
                    <p id="selectionHint" class="text-sm text-gray-500 dark:text-gray-400 mt-4">Select subject and class to load students (exam optional for preview).</p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 dashboard-card">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-dark dark:text-white">Student Scores</h3>
                        <button id="saveAllBtn" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark disabled:opacity-50" disabled>
                            <i class="fas fa-save mr-2"></i>Save All
                        </button>
                    </div>

                    <div id="tableState" class="text-sm text-gray-500 dark:text-gray-400">No students loaded yet.</div>

                    <div class="overflow-x-auto mt-4">
                        <table class="w-full">
                            <thead>
                                <tr class="text-left text-gray-500 dark:text-gray-400 text-sm border-b dark:border-gray-700">
                                    <th class="pb-3 font-medium">Student Name</th>
                                    <th class="pb-3 font-medium">Registration No.</th>
                                    <th class="pb-3 font-medium">Score</th>
                                </tr>
                            </thead>
                            <tbody id="studentsTableBody" class="divide-y divide-gray-100 dark:divide-gray-700"></tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        const examSelect = document.getElementById('examSelect');
        const classSelect = document.getElementById('classSelect');
        const subjectSelect = document.getElementById('subjectSelect');
        const tableBody = document.getElementById('studentsTableBody');
        const tableState = document.getElementById('tableState');
        const saveAllBtn = document.getElementById('saveAllBtn');

        function canLoad() {
            // Allow loading students when a class is selected. Subject is optional (will filter results).
            return !!classSelect.value;
        }

        async function loadStudents() {
            if (!canLoad()) {
                tableBody.innerHTML = '';
                tableState.textContent = 'Select subject and class to load students.';
                saveAllBtn.disabled = true;
                return;
            }

            tableState.textContent = 'Loading students...';
            tableBody.innerHTML = '';
            saveAllBtn.disabled = true;

            const params = new URLSearchParams({
                class_id: classSelect.value,
                subject_id: subjectSelect.value,
            });
            if (examSelect.value) {
                params.set('exam_id', examSelect.value);
            }

            const response = await fetch(`{{ route('marks.students') }}?${params.toString()}`);
            const payload = await response.json();

            if (!response.ok || payload.status !== 'success') {
                tableState.textContent = 'Failed to load students.';
                return;
            }

            const students = payload.data || [];
            if (students.length === 0) {
                tableState.textContent = 'No students found for this class and subject.';
                return;
            }

            tableState.textContent = '';
            students.forEach(student => {
                const existingScore = student.results && student.results.length > 0
                    ? student.results[0].score
                    : '';

                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="py-3 text-dark dark:text-white font-medium">${student.first_name} ${student.last_name}</td>
                    <td class="py-3 text-gray-600 dark:text-gray-300 font-mono">${student.registration_number ?? ''}</td>
                    <td class="py-3">
                        <input type="number" step="0.01" min="0" max="100"
                               class="score-input w-32 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-dark dark:text-white"
                               data-student-id="${student.id}"
                               value="${existingScore ?? ''}">
                    </td>
                `;
                tableBody.appendChild(row);
            });

            // Enable Save only if an exam is selected (saving requires an exam)
            saveAllBtn.disabled = !examSelect.value;
        }

        async function saveAll() {
            const inputs = Array.from(document.querySelectorAll('.score-input'));
            if (inputs.length === 0) {
                return;
            }

            const marks = [];
            let hasEmpty = false;

            inputs.forEach(input => {
                const value = input.value.trim();
                if (value === '') {
                    hasEmpty = true;
                }
                marks.push({
                    student_id: parseInt(input.dataset.studentId, 10),
                    score: value === '' ? null : parseFloat(value),
                });
            });

            if (hasEmpty) {
                alert('Please fill all scores before saving.');
                return;
            }

            if (!examSelect.value) {
                alert('Please select an exam before saving marks.');
                return;
            }

            const response = await fetch(`{{ route('marks.store') }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    exam_id: examSelect.value,
                    subject_id: subjectSelect.value,
                    marks: marks
                })
            });

            const payload = await response.json();
            if (!response.ok) {
                alert(payload.message || 'Failed to save marks.');
                return;
            }

            alert(payload.message || 'Marks saved.');
        }

        async function loadClassesForSubject() {
            const subjectId = subjectSelect.value;
            classSelect.innerHTML = '<option value="">Choose Class</option>';
            
            if (!subjectId) {
                return;
            }

            const response = await fetch(`{{ route('marks.classes_for_subject') }}?subject_id=${subjectId}`);
            const payload = await response.json();

            if (!response.ok || payload.status !== 'success') {
                console.error('Failed to load classes:', payload.message);
                return;
            }

            const classes = payload.data || [];
            classes.forEach(cls => {
                const option = document.createElement('option');
                option.value = cls.id;
                option.textContent = cls.name;
                classSelect.appendChild(option);
            });
        }

        examSelect.addEventListener('change', loadStudents);
        classSelect.addEventListener('change', loadStudents);
        subjectSelect.addEventListener('change', async () => {
            // Wait for classes to be loaded (if subject change affects classes)
            await loadClassesForSubject();
            // Then refresh student list (will filter by subject if class selected)
            loadStudents();
        });
        saveAllBtn.addEventListener('click', saveAll);
    </script>
</body>
</html>
