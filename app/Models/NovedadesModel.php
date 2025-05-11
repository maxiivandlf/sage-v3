<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NovedadesModel extends Model
{
    use HasFactory;
    protected $table='tb_novedades';
    protected $primaryKey = 'idNovedad';
}
