<?php

namespace App\Models\Sage;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HorasIpeModel extends Model
{
    use HasFactory;
    protected $connection = 'DB7';
    protected $table='tb_horas';
    protected $primaryKey = 'idHoras';
}
