<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diagnosa extends Model
{
    protected $table = 'master_diagnosa';

    protected $fillable = ['kode', 'nama'];
}
