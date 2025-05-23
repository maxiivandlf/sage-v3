@extends('layout.app')
@section('Titulo', 'Sage2.0 - Nivel Superior - Crear Llamado')
@section('LinkCSS')
    {{-- para superior --}}
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">

    <link rel="stylesheet" href="{{ asset('css/superior/tablallamado.css') }}">  
    <!--fin superior -->
@endsection
@section('ContenidoPrincipal')
    <section id="container" class="col-md-12">
        <section id="main-content">
            <section class="content-wrapper">
               
                <div class="form-wrapper container-fluid bg-light p-4 rounded shadow-sm">                   
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif             
                    <div class="row mt-4 align-items-center">
                        <div class="col-auto">
                            <button id="btnCrearLlamado" type="button" class="btn btn-primary btn-mismo-ancho">Crear Convocatoria</button>
                        </div>
                        <div class="col-auto">
                            <button style="margin-left: 5px;" type="button" class="btn btn-success btn-abrir-modal-perfil btn-mismo-ancho" data-toggle="modal" data-target="#modalPerfiles" data-opcion="">
                                Perfil
                            </button>
                        </div>
                        <div class="col-auto">                          
                            <button id="btnVerEspacioCurricular" type="button" class="btn btn-warning">
                               Unidad Curricular
                            </button>
                        </div>


                       
                    </div>      
                    <div id="formularioLlamado" style="display: none;">
                        <form action="{{ route('llamados.store') }}" method="POST" id="formActualizarLlamado" enctype="multipart/form-data">
                            @csrf
                       <h5 class="text-dark text-center font-weight-bold mt-2 mb-2 border-bottom pb-1">Formulario de Creación Llamado: <strong id="idllamadoCrear"></strong></h5>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="idtb_zona">Zona:</label>
                                    <select name="idtb_zona" id="idtb_zona" class="form-control select2" required>
                                        <option value="">Seleccione una zona</option>
                                        @foreach($zonas as $zona)
                                            <option value="{{ $zona->idtb_zona }}">{{ $zona->nombre_zona }}</option>
                                        @endforeach
                                    </select>
                                </div>                            
                                <div class="form-group col-md-4">
                                    <label for="id_instituto_superior">Instituto:</label>
                                    <select name="id_instituto_superior" id="id_instituto_superior" class="form-control select2" required>
                                        <option value="">Seleccione un instituto</option>
                                        {{-- @foreach($institutos as $instituto)
                                            <option value="{{ $instituto->id_instituto_superior }}">{{ $instituto->nombre_instsup }}</option>
                                        @endforeach --}}
                                    </select>
                                </div>
                    
                               <div class="form-group col-md-4">
                                    <label for="idCarrera">Carrera:</label>
                                    <select name="idCarrera" id="idCarrera" class="form-control select2" required>
                                        <option value="">Seleccione una carrera</option>
                                        {{-- @foreach($carreras as $carrera)
                                            <option value="{{ $carrera->idCarrera }}">{{ $carrera->nombre_carrera }}</option>
                                        @endforeach --}}
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="idtipo_llamado">Tipo de llamado:</label>
                                    <select name="idtipo_llamado" id="idtipo_llamado" class="form-control" required>
                                        <option value="">Seleccione un tipo</option>
                                        @foreach($tiposLlamado as $tipo)
                                            <option value="{{ $tipo->idtipo_llamado }}">{{ $tipo->nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>
                        
                                <div class="form-group col-md-4">
                                    <label for="fecha_ini">Fecha Inicio:</label>
                                    <input type="datetime-local" name="fecha_ini" class="form-control" required>
                                </div>
                        
                                <div class="form-group col-md-4">
                                    <label for="fecha_fin">Fecha Fin:</label>
                                    <input type="datetime-local" name="fecha_fin" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <label for="descripcion">Descripción:</label>
                                <textarea name="descripcion" class="form-control"></textarea>
                            </div>
                              <!-- imagen -->
                              <div class="form-row">                           
                                <label for="nombre_img" class="font-weight-bold mt-3">Imagen:</label>
                                <input type="file" name="imagen" id="imagen" class="form-control">                          
                            </div>
                             <!-- link-->
                            <div class="form-row">                           
                                <label for="url_form" class="font-weight-bold mt-3">Link Formulario:</label>
                                <input type="text" name="url_form" id="url_form" class="form-control">                                         
                            </div>
                            <div class="form-row">                             
                                  <input type="hidden" name="llamado_id" id="llamado_id">                        
                                  <button type="submit" class="btn btn-primary" id="btn-actualizar">Actualizar</button>
                                
                            </div>                            
                        </form>
                        <!-- Botones -->
                        <button id="btnCargo" type="button" class="btn btn-secondary mt-3" data-id="">Agregar Cargo</button>
                        <button id="btnEspacio" type="button" class="btn btn-secondary mt-3" data-id="">Agregar Espacio Curricular</button>
                    </div>
                </div>
            </section>
        </section>
    </section>

    <section id="container" class="col-12">
            <section id="main-content">
               <section class="content-wrapper">                               
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                   
                    <div class="row">
                        <h3 style="display: block">Espacios Cargados</h3>
                        <table class="table table-striped table-bordered dt-responsive nowrap tablaEspacios" style="width:100%">
                            <tr>
                                <th>N° Llamado</th>
                                <th>Unidad Curricular</th>
                                <th>Horas Catedra</th>
                                <th>Situación de Revista</th>
                                <th>Horario</th>                               
                                <th>Turno</th>                               
                                <th>Periodo</th>
                                <th>Perfil</th>                               
                                <th>Editar / Borrar</th>
                            </tr>
                            <tr>
                                <td colspan="8">Sin Información</td>
                            </tr>
                        </table>
                    </div>

                    <div class="row">
                        <h3 style="display: block">Cargos Cargados</h3>
                        <table class="table table-striped table-bordered dt-responsive nowrap tablaCargos" style="width:100%">
                            <tr>
                                <th>N° Llamado</th>
                                <th>Cargo</th>
                                <th>Horas Catedra</th>
                                <th>Situación de Revista</th>
                                <th>Horario</th>                               
                                <th>Turno</th>                               
                                <th>Periodo</th>
                                <th>Perfil</th>                               
                                <th>Editar / Borrar</th>
                            </tr>
                            <tr>
                                <td colspan="8">Sin Información</td>
                            </tr>
                        </table>
                    </div>
                </section>               
           </section>
    </section>   
    {{-- modales --}}
    <!-- Modal Cargo -->
    <div class="modal fade col-md-12" id="modalCargo" tabindex="-1" role="dialog" aria-labelledby="modalCargoLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Cargo al llamado <span id="mostrarIdCargo"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">             
                    <form action="{{ route('llamado.agregarCargo') }}" method="POST" id="formAgregarCargo">
                        @csrf
                        <div class="row mt-2">             
                            <div class="col-md-6">
                                <label for="cargoSelect" class="font-weight-bold">Cargo:</label>
                                <select name="idtb_cargos_modal" class="form-control" id="cargoSelect_modal">
                                    @foreach($cargos as $cargo)
                                        <option value="{{ $cargo->idtb_cargos }}">{{ $cargo->nombre_cargo }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="font-weight-bold">Turno:</label>
                                <select name="idTurno_modal" class="form-control" id="idTurno_modal">
                                    @foreach($turnos as $turno)
                                        <option value="{{ $turno->idTurno }}">{{ $turno->nombre_turno }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>                 
                        <div class="row mt-2">                         
                            <div class="col-md-6">
                                <label class="font-weight-bold">Horas Cátedra:</label>
                                <input type="text" name="horacat_modal" class="form-control" maxlength="20" id="horacat_modal">
                            </div>
                            <div class="col-md-6">
                                <label class="font-weight-bold">Situación de Revista:</label>
                                <select name="idtb_situacion_revista_modal" class="form-control" id="idtb_situacion_revista_modal">
                                    @foreach($situacion_revista as $situacion)
                                        <option value="{{ $situacion->idtb_situacion_revista }}">{{ $situacion->nombre_situacion_revista }}</option>
                                    @endforeach
                                </select>
                            </div>                            
                        </div>    
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label class="font-weight-bold">Horario:</label>
                                <textarea name="horario_modal" class="form-control" id="horario_modal"></textarea>
                            </div>    
                            <div class="col-md-6">
                                <label class="font-weight-bold">Período de Cursado:</label>
                                <select name="idtb_periodo_cursado_modal" class="form-control" id="idtb_periodo_cursado_modal">
                                    @foreach($periodo_cursado as $periodo)
                                        <option value="{{ $periodo->idtb_periodo_cursado }}">{{ $periodo->nombre_periodo }}</option>    
                                    @endforeach
                                </select>
                            </div>                       
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label for="idtb_perfil_modal" class="font-weight-bold">Perfil:</label>
                                     <!-- Campo oculto donde guardamos el ID seleccionado -->
                                     <input type="hidden" name="idtb_perfil_modal" id="idPerfilCargo">
                                    <!-- Input visible (solo lectura) + botón -->
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="nombrePerfilCargo" placeholder="Seleccione un perfil..." readonly required>
                                        <button type="button" class="btn btn-outline-primary btn-abrir-modal-perfil" data-toggle="modal" data-target="#modalPerfiles" data-opcion="cargo">
                                            Buscar Perfil
                                        </button>

                                    </div>             
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" id="llamadoIdCargo" name="llamado_id">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary" id="btnSubmitCargo" >Agregar</button>
                        </div>
                    </form>    
                </div>
            </div>
        </div>
    </div>
      <!-- Modal Editar Cargo -->
    <div class="modal fade col-md-12" id="modalEditarCargo" tabindex="-1" role="dialog" aria-labelledby="modalEditarCargoLabel">        
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Cargo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('llamado.editarCargo') }}" method="POST" id="formEditarCargo">
                        @csrf
                    
                        <!-- IDs ocultos -->
                        <input type="hidden" name="idCargoEditar" id="idCargoEditar">
                        <input type="hidden" name="llamado_id" id="llamadoIdCargo">
                    
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label class="font-weight-bold">Cargo:</label>
                                <select name="idtb_cargos_modal" class="form-control" id="cargoSelectEditar">
                                    @foreach($cargos as $cargo)
                                        <option value="{{ $cargo->idtb_cargos }}">{{ $cargo->nombre_cargo }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="font-weight-bold">Turno:</label>
                                <select name="idTurno_modal" class="form-control" id="idTurnoEditar">
                                    @foreach($turnos as $turno)
                                        <option value="{{ $turno->idTurno }}">{{ $turno->nombre_turno }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>                    
                        <div class="row mt-2">                            
                            <div class="col-md-6">
                                <label class="font-weight-bold">Horas Cátedra:</label>
                                <input type="text" name="horacat_modal" class="form-control" id="horacatEditarCargo" maxlength="20" >
                            </div>
                            <div class="col-md-6">
                                <label class="font-weight-bold">Situación de Revista:</label>
                                <select name="idtb_situacion_revista_modal" class="form-control" id="idSituacionRevistaEditar">
                                    @foreach($situacion_revista as $situacion)
                                        <option value="{{ $situacion->idtb_situacion_revista }}">{{ $situacion->nombre_situacion_revista }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label class="font-weight-bold">Horario:</label>
                                <textarea name="horario_modal" class="form-control" id="horarioEditarCargo"></textarea>
                            </div>    
                            <div class="col-md-6">
                                <label class="font-weight-bold">Periodo de Cursado:</label>
                                <select name="idtb_periodo_cursado_modal" class="form-control" id="idPeriodoEditar">
                                    @foreach($periodo_cursado as $periodo)
                                        <option value="{{ $periodo->idtb_periodo_cursado }}">{{ $periodo->nombre_periodo }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>                                                  
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label class="font-weight-bold">Perfil:</label>
                                     <!-- Campo oculto donde guardamos el ID seleccionado -->
                                     <input type="hidden" name="idtb_perfil_modal" id="idPerfilCargoEditar">
                                    <!-- Input visible (solo lectura) + botón -->
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="nombrePerfilCargoEditar" placeholder="Seleccione un perfil..." readonly>
                                        <button type="button" class="btn btn-outline-primary btn-abrir-modal-perfil" data-toggle="modal" data-target="#modalPerfiles" data-opcion="cargoEditar">
                                            Buscar Perfil
                                        </button>

                                    </div>             
                            </div>
                        </div>                    
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar cambios</button>
                        </div>
                    </form>                    
                </div>
            </div>
        </div>
    </div>
  
     <!-- Modal Espacio Curricular -->
    <div class="modal fade col-md-12" id="modalEspacio" tabindex="-1" role="dialog" aria-labelledby="modalEspacioLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Espacio Curricular al llamado <span id="mostrarIdEspacio"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">             
                    <form action="{{ route('llamado.agregarEspacio') }}" method="POST" id="formAgregarEspacio">
                        @csrf
                        <div class="row-mt-2">             
                            <div class="col-md-12">
                                <label for="espacioSelect" class="font-weight-bold">Unidad Curricular:</label>
                                <select name="idEspacioCurricular_modal" class="form-control espacioSelect" id="espacioSelect_modal">
                                    @foreach($espacios as $espacio)
                                        <option value="{{ $espacio->idEspacioCurricular }}">{{ $espacio->nombre_espacio }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                        </div>
                        <div class="row mt-3">                            
                            <div class="col-md-4">
                                <label class="font-weight-bold">Horas Cátedra:</label>
                                <input type="text" name="horacat_modal" class="form-control" maxlength="20" id="horacat_modal">
                            </div>
                            <div class="col-md-4">
                                <label class="font-weight-bold">Situación de Revista:</label>
                                <select name="idtb_situacion_revista_modal" class="form-control" id="idtb_situacion_revista_modal">
                                    @foreach($situacion_revista as $situacion)
                                        <option value="{{ $situacion->idtb_situacion_revista }}">{{ $situacion->nombre_situacion_revista }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="font-weight-bold">Turno:</label>
                                <select name="idTurno_modal" class="form-control" id="idTurno_modal">
                                    @foreach($turnos as $turno)
                                        <option value="{{ $turno->idTurno }}">{{ $turno->nombre_turno }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>   
                        <div class="row mt-2"> 
                            <div class="col-md-6">
                                <label class="font-weight-bold">Horario:</label>
                                <textarea name="horario_modal" class="form-control" id="horario_modal"></textarea>
                            </div>     
                            <div class="col-md-6">
                                <label class="font-weight-bold">Período de Cursado:</label>
                                <select name="idtb_periodo_cursado_modal" class="form-control" id="idtb_periodo_cursado_modal">
                                    @foreach($periodo_cursado as $periodo)
                                        <option value="{{ $periodo->idtb_periodo_cursado }}">{{ $periodo->nombre_periodo }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>                    
                        <div class="row mt-2">
                            <div class="col-md-12">
                                    <label class="font-weight-bold">Perfil:</label>
                                      <!-- Campo oculto donde guardamos el ID seleccionado -->
                                     <input type="hidden" name="idtb_perfil_modal" id="idPerfilEspacio">
                                    <!-- Input visible (solo lectura) + botón -->
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="nombrePerfilEspacio" placeholder="Seleccione un perfil..." readonly>
                                        <button type="button" class="btn btn-outline-primary btn-abrir-modal-perfil" data-toggle="modal" data-target="#modalPerfiles" data-opcion="espacio">
                                            Buscar Perfil
                                        </button>

                                    </div>                               
                             </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" id="llamadoIdEspacio" name="llamado_id">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary" id="btnSubmitEspacio" >Agregar</button>
                        </div>
                    </form>    
                </div>
            </div>
        </div>
    </div>

     {{-- modal para editar --}}
    <!-- Modal Editar Espacio Curricular por llamado-->
    <div class="modal fade col-md-12" id="modalEditarEspacio" tabindex="-1" role="dialog" aria-labelledby="modalEditarEspacioLabel">        
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Espacio Curricular</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('llamado.editarEspacio') }}" method="POST" id="formEditarEspacio">
                        @csrf
                        <input type="hidden" name="idEspacioEditar" id="idEspacioEditar">
            
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label class="font-weight-bold">Unidad Curricular:</label>
                                <select name="idEspacioCurricular_modal" class="form-control" id="espacioSelectEditar">
                                @foreach($espacios as $espacio)
                                    <option value="{{ $espacio->idEspacioCurricular }}">{{ $espacio->nombre_espacio }}</option>
                                @endforeach
                                </select>
                            </div>
                          
                        </div>            
                        <div class="row mt-3">                          
                            <div class="col-md-4">
                                <label class="font-weight-bold">Horas Cátedra:</label>
                                <input type="text" name="horacat_modal" class="form-control" id="horacatEditar" maxlength="20">
                            </div>
                            <div class="col-md-4">
                                <label class="font-weight-bold">Situación de Revista:</label>
                                <select name="idtb_situacion_revista_modal" class="form-control" id="idSituacionRevistaEditar">
                                @foreach($situacion_revista as $situacion)
                                    <option value="{{ $situacion->idtb_situacion_revista }}">{{ $situacion->nombre_situacion_revista }}</option>
                                @endforeach
                                </select>
                            </div>        
                              <div class="col-md-4">
                                <label class="font-weight-bold">Turno:</label>
                                <select name="idTurno_modal" class="form-control" id="idTurnoEditar">
                                @foreach($turnos as $turno)
                                    <option value="{{ $turno->idTurno }}">{{ $turno->nombre_turno }}</option>
                                @endforeach
                                </select>
                            </div>                   
                        </div>            
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label class="font-weight-bold">Horario:</label>
                                <textarea name="horario_modal" class="form-control" id="horarioEditar"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="font-weight-bold">Periodo de Cursado:</label>
                                <select name="idtb_periodo_cursado_modal" class="form-control" id="idPeriodoEditar">
                                @foreach($periodo_cursado as $periodo)
                                    <option value="{{ $periodo->idtb_periodo_cursado }}">{{ $periodo->nombre_periodo }}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label class="font-weight-bold">Perfil:</label>
                                     <!-- Campo oculto donde guardamos el ID seleccionado -->
                                     <input type="hidden" name="idtb_perfil_modal" id="idPerfilEspacioEditar">
                                    <!-- Input visible (solo lectura) + botón -->
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="nombrePerfilEspacioEditar" placeholder="Seleccione un perfil..." readonly>
                                       <button type="button" class="btn btn-outline-primary btn-abrir-modal-perfil" data-toggle="modal" data-target="#modalPerfiles" data-opcion="espacioEditar">
                                            Buscar Perfil
                                        </button>

                                    </div>             
                            </div>
                        </div>            
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal para seleccionar perfil -->
    <div class="modal fade" id="modalPerfiles" tabindex="-1" role="dialog" aria-labelledby="modalPerfilesLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
               <div class="modal-header">
                    <h5 class="modal-title">Seleccione un Perfil</h5>
                      <button type="button" class="btn btn-success btn-sm ml-3" id="btnAgregarPerfil">
                        + Agregar Perfil
                    </button>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <table class="table table-bordered table-hover" id="tablaPerfiles">
                    <thead>
                        <tr>
                        <th>ID</th>
                        <th>Nombre del Perfil</th>
                        <th>Acción</th>
                        </tr>
                    </thead>
                        <tbody>
                            @foreach($perfil as $per)
                                <tr>
                                    <td>{{ $per->idtb_perfil }}</td>
                                    <td>{{ $per->nombre_perfil }}</td>
                                    <td>
                                    <button type="button" class="btn btn-success btn-xs btn-seleccionar-perfil"
                                            data-id="{{ $per->idtb_perfil }}"
                                            data-nombre="{{ $per->nombre_perfil }}">
                                        Seleccionar
                                    </button>
                                     <button type="button" class="btn btn-xs btn-warning btn-editar-perfil"
                                            data-id="{{ $per->idtb_perfil }}"
                                            data-nombre="{{ $per->nombre_perfil }}">
                                        Editar
                                    </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Agregar/Editar Perfil -->
    <div class="modal fade" id="modalAgregarEditarPerfil" tabindex="-1" role="dialog" aria-labelledby="modalLabelPerfil" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document" style="max-width: 800px;">
            <form id="formPerfil">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="modalLabelPerfil">Agregar Perfil</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="idPerfilForm">
                    <div class="form-group">
                        <label for="nombrePerfilForm">Nombre del Perfil</label>
                        <textarea class="form-control" id="nombrePerfilForm" rows="4" style="resize: vertical;" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </div>
            </form>
        </div>
    </div>
    {{-- Modal para agregar/crear espacio curricular --}}
    <div class="modal fade" id="modalEspacios" tabindex="-1" role="dialog" aria-labelledby="modalLabelEspacios" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabelEspacios">Espacios Curriculares</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="d-flex justify-content-end mb-3">
                            <button id="btnAgregarEspacio" class="btn btn-success">Agregar Nuevo Espacio</button>
                        </div>

                        <table class="table table-bordered table-hover" id="tablaEspacios">
                        <thead>
                            <tr>
                            <th>#</th>
                            <th>Nombre del Espacio</th>
                            <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Se llena por AJAX -->
                        </tbody>
                        </table>
                    </div>
                </div>
        </div>
    </div>

        <div class="modal fade" id="modalFormularioEspacio" tabindex="-1" role="dialog" aria-labelledby="modalLabelFormEspacio" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <form id="formEspacioCurricular">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalLabelFormEspacio">Agregar Espacio Curricular</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            @csrf
                            <input type="hidden" id="idEspacioForm">
                            <div class="form-group">
                                <label for="nombreEspacioForm">Nombre del Espacio</label>
                                <textarea class="form-control" id="nombreEspacioForm" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        

  
@endsection

@section('Script')     
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>

    <!-- Librerías necesarias para exportación -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
   
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
 
 
    <script>
          // Variables globales
          let modo = 'agregar'; 
          let filaActual = null;
          let idEspacioEditar = null; 
          let idCargoEditar = null; // Para editar el cargo
          let formularioEnviado = false;
    $(document).ready(function () {            
            $('#btnCrearLlamado').on('click', function () {
                Swal.fire({
                    title: '¿Estás segura?',
                    text: "¿Querés crear un nuevo llamado?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, crear',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route("llamado.crear") }}',
                            type: 'POST',
                            headers: {
                                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                            },
                            success: function (response) {
                                console.log(response);
                                $('#llamado_id').val(response.id);
                                $('#idllamadoCrear').text(response.id);
                                $('#formularioLlamado').show();
                                $('#btnCrearLlamado').hide();

                                // Agregar el ID al data-id de los botones
                                $('#btnCargo').attr('data-id', response.id);
                                $('#btnEspacio').attr('data-id', response.id);

                                Swal.fire(
                                    '¡Llamado creado!',
                                    'Se creó un nuevo llamado con ID: ' + response.id,
                                    'success'
                                   )

                                llenarTablaEspacio($('#llamadoIdEspacio').val()); // <--- ACÁ
                                llenarTablaCargo($('#llamadoIdCargo').val());

                            },
                            error: function () {
                                Swal.fire(
                                    'Error',
                                    'Hubo un problema al crear el llamado.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });

            $('#formActualizarLlamado').on('submit', function (e) {
                e.preventDefault();

                Swal.fire({
                    title: '¿Actualizar llamado?',
                    text: "Se guardarán los cambios de este llamado.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, actualizar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const formData = new FormData(this); // 👈 Aca usamos FormData correctamente

                        $.ajax({
                            url: '{{ route("llamado.actualizar") }}',
                            type: 'POST',
                            data: formData,
                            processData: false, // 👈 Importante para que jQuery NO convierta los datos
                            contentType: false, // 👈 Importante para que se envíe como multipart/form-data
                            success: function () {
                                 formularioEnviado = true;
                                Swal.fire(
                                    '¡Actualizado!',
                                    'Los datos del llamado se actualizaron correctamente.',
                                    'success'
                                );
                            },
                            error: function (xhr) {
                                console.log(xhr.responseText); // Para debug si da error
                                Swal.fire(
                                    'Error',
                                    'No se pudo actualizar el llamado.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
        //para modales
     });
            // Abrir modal de Cargo
            $('#btnCargo').on('click', function () {
                const llamadoId = $(this).data('id');
                $('#llamadoIdCargo').val(llamadoId);
                $('#mostrarIdCargo').text(llamadoId);
                $('#modalCargo').modal('show');

                modo = 'agregar';
                filaActual = null;
                idCargoEditar = null;
                          
                $('#btnSubmitCargo').text('Agregar');
            });

            // Abrir modal de Espacio Curricular
            $('#btnEspacio').on('click', function () {
                const llamadoId = $(this).data('id');
                $('#llamadoIdEspacio').val(llamadoId);
                $('#mostrarIdEspacio').text(llamadoId);
                $('#modalEspacio').modal('show');

                modo = 'agregar';
                filaActual = null;
                idEspacioEditar = null;
                          
                $('#btnSubmitEspacio').text('Agregar');
             });
        

            // BOTÓN BORRAR ESPACIOS
            $(document).on('click', '.btn-borrar', function () {
                const fila = $(this).closest('tr');
                const idEspacio = fila.data('idespacio');
                console.log(idEspacio);
                Swal.fire({
                    title: '¿Seguro de eliminar?',
                    text: "Esta acción no se puede deshacer.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, borrar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route("llamado.eliminarEspacio") }}', // RUTA para borrar
                            type: 'POST',
                            data: {
                                id: idEspacio,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function (response) {
                                if (response.success) {
                                    Swal.fire('¡Borrado!', response.message, 'success');
                                    llenarTablaEspacio($('#llamadoIdEspacio').val());
                                } else {
                                    Swal.fire('Error', 'No se pudo eliminar.', 'error');
                                }
                            },
                            error: function (xhr) {
                                Swal.fire('Error', 'Error en el servidor.', 'error');
                            }
                        });
                    }
                });
            });

            //FORMULARIO AGREGAR ESPACIOS
            $('#formAgregarEspacio').on('submit', function (e) {
            e.preventDefault();

                Swal.fire({
                    title: modo === 'agregar' ? '¿Agregar espacio curricular?' : '¿Guardar cambios?',
                    text: modo === 'agregar' ? "Se agregará un nuevo espacio." : "Se actualizará el espacio.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, confirmar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = $(this);
                        const formData = form.serialize();

                        let url = '';
                        if (modo === 'agregar') {
                            url = form.attr('action'); // guardar normal
                        } else if (modo === 'editar') {
                            url = '{{ route("llamado.editarEspacio") }}'; // una nueva ruta para editar
                        }

                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: modo === 'agregar' ? formData : formData + '&idEspacio=' + idEspacioEditar,
                            success: function (response) {
                                if (response.success) {
                                    Swal.fire(
                                        '¡Correcto!',
                                        response.message,
                                        'success'
                                    ).then(() => {
                                        $('#modalEspacio').modal('hide');
                                        form[0].reset();
                                        llenarTablaEspacio($('#llamadoIdEspacio').val());

                                        modo = 'agregar'; // Volver a agregar
                                        filaActual = null;
                                        idEspacioEditar = null;
                                    });
                                } else {
                                    Swal.fire('Error', 'Ocurrió un error.', 'error');
                                }
                            },
                            error: function (xhr) {
                                Swal.fire('Error', 'No se pudo procesar.', 'error');
                            }
                        });
                    }
                });
            });

            //formulario agregar cargos
            $('#formAgregarCargo').on('submit', function (e) {
                    e.preventDefault();

                    Swal.fire({
                        title: modo === 'agregar' ? '¿Agregar Cargo?' : '¿Guardar cambios?',
                        text: modo === 'agregar' ? "Se Agregará Un Nuevo Cargo." : "Se actualizará El Cargo.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, confirmar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const form = $(this);
                            const formData = form.serialize();

                            let url = '';
                            if (modo === 'agregar') {
                                url = form.attr('action'); // guardar normal
                            } else if (modo === 'editar') {
                                url = '{{ route("llamado.editarCargo") }}'; // una nueva ruta para editar
                            }
                            
                            $.ajax({
                                url: url,
                                type: 'POST',
                                data: modo === 'agregar' ? formData : formData + '&idCargo=' + idCargoEditar,
                                success: function (response) {
                                    if (response.success) {
                                        Swal.fire(
                                            '¡Correcto!',
                                            response.message,
                                            'success'
                                        ).then(() => {
                                            $('#modalCargo').modal('hide');
                                            form[0].reset();
                                            llenarTablaCargo($('#llamadoIdCargo').val());

                                            modo = 'agregar'; // Volver a agregar
                                            filaActual = null;
                                            idEspacioEditar = null;
                                        });
                                    } else {
                                        Swal.fire('Error', 'Ocurrió un error.', 'error');
                                    }
                                },
                                error: function (xhr) {
                                    Swal.fire('Error', 'No se pudo procesar.', 'error');
                                }
                            });
                        }
                    });
             });

             function llenarTablaCargo(idLlamado) {
                $.ajax({
                    url: '{{ route("llamado.obtenerCargos") }}',
                    type: 'POST',
                    data: { idLlamado: idLlamado, _token: '{{ csrf_token() }}' },
                    success: function (response) {
                        let tabla = $('.tablaCargos tbody'); // Clase diferente para cargos
                        tabla.empty();
                        console.log(response);
                        if (response.cargos.length > 0) {
                            tabla.append(`
                            <tr>
                                <th>N° Llamado</th>
                                <th>Cargo</th>
                                <th>Horas Catedra</th>
                                <th>Situación de Revista</th>
                                <th>Horario</th>                       
                                <th>Turno</th>                      
                                <th>Periodo</th>
                                <th>Perfil</th>                      
                                <th>Editar / Borrar</th>
                            </tr>
                        `);
                            response.cargos.forEach(function (cargo) {
                                tabla.append(`
                                    <tr
                                        data-idllamado="${cargo.idllamado}"
                                        data-idcargorel="${cargo.idrel_cargo_por_llamado}"
                                        data-idcargo="${cargo.idtb_cargos}"
                                        data-horacat="${cargo.horacat_cargo}"
                                        data-situacionrevista="${cargo.idtb_situacion_revista}"
                                        data-horario="${cargo.horario_cargo}"
                                        data-turno="${cargo.idTurno}"
                                        data-periodo="${cargo.idtb_periodo_cursado}"
                                        data-perfil="${cargo.idtb_perfil}"
                                        data-nombreperfil="${cargo.nombre_perfil}">
                                        <td>${cargo.idllamado ?? ''}</td>
                                        <td>${cargo.nombre_cargo ?? ''}</td>
                                        <td>${cargo.horacat_cargo ?? ''}</td>
                                        <td>${cargo.nombre_situacion_revista ?? ''}</td>
                                        <td>${cargo.horario_cargo ?? ''}</td>
                                        <td>${cargo.nombre_turno ?? ''}</td>
                                        <td>${cargo.nombre_periodo ?? ''}</td>
                                        <td>${cargo.nombre_perfil ?? ''}</td>
                                        <td>
                                            <button class="btn btn-warning btn-sm btn-editar-cargo">Editar</button>
                                            <button class="btn btn-danger btn-sm btn-borrar-cargo">Borrar</button>
                                        </td>
                                    </tr>
                                `);
                            });
                        } else {
                            tabla.append(`<tr><td colspan="8" class="text-center">No hay cargos cargados.</td></tr>`);
                        }
                    },
                    error: function (xhr) {
                        console.error('Error al cargar cargos:', xhr.responseText);
                    }
                });
            }
              // BOTÓN BORRAR CARGOS
            $(document).on('click', '.btn-borrar-cargo', function () {
                const fila = $(this).closest('tr');
                const idCargoRel = fila.data('idcargorel'); // ← clave: este es el id de la tabla rel

                if (!idCargoRel) {
                    console.error("No se encontró el ID del cargo para borrar.");
                    return;
                }

                Swal.fire({
                    title: '¿Eliminar cargo?',
                    text: "Esta acción no se puede deshacer.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route("llamado.eliminarCargo") }}',
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                id: idCargoRel // <- lo que espera el controller
                            },
                            success: function (response) {
                                if (response.success) {
                                    Swal.fire('¡Eliminado!', response.message, 'success');
                                    llenarTablaCargo($('#llamadoIdCargo').val());
                                } else {
                                    Swal.fire('Error', 'No se pudo eliminar.', 'error');
                                }
                            },
                            error: function (xhr) {
                                console.error("Error al eliminar:", xhr.responseText);
                                Swal.fire('Error', 'Error en el servidor.', 'error');
                            }
                        });
                    }
                });
            });


            // Submit Editar Cargo
            $(document).on('click', '.btn-editar-cargo', function () {
                const fila = $(this).closest('tr');
                 // Debug para ver los datos
                console.log("Datos fila:", fila.data());

                // Cargar los datos al modal
                $('#idCargoEditar').val(fila.data('idcargorel'));
                $('#cargoSelectEditar').val(fila.data('idcargo')).trigger('change');
                $('#horacatEditarCargo').val(fila.data('horacat'));
                $('#idSituacionRevistaEditar').val(fila.data('situacionrevista'));
                $('#horarioEditarCargo').val(fila.data('horario'));
                $('#idTurnoEditar').val(fila.data('turno'));
                $('#idPeriodoEditar').val(fila.data('periodo'));
                $('#idPerfilCargoEditar').val(fila.data('perfil'));
                $('#nombrePerfilCargoEditar').val(fila.data('nombreperfil'));

                // Asegurarse de pasar el id del llamado (usamos el del botón de abrir modal original)
                $('#llamadoIdCargo').val($('#btnCargo').data('id'));

                // Mostrar el modal
                $('#modalEditarCargo').modal('show');
            });

            $('#formEditarCargo').on('submit', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: '¿Guardar cambios?',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, guardar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const formData = $(this).serialize();
                        const formArray = $(this).serializeArray();
                        const formJson = {};

                        formArray.forEach(({ name, value }) => {
                            formJson[name] = value;
                        });

                        console.log(formJson);
                        $.ajax({
                            url: $(this).attr('action'),
                            type: 'POST',
                            data: formData,
                            headers: {
                                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                                    },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire('¡Actualizado!', response.message, 'success');
                                    $('#modalEditarCargo').modal('hide');
                                    llenarTablaCargo($('#llamadoIdCargo').val());
                                } else {
                                    Swal.fire('Error', 'Ocurrió un error.', 'error');
                                }
                            },
                            error: function(xhr) {
                                Swal.fire('Error', 'Error en el servidor.', 'error');
                            }
                        });
                    }
                });
            })

        function llenarTablaEspacio(idLlamado) {
            $.ajax({
                url: '{{ route("llamado.obtenerEspacios") }}',
                type: 'POST',
                data: {
                    idLlamado: idLlamado,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    let tabla = $('.tablaEspacios tbody');
                    tabla.empty(); // Vaciar la tabla antes de llenar

                    if (response.espacios.length > 0) {
                        tabla.append(`
                            <tr>
                                <th>N° Llamado</th>
                                <th>Unidad Curricular</th>
                                <th>Horas Catedra</th>
                                <th>Situación de Revista</th>
                                <th>Horario</th>                       
                                <th>Turno</th>                      
                                <th>Periodo</th>
                                <th>Perfil</th>                      
                                <th>Editar / Borrar</th>
                            </tr>
                        `);
                        response.espacios.forEach(function (espacio) {
                            tabla.append(`
                                <tr
                                    data-idllamado="${espacio.idllamado}"
                                    data-idespacio="${espacio.idrel_espacios_por_llamado}" 
                                    data-idespaciocurricular="${espacio.idEspacioCurricular}" 
                                    data-horacat="${espacio.horacat_espacio}" 
                                    data-situacionrevista="${espacio.idtb_situacion_revista}" 
                                    data-horario="${espacio.horario_espacio}" 
                                    data-turno="${espacio.idTurno}" 
                                    data-periodo="${espacio.idtb_periodo_cursado}" 
                                    data-perfil="${espacio.idtb_perfil}"
                                    data-nombreperfil="${espacio.nombre_perfil}">
                                    <td>${espacio.idllamado ?? ''}</td>
                                    <td>${espacio.nombre_espacio ?? ''}</td>
                                    <td>${espacio.horacat_espacio ?? ''}</td>
                                    <td>${espacio.nombre_situacion_revista ?? ''}</td>
                                    <td>${espacio.horario_espacio ?? ''}</td>
                                    <td>${espacio.nombre_turno ?? ''}</td>                          
                                    <td>${espacio.nombre_periodo ?? ''}</td>
                                    <td>${espacio.nombre_perfil ?? ''}</td>                          
                                    <td>
                                        <button class="btn btn-sm btn-warning btn-editar">Editar</button>
                                        <button class="btn btn-sm btn-danger btn-borrar">Borrar</button>
                                    </td>
                                </tr>
                            `);
                        });
                    } else {
                        tabla.append(`
                            <tr>
                                <td colspan="8" class="text-center">No hay espacios cargados.</td>
                            </tr>
                        `);
                    }
                },
                error: function (xhr) {
                    console.error('Error al cargar espacios:', xhr.responseText);
                }
            });
        }

        $(document).on('click', '.btn-editar', function () {
            const fila = $(this).closest('tr');

            // Cargar los datos en el modal de editar
            $('#idEspacioEditar').val(fila.data('idespacio'));
            $('#espacioSelectEditar').val(fila.data('idespaciocurricular'));
            $('#horacatEditar').val(fila.data('horacat'));
            $('#idSituacionRevistaEditar').val(fila.data('situacionrevista'));
            $('#horarioEditar').val(fila.data('horario'));
            $('#idTurnoEditar').val(fila.data('turno'));
            $('#idPeriodoEditar').val(fila.data('periodo'));
            $('#idPerfilEspacioEditar').val(fila.data('perfil'));
            
            console.log({
                idEspacio: fila.data('idespacio'),
                idEspacioCurricular: fila.data('idespaciocurricular'),
                horacat: fila.data('horacat'),
                situacionrevista: fila.data('situacionrevista'),
                horario: fila.data('horario'),
                turno: fila.data('turno'),
                periodo: fila.data('periodo'),
                perfil: fila.data('perfil')
            });
                        // Mostrar el modal de edición
            $('#modalEditarEspacio').modal('show');
        });
        // Submit Editar Espacio
        $('#formEditarEspacio').on('submit', function(e) {
            e.preventDefault();

            Swal.fire({
                title: '¿Guardar cambios?',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Sí, guardar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = $(this).serialize();
                    $.ajax({
                        url: $(this).attr('action'),
                        type: 'POST',
                        data: formData,
                        headers: {
                                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                                },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('¡Actualizado!', response.message, 'success');
                                $('#modalEditarEspacio').modal('hide');
                                llenarTablaEspacio($('#llamadoIdEspacio').val());
                            } else {
                                Swal.fire('Error', 'Ocurrió un error.', 'error');
                            }
                        },
                        error: function(xhr) {
                            Swal.fire('Error', 'Error en el servidor.', 'error');
                        }
                    });
                }
            });
        });

    </script>
       <script>
        window.routes = {
            obtenerInstitutos: "{{ route('llamado.obtenerInstitutos') }}",
            obtenerCarreras: "{{ route('llamado.obtenerCarreras') }}",
            csrf: "{{ csrf_token() }}"
        };
        window.addEventListener("beforeunload", function (e) {
            if (!formularioEnviado) {
                // ⚠️ Este mensaje no siempre se puede personalizar por seguridad del navegador
                const mensaje = "Tenés cambios sin guardar. ¿Seguro que querés salir?";
                e.preventDefault(); // Necesario para algunos navegadores
                e.returnValue = mensaje;
                return mensaje;
            }
        });
    </script>
    <script src="{{ asset('js/superior/tipoLlamado.js') }}"></script>

@endsection
