<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Student;

$subjectId = $argv[1] ?? 2;
$classId = $argv[2] ?? 2;

$students = Student::where('school_class_id', $classId)
    ->whereHas('subjects', function($q) use ($subjectId) {
        $q->where('subjects.id', $subjectId);
    })->get();

foreach ($students as $s) {
    echo "id={$s->id} name={$s->first_name} {$s->last_name} class_id={$s->school_class_id}\n";
}

echo "Total: " . $students->count() . "\n";
