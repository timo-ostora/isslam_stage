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
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained()->cascadeOnDelete();
            $table->integer('order_index')->default(0);
            $table->string('title');
            $table->string('description')->nullable();
            $table->enum('type', ['quiz', 'exam', 'assignment'])->default('quiz');
            $table->integer('duration_seconds')->default(0);
            $table->integer('passing_score')->default(60);
            $table->integer('max_attempts')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};
