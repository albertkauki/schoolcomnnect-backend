<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\examconfiguration;

class ExamconfigurationController extends Controller
{
    public function index()
    {
        return view('exams.examconfigurations', [
            'configs' => examconfiguration::orderBy('academic_year', 'desc')
                ->orderBy('term')
                ->orderBy('name')
                ->paginate(10),
            'activeCount' => examconfiguration::where('is_active', true)->count(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'term' => 'required|integer|min:1|max:2',
            'academic_year' => 'required|string|max:20',
            'weight' => 'required|numeric|min:0|max:100',
            'is_active' => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        try {
            examconfiguration::create($validated);
            return back()->with('success', 'Exam configuration saved successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to save configuration: ' . $e->getMessage()])->withInput();
        }
    }

    public function edit(examconfiguration $examconfiguration)
    {
        return view('exams.examconfigurations_edit', [
            'config' => $examconfiguration
        ]);
    }

    public function update(Request $request, examconfiguration $examconfiguration)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'term' => 'required|integer|min:1|max:2',
            'academic_year' => 'required|string|max:20',
            'weight' => 'required|numeric|min:0|max:100',
            'is_active' => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        try {
            $examconfiguration->update($validated);
            return redirect()->route('examconfigurations.index')->with('success', 'Exam configuration updated successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update configuration: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy(examconfiguration $examconfiguration)
    {
        try {
            $examconfiguration->delete();
            return back()->with('success', 'Exam configuration deleted successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete configuration: ' . $e->getMessage()]);
        }
    }

    public function activate(Request $request, examconfiguration $examconfiguration)
    {
        $mode = $request->input('mode', 'single'); // single | both | deactivate

        try {
            if ($mode === 'deactivate') {
                $examconfiguration->update(['is_active' => false]);
                return back()->with('success', 'Exam configuration deactivated.');
            }

            if ($mode === 'single') {
                examconfiguration::where('is_active', true)->where('id', '!=', $examconfiguration->id)
                    ->update(['is_active' => false]);
            }

            $examconfiguration->update(['is_active' => true]);
            return back()->with('success', 'Exam configuration activated.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update status: ' . $e->getMessage()]);
        }
    }
}
