<?php

namespace App\Models\Sage;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuperRelacionCUEModel extends Model
{
    use HasFactory;
    protected $table='tb_super_cue_relacion';
    protected $primaryKey = 'idSuperCueRelacion';
}
