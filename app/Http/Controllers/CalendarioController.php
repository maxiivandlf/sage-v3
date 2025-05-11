<?php

namespace App\Http\Controllers;

use App\Models\POFMH\PofmhCalendarioModel;
use Illuminate\Http\Request;


class CalendarioController extends Controller
{
    public function index()
    {
        $fechas = PofmhCalendarioModel::all();
        return view('calendario.index', compact('fechas'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'Titulo' => 'required|string|max:255',
            'Fecha' => 'required|date',
            'esFeriado' => 'required|in:S,N',
            'descripcion' => 'nullable|string'
        ]);

        PofmhCalendarioModel::create([
            'fecha' => $request->Fecha,
            'descripcion' => $request->descripcion,
            'es_feriado' => $request->esFeriado,
            'tipoCalendario' => $request->Titulo,
            // Otros campos según sea necesario
        ]);

        return response()->json(['success' => 'Fecha agregada con éxito']);
    }

    public function edit($id)
    {
        $fecha = PofmhCalendarioModel::findOrFail($id);
        return response()->json($fecha);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Titulo' => 'required|string|max:255',
            'Fecha' => 'required|date',
            'esFeriado' => 'required|in:S,N',
            'descripcion' => 'nullable|string'
        ]);

        $fecha = PofmhCalendarioModel::findOrFail($id);
        $fecha->update([
            'fecha' => $request->Fecha,
            'descripcion' => $request->descripcion,
            'es_feriado' => $request->esFeriado,
            'tipoCalendario' => $request->Titulo,
        ]);

        return response()->json(['success' => 'Fecha actualizada con éxito']);
    }

    public function destroy($id)
    {
        $fecha = PofmhCalendarioModel::findOrFail($id);
        $fecha->delete();

        return response()->json(['success' => 'Fecha eliminada con éxito']);
    }
}
