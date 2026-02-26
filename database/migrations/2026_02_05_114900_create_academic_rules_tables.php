<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Grading Scales (Score -> Grade)
        Schema::create('grading_scales', function (Blueprint $table) {
            $table->id();
            // Enum for level is perfect: strict and safe
            $table->enum('level', ['A-Level', 'O-Level']); 
            
            // String for grade: flexible for A, B, B+, S, etc.
            $table->string('grade', 5);      
            
            $table->string('definition');    // Excellent, Good, etc.
            $table->integer('min_score');    // e.g., 75
            $table->integer('max_score');    // e.g., 100
            $table->integer('points');       // NECTA Points (1, 2, 3...)
            $table->timestamps();
        });

        // 2. Division Scales (Points -> Division)
        Schema::create('division_scales', function (Blueprint $table) {
            $table->id();
            $table->enum('level', ['o-level', 'a-level']);
            
            // String for division: handles 'I', 'II', 'IV', or '0'
            $table->string('division', 10);   
            
            $table->integer('min_points');   // e.g., 7
            $table->integer('max_points');   // e.g., 17
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grading_scales');
        Schema::dropIfExists('division_scales');
    }
};