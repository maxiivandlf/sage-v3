<?php

namespace App\Models\Sage;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PofIpeModel extends Model
{
    use HasFactory;
    protected $connection = 'DB7';
    protected $table='tb_pof_ipe';
    protected $primaryKey = 'idPofIpe';
}
