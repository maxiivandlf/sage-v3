<?php

namespace App\Models\titulo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class certificadosModelo extends Model
{
    use HasFactory;
    protected $connection = 'DB2';
    protected $table='tb_certificados';
    protected $primaryKey = 'idCertificado';
}
