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
    Schema::create('students', function (Blueprint $table) {
        $table->id();
        
        // Identification
        $table->string('registration_number')->unique();
        $table->string('prem_number')->unique()->nullable();
        $table->string('necta_index_number')->unique()->nullable();
        
        // Name details
        $table->string('first_name');
        $table->string('middle_name')->nullable();
        $table->string('last_name');
        
        // Personal Info
        $table->enum('gender', ['Male', 'Female']);
        $table->date('date_of_birth')->nullable();
        $table->string('religion')->nullable();
        
        // Location Details
        $table->string('home_region')->nullable();
        $table->string('home_district')->nullable();
        $table->string('ward')->nullable();
        
        // THE FIX: Link to the specific Class Room
        // This replaces level, form, stream, and combination strings
        $table->foreignId('school_class_id')->constrained('school_classes')->onDelete('cascade');
        
        // Parent/Guardian
        $table->string('parent_name')->nullable();
        $table->string('parent_phone')->nullable();
        
        $table->enum('status', ['active', 'transferred', 'graduated', 'dropped'])->default('active');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
