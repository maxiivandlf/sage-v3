<?php

namespace App\Models\SAGE2_1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class instarealiq extends Model
{
    use HasFactory;
    protected $connection = 'DB8';
    protected $table='instarealiq';
    protected $primaryKey = 'ID_inst_area_liq';
}
