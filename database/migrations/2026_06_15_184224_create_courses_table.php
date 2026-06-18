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

        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')
                ->unique();
            $table->string('description')
                ->nullable();
            $table->string('thumbnail_url')
                ->nullable();
            $table->enum('status', ["draft","published","archived"])
                ->default('draft');
            
            $table->foreignId('category_id')
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('creator_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->integer('duration_seconds')->nullable();

            $table->enum('difficulty_level', ["easy","medium","hard"])
                ->default('easy');

            $table->string('language')
                ->default('en');
                
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
