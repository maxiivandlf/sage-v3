<?php

namespace App\Models\titulo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class establecimientosModelo extends Model
{
    use HasFactory;
    protected $connection = 'DB2';
    protected $table='tb_establecimientos';
    protected $primaryKey = 'idEstablecimiento';
}
