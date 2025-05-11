<?php

namespace App\Models\Superior;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    protected $connection = 'DB4';
    use HasFactory;
    protected $table = 'tb_cargos';
    protected $primaryKey = 'idtb_cargos';
    public $timestamps = true;
    protected $fillable = [
        'nombre_cargo', // Asegúrate de que estos campos coincidan con los de la tabla en la base de datos
    ];
}
