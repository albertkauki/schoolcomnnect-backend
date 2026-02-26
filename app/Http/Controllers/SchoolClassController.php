<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Combination;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class SchoolClassController extends Controller
{
    public function index() {
        return view('classes.classes', [
            'classes' => SchoolClass::with('combination')->orderBy('form')->paginate(10),
            'combinations' => Combination::all(),
            'totalClasses' => SchoolClass::count(),
            'oLevelCount' => SchoolClass::where('level', 'O-Level')->count(),
            'aLevelCount' => SchoolClass::where('level', 'A-Level')->count(),
            'streamCount' => SchoolClass::whereNotNull('stream')->count(),
        ]);
    }

    public function store(Request $request) {
        $request->validate([
            'level' => 'required|in:O-Level,A-Level',
            'form' => 'required|integer|between:1,6',
            'stream' => 'nullable|string|max:10',
            'combination_id' => 'required_if:level,A-Level|nullable|exists:combinations,id',
        ]);

        try {
            DB::transaction(function () use ($request) {
                SchoolClass::create($request->all());
            });
            return back()->with('success', 'Class created successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'This class already exists or data is invalid.']);
        }
    }
}
