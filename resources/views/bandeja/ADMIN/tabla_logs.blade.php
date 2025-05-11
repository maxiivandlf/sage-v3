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
                    <h4 class="text-center display-4">Panel de Seguimiento de Accesos</h4>
                    <!-- Agregar Nuevo Agente -->
                   
                    <div class="row d-flex justify-content-center">
                        <!-- left column -->
                        <div class="col-md-10">
                            <div class="card card-lightblue">
                            <div class="card-header">
                                <h3 class="card-title">Lista de Logs</h3>&nbsp; 
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>COD</th>
                                    <th>CUECOMPLETO</th>
                                    <th>Institucion</th>
                                    <th>Nivel</th>
                                    <th>Turno</th>
                                    <th>Nombre de Usuario</th>
                                    <th>Rol</th>
                                    <th>Correo Electrónico</th>
                                    <th>Fecha de Creación</th>
                                    <th>Fecha Ultimo Acceso</th>
                                    <th>Dias entre Accesos</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ($Logs as $nag)
                                    <tr>
                                        <td>{{$nag->idLog}}</td>
                                        <td  style="text-align: center">{{$nag->CUECOMPLETO}}</td>
                                        <td  style="text-align: center">{{$nag->Nombre_Institucion}}</td>
                                        <td  style="text-align: center">{{$nag->Nivel}}</td>
                                        <td>{{$nag->Descripcion}}</td>
                                        <td>{{$nag->Nombre}}</td>
                                        <td>{{$nag->Modo}}</td>
                                        <td>{{$nag->email}}</td>
                                        <td style="background-color:rgb(223, 186, 173);font-weight:bold">{{$nag->FechaCreacion}}</td>
                                        <td style="background-color:rgb(206, 245, 209);font-weight:bold">{{$nag->FechaUltimoAcceso}}</td>
                                        <td style="text-align: center">
                                            @php
                                                $logs = DB::table('tb_logs')
                                                            ->where('idUsuario', $nag->UsuarioInfo)
                                                            ->orderBy('created_at', 'desc')
                                                            ->take(2)
                                                            ->get();

                                                if ($logs->count() == 2) {
                                                    $ultimoRegistro = \Carbon\Carbon::parse($logs[0]->created_at);
                                                    $penultimoRegistro = \Carbon\Carbon::parse($logs[1]->created_at);
                                                    $diferenciaDias = $ultimoRegistro->diffInDays($penultimoRegistro);
                                                } else {
                                                    // Manejar el caso donde hay menos de dos registros
                                                    $diferenciaDias = 0;
                                                }

                                                echo $diferenciaDias;
                                            @endphp
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

    $('.formularioNuevoUsuario').submit(function(e){
        e.preventDefault();
        Swal.fire({
            title: '¿Está seguro de querer agregar el Usuario?',
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
