<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Herbarium extends Model
{
    use HasFactory;

    protected $fillable = [
        'seed_sample',
        'leaf_sample',
    ];

    public function observation()
    {
        return $this->belongsTo(Observation::class);
    }
}
