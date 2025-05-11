<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TurnosRelInstModel extends Model
{
    use HasFactory;
    protected $table='tb_turnos_inst';
    protected $primaryKey = 'idTurnos_Inst';
}
