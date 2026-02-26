<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
     Schema::create('results', function (Blueprint $table) {
    $table->id();
    $table->foreignId('student_id')->constrained()->onDelete('cascade');
    $table->foreignId('subject_id')->constrained()->onDelete('cascade');
    $table->foreignId('examconfiguration_id')->constrained('examconfigurations')->onDelete('cascade');
    
    $table->decimal('score', 5, 2);     // Raw score (e.g., 78.5)
    $table->string('grade', 2)->nullable();  // Calculated Grade (A, B, C...)
    $table->integer('points')->nullable();   // NECTA Points (1, 2, 3...)
    
    $table->timestamps();

    // Prevent a student from having two scores for the same subject in one exam
    $table->unique(['student_id', 'subject_id', 'examconfiguration_id'], 'unique_student_result');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
