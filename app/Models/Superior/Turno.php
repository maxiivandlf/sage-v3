<?php

namespace App\Models\Superior;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turno extends Model
{
    protected $connection = 'DB4';
    use HasFactory;
    protected $table = 'tb_turnos';
    protected $primaryKey = 'idTurno';
    public $timestamps = false;
    protected $fillable = [
        'nombre_turno',];
}
