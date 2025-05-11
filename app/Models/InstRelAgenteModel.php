<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstRelAgenteModel extends Model
{
    use HasFactory;
    protected $table='tb_institucion_rel_agente';
    protected $primaryKey = 'idInstitucionExtensionAgente';
    
}
