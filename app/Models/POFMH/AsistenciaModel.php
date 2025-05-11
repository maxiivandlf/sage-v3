<?php

namespace App\Models\POFMH;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsistenciaModel extends Model
{
    use HasFactory;
    protected $connection = 'DB7';
    protected $table='tb_asistencia';
    protected $primaryKey = 'idAsistencia';
}
