<?php

namespace App\Models\Sage;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogPofmhModel extends Model
{
    use HasFactory;
    protected $connection = 'DB7';
    protected $table='tb_log_pofmh';
    protected $primaryKey = 'idLogPofmh';
}
