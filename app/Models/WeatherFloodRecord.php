<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeatherFloodRecord extends Model
{
    use HasFactory;

    protected $table = 'weather_flood_records';

    protected $fillable = [
        'date',
        'avg_temperature',
        'avg_humidity',
        'avg_surface_wind',
        'monsoon_wind',
        'rainfall',
        'rainfall_one_week',
        'flood_occurred',
    ];

    protected $casts = [
        'date' => 'date',
        'avg_temperature' => 'float',
        'avg_humidity' => 'float',
        'avg_surface_wind' => 'float',
        'monsoon_wind' => 'float',
        'rainfall' => 'float',
        'rainfall_one_week' => 'float',
        'flood_occurred' => 'boolean',
    ];
}
