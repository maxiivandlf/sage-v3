<?php

namespace App\Models\Aegis;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tb_sexo extends Model
{
    use HasFactory;
    protected $connection = 'DB5';
    protected $table='tb_sexos';
    protected $primaryKey = 'idSexo';
}
