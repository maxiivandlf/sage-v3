<?php

namespace App\Models\superior;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Titulo_Cursos extends Model
{
    use HasFactory;
    protected $connection = 'DB4';
    protected $table='tb_titulos_cursos';
    protected $primaryKey = 'idTitulo_curso';
}
