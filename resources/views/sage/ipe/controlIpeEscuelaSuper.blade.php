@extends('layout.app')

@section('Titulo', 'Sage2.0 - Nuevo Usuario ADMIN')
@section('LinkCSS')
<style>
.modal-custom-width {
    width: 90%;
    max-width: none;
}
</style>
@endsection
@section('ContenidoPrincipal')

<section id="container">
    <section id="main-content">
        <section class="content-wrapper">
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    {{--Conformidad--}}   
            <div class="row"> 
                        <div class="alert alert-danger">
                        <h4>CONFORMIDAD</h4>

                        <p>
                            *Advertencia:* La carga de datos en este sistema reviste el carácter de declaración jurada, realizada en cumplimiento de lo dispuesto por el Decreto N° 1556/24, las Resoluciones Ministeriales N° 983/24 y N° 328/25, y la Ley Provincial N° 9911. Asimismo, se recuerda que esta acción se encuentra sujeta a las disposiciones de la Ley Nacional N° 25.164 de Ética en la Función Pública, la Ley N° 25.326 de Protección de Datos Personales y la Ley N° 27.275 de Acceso a la Información Pública. El ingreso de información falsa o inexacta podrá acarrear sanciones administrativas y/o legales correspondientes.
                        </p>
                        </div>
                    </div>
                    <!-- Buscador Agente -->
                    <h4 class="text-center display-4">Lista de Agentes - NO EDITABLE</h4>
                    <!-- Agregar Nuevo Agente -->
                   
                    <div class="row d-flex justify-content-center">
                        <!-- left column -->
                        <div class="">
                            <div class="card card-lightblue">
                                <div class="card-header">
                                    <h3 class="">Control de IPE</h3>
                                    <h4>Escuela: {{$NombreInstitucion}}</h4>
                                    <h4>CUECOMPLETO: {{$CUECOMPLETOBASE}}</h4>
                                    <h4>Unidades Relacionadas: {!! ($liqText ? '<span style="color:yellow">' . rtrim($liqText, ' / ') . '</span>' : '<span style="color:red">No se encontró unidad de liquidación</span>') !!}</h4>
                                    <h4>Mes Actual: {{$MesActual}}</h4>
                                    
                                    
                                    
                                    {{-- modal --}}
                                    <div class="modal fade" id="modal-alta">
                                        <div class="modal-dialog modal-xl">
                                          <div class="modal-content">
                                            <div class="modal-header d-flex flex-column align-items-start" style="color:black">
                                                
                                                <h4 class="modal-title w-100">Alta de Docentes Nuevos y Volantes</h4>
                                                <h6 class="mb-0">CUE: <b>{{ session('CUECOMPLETOBASE') }}</b></h6>
                                                <button type="button" class="close position-absolute" style="right: 15px;" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                
                                                <form method="POST" action="{{ route('FormNuevoAgenteAltaControlIpe') }}" class="FormNuevoAgenteAltaControlIpe form-group">
                                                    @csrf
                                                        <div class="card-body" id="NuevoAgenteContenido1">
                                                            <!-- Fila Tipo Documento y DNI -->
                                                            <div class="form-group row">
                                                                <div class="col-4">
                                                                    <label for="Apellido">Apellido: </label>
                                                                    <input type="text" autocomplete="off" class="form-control" id="Apellido" name="Apellido" placeholder="Ingrese apellido">
                                                                </div>
                                                                <div class="col-4">
                                                                    <label for="Nombre">Nombre: </label>
                                                                    <input type="text" autocomplete="off" class="form-control" id="Nombre" name="Nombre" placeholder="Ingrese nombre">
                                                                </div>
                                                                <div class="col-4">
                                                                    <label for="Sexo">Sexo: </label>
                                                                    <select class="form-control" name="Sexo" id="Sexo">
                                                                        @foreach ($Sexos as $key => $o)
                                                                            <option value="{{ $o->Mnemo }}">{{ $o->Descripcion }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                
                                                            </div>
                    
                                                            <!-- Fila Apellido, Nombre y Sexo -->
                                                            <div class="form-group row">
                                                                <div class="col-3">
                                                                    <label for="Documento">Documento: </label>
                                                                    <input type="text" autocomplete="off" class="form-control" id="Documento" name="Documento" placeholder="Ingrese número de documento">
                                                                  
                                                                </div>
                                                                <div class="col-6">
                                                                    <label for="CUIL">CUIL: </label>
                                                                    <input type="text" autocomplete="off" class="form-control" id="CUIL" name="CUIL" placeholder="Ingrese número de cuil">
                                                                </div>
                                                                <div class="col-3">
                                                                    <label for="SitRev">Situación de Revista: </label>
                                                                    <select class="form-control" name="SitRev" id="SitRev">
                                                                        @foreach ($SitRev as $key => $s)
                                                                            <option value="{{ $s->idSituacionRevista }}">{{ $s->Descripcion }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                    
                                                            <!-- Fila CUIL, Tipo de Agente -->
                                                            <div class="form-group row">
                                                                <div class="col-6">
                                                                    <label for="CS">Cargo Salarial: </label>
                                                                    <select class="form-control" name="CargoSalarial" id="CargoSalarial">
                                                                        @foreach ($CargoSalarial as $key => $c)
                                                                            <option value="{{ $c->Codigo }}">{{ $c->Codigo }}-{{ $c->Cargo }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                
                                                            </div>
                    
                                                            
                                                        </div>
                                                        <!-- /.card-body -->
                    
                                                        <div class="card-footer bg-transparent" id="NuevoAgenteContenido2">
                                                            <button type="submit" class="btn btn-primary" disabled>Agregar</button>
                                                        </div>
                                                </form>
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                              <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                             
                                            </div>
                                          </div>
                                          <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                      </div>

                                </div>
                                {{--BUSCADOR--}}
                                <div class="container-fluid m-1">
                                    <form id="searchForm" class="row col-12">
                                    <div class="input-group">
                                        <input type="search" id="searchInput" class="form-control" placeholder="Buscar Apellido, Nombre o N° de DNI">
                                    </div>
                                    </form>
                                </div>
                              <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="tablacontrolIpe" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Registro</th>
                                                <th>DNI</th>
                                                <th>CUIL</th>
                                                <th>N&deg; de Trabajo</th>
                                                <th>Apellido y Nombre</th>
                                                <th>Unidad liquidación</th>
                                                <th>Situación Revista</th>
                                                <th>Código Salarial</th>
                                                <th>Area</th>
                                                <th style="min-width: 180px; width: 180px;">Turno de Trabajo</th>
                                                <th style="min-width: 120px;width: 120px;">Cobra IPE?</th>
                                                <th style="min-width: 120px; width: 120px;">Horas</th>
                                                {{-- <th> Pertenece a la Institución</th> --}}
                                                <th><i class="fas fa-cog"></i> Acciones</th>
                                                <th><i class="fas fa-clock"></i> Hora de Control</th>
                                                <th><i class="fas fa-school"></i> Esc. Control</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $contador = 0; @endphp
                                            @if($infoAgentes->isNotEmpty())
                                                <tr>
                                                    <td colspan="15" class="text-center" style="color:green">
                                                        <strong>APARTADO DE AGENTES QUE PERTENECEN AL ESTABLECIMIENTO EN UNIDAD DE LIQUIDACIÓN Y AREA
                                                    </td>
                                                </tr>
                                                
                                                @foreach ($infoAgentes as $indice => $agente)
                                                    <tr data-id="{{ $agente->idPofIpe }}">
                                                        <td>{{ $contador }}</td>
                                                        <td class="dni-input">{{ $agente->Documento }}</td>
                                                        <td>{{ $agente->Cuil }}</td>
                                                        <td class="text-center">{{ $agente->Trabajo }}</td>
                                                        <td class="apenom-input">{{ $agente->ApeNom }}</td>
                                                        <td class="text-center">{{ $agente->Escu }}</td>
                                                        <td class="text-center">{{ $agente->Descripcion }}</td>
                                                        <td class="text-center">{{ $agente->Codigo }}</td>
                                                        <td class="text-center area-input">{{ $agente->Area }}</td>
                                                        <td class="text-center">
                                                            <select class="form-control turno-normal" id="turno_{{ $agente->idPofIpe }}" data-id="{{ $agente->idPofIpe }}" disabled>
                                                                <option value="" disabled {{ is_null($agente->Turno) ? 'selected' : '' }}>Seleccione</option>
                                                                @foreach ($Turnos as $turno)
                                                                    <option value="{{ $turno->idTurno }}"
                                                                        {{ $agente->Turno == $turno->idTurno ? 'selected' : '' }}>
                                                                        {{ $turno->nombre_turno }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input checkbox-ipe-si" type="checkbox"
                                                                    id="ipe_si_{{ $agente->idPofIpe }}"
                                                                    name="ipe_{{ $agente->idPofIpe }}"
                                                                    data-id="{{ $agente->idPofIpe }}"
                                                                    {{ $agente->IPE == 'SI' ? 'checked' : '' }} disabled>
                                                                <label class="form-check-label" for="ipe_si_{{ $agente->idPofIpe }}">SI</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input checkbox-ipe-no" type="checkbox"
                                                                    id="ipe_no_{{ $agente->idPofIpe }}"
                                                                    name="ipe_{{ $agente->idPofIpe }}"
                                                                    data-id="{{ $agente->idPofIpe }}"
                                                                    {{ $agente->IPE == 'NO' ? 'checked' : '' }} disabled>
                                                                <label class="form-check-label" for="ipe_no_{{ $agente->idPofIpe }}">NO</label>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <input 
                                                                type="text"
                                                                class="form-control hora-normal"
                                                                id="hora_{{ $agente->idPofIpe }}"
                                                                data-id="{{ $agente->idPofIpe }}"
                                                                value="{{ $agente->Horas_Trabajadas ?? '' }}"
                                                                min="0" max="50"
                                                                inputmode="numeric" disabled
                                                            
                                                            
                                                            />
                                                        </td>
                                                        <td class="text-center estado-validacion" id="estado_{{ $agente->idPofIpe }}" style="display:flex;justify-content: space-between;">
                                                            <span class="text-success check-validacion d-none" id="check_{{ $agente->idPofIpe }}">
                                                                <i class="fas fa-check-circle"></i>
                                                            </span>
                                                            
                                                        </td>
                                                        <td>
                                                            @if ($agente->updated_at != null)
                                                                <small>Ultimo Control: <span style="color:green"><br>{{ \Carbon\Carbon::parse($agente->updated_at)->format('d-m-Y H:i:s') }}</span></small>
                                                            @else
                                                                Sin Fecha de Control
                                                            @endif
                                                            
                                                        </td>
                                                        <td>
                                                            @if ($agente->CUECOMPLETO != null)
                                                                <small>CUE: <span style="color:green"><br>
                                                                    {{$agente->CUECOMPLETO}}
                                                                </span></small>
                                                            @else
                                                                CUE. No Controlada
                                                            @endif
                                                            
                                                        </td>
                                                    </tr>
                                                    @php $contador++; @endphp
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="15" class="text-center">
                                                        <strong style="color:red">NO SE ENCONTRARON AGENTES CARGADOS PARA ESTA UNIDAD DE LIQUIDACIÓN Y AREA</strong>
                                                    </td>
                                                </tr>
                                            @endif
                                            @if($AgentesNuevos->isNotEmpty())
                                                <tr>
                                                    <td colspan="15" class="text-center" style="color:yellow !important;">
                                                        <strong>En este apartado podrá incorporar a los agentes nuevos (altas/volantes) que aún no figuran en la POF nominal.*  
                                                            *Es importante aclarar que la incorporación a esta lista implica únicamente un registro en SAGE, y estará sujeta a verificación por parte del área de Liquidación, la cual confirmará la validez del agente de alta.</strong>
                                                    </td>
                                                </tr>
                                                @foreach ($AgentesNuevos as $indice => $agente)
                                                    <tr data-id="{{ $agente->idPofIpe }}">
                                                        <td>{{ $contador }}</td>
                                                        <td class="dni-input">{{ $agente->Documento }}</td>
                                                        <td>{{ $agente->Cuil }}</td>
                                                        <td class="text-center">{{ $agente->Trabajo?$agente->Trabajo:"S/S" }}</td>
                                                        <td class="apenom-input">{{ $agente->ApeNom }}</td>
                                                        <td class="text-center">{{ $agente->Escu }}</td>
                                                        <td class="text-center">{{ $agente->Descripcion?$agente->Descripcion:"S/D" }}</td>
                                                        <td class="text-center">{{ $agente->Codigo }}</td>
                                                        <td class="text-center area-input">{{ $agente->Area }}</td>
                                                        <td class="text-center">
                                                            <select class="form-control turno-normal" id="turno_{{ $agente->idPofIpe }}" data-id="{{ $agente->idPofIpe }}" disabled>
                                                                <option value="" disabled {{ is_null($agente->Turno) ? 'selected' : '' }}>Seleccione</option>
                                                                @foreach ($Turnos as $turno)
                                                                    <option value="{{ $turno->idTurno }}"
                                                                        {{ $agente->Turno == $turno->idTurno ? 'selected' : '' }}>
                                                                        {{ $turno->nombre_turno }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input checkbox-ipe-si" type="checkbox"
                                                                    id="ipe_si_{{ $agente->idPofIpe }}"
                                                                    name="ipe_{{ $agente->idPofIpe }}"
                                                                    data-id="{{ $agente->idPofIpe }}"
                                                                    {{ $agente->IPE == 'SI' ? 'checked' : '' }} disabled>
                                                                <label class="form-check-label" for="ipe_si_{{ $agente->idPofIpe }}">SI</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input checkbox-ipe-no" type="checkbox"
                                                                    id="ipe_no_{{ $agente->idPofIpe }}"
                                                                    name="ipe_{{ $agente->idPofIpe }}"
                                                                    data-id="{{ $agente->idPofIpe }}"
                                                                    {{ $agente->IPE == 'NO' ? 'checked' : '' }} disabled>
                                                                <label class="form-check-label" for="ipe_no_{{ $agente->idPofIpe }}">NO</label>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <input 
                                                                type="text"
                                                                class="form-control hora-normal"
                                                                id="hora_{{ $agente->idPofIpe }}"
                                                                data-id="{{ $agente->idPofIpe }}"
                                                                value="{{ $agente->Horas_Trabajadas ?? '' }}"
                                                                min="0" max="50"
                                                                inputmode="numeric"
                                                               disabled
                                                            />
                                                        </td>
                                                        <td class="text-center estado-validacion" id="estado_{{ $agente->idPofIpe }}" style="display:flex;justify-content: space-between;">
                                                            <span class="text-success check-validacion d-none" id="check_{{ $agente->idPofIpe }}">
                                                                <i class="fas fa-check-circle"></i>
                                                            </span>
                                                            
                                                        </td>
                                                        <td>
                                                            @if ($agente->updated_at != null)
                                                                <small>Ultimo Control: <span style="color:green"><br>{{ \Carbon\Carbon::parse($agente->updated_at)->format('d-m-Y H:i:s') }}</span></small>
                                                            @else
                                                                Sin Fecha de Control
                                                            @endif
                                                            
                                                        </td>
                                                        <td>
                                                            @if ($agente->CUECOMPLETO != null)
                                                                <small>CUE: <span style="color:green"><br>
                                                                    {{$agente->CUECOMPLETO}}
                                                                </span></small>
                                                            @else
                                                                CUE. No Controlada
                                                            @endif
                                                            
                                                        </td>
                                                    </tr>
                                                    @php $contador++; @endphp
                                                @endforeach
                                            @endif
                                            {{-- esta parte solo la realiza si tiene elementos relacionados --}}
                                            @if($infoAgentesRelacionados->isNotEmpty())
                                            <tr>
                                                <td colspan="15" class="text-center">
                                                    <strong style="color:rgb(22, 89, 105);">Agentes Relacionados: Afectados, Cambios de Función, Reubicaciones, Permutas, Innovartes, Esc. Municipales, otros</strong>
                                                </td>
                                            </tr>
                                                @foreach ($infoAgentesRelacionados as $indice => $agente)
                                                <tr data-id="{{ $agente->idPofIpe }}">
                                                    <td>{{ $contador }}</td>
                                                    <td class="dni-input">{{ $agente->Documento }}</td>
                                                    <td>{{ $agente->Cuil }}</td>
                                                    <td class="text-center">{{ $agente->Trabajo }}</td>
                                                    <td class="apenom-input">{{ $agente->ApeNom }}</td>
                                                    <td class="text-center">{{ $agente->Escu }}</td>
                                                    <td class="text-center">{{ $agente->Descripcion }}</td>
                                                    <td class="text-center">{{ $agente->Codigo }}</td>
                                                    <td class="text-center">{{ $agente->Area }}</td>
                                                    <td class="text-center">
                                                        @php
                                                            $infoRelAgenteIpe = DB::connection('DB7')->table('tb_rel_pof_ipe')
                                                                ->where('idPofIpe', $agente->idPofIpe)
                                                                ->where('CUECOMPLETO', session('CUECOMPLETOBASE'))
                                                                ->first();
                                                                //dd($infoRelAgenteIpe);
                                                        @endphp
                                                        <select class="form-control turno-relacionado" id="turno_r1_{{ $infoRelAgenteIpe->idPofIpe }}" data-idr1="{{ $infoRelAgenteIpe->idRelPofIpe }}" disabled>
                                                            <option value="" disabled {{ is_null($infoRelAgenteIpe->Turno) ? 'selected' : '' }}>Seleccione</option>
                                                            @foreach ($Turnos as $turno)
                                                                <option value="{{ $turno->idTurno }}"
                                                                    {{ $infoRelAgenteIpe->Turno == $turno->idTurno ? 'selected' : '' }}>
                                                                    {{ $turno->nombre_turno }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td class="text-center">
                                                        
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input checkbox-ipe-si-r1" type="checkbox"
                                                                   id="ipe_si_r1_{{ $agente->idPofIpe }}"
                                                                   name="ipe_{{ $agente->idPofIpe }}"
                                                                   data-id="{{ $agente->idPofIpe }}"
                                                                   data-idr1="{{ $infoRelAgenteIpe->idRelPofIpe }}"
                                                                   {{ $infoRelAgenteIpe->IPE == 'SI' ? 'checked' : '' }} disabled>
                                                            <label class="form-check-label" for="ipe_si_r1_{{ $agente->idPofIpe }}">SI</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input checkbox-ipe-no-r1" type="checkbox"
                                                                   id="ipe_no_r1_{{ $agente->idPofIpe }}"
                                                                   name="ipe_{{ $agente->idPofIpe }}"
                                                                   data-id="{{ $agente->idPofIpe }}"
                                                                   data-idr1="{{ $infoRelAgenteIpe->idRelPofIpe }}"
                                                                   {{ $infoRelAgenteIpe->IPE == 'NO' ? 'checked' : '' }} disabled>
                                                            <label class="form-check-label" for="ipe_no_r1_{{ $agente->idPofIpe }}">NO</label>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <input 
                                                            type="text"
                                                            class="form-control hora-relacionado"
                                                            id="hora_r1_{{ $agente->idPofIpe }}"
                                                            data-id="{{ $agente->idPofIpe }}"
                                                            data-idr1="{{ $infoRelAgenteIpe->idRelPofIpe }}"
                                                            value="{{ $infoRelAgenteIpe->Horas_Trabajadas ?? '' }}"
                                                            min="0" max="50"
                                                            inputmode="numeric" disabled
                                                           
                                                        />
                                                    </td>
                                                    
                                                    <td class="text-center estado-validacion2" id="estado2_{{ $agente->idPofIpe }}" style="display: flex; justify-content: space-between;">
                                                        <span class="text-success check-validacion2 d-none" id="check2_{{ $agente->idPofIpe }}">
                                                            <i class="fas fa-check-circle"></i>
                                                        </span>
                                                        
                                                    </td>
                                                    <td>
                                                        @if ($agente->updated_at != null)
                                                            <small>Control: <span style="color:green"><br>{{ \Carbon\Carbon::parse($agente->updated_at)->format('d-m-Y H:i:s') }}</span></small>
                                                            
                                                            
                                                        @else
                                                            Sin Fecha de Control
                                                        @endif
                                                        
                                                    </td>
                                                    <td>
                                                        @if ($agente->CUECOMPLETO != null)
                                                            <small>CUE: <span style="color:green"><br>
                                                                {{$agente->CUECOMPLETO}}
                                                            </span></small>
                                                        @else
                                                            CUE. No Controlada
                                                        @endif
                                                        
                                                    </td>
                                                </tr>
                                                @php $contador++; @endphp
                                                @endforeach
                                            
                                            @endif

                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>
                      
                    </div>  
                   <!-- Botón para exportar a Excel -->
                   <div class="row d-flex justify-content-center mt-3">
                    <button id="btn-exportar"
                            type="button"
                            class="btn btn-primary"
                            data-mes="{{ $MesActual }}"
                            data-liq="{{ rtrim($liqText, ' / ') }}"
                            data-cue="{{ $CUECOMPLETOBASE }}">
                        Exportar a Excel el Control de IPE
                    </button>
                </div>  
            </section>
            <!-- /.content -->
        </section>
    </section>
</section>

<div class="modal fade" id="modalAgente">
    <div class="modal-dialog modal-custom-width">
      <div class="modal-content">
        <div class="modal-header">
          <div class="modal-title">
            <h4 class="modal-title">Buscar Agente</h4>
            <h6 class="">CUE:<b>{{ session('CUECOMPLETOBASE') }}</b></h6>
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
                <button class="btn btn-sm btn-info form-control" type="button" id="traerAgentes" onclick="getAgentesIPE()">Buscar
                    <i class="fa fa-search ml-2"></i>
                </button>
              </div>
            </div>
              <!-- /.card-header -->
            <div class="card-body">
              <table id="examplex" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Registro</th>
                        <th>DNI</th>
                        <th>CUIL</th>
                        <th>N&deg; de Trabajo</th>
                        <th>Apellido y Nombre</th>
                        <th>SEXO</th>
                        <th>Zona</th>
                        <th>Unidad liquidación - Escuela Origen</th>
                        <th>Situación Revista</th>
                        <th>Antigüedad</th>
                        <th>Agrupación</th>
                        <th>Código Salarial</th>
                        <th>Area</th>
                        <th>Turno de Trabajo</th>
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
@endsection

@section('Script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>

<script>
    window.appData = {
        cue: '{{ $CUECOMPLETOBASE }}',
        mes: '{{ $MesActual }}'
    };
    document.getElementById("btn-exportar").addEventListener("click", function (e) {
        e.preventDefault();
        e.stopPropagation();
    
        const originalTable = document.getElementById("tablacontrolIpe");
    
        if (!originalTable) {
            alert("No se encontró la tabla para exportar");
            return;
        }
    
        // Clonamos la tabla para no modificar la original
        const tableClone = originalTable.cloneNode(true);
    
        // Eliminar la última columna (acciones)
        tableClone.querySelectorAll("tr").forEach((row) => {
            row.deleteCell(-1); // elimina la última celda (acciones)
        });
    
        // Procesar todos los <select> de turnos
        tableClone.querySelectorAll("select").forEach((select) => {
            const selectedText = select.options[select.selectedIndex]?.text || "";
            const td = select.closest("td");
            if (td) td.textContent = selectedText;
        });
    
        // Procesar los checkbox (IPE SI/NO)
        tableClone.querySelectorAll("td").forEach((td) => {
            const checkboxSI = td.querySelector("input[type='checkbox'].checkbox-ipe-si, input[type='checkbox'].checkbox-ipe-si-r1");
            const checkboxNO = td.querySelector("input[type='checkbox'].checkbox-ipe-no, input[type='checkbox'].checkbox-ipe-no-r1");
    
            if (checkboxSI || checkboxNO) {
                td.textContent = checkboxSI?.checked ? "SI" : "NO";
            }
        });
    
        // Procesar los campos de horas
        tableClone.querySelectorAll("input[type='number']").forEach((input) => {
            const value = input.value;
            const td = input.closest("td");
            if (td) td.textContent = value;
        });
    
        // Obtener valores del botón exportar
        const mes = this.getAttribute("data-mes")?.replace(/\s/g, "") || "MES";
        const unidad = this.getAttribute("data-liq")?.replace(/\s/g, "") || "UNIDAD";
        const cue = this.getAttribute("data-cue") || "CUE";
    
        const nombreArchivo = `controlIPE-${mes}-Escu-${unidad}-CUE-${cue}.xlsx`;
    
        // Exportar con SheetJS
        const workbook = XLSX.utils.table_to_book(tableClone, { sheet: "IPE" });
        XLSX.writeFile(workbook, nombreArchivo);
    });

 
</script>


@endsection
