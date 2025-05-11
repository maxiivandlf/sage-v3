<?php

namespace App\Models\POFMH;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\POFMH\PofMhSitRev;
use App\Models\POFMH\PofmhCargoSalariales;

class PofmhModel extends Model
{
    use HasFactory;

    protected $connection = 'DB7';
    protected $table='tb_pofmh';
    protected $primaryKey = 'idPofmh';
    protected $fillable = [
        'idPofmh',
        'CUECOMPLETO',
        'Agente',
        'ApeNom',
        'Cargo',
        'Unidad_Liquidacion' // Esta es la que quieres actualizar
        // ... otras columnas que puedan ser asignadas masivamente según lo que necesites
    ];
    
     // Relación con PofMhSitRev (situación de revista)
     public function situacionRevista()
     {
         return $this->belongsTo(PofMhSitRev::class, 'idSituacionRevista');
     }
 
     // Relación con Cargo Salarial
     public function cargoSalarial()
     {
         return $this->belongsTo(PofmhCargoSalariales::class, 'Cargo', 'idCargo');
     }
}
