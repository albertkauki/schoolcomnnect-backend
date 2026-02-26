<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubjectAssignmentController extends Controller
{
    /**
     * Sync all core subjects of a student's level to the student.
     * Useful for bulk assignment of required subjects.
     */
    public function syncCoreSubjects(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $student = Student::with('schoolClass')->findOrFail($request->student_id);
                
                // Get the level from the student's school class
                $level = $student->schoolClass->level;
                
                // Fetch all core subjects for this level
                $coreSubjects = Subject::where('level', $level)
                                      ->where('category', 'core')
                                      ->pluck('id');

                // Sync: attach all core subjects, detach others
                $student->subjects()->sync($coreSubjects);
            });

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Core subjects assigned successfully!']);
            }
            return back()->with('success', 'Core subjects assigned successfully!');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Failed to assign core subjects: ' . $e->getMessage()], 400);
            }
            return back()->withErrors(['error' => 'Failed to assign core subjects: ' . $e->getMessage()]);
        }
    }

    /**
     * Attach a specific elective subject to a student.
     */
    public function attachElective(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $student = Student::with('schoolClass')->findOrFail($request->student_id);
                $subject = Subject::findOrFail($request->subject_id);

                // Verify subject is elective and matches student's level
                if ($subject->category !== 'elective') {
                    throw new \Exception('Subject must be marked as elective.');
                }

                if ($subject->level !== $student->schoolClass->level) {
                    throw new \Exception('Subject level does not match student level.');
                }

                // Attach if not already attached
                $student->subjects()->syncWithoutDetaching($request->subject_id);
            });

            return response()->json(['success' => true, 'message' => 'Elective subject added!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to add elective: ' . $e->getMessage()], 400);
        }
    }

    /**
     * Detach a specific elective subject from a student.
     */
    public function detachElective(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $student = Student::findOrFail($request->student_id);
                
                // Detach the subject
                $student->subjects()->detach($request->subject_id);
            });

            return response()->json(['success' => true, 'message' => 'Elective subject removed!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to remove elective: ' . $e->getMessage()], 400);
        }
    }

    /**
     * Get available electives for a student (by level, excluding already assigned).
     */
    public function getAvailableElectives(Student $student)
    {
        $level = $student->schoolClass->level;
        
        $available = Subject::where('level', $level)
                            ->where('category', 'elective')
                            ->whereNotIn('id', $student->subjects()->pluck('subjects.id'))
                            ->get();

        return response()->json($available);
    }

    /**
     * Get assigned electives for a student (for UI display).
     */
    public function getAssignedElectives(Student $student)
    {
        $assigned = $student->subjects()
                            ->where('category', 'elective')
                            ->get();

        return response()->json($assigned);
    }

    /**
     * Get all assigned subjects (core + electives) for a student.
     */
    public function getAllAssignedSubjects(Student $student)
    {
        $assigned = $student->subjects()->get();
        return response()->json($assigned);
    }
}
