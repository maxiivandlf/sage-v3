<?php

namespace App\Models\POFMH;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\POFMH\PofmhModel;

class PofmhCargoSalariales extends Model
{
    use HasFactory;
    protected $connection = 'DB7';
    protected $table='tb_cargossalariales';
    protected $primaryKey = 'idCargo';
    
    public function pofmh()
    {
        return $this->hasMany(PofmhModel::class, 'Cargo', 'idCargo');
    }
}
