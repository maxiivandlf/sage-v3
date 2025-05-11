<?php

namespace App\Models\Superior;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Llamado extends Model
{
    use HasFactory;
    protected $connection = 'DB4';
    protected $table='llamado';
    protected $primaryKey = 'idllamado';
    public $timestamps = true;

    protected $fillable = [
        'idtb_zona',
        'id_instituto_superior',
        'idCarrera',
        'idtipo_llamado',
        'fecha_ini',
        'fecha_fin',     
        'idtb_tipoestado',
        'descripcion',
        'url_form',
        'nombre_img',       
    ];
    public function espacios()
    {
        return $this->hasMany(EspacioPorLlamado::class, 'idllamado');
    }

    public function cargos()
    {
        return $this->hasMany(CargoPorLlamado::class, 'idllamado');
    }

    public function institucion()
    {
        return $this->belongsTo(InstitutoSuperior::class, 'id_instituto_superior', 'id_instituto_superior');
    }

    public static function conRelaciones()
    {
        return DB::connection('DB4')
            ->table('llamado')
            ->join('tb_instituto_superior AS instituto', 'instituto.id_instituto_superior', '=', 'llamado.id_instituto_superior')
            ->join('tipo_llamado AS tipollamado', 'tipollamado.idtipo_llamado', '=', 'llamado.idtipo_llamado')
            ->join('tb_zona AS zona', 'zona.idtb_zona', '=', 'llamado.idtb_zona')
            ->join('tb_tipoestado AS estado', 'estado.idtb_tipoestado', '=', 'llamado.idtb_tipoestado')
            ->join('tb_carreras AS carrera', 'carrera.idCarrera', '=', 'llamado.idCarrera')
            ->leftJoin('rel_espacios_por_llamado AS espacios', 'espacios.idllamado', '=', 'llamado.idllamado')
            ->leftJoin('tb_espacioscurriculares AS espacio', 'espacio.idEspacioCurricular', '=', 'espacios.idEspacioCurricular')
            ->leftJoin('tb_situacion_revista AS situacion', 'situacion.idtb_situacion_revista', '=', 'espacios.idtb_situacion_revista')
            ->leftJoin('tb_turnos AS turno', 'turno.idTurno', '=', 'espacios.idTurno')
            ->leftJoin('tb_perfil AS perfil', 'perfil.idtb_perfil', '=', 'espacios.idtb_perfil')
            ->leftJoin('tb_periodo_cursado AS periodo', 'periodo.idtb_periodo_cursado', '=', 'espacios.idtb_periodo_cursado')
            ->leftJoin('rel_cargo_por_llamado AS cargos', 'cargos.idllamado', '=', 'llamado.idllamado')
            ->leftJoin('tb_cargos AS cargosec', 'cargosec.idtb_cargos', '=', 'cargos.idtb_cargos')
            ->leftJoin('tb_situacion_revista AS situacioncargo', 'situacioncargo.idtb_situacion_revista', '=', 'cargos.idtb_situacion_revista')
            ->leftJoin('tb_turnos AS turnocargo', 'turnocargo.idTurno', '=', 'cargos.idTurno')
            ->leftJoin('tb_perfil AS perfilcargo', 'perfilcargo.idtb_perfil', '=', 'cargos.idtb_perfil')
            ->leftJoin('tb_periodo_cursado AS periodocargo', 'periodocargo.idtb_periodo_cursado', '=', 'cargos.idtb_periodo_cursado');
    }

}
