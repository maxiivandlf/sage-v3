<?php

namespace App\Models\titulo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroDocModel extends Model
{
    use HasFactory;
    protected $connection = 'DB2';
    protected $table='tb_registros_doc';
    protected $primaryKey = 'idRegistro_doc';
}
