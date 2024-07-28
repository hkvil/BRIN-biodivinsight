<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Observation extends Model
{
    use HasFactory;

    protected $fillable = [
        'plant_id',
        'location_id',
        'observation_date',
        'observation_time'
    ];

    /**
     * Get the plant associated with the observation.
     */
    public function plant()
    {
        return $this->belongsTo(Plant::class);
    }

    /**
     * Get the location associated with the observation.
     */
    public function location() 
    {
        return $this->belongsTo(Location::class);
    }
}
