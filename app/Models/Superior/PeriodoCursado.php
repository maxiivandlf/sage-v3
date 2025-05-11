<?php

namespace App\Models\Superior;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodoCursado extends Model
{
    protected $connection = 'DB4';
    use HasFactory;
    protected $table = 'tb_periodo_cursado';
    protected $primaryKey = 'idtb_periodo_cursado';
    public $timestamps = true;
    protected $fillable = [
        'nombre_periodo', // Asegúrate de que estos campos coincidan con los de la tabla en la base de datos
    ];
}
