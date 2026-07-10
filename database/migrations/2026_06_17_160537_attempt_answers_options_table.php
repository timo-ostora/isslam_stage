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
        Schema::disableForeignKeyConstraints();

        // The actual option(s) a student selected for a given attempt_answer.
        // single_choice / true_false -> exactly 1 row.
        // multiple_choice -> 1..N rows.
        Schema::create('attempt_answer_options', function (Blueprint $table) {
            $table->id();

            $table->foreignId('attempt_answer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('question_option_id')->constrained();

            $table->timestamps();

            $table->unique(['attempt_answer_id', 'question_option_id'], 'attempt_answer_option_unique');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attempt_answer_options');
    }
};