<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelPofUsuariosModel extends Model
{
    use HasFactory;
    protected $table='tb_rel_admines_instituciones_extensiones';
    protected $primaryKey = 'id_rel_admin_institucion_extension';
}
