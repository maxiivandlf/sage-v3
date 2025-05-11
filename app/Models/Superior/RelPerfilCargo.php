<?php

namespace App\Models\Superior;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelPerfilCargo extends Model
{
    use HasFactory;
    protected $table = 'rel_perfil_cargo';
    protected $primaryKey = 'idrel_perfil_cargo';
    public $timestamps = true;

    protected $fillable = [
        'idtb_perfil',
        'idtb_cargos',
    ];

    public function carrera()
    {
        return $this->belongsTo(Perfil::class, 'idtb_perfil');
    }

    public function espacio()
    {
        return $this->belongsTo(Cargo::class, 'idtb_cargos');
    }
}
