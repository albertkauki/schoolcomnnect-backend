<?php
$require = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($require)) {
    echo "Cannot find vendor/autoload.php. Run composer install.\n";
    exit(1);
}
require $require;
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$rows = DB::table('subject_student')->limit(50)->get();
if ($rows->isEmpty()) {
    echo "subject_student is empty\n";
    exit(0);
}
foreach ($rows as $r) {
    echo "id={$r->id} student_id={$r->student_id} subject_id={$r->subject_id} created_at={$r->created_at}\n";
}
