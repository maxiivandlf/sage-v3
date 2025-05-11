@extends('layout.app')

@section('Titulo', 'Sage2.1 - POF MH')

@section('LinkCSS')
  <link rel="stylesheet" href="{{ asset('css/pofmh.css') }}">
  <style>
  .nav-item.disabled {
      pointer-events: none;
      opacity: 0.5;
  }
  </style>
@endsection
@section('ContenidoPrincipal')

<section id="container" >
    <section id="main-content">
        <section class="content-wrapper">
          <div class="container-fluid"> 

            {{--Conformidad--}}   
            <div class="row"> 
              <div class="alert alert-danger">
                <h4>CONFORMIDAD</h4>
                <p>
                  Esta declaración se realiza en cumplimiento de las normativas vigentes, 
                  incluyendo la Ley N° 25.164 de Ética en la Función Pública, la Ley N° 25.326 de Protección de 
                  Datos Personales y en conformidad con la Ley N° 27.275 de Acceso a la Información Pública. 
                  Autorizo ​​al Director/a de <b>{{$institucionExtension->Nombre_Institucion}}</b> a registrar y 
                  digitalizar los datos consignados en esta declaración, los cuales serán tratados exclusivamente 
                  en el marco de lo establecido por las normativas antes mencionadas.
                </p>
              </div>
            </div>

            {{--Datos Institucional--}}
            <div class="card alert-warning row">
              <div class="alert alert-dismissible" id="mensajeprincipalescuela">
                @php
                    // Obtener la fecha actuals
                    \Carbon\Carbon::setLocale('es');
                    $fechaActual = \Carbon\Carbon::now();
                    $fechaFormateada = $fechaActual->format('d/m/Y');
                    $mesDeControl = $fechaActual->translatedFormat('F'); // Formato de mes en español
                @endphp
                  <div class="row">
                    <div class="col-sm-12 col-md-6">
                      <h5><i class="icon fas fa-exclamation-triangle"></i> Importante!</h5>
                      <h5 id="datosInst">Institución: <b>{{$institucionExtension->Nombre_Institucion}}</b> - CUE: <b>{{$institucionExtension->CUE}}<span style="color:red">(CÓDIGO INTERNO)</span></b> - Turno(cuenta del Usuario): <b>{{$institucionExtension->Descripcion}}</b></h5>
                      <input type="hidden" name="valCUE" id="valCUE" value="{{$institucionExtension->CUECOMPLETO}}">
                      <input type="hidden" name="valCUEInd" id="valCUEInd" value="{{$institucionExtension->CUE}}">
                      <input type="hidden" name="valTurno" id="valTurno" value="{{$institucionExtension->idTurnoUsuario}}">
                      <input type="hidden" name="valIdExt" id="valIdExt" value="{{$institucionExtension->idInstitucionExtension}}">
                      <h5>Nivel: <b>{{$institucionExtension->NivelEnsenanza}}</b></h5>
                    </div>
                    <div class="col-sm-12 col-md-6">
                      <h5>Cue Ext: <b>{{$institucionExtension->CUECOMPLETO}}<br><small style="color:red">(CÓDIGO INTERNO SAGE)</small></b></h5>
                      <h5>Fecha: <b>{{ $fechaFormateada }}</b></h5>
                      <h5>Mes de Control: <b>{{ ucfirst($mesDeControl) }}</b></h5>
                      <button type="button" class="btn confirm-btn" id="confirmBtn" style="color:white">
                        <i class="fas fa-file-export"></i>
                        Bajar Copia PDF/Excel
                      </button>
                    </div>
                  </div>
              </div>
            </div>


            {{--Botones--}}
            <form id="excelForm row">
                @csrf <!-- Agregar el token CSRF de Laravel -->
            
                <!-- Botón para agregar la primera fila -->
                <div class="row align-items-center container-fluid">
                  <div class="botonera col-md-8 col-sm-12">
                    <div class="botonera-izquierda">
                      <button type="button" class="add-first-row-btn" id="addFirstRowBtn">
                        <i class="fas fa-plus"></i> Crear primera fila
                      </button>
                      <button type="button" class="add-ultimo-row-btn" id="addLastRow">
                          <i class="fas fa-plus-circle"></i> Crear Fila al Último
                      </button>
                      <button type="button" class="ir-al-ultimo-btn" id="irAlUltimoBtn">
                        <i class="fas fa-arrow-down"></i> Ir al Último
                      </button>
                    </div>
                  </div>

                  {{--PAGINACIÓN--}}
                  <form id="paginationForm" method="GET" action="{{ url()->current() }}">
                    <div class="d-flex col-md-4 col-sm-12 justify-content-center">
                      <input type="hidden" name="page" value="{{ request('page', 1) }}"> <!-- Campo oculto para la página actual -->
                      <div class="">
                          <label class="" for="perPage">Filas por página:</label>
                          <select class="form-control mb-1" name="perPage" id="perPage" onchange="document.getElementById('paginationForm').submit();">
                              <option value="50" {{ request('perPage') == 75 ? 'selected' : '' }}>75 filas</option>
                              <option value="all" {{ request('perPage') == 'all' ? 'selected' : '' }}>Ver todas las filas</option>
                          </select>
                          {{ $infoPofMH->links('pagination::bootstrap-4') }}
                      </div>
                    </div>
                  </form>
                </div>

                {{--BUSCADOR--}}
                <div class="container-fluid m-1">
                  <form id="searchForm" class="row col-12">
                    <div class="input-group">
                      <input type="search" id="searchInput" class="form-control" placeholder="Buscar Apellido, Nombre o N° de DNI">
                    </div>
                  </form>
                </div>

                {{--POFMH--}}
                <div class="content m-0">
                  <div class="container-fluid">
                      <div class="card table-responsive" id="cardPOFMH">
                          <table id="POFMH">
                              <thead class="card-header">
                                  <tr >
                                      <th class="custom-5rem" id="tablaarriba">#ID</th>
                                      <th class="custom-5rem">Orden</th>
                                      <th class="custom-8rem">DNI</th>
                                      <th class="custom-15rem">Apellido y Nombre</th>

                                      <th class="custom-30rem">Cargo de Origen en la Institución</th>
                                      
                                      <th class="custom-15rem">Sit.Rev</th>
                                      <th class="custom-5rem">Horas</th>
                                      <th class="custom-13rem">Antigüedad Docente</th>
                                      <th class="custom-33rem">Código Cargo</th>

                                      <th class="custom-20rem">Aula</th>
                                      <th class="custom-8rem">Division</th>
                                      <th class="custom-15rem">Indicar Turno Agente</th>

                                      <th class="custom-18rem">Esp.Cur</th>
                                      <th class="custom-5rem">Matricula</th>
                                      <th class="custom-9rem">Posesión del Cargo</th>
                                      <th class="custom-9rem">Designado al cargo</th>
                                      <th class="custom-13rem">Condición</th>
                                      <th class="custom-13rem">¿En el Aula?</th>

                                      <th class="custom-33rem">Tipo-Motivo-Art.Licencia</th>
                                      <th class="custom-20rem">Otros Datos por Condición</th>
                                      <th class="custom-9rem">Desde</th>
                                      <th class="custom-9rem">Hasta</th>

                                      <th class="custom-8rem">DNI Suplente</th>

                                      <th class="custom-8rem">Adjuntos</th>
                                      {{-- <th class="custom-5rem">Asistencia</th>
                                      <th class="custom-5rem">Justificada</th>
                                      <th class="custom-5rem">Injustificada</th> --}}
                                      
                                      <th class="custom-33rem">Observaciones</th>

                                      <th class="custom-33rem">Carrera</th>
                                      <th class="custom-33rem">Orientación</th>
                                      <th class="custom-33rem">Titulo</th>

                                      <th class="custom-13rem">Acción</th>
                                      <th class="custom-33rem">Observación Supervisión</th>
                                  </tr>
                              </thead>
                              <tbody class="card-body direct-chat-messages">
                                @if ($infoPofMH->isNotEmpty())
                                  @foreach ($infoPofMH as $fila)
                                    <tr data-id="{{$fila->idPofmh}}" class="fila">
                                      <td data-id="{{$fila->idPofmh}}">
                                        <div>{{$fila->idPofmh}}</div>
                                        <input type="checkbox" data-id="{{$fila->idPofmh}}">
                                      </td>
                                      <td>
                                          <input type="text" name="dato1[]" value="{{$fila->orden}}" class="orden-input" data-id="{{$fila->idPofmh}}" disabled pattern="^\d+(\.\d+)?$" title="Por favor, ingresa un número decimal válido. Ejemplo: 1.0 o 2.5, etc">
                                      </td>
                                      <td>
                                          <input type="text" name="dato2[]" value="{{$fila->Agente}}" class="dni-input" id="dni-input-{{ $fila->idPofmh }}" data-id="{{$fila->idPofmh}}" disabled>
                                      </td>
                                      <!-- Otros campos -->
                                      <td>
                                        <input type="text" name="dato3[]" value="{{$fila->ApeNom}}" class="apenom-input" id="apenom-input-{{ $fila->idPofmh }}" data-id="{{$fila->idPofmh}}" disabled>
                                      </td>
                                      <td>
                                        <select class="form-control origen-input" name="Origen"  data-id="{{$fila->idPofmh}}" id="Origen">
                                          <option value="-1" disabled selected>SELECCIONE UNA OPCIÓN</option>
                                          @php
                                            $origenUnico=0;
                                            $nombreUnico="";
                                          @endphp
                                          @foreach ($CargosCreados as $cargo)
                                            @if ($cargo->idOrigenCargo == $fila->Origen)
                                              @php
                                                $origenUnico=$cargo->idOrigenCargo; //respaldo linea a linea esta opcions
                                                $nombreUnico =$cargo->nombre_cargo_origen;
                                              @endphp
                                              <option value="{{$cargo->idOrigenCargo}}" selected>{{$cargo->nombre_cargo_origen}}</option>
                                            @else
                                              <option value="{{$cargo->idOrigenCargo}}">{{$cargo->nombre_cargo_origen}}</option>
                                            @endif   
                                          @endforeach
                                        </select>
                                      </td>
                                      
                                      <td>
                                        <select class="form-control sitrev-input" name="SitRev" id="SitRev" data-id="{{$fila->idPofmh}}">
                                          <option value="-1" disabled selected>SELECCIONE UNA OPCIÓN</option>
                                          @foreach($SitRev as $key => $o)
                                              @if ($o->idSituacionRevista == $fila->SitRev)
                                                  <option value="{{$o->idSituacionRevista}}" selected="selected">{{$o->Descripcion}}</option>
                                              @else
                                                  <option value="{{$o->idSituacionRevista}}">{{$o->Descripcion}}</option>
                                              @endif
                                          @endforeach
                                        </select>
                                      </td>
                                      <td>
                                        <input type="text" name="Horas" value="{{$fila->Horas}}" class="horas-input" data-id="{{$fila->idPofmh}}" disabled>
                                      </td>
                                      <td>
                                        <input type="text" name="Antiguedad" value="{{$fila->Antiguedad}}" class="antiguedad-input" data-id="{{$fila->idPofmh}}" disabled>
                                      </td>
                                      <td>
                                        <select class="form-control cargo-input" name="CargoSalarial"  data-id="{{$fila->idPofmh}}" id="CargoSalarial">
                                          <option value="-1" disabled selected>SELECCIONE UNA OPCIÓN</option>
                                          @foreach ($CargosSalariales as $cargo)
                                            @if ($cargo->idCargo == $fila->Cargo)
                                              <option value="{{$cargo->idCargo}}" selected><b>({{$cargo->Codigo}})</b> - {{$cargo->Cargo}}</option>
                                            @else
                                              <option value="{{$cargo->idCargo}}"><b>({{$cargo->Codigo}})</b> - {{$cargo->Cargo}}</option>
                                            @endif
                                          @endforeach
                                        </select>
                                      </td>
                                      <td>
                                        @if ($nombreUnico && $nombreUnico == 'HORAS DOCENTES' || $nombreUnico == 'PRECEPTOR/A')
                                         <select class="form-control aula-input" name="Aula" id="Aula" data-id="{{$fila->idPofmh}}">
                                           <option value="-1" disabled selected>SELECCIONE UNA OPCIÓN</option>
                                           
                                           @php
                                             $filtro =[22,23,24,25,26,27,29,30,49];
                                             $aulas = DB::connection('DB7')->table('tb_aulas')
                                             ->whereIn('idAula',$filtro)->get();
                                             
                                           @endphp
                                           @foreach($aulas as $key => $o)
                                               @if ($o->idAula == $fila->Aula)
                                                 <option value="{{$o->idAula}}" selected="selected">{{$o->nombre_aula}}</option>
                                               @else
                                                 <option value="{{$o->idAula}}">{{$o->nombre_aula}}</option>
                                               @endif
                                           @endforeach
                                         </select> 
                                        @else
                                           <select class="form-control aula-input" name="Aula" id="Aula" data-id="{{$fila->idPofmh}}">
                                             <option value="-1" disabled selected>SELECCIONE UNA OPCIÓN</option>
                                             
                                             @php
                                               $cue = $institucionExtension->CUECOMPLETO;
                                               $turno = $institucionExtension->idTurnoUsuario;
                                               $aulasRelacionadas = DB::connection('DB7')->table('tb_padt')
                                                   ->where('idOrigenCargo', $origenUnico)
                                                   ->where(function($query) use ($cue, $turno) { 
                                                       $query->where('CUECOMPLETO', $cue)
                                                             ->where(function($subQuery) use ($turno) { 
                                                                 $subQuery->where('idTurno', $turno)
                                                                         ->orWhere('idTurno', 5);
                                                             });
                                                   })
                                                   ->join('tb_aulas', 'tb_padt.idAula', '=', 'tb_aulas.idAula')
                                                   ->select('tb_aulas.idAula', 'tb_aulas.nombre_aula')
                                                   ->distinct()
                                                   ->get();
                                               
                                             @endphp
                                             @foreach($aulasRelacionadas as $key => $o)
                                                 @if ($o->idAula == $fila->Aula)
                                                     @php
                                                       if($o->nombre_aula=== 'HORAS DOCENTES'){
                                                         $aulaSeleccionadaOrigen =  14;
                                                       }else{
                                                         $aulaSeleccionadaOrigen =  0;
                                                       }
                                                     @endphp
                                                     <option value="{{$o->idAula}}" selected="selected">{{$o->nombre_aula}}</option>
                                                 @else
                                                     <option value="{{$o->idAula}}">{{$o->nombre_aula}}</option>
                                                 @endif
                                             @endforeach
                                           </select>
                                         @endif
                                       </td>
                                       <td>
                                         @if ($nombreUnico && $nombreUnico == 'HORAS DOCENTES' || $nombreUnico == 'PRECEPTOR/A')
                                         <select class="form-control division-input" name="Division" id="Division" data-id="{{$fila->idPofmh}}">
                                           <option value="-1" disabled selected>SELECCIONE UNA OPCIÓN</option>
                                           @php
                                             $divRelacionadas = DB::connection('DB7')->table('tb_divisiones')
                                             ->get();
                                           @endphp
                                           @foreach($divRelacionadas as $key => $o)
                                               @if ($o->idDivision == $fila->Division)
                                                   <option value="{{$o->idDivision}}" selected="selected">{{$o->nombre_division}}</option>
                                               @else
                                                   <option value="{{$o->idDivision}}">{{$o->nombre_division}}</option>
                                               @endif
                                           @endforeach
                                         </select>
                                         @else
                                           <select class="form-control division-input" name="Division" id="Division" data-id="{{$fila->idPofmh}}">
                                             <option value="-1" disabled selected>SELECCIONE UNA OPCIÓN</option>
                                             @php
                                               
                                               
                                               $divRelacionadas = DB::connection('DB7')->table('tb_padt')
                                               ->where('idOrigenCargo', $origenUnico)
                                               ->where('CUECOMPLETO', $institucionExtension->CUECOMPLETO)
                                               ->where(function($query) use ($institucionExtension) {
                                                   $query->where('idTurno', $institucionExtension->idTurnoUsuario)
                                                         ->orWhere('idTurno', 5);
                                               })
                                               ->join('tb_divisiones', 'tb_padt.idDivision', '=', 'tb_divisiones.idDivision')
                                               ->select('tb_divisiones.idDivision', 'tb_divisiones.nombre_division')
                                               ->distinct()
                                               ->get();
           
                                             @endphp
                                             @foreach($divRelacionadas as $key => $o)
                                                 @if ($o->idDivision == $fila->Division)
                                                     <option value="{{$o->idDivision}}" selected="selected">{{$o->nombre_division}}</option>
                                                 @else
                                                     <option value="{{$o->idDivision}}">{{$o->nombre_division}}</option>
                                                 @endif
                                             @endforeach
                                           </select>
                                         @endif
                                        
                                       </td>
                                      <td>
                                        <select class="form-control turno-input" name="Turno" id="Turno" data-id="{{$fila->idPofmh}}">
                                          @foreach($Turnos as $key => $o)
                                              @if ($o->idTurno == $fila->Turno)
                                                  <option value="{{$o->idTurno}}" selected="selected">{{$o->nombre_turno}}</option>
                                              @else
                                                  <option value="{{$o->idTurno}}">{{$o->nombre_turno}}</option>
                                              @endif
                                          @endforeach
                                        </select>
                                      </td>
                                      <td>
                                        <input type="text" name="EspCur" id="EspCur" value="{{$fila->EspCur}}" class="espcur-input" data-id="{{$fila->idPofmh}}" disabled>
                                      </td>
                                      <td>
                                        <input type="text" name="Matricula" id="Matricula" value="{{$fila->Matricula}}" class="matricula-input" data-id="{{$fila->idPofmh}}" disabled>
                                      </td>
                                      <td>
                                        <input type="date" name="AltaCargo" id="AltaCargo" 
                                          value="{{ $fila->FechaAltaCargo ? \Carbon\Carbon::parse($fila->FechaAltaCargo)->format('Y-m-d') : '' }}" 
                                          class="fechaaltacargo-input" data-id="{{$fila->idPofmh}}">
                                      </td>
                                      <td>
                                        <input type="date" name="Designado" 
                                        value="{{ $fila->FechaDesignado ? \Carbon\Carbon::parse($fila->FechaDesignado)->format('Y-m-d') : '' }}" 
                                        class="fechadesignado-input" data-id="{{$fila->idPofmh}}">

                                      </td>
                                      <td>
                                        <select class="form-control condicion-input" name="Condicion" id="Condicion" data-id="{{$fila->idPofmh}}">
                                          <option value="-1" disabled selected>SELECCIONE UNA OPCIÓN</option>
                                          @foreach($Condiciones as $key => $o)
                                              @if ($o->idCondicion == $fila->Condicion)
                                                  <option value="{{$o->idCondicion}}" selected="selected">{{$o->Descripcion}}</option>
                                              @else
                                                  <option value="{{$o->idCondicion}}">{{$o->Descripcion}}</option>
                                              @endif
                                          @endforeach
                                        </select>
                                      </td>
                                      <td>
                                        <select class="form-control activo-input" name="Activo" id="Activo" data-id="{{$fila->idPofmh}}">
                                          <option value="-1" disabled selected>SELECCIONE UNA OPCIÓN</option>
                                          @foreach($Activos as $key => $o)
                                              @if ($o->idActivo == $fila->Activo)
                                                  <option value="{{$o->idActivo}}" selected="selected">{{$o->nombre_activo}}</option>
                                              @else
                                                  <option value="{{$o->idActivo}}">{{$o->nombre_activo}}</option>
                                              @endif
                                          @endforeach
                                        </select>
                                      </td>
                                      <td>
                                        <select class="form-control motivos-input" name="Motivos" id="Motivos" data-id="{{$fila->idPofmh}}">
                                          @foreach($Motivos as $key => $o)
                                              @if ($o->idMotivo == $fila->Motivo)
                                                  <option value="{{$o->idMotivo}}" selected="selected"><b>({{$o->Codigo}})</b>{{$o->Nombre_Licencia}}</option>
                                              @else
                                                  <option value="{{$o->idMotivo}}"><b>({{$o->Codigo}})</b>{{$o->Nombre_Licencia}}</option>
                                              @endif
                                          @endforeach
                                        </select>
                                      </td>
                                      <td>
                                        <textarea name="DatosPorCondicion"  class="datosporcondicion-input" data-id="{{$fila->idPofmh}}">{{$fila->DatosPorCondicion}}</textarea>
                                      </td>
                                      <td>
                                        <input type="date" name="Desde" 
                                              value="{{ $fila->FechaDesde ? \Carbon\Carbon::parse($fila->FechaDesde)->format('Y-m-d') : '' }}" 
                                              class="fechadesde-input" data-id="{{$fila->idPofmh}}">
                                      </td>
                                      <td>
                                        <input type="date" name="Hasta" 
                                              value="{{ $fila->FechaHasta ? \Carbon\Carbon::parse($fila->FechaHasta)->format('Y-m-d') : '' }}" 
                                              class="fechahasta-input" data-id="{{$fila->idPofmh}}">
                                      </td>
                                      <td>
                                        <input type="text" name="AgenteR"  id="AgenteR-{{ $fila->idPofmh }}" value="{{$fila->AgenteR}}" class="agenter-input" data-id="{{$fila->idPofmh}}" disabled>
                                      </td>
                                      <td>
                                     
                                        <button type="button" class="btn btn-default view-novedades" data-toggle="modal" data-target="#modal-novedades" data-id="{{ $fila->idPofmh }}">
                                          <i class="fas fa-newspaper"></i>
                                        </button>
                                      </td>
                                      {{-- <td>
                                        <input type="text" name="Asistencia" value="{{$fila->Asistencia}}" class="asistencia-input" data-id="{{$fila->idPofmh}}" disabled></td>
                                      </td>
                                      <td>
                                        <input type="text" name="AsistenciaJustificada" value="{{$fila->Justificada}}" class="asistenciajus-input" data-id="{{$fila->idPofmh}}" disabled></td>

                                      </td>
                                      <td>
                                        <input type="text" name="AsistenciaInjustificada" value="{{$fila->Injustificada}}" class="asistenciain-input" data-id="{{$fila->idPofmh}}" disabled></td>
                                      </td> --}}
                                    
                                      <td>
                                        <textarea name="Observaciones"  class="observaciones-input" data-id="{{$fila->idPofmh}}">{{$fila->Observaciones}}</textarea>
                                      </td>
                                      <td>
                                        <input type="text" name="Carrera" value="{{$fila->Carrera}}" class="carrera-input" data-id="{{$fila->idPofmh}}" disabled>
                                      </td>
                                      <td>
                                        <input type="text" name="Orientacion" value="{{$fila->Orientacion}}" class="orientacion-input" data-id="{{$fila->idPofmh}}" disabled>
                                      </td>
                                      <td>
                                        <input type="text" name="Titulo" value="{{$fila->Titulo}}" class="titulo-input" data-id="{{$fila->idPofmh}}" disabled>
                                      </td>
                                      <td>
                                          <span class="add-row">
                                              <i class="fas fa-plus-circle"></i>
                                          </span>
                                          <span class="ir-al-izquierda-btn" id="irAlIzquierdaBtn">
                                            <i class="fas fa-arrow-circle-left"></i> 
                                          </span>
                                          <span class="confirmarFilaCompleta" id="confirmarFilaCompleta" data-id="{{$fila->idPofmh}}">
                                            <i class="fas fa-check"></i> 
                                          </span>
                                          <span style="margin-right: 2rem">
                                            |
                                          </span>
                                          <span class="delete-row">
                                              <i class="fas fa-eraser"></i>
                                          </span>
                                      </td>
                                      <td>
                                        <textarea class="zonasupervision" id="zonasupervision" disabled >{{$fila->ZonaSupervision}}</textarea>
                                      </td>
                                      
                                  </tr>
                                  @endforeach
                                @endif
                                  <!-- Inicialmente vacío -->
                              </tbody>
                          </table>
                      </div>
                  </div>
                </div>
                {{--FIN de POFMH--}}

                {{-- <div class="row m-2">
                  {{ $infoPofMH->links('pagination::bootstrap-4') }}
                </div> --}}
            </form>
          </div>
        </section>
    </section> 
</section>


<!-- Modal para mostrar el gráfico -->
<div class="modal fade" id="modal-novedades">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Panel de Novedades</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="col-12">
          <div class="card">
            <div class="card-header d-flex p-0">
              <h3 class="card-title p-3">Panel de Novedades Generales</h3>
              <ul class="nav nav-pills ml-auto p-2">
                <li class="nav-item disabled">
                  <a class="nav-link " href="#tab_1" data-toggle="tab">
                      <i class="fas fa-plus-circle"></i> Agregar novedad
                  </a>
              </li>
              <li class="nav-item disabled" >
                  <a class="nav-link" href="#tab_2" data-toggle="tab">
                      <i class="fas fa-eye"></i> Ver Novedades
                  </a>
              </li>
              <li class="nav-item">
                  <a class="nav-link active" href="#tab_3" data-toggle="tab">
                      <i class="fas fa-upload"></i> Subir Documentación
                  </a>
              </li>
              </ul>
            </div><!-- /.card-header -->
            <div class="card-body">
              <div class="tab-content">
                <div class="tab-pane" id="tab_1">
                  <form method="POST" action="{{ route('pofmhformularioNovedadParticular') }}" class="pofmhformularioNovedadParticular" id="pofmhformularioNovedadParticular">
                    @csrf
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="FechaInicio">Fecha Inicio</label>
                                <input type="date" class="form-control" id="FechaInicio" name="FechaInicio" placeholder="Fecha Inicio" value="">
                            </div>
                            <div class="form-group" style="margin-left: 20px">
                                <label for="FechaHasta">Fecha Hasta</label>
                                <input type="date" class="form-control" id="FechaHasta" name="FechaHasta" placeholder="Fecha Hasta" value="">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="DNI">DNI del Agente"</label>
                                <input type="text" class="form-control" id="DNI" name="DNI" placeholder="Ingrese DNI del Agente" value="" readonly disabled>
                            </div>
                            
                            <div class="form-group"  style="margin-left: 20px">
                                <label for="ApeNom">Apellido y Nombre"</label>
                                <input type="text" class="form-control" id="ApeNom" name="ApeNom" placeholder="Agente" value="" disabled readonly>
                            </div>
                            <div class="form-group" style="display: flex">
                              <div class="form-group" style="margin-left: 20px">
                                  <label for="TL">Tipo de Novedad </label>
                                  <select name="TipoNovedad" class="form-control custom-select">
                                      @foreach($NovedadesExtras as $key => $o)
                                          <option value="{{$o->idNovedadExtra}}">({{$o->tipo_novedad}})</option>
                                      @endforeach 
                                  </select>
                              </div>
                              <div class="form-group" style="margin-left: 20px">
                                  <label for="TL">Tipo de Licencia </label>
                                  <select class="form-control motivos-input" name="Motivos" id="Motivos" >
                                      @foreach($Motivos as $key => $o)
                                          <option value="{{$o->idMotivo}}"><b>({{$o->Codigo}})</b>{{$o->Nombre_Licencia}}</option>
                                      @endforeach
                                    </select>
                              </div>
                          </div>
                        </div>
                        <input type="hidden" id="novedad_dni" name="novedad_dni" value="">
                        <input type="hidden" id="novedad_apenom" name="novedad_apenom" value="">
                        <input type="hidden" id="novedad_cue" name="novedad_cue" value="">
                        <input type="hidden" id="novedad_turno" name="novedad_turno" value="">
                        <div class="form-group">
                            <label for="Observacion">Observación</label><br>
                            <textarea class="form-control" name="Observaciones" id="novedad_observacion" rows="5" cols="100%"></textarea>
                        </div>
                        
                        
                       
                    </div>
                    <div class="card-footer bg-transparent">
                        <button type="submit" class="btn btn-primary">Agregar</button>
                    </div>
                    
                </form>
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_2">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th rowspan="2" style="text-align:center">DNI</th>
                            <th colspan="3" style="text-align:center">Fecha Novedad</th>
                            <th rowspan="2" style="text-align:center">Tipo Novedad</th>
                            <th rowspan="2" style="text-align:center">Tipo Motivo</th>
                            <th rowspan="2" style="text-align:center">Observaciones</th>
                            <th rowspan="2" style="text-align:center">Acciones</th>
                        </tr>
                        <tr>
                            <th style="text-align:center">Fecha Desde</th>
                            <th style="text-align:center">Fecha Hasta</th>
                            <th style="text-align:center">Total Días</th>
                            
                            
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                </div>
                <!-- /.tab-pane -->
                <!-- /.tab-pane -->
                <div class="tab-pane active" id="tab_3">
                  <div class="container_archivos"  style="display: flex; gap:1rem;">
                        <!-- INICIO SUBIR DOC -->
                      <div class="card card-secondary col-6">
                        <div class="card-header">
                            <h3 class="card-title">Subir Documentos <small><em></em></small></h3>
                        </div>
                        <div class="card-body" >
                            <div id="actions" class="row">
                                <div class="">
                                    <div class="btn-group w-100" >
                                        <span class="btn btn-success fileinput-button">
                                            <i class="fas fa-plus"></i>
                                            Agregar
                                        </span>                        
                                    </div>
                                </div>
                                <div class="col-lg-6 d-flex align-items-center">
                                    <div class="fileupload-process w-100">
                                        <div id="total-progress" class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                                            <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table table-striped files" id="previews">
                                <div id="template2" class="row mt-2">
                                    <div class="col-auto">
                                        <span class="preview"><img src="data:," alt="" data-dz-thumbnail /></span>
                                    </div>
                                    <div class="col d-flex align-items-center">
                                        <p class="mb-0">
                                            <span class="lead" data-dz-name></span>
                                            (<span data-dz-size></span>)
                                        </p>
                                        <strong class="error text-danger" data-dz-errormessage></strong>
                                    </div>
                                    <div class="col-4 d-flex align-items-center">
                                        <div class="progress progress-striped active w-100" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                                            <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                                            
                                        </div>
                                    </div>
                                    <div class="col-auto d-flex align-items-center">
                                        <div class="btn-group">
                                            <button class="btn btn-primary start" title="Enviar Archivo">
                                                <i class="fas fa-upload"></i>
                                            </button>
                                            <button data-dz-remove class="btn btn-warning cancel"  title="Cancelar Subida">
                                                <i class="fas fa-times-circle"></i>
                                            </button>
                                            <button data-dz-remove class="btn btn-danger delete"  title="Borrar Envio">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer" id="upload-status">                                  
                            <!-- Aquí se mostrarán los mensajes de estado o errores de la carga de archivos -->
                        </div>
                    </div>
                    <!-- /.card -->
                      <table id="example3" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="text-align:center">Archivo</th>
                                <th style="text-align:center">Fecha Alta</th>
                                <th style="text-align:center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="modalBody">
                                                          
                        </tbody>
                      </table>
                  </div>
                  
                </div>
                <!-- /.tab-pane -->
              </div>
              <!-- /.tab-content -->
            </div><!-- /.card-body -->
          </div>
          
          <!-- /.card -->
      </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar Panel</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
@php
$nombreInstitucion = "Prueba Inst";
$cue =" 452542545";
$turno="Tarde";

@endphp
@endsection

@section('Script')

<script src="{{ asset('js/pofmh.js') }}"></script>

<!-- Incluye la librería SheetJS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

document.getElementById('confirmBtn').addEventListener('click', function () {
    // Mostrar SweetAlert
    Swal.fire({
        title: 'Información',
        text: `El sistema realiza copias de seguridad automática de los datos, este archivo es solo un reflejo de la POF Nominal actual, en la cual se incluyen los datos cargados hasta el día anterior.
        Resguarde este documento para posibles reclamos.`,
        icon: 'info',
        confirmButtonText: 'Bajar Archivo',
        allowOutsideClick: false // Evita cerrar al hacer clic fuera
    }).then((result) => {
        if (result.isConfirmed) {
            // Solo se ejecuta si el usuario presiona OK
            generarExcel();
            // Si necesitas llamar a otra función, descomenta la siguiente línea
            // generarExcelEnArchivoExistente();
        }
    });
});


function generarExcel() {
    // Simulamos la obtención de datos del servidor (puedes adaptar esto según tus necesidades)
    const infoPofMH = @json($infoPofMH); // Asumiendo que la variable está disponible en JavaScript
    const dataPofMH = infoPofMH.data || [];
    // Cabecera de la institución
    const nombreInstitucion = "{{ $institucionExtension->Nombre_Institucion }}";
    const cue = "{{ $institucionExtension->CUECOMPLETO }}";
    const turno = "{{ $institucionExtension->Descripcion }}";
    
    // Obtener la fecha y hora actual en el huso horario de Argentina
    const now = new Date();
    const opcionesFecha = { day: 'numeric', month: 'long', year: 'numeric', hour: 'numeric', minute: 'numeric', hour12: true, timeZone: 'America/Argentina/Buenos_Aires' };
    const fechaActual = now.toLocaleString('es-AR', opcionesFecha); // Cambia 'es-AR' si es necesario para el idioma

 
    // Datos del POF
    const mes = "Diciembre"; // Puedes cambiar el mes según sea necesario
    const datosPOFHeader = `Datos del POF 2024 - Mes ${mes}`;

    // Convertir los datos a un formato adecuado para Excel
    const worksheetData = [];

    // Agregar la cabecera de la institución
    worksheetData.push([`Institución: ${nombreInstitucion} - CUE: ${cue} - Turno: ${turno} - Fecha: ${fechaActual}`]);
    worksheetData.push([datosPOFHeader]); // Agregar la línea con datos del POF

    // Encabezados de las columnas
    const headers = [
        'ID', 'Orden', 'DNI', 'Apellido y Nombre', 
        'Cargo de Origen en la Institución','Cargo Salarial','Sit.Rev','Horas', 
        'Antigüedad Docente','Aula','Division',
        'Turno del Agente','Espacio Curricular', ' Matricula' , 
        'Alta Cargo?', 'Designado', 'Condición', '¿En el Aula?',
        'Tipo-Motivo-Art.Licencia', 'Otros Datos por Condición', 
        'Desde', 'Hasta', 'Agente / DNI Reemplazo','Asistencia', 'Justificada', 
        'Injustificada','Observaciones','Carrera','Orientación','Titulo'
    ];
    worksheetData.push(headers); // Añadir los encabezados al array

    // Recorre los datos para agregar cada fila
    dataPofMH.forEach(fila => {
        // Obtener el texto visible de cada select usando data-id en lugar de id
        const origenSelect = document.querySelector(`select[name="Origen"][data-id="${fila.idPofmh}"]`);
        const cargoSelect = document.querySelector(`select[name="CargoSalarial"][data-id="${fila.idPofmh}"]`);
        const espCurSelect = document.querySelector(`select[name="EspCur"][data-id="${fila.idPofmh}"]`);
        const turnoSelect = document.querySelector(`select[name="Turno"][data-id="${fila.idPofmh}"]`);
        const sitRevSelect = document.querySelector(`select[name="SitRev"][data-id="${fila.idPofmh}"]`);
        const motivosSelect = document.querySelector(`select[name="Motivos"][data-id="${fila.idPofmh}"]`);
        const condicionSelect = document.querySelector(`select[name="Condicion"][data-id="${fila.idPofmh}"]`);
        const aulaSelect = document.querySelector(`select[name="Aula"][data-id="${fila.idPofmh}"]`);
        const divisionSelect = document.querySelector(`select[name="Division"][data-id="${fila.idPofmh}"]`);
        const activoSelect = document.querySelector(`select[name="Activo"][data-id="${fila.idPofmh}"]`);

        const origenText = origenSelect ? origenSelect.options[origenSelect.selectedIndex].text : fila.Origen;
        const cargoText = cargoSelect ? cargoSelect.options[cargoSelect.selectedIndex].text : fila.Cargo;
        const espCurText = espCurSelect ? espCurSelect.options[espCurSelect.selectedIndex].text : fila.EspCur;
        const turnoText = turnoSelect ? turnoSelect.options[turnoSelect.selectedIndex].text : fila.Turno;
        const sitRevText = sitRevSelect ? sitRevSelect.options[sitRevSelect.selectedIndex].text : fila.SitRev;
        const motivosText = motivosSelect ? motivosSelect.options[motivosSelect.selectedIndex].text : fila.Motivos;
        const condicionText = condicionSelect ? condicionSelect.options[condicionSelect.selectedIndex].text : fila.Condicion;
        const aulaText = aulaSelect ? aulaSelect.options[aulaSelect.selectedIndex].text : fila.Aula;
        const divisionText = divisionSelect ? divisionSelect.options[divisionSelect.selectedIndex].text : fila.Division;
        const activoText = activoSelect ? activoSelect.options[activoSelect.selectedIndex].text : fila.Activo;

        // Obtener valores de los otros campos que no son selects, pero están en la fila usando data-id
        const altaCargoInput = document.querySelector(`input[name="AltaCargo"][data-id="${fila.idPofmh}"]`);
        const designadoInput = document.querySelector(`input[name="Designado"][data-id="${fila.idPofmh}"]`);
        // const condicionInput = document.querySelector(`input[name="Condicion"][data-id="${fila.idPofmh}"]`);
        const desdeInput = document.querySelector(`input[name="Desde"][data-id="${fila.idPofmh}"]`);
        const hastaInput = document.querySelector(`input[name="Hasta"][data-id="${fila.idPofmh}"]`);
        const otrosDatosCondicionInput = document.querySelector(`input[name="DatosPorCondicion"][data-id="${fila.idPofmh}"]`);
        const antiguedadCargoInput = document.querySelector(`input[name="Antiguedad"][data-id="${fila.idPofmh}"]`);
        const agenteDNICuandoReemplazoInput = document.querySelector(`input[name="AgenteR"][data-id="${fila.idPofmh}"]`);
        const novedadesInput = document.querySelector(`input[name="Novedades"][data-id="${fila.idPofmh}"]`);
        const carreraInput = document.querySelector(`input[name="Carrera"][data-id="${fila.idPofmh}"]`);
        const orientacionInput = document.querySelector(`input[name="Orientacion"][data-id="${fila.idPofmh}"]`);
        const tituloInput = document.querySelector(`input[name="Titulo"][data-id="${fila.idPofmh}"]`);
        const matriculaInput = document.querySelector(`input[name="Matricula"][data-id="${fila.idPofmh}"]`);
        const agenteInput = document.querySelector(`input[name="dato2[]"][data-id="${fila.idPofmh}"]`);
        const apenomInput = document.querySelector(`input[name="dato3[]"][data-id="${fila.idPofmh}"]`);
        const ordenInput = document.querySelector(`input[name="dato1[]"][data-id="${fila.idPofmh}"]`);
        const horasInput = document.querySelector(`input[name="Horas"][data-id="${fila.idPofmh}"]`);

        // Obtener los valores (o usar el valor en fila si no se encuentra el campo)
        const altaCargo = altaCargoInput ? altaCargoInput.value : fila.AltaCargo;
        const designado = designadoInput ? designadoInput.value : fila.Designado;
        // const condicion = condicionInput ? condicionInput.value : fila.Condicion;
        const desde = desdeInput ? desdeInput.value : fila.Desde;
        const hasta = hastaInput ? hastaInput.value : fila.Hasta;
        
        const otrosDatosCondicion = otrosDatosCondicionInput ? otrosDatosCondicionInput.value : fila.DatosPorCondicion;
        const antiguedadCargo = antiguedadCargoInput ? antiguedadCargoInput.value : fila.Antiguedad;
        const agenteDNICuandoReemplazo = agenteDNICuandoReemplazoInput ? agenteDNICuandoReemplazoInput.value : fila.AgenteR;
        const novedades = novedadesInput ? novedadesInput.value : fila.Novedades;
        const carrera = carreraInput ? carreraInput.value : fila.Carrera;
        const orientacion = orientacionInput ? orientacionInput.value : fila.Orientacion;
        const titulo = tituloInput ? tituloInput.value : fila.Titulo;
        const matricula = matriculaInput ? matriculaInput.value : fila.Matricula;
        const agente = agenteInput ? agenteInput.value : fila.Agente;
        const apenom = apenomInput ? apenomInput.value : fila.ApeNom;
        const orden = ordenInput ? ordenInput.value : fila.orden;
        const horas = horasInput ? horasInput.value : fila.Horas;

        // Agregar los datos al Excel
        worksheetData.push([
            fila.idPofmh,              // ID del registro
            orden,                // Orden
            agente,               // DNI
            apenom,               // Apellido y nombre del agente
            origenText,                // Origen del cargo
            cargoText,                 // Texto visible del select 'Cargo'
            sitRevText,                // Texto visible del select 'Sit.Rev'
            horas,                // Horas
            antiguedadCargo,           // Antigüedad Cargo c/SitRev
            
            aulaText,
            divisionText,              // Sala/Curso/Division
            turnoText,                 // Texto visible del select 'Turno'
            espCurText,                // Especialidad Curricular
            matricula,
            altaCargo,                 // Alta Cargo
            designado,                 // Designado
            condicionText,             // Condición
            activoText,                // si esta o no frente al aula
            motivosText,                      // Tipo
            otrosDatosCondicion,        // Otros Datos por Condición
            desde,                     // Desde
            hasta,                     // Hasta
            agenteDNICuandoReemplazo,   // Agente / DNI Reemplazo
            fila.Asistencia,           // Asistencia
            fila.Justificada,          // Justificada
            fila.Injustificada,        // Injustificada
            fila.Observaciones,        // Observaciones
            carrera,
            orientacion,
            titulo
        ]);
    });

    // Crear un libro de trabajo y una hoja
    const wb = XLSX.utils.book_new();
    const ws = XLSX.utils.aoa_to_sheet(worksheetData);
    // Definir los anchos de las columnas
    ws['!cols'] = [
        { wpx: 50 },  // ID
        { wpx: 50 },  // Orden
        { wpx: 80 },  // DNI
        { wpx: 250 }, // Apellido y Nombre
        { wpx: 230 }, // Origen
        { wpx: 400 }, // Cargo
        { wpx: 110 }, // SitRev
        { wpx: 60 },  // Horas
        { wpx: 160 }, // Antiguedad
        { wpx: 170 },  // Aula
        { wpx: 75 }, // Division
        { wpx: 130 }, // Turno
        { wpx: 250 },  // Espacio curricular
        { wpx: 90 },  // Matricula
        { wpx: 120 },  // FAlta
        { wpx: 120 },  // FDesign
        { wpx: 200 },  // Condicion
        { wpx: 100 }, // En aula?
        { wpx: 470 }, // Motivo
        { wpx: 450 },  // otro motivo
        { wpx: 120 },  // FDesde
        { wpx: 120 }, // Fhasta
        { wpx: 170 }, // Agente Reemplazo
        { wpx: 100 },  // Asistencia
        { wpx: 100 },  // Justificada
        { wpx: 100 },  // Injustificada
        { wpx: 1100 }, // Observaciones
        { wpx: 250 },   // Carrera
        { wpx: 270 },   // Orientacion
        { wpx: 25 }   // Titulo
    ];
    

    // Aplicar estilos a las filas de encabezado
    for (let i = 0; i < headers.length; i++) {
        ws[XLSX.utils.encode_cell({ r: 2, c: i })].s = {
            fill: {
                fgColor: { rgb: "FFDDDDDD" } // Color de fondo gris claro
            },
            font: {
                bold: true // Negrita para los encabezados
            }
        };
    }

    // Agregar la hoja al libro de trabajo
    XLSX.utils.book_append_sheet(wb, ws, "Datos");

    // Generar un archivo Excel y descargarlo
    XLSX.writeFile(wb, "Datos_POFMH_Combos.xlsx");
}
// Función para convertir string a ArrayBuffer
function s2ab(s) {
    const buf = new ArrayBuffer(s.length);
    const view = new Uint8Array(buf);
    for (let i = 0; i < s.length; i++) {
        view[i] = s.charCodeAt(i) & 0xFF;
    }
    return buf;
}
</script>

<script>
  // Obtener la fecha actual
  const fechaActual = new Date();

  // Fecha objetivo: 18 de octubre del año actual
  const fechaObjetivo = new Date(fechaActual.getFullYear(), 10, 20); // Octubre es el mes 9

  // Calcular la diferencia en milisegundos
  const diferenciaMilisegundos = fechaObjetivo - fechaActual;

  // Convertir la diferencia de milisegundos a días
  const diferenciaDias = Math.ceil(diferenciaMilisegundos / (1000 * 60 * 60 * 24));

  // Mostrar el mensaje en la alerta
  const mensajeDiferencia = `En ${diferenciaDias} días se generar la POF Automática.`;
  
  //document.getElementById('mensajeDiferencia').innerText = mensajeDiferencia;
  document.getElementById('alertaDiferencia').style.display = 'block';
  // Mostrar la alerta si la diferencia es positiva
  /*
  if (diferenciaDias > 0) {
      document.getElementById('alertaDiferencia').style.display = 'block';
  } else if (diferenciaDias === 0) {
      document.getElementById('mensajeDiferencia').innerText = "¡Hoy es el 18 de octubre!";
      document.getElementById('alertaDiferencia').style.display = 'block';
  } else {
      document.getElementById('mensajeDiferencia').innerText = "El 18 de octubre ya ha pasado.";
      document.getElementById('alertaDiferencia').style.display = 'block';
  }
  document.getElementById('alertaDiferencia').style.display = 'block';*/
</script>
<script>
  function validarFecha() {
          var fechaInput = document.getElementById('FechaInicio').value;
          var regex = /^\d{4}-\d{2}-\d{2}$/;
          if (!regex.test(fechaInput)) {
              //alert('Formato de fecha inválido. Por favor, ingrese una fecha válida en el formato YYYY-MM-DD.');
              document.getElementById('FechaInicio').focus();
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Formato de fecha inválido. Por favor, ingrese una fecha válida en el formato Día-Mes-Año",
    
              });
              return false; // Retorna false si el formato de fecha es inválido
          }
    
          // Dividir la fecha en sus componentes
          var partesFecha = fechaInput.split("-");
          var año = parseInt(partesFecha[0]);
          var mes = parseInt(partesFecha[1]);
          var dia = parseInt(partesFecha[2]);
    
          // Verificar si el año es válido (entre 1000 y 9999)
          if (año < 1000 || año > 9999) {
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Año inválido. Por favor, ingrese un año válido entre 1000 y 9999",
    
              });
              return false;
          }
    
          // Verificar si el mes es válido (entre 1 y 12)
          if (mes < 1 || mes > 12) {
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Mes inválido. Por favor, ingrese un mes válido entre 01 y 12",
    
              });
              return false;
          }
    
          // Verificar si el día es válido
          var diasEnMes = new Date(año, mes, 0).getDate();
          if (dia < 1 || dia > diasEnMes) {
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Día inválido para el mes y año especificados. Por favor, ingrese un día válido",
    
              });
              return false;
          }
    
          // Si pasa todas las validaciones, retorna true
          return true;
      }
      function validarFecha2() {
          var fechaInput = document.getElementById('FechaHasta').value;
          var regex = /^\d{4}-\d{2}-\d{2}$/;
          if (!regex.test(fechaInput)) {
              //alert('Formato de fecha inválido. Por favor, ingrese una fecha válida en el formato YYYY-MM-DD.');
              document.getElementById('FechaHasta').focus();
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Formato de fecha inválido. Por favor, ingrese una fecha válida en el formato Día-Mes-Año",
    
              });
              return false; // Retorna false si el formato de fecha es inválido
          }
    
          // Dividir la fecha en sus componentes
          var partesFecha = fechaInput.split("-");
          var año = parseInt(partesFecha[0]);
          var mes = parseInt(partesFecha[1]);
          var dia = parseInt(partesFecha[2]);
    
          // Verificar si el año es válido (entre 1000 y 9999)
          if (año < 1000 || año > 9999) {
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Año inválido. Por favor, ingrese un año válido entre 1000 y 9999",
    
              });
              return false;
          }
    
          // Verificar si el mes es válido (entre 1 y 12)
          if (mes < 1 || mes > 12) {
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Mes inválido. Por favor, ingrese un mes válido entre 01 y 12",
    
              });
              return false;
          }
    
          // Verificar si el día es válido
          var diasEnMes = new Date(año, mes, 0).getDate();
          if (dia < 1 || dia > diasEnMes) {
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Día inválido para el mes y año especificados. Por favor, ingrese un día válido",
    
              });
              return false;
          }
    
          // Si pasa todas las validaciones, retorna true
          return true;
      }
      document.getElementById('FechaInicio').addEventListener('blur', validarFecha);
      document.getElementById('FechaHasta').addEventListener('blur', validarFecha2);
  </script>
 
 <script>
  $(document).ready(function() {
      // Función para cargar datos en la tabla de novedades
      function cargarNovedades() {
          var dni = $('#DNI').val();
          var cue = $('#valCUE').val();
          $.ajax({
              url: "/pofmhNovedades/" + dni + "/" + cue, // Ruta definida en web.php
              method: "GET",
              dataType: "json",
              success: function(data) {
                  // Limpiar la tabla antes de llenarla
                  $('#tab_2 tbody').empty();
                  
                  // Iterar sobre los datos y llenar la tabla
                  $.each(data.novedades, function(key, n) { // Aquí usamos data.novedades
                    let motivo = data.Motivos.find(m => m.idMotivo === n.Motivo) || { Codigo: 'N/A', Nombre_Licencia: 'N/A' };
                    
                    var row = `<tr class="gradeX" data-id="${n.idNovedad}">
                        <td>${n.Agente || 'Sin datos'}</td>
                        <td class="text-center">${new Date(n.FechaDesde).toLocaleDateString('es-ES')}</td>
                        <td class="text-center">${new Date(n.FechaHasta).toLocaleDateString('es-ES')}</td>
                        <td class="text-center">${n.TotalDiasLicencia || '1'}</td>
                        <td class="text-center">${n.tipo_novedad || 'Sin novedad'}</td>
                        <td class="text-center">${motivo.Codigo}-${motivo.Nombre_Licencia}</td>
                        <td>${n.Observaciones || 'Sin observaciones'}</td>
                        <td>
                            <i class="fas fa-eraser btn-eliminar-pof" style="color:red"></i>
                        </td>
                    </tr>`;
                    $('#tab_2 tbody').append(row);
                });
              },
              error: function(xhr) {
                  console.error("Error al cargar las novedades:", xhr);
              }
          });
      }
  
      // Cargar datos al abrir el modal
      $('#modal-novedades').on('show.bs.modal', function () {
          cargarNovedades(); // Llama a la función para cargar las novedades
      });
  
      $('.pofmhformularioNovedadParticular').submit(function(e){
          e.preventDefault();
          
          var dni = $('#DNI').val();
          var fi = $('#FechaInicio').val();
          var fh = $('#FechaHasta').val();
          var ob = $('#novedad_observacion').val();
  
          if (!dni || !fi || !fh || !ob) {
              Swal.fire({
                  title: 'Error',
                  text: 'Debe completar todos los campos solicitados.',
                  icon: 'error'
              });
              return;
          }
  
          Swal.fire({
              title: '¿Está seguro de querer agregar una novedad para el Agente?',
              text: "Recuerde colocar datos verdaderos",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Si, guardo el registro!'
          }).then((result) => {
              if (result.isConfirmed) {
                  var formData = new FormData(this);
                  
                  $.ajax({
                      url: $(this).attr('action'), // URL del formulario
                      method: 'POST',
                      data: formData,
                      processData: false,
                      contentType: false,
                      headers: {
                          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content")
                      },
                      success: function(response) {
                          console.log(response);
                          Swal.fire('Éxito', 'Novedad agregada correctamente.', 'success');
                          cargarNovedades(); // Actualiza la tabla de novedades después de agregar
                      },
                      error: function(xhr, status, error) {
                          console.error(xhr.responseText);
                          Swal.fire('Error', 'No se pudo agregar la novedad.', 'error');
                      }
                  });
              }
          });
      });
  });

  $(document).on('click', '.btn-eliminar-pof', function() {
    var fila = $(this).closest('tr'); // Encuentra la fila correspondiente
    var id = fila.data('id'); // Obtiene el ID de la novedad

    Swal.fire({
        title: '¿Está seguro de querer eliminar esta novedad?',
        text: "¡Esta acción no se puede deshacer!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/novedadesModal/${id}`, // URL para eliminar la novedad
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content")
                },
                success: function(response) {
                    console.log(response);
                    Swal.fire('Eliminado', 'Novedad eliminada correctamente.', 'success');
                    fila.remove(); // Eliminar la fila de la tabla
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    Swal.fire('Error', 'No se pudo eliminar la novedad.', 'error');
                }
            });
        }
    });
});

  </script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const perPageSelect = document.getElementById('perPage');

        if (perPageSelect) {
            perPageSelect.addEventListener('change', function () {
                // Buscar si existe el formulario
                let paginationForm = document.getElementById('paginationForm');
                
                // Si no existe, crear y agregarlo al DOM
                if (!paginationForm) {
                    paginationForm = document.createElement('form');
                    paginationForm.id = 'paginationForm';
                    paginationForm.method = 'GET';
                    paginationForm.action = window.location.href; // Usa la URL actual

                    // Mover el select al nuevo formulario
                    document.body.appendChild(paginationForm);
                    paginationForm.appendChild(perPageSelect);
                }

                // Enviar el formulario
                paginationForm.submit();
            });
        }
    });
</script>

<script>
    $(document).ready(function () {
        // Evento para capturar entrada en el campo de búsqueda
        $('#searchInput').on('keyup', function () {
            const value = $(this).val().toLowerCase().trim(); // Convertir a minúsculas y eliminar espacios extra

            // Recorrer las filas del tbody de la tabla
            $('#POFMH tbody tr').each(function () {
                const dni = $(this).find('.dni-input').val().toLowerCase().trim(); // Tomar el valor del input de DNI
                const name = $(this).find('.apenom-input').val().toLowerCase().trim(); // Cambiar al índice real del Apellido y Nombre
                const espcur = $(this).find('.espcur-input').val().toLowerCase().trim();

                // Mostrar u ocultar fila si coincide el valor
                const matches = dni.includes(value) || name.includes(value) || espcur.includes(value);
                $(this).toggle(matches);
            });
        });
    });
</script>

@endsection
