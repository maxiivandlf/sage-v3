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
                    <h2 class="text-center display-4">Agentes cargados en la Institución</h2>
                    <div class="row d-flex justify-content-center">
                        <!-- left column -->
                        <div class="col-md-10">
                            <div class="card card-lightblue">
                            <div class="card-header">
                                <h3 class="card-title">Información de los Agentes y No Agentes agregados por la Institución</h3> 
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Documento</th>
                                    <th>Cuil</th>
                                    <th>Apellido y Nombre</th>
                                    <th>Sexo</th>
                                    <th>Barrio</th>
                                    <th>Calle</th>
                                    <th>Numero Casa</th>
                                    <th>Piso</th>
                                    <th>Numero Dpto.</th>
                                    <th>Código Postal</th>
                                    <th>Localidad</th>
                                    <th>Departamento</th>
                                    <th>Opciones</th>

                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ListaAgentes as $ag)
                                    <tr>
                                        <td>{{$ag->Documento}}</td>
                                        <td>{{$ag->Cuil}}</td>
                                        <td>{{$ag->ApeNom}}</td>
                                        <td>{{$ag->Sexo}}</td>
                                        <td>{{$ag->Barrio}}</td>
                                        <td>{{$ag->Calle}}</td>
                            
                                        <td>{{$ag->Numero_Casa}}</td>
                                        <td>{{$ag->Piso}}</td>
                                        <td>{{$ag->Numero_Dpto}}</td>
                                        
                                        @php
                                            $info = DB::table('tb_localidades')
                                            ->join('tb_departamentos','tb_departamentos.idDepartamento','=','tb_localidades.Departamento')
                                            
                                            ->where('idLocalidad',$ag->Localidad)->first();
                                            //dd($ag->Localidad);
                                        @endphp
                                        @if ($info)
                                            <td>{{$info->CodigoPostal}}</td>
                                            <td>{{$info->localidad}}</td>
                                            <td>{{$info->nombre_dpto}}</td>
                                            
                                        @else
                                            <td> -- </td>
                                            <td> -- </td>
                                            <td> -- </td>
                                            
                                        @endif
                                        
                                        <td>
                                            <a href="{{route('editarAgente',$ag->idAgente)}}" title="Editar Agente">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr> 
                                    @endforeach
                                
                                
                                </table>
                            </div>
                            <!-- /.card-body -->
                            </div>
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
