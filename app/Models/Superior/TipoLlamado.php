<?php

namespace App\Models\Superior;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoLlamado extends Model
{
    use HasFactory;
    protected $connection = 'DB4';
    protected $table='tipo_llamado';
    protected $primaryKey = 'idtipo_llamado';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
    ];
}
