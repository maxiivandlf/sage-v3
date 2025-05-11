<?php

namespace App\Models\titulo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class registroTituloModelo extends Model
{
    use HasFactory;
    protected $connection = 'DB2';
    protected $table='tb_registro_de_titulos';
    protected $primaryKey = 'idRegistroUnico';
}
