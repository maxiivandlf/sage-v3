<?php

namespace App\Models\POFMH;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PofmhActivosModel extends Model
{
    use HasFactory;
    protected $connection = 'DB7';
    protected $table='tb_activos';
    protected $primaryKey = 'idActivo';
}
