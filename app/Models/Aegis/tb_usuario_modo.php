<?php

namespace App\Models\Aegis;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tb_usuario_modo extends Model
{
    use HasFactory;
    protected $connection = 'DB5';
    protected $table='tb_usuario_modo';
    protected $primaryKey = 'idUsuarioModo';
}
