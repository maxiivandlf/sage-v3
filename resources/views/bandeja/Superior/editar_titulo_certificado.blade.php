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
                    <h4 class="text-center display-4">Panel de Documentos Registrados para el Agente</h4>
                    <!-- Agregar Nuevo Agente -->
                   
                    <div class="row d-flex justify-content-center">
                        <!-- left column -->
                        <div class="col-md-10">
                            <div class="card card-lightblue">
                            <div class="card-header">
                                <h3 class="card-title">Lista de Títulos y Certificados Exportados</h3>&nbsp; 
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>COD</th>
                                    <th>titulo</th>
                                    <th>Habilitado?</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ($Titulos as $nag)
                                        
                                            <tr>
                                                <td>{{$nag->idtitulaciones_exportadas}}</td>
                                                <td>{{$nag->titulo}}</td>
                                                <td style="display: flex; flex-wrap:wrap;">
                                                   
                                                @php
                                                //voy a verificar que no exista
                                                    $cantidad = DB::connection('DB4')->table('tb_titulos_cursos')
                                                    ->where('nombre_titulo', $nag->titulo)
                                                    ->count();

                                                @endphp
                                                @if ($cantidad==0)
                                                    <form method="POST" action="{{ route('FormRegistrarDocumentoSup') }}" class="FormRegistrarDocumentoSup">
                                                        @csrf
                                                        <input type="hidden" name="u" value="{{$nag->idtitulaciones_exportadas}}">
                                                        <input type="submit" name="Agregar" value="Agregar">
                                                    </form>
                                                @endif
                                                   
                                                    
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
                    
                    <div class="row d-flex justify-content-center">
                        <!-- left column -->
                        <div class="col-md-10">
                            <div class="card card-green">
                            <div class="card-header">
                                <h3 class="card-title">Lista de Títulos y Certificados Reconocidos</h3>&nbsp; 
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>COD</th>
                                    <th>titulo</th>
                                    <th>Habilitado?</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ($TitulosReg as $nag)
                                        
                                            <tr>
                                                <td>{{$nag->idTitulo_curso}}</td>
                                                <td>{{$nag->nombre_titulo}}</td>
                                                <td style="display: flex; flex-wrap:wrap;">
                                                   
                                
                                                    <a href="{{route('editarTituloSup',$nag->idTitulo_curso)}}" title="Editar Titulo Superior">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    
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
                    <div class="row d-flex justify-content-center">
                        <!-- left column -->
                        <div class="col-md-10">
                            <form method="POST" action="{{ route('registrarTituloSuperior') }}" class="registrarTituloSuperior">
                                @csrf
                                <div class="card card-yellow">
                                    <div class="card-header">
                                        <h3 class="card-title">Agregar Titulo</h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                    
                                            
                                            <div class="col-6">
                                                <label for="titulo">Titulo/Certificado: </label>
                                                <input type="text" autocomplete="off" class="form-control" id="titulo" name="titulo" placeholder="Ingrese titulo" value="" required>
                                                <input type="hidden" name="user" value="{{$Usuario[0]->idAgente}}">
                                                <input type="hidden" name="doc" value="{{$Usuario[0]->Documento}}">
                                            </div>
                                        
                                    </div>
                                
                                    <!-- /.card-body -->
                                    <div class="card-footer bg-transparent" id="NuevoAgenteContenido2" style="display:visible">
                                        <button type="submit" class="btn btn-primary btn-block bg-success">Agregar Titulo</button>
                                    </div>
                                </div>
                            </form>
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
        @if (session('ConfirmarNuevoTituloRegistrado')=='OK')
            <script>
            Swal.fire(
                'Registro guardado',
                'Se creo un nuevo registro de un Titulo Nuevo',
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
    
    $('.registrarTituloSuperior').submit(function(e){
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
