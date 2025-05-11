<?php

namespace App\Http\Controllers;

use App\Models\superior\Agentes_Superior;
use App\Models\superior\Titulo_Cursos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
class SuperiorController extends Controller
{
    public function __construct()
    {
        // Verificar la sesión en cada llamada al controlador
        $this->middleware(function ($request, $next) {
            // Verificar si la sesión 'Usuario' está activa
            if (!Session::has('Usuario')) {
                // Redirigir a la raíz si la sesión no está activa
                return Redirect::to('/');
            }

            return $next($request);
        });
    }
    //aqui controlamos los exportados 
    public function usuariosListaSup(){
        $UsuariosSup = DB::connection('DB4')->table('tb_agentes_exportados')
        ->leftJoin('tb_agentes', 'tb_agentes_exportados.Documento', '=', 'tb_agentes.Documento')
        ->whereNull('tb_agentes.Documento') // Filtra los registros que no tienen una correspondencia en tb_agentes
        ->whereNotNull('tb_agentes_exportados.Documento') // Excluye registros donde Documento sea NULL
        ->whereNotNull('tb_agentes_exportados.ApeNom') 
        ->select('tb_agentes_exportados.*') // Selecciona solo las columnas de tb_agentes_exportados
        ->get();
    //dd($UsuariosSup);
               
             
               //dd($RelSubOrgAgente);
               $datos=array(
                   'mensajeError'=>"",
                   'UsuariosLista'=>$UsuariosSup,
                   'mensajeNAV'=>'Panel de Configuración de Usuarios Exportados',
               );
               //dd($infoPlaza);
               return view('bandeja.Superior.usuarioListaSup',$datos);
    }

    //aqui controlamos a los registrados
    public function usuariosListaSupRegistrado(){
        $UsuariosSup = DB::connection('DB4')->table('tb_agentes')
        ->get();
    //dd($UsuariosSup);
               
             
               //dd($RelSubOrgAgente);
               $datos=array(
                   'mensajeError'=>"",
                   'UsuariosLista'=>$UsuariosSup,
                   'mensajeNAV'=>'Panel de Configuración de Usuarios Exportados',
               );
               //dd($infoPlaza);
               return view('bandeja.Superior.usuariosListaSupRegistrado',$datos);
    }
    public function editarUsuarioSup($idUsuario){
        

        $Usuario = DB::connection('DB4')->table('tb_agentes')
        ->where('idAgente', $idUsuario)
        ->get();
        $sexo = DB::connection('DB4')->table('tb_sexo')->get();
        $sino = DB::connection('DB4')->table('tb_sino')->get();
        $archivo = DB::connection('DB4')->table('tb_archivo')->get();
        //dd($RelSubOrgAgente);
        $datos=array(
            'mensajeError'=>"",
            'Sexos'=>$sexo,
            'Respuestas'=>$sino,
            'Archivos'=>$archivo,
            'mensajeNAV'=>'Panel de Edición de Usuarios',
            'Usuario'=>$Usuario
            
        );
        //dd($infoPlaza);
        return view('bandeja.Superior.editar_usuario_sup',$datos);
    }

    public function FormRegistrarUsuarioSup(Request $request){
        //dd($request);
        /*
         +"idAgentes_exportados": 1
            +"ApeNom": "Aballay Cintia Dayana "
            +"Documento": "37494975"
            +"telefono": "3825677429"
            +"email": "cintiadayanaaballay@gmail.com"
            +"domicilio": "René Favaloro  B° San Héctor de Valdivieso "
            +"localidad": "Villa Unión "
         */
        //llega un solo dato que es u, es el iddel registro en exportado usuario

        //paso 1 buscar el agente en exportado
        $Usuario = DB::connection('DB4')->table('tb_agentes_exportados')
        ->where('idAgentes_exportados', $request->u)
        ->first();
        //dd($Usuario);

        //paso 2 insertar el agente en su tabla
        $nuevo = new Agentes_Superior();
            $nuevo->ApeNom = strtoupper($Usuario->ApeNom);
            $nuevo->Documento = $Usuario->Documento;
            $nuevo->telefono = $Usuario->telefono;
            $nuevo->email = strtolower($Usuario->email);
            $nuevo->domicilio = Str::title($Usuario->domicilio);
            $nuevo->localidad = strtoupper($Usuario->localidad);
            $nuevo->estado = 0;
        $nuevo->save();

        //paso 3 obtengo el id para poder enviarlo a editar
        //dd($nuevo->idAgente);
        $usuario = $nuevo->idAgente;
        //paso 4 armar y enviar aa editar
        return redirect("/editarUsuarioSup/$usuario");
    }

    public function FormActualizarUsuarioSup(Request $request){
        //dd($request);
        /*
       "_token" => "p3Ri6msLgAgGSinvgTV93HU1YRySCwZo54MryQox"
      "ApeNom" => "ABALLAY CINTIA DAYANA"                               *******
      "sexo" => "1"                                                     *******
      "Documento" => "37494975"                                     *   *******
      "cuil" => "27-37494975-6"                                         *******
      "email" => "cintiadayanaaballay@gmail.com"                        *******
      "domicilio" => "René Favaloro  B° San Héctor De Valdivieso"       *******
      "localidad" => "VILLA UNIóN"                                       *****
      "telefono" => "3825677429"                                        *******
      "legajo" => "1"                                                   ******
      "f2" => "2"                                                       ******
      "fechaeva" => "2000-01-01"                                        ********
      "titulo_evaluacion" => "mecatroncia"                               ****
      "antiguedad_titulo_evaluacion" => "Antigüedad Titulo Evaluación:"
      "antiguedad_nivel_evaluacion" => "Antigüedad Nivel Evaluación:"
      "antiguedad_uc_evaluacion" => "Antigüedad UC Evaluación:"
      "antecedentes_evaluacion" => "Antecedentes Evaluación:"
      "otros_estudios_evaluacion" => "Otros Estudios Evaluación:"
      "otros_evaluacion" => "Otros Evaluación:"
      "total_evaluacion" => "Total de Evaluación:"
      "u" => "2"

      recibe reclamo, cargo, fecha_reclamo_estante
      "feche_evaluacion" => "2024-08-09"
      "cargo2" => "22"
      observacion se agrego
        */
        $agente = Agentes_Superior::where('idAgente',$request->u)->first(); //traigo a la persona a editar
            $agente->ApeNom = strtoupper($request->ApeNom);
            $agente->Documento = $request->Documento;
            $agente->telefono =$request->telefono;
            $agente->email =$request->email;
            $agente->domicilio = Str::title($request->domicilio);
            $agente->localidad =strtoupper($request->localidad);
            $agente->cuil =$request->cuil;
            $agente->sexo =$request->sexo;
            $agente->legajo =$request->legajo;
            $agente->f2 =$request->f2;
            $agente->fecha_evaluacion = $request->fechaeva;
            $agente->titulo_evaluacion =strtoupper($request->titulo_evaluacion);
            $agente->antiguedad_titulo_evaluacion = $request->antiguedad_titulo_evaluacion;
            $agente->antiguedad_nivel_evaluacion = $request->antiguedad_nivel_evaluacion;
            $agente->antiguedad_uc_evaluacion = $request->antiguedad_uc_evaluacion;
            $agente->antecedentes_evaluacion = $request->antecedentes_evaluacion;
            $agente->otros_estudios_evaluacion = $request->otros_estudios_evaluacion;
            $agente->otros_evaluacion = $request->otros_evaluacion;
            $agente->total_evaluacion = $request->total_evaluacion;
            $agente->observacion = $request->observacion;
            $agente->reclamo = $request->reclamo;
            $agente->fecha_reclamo = $request->fecha_reclamo;
            $agente->estante = $request->estante;
            $agente->cargo = $request->cargo;
            $agente->cargo2 = $request->cargo2;
            $agente->fecha_evaluacion2 = $request->feche_evaluacion2;

            $agente->estado = 0;  
        $agente->save();
        return redirect()->back()->with('ConfirmarEditarAgenteSup','OK');
    }

    public function editarUsuarioSupDocumentos($idUsuario){
        $Usuario = DB::connection('DB4')->table('tb_agentes')
        ->where('idAgente', $idUsuario)
        ->get();
       
        //busco si tiene alguna relacion con titulo exportado mediante su dni
        $DocumentosSup = DB::connection('DB4')->table('tb_titulaciones_exportadas')
        ->where('Documento', $Usuario[0]->Documento)
        ->get();
        $DocumentosSupReg = DB::connection('DB4')->table('tb_titulos_cursos')
        ->where('Documento', $Usuario[0]->Documento)
        ->get();
        //dd($RelSubOrgAgente);
        $datos=array(
            'mensajeError'=>"",
            'Titulos'=>$DocumentosSup,
            'TitulosReg'=>$DocumentosSupReg,
            'mensajeNAV'=>'Panel de Edición de Títulos y Certificados',
            'Usuario'=>$Usuario
            
        );
        //dd($infoPlaza);
        return view('bandeja.Superior.editar_titulo_certificado',$datos);
    }

    //zona de recu titulo y certf
    public function documentosListaSup(){
        $DocumentosSup = DB::connection('DB4')->table('tb_titulaciones_exportadas')
        ->whereNotNull('tb_titulaciones_exportadas.Documento') // Excluye registros donde Documento sea NULL
        ->whereNotNull('tb_titulaciones_exportadas.ApeNom') 
        ->get();
        //dd($UsuariosSup);
               
             
               //dd($RelSubOrgAgente);
               $datos=array(
                   'mensajeError'=>"",
                   'DocumentosLista'=>$DocumentosSup,
                   'mensajeNAV'=>'Panel de Configuración de Documentos Exportados',
               );
               //dd($infoPlaza);
               return view('bandeja.Superior.documentosListaSup',$datos);
    }

    public function documentosListaSupRegistrado(){
        $DocumentosSup = DB::connection('DB4')->table('tb_titulaciones_exportadas')->get();
        //dd($UsuariosSup);
               
             
               //dd($RelSubOrgAgente);
               $datos=array(
                   'mensajeError'=>"",
                   'DocumentosLista'=>$DocumentosSup,
                   'mensajeNAV'=>'Panel de Configuración de Documentos Exportados',
               );
               //dd($infoPlaza);
               return view('bandeja.Superior.documentosListaSup',$datos);
    }

    public function FormRegistrarDocumentoSup(Request $request){
        //dd($request);
        /*
        llego U que es el id
         */
        //llega un solo dato que es u, es el iddel registro en exportado usuario

        //paso 1 buscar el agente en exportado
        $Doc = DB::connection('DB4')->table('tb_titulaciones_exportadas')
        ->where('idtitulaciones_exportadas', $request->u)
        ->first();
        //dd($Doc);
        $usuario =DB::connection('DB4')->table('tb_agentes')
        ->where('Documento', $Doc->Documento)
        ->first();
        //paso 2 insertar el agente en su tabla
        $nuevo = new Titulo_Cursos();
            $nuevo->nombre_titulo = strtoupper($Doc->titulo);
            $nuevo->Documento = $Doc->Documento;
            $nuevo->estado = 0;
            $nuevo->tipo_operacion = 1;
            $nuevo->idAgente = $usuario->idAgente;
        $nuevo->save();

        //paso 3 obtengo el id para poder enviarlo a editar
        //dd($nuevo->idAgente);
        
        
        $usuario = $usuario->idAgente;
        //paso 4 armar y enviar aa editar
        return redirect("/editarUsuarioSupDocumentos/$usuario");

    }

    public function editarTituloSup($idTitulo){
        

        $Titulo = DB::connection('DB4')->table('tb_titulos_cursos')
        ->where('idTitulo_Curso', $idTitulo)
        ->first();
        
        $Operacion = DB::connection('DB4')->table('tb_operacion_titulo')->get();
        $Tipo_Curso = DB::connection('DB4')->table('tb_tipo_curso')->get();
        //dd($RelSubOrgAgente);
        $datos=array(
            'mensajeError'=>"",
            'Operacion'=> $Operacion,
            'Tipo_Curso'=>$Tipo_Curso,
            'mensajeNAV'=>'Panel de Edición de Usuarios',
            'Titulo'=>$Titulo 
            
        );
        //dd($infoPlaza);
        return view('bandeja.Superior.editar_titulo_sup',$datos);
    }


    public function FormActualizarTituloSup(Request $request){
        //dd($request);
        /*
        "_token" => "p3Ri6msLgAgGSinvgTV93HU1YRySCwZo54MryQox"
      "tipo_operacion" => "1"
      "nombre_titulo" => "PROFESOR  PARA LA EDUCACIóN PRIMARIA"
      "fecha_egreso" => "2002-01-01"
      "fecha_registro" => "2000-01-01"
      "institucion" => "Epet 2"
      "num_registro" => "123"
      "ncat" => "20"
      "tipo_curso" => "Nose"
      "otros" => "otro"
      "observacion" => "observacion"
      "u" => "3"
      */
      $titulo = Titulo_Cursos::where('idTitulo_curso',$request->u)->first();
      //dd($titulo);
        $titulo->tipo_operacion = $request->tipo_operacion;
        $titulo->nombre_titulo = strtoupper($request->nombre_titulo);
        $titulo->fecha_egreso = $request->fecha_egreso;
        $titulo->fecha_registro = $request->fecha_registro;
        $titulo->institucion = strtoupper($request->institucion);
        $titulo->num_registro = $request->num_registro;
        $titulo->num_horas_catedras_curso = $request->ncat;
        $titulo->tipo_curso = strtoupper($request->tipo_curso);
        $titulo->otros = $request->otros;
        $titulo->observacion = $request->observacion;
        $titulo->estado=0;

      $titulo->save();
      return redirect()->back()->with('ConfirmarEditarTituloSup','OK');
    }

    public function altaAgenteSup(){
        $sexo = DB::connection('DB4')->table('tb_sexo')->get();
        $sino = DB::connection('DB4')->table('tb_sino')->get();
        //dd($RelSubOrgAgente);
        $datos=array(
            'mensajeError'=>"",
            'Sexos'=>$sexo,
            'Respuestas'=>$sino,
            'mensajeNAV'=>'Panel de Edición de Usuarios'
            
        );
        //dd($infoPlaza);
        return view('bandeja.Superior.alta_usuario_sup',$datos);
    }

    public function FormRegUsuarioSuperior(Request $request){
        //dd($request);
        /*
 "_token" => "p3Ri6msLgAgGSinvgTV93HU1YRySCwZo54MryQox"
      "ApeNom" => "leo martin loyol"
      "sexo" => "2"
      "Documento" => "26731952"
      "cuil" => "26731952"
      "email" => "masterdjmov@gmail.com"
      "domicilio" => "las heras"
      "localidad" => "CAPITAL - LA RIOJA"
      "telefono" => "03804368321"
      "legajo" => "1"
      "f2" => "1"
      "fechaeva" => "2024-02-01"
      "titulo_evaluacion" => "mecatronica"
      "antiguedad_titulo_evaluacion" => "1"
      "antiguedad_nivel_evaluacion" => "1"
      "antiguedad_uc_evaluacion" => "1"
      "antecedentes_evaluacion" => "1"
      "otros_estudios_evaluacion" => "1"
      "otros_evaluacion" => "1"
      "total_evaluacion" => "1"
      "observacion" => "otros datos"
        */
        $agente = new Agentes_Superior(); //traigo a la persona a editar
            $agente->ApeNom = strtoupper($request->ApeNom);
            $agente->Documento = $request->Documento;
            $agente->telefono =$request->telefono;
            $agente->email =$request->email;
            $agente->domicilio = Str::title($request->domicilio);
            $agente->localidad =strtoupper($request->localidad);
            $agente->cuil =$request->cuil;
            $agente->sexo =$request->sexo;
            $agente->legajo =$request->legajo;
            $agente->f2 =$request->f2;
            $agente->fecha_evaluacion = $request->fechaeva;
            $agente->titulo_evaluacion =strtoupper($request->titulo_evaluacion);
            $agente->antiguedad_titulo_evaluacion = $request->antiguedad_titulo_evaluacion;
            $agente->antiguedad_nivel_evaluacion = $request->antiguedad_nivel_evaluacion;
            $agente->antiguedad_uc_evaluacion = $request->antiguedad_uc_evaluacion;
            $agente->antecedentes_evaluacion = $request->antecedentes_evaluacion;
            $agente->otros_estudios_evaluacion = $request->otros_estudios_evaluacion;
            $agente->otros_evaluacion = $request->otros_evaluacion;
            $agente->total_evaluacion = $request->total_evaluacion;
            $agente->observacion = $request->observacion;
            $agente->estado = 0;  
        $agente->save();
        return redirect()->back()->with('ConfirmarRegistrarUsuarioSup','OK');
    }

    public function registrarTituloSuperior(Request $request){
        //dd($request);
        /*
         "_token" => "p3Ri6msLgAgGSinvgTV93HU1YRySCwZo54MryQox"
      "titulo" => "mecatronica"
      "user" => "6"
      "doc" => "26731952"
      */
        $nuevo = new Titulo_Cursos();
            $nuevo->nombre_titulo = strtoupper($request->titulo);
            $nuevo->Documento = $request->doc;
            $nuevo->estado = 0;
            $nuevo->tipo_operacion = 1;
            $nuevo->idAgente = $request->user;
        $nuevo->save();
        return redirect()->back()->with('ConfirmarNuevoTituloRegistrado','OK');
    }


}
