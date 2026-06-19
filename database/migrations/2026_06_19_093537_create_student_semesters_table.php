<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_semesters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->integer('semester_number');
            $table->float('gpa')->nullable();
            $table->integer('credits')->nullable();
            $table->enum('status', ['active', 'completed', 'dropped'])->default('active');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'semester_number']);
            $table->index('student_id');
            $table->index('semester_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_semesters');
    }
};