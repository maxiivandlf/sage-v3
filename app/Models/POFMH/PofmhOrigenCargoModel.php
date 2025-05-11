<?php

namespace App\Models\POFMH;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PofmhOrigenCargoModel extends Model
{
    use HasFactory;
    protected $connection = 'DB7';
    protected $table='tb_origenes_cargos';
    protected $primaryKey = 'idOrigenCargo';
}
