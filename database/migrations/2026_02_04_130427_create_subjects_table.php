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
        Schema::create('subjects', function (Blueprint $table) {
    $table->id();
    $table->string('name'); // e.g., Physics
    $table->string('subject_code')->unique(); // e.g., PHY-OL or PHY-AL
    $table->enum('level', ['O-Level', 'A-Level']);
    $table->enum('category', ['core', 'elective', 'vocation'])->default('core');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
