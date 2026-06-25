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
            $table->integer('credits');
            $table->foreignId('dosen_id')->references('id')->on('dosens');
            $table->integer('quota');
            $table->integer('current_quota')->default(0);
            $table->string('room')->nullable();
            $table->integer('semester');

            // Bobot total (dalam persen, total 100)
            $table->float('weight_assignment')->default(40);
            $table->float('weight_mid')->default(30);
            $table->float('weight_final')->default(30);

            // Bobot per assignment (masing-masing dalam persen)
            $table->float('weight_assignment_1')->default(10);
            $table->float('weight_assignment_2')->default(10);
            $table->float('weight_assignment_3')->default(10);
            $table->float('weight_assignment_4')->default(10);

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
