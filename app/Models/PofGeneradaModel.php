<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PofGeneradaModel extends Model
{
    use HasFactory;
    protected $table='tb_pof_generada';
    protected $primaryKey = 'idPofGenerada';
}
