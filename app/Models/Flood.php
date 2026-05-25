<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flood extends Model
{
    use HasFactory;

    protected $fillable = [
        'region_id',
        'location_name',
        'latitude',
        'longitude',
        'status',
        'water_level',
        'affected_population',
        'weather',
        'description',
        'reported_at'
    ];

    protected $casts = [
        'reported_at' => 'datetime',
        'latitude' => 'float',
        'longitude' => 'float',
        'water_level' => 'integer',
        'affected_population' => 'integer'
    ];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
