<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EdificioModel extends Model
{
    use HasFactory;
    protected $table='tb_edificios';
    protected $primaryKey = 'idEdificio';
}
