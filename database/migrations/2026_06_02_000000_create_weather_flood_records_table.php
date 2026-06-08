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
        Schema::create('weather_flood_records', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->float('avg_temperature');
            $table->float('avg_humidity');
            $table->float('avg_surface_wind');
            $table->float('monsoon_wind');
            $table->float('rainfall');
            $table->float('rainfall_one_week');
            $table->boolean('flood_occurred');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weather_flood_records');
    }
};
