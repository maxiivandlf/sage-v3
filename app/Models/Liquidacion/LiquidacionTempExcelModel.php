<?php

namespace App\Models\Liquidacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiquidacionTempExcelModel extends Model
{
    use HasFactory;
    protected $table = 'liquidacion_temp';
    protected $primaryKey = 'docu';
    public $timestamps = true;
    protected $fillable = [
        'docu',
        'cuil',
        'trab',
        'nomb',
        'sexo',
        'zona',
        'escu',
        'plan',
        'lcat',
        'ncat',
        'hora',
        'agru',
        'area',
        'dias',
        // 'fecha_ingreso',
        // 'fecha_salida',
        // 'tipo_liquidacion',
        // 'monto_liquidacion',
    ];
    // protected $casts = [
    //     'fecha_ingreso' => 'date',
    //     'fecha_salida' => 'date',
    //     'monto_liquidacion' => 'decimal:2',
    //     // agrega otros casts según tu necesidad
    // ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    // protected $attributes = [
    //     'estado' => 'pendiente', // valor por defecto
    // ];



}
