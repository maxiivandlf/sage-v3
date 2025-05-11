<?php

namespace App\Models\Aegis;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class aegis_agentes extends Model
{
    use HasFactory;
    protected $connection = 'DB5';
    protected $table='tb_agentes';
    protected $primaryKey = 'idAgente';
}
