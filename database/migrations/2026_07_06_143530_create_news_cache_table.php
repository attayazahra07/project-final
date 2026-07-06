<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news_cache', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('source')->nullable();
            $table->string('url')->nullable();
            $table->string('image_url')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->decimal('sentiment_positive', 5, 2)->default(0);
            $table->decimal('sentiment_neutral', 5, 2)->default(0);
            $table->decimal('sentiment_negative', 5, 2)->default(0);
            $table->timestamp('data_cached_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news_cache');
    }
};
