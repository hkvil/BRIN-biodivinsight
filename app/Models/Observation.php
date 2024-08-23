<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Plant;
use App\Models\Location;
use App\Models\LeafPhysiology;
use App\Models\Microclimate;
use App\Models\Soil;
use App\Models\Herbarium;
use App\Models\Observation;
use App\Models\GreenHouseMeasurement;
use OwenIt\Auditing\Contracts\Auditable;

class Observation extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    const TYPE_LAB = 'Lab Observation';
    const TYPE_FIELD = 'Field Observation';

    protected $fillable = [
        'plant_id',
        'location_id',
        'remark_id',
        'observation_type',
        'observation_date',
        'observation_time',
    ];

    public function plant()
    {
        return $this->belongsTo(Plant::class);
    }

    public function location() 
    {
        return $this->belongsTo(Location::class);
    }

    public function greenHouseMeasurement()
    {
        if($this->observation_type == self::TYPE_LAB){
            return $this->hasOne(GreenHouseMeasurement::class);
        }
    }

    public function leafPhysiology()
    {   if($this->observation_type == self::TYPE_FIELD){
            return $this->hasMany(LeafPhysiology::class);
        }
    }

    public function microclimate(){
        if($this->observation_type == self::TYPE_FIELD){
            return $this->hasOne(Microclimate::class);
        }
    }

    public function soil(){
        if($this->observation_type == self::TYPE_FIELD){
            return $this->hasOne(Soil::class);
        }
    }

    public function remarks(){
        return $this->hasOne(Remark::class);
    }
}
