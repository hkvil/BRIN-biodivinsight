<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Remark extends Model
{
    use HasFactory;

    protected $fillable = [
        'observation_id',
        'remarks',
    ];

    public function observation()
    {
        return $this->belongsTo(Observation::class);
    }
}
