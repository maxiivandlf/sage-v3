<?php

namespace App\Models\Superior;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoEstado extends Model
{
    use HasFactory;
    protected $connection = 'DB4';
    protected $table='tb_tipoestado';
    protected $primaryKey = 'idtb_tipoestado';
    public $timestamps = true;

    protected $fillable = [
        'nombre_tipoestado',
    ];
}
