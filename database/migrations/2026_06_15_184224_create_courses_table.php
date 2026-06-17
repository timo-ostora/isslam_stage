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
            $table->string('slug')->unique();
            $table->foreignId('category_id')->constrained();
            $table->foreignId('creator_id')->constrained('users');
            $table->string('title');
            $table->string('description');
            $table->string('thumbnail_url')->nullable();
            $table->enum('status', ["draft","published","archived"])->default('draft');
            $table->enum('difficulty_level', ["easy","medium","hard"]);
            $table->string('language')->default('en');
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
