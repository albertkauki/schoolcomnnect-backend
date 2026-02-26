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
        Schema::create('examconfigurations', function (Blueprint $table) {
           $table->id();
    $table->string('name'); // e.g., "Monthly Test 1", "Mid-Term", "Terminal"
    $table->string('slug'); // e.g., "monthly-test-1" (This is what we use in the code)
    $table->integer('term'); // 1 or 2
    $table->string('academic_year'); // 2026
    $table->decimal('weight', 5, 2); // e.g., 20.00 (This exam is 20% of the total)
    $table->boolean('is_active')->default(false);
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations
     */
    public function down(): void
    {
        Schema::dropIfExists('examconfigurations');
    }
};
