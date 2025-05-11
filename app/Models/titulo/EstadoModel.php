<?php

namespace App\Models\titulo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoModel extends Model
{
    use HasFactory;
    protected $connection = 'DB2';
    protected $table='tb_estados';
    protected $primaryKey = 'idEstado';
}
