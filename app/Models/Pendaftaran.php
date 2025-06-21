<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    use HasFactory;

    protected $table ='pasien_pendaftaran';
    protected $primaryKey='id';
    protected $guarded = [];
    protected $keyType='string';
    public $timestamps = true;
protected $casts = [
    'tgl_lahir' => 'date:Y-m-d',
];
    protected $fillable =[
        'id',
        'no_rm',
        'nama',
        'tempat_lahir',
        'tgl_lahir',
        'jns_kelamin',
        'alamat',
        'no_tlp',
        'pendidikan',
        'pekerjaan',
        'no_ktp',
        'no_asuransi',
        'jns_asuransi',
        'created_at',
        'updated_at'
    ];
}
