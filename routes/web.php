<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VieweController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\AcademicRuleController;
use App\Http\Controllers\SubjectAssignmentController;
use App\Http\Controllers\ExamconfigurationController;
use App\Http\Controllers\MarkEntryController;

// Auth Routes
// Root should go to dashboard; unauthenticated users will be redirected
Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/login', [VieweController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes (require authentication)
Route::middleware([\App\Http\Middleware\EnsureAuthenticated::class])->group(function () {
    // Dashboard
    Route::get('/dashboard', [VieweController::class, 'showDashboard'])->name('dashboard');
    Route::get('/rules', [VieweController::class, 'showRules'])->name('showRules');

// Student Routes - FIXED ORDER (specific routes BEFORE dynamic routes)
Route::get('/students', [StudentController::class, 'index'])->name('showStudents');
Route::get('/students/register', [StudentController::class, 'showStudentRegister'])->name('students.register'); // MOVED BEFORE {student}
Route::post('/students', [StudentController::class, 'store'])->name('students.store'); // Changed from /students/register
Route::get('/students/{student}', [StudentController::class, 'show'])->name('students.show');
Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update');
Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy'); // ADDED - was missing!

// Subject Assignment Routes for Students - MUST come BEFORE /students/{student} or use different path
Route::get('/students/{student}/all-subjects', [SubjectAssignmentController::class, 'getAllAssignedSubjects'])->name('subjects.allAssigned');
Route::get('/students/{student}/available-electives', [SubjectAssignmentController::class, 'getAvailableElectives'])->name('subjects.availableElectives');
Route::get('/students/{student}/assigned-electives', [SubjectAssignmentController::class, 'getAssignedElectives'])->name('subjects.assignedElectives');
Route::post('/subjects/sync-core', [SubjectAssignmentController::class, 'syncCoreSubjects'])->name('subjects.syncCore'); // Changed path
Route::post('/subjects/attach-elective', [SubjectAssignmentController::class, 'attachElective'])->name('subjects.attachElective'); // Changed path
Route::post('/subjects/detach-elective', [SubjectAssignmentController::class, 'detachElective'])->name('subjects.detachElective'); // Changed path

    // Teacher Routes (admin-only)
    Route::get('/teachers', [TeacherController::class, 'index'])->name('showTeachers')
        ->middleware(\App\Http\Middleware\EnsureAdmin::class);
    Route::post('/teachers', [TeacherController::class, 'store'])->name('teachers.store')
        ->middleware(\App\Http\Middleware\EnsureAdmin::class);
    Route::post('/teachers/{teacher}/subjects', [TeacherController::class, 'updateSubjects'])->name('teachers.subjects.update')
        ->middleware(\App\Http\Middleware\EnsureAdmin::class);

    // Subject Routes (admin-only for create)
    Route::get('/subjects', [SubjectController::class, 'index'])->name('showSubjects');
    Route::post('/subjects', [SubjectController::class, 'storeSubject'])->name('subjects.store')
        ->middleware(\App\Http\Middleware\EnsureAdmin::class);
    Route::post('/combinations', [SubjectController::class, 'storeCombination'])->name('combinations.store')
        ->middleware(\App\Http\Middleware\EnsureAdmin::class);

    // Class Routes
    Route::get('/classes', [SchoolClassController::class, 'index'])->name('showClasses');
    Route::post('/classes', [SchoolClassController::class, 'store'])->name('classes.store')
        ->middleware(\App\Http\Middleware\EnsureAdmin::class);

    // Academic Rules Routes (admin-only for changes)
    Route::get('/academic-rules', [AcademicRuleController::class, 'index'])->name('academic-rules.index');
    Route::post('/academic-rules/save', [AcademicRuleController::class, 'store'])->name('academic-rules.store')
        ->middleware(\App\Http\Middleware\EnsureAdmin::class);
    Route::get('/exam-configurations', [ExamconfigurationController::class, 'index'])->name('examconfigurations.index');
    Route::post('/exam-configurations', [ExamconfigurationController::class, 'store'])->name('examconfigurations.store')
        ->middleware(\App\Http\Middleware\EnsureAdmin::class);
    Route::get('/exam-configurations/{examconfiguration}/edit', [ExamconfigurationController::class, 'edit'])->name('examconfigurations.edit')
        ->middleware(\App\Http\Middleware\EnsureAdmin::class);
    Route::put('/exam-configurations/{examconfiguration}', [ExamconfigurationController::class, 'update'])->name('examconfigurations.update')
        ->middleware(\App\Http\Middleware\EnsureAdmin::class);
    Route::delete('/exam-configurations/{examconfiguration}', [ExamconfigurationController::class, 'destroy'])->name('examconfigurations.destroy')
        ->middleware(\App\Http\Middleware\EnsureAdmin::class);
    Route::post('/exam-configurations/{examconfiguration}/activate', [ExamconfigurationController::class, 'activate'])->name('examconfigurations.activate')
        ->middleware(\App\Http\Middleware\EnsureAdmin::class);

    // Marks: accessible to teachers and admins
    Route::prefix('marks')->name('marks.')->group(function () {
        Route::get('/entry', [MarkEntryController::class, 'index'])
            ->name('entry')
            ->middleware(\App\Http\Middleware\EnsureTeacher::class);
        Route::get('/classes-for-subject', [MarkEntryController::class, 'getClassesForSubject'])
            ->name('classes_for_subject')
            ->middleware(\App\Http\Middleware\EnsureTeacher::class);
        Route::get('/students', [MarkEntryController::class, 'getStudentList'])
            ->name('students')
            ->middleware(\App\Http\Middleware\EnsureTeacher::class);
        Route::post('/store', [MarkEntryController::class, 'storeMarks'])
            ->name('store')
            ->middleware(\App\Http\Middleware\EnsureTeacher::class);
    });

});
