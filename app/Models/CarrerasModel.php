<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarrerasModel extends Model
{
    use HasFactory;
    protected $table='tb_carerras';
    protected $primaryKey = 'idCarrera';
}
