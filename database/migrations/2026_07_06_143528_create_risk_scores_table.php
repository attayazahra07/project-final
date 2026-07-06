<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('risk_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained()->cascadeOnDelete();
            $table->decimal('weather_risk', 5, 2)->default(0);
            $table->decimal('inflation_risk', 5, 2)->default(0);
            $table->decimal('currency_risk', 5, 2)->default(0);
            $table->decimal('news_risk', 5, 2)->default(0);
            $table->decimal('total_risk', 5, 2)->default(0);
            $table->enum('risk_label', ['Low', 'Medium', 'High'])->default('Low');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('risk_scores');
    }
};
