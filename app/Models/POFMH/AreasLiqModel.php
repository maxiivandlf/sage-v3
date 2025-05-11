<?php

namespace App\Models\POFMH;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AreasLiqModel extends Model
{
    use HasFactory;
    protected $connection = 'DB7';
    protected $table='tb_areas_liquidacion';
    protected $primaryKey = 'idAreaLiquidacion';
}
