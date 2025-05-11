<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtensionModel extends Model
{
    use HasFactory;
    protected $table='tb_extensiones';
    protected $primaryKey = 'idExtension';

    public $timestamps = false;
}
