<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarrerasRelSubOrgModel extends Model
{
    use HasFactory;
    protected $table='tb_carreras_suborg';
    protected $primaryKey = 'idCarreras_SubOrg';
}
