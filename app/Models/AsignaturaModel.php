<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsignaturaModel extends Model
{
    use HasFactory;
    protected $table='tb_asignaturas';
    protected $primaryKey = 'idAsignatura';
}
