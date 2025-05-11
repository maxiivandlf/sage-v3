<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NivelesEnsenanzaRelSubOrgModel extends Model
{
    use HasFactory;
    protected $table='tb_niveles_suborg';
    protected $primaryKey = 'idNiveles_SubOrg';
}
