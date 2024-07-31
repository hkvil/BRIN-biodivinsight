<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeafPhysiology extends Model
{
    use HasFactory;

    protected $fillable = [
        'observation_id',
        'chlorophyll',
        'nitrogen',
        'leaf_moisture',
        'leaf_temperature'
    ];

    public function observation()
    {
        return $this->belongsTo(Observation::class);
    }
}
