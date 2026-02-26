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
        Schema::create('school_classes', function (Blueprint $table) {
          $table->id();
          $table->string('name')->unique(); 
          $table->enum('level', ['O-Level', 'A-Level']);
          $table->integer('form'); 
          $table->string('stream')->nullable(); // Optional stream
          $table->foreignId('combination_id')->nullable()->constrained()->onDelete('set null');
          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sSchool_classes');
    }
};
