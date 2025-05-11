<?php

namespace App\Models\superior;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agentes_Superior extends Model
{
    use HasFactory;
    protected $connection = 'DB4';
    protected $table='tb_agentes';
    protected $primaryKey = 'idAgente';
}
