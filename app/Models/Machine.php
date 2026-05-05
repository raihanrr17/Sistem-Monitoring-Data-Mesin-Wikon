<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    protected $table = 'machines'; 

    public $timestamps = false;

    protected $fillable = [
        'bulan',
        'tahun',
        'plant',
        'kode_mesin',
        'loading_time',
        'operating_time',
        'breakdown_time',
        'freq_breakdown',
        'masalah',
        'langkah_perbaikan',
        'langkah_pencegahan',
        'availability',
        'mtbf',
        'mttr',
        'status'
    ];
}