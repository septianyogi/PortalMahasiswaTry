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
            $table->foreignId('class_id')->references('id')->on('kelas');
            $table->bigInteger('student_id');
            $table->foreign('student_id')->references('npm')->on('students');
            $table->integer('attendance')->nullable();
            $table->integer('absent')->nullable();
            $table->integer('assignment')->nullable();
            $table->integer('mid_exam')->nullable();
            $table->integer('final_exam')->nullable();
            $table->string('final_score')->nullable();
            $table->timestamps();
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
