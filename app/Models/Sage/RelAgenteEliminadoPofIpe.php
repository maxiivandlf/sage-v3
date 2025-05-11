<?php

namespace App\Models\Sage;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelAgenteEliminadoPofIpe extends Model
{
    use HasFactory;
    protected $connection = 'DB7';
    protected $table='tb_rel_agente_eliminado_pof_ipe';
    protected $primaryKey = 'idRelAgenteEliminadoPofIpe';
}
