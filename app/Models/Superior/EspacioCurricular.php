<?php

namespace App\Models\Superior;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EspacioCurricular extends Model
{
    protected $connection = 'DB4';
    use HasFactory;
    protected $table = 'tb_espacioscurriculares';  

    protected $primaryKey = 'idEspacioCurricular'; 
    public $timestamps = true;
    protected $fillable = [
        'nombre_espacio', 
    ];
}
