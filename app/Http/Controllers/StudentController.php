<?php
namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\SchoolClass; // Added this
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    /**
     * Display a listing of students with filters.
     */
    public function index(Request $request)
    {
        $query = Student::with('schoolClass'); // Eager load the class details

        // 1. Filter by specific Class ID
        if ($request->filled('school_class_id')) {
            $query->where('school_class_id', $request->school_class_id);
        }

        // 2. Filter by Gender
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        // 3. Search by Name or Reg Number
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('registration_number', 'like', "%{$search}%");
            });
        }

        return view('students.students', [
            'students' => $query->latest()->paginate(20)->withQueryString(),
            'classes' => SchoolClass::orderBy('form')->get() // For the filter dropdown
        ]);
    }
     public function showStudentRegister()
    {
        return view('students.register', [

            'classes' => SchoolClass::orderBy('form')->get() // For the filter dropdown
        ]);
    }

    /**
     * Get student details as JSON (for modal)
     */
    public function show(Student $student)
    {
        return response()->json($student->load('schoolClass', 'subjects'));
    }

    public function edit(Student $student)
    {
        return view('students.edit', [
            'student' => $student,
            'classes' => SchoolClass::orderBy('form')->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:50',
            'middle_name' => 'nullable|string|max:50',
            'last_name' => 'required|string|max:50',
            'gender' => 'required|in:Male,Female',
            'date_of_birth' => 'required|date',
            
            // THE FIX: Single validation for the class link
            'school_class_id' => 'required|exists:school_classes,id',
            
            'prem_number' => 'nullable|unique:students,prem_number',
            'necta_index_number' => 'nullable|unique:students,necta_index_number',
            'parent_name' => 'nullable|string|max:100',
            'parent_phone' => 'nullable|string|min:10',
        ]);

        try {
            return DB::transaction(function () use ($request, $validated) {
                
                $student = Student::create($validated);

                // Handle "Add another" logic
                if ($request->has('add_another') && $request->add_another == "1") {
                    return redirect()->back()
                        ->with('success', "Student {$student->registration_number} registered!")
                        ->withInput($request->only(['school_class_id', 'gender'])); 
                        // Just keep the class and gender for the next student entry
                }

                return redirect()->route('students.index')->with('success', 'Student registered successfully.');
            });

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to save: ' . $e->getMessage()])->withInput();
        }
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:50',
            'middle_name' => 'nullable|string|max:50',
            'last_name' => 'required|string|max:50',
            'gender' => 'required|in:Male,Female',
            'date_of_birth' => 'required|date',
            'admission_date' => 'nullable|date',
            'school_class_id' => 'required|exists:school_classes,id',
            'prem_number' => [
                'nullable',
                Rule::unique('students', 'prem_number')->ignore($student->id)
            ],
            'necta_index_number' => [
                'nullable',
                Rule::unique('students', 'necta_index_number')->ignore($student->id)
            ],
            'parent_name' => 'nullable|string|max:100',
            'parent_phone' => 'nullable|string|min:10',
        ]);

        try {
            $student->update($validated);
            return redirect()->route('showStudents')->with('success', 'Student updated successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update: ' . $e->getMessage()])->withInput();
        }
    }
}
