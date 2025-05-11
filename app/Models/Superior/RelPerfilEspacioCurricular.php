<?php

namespace App\Models\Superior;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelPerfilEspacioCurricular extends Model
{
    protected $connection = 'DB4';
    use HasFactory;

    protected $table = 'rel_perfil_espaciocurricular';
    protected $primaryKey = 'idrel_perfil_espaciocurricular';
    public $timestamps = true;

    protected $fillable = [
        'idtb_perfil',
        'idEspacioCurricular',
    ];

    public function carrera()
    {
        return $this->belongsTo(Perfil::class, 'idtb_perfil');
    }

    public function espacio()
    {
        return $this->belongsTo(EspacioCurricular::class, 'idEspacioCurricular');
    }
}
