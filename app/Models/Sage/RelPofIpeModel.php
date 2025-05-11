<?php

namespace App\Models\Sage;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelPofIpeModel extends Model
{
    use HasFactory;
    protected $connection = 'DB7';
    protected $table='tb_rel_pof_ipe';
    protected $primaryKey = 'idRelPofIpe';
}
