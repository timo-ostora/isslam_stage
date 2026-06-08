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
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained()->cascadeOnDelete();
            $table->integer('order_index')->default(0);
            $table->string('title');
            $table->string('description')->nullable();
            $table->enum('type', ['video', 'article', 'pdf', 'link'])->default('article');
            $table->string('content_url')->nullable();
            $table->longText('content_text')->nullable();
            $table->json('metadata')->nullable();
            $table->integer('duration_seconds')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
