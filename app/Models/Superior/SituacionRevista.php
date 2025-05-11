<?php

namespace App\Models\Superior;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SituacionRevista extends Model
{
    protected $connection = 'DB4';
    use HasFactory;
    protected $table = 'tb_situacion_revista';
    protected $primaryKey = 'idtb_situacion_revista';
    public $timestamps = true;

    protected $fillable = [
        'nombre_situacion_revista',       
    ];
}
