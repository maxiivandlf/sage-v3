<?php

namespace App\Models\Suri;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminPersonaCertificaciones extends Model
{
    use HasFactory;

    protected $connection = 'BD6';

    protected $table = 'admin_personas_certificaciones';

    public $timestamps = false;

    protected $primaryKey = 'id'; 
}
