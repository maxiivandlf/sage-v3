@extends('layout.app')

@section('Titulo', 'Sage2.0 - Nuevo Agente')

@section('ContenidoPrincipal')

<section id="container">
    <section id="main-content">
        <section class="content-wrapper">
                <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- Buscador Agente -->
                    <h2 class="text-center display-4">Buscar Agente</h2>
                    <div class="row mb-4">
                        <div class="col-md-8 offset-md-2">
                            <div class="input-group m-3">
                                <label class="sr-only" for="buscarAgente">DNI del Agente</label>
                                <input type="text" class="form-control form-control-lg rounded border-0" id="buscarAgente"
                                    placeholder="Ingrese DNI sin Puntos" autocomplete="off">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-default btn-lg border"
                                        onclick="getNuevoAgenteDNI()"><i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                                <!-- general form elements -->
                        </div>
                    </div>
                    <!-- Fin Buscador Agente -->

                    <!-- Agregar Nuevo Agente -->
                    <div class="row d-flex justify-content-center">
                        <!-- left column -->
                        <div class="col-md-10">
                            <!-- general form elements -->
                            <div class="text-center alert alert-warning alert-dismissible">
                                <h6 class="font-italic">
                                    <i class="icon fas fa-exclamation-triangle"></i>   
                                    Este proceso sera validado por RRHH
                                </h6>
                            </div>
                            <div class="card card-lightblue">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        Agregar Nuevo Agente
                                    </h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                            
                                <form method="POST" action="{{ route('FormNuevoAgente') }}" class="formularioNuevoAgente form-group">
                                @csrf
                                    <div class="card-body" id="NuevoAgenteContenido1" style="display:none">
                                        <!-- Fila Tipo Documento y DNI -->
                                        <div class="form-group row">
                                            <div class="col-6">
                                                <label for="Apellido">Apellido: </label>
                                                <input type="text" autocomplete="off" class="form-control" id="Apellido" name="Apellido" placeholder="Ingrese apellido">
                                            </div>
                                            <div class="col-6">
                                                <label for="Nombre">Nombre: </label>
                                                <input type="text" autocomplete="off" class="form-control" id="Nombre" name="Nombre" placeholder="Ingrese nombre">
                                            </div>
                                            
                                            
                                        </div>

                                        <!-- Fila Apellido, Nombre y Sexo -->
                                        <div class="form-group row">
                                            <div class="col-6">
                                                <label for="Documento">Documento: </label>
                                                <input type="text" autocomplete="off" class="form-control" disabled id="Documento" placeholder="Ingrese número de documento">
                                                <input type="hidden" id="DH" name="Documento">
                                            </div>
                                            <div class="col-6">
                                                <label for="Sexo">Sexo: </label>
                                                <select class="form-control" name="Sexo" id="Sexo">
                                                    @foreach ($Sexos as $key => $o)
                                                        <option value="{{ $o->Mnemo }}">{{ $o->Descripcion }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Fila CUIL, Tipo de Agente -->
                                        <div class="form-group row">
                                            <div class="col-6">
                                                <label for="CUIL">CUIL: </label>
                                                <input type="text" autocomplete="off" class="form-control" id="CUIL" name="CUIL" placeholder="Ingrese número de cuil">
                                            </div>
                                            
                                        </div>

                                        
                                    </div>
                                    <!-- /.card-body -->

                                    <div class="card-footer bg-transparent" id="NuevoAgenteContenido2" style="display:none">
                                        <button type="submit" class="btn btn-primary">Agregar</button>
                                    </div>
                                </form>
                            </div>
                            <!-- /.card -->
                        </div>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
            <div class="row d-flex justify-content-center">
                <!-- left column -->
                <div class="col-md-10">
                    <div class="card card-lightblue">
                    <div class="card-header">
                        <h3 class="card-title">Agentes y No Agentes agregados por la Institución</h3>&nbsp; 
                        <span class="text-danger"><b>(Estos datos no se borran y quedan como registro)</b></span>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>COD</th>
                            <th>Apellido y Nombre</th>
                            <th>Documento</th>
                            <th>Zona</th>
                            <th>Localidad</th>
                            <th>Institución</th>
                        </tr>
                        </thead>
                        <tbody>
                             @foreach ($RelInstAgente as $nag)
                            <tr>
                                <td>{{$nag->idAgente}}</td>
                                <td>{{$nag->ApeNom}}</td>
                                <td>{{$nag->Documento}}</td>
                                <td>{{$nag->Zona}}</td>
                                <td>{{$nag->Localidad}}</td>
                                <td>{{$nag->Nombre_Institucion}}</td>
                            </tr> 
                            @endforeach
                         
                        
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
        @if (session('ConfirmarNuevoAgente')=='OK')
            <script>
            Swal.fire(
                'Registro guardado',
                'Se creo un nuevo registro de un Agente',
                'success'
                    )
            </script>
        @endif
        @if (session('ConfirmarNuevoAgenteExiste')=='OK')
            <script>
            Swal.fire(
                'Registro Fallido',
                'El Agente ya existe no puede volver a crearlo',
                'error'
                    )
            </script>
        @endif
    <script>



    $('.formularioNuevoAgente').submit(function(e){
        if($("#Apellido").val()=="" || $("#Nombre").val()=="" || $("#Documento").val()==""){
        console.log("error")
        e.preventDefault();
          Swal.fire(
            'Error',
            'No se pudo registrar, falta completar campos',
            'error'
                )
      }else{
        e.preventDefault();
        Swal.fire({
            title: 'Esta seguro de querer agregar el Agente?',
            text: "Este cambio no puede ser borrado luego, y debera ser validado por RRHH!",
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
        }
    })
    
</script>

@endsection
