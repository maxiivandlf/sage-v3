<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function redirect()
    {
        $modo = Session::get('Modo');



        switch ($modo) {
            case 4:
                return redirect()->route('liquidacion');
            default:
                return redirect()->route('Bandeja');
        }
    }
}
