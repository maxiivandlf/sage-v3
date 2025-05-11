<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlazasModel extends Model
{
    use HasFactory;
    protected $table='tb_plazas';
    protected $primaryKey = 'IdPlaza';
}
