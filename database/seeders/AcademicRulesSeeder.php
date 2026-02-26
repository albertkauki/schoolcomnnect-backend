<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GradingScale;
use App\Models\DivisionScale;

class AcademicRulesSeeder extends Seeder
{
    public function run(): void
    {
        // --- O-LEVEL GRADES ---
        $oGrades = [
            ['grade' => 'A', 'definition' => 'Excellent', 'min' => 75, 'max' => 100, 'points' => 1],
            ['grade' => 'B', 'definition' => 'Very Good', 'min' => 65, 'max' => 74, 'points' => 2],
            ['grade' => 'C', 'definition' => 'Good', 'min' => 45, 'max' => 64, 'points' => 3],
            ['grade' => 'D', 'definition' => 'Satisfactory', 'min' => 30, 'max' => 44, 'points' => 4],
            ['grade' => 'F', 'definition' => 'Fail', 'min' => 0, 'max' => 29, 'points' => 5],
        ];

        foreach ($oGrades as $g) {
            GradingScale::create([
                'level' => 'O-Level',
                'grade' => $g['grade'],
                'definition' => $g['definition'],
                'min_score' => $g['min'],
                'max_score' => $g['max'],
                'points' => $g['points'],
            ]);
        }

        // --- O-LEVEL DIVISIONS ---
        $oDivs = [
            ['div' => 'I', 'min' => 7, 'max' => 17],
            ['div' => 'II', 'min' => 18, 'max' => 21],
            ['div' => 'III', 'min' => 22, 'max' => 25],
            ['div' => 'IV', 'min' => 26, 'max' => 33],
            ['div' => '0', 'min' => 34, 'max' => 35],
        ];

        foreach ($oDivs as $d) {
            DivisionScale::create([
                'level' => 'O-Level',
                'division' => $d['div'],
                'min_points' => $d['min'],
                'max_points' => $d['max'],
                'description' => "Division " . $d['div']
            ]);
        }

        // --- A-LEVEL GRADES ---
        $aGrades = [
            ['grade' => 'A', 'definition' => 'Excellent', 'min' => 80, 'max' => 100, 'points' => 1],
            ['grade' => 'B', 'definition' => 'Very Good', 'min' => 70, 'max' => 79, 'points' => 2],
            ['grade' => 'C', 'definition' => 'Good', 'min' => 60, 'max' => 69, 'points' => 3],
            ['grade' => 'D', 'definition' => 'Average', 'min' => 50, 'max' => 59, 'points' => 4],
            ['grade' => 'E', 'definition' => 'Satisfactory', 'min' => 40, 'max' => 49, 'points' => 5],
            ['grade' => 'S', 'definition' => 'Subsidiary', 'min' => 35, 'max' => 39, 'points' => 6],
            ['grade' => 'F', 'definition' => 'Fail', 'min' => 0, 'max' => 34, 'points' => 7],
        ];

        foreach ($aGrades as $g) {
            GradingScale::create([
                'level' => 'A-Level',
                'grade' => $g['grade'],
                'definition' => $g['definition'],
                'min_score' => $g['min'],
                'max_score' => $g['max'],
                'points' => $g['points'],
            ]);
        }

        // --- A-LEVEL DIVISIONS ---
        $aDivs = [
            ['div' => 'I', 'min' => 3, 'max' => 9],
            ['div' => 'II', 'min' => 10, 'max' => 12],
            ['div' => 'III', 'min' => 13, 'max' => 17],
            ['div' => 'IV', 'min' => 18, 'max' => 19],
            ['div' => '0', 'min' => 20, 'max' => 21],
        ];

        foreach ($aDivs as $d) {
            DivisionScale::create([
                'level' => 'A-Level',
                'division' => $d['div'],
                'min_points' => $d['min'],
                'max_points' => $d['max'],
                'description' => "Division " . $d['div']
            ]);
        }
    }
}