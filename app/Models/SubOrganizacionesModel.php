<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubOrganizacionesModel extends Model
{
    use HasFactory;
    protected $table='tb_suborganizaciones';
    protected $primaryKey = 'idSubOrganizacion';
}
