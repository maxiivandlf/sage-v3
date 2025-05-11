<?php

namespace App\Models\Superior;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    protected $connection = 'DB4';
    use HasFactory;
    protected $table = 'tb_perfil';
    protected $primaryKey = 'idtb_perfil';
    public $timestamps = true;
    protected $fillable = [
        'nombre_perfil', // Asegúrate de que estos campos coincidan con los de la tabla en la base de datos
    ];
}
