<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
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
