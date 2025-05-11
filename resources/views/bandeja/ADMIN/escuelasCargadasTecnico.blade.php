@extends('layout.app')

@section('Titulo', 'Sage2.0 - Nuevo Usuario ADMIN')

@section('ContenidoPrincipal')

<section id="container">
    <section id="main-content">
        <section class="content-wrapper">
                <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- Buscador Agente -->
                    <h4 class="text-center display-4">Panel de Escuelas para Técnico</h4>
                    <!-- Agregar Nuevo Agente -->
                   
                    <div class="row d-flex justify-content-center">
                        <!-- left column -->
                        <div class="col-12">
                            <div class="card card-lightblue">
                            <div class="card-header">
                                <h3 class="card-title">Lista de Escuelas sin datos</h3>&nbsp; 
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th style="background-color: salmon">COD</th>
                                    <th style="background-color: salmon">CUE</th>
                                    <th style="background-color: salmon">Turno</th>
                                    <th>Nombre Inst.</th>
                                    <th>Nivel</th>
                                    <th>Categoría</th>
                                    <th>Localidad</th>
                                    <th>Departamento</th>
                                    <th>Zona</th>
                                    <th>Zona Supervision</th>
                                    <th>Jornada</th>
                                    <th>Ámbito</th>
                                    <th>Acción</th>
                                    <th>Ultimo Acceso</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ($Escuelas as $nag)
                                        <tr data-id="{{$nag->idInstitucionExtension}}">
                                            <td>{{$nag->idInstitucionExtension}}</td>
                                            <td>{{$nag->CUECOMPLETO}}</td>
                                            <td>{{$nag->Descripcion}}</td>
                                            <td>
                                                @if(empty($nag->Nombre_Institucion))
                                                    <input type="text" name="Nombre_Institucion"><span style="color: red;">Completar</span>
                                                @else
                                                    <input type="text" name="Nombre_Institucion" value="{{$nag->Nombre_Institucion}}">
                                                @endif
                                            </td>
                                            <td>
                                                @if(empty($nag->Nivel))
                                                <span style="color: red;">Completar</span>
                                                @endif
                                                <select name="Nivel">
                                                    @foreach ($Niveles as $o)
                                                        @if ($o->NivelEnsenanza == $nag->Nivel)
                                                            <option value="{{$o->NivelEnsenanza}}" selected="selected">{{$o->NivelEnsenanza}}</option>
                                                        @else
                                                            <option value="{{$o->NivelEnsenanza}}">{{$o->NivelEnsenanza}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                
                                            </td>
                                            <td>
                                                @if(empty($nag->Categoria))
                                                <span style="color: red;">Completar</span>
                                                @endif
                                                <select name="Categoria">
                                                    @foreach ($Categorias as $o)
                                                        @if ($o->codigoCategoria == $nag->Categoria)
                                                            <option value="{{$o->codigoCategoria}}" selected="selected">{{$o->codigoCategoria}}</option>
                                                        @else
                                                            <option value="{{$o->codigoCategoria}}">{{$o->codigoCategoria}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                @if(empty($nag->Localidad))
                                                <span style="color: red;">Completar</span>
                                                @endif
                                                <select name="Localidad">
                                                    @foreach ($Localidades as $o)
                                                        @if ($o->localidad == $nag->Localidad)
                                                            <option value="{{$o->localidad}}" selected="selected">{{$o->localidad}}</option>
                                                        @else
                                                            <option value="{{$o->localidad}}">{{$o->localidad}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                @if(empty($nag->Departamento))
                                                <span style="color: red;">Completar</span>
                                                @endif
                                                <select name="Departamento">
                                                    @foreach ($Departamentos as $o)
                                                        @if ($o->nombre_dpto == $nag->Departamento)
                                                            <option value="{{$o->nombre_dpto}}" selected="selected">{{$o->nombre_dpto}}</option>
                                                        @else
                                                            <option value="{{$o->nombre_dpto}}">{{$o->nombre_dpto}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                @if(empty($nag->Zona))
                                                <span style="color: red;">Completar</span>
                                                @endif
                                                <select name="Zona">
                                                    @foreach ($Zonas as $o)
                                                        @if ($o->codigo_letra == $nag->Zona)
                                                            <option value="{{$o->codigo_letra}}" selected="selected">{{$o->codigo_letra}}</option>
                                                        @else
                                                            <option value="{{$o->codigo_letra}}">{{$o->codigo_letra}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                @if(empty($nag->ZonaSupervision))
                                                <span style="color: red;">Completar</span>
                                                @endif
                                                <select name="ZonaSupervision">
                                                    @foreach ($ZonasSup as $o)
                                                        @if ($o->idZonaSupervision == $nag->ZonaSupervision)
                                                            <option value="{{$o->idZonaSupervision}}" selected="selected">{{$o->Descripcion}}({{$o->Codigo}})</option>
                                                        @else
                                                            <option value="{{$o->idZonaSupervision}}">{{$o->Descripcion}}({{$o->Codigo}})</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                @if(empty($nag->Jornada))
                                                <span style="color: red;">Completar</span>
                                                @endif
                                                <select name="Jornada">
                                                    @foreach ($Jornadas as $o)
                                                        @if ($o->Descripcion == $nag->Jornada)
                                                            <option value="{{$o->Descripcion}}" selected="selected">{{$o->Descripcion}}</option>
                                                        @else
                                                            <option value="{{$o->Descripcion}}">{{$o->Descripcion}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                @if(empty($nag->Ambito))
                                                <span style="color: red;">Completar</span>
                                                @endif
                                                <select name="Ambito">
                                                    @foreach ($Ambitos as $o)
                                                        @if ($o->idAmbito == $nag->Ambito)
                                                            <option value="{{$o->idAmbito}}" selected="selected">{{$o->nombreAmbito}}</option>
                                                        @else
                                                            <option value="{{$o->idAmbito}}">{{$o->nombreAmbito}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                @php
                                                    $log = DB::table('tb_logs')
                                                    ->join('tb_usuarios', 'tb_usuarios.idUsuario', '=', 'tb_logs.idUsuario')
                                                    ->where('tb_usuarios.CUECOMPLETO', $nag->CUECOMPLETO)
                                                    ->where('tb_usuarios.Turno', $nag->idTurnoUsuario)
                                                    ->orderby('tb_logs.idLog', 'desc')
                                                    ->limit(1)
                                                    ->first();
                                                    //dd($log);
                                                    if($log){
                                                        echo "Ultimo Acceso: <b>".$log->updated_at."</b>";
                                                    }else{
                                                        echo "Ultimo Acceso: <b>NO CONTROLADO</b>";
                                                    }
                                                    
                                                @endphp
                                            </td>
                                            <td style="display: flex; justify-content: space-between;">
                                                <input type="hidden" name="idInstitucion" value="{{$nag->idInstitucionExtension}}">
                                                <button type="button" class="btnSubmit" data-id="{{$nag->idInstitucionExtension}}">
                                                    <i style="color:green;" class="fa fa-check"></i>
                                                </button>
                                                
                                                    <div style="display: flex; gap: 1rem; justify-content: space-between;margin-top:5px">
                                                        <div>
                                                            <a href="{{route('verPofMhidExt',$nag->idInstitucionExtension)}}">
                                                                <i class="fas fa-eye"></i>
                                                                <span>
                                                                @php
                                                                    $cantidad = DB::connection('DB7')->table('tb_pofmh')->where('CUECOMPLETO',$nag->CUECOMPLETO)->count();
                                                                    echo $cantidad;
                                                                @endphp
                                                                </span>
                                                                Ver Agentes
                                                            </a>
                                                        </div>
                                                        <div>
                                                            <a href="{{route('verCargosCreados',$nag->idInstitucionExtension)}}" class="nav-link">
                                                                <i class="fa fa-edit"></i>
                                                                Ver Pof
                                                              </a>
                                                        </div>
                                                    </div>
                                                
                                                
                                                
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                            </div>
                        </div>
                    </div>
                  
            </section>
            <!-- /.content -->
        </section>
    </section>
</section>
@endsection

@section('Script')
<script src="{{ asset('js/funcionesvarias.js') }}"></script>
@if (session('ConfirmarNuevoUsuario')=='OK')
    <script>
        Swal.fire(
            'Registro guardado',
            'Se creo un nuevo registro de un Usuario',
            'success'
        )
    </script>
@endif

<script>
$(document).ready(function() {
    $(document).on('click', '.btnSubmit', function() {
        var row = $(this).closest('tr');
        // Depuración para verificar los valores
        // Obtén el ID del botón clickeado
        var id = $(this).data('id');
        console.log("ID del botón:", id);

        // Encuentra la fila correspondiente usando el ID
        var row = $('tr[data-id="' + id + '"]');
        //console.log("Fila actual:", row.html()); // Muestra el HTML de la fila seleccionada

        if (row.length === 0) {
            console.error("No se encontró la fila con ID:", id);
            return;
        }
        console.log("Nombre Institución:", row.find('input[name="Nombre_Institucion"]').val());
        console.log("Nivel:", row.find('select[name="Nivel"]').val());
        console.log("Categoría:", row.find('select[name="Categoria"]').val());
        console.log("Localidad:", row.find('select[name="Localidad"]').val());
        console.log("Departamento:", row.find('select[name="Departamento"]').val());
        console.log("Zona:", row.find('select[name="Zona"]').val());
        console.log("Zona Supervision:", row.find('select[name="ZonaSupervision"]').val());
        console.log("Jornada:", row.find('select[name="Jornada"]').val());
        console.log("Ámbito:", row.find('select[name="Ambito"]').val());
        console.log("idInstitucion:", $(this).data('id'));
        var data = {
            "_token": "{{ csrf_token() }}",
            "idInstitucionExtension": $(this).data('id'),
            "Nombre_Institucion": row.find('input[name="Nombre_Institucion"]').val(),
            "Nivel": row.find('select[name="Nivel"]').val(),
            "Categoria": row.find('select[name="Categoria"]').val(),
            "Localidad": row.find('select[name="Localidad"]').val(),
            "Departamento": row.find('select[name="Departamento"]').val(),
            "Zona": row.find('select[name="Zona"]').val(),
            "ZonaSupervision": row.find('select[name="ZonaSupervision"]').val(),
            "Jornada": row.find('select[name="Jornada"]').val(),
            "Ambito": row.find('select[name="Ambito"]').val(),
            "idInstitucion": $(this).data('id'),
        };

        console.log("Datos a enviar:", data);

        Swal.fire({
            title: '¿Está seguro de editar la escuela?',
            text: "Este cambio no puede ser borrado luego, y deberá ser validado por RRHH!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, actualizar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/formActualizarEscTec',
                    type: "POST",
                    data: JSON.stringify(data), // Convierte el objeto a JSON
                    contentType: 'application/json; charset=utf-8',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content")
                    },
                    success: function(response) {
                        console.log("Respuesta exitosa:", response.msg);
                        Swal.fire({
                            title: 'Actualizado!',
                            text: 'La información de la escuela ha sido actualizada.',
                            icon: 'success'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Redirige a la URL deseada después de cerrar el mensaje
                                window.location.href = '/escuelasCargadasTecnico';
                            }
                        });
                    },
                    error: function(xhr) {
                        console.log("Error en la respuesta:", xhr);
                        Swal.fire(
                            'Error!',
                            'Hubo un problema al actualizar la información.',
                            'error'
                        );
                    }
                });
            }
        });
    });
});
</script>

<script>
$(function () {
    $('#tecnicoSage').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
    });
});
</script>
@endsection
