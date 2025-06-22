<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Antrian extends Model
{
    protected $table   = 'antrian_pasien';
    protected $guarded = [];      // mass-assign bebas
    public $timestamps = true;

    protected $casts = [
        'waktu_masuk' => 'datetime:Y-m-d H:i:s',
    ];
}

