<?php

namespace App\Http\Controllers;

use App\Models\GradingScale;
use App\Models\DivisionScale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcademicRuleController extends Controller
{
    /**
     * Show the Academic Rules Management page
     */
    public function index()
    {
        // Fetch all rules grouped by Level
        $oLevelGrades = GradingScale::where('level', 'O-Level')->orderBy('points', 'asc')->get();
        $aLevelGrades = GradingScale::where('level', 'A-Level')->orderBy('points', 'asc')->get();
        
        $oLevelDivisions = DivisionScale::where('level', 'O-Level')->orderBy('min_points', 'asc')->get();
        $aLevelDivisions = DivisionScale::where('level', 'A-Level')->orderBy('min_points', 'asc')->get();

        return view('rules.rules', compact(
            'oLevelGrades', 
            'aLevelGrades', 
            'oLevelDivisions', 
            'aLevelDivisions'
        ));
    }

    /**
     * Save all changes from the UI
     */
   public function store(Request $request)
{
    // Validate the incoming arrays to be safe
    $request->validate([
        'grades' => 'required|array',
        'divisions' => 'required|array',
    ]);

    DB::transaction(function () use ($request) {
        // 1. Process Grades
        // Each $g has {id, field, value}
        foreach ($request->grades as $g) {
            GradingScale::where('id', $g['id'])->update([
                $g['field'] => $g['value']
            ]);
        }

        // 2. Process Divisions
        // Each $d has {id, field, value}
        foreach ($request->divisions as $d) {
            DivisionScale::where('id', $d['id'])->update([
                $d['field'] => $d['value']
            ]);
        }
    });

    return response()->json([
        'status' => 'success', 
        'message' => 'Academic rules updated in database!'
    ]);
}
}