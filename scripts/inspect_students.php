<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Student;

$students = Student::with('subjects')->limit(20)->get();
if ($students->isEmpty()) {
    echo "No students\n";
    exit(0);
}
foreach ($students as $s) {
    $subjectIds = $s->subjects->pluck('id')->join(',');
    echo "id={$s->id} name={$s->first_name} {$s->last_name} class_id={$s->school_class_id} subjects=[{$subjectIds}]\n";
}
