<?php

namespace App\Models\Superior;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lom extends Model
{
    use HasFactory;
    protected $connection = 'DB4';
    protected $table='tb_lom';
    protected $primaryKey = 'idtb_lom';
    public $timestamps = true;

    protected $fillable = [
        'idtb_zona',
        'id_instituto_superior',
        'idCarrera',
        'idtipo_llamado',   
        'idtb_tipoestado',       
        'imglom',
        'pdf',       
        'idtb_cargo',
        'idEspacioCurricular',
        'mes',
        'idUsuarioCrear',
        'idUsuarioEditar',
        'CUE',
    ];
    public function institucion()
    {
        return $this->belongsTo(InstitutoSuperior::class, 'id_instituto_superior', 'id_instituto_superior');
    }
    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'idCarrera', 'idCarrera');
    }
    public function tipoLlamado()
    {
        return $this->belongsTo(TipoLlamado::class, 'idtipo_llamado', 'idtipo_llamado');    
    }
    public function estado()
    {
        return $this->belongsTo(TipoEstado::class, 'idtb_tipoestado', 'idtb_tipoestado');
    }
    public function zona()
    {
        return $this->belongsTo(Zona::class, 'idtb_zona', 'idtb_zona');
    }
    public function cargo()
    {
        return $this->belongsTo(Cargo::class, 'idtb_cargo', 'idtb_cargo');
    }
    public function espacio()
    {
        return $this->belongsTo(EspacioCurricular::class, 'idEspacioCurricular', 'idEspacioCurricular');
    }
        
}
