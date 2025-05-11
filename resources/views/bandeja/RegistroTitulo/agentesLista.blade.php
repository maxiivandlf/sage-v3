@extends('layout.app')

@section('Titulo', 'Sage2.0 - Certificados')

@section('ContenidoPrincipal')
<section id="container" >
    <section id="main-content">
        <section class="content-wrapper">
            <!-- Mensaje ALERTA -->
            <div class="alert alert-warning alert-dismissible">
                <h4><i class="icon fas fa-exclamation-triangle"></i> AVISO!</h4>
                Aqui texto para.......<br>
                Ejemplo: <b>Polimodal etc etc etc</b>
            </div>
            <!-- Inicio Selectores -->
            <div class="row">
                <!-- Inicio Tabla-Card -->
                <div class="col-md-12">
                    <div class="card card-lightblue">
                        <div class="card-header">
                            <h3 class="card-title">Lista de Agentes Cargados</h3>
                        </div>
                        @php
                            use Carbon\carbon;
                        @endphp
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table id="example" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Apellido y Nombre</th>
                                        <th>DNI</th>
                                        <th>Correo</th>
                                        <th>Fecha Nac</th>
                                        <th>Provincia/Ciudad Nac</th>
                                        <th>Teléfono</th>
                                        {{-- <th>Nacionalidad</th> --}}
                                        <th>Localidad</th>
                                        <th>Opción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($Agentes as $key => $o)
                                    <tr class="gradeX">
                                        <td>{{$o->idAgente}}</td>
                                        <td>{{$o->apellido_nombre}}</td>
                                        <td>{{$o->dni}}</td>
                                        <td style="text-align: center">
                                            @if ($o->correo)
                                                <span style="color:green">{{$o->correo}}</span>
                                            @else
                                                <span style="color:red">COMPLETAR</span>
                                            @endif
                                        </td>
                                        <td>{{Carbon::parse($o->fecha_de_nacimiento)->format("d/m/Y")}}</td>
                                        <td>{{$o->provincia}} / {{$o->lugar_de_nacimiento}}</td>
                                        <td>{{$o->numero_telefono}}</td>
                                        {{-- <td>{{$o->nacionalidad}}</td> --}}
                                        <td>{{$o->localidad}}</td>
                                        
                                        <td style="display: flex">
                                            <a style="margin-right: 5px" class="" href="{{route('editarAgenteTitulo',$o->idAgente)}}" title="Editar" data-id="{{$o->idAgente}}">
                                                <i class="fa fa-edit" style="color: green"></i>
                                            </a> |
                                            <a style="margin-right: 5px"  class="agregar-enlace" href="{{route('agregarTituloyCertificado',$o->idAgente)}}" title="Titulo y Certificado" data-id="{{$o->idAgente}}">
                                                <i class="fa fa-book" style="color: green"></i>
                                            </a>
                                            |
                                            <a style="margin-right: 5px"  class="agregar-enlace" href="{{route('agregarDocAgenteTitulo',$o->idAgente)}}" title="Titulo y Certificado" data-id="{{$o->idAgente}}">
                                                <i class="fa fa-upload" style="color: green"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    
                                </tbody>
                            </table>
                            
  
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
        @if (session('ConfirmarNuevoCertificado')=='OK')
            <script>
            Swal.fire(
                'Registro guardado',
                'Se cargo correctamente un nuevo Certificado',
                'success'
                    )
            </script>
        @endif
        @if (session('ConfirmarActualizarCertificado')=='OK')
        <script>
        Swal.fire(
            'Registro Actualizado',
            'Se actualizo correctamente un Certificado',
            'success'
                )
        </script>
    @endif
    <script>

    $('.formularioCertificados').submit(function(e){
        e.preventDefault();
        
        if(document.getElementById('DescripcionCertificado').value === ""){
            Swal.fire(
                'Alerta',
                'Por favor, controlar campos vacíos',
                'error'
                    )
        }else{
            e.preventDefault();
            Swal.fire({
                title: 'Esta seguro de querer agregar un Certificado?',
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
        @if (session('ConfirmarBorradoCertificado')=='OK')
            <script>
            Swal.fire(
                'Registro Eliminado Exitosamente',
                'Se desvinculo correctamente',
                'success'
                    )
            </script>
        @endif
        @if (session('ConfirmarBorradoCertificadoFallido')=='OK')
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
            $(document).on('click', '.editar-enlace', function(e) {
                e.preventDefault(); // Evita que se siga el enlace automáticamente
                // Captura el ID del objeto
                var itemId = $(this).data('id');
                // Muestra la ventana de alerta
                Swal.fire({
                    title: '¿Está seguro de querer editar este Item?',
                    text: 'Esta acción no se puede deshacer.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, confirmo editar el Item'
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