<?php

namespace App\Models\POFMH;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\POFMH\PofmhModel;

class PofMhSitRev extends Model
{
    use HasFactory;
    protected $connection = 'DB7';
    protected $table='tb_situacionrevista';
    protected $primaryKey = 'idSituacionRevista';

    public function pofmh()
    {
        return $this->hasMany(PofmhModel::class, 'SitRev', 'idSituacionRevista');
    }
}
