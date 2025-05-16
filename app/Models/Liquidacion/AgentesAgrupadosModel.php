<?php

namespace App\Models\Liquidacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentesAgrupadosModel extends Model
{

    use HasFactory;
    protected $connection = 'DB9';
    protected $table = 'tb_agentesagrupados';
    protected $primaryKey = 'docu';
}
