@extends('layout.app')

@section('Titulo', 'Sage2.0 - Editar Usuario ADMIN')

@section('ContenidoPrincipal')

<section id="container">
    <section id="main-content">
        <section class="content-wrapper">
                <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- Buscador Agente -->
                    <h4 class="text-center display-4">Agregar CUE + Extensión a Usuario</h4>
                    <!-- Agregar Nuevo Agente -->
                    <div class="row d-flex justify-content-center">
                       
                        <div class="card card-info col-lg-12">
                            <div class="card-header">
                              <h3 class="card-title">Agregar CUE a un Usuario T&eacute;cnico</h3>
                            </div>
                            <form method="POST" action="{{ route('FormInsertarCUE') }}" class="FormInsertarCUE form-group">
                                @csrf
                                    <div class="card-body" id="NuevoAgenteContenido1" style="display:visible">
                                        <!-- Fila CUIL, Tipo de Agente -->
                                        <div class="form-group row">
                                            <div class="col-3">
                                                <label for="Usuario">CUE COMPLETO </label>
                                                <input type="text" autocomplete="off" class="form-control" id="CUECOMPLETO" name="CUECOMPLETO" placeholder="Ingrese CUE+Extension" value="">
                                            </div>
                                            <div class="col-3">
                                                <label for="Clave">Cantidad de Personas en POF:</label>
                                                <input type="text" autocomplete="off" class="form-control" id="CantidadPersonas" name="CantidadPersonas" placeholder="Ingrese cantidad de personas en POF" value="">
                                                
                                            </div>
                                            <div class="col-3">
                                                <label for="Clave">&nbsp;</label>
                                                <button type="submit" class="btn btn-primary btn-block bg-success">Agregar CUE</button>
                                            </div>
                                    </div>
                                    <!-- /.card-body -->
                                    <input type="hidden" name="usuario" value="{{$Usuario[0]->idUsuario}}"/>
                                    
                                </form>
                            <!-- /.card-body -->
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <!-- Inicio Tabla-Card -->
                                
                                <div class="card card-lightblue">
                                    <div class="card-header ">
                                        
                                        <h3 class="card-title">Novedades - por DNI o por Nombre</h3>
                                    </div>
                                
                                        <!-- /.card-header -->
                                        <div class="card-body">
                                            <table id="example" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th colspan="6" style="text-align:center">Asignados</th>
                                                        <th colspan="4" style="text-align:center">Progreso</th>
                                                    </tr>
                                                    <tr>
                                                        <th rowspan="1" style="text-align:center">ID</th>
                                                        <th rowspan="1" style="text-align:center">CUECOMPLETO</th>
                                                        <th rowspan="1" style="text-align:center">Fecha de Inicio</th>
                                                        <th rowspan="1" style="text-align:center">Fecha de Fin</th>
                                                        <th rowspan="1" style="text-align:center">Estado</th>
                                                        <th rowspan="1" style="text-align:center">Cantidad de Personas en POF</th>  
                                                        <th rowspan="1" style="text-align:center" >%</th>    
                                                    </tr>
                                                </thead>
                                                 <tbody>
                                                    @php
                                                    //dd($indoDesglose);
                                                    @endphp
                                                @foreach($infoCUEAgregada as $key => $n)
                                                        <tr class="gradeX">
                                                            <td align="center">{{$n->id_rel_admin_institucion_extension}}</td>
                                                            <td align="center">{{$n->CUECOMPLETO}}</td>
                                                            <td align="center">{{$n->FechaInicio}}</td>
                                                            <td align="center">{{$n->FechaFin}}</td>
                                                            <td align="center">
                                                                @foreach ($EstadoPOF as $e)
                                                                    @if ($e->idEstadoPOF == $n->EstadoPOF)
                                                                        {{$e->Descripcion_POF}}
                                                                    @endif
                                                                @endforeach    
                                                            </td>
                                                            <td align="center">{{$n->CantidadSubidos}}</td>
                                                            <td></td>
                                                            <td class="project_progress">
                                                                @php
                                                                    //obtengo la cantidad de esa pof por nodos
                                                                    $infoNodosCUE = DB::table('tb_nodos')->where('CUECOMPLETO',$n->CUECOMPLETO)->count();
                                                                    $MaximoValor = $n->CantidadSubidos;
                                                                    // Calcula el porcentaje
                                                                    $porcentaje = intval(($infoNodosCUE / $MaximoValor) * 100);
                                                                    
                                                                @endphp
                                                                <div class="progress progress-sm">
                                                                    <div class="progress-bar bg-green" role="progressbar" aria-valuenow="{{$porcentaje}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$porcentaje}}%">
                                                                    </div>
                                                                </div>
                                                                <small>

                                                                    {{$porcentaje}}% completado
                                                                </small>
                                                            </td>
                                                            <td class="project-state">
                                                                <span class="badge badge-success">Success</span>
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

                    <!-- /.row -->
                </div><!-- /.container-fluid -->
         

                
            </section>
            <!-- /.content -->
        </section>
    </section>
</section>
@endsection

@section('Script')
    <script src="{{ asset('js/funcionesvarias.js') }}"></script>
        @if (session('usuario')=='OK')
            <script>
            Swal.fire(
                'Registro Insertado',
                'Se agrego un CUE a un Usuario',
                'success'
                    )
            </script>
        @endif
    <script>

    $('.FormInsertarCUE').submit(function(e){
        e.preventDefault();
        Swal.fire({
            title: '¿Está seguro de querer entrar el CUE al usuario?',
            text: "Este cambio sólo será borrado por el admin",
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
