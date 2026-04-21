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
        Schema::create('class_sessions', function (Blueprint $table) {
           $table->id();

            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();

            $table->integer('week');
            $table->date('date')->nullable();

            $table->string('qr_token');
            $table->dateTime('expired_at')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            $table->unique(['class_id', 'week']);
            $table->index('class_id');
            $table->index('qr_token');
            $table->index('expired_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_sessions');
    }
};
