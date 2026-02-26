<?php
namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Subject;
use App\Models\GradingScale;
use App\Models\examconfiguration;
use App\Models\Results;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MarkEntryController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Get only active exams
        $exams = examconfiguration::where('is_active', true)
            ->orderBy('academic_year', 'desc')
            ->orderBy('term')
            ->orderBy('name')
            ->get();
        
        $teacherSubjects = $user->subjects()
            ->with(['schoolClasses' => function($q) use ($user) {
                $q->whereHas('teachers', function($q2) use ($user) {
                    $q2->where('user_id', $user->id);
                });
            }])
            ->orderBy('name')
            ->get();
        
        return view('marks.entry', [
            'exams' => $exams,
            'teacherSubjects' => $teacherSubjects,
        ]);
    }

    public function getClassesForSubject(Request $request)
    {
        try {
            $request->validate([
                'subject_id' => 'required|exists:subjects,id',
            ]);

            $user = auth()->user();
            $subjectId = $request->subject_id;

            $classes = SchoolClass::whereHas('teachers', function($q) use ($user, $subjectId) {
                $q->where('user_id', $user->id)
                  ->where('subject_id', $subjectId);
            })
            ->orderBy('form')
            ->orderBy('stream')
            ->get(['id', 'name', 'form', 'stream']); // Only get needed fields

            return response()->json([
                'status' => 'success',
                'data' => $classes
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getClassesForSubject: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to load classes.'
            ], 500);
        }
    }

    public function getStudentList(Request $request)
    {
        try {
            $request->validate([
                'subject_id' => 'required|exists:subjects,id',
                'class_id' => 'required|exists:school_classes,id',
                'exam_id' => 'nullable|exists:examconfigurations,id',
            ]);

            $user = auth()->user();
            $subjectId = $request->subject_id;
            $classId = $request->class_id;
            $examConfigId = $request->exam_id;

            // Verify teacher is assigned to this subject in this class
            $isAssigned = DB::table('subject_user')
                ->where('user_id', $user->id)
                ->where('subject_id', $subjectId)
                ->where('school_class_id', $classId)
                ->exists();

            if (!$isAssigned) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You are not assigned to teach this subject in this class.'
                ], 403);
            }

            // Fetch students who are in this class AND assigned to this subject
            $students = Student::where('school_class_id', $classId)
                ->whereHas('subjects', function($q) use ($subjectId) {
                    $q->where('subjects.id', $subjectId);
                })
                ->with(['results' => function($q) use ($subjectId, $examConfigId) {
                    $q->where('subject_id', $subjectId);
                    if ($examConfigId) {
                        $q->where('examconfiguration_id', $examConfigId);
                    }
                }])
                ->orderBy('first_name')
                ->orderBy('last_name')
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $students
            ]);

        } catch (\Exception $e) {
            Log::error('Error in getStudentList: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to load students.'
            ], 500);
        }
    }

    public function storeMarks(Request $request)
    {
        try {
            $request->validate([
                'exam_id' => 'required|exists:examconfigurations,id',
                'subject_id' => 'required|exists:subjects,id',
                'marks' => 'required|array',
                'marks.*.student_id' => 'required|exists:students,id',
                'marks.*.score' => 'required|numeric|min:0|max:100',
            ]);

            $user = auth()->user();
            $subjectId = $request->subject_id;
            
            // Get the class_id from the first student to verify teacher assignment
            $firstStudent = Student::find($request->marks[0]['student_id']);
            
            // Verify teacher is assigned to this subject
            $isAssigned = DB::table('subject_user')
                ->where('user_id', $user->id)
                ->where('subject_id', $subjectId)
                ->where('school_class_id', $firstStudent->school_class_id)
                ->exists();

            if (!$isAssigned) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You are not authorized to enter marks for this subject.'
                ], 403);
            }

            $exam = examconfiguration::findOrFail($request->exam_id);
            $subject = Subject::findOrFail($request->subject_id);

            DB::transaction(function () use ($request, $subject) {
                foreach ($request->marks as $entry) {
                    $score = $entry['score'];

                    // Auto-calculate Grade and Points
                    $gradeData = GradingScale::where('level', $subject->level)
                        ->where('min_score', '<=', $score)
                        ->where('max_score', '>=', $score)
                        ->first();

                    Results::updateOrCreate(
                        [
                            'student_id' => $entry['student_id'],
                            'subject_id' => $subject->id,
                            'examconfiguration_id' => $request->exam_id,
                        ],
                        [
                            'score' => $score,
                            'grade' => $gradeData->grade ?? 'F',
                            'points' => $gradeData->points ?? 9,
                        ]
                    );
                }
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Marks saved successfully!'
         
            ]);

        } catch (\Exception $e) {
            Log::error('Error in storeMarks: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to save marks.'
            ], 500);
        }
    }
}