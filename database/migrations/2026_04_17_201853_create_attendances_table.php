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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();

            $table->foreignId('session_id')->constrained('class_sessions')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('class_id')->constrained('classes');
            $table->integer('week');

            $table->enum('status', ['hadir', 'absent', 'izin'])->default('absent');
            $table->dateTime('scanned_at')->nullable();

            $table->timestamps();

            // prevent double scan
            $table->unique(['session_id', 'student_id']);
            $table->index('student_id');
            $table->index('session_id');
            $table->index('class_id');
            $table->index('status');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
