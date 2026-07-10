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

        Schema::create('attempt_answers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('attempt_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('question_id')->constrained();

            // Snapshotted at grading time so later edits to question_options
            // don't retroactively change a graded answer.
            $table->boolean('is_correct')->nullable();
            $table->unsignedInteger('points_awarded')->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['attempt_id', 'question_id']);
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attempt_answers');
    }
};
