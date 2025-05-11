<?php

namespace App\Models\Aegis;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tb_system_status extends Model
{
    use HasFactory;
    protected $connection = 'DB5';
    protected $table='tb_system_status';
    protected $primaryKey = 'idSystemStatus';
}
