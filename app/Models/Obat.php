<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    protected $table = 'obat';
    protected $fillable = [
        'kode_obat',
        'nama_obat',
        'satuan',
        'isi',
        'stok',
        'harga_jual',
        'minimum_persediaan'
    ];
}
