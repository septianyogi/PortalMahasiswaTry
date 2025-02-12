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
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->foreignId('jurusan_id')->references('id')->on('jurusans');
            $table->string('name');
            $table->string('date');
            $table->time('time_start');
            $table->time('time_end');
            $table->bigInteger('dosen_id');
            $table->foreign('dosen_id')->references('nip')->on('dosens');
            $table->integer('quota');
            $table->string('room')->nullable();
            $table->string('semester');
            $table->string('attendance')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
