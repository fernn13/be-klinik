<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservasi extends Model
{
    use HasFactory;

    protected $table ='pasien_reservasi';
    protected $primaryKey='id';
    protected $guarded = [];
    protected $keyType='string';
    public $timestamps = true;
protected $casts = [
    'tgl_reservasi' => 'datetime:Y-m-d H:i:s',
];

    protected $fillable =[
        'id',
        'no_rm',
        'nama',
        'no_ktp',
        'tgl_reservasi',
        'kode_kunjungan',
        'ruangan',
        'keluhan',
        'created_at',
        'updated_at'
    ];
}