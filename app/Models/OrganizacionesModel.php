<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizacionesModel extends Model
{
    use HasFactory;
    protected $table='tb_organizaciones';
    protected $primaryKey = 'idOrganizaciones';
}
