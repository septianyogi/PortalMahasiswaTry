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
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->foreignId('jurusan_id')->references('id')->on('jurusans');
            $table->string('name');
            $table->string('date');
            $table->time('time_start');
            $table->time('time_end');
            $table->foreignId('dosen_id')->references('id')->on('dosens');
            $table->integer('quota');
            $table->integer('current_quota')->default(0);
            $table->string('room')->nullable();
            $table->string('semester');
            $table->timestamps();

            $table->index('jurusan_id');
            $table->index('dosen_id');
            $table->index('quota');
            $table->index('current_quota');
            $table->index(['jurusan_id', 'semester']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
