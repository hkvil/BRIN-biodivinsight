<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Observation;

class GreenHouseMeasurement extends Model
{
    use HasFactory;

    protected $table = 'gh_measurements';
    
    protected $fillable = [
        'observation_id',
        'no',
        'kode',
        'perlakuan_penyiraman',
        'tinggi_tanaman',
        'panjang_akar',
        'bb_tunas',
        'bk_tunas',
        'bb_akar',
        'bk_akar',
        'minggu_panen',
        'mean_tinggi_tanaman',
        'stddev_tinggi_tanaman',
        'mean_panjang_akar',
        'stddev_panjang_akar',
        'mean_bb_tunas',
        'stddev_bb_tunas',
        'mean_bk_tunas',
        'stddev_bk_tunas',
        'mean_bb_akar',
        'stddev_bb_akar',
        'mean_bk_akar',
        'stddev_bk_akar'
    ];

    public function observation()
    {
        return $this->belongsTo(Observation::class);
    }
}
