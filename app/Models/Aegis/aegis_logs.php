<?php

namespace App\Models\Aegis;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class aegis_logs extends Model
{
    use HasFactory;
    protected $connection = 'DB5';
    protected $table='tb_logs';
    protected $primaryKey = 'idLog';
}
