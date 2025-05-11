<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZonasLiqModel extends Model
{
    use HasFactory;
    protected $table='tb_zonas_liq';
    protected $primaryKey = 'idZona';
}
