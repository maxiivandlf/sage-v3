<?php

namespace App\Models\POFMH;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CargoOrigenPofMHModel extends Model
{
    use HasFactory;
    protected $connection = 'DB7';
    protected $table='tb_cargos_pof_origen';
    protected $primaryKey = 'idCargos_Pof_Origen';
}
