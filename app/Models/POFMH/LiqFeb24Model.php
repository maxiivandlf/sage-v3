<?php

namespace App\Models\POFMH;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiqFeb24Model extends Model
{
    use HasFactory;
    protected $connection = 'DB7';
    protected $table='liqfeb2024';
    protected $primaryKey = 'idLiquidacion';
}
