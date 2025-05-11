<?php

namespace App\Models\Suri;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminPersona extends Model
{
    protected $connection = 'DB6';

    protected $table = 'admin_personas';

    public $timestamps = false;

    protected $primaryKey = 'id_persona'; 

}
