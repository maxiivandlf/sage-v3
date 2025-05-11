@extends('layout.app')

@section('Titulo', 'Sage2.0 - Movimientos')
@section('ContenidoPrincipal')
  
  <section id="container">
    <section id="main-content">
      <section class="content-wrapper">
        <div class="row">
          <div class="col-12">
            <!-- Custom Tabs -->
            <div class="card card-lightblue">
              <div class="card-header d-flex p-0">
                <h3 class="card-title p-3">Panel de Control POF - CUE COMPLETO: {{session('CUECOMPLETO')}}</h3>
              </div><!-- /.card-header -->

              <div class="card-body">
                <div class="tab-content">
                <!-- /.tab-pane -->
                  <div class="" id="tab_3">
                    {{-- Agente info Inicio--}}
                    <h3>Organización: {{$Nombre_Institucion}} - CUE: {{ $CUECOMPLETO }}</h3>
                    
                    <div class="row" id="contenidoNodos">
                      @php
                        if(session('infoNodos')){
                          //$infoNodos = session('infoNodos');
                        }
                      @endphp
                    </div>
                    <div class="card">
                      <div class="card-header d-flex justify-content-between">
                        <h3 class="card-title">Agentes</h3>
                        <div">
                          <form method="POST" action="{{ route('activarFiltro') }}" class="formularioFiltro" id="formularioFiltro">
                            @csrf
                            <select class="form-control-sm border-0" name="idDivision" id="idDivision">
                              <option value="">Sin Filtro Activo</option>
                              @foreach($Divisiones as $key => $o)
                                @if ($o->idDivision == session('filtroDivision'))
                                  <option value="{{$o->idDivision}}" selected="selected">{{$o->DescripcionDivi}} - {{$o->DescripcionCurso}} - "{{$o->DescripcionDivision}}" - {{$o->DescripcionTurno}}</option>
                                @else
                                  <option value="{{$o->idDivision}}">{{$o->DescripcionDivi}} - {{$o->DescripcionCurso}} - "{{$o->DescripcionDivision}}" - {{$o->DescripcionTurno}}</option>
                                @endif
                              @endforeach
                              </select>
                              <button type="submit" name="btnEnviar"><i class="fa fa-check"></i></button>
                          </form>

                        </div>
                      </div>
                      <!-- /.card-header -->
                      <div class="card-body">
                        @foreach ($infoNodos as $key => $o)
                          <div class=" mt-3 d-block">
                            @if ($o->PosicionAnterior == null)
                              <div class="row" style="border:1px solid #CCC;padding:5px">
                                    @include('bandeja.AG.Servicios.nodo.individualNodo', ['infoNodo' => $o])
                              </div>
                            @endif
                            
                          </div>
                        @endforeach
                      </div>
                      <!-- /.card-body -->
                    </div>
                  </div>
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- ./card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </section>
    </section>
  </section>
@endsection

 


@section('Script')
@section('Script')
    <script src="{{ asset('js/funcionesvarias.js') }}"></script>
   

     @if (session('ConfirmarNuevoAgenteNodo')=='OK')
        <script>
        Swal.fire(
            'Registro guardado',
            'Se creo un nuevo registro de un Agente',
            'success'
                )
        </script>
    @endif
    @if (session('ConfirmarNuevoNodo')=='OK')
        <script>
        Swal.fire(
            'Nodo Agregado',
            'Se creo un registro en Blanco, puede agregar los datos del Agente',
            'success'
                )
        </script>
    @endif
    @if (session('ConfirmarNuevoNodoDerechoFallo')=='OK')
        <script>
        Swal.fire(
            'Nodo Agregado',
            'No se permite crear un registro en blanco entre dos agentes',
            'error'
                )
        </script>
    @endif
    @if (session('ConfirmarBorradoNodo')=='OK')
        <script>
        Swal.fire(
            'Nodo Borrado',
            'Se borro el nodo, no se puede recuperar',
            'success'
                )
        </script>
    @endif
    @if (session('ConfirmarBorradoNodoAnulado')=='OK')
        <script>
        Swal.fire(
            'Se cancelo la desvinculaciÓn, ese Bloque esta relacionado con otro Agente',
            'Se canceló el proceso',
            'error'
                )
        </script>
    @endif
    @if (session('ConfirmarLimpieza')=='OK')
        <script>
        Swal.fire(
            'Aviso - Volver a Iniciar',
            'Se Eliminó todo el contenido de Bloques de Agentes(Docentes, PSG, etc) y Novedades, por favor, salga de la sesión y entre otra vez',
            'success'
                )
        </script>
    @endif
    @if (session('ConfirmarLimpiezaError')=='OK')
        <script>
        Swal.fire(
            'Aviso',
            'No tiene permiso de Borrado Completo, debe solicitarlo a los Administradores',
            'error'
                )
        </script>
    @endif
<script>

    $('.formularioNuevoAgenteNodo').submit(function(e){
      if($("#idAgente").val()=="" ||
        $("#CargoSal").val()=="" ||
        //$("#idEspCur").val()=="" ||
        $("#cant_horas").val()==""){
        console.log("error")
         e.preventDefault();
          Swal.fire(
            'Error',
            'No se pudo agregar, hay datos incompletos',
            'error'
                )
      }else{
        e.preventDefault();
        Swal.fire({
            title: 'Esta seguro de querer agregar el Agente?',
            text: "Prueba por ahora",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, crear el registro!'
          }).then((result) => {
            if (result.isConfirmed) {
              this.submit();
              //prueba();
            }
          })
      }
    })
    

    $('.ConfirmarAgregarAgenteANodo').submit(function(e){
        e.preventDefault();
        Swal.fire({
            title: 'Esta seguro de querer agregar el Agente?',
            text: "Prueba por ahora",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, crear el registro!'
          }).then((result) => {
            if (result.isConfirmed) {
              this.submit();
            }
          })
    })
</script>
@endsection

<?php
  function recursiva($nodo) {
    //variables a usar
    $Agente="";
    $idAgente="";
    $SitRev="";
    $CargoSalarial="";
    $nomSitRev="";
    $Nombres="";
    $nomCargo="";
    $nomCodigo="";
    $idCargo="";
    $Descripcion="";
    $DescripcionTurno="";
    $Asignatura="";
    $idAsignatura="";

    //recupero el nodo pasado para recursiva
    $recNodo=DB::table('tb_nodos')
      ->where('tb_nodos.idNodo',$nodo)
      ->select('tb_nodos.*')
      ->get();

    //dd($recNodo);
    if($recNodo[0]->Agente==null){
      $idAgente="";
      $Nombres="VACANTE";
    }else{
     $iAgente = DB::table('tb_desglose_agentes')
      ->where('tb_desglose_agentes.docu',$recNodo[0]->Agente) //busco el dni
      ->select('*')
      ->get();
      //dd($iAgente);
      $idAgente=$iAgente[0]->docu;
      $Nombres=$iAgente[0]->nomb;
    }

    //busco los datos
    if($recNodo[0]->SitRev==null){
      $nomSitRev="SR";
    }else{
      $SituacionRevista = DB::table('tb_situacionrevista')
        ->where('tb_situacionrevista.idSituacionRevista',$recNodo[0]->SitRev)
        ->select(
          'tb_situacionrevista.idSituacionRevista',
          'tb_situacionrevista.Descripcion as nomSitRev',
                )
        ->get();
        $nomSitRev=$SituacionRevista[0]->nomSitRev;
    }
     
    if($recNodo[0]->CargoSalarial==null){
      $nomCargo="Cargo";
      $nomCodigo="Cod";
      $idCargo="";
    }else{
      $iCargo = DB::table('tb_cargossalariales')
            ->where('tb_cargossalariales.idCargo',$recNodo[0]->CargoSalarial)
            ->select(
                  'tb_cargossalariales.idCargo',
                  'tb_cargossalariales.Cargo as nomCargo',
                  'tb_cargossalariales.Codigo as nomCodigo'
                  )
            ->get();
      $nomCargo=$iCargo[0]->nomCargo;
      $nomCodigo=$iCargo[0]->nomCodigo;
      $idCargo=$iCargo[0]->idCargo;
    }

    if($recNodo[0]->Division==null){
      $Descripcion="";
      $DescripcionTurno="";
    }else{
      $iDivTur = DB::table('tb_divisiones')
            ->where('tb_divisiones.idDivision',$recNodo[0]->Division)
            ->join('tb_division','tb_division.idDivisionU','tb_divisiones.Division')
            ->join('tb_turnos', 'tb_turnos.idTurno', 'tb_divisiones.Turno')
            ->select(
                  'tb_divisiones.Descripcion as Descripcion',
                  'tb_turnos.Descripcion as DescripcionTurno',
                  'tb_division.DescripcionDivision',
                  )
            ->get();
      $Descripcion=$iDivTur[0]->Descripcion;
      $DescripcionTurno=$iDivTur[0]->DescripcionTurno;
      $DescripcionDivision=$iDivTur[0]->DescripcionDivision;
      
    }   

    if($recNodo[0]->Asignatura==null){
      $Asignatura="";
      $idAsignatura="";
    }else{
      $iAsignatura = DB::table('tb_asignaturas')
            ->where('tb_asignaturas.idAsignatura',$recNodo[0]->Asignatura)
            ->select(
                  'tb_asignaturas.Descripcion as Descripcion',
                  'tb_asignaturas.idAsignatura',
                  )
            ->get();
      $Asignatura=$iAsignatura[0]->Descripcion; 
      $idAsignatura=$iAsignatura[0]->idAsignatura;     
    } 
    // dd($SituacionRevista[0]->nomSitRev); 
?>
    <!--primera Card-->
    <div class="ml-1">
      <div class="card shadow-lg bg-{{$nomSitRev}}">
        <div class="card-title mt-4 d-flex justify-content-center">
          {{-- $o->Nombres sale de agente--}}
          @if ($recNodo[0]->Agente != null)
            <h5 id="DescripcionNombreAgente" class="mb-0">({{$recNodo[0]->idNodo}}) {{$Nombres}} </h5>
          @else
            <h5 id="DescripcionNombreAgente" class="mb-0">({{$recNodo[0]->idNodo}}) <b class="text-danger">VACANTE</b> </h5>
          @endif
                                            
          <input type="hidden" name="idAgente" id="idAgente2" value="{{$idAgente}}">
        </div>
        <div class="card-body">
          <p class="mb-0">Cargo: <label for="cargo" id="DescripcionCargo">{{$nomCargo}} - ({{$nomCodigo}})</label>
            <input type="hidden" id="CargoSal2" name="CargoSal" value="{{$idCargo}}">
          </p>
          <p class="mb-0">S.R:<b>{{$nomSitRev}}</b> (<b>{{$Descripcion}} - {{$DescripcionDivision}} - {{$DescripcionTurno}} </b>)</p>
          
          <p class="mb-0">
            Cant. Horas: <label for="CantidadHoras" id="CantidadHoras">{{$recNodo[0]->CantidadHoras}}</label> - 
            F.A.Inst: <label for="Fa" id="Fa">{{ \Carbon\Carbon::parse($recNodo[0]->FechaDeAlta)->format('d-m-Y')}}</label> / 
            F.A.Junta: <label for="Fa2" id="Fa2">{{ \Carbon\Carbon::parse($recNodo[0]->FechaDeAltaJuetaeno)->format('d-m-Y')}}</label>
            </p>
            
            <div class="d-flex justify-content-between">
              <div>Licencia: <label for="Licencia" id="Licencia">{{$recNodo[0]->LicenciaActiva}}</label></div>
              <div>
                <form method="POST" action="{{ route('controlAsistencia') }}" class="formcontrolAsistencia{{$recNodo[0]->idNodo}}" id="formcontrolAsistencia{{$recNodo[0]->idNodo}}">
                  <div>Cant.Asistencia: 
                    @csrf
                    <input type="number" value="{{$recNodo[0]->CantidadAsistencia}}" id="cantidadAsistencia{{$recNodo[0]->idNodo}}" style="border: none; outline: none; background-color: transparent;font-weight:bold;width:40px">
                   
                    <input type="hidden" name="idn" value="{{{$recNodo[0]->idNodo}}}">
                    
                  </div>
                </form>
              </div>
              <div class="d-flex justify-content-between">
                <button id="incrementar" onclick="actualizarAsistencia(1,{{$recNodo[0]->idNodo}})">+</button>
                <button id="decrementar" onclick="actualizarAsistencia(-1,{{$recNodo[0]->idNodo}})">-</button>
              </div>
            </div>
              
            
        </div>
        <div class="card-footer">
          {{-- <a type="button" href="#" class="btn mx-1" data-toggle="tooltip" data-placement="top" title="Licencia">
            <span class="material-symbols-outlined pt-1">medical_services</span>
            </a> --}}
          <a  href="{{route('ActualizarNodoAgente',$recNodo[0]->idNodo)}}" class="btn mx-1 "  data-placement="top" title="Actualizar Docente"  >
            <span class="material-symbols-outlined pt-1" >edit_square</span>
          </a>
          {{-- @if ($o->PosicionSiguiente == "")
            <a href="{{route('agregaNodo',$o->idNodo)}}" class="btn mx-1 Vincular">
              <span class="material-symbols-outlined pt-1" data-toggle="tooltip" data-placement="top" title="Vincular">compare_arrows</span>
            </a>
          @endif --}}
        </div>
      </div>
    </div>
  <!--Fin primera Card-->
<?php
        if ($recNodo[0]->PosicionSiguiente==null || $recNodo[0]->PosicionSiguiente=="") {
            return 1;
        } else {
          //armo el proceso de flecha antes de irse a buscar otro nodo
?>
        <!--Flechita-->
        <div class="d-flex align-self-center ml-2 mb-4">
          <div class="align-items-center st0"></div>
          <div class="align-items-center st2"></div>
        </div>
<?php
    
        
          return recursiva($recNodo[0]->PosicionSiguiente);//envio el nodo a analizar
        }
    }
?>

