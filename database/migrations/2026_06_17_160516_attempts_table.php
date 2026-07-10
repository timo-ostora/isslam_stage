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

        Schema::create('attempts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained();
            $table->foreignId('assessment_id')->constrained();

            $table->unsignedInteger('score')
                ->default(0);
            $table->boolean('passed')
                ->default(false);

            $table->timestamp('started_at')
                ->nullable();

            $table->timestamp('submitted_at')
                ->nullable();

            $table->unsignedInteger('time_taken_seconds')
                ->nullable();

            // Snapshot of the assessment's questions/options/correct-answers at the moment
            // the attempt was submitted. Prevents later edits to Question/QuestionOption
            // from silently rewriting historical results.
            $table->json('questions_snapshot')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'assessment_id']);
            $table->index(['assessment_id', 'passed']);
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attempts');
    }
};
