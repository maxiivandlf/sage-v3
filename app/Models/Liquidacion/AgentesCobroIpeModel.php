<?php

namespace App\Models\Liquidacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentesCobroIpeModel extends Model
{
    use HasFactory;
    protected $table = 'tb_agentescobroipe';
    protected $primaryKey = 'docu';
}
