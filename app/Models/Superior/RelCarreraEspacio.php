<?php

namespace App\Models\Superior;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelCarreraEspacio extends Model
{
    protected $connection = 'DB4';
    use HasFactory;

    protected $table = 'rel_carrera_espacio';
    protected $primaryKey = 'idrel_carrera_espacio';
    public $timestamps = true;

    protected $fillable = [
        'idCarrera',
        'idEspacioCurricular',
    ];

    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'idCarrera');
    }

    public function espacio()
    {
        return $this->belongsTo(EspacioCurricular::class, 'idEspacioCurricular');
    }
}
