<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Combination;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{
  /**
     * Display the Management Screen
     */
   public function index()
    {
        // 1. Get all subjects for the "Subject Registry" table
        $subjects = Subject::orderBy('level')->orderBy('name')->get();

        // 2. Get all combinations for the "Combination Registry" table
        // We load subjects with their pivot 'type' to display Principals vs Subsidiaries
        $combinations = Combination::with('subjects')->latest()->get();

        // 3. Get A-Level subjects specifically for the "Combination Builder" dropdowns
        $aLevelSubjects = Subject::where('level', 'A-Level')->orderBy('name')->get();

        return view('subjects.subjects', compact('subjects', 'combinations', 'aLevelSubjects'));
    }

    /**
     * Save a New Subject
     */
    public function storeSubject(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject_code' => 'required|string|unique:subjects,subject_code',
            'level' => 'required|in:O-Level,A-Level',
            'category' => 'required|in:core,elective,vocational',
        ]);

        Subject::create($validated);

        return back()->with('success', 'Subject registered successfully!');
    }

    /**
     * Build and Save a Combination
     */
    public function storeCombination(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:combinations,name',
            'principal_ids' => 'required|array|size:3', // Must pick exactly 3
            'subsidiary_ids' => 'nullable|array',
        ]);

        DB::transaction(function () use ($request) {
            // 1. Create the Combination name (e.g., PCM)
            $combination = Combination::create(['name' => $request->name]);

            // 2. Attach the 3 Principals
            $combination->subjects()->attach($request->principal_ids, ['type' => 'principal']);

            // 3. Attach Subsidiaries (GS, BAM, etc.) if selected
            if ($request->subsidiary_ids) {
                $combination->subjects()->attach($request->subsidiary_ids, ['type' => 'subsidiary']);
            }
        });

        return back()->with('success', "Combination {$request->name} created successfully!");
    }
}
