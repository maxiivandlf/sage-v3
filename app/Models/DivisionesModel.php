<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DivisionesModel extends Model
{
    use HasFactory;
    protected $table='tb_divisiones';
    protected $primaryKey = 'idDivision';
}
