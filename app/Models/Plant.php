<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Plant extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    
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
