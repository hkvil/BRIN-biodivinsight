<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Microclimate extends Model
{
    use HasFactory;
    protected $fillable = [
        'observation_id',
        'temperature',
        'humidity',
        'pressure'
    ];

    /**
     * Get the observation that owns the microclimate.
     */
    public function observation()
    {
        return $this->belongsTo(Observation::class);
    }
}
