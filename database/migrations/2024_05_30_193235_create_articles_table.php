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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('feed_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('link');
            $table->text('description')->nullable();
            $table->text('content')->nullable();
            $table->string('category')->nullable();
            $table->string('author')->nullable();
            $table->string('published_at')->nullable();
            $table->boolean('is_read')->default(false);



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
