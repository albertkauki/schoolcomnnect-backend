<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\examconfiguration;

$exams = examconfiguration::all();
if ($exams->isEmpty()) {
    echo "No exams\n";
    exit(0);
}
foreach ($exams as $e) {
    echo "id={$e->id} name={$e->name} active={$e->is_active} year={$e->academic_year}\n";
}
