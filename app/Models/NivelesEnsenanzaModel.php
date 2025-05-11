<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NivelesEnsenanzaModel extends Model
{
    use HasFactory;
    protected $table='tb_nivelesensenanza';
    protected $primaryKey = 'idNivelEnsenanza';
}
