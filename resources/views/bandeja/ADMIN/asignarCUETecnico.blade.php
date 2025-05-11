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
                    <h4 class="text-center display-4">Panel de Asignar Escuelas a Técnicos</h4>
                    <!-- Agregar Nuevo Agente -->
                   
                    <div class="row d-flex justify-content-center">
                        <!-- left column -->
                        <div class="col-md-6">
                            <div class="card card-lightblue">
                            <div class="card-header">
                                <h3 class="card-title">Lista de Escuelas no Asignadas</h3>&nbsp; 
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>COD</th>
                                    <th>CUE</th>
                                    <th>Nombre</th>
                                    <th>Técnico?</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ($EscuelasS as $nag)
                                    <tr>
                                        <td>{{$nag->idInstitucionExtension}}</td>
                                        <td>
                                            CUE: <b>{{$nag->CUECOMPLETO}}</b><br>
                                            Nivel: <b>{{$nag->Nivel}}</b> <br>
                                            Turno: <b>{{$nag->Descripcion}}</b><br>
                                        </td>
                                        <td>{{$nag->Nombre_Institucion}}</td>
                                        <td>
                                            <form method="POST" action="{{ route('formAsignarTecnico') }}" class="formAsignarTecnico form-group">
                                                @csrf
                                                <div style="display: flex">
                                                    <select name="usuario" class="form-control">
                                                        @foreach ($Tecnicos as  $o)
                                                            <option value="{{$o->idUsuario}}">{{$o->Nombre}}</option>        
                                                        @endforeach
                                                    </select>
                                                    <input type="hidden" name="institucion" value="{{$nag->idInstitucionExtension}}">

                                                    <button style="margin-left: 5px;border:none;" type="submit" name="btn"><i style="color:green" class="fa fa-check"></i></button>
                                                </div>
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
                        <!-- rigth column -->
                        <div class="col-md-6">
                            <div class="card card-lightblue">
                            <div class="card-header">
                                <h3 class="card-title">Lista de Escuelas no Asignadas</h3>&nbsp; 
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="asignado" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>COD</th>
                                    <th>CUE</th>
                                    <th>Nombre</th>
                                    <th>Técnico?</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ($EscuelasC as $nag)
                                    <tr>
                                        <td>{{$nag->idInstitucionExtension}}</td>
                                        <td>
                                            CUE: <b>{{$nag->CUECOMPLETO}}</b><br>
                                            Nivel: <b>{{$nag->Nivel}}</b> <br>
                                            Turno: <b>{{$nag->Descripcion}}</b><br>
                                        </td>
                                        <td>{{$nag->Nombre_Institucion}}</td>
                                        <td>
                                            <form method="POST" action="{{ route('FormQuitarAsignacion') }}" class="FormQuitarAsignacion form-group">
                                                @csrf
                                                <div style="display: flex">
                                                    <label>Asignado a: </label> 
                                                        @foreach ($Tecnicos as  $o)
                                                            @if ($o->idUsuario == $nag->idTecnicoSage)
                                                                <span style="color:darkgreen">{{$o->Nombre}}</span>     
                                                            @endif
                                                        @endforeach
                                                    <input type="hidden" name="institucion" value="{{$nag->idInstitucionExtension}}">
                                                    <button title="QUITAR" style="margin-left: 5px;border:none;" type="submit" name="btn"><i style="color:red" class="fa fa-trash"></i></button>
                                                </div>
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
<script>
    $(function () {
      $("#asignado").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false,
        //"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)')
      $('#noasignado').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
      })
      
    })
  </script>
    <script src="{{ asset('js/funcionesvarias.js') }}"></script>
        @if (session('ConfirmarUbicacionUsuario')=='OK')
            <script>
            Swal.fire(
                'Registro guardado',
                'Se Asigno un CUE al Usuario y se mostrara en la tabla de la derecha',
                'success'
                    )
            </script>
        @endif
        @if (session('ConfirmarUbicacionQuitarUsuario')=='OK')
            <script>
            Swal.fire(
                'Registro guardado',
                'Se quito un CUE al Usuario y se regresa a la tabla izquierda',
                'success'
                    )
            </script>
        @endif
    <script>
    $('.formAsignarTecnico').submit(function(e){
        e.preventDefault();
        Swal.fire({
            title: '¿Está seguro de querer asignar el técnico?',
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

    $('.FormQuitarAsignacion').submit(function(e){
        e.preventDefault();
        Swal.fire({
            title: '¿Está seguro de querer quitar el Usuario del CUE?',
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
