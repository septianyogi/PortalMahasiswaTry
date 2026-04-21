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
        Schema::create('class_attendeds', function (Blueprint $table) {
            $table->id();

            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->timestamp('verified_at')->nullable();

            $table->integer('attendance')->default(0);
            $table->integer('absent')->default(0);

            $table->integer('mid_exam')->default(0);
            $table->integer('final_exam')->default(0);
            $table->integer('final_score')->default(0);

            $table->timestamps();

            $table->unique(['class_id', 'student_id']);

            $table->index('student_id');
            $table->index('class_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_attendeds');
    }
};
