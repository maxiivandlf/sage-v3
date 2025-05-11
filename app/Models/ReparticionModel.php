<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReparticionModel extends Model
{
    use HasFactory;
    protected $table='tb_reparticiones';
    protected $primaryKey = 'idReparticion';
    public $timestamps = false;
}
