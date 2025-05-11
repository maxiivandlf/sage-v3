<?php

namespace App\Models\Superior;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\Superior\Llamado;
use App\Models\Superior\Cargo;
use App\Models\Superior\Turno;
use App\Models\Superior\SituacionRevista;
use App\Models\Superior\Perfil;
use App\Models\Superior\PeriodoCursado;
class CargoPorLlamado extends Model
{
    use HasFactory;
    protected $connection = 'DB4';
    protected $table = 'rel_cargo_por_llamado';    
    protected $primaryKey = 'idrel_cargo_por_llamado';
    public $timestamps = true;

    protected $fillable = [
        'idllamado',
        'idtb_cargos',
        'idTurno',
        'horacat_cargo',
        'horario_cargo',
        'idtb_situacion_revista',
        'idtb_perfil',
        'idtb_periodo_cursado',
    ];
    public function llamado(): BelongsTo
    {
        return $this->belongsTo(Llamado::class, 'idllamado', 'idLlamado');
    }
    public function cargo(): BelongsTo
    {
        return $this->belongsTo(Cargo::class, 'idtb_cargos', 'idtb_cargos');
    }
    public function turno(): BelongsTo
    {
        return $this->belongsTo(Turno::class, 'idTurno', 'idTurno');
    }
    public function situacionRevista(): BelongsTo
    {
        return $this->belongsTo(SituacionRevista::class, 'idtb_situacion_revista', 'idtb_situacion_revista');
    }
    public function perfil(): BelongsTo
    {
        return $this->belongsTo(Perfil::class, 'idtb_perfil', 'idtb_perfil');
    }
    public function periodoCursado(): BelongsTo
    {
        return $this->belongsTo(PeriodoCursado::class, 'idtb_periodo_cursado', 'idtb_periodo_cursado');
    }
}
