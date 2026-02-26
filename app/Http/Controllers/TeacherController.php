<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Subject;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    public function index()
    {
        return view('teachers.teachers', [
            'teachers' => User::whereIn('role', ['class_teacher', 'academic'])
                ->with(['subjects' => function($query) {
                    $query->withPivot('school_class_id', 'academic_year');
                }])
                ->latest()
                ->paginate(10),
            'subjects' => Subject::orderBy('level')->orderBy('name')->get(),
            'classes' => SchoolClass::orderBy('form')->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name'   => 'required|string|max:255',
            'last_name'    => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'gender'       => 'required|in:male,female',
            'academic_year'=> 'required|string',
            
            // Validate the assignments array (min 1, max 2)
            'assignments'  => 'required|array|min:1|max:2',
            'assignments.*.subject_id' => 'required|exists:subjects,id',
            'assignments.*.school_class_id' => 'required|exists:school_classes,id',
        ]);

        try {
            return DB::transaction(function () use ($request) {
                // 1. Create the Teacher
                $teacher = User::create([
                    'first_name' => $request->first_name,
                    'last_name'  => $request->last_name,
                    'email'      => $request->email,
                    'gender'     => $request->gender,
                    'password'   => Hash::make('password123'),
                    'role'       => 'class_teacher',
                    'status'     => 'active',
                ]);

                // 2. Attach Subjects with their specific Class IDs
                foreach ($request->assignments as $assignment) {
                    $teacher->subjects()->attach($assignment['subject_id'], [
                        'school_class_id' => $assignment['school_class_id'],
                        'academic_year'   => $request->academic_year
                    ]);
                }

                return back()->with('success', "Teacher registered and assigned successfully!");
            });
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Registration failed: ' . $e->getMessage()]);
        }
    }
}
