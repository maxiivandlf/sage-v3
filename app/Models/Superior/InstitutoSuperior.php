<?php

namespace App\Models\Superior;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstitutoSuperior extends Model
{
    protected $connection = 'DB4';
    use HasFactory;
    protected $table = 'tb_instituto_superior';
    protected $primaryKey = 'id_instituto_superior';
    public $timestamps = true;
    protected $fillable = [
        'nombre_instsup', 
        'idtb_zona',
        'idLocalidad',
        'cue_instsup',
    ];
}
