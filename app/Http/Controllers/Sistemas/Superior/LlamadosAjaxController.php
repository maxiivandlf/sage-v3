<?php

namespace App\Http\Controllers\Sistemas\Superior;
use App\Models\Superior\Cargo;
use App\Models\Superior\EspacioCurricular;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Superior\CargoPorLlamado;
use App\Models\Superior\EspacioPorLlamado;
use Illuminate\Http\Request;
use App\Models\Superior\Llamado;

class LlamadosAjaxController extends Controller
{
    // Método para obtener los cargos
    public function getCargos()
    {
        $cargos = Cargo::all(['idtb_cargos', 'nombre_cargo']);
        return response()->json($cargos);// Retornamos los cargos como JSON
    }

    // Método para obtener los espacios curriculares
    public function getEspacios()
    {
        $espacios = EspacioCurricular::all(['idEspacioCurricular', 'nombre_espacio']);
        return response()->json($espacios); // Retornamos los espacios curriculares como JSON
    }
}
