<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soil extends Model
{
    use HasFactory;

    protected $fillable = [
        'observation_id',
        'ph',
        'moisture',
        'temperature'
    ];

    public function observation()
    {
        return $this->belongsTo(Observation::class);
    }
    
}
