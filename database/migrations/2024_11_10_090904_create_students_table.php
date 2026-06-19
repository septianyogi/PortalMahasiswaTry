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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->bigInteger('npm')->unique();
            $table->string('name');
            $table->string('email');
            $table->string('jurusan');
            $table->foreignId('jurusan_id')->references('id')->on('jurusans');
            $table->foreignId('fakultas_id')->references('id')->on('fakultas');
            $table->integer('credits')->default(0);
            $table->integer('gpa')->default(0);
            $table->integer('semester');
            $table->string('dob')->nullable();
            $table->string('country')->nullable();
            $table->string('province')->nullable();
            $table->string('city')->nullable();
            $table->string('subdistrict')->nullable();
            $table->string('postal_code')->nullable();
            $table->text('alamat')->nullable();
            $table->boolean('pembayaran')->default(false);
            $table->date('semester_start_date')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('jurusan_id');
            $table->index('fakultas_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
