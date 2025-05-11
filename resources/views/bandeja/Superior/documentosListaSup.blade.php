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
                    <h4 class="text-center display-4">Panel de Títulos y Certificados Exportados para Control</h4>
                    <!-- Agregar Nuevo Agente -->
                   
                    <div class="row d-flex justify-content-center">
                        <!-- left column -->
                        <div class="col-md-10">
                            <div class="card card-lightblue">
                            <div class="card-header">
                                <h3 class="card-title">Lista de Recuperados</h3>&nbsp; 
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>COD</th>
                                    <th>Apellido y Nombre</th>
                                
                                    <th>Documento</th>
                                    <th>titulo</th>
                                    <th>Legajo</th>
                                    <th>Estado</th>
                                  
                                    <th>Habilitado?</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ($DocumentosLista as $nag)
                                        
                                            <tr>
                                                <td>{{$nag->idtitulaciones_exportadas}}</td>
                                                <td>{{$nag->ApeNom}}</td>
                                                
                                                <td>{{$nag->Documento}}</td>
                                                <td>{{$nag->titulo}}</td>
                                                
                                                <td>{{$nag->legajo}}</td>
                                                <td>{{$nag->estado}}</td>
                            
                                                <td style="display: flex; flex-wrap:wrap;">
                                                   
                                                    {{-- <a href="{{route('agregarCUEUsuario',$nag->idAgentes_exportados)}}" title="Agregar CUE a Usuario">
                                                        <i class="fa fa-sitemap"></i>
                                                    </a>
                                                
                                                    <a>
                                                        <i class="fa fa-eraser" style="color:red"></i>
                                                    </a> --}}
                                                    <form method="POST" action="{{ route('FormRegistrarDocumentoSup') }}" class="FormRegistrarDocumentoSup">
                                                        @csrf
                                                        <input type="hidden" name="u" value="{{$nag->Documento}}">
                                                        <input type="submit" name="insertar" value="insertar">
                                                    </form>
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

    $('.FormRegistrarDocumentoSup').submit(function(e){
        e.preventDefault();
        Swal.fire({
            title: '¿Está seguro de querer agregar el Titulo?',
            text: "Este cambio no puede ser borrado luego, y deberá ser validado por RRHH!",
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
