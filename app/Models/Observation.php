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

    public function plant()
    {
        return $this->belongsTo(Plant::class);
    }

    public function location() 
    {
        return $this->belongsTo(Location::class);
    }

    public function leafPhysiology()
    {
        return $this->hasMany(LeafPhysiology::class);
    }

    public function microclimate(){
        return $this->hasOne(Microclimate::class);
    }

    public function soil(){
        return $this->hasOne(Soil::class);
    }

    public function herbarium(){
        return $this->hasOne(Herbarium::class);
    }
}
