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
        Schema::create('teacher_subject_years', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teacher_id')->constrained('teachers')->cascadeOnDelete();
            $table->unsignedBigInteger('subject_year_id')->constrained('subject_year')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_subject_years');
    }
};
