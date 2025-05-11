<?php

namespace App\Models\Aegis;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tb_tipo_status extends Model
{
    use HasFactory;
    protected $connection = 'DB5';
    protected $table='tb_tipo_status';
    protected $primaryKey = 'idTipoStatus';
}
