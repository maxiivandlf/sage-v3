<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Sage2_1Controller extends Controller
{
    //
    public function index(){
        echo "sage2";
        //prueba de conexion a sage2
        $instarealiq = \App\Models\SAGE2_1\instarealiq::all();
        foreach ($instarealiq as $instarealiq) {
            echo $instarealiq->ID_inst_area_liq . "- " .$instarealiq->CUEA . "- ". $instarealiq->nombreInstitucion . "<br>";
        }
    }
}
