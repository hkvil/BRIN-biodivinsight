<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plant extends Model
{
    use HasFactory;
    protected $fillable = [
        'species_name',
        'common_name'
    ];

    /**
     * Get the observations for the plant.
     */
    public function observations()
    {
        return $this->hasMany(Observation::class);
    }
}
