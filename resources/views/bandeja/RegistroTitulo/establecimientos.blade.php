@extends('layout.app')

@section('Titulo', 'Sage2.0 - Establecimientos')

@section('ContenidoPrincipal')
<section id="container" >
    <section id="main-content">
        <section class="content-wrapper">
            <!-- Mensaje ALERTA -->
            <div class="alert alert-warning alert-dismissible">
                <h4><i class="icon fas fa-exclamation-triangle"></i> AVISO!</h4>
                Aqui texto para cargar establecimientos, cualquier aviso importante colocar aquí<br>
                Ejemplo: <b>EPET N2, etc etc etc</b>
            </div>
            <!-- Inicio Selectores -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-lightblue">
                        <div class="card-header">
                            <h3 class="card-title">
                            <i class="fas fa-book"></i>
                            Panel de Control - Establecimientos
                            </h3>
                        </div>
                        <!-- /.card-header -->
                        <form method="POST" action="{{ route('formularioEstablecimientos') }}" class="formularioEstablecimientos">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="Descripcion">Descripción del Establecimiento</label>
                                    <input type="text" class="form-control" id="DescripcionEstablecimiento" name="DescripcionEstablecimiento" placeholder="Ingrese Descripción del Establecimiento" value="">
                                </div>
                            </div>
                            <div class="card-footer bg-transparent">
                                <button type="submit" class="btn btn-primary">Agregar</button>
                            </div>
                            
                        </form>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                    
                <!-- Inicio Tabla-Card -->
                <div class="col-md-6">
                    <div class="card card-lightblue">
                        <div class="card-header">
                            <h3 class="card-title">Lista de Establecimientos Cargados</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table id="example" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Descripción</th>
                                        <th>Estado</th>
                                        <th>Opción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($Establecimiento as $key => $o)
                                    <tr class="gradeX">
                                        <td>{{$o->idEstablecimiento}}</td>
                                        <td>{{$o->nombre_establecimiento}}</td>
                                        <td>
                                            @if ($o->estado_establecimiento==1)
                                            <small class="badge badge-success"><i class="far fa-clock"></i> Habilitado</small>
                                            @else
                                            <small class="badge badge-danger"><i class="far fa-clock"></i> Deshabilitado</small>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="row" style="display: flex;justify-content:center;gap: 10px">
                                                <a class="d-flex justify-content-center edit-btn" href="#" title="Editar" data-id="{{$o->idEstablecimiento}}" data-nombre="{{$o->nombre_establecimiento}}" data-estado="{{$o->estado_establecimiento}}" data-toggle="modal" data-target="#editModal">
                                                    <i class="fa fa-edit" style="color:blue"></i>
                                                </a>
                                                @php
                                                    //consulto para ver si esta ligado o no
                                                    $permiteBorrar1 = $permiteBorrar = DB::connection('DB2')
                                                                    ->table('tb_establecimientos')
                                                                    ->join('tb_registro_de_certificados', 'tb_registro_de_certificados.otorgado_por', '=', 'tb_establecimientos.nombre_establecimiento')
                                                                    ->where('idEstablecimiento', $o->idEstablecimiento)
                                                                    ->count();
                                                    $permiteBorrar2 = $permiteBorrar = DB::connection('DB2')
                                                                    ->table('tb_establecimientos')
                                                                    ->join('tb_registro_de_titulos', 'tb_registro_de_titulos.otorgado_por', '=', 'tb_establecimientos.nombre_establecimiento')
                                                                    ->where('idEstablecimiento', $o->idEstablecimiento)
                                                                    ->count();
                                                    $suma = $permiteBorrar1 + $permiteBorrar2;
                                                @endphp
                                                @if ($suma == 0)
                                                    <a class="eliminar-enlace d-flex justify-content-center" href="#" title="Eliminar" data-id="{{$o->idEstablecimiento}}">
                                                        <i class="fa fa-trash" style="color: red"></i>
                                                    </a>
                                                @endif
                                                
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                    
                                </tbody>
                            </table>
                            {{-- modal unico --}}
                            <!-- Modal -->
                            <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"     aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Editar Establecimiento</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    </div>
                                    <form id="editForm" action="{{route('formularioActualizarEstablecimiento')}}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="establecimientoId">ID</label>
                                                    <input type="text" class="form-control" id="establecimientoId" readonly>
                                                    <input type="hidden" id="establecimientoidEnv" name="establecimientoidEnv" value="">
                                                </div>
                                                <div class="form-group">
                                                    <label for="establecimientoNombre">Nombre del Establecimiento</label>
                                                    <input type="text" class="form-control" id="establecimientoNombre" name="Descripcion">
                                                </div>
                                                <div class="form-group">
                                                    <label for="Estado">Estado</label>
                                                    <select class="form-control" name="Estado" id="Estado">
                                                        @foreach($Estados as $key => $e)
                                                            <option value="{{$e->idEstado}}">{{$e->nombre_estado}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                
                                                <!-- Añadir más campos según sea necesario -->
                                            
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                            <button type="submit" class="btn btn-primary" id="saveChanges">Guardar cambios</button>
                                        </div>
                                    </form>
                                </div>
                                </div>
                            </div>
  
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            
        </section>
    </section>
</section>

@endsection

@section('Script')
    <script>
        var estados = {!! json_encode($Estados) !!};
        
        $(document).ready(function() {
            // Capturar el clic en el botón de edición
            $('.edit-btn').on('click', function() {
                // Obtener los datos del atributo data
                var id = $(this).data('id');
                var nombre = $(this).data('nombre');
                var estado = $(this).data('estado');
                

                // Cargar los datos en el modal
                $('#establecimientoId').val(id);
                $('#establecimientoidEnv').val(id);
                $('#establecimientoNombre').val(nombre);
                // Limpiar las opciones previas en el select del modal
                var select = $('#editModal #Estado');
                select.empty();

                // Reagregar las opciones dinámicamente y seleccionar la correcta
                estados.forEach(function(e) {
                    var option = $('<option>', {
                        value: e.idEstado,
                        text: e.nombre_estado
                    });
                  
                    if (parseInt(e.idEstado) === parseInt(estado)) {
                       
                        option.prop('selected', true);
                    }
                    select.append(option);
                });
                // Mostrar el modal (opcional, ya que el data-toggle="modal" se encargará de esto)
                $('#editModal').modal('show');
            });
        });

    </script>

    <script type="text/javascript" charset="utf-8">
        $(document).ready(function() {
            $('#example').dataTable( {
                "aaSorting": [[ 0, "asc" ]],
                "oLanguage": {
                    "sLengthMenu": "Escuelas _MENU_ por página",
                    "search": "Buscar:",
                    "oPaginate": {
                        "sPrevious": "Anterior",
                        "sNext": "Siguiente"
                    }
                }
            } );
        } );
  </script>


<script src="{{ asset('js/funcionesvarias.js') }}"></script>
        @if (session('ConfirmarNuevoEstablecimiento')=='OK')
            <script>
            Swal.fire(
                'Registro guardado',
                'Se cargo correctamente un nuevo Establecimiento',
                'success'
                    )
            </script>
        @endif
        @if (session('ConfirmarActualizarEstablecimiento')=='OK')
        <script>
        Swal.fire(
            'Registro Actualizado',
            'Se actualizo correctamente un Establecimiento',
            'success'
                )
        </script>
    @endif
    <script>

    $('.formularioEstablecimientos').submit(function(e){
        e.preventDefault();
        
        if(document.getElementById('DescripcionEstablecimiento').value === ""){
            Swal.fire(
                'Alerta',
                'Por favor, controlar campos vacíos',
                'error'
                    )
        }else{
            e.preventDefault();
            Swal.fire({
                title: 'Esta seguro de querer agregar un nuevo Establecimiento?',
                text: "Recuerde colocar datos verdaderos",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, guardo el registro!'
            }).then((result) => {
                if (result.isConfirmed) {
                this.submit();
                }
            })
        }
    })
    
    
</script>
 <script src="{{ asset('js/funcionesvarias.js') }}"></script>
        @if (session('ConfirmarBorradoEstablecimiento')=='OK')
            <script>
            Swal.fire(
                'Registro Eliminado Exitosamente',
                'Se desvinculo correctamente',
                'success'
                    )
            </script>
        @endif
        @if (session('ConfirmarBorradoEstablecimientoFallido')=='OK')
        <script>
        Swal.fire(
            'Error al borrar Registro',
            'No se puede borrar, debido a que esta vinculado a docente/s',
            'error'
                )
        </script>
    @endif

    <script>
        $(document).ready(function() {
            // Usa la delegación de eventos para manejar clics en enlaces con la clase 'eliminar-enlace'
            $(document).on('click', '.eliminar-enlace', function(e) {
                e.preventDefault(); // Evita que se siga el enlace automáticamente
                // Captura el ID del objeto
                var itemId = $(this).data('id');
                // Muestra la ventana de alerta
                Swal.fire({
                    title: '¿Está seguro de querer borrar este Item?',
                    text: 'Esta acción no se puede deshacer.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, confirmo eliminar el Item'
                }).then((result) => {
                    if (result.isConfirmed) {
                       // Si el usuario confirma, redirige a la URL deseada
                        var url = "{{ route('eliminarCertificado', ':id') }}";
                        url = url.replace(':id', itemId);
                        window.location.href = url;

                    }
                });
            });
        });
      </script>
      

@endsection