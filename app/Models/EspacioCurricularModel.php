<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EspacioCurricularModel extends Model
{
    use HasFactory;
    protected $table='tb_espacioscurriculares';
    protected $primaryKey = 'idEspacioCurricular';

    public $timestamps = false;
}
