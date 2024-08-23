<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Location extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    
    protected $fillable = [
        'dusun',
        'desa',
        'kelurahan',
        'kecamatan',
        'kabupaten',
        'altitude',
        'longitude',
        'latitude'
    ];

    /**
     * Get the observations for the location.
     */
    public function observations()
    {
        return $this->hasMany(Observation::class);
    }
}
