<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstitucionExtensionModel extends Model
{
    use HasFactory;

    protected $table='tb_institucion_extension';
    protected $primaryKey = 'idInstitucionExtension';
}
