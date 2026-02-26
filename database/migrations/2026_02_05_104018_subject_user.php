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
    Schema::create('subject_user', function (Blueprint $table) {
        $table->id();
        
        // The Teacher
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        
        // The Subject
        $table->foreignId('subject_id')->constrained()->onDelete('cascade');
        
        // The Specific Class (Linking to your school_classes table)
        $table->foreignId('school_class_id')->constrained('school_classes')->onDelete('cascade');
        
        // Academic Year
        $table->string('academic_year')->nullable(); 
        
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subject_user');
    }
};
