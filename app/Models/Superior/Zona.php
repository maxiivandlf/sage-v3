<?php

namespace App\Models\Superior;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zona extends Model
{
    protected $connection = 'DB4';
    use HasFactory;

    protected $table = 'tb_zona';
    protected $primaryKey = 'idtb_zona';
    public $timestamps = true;

    protected $fillable = [
        'nombre_zona',
    ];
}
