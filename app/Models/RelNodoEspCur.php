<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelNodoEspCur extends Model
{
    use HasFactory;
    protected $table='tb_rel_nodo_espcur';
    protected $primaryKey = 'idRelNodoEspCur';
}
