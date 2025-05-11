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
                <h3 class="card-title p-3">Panel de Control POF - CUE COMPLETO: {{session('CUEa')}}</h3>
                <ul class="nav nav-tabs ml-auto p-2">
                  <li class="ml-2"><a class="btn btn-outline-light active" href="#tab_1" data-toggle="tab">Agregar Agente</a></li>
                  <li class="ml-2"><a class="btn btn-outline-light" href="#tab_2" data-toggle="tab">Ver Agentes</a></li>
                  <li class="ml-2"><a class="btn btn-outline-light" href="#tab_3" data-toggle="tab">Extras</a></li> 
                </ul>
              </div><!-- /.card-header -->

              <div class="card-body">
                <div class="tab-content">
                  <div class="tab-pane active" id="tab_1">
                    <h3>Configurar Nuevo Agente / Docente:</h3>
                    <div class="container mt-3 d-block">
                      <form method="POST" action="{{ route('agregarAgenteEscuela') }}" class="formularioNuevoAgenteNodo" id="formularioNuevoAgenteNodoxxx">
                          @csrf
                        <div class="row">
                          <!--primera Card-->
                          <div class="ml-1">
                            <div class="card bg-Suplente">
                              <div class="card-title mt-4 d-flex justify-content-center">
                                <h5 id="DescripcionNombreAgente" class="mb-0">Docente: </h5>
                                <input type="hidden" name="idAgenteNuevoNodo" id="idAgenteNuevoNodo" value="">
                              </div>
                              <div class="card-body">
                                <p class="mb-0">Cargo/Función: <label for="cargo" id="DescripcionCargo"> Sin Selección </label>
                                <input type="hidden" id="CargoSal" name="CargoSal" value="">
                                </p>
                                <p class="mb-0">Esp. Curricular: <label for="DescripcionEspCur" id="DescripcionEspCur"> Sin Selección = Horas Disponibles</label>
                                <input type="hidden" id="idEspCur" name="idEspCur" value="">
                                </p>
                                <p class="mb-0 mr-1">Sit.Rev:
                                <select class="form-control-sm border-0 mb-1" name="SituacionDeRevista" id="SituacionDeRevista">
                                  @foreach ($SituacionDeRevista as $sr)
                                    <option value='{{$sr->idSituacionRevista}}'>{{$sr->Descripcion}}</option>
                                  @endforeach
                                  </select>
                                </p>
                                
                                <p class="mb-0">Sala/Division/Año: 
                                    <select class="form-control-sm border-0" name="idDivision" id="idDivision">
                                    @foreach($Divisiones as $key => $o)
                                        <option value="{{$o->idDivision}}">{{$o->DescripcionCurso}} - "{{$o->DescripcionDivision}}" - {{$o->DescripcionTurno}}</option>
                                    @endforeach
                                    </select>
                                </p>
                                <p class="mb-0">Horas: <input type="number" id="cant_horas" class="form-control-sm border-0" name="cant_horas" style="width:50px" value=""></p>
                                <p class="mb-0">Fecha de Ingreso: <input type="date" id="FechaAltaN" class="form-control-sm border-0" name="FechaAltaN" style="width:125px" value=""></p>
                              </div>
                              <div class="card-footer d-flex justify-content-center">
                                {{-- <a type="button" href="#" class="btn mx-1" data-toggle="tooltip" data-placement="top" title="Licencia">
                                        <span class="material-symbols-outlined pt-1">medical_services</span>
                                      </a> --}}
                                      <a  href="#modalAgente" class="btn mx-1 " data-toggle="modal" data-placement="top" title="Agregar Docente"  data-target="#modalAgente">
                                        <span class="material-symbols-outlined pt-1" >group_add</span>
                                      </a>
                                      <a  href="#modalCargoFuncion" class="btn mx-1 " data-toggle="modal" data-placement="top" title="Cargo/Funcion"  data-target="#modalCargoFuncion">
                                        <span class="material-symbols-outlined pt-1" >library_add</span>
                                      </a>
                                      <a  href="#modalEspCur" class="btn mx-1 " data-toggle="modal" data-placement="top" title="Esp. Curricular"  data-target="#modalEspCur">
                                        <span class="material-symbols-outlined pt-1" >view_timeline</span>
                                      </a>
                              
                                {{-- <a href="#" class="btn mx-1">
                                        <span class="material-symbols-outlined pt-1" data-toggle="modal" data-placement="top" title="Traslado/Afectación">transfer_within_a_station</span>
                                      </a> --}}
                                      <button type="submit" name="btnAgregarAgenteNuevo" class="btn mx-1">
                                        <span class="material-symbols-outlined pt-1" data-toggle="tooltip" data-placement="top" title="Confirmar">done</span>
                                      </button>
                                      
                                      {{-- <button type="submit" name="btnAgregarAgenteNuevo2"  class="btn mx-1">
                                        <span class="material-symbols-outlined pt-1" data-toggle="tooltip" data-placement="top" title="Confirmar">done</span>probar
                                      </button> --}}
                          
                                      {{-- <a href="{{route('agregaNodo',1)}}" class="btn mx-1">
                                        <span class="material-symbols-outlined pt-1" data-toggle="tooltip" data-placement="top" title="Vincular">compare_arrows</span>
                                      </a> --}}
                              </div>
                            </div>
                          </div>
                          <!--Fin primera Card-->
                        </div>
                      </form>
                    </div>

                    <!-- /.modal -->
                    <div class="modal fade" id="modalEspCur">
                      <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                              <h4 class="modal-title">Buscar Espacios Curriculares</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                              <div class="card card-olive">
                                <div class="card-header">
                                    <h3 class="card-title">Buscar Cargos / Funciones: </h3>
                                    {{-- <input type="text" class="form-control" id="btCargos" onkeyup="getCargosFunciones()" placeholder="Ingrese Cargo/Funcion o Codigo Salarial"> --}}
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                  <table id="example" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Descripcion</th>
                                            <th>Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="contenidoEspCur">
                                    @foreach($EspaciosCurriculares as $key => $o)
                                        <tr class="gradeX">
                                          <td>{{$o->idEspacioCurricular}}</td>
                                          <td>{{$o->Descripcion}}
                                            <input type="hidden" id="nomAsignaturaAgenteModal{{$o->idEspacioCurricular}}" value="{{$o->Descripcion}}">
                                            <input type="hidden" id="idAsignaturaAgenteModal{{$o->idEspacioCurricular}}" value="{{$o->idEspacioCurricular}}">
                                          </td>
                                          <td>
                                              <button type="button" name="btnSeleccionar" onclick="seleccionarAsigAgente({{$o->idEspacioCurricular}})">Seleccionar Asignatura</button>
                                          </td>
                                        </tr>
                                      @endforeach
                                    </tbody>
                                  </table>
                                </div>
                                <!-- /.card-body -->
                              </div>
                            </div>
                            <div class="modal-footer justify-content-end">
                                <button type="button" class="btn bg-olive"  data-dismiss="modal">Salir</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                      </div>
                      <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->

                    <!-- /.modal -->
                    <div class="modal fade" id="modalCargoFuncion">
                      <div class="modal-dialog modal-lg">
                      <div class="modal-content">
                          <div class="modal-header">
                            <h4 class="modal-title">Buscar Cargo/Función</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <div class="card card-olive">
                              <div class="card-header">
                                <h3 class="card-title">Buscar Cargos / Funciones: </h3>
                                <input type="text" class="form-control" id="btCargos" onkeyup="getCargosFunciones()" placeholder="Ingrese Cargo/Funcion o Codigo Salarial" autocomplete="off">
                              </div>
                              <!-- /.card-header -->
                              <div class="card-body">
                                <table id="examplex" class="table table-bordered table-striped">
                                  <thead>
                                      <tr>
                                          <th>ID</th>
                                          <th>Apellido y Nombre</th>
                                          <th>DNI</th>
                                          <th>Opciones</th>
                                      </tr>
                                  </thead>
                                  <tbody id="contenidoCargosFunciones">
                                  
                                  </tbody>
                                </table>
                              </div>
                              <!-- /.card-body -->
                            </div>
                          </div>
                          <div class="modal-footer justify-content-end">
                              <button type="button" class="btn bg-olive"  data-dismiss="modal">Salir</button>
                          </div>
                      </div>
                      <!-- /.modal-content -->
                      </div>
                      <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->

                    <div class="modal fade" id="modalAgente">
                      <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                          <div class="modal-header">
                            <div class="modal-title">
                              <h4 class="modal-title">Buscar Agente</h4>
                              <h6 class="">CUE:<b>{{ session('CUEa') }}</b></h6>
                            </div>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                          </div>

                          <div class="modal-body">
                            <div class="card card-olive">
                              <div class="card-header">
                                <div class="form-inline">
                                  <label class="col-auto col-form-label">Lista de Agentes: </label>
                                  <input type="text" autocomplete="off" class="form-control form-control-sm col-5" id="buscarAgente" placeholder="Ingrese DNI sin Puntos" value="">
                                  <button class="btn btn-sm btn-info form-control" type="button" id="traerAgentes" onclick="getAgentes()">Buscar
                                      <i class="fa fa-search ml-2"></i>
                                  </button>
                                </div>
                              </div>
                                <!-- /.card-header -->
                              <div class="card-body">
                                <table id="examplex" class="table table-bordered table-striped">
                                  <thead>
                                      <tr>
                                          <th>ID</th>
                                          <th>Apellido y Nombre</th>
                                          <th>DNI</th>
                                          <th>Opciones</th>
                                      </tr>
                                  </thead>
                                  <tbody id="contenidoAgentes">
                                  
                                  </tbody>
                                </table>
                              </div>
                                <!-- /.card-body -->
                            </div>
                          </div>
                          <div class="modal-footer justify-content-end">
                              <button type="button" class="btn bg-olive"  data-dismiss="modal">Salir</button>
                          </div>
                        </div>
                        <!-- /.modal-content -->
                      </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->
                  </div>
                  <!-- /.tab-pane -->
                  <div class="tab-pane" id="tab_2">
                    {{-- Agente info Inicio--}}
                    <h3>Organizacion: {{$nombreSubOrg}} - CUE: {{ $CueOrg }}</h3>
                    
                    <div class="row" id="contenidoNodos">
                      @php
                        if(session('infoNodos')){
                          $infoNodos = session('infoNodos');
                        }
                      @endphp
                    </div>
                    <div class="card">
                      <div class="card-header">
                        <h3 class="card-title">Agentes</h3>
                      </div>
                      <!-- /.card-header -->
                      <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                          <thead>
                            <tr>
                              <th>Agente</th>
                              <th>Cargo</th>
                              <th>Horas</th>
                              <th>Esp.Cur</th>
                              <th>S.R.</th>
                              <th>F.Alta(Res)</th>
                              <th>Sala/Div</th>
                              <th>Opciones</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($infoNodos as $key => $o)
                            <tr>
                              <td>
                                @if ($o->Nombres != "")
                                  <label id="DescripcionNombreAgente" class="mb-0">({{$o->idNodo}})<b class="text-success">{{$o->Nombres}}</b></label>
                                @else
                                  <label id="DescripcionNombreAgente" class="mb-0">({{$o->idNodo}})<b class="text-danger">VACANTE</b> </label>
                                @endif
                                <input type="hidden" name="idAgente" id="idAgente2" value="{{$o->idAgente}}">
                              </td>
                              <td>
                                <p class=""><label for="cargo" id="DescripcionCargo">{{$o->nomCargo}} - ({{$o->nomCodigo}})</p>
                                <input type="hidden" id="CargoSal2" name="CargoSal" value="{{$o->idCargo}}">
                              </td>
                              <td>
                                <p class="mb-0"><label for="CantidadHoras" id="CantidadHoras">{{$o->CantidadHoras}}</label></p>
                              </td>
                              <td>
                                <p class="mb-0"><label for="DescripcionEspCur" id="DescripcionEspCur">{{$o->nomAsignatura}}</label>
                                <input type="hidden" id="idEspCur2" name="idEspCur" value="{{$o->idAsignatura}}">
                              </td>
                              <td  class="bg-{{$o->nomSitRev}}" title="Detalle: {{$o->Observaciones}}">
                                <p class="mb-0">
                                  @foreach ($SituacionDeRevista as $sr)
                                    @if ($sr->idSituacionRevista == $o->idSituacionRevista)
                                      <label for="SituacionDeRevista" id="SituacionDeRevista">{{$sr->Descripcion}}</label>
                                    @endif
                                  @endforeach
                                </p>
                              </td>
                              <td>
                                <p class="mb-0"><label for="Fa" id="Fa">{{ \Carbon\Carbon::parse($o->FechaDeAlta)->format('d-m-Y')}}</label></p>
                              </td>
                              <td>
                                <p class="mb-0"> 
                                  @foreach($Divisiones as $key => $d)
                                    @if ($d->idDivision == $o->idDivision)
                                      <label for="idDivision" id="idDivision">{{$d->Descripcion}}<br>T: {{$d->DescripcionTurno}}</label>
                                    @endif 
                                  @endforeach
                                </p>
                              </td>
                              <td>
                                {{-- <a type="button" href="#" class="btn mx-1" data-toggle="tooltip" data-placement="top" title="Licencia">
                                  <span class="material-symbols-outlined pt-1">medical_services</span>
                                </a> --}}
                                <a  href="{{route('ActualizarNodoAgente',$o->idNodo)}}" class="btn mx-1 "  data-placement="top" title="Actualizar Docente"  >
                                  <span class="material-symbols-outlined pt-1" >edit_square</span>
                                </a>

                                <!--boton PRUEBA modal historial plaza-->
                                  {{-- @if ($o->PosicionSiguiente != "")
                                    <button type="button" data-toggle="modal" data-target="#modal-{{$o->PosicionSiguiente}}" class="btn mx-1 " >
                                      <span class="material-symbols-outlined pt-1" >history</span>
                                    </button>
                                  @else
                                                    
                                  @endif --}}

                                  {{-- @if ($o->PosicionSiguiente == "")
                                    <a href="{{route('agregaNodo',$o->idNodo)}}" class="btn mx-1 Vincular">
                                      <span class="material-symbols-outlined pt-1" data-toggle="tooltip" data-placement="top" title="Vincular">compare_arrows</span>
                                    </a>
                                  @endif --}}                    
                              </td>
                            </tr>
                            @endforeach
                          </tbody>
                          
                        </table>
                      </div>
                      <!-- /.card-body -->
                    </div>
                  </div>
                  {{-- Agente info Fin --}}
                  
                  <!-- /.tab-pane -->
                  <div class="tab-pane" id="tab_3">
                    {{-- Agente info Inicio--}}
                    <h3>Organizacion: {{$nombreSubOrg}} - CUE: {{ $CueOrg }}</h3>
                    
                    <div class="row" id="contenidoNodos">
                      @php
                        if(session('infoNodos')){
                          $infoNodos = session('infoNodos');
                        }
                      @endphp
                    </div>
                    <div class="card">
                      <div class="card-header">
                        <h3 class="card-title">Agentes</h3>
                      </div>
                      <!-- /.card-header -->
                      <div class="card-body">
                        @foreach ($infoNodos as $key => $o)
                          <div class="container mt-3 d-block">
                            <form method="POST" action="" class="">
                            @csrf
                              <div class="row">
                                <!--primera Card-->
                                <div class="ml-1">
                                  <div class="card shadow-lg bg-{{$o->nomSitRev}}">
                                    <div class="card-title mt-4 d-flex justify-content-center">
                                    @if ($o->Nombres != "")
                                      <h5 id="DescripcionNombreAgente" class="mb-0">({{$o->idNodo}})Docente: {{$o->Nombres}} </h5>
                                    @else
                                      <h5 id="DescripcionNombreAgente" class="mb-0">({{$o->idNodo}})Docente: <b>VACANTE</b> </h5>
                                    @endif
                                      
                                      <input type="hidden" name="idAgente" id="idAgente2" value="{{$o->idAgente}}">
                                    </div>
                                    <div class="card-body">
                                      <p class="mb-0">Cargo/Función: <label for="cargo" id="DescripcionCargo">{{$o->nomCargo}} - ({{$o->nomCodigo}})</label>
                                      <input type="hidden" id="CargoSal2" name="CargoSal" value="{{$o->idCargo}}">
                                      </p>
                                      <p class="mb-0">Esp. Curricular: <label for="DescripcionEspCur" id="DescripcionEspCur">{{$o->nomAsignatura}}</label>
                                      <input type="hidden" id="idEspCur2" name="idEspCur" value="{{$o->idAsignatura}}">
                                      </p>
                                      <p class="mb-0">Sit.Rev: 
                                      
                                        @foreach ($SituacionDeRevista as $sr)
                                          @if ($sr->idSituacionRevista == $o->idSituacionRevista)
                                            <label for="SituacionDeRevista" id="SituacionDeRevista">{{$sr->Descripcion}}</label>
                                          @endif
                                        @endforeach
                                        
                                      </p>
                                      
                                      <p class="mb-0">Sala/Division/Año: 
                                          @foreach($Divisiones as $key => $d)
                                            @if ($d->idDivision == $o->idDivision)
                                              <label for="idDivision" id="idDivision">{{$d->Descripcion}} - {{$d->DescripcionTurno}}</label>
                                            @endif 
                                          @endforeach
                                          
                                      </p>
                                      <p class="mb-0">Horas: <label for="CantidadHoras" id="CantidadHoras">{{$o->CantidadHoras}}</label></p>
                                      <p class="mb-0">Fecha de Alta(Res): <label for="Fa" id="Fa">{{ \Carbon\Carbon::parse($o->FechaDeAlta)->format('d-m-Y')}}</label></p>
                                    </div>
                                    <div class="card-footer">
                                      {{-- <a type="button" href="#" class="btn mx-1" data-toggle="tooltip" data-placement="top" title="Licencia">
                                        <span class="material-symbols-outlined pt-1">medical_services</span>
                                      </a> --}}
                                      <a  href="{{route('ActualizarNodoAgente',$o->idNodo)}}" class="btn mx-1 "  data-placement="top" title="Actualizar Docente"  >
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
                                <!--Flechita-->
                                @if($o->PosicionSiguiente != "")
                                @php
                                  //traigo los nodos
                                  $infoNodoSiguiente=DB::table('tb_nodos')
                                  ->where('tb_nodos.idNodo',$o->PosicionSiguiente)
                                  ->leftjoin('tb_suborganizaciones', 'tb_suborganizaciones.cuecompleto', 'tb_nodos.CUE')
                                  ->leftjoin('tb_agentes', 'tb_agentes.idAgente', 'tb_nodos.Agente')
                                  ->leftjoin('tb_asignaturas', 'tb_asignaturas.idAsignatura', 'tb_nodos.Asignatura')
                                  ->leftjoin('tb_cargossalariales', 'tb_cargossalariales.idCargo', 'tb_nodos.CargoSalarial')
                                  ->leftjoin('tb_situacionrevista', 'tb_situacionrevista.idSituacionRevista', 'tb_nodos.SitRev')
                                  ->leftjoin('tb_divisiones', 'tb_divisiones.idDivision', 'tb_nodos.Division')
                                  ->select(
                                      'tb_agentes.*',
                                      'tb_nodos.*',
                                      'tb_asignaturas.idAsignatura',
                                      'tb_asignaturas.Descripcion as nomAsignatura',
                                      'tb_cargossalariales.idCargo',
                                      'tb_cargossalariales.Cargo as nomCargo',
                                      'tb_cargossalariales.Codigo as nomCodigo',
                                      'tb_situacionrevista.idSituacionRevista',
                                      'tb_situacionrevista.Descripcion as nomSitRev',
                                      'tb_divisiones.idDivision',
                                      'tb_divisiones.Descripcion as nomDivision',
                                  )
                                  ->get();
                                @endphp
                                <div class="d-flex align-self-center ml-2 mb-4">
                                  <div class="align-items-center st0"></div>
                                  <div class="align-items-center st2"></div>
                                </div>

                                <!--segunda Card-->
                                @foreach ($infoNodoSiguiente as $sig)
                                  <div class="ml-1">
                                  <div class="card shadow-lg bg-{{$infoNodoSiguiente[0]->nomSitRev}}">
                                    <div class="card-title mt-4 d-flex justify-content-center">
                                    @if ($sig->Nombres != "")
                                      <h5 id="DescripcionNombreAgente" class="mb-0">({{$sig->idNodo}})-Docente: {{strtoupper($sig->Nombres)}} <span class="material-symbols-outlined text-danger">history</span></h5>
                                    @else
                                      <h5 id="DescripcionNombreAgente" class="mb-0">({{$sig->idNodo}})Docente: <b>VACANTE</b> </h5>
                                    @endif
                                      
                                      <input type="hidden" name="idAgente" id="idAgente2" value="{{$sig->idAgente}}">
                                    </div>
                                    <div class="card-body">
                                      <p class="mb-0">Cargo/Función: <label for="cargo" id="DescripcionCargo">{{$sig->nomCargo}} - ({{$sig->nomCodigo}})</label>
                                      <input type="hidden" id="CargoSal2" name="CargoSal" value="{{$sig->idCargo}}">
                                      </p>
                                      <p class="mb-0">Esp. Curricular: <label for="DescripcionEspCur" id="DescripcionEspCur">{{$sig->nomAsignatura}}</label>
                                      <input type="hidden" id="idEspCur2" name="idEspCur" value="{{$sig->idAsignatura}}">
                                      </p>
                                      <p class="mb-0">Sit.Rev: 
                                      
                                        @foreach ($SituacionDeRevista as $sr)
                                          @if ($sr->idSituacionRevista == $sig->idSituacionRevista)
                                            <label for="SituacionDeRevista" id="SituacionDeRevista">{{$sr->Descripcion}}</label>
                                          @endif
                                        @endforeach
                                        
                                      </p>
                                      
                                      <p class="mb-0">Sala/Division/Año: 
                                          @foreach($Divisiones as $key => $d)
                                            @if ($d->idDivision == $sig->idDivision)
                                              <label for="idDivision" id="idDivision">{{$d->Descripcion}} - {{$d->DescripcionTurno}}</label>
                                            @endif 
                                          @endforeach
                                          
                                      </p>
                                      <p class="mb-0">Horas: <label for="CantidadHoras" id="CantidadHoras">{{$sig->CantidadHoras}}</label></p>
                                      <p class="mb-0">Fecha de Alta(Res): <label for="Fa" id="Fa">{{ \Carbon\Carbon::parse($sig->FechaDeAlta)->format('d-m-Y')}}</label></p>
                                    </div>
                                    <div class="card-footer">
                                      {{-- <a type="button" href="#" class="btn mx-1" data-toggle="tooltip" data-placement="top" title="Licencia">
                                        <span class="material-symbols-outlined pt-1">medical_services</span>
                                      </a> --}}
                                      <a  href="{{route('ActualizarNodoAgente',$sig->idNodo)}}" class="btn mx-1 "  data-placement="top" title="Actualizar Docente"  >
                                        <span class="material-symbols-outlined pt-1" >edit_square</span>
                                      </a>
                                      {{-- <a href="{{route('agregaNodo',$o->idNodo)}}" class="btn mx-1">
                                        <span class="material-symbols-outlined pt-1" data-toggle="tooltip" data-placement="top" title="Vincular">compare_arrows</span>
                                      </a> --}}
                                    </div>
                                  </div>
                                </div>
                                @endforeach
                                <!--Fin segunda Card-->
                                @endif
                              </div>
                            </form>
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
            'Se cancelo la desvinculacion, ese nodo esta relacionado con otro Agente',
            'Se cancelo el proceso',
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


