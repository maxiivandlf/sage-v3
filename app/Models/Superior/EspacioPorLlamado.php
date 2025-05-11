<?php

namespace App\Models\Superior;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\Superior\Llamado;
use App\Models\Superior\EspacioCurricular;
use App\Models\Superior\Turno;
use App\Models\Superior\SituacionRevista;
use App\Models\Superior\Perfil;
use App\Models\Superior\PeriodoCursado;


class EspacioPorLlamado extends Model
{   
    protected $connection = 'DB4';
    protected $table = 'rel_espacios_por_llamado';
    protected $primaryKey = 'idrel_espacios_por_llamado';
    public $timestamps = true;

    protected $fillable = [
        'idllamado',
        'idEspacioCurricular',
        'idTurno',
        'horacat_espacio',
        'horario_espacio',
        'idtb_situacion_revista',
        'idtb_perfil',
        'idtb_periodo_cursado',
    ]; 
    public function llamado(): BelongsTo
    {
        return $this->belongsTo(Llamado::class, 'idllamado', 'idLlamado'); // 'idllamado' es la clave foránea en rel_espacios_por_llamado
    }
    public function espacioCurricular(): BelongsTo
    {
        return $this->belongsTo(EspacioCurricular::class, 'idEspacioCurricular', 'idEspacioCurricular');
    }
    public function perfil(): BelongsTo
    {
        return $this->belongsTo(Perfil::class, 'idtb_perfil', 'idtb_perfil');
    }
    public function turno(): BelongsTo
    {
        return $this->belongsTo(Turno::class, 'idTurno', 'idTurno');
    }
    public function situacionRevista(): BelongsTo
    {
        return $this->belongsTo(SituacionRevista::class, 'idtb_situacion_revista', 'idtb_situacion_revista');
    }
    public function periodoCursado(): BelongsTo
    {
        return $this->belongsTo(PeriodoCursado::class, 'idtb_periodo_cursado', 'idtb_periodo_cursado');
    }
}
