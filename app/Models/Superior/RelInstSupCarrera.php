<?php

namespace App\Models\Superior;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelInstSupCarrera extends Model
{
    protected $connection = 'DB4';
    use HasFactory;

    protected $table = 'rel_instsup_carrera';
    protected $primaryKey = 'idrel_instsup_carrera';
    public $timestamps = true;

    protected $fillable = [
        'id_instituto_superior',
        'idCarrera',
    ];

    public function instituto()
    {
        return $this->belongsTo(InstitutoSuperior::class, 'id_instituto_superior');
    }

    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'idCarrera');
    }
}
