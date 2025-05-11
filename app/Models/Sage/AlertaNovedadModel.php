<?php

namespace App\Models\Sage;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlertaNovedadModel extends Model
{
    use HasFactory;
    protected $table='tb_alerta_novedades';
    protected $primaryKey = 'idAlertaNovedad';
}
