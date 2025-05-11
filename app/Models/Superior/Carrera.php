<?php

namespace App\Models\Superior;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrera extends Model
{
    protected $connection = 'DB4';
    use HasFactory;

    protected $table = 'tb_carreras';
    protected $primaryKey = 'idCarrera';
    public $timestamps = true;

    protected $fillable = [
        'nombre_carrera',
        'Titulo',
        'Duracion',
        'InstrumentoLegal',    ];
}
