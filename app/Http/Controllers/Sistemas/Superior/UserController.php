<?php
namespace App\Http\Controllers\Sistemas\Superior;
use App\Models\superior\Agentes_Superior;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $usuarios = Agentes_Superior::all();
        return view('aegis.sistemas/superior/infoSuperior', compact('usuarios'));
    }

    public function subirImagen(Request $request)
    {
        $request->validate([
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('imagen')) {
            $imagenPath = $request->file('imagen')->store('imagenes', 'public');

            // Aquí puedes asignar la imagen a un usuario específico
            $usuario = idAgente::find(1); // Cambia esto según tu lógica
            $usuario->imagen = $imagenPath;
            $usuario->save();

            return back()->with('success', 'Imagen subida correctamente');
        }

        return back()->with('error', 'Error al subir la imagen');
    }
}
