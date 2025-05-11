<?php

namespace App\Models\POFMH;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PofmhCalendarioModel extends Model
{
    use HasFactory;
    protected $connection = 'DB7';
    protected $table='tb_calendario';
    protected $primaryKey = 'idCalendario';

    protected $fillable = [
        'fecha',
        'dia_semana',
        'es_feriado',
        'descripcion',
        'tipoCalendario',
        'CUECOMPLETO'
    ];
}
