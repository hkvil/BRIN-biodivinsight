<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class LeafPhysiology extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

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
