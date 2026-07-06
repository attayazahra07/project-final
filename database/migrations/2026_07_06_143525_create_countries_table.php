<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('code', 3)->unique(); // ISO 3166-1 alpha-2/3
            $table->string('name');
            $table->string('region')->nullable();
            $table->string('currency_code', 10)->nullable();
            $table->string('currency_name')->nullable();
            $table->string('language')->nullable();
            $table->decimal('lat', 10, 6)->nullable();
            $table->decimal('lng', 10, 6)->nullable();
            $table->string('flag_url')->nullable();
            $table->json('cached_data')->nullable(); // cache dari REST Countries API
            $table->timestamp('data_cached_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
