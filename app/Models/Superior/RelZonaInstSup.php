<?php

namespace App\Models\Superior;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelZonaInstSup extends Model
{
    protected $connection = 'DB4';
    use HasFactory;

    protected $table = 'rel_zona_instsup';
    protected $primaryKey = 'idrel_zona_instsup';
    public $timestamps = true;

    protected $fillable = [
        'idtb_zona',
        'id_instituto_superior',
    ];

    public function zona()
    {
        return $this->belongsTo(Zona::class, 'idtb_zona');
    }

    public function instituto()
    {
        return $this->belongsTo(InstitutoSuperior::class, 'id_instituto_superior');
    }
}
