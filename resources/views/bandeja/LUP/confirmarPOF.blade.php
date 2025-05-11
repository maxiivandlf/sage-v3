@extends('layout.app')

@section('Titulo', 'Sage2.0 - Divisiones')

@section('ContenidoPrincipal')
<section id="container" >
    <section id="main-content">
        <section class="content-wrapper">
            <!-- Mensaje ALERTA -->
            <div class="alert alert-warning alert-dismissible">
                <h4><i class="icon fas fa-exclamation-triangle"></i> AVISO!</h4>
                En esta sección se darán de alta novedades individuales, se irán agregando según la necesidad</b>
            </div>
            <!-- Inicio Selectores -->
            <div class="row" style="text-align: ce">
                <div class="card-body text-center" style="width: 50%; margin: 0 auto;">
                    <form method="POST" action="{{ route('generarPOF') }}" class="generarPOF">
                        @csrf
                        <div class="col-md-3 col-sm-6">
                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="far fa-thumbs-up"></i></span>
                    
                                <div class="info-box-content">
                                    <span class="info-box-text">Confirmar POF</span>
                                    <span class="info-box-number">Fecha: <?php echo date('d-m-Y H:i:s');?></span>

                    
                                    
                                <button class="btn btn-warning" type="submit">
                                    <span class="progress-description">
                                        Haga Click para Confirmar
                                    </span>
                                </button>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">       
                <!-- Inicio Tabla-Card -->
                <div class="col-md-6">
                    <div class="card card-lightblue">
                        <div class="card-header">
                            <h3 class="card-title">Lista de POF generadas</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table id="example" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Fecha de Presentación</th>
                                        <th>Archivo</th>
                                        <th>Opción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($pofGeneradas as $key => $o)
                                        <tr class="gradeX">
                                            <td>{{$o->idPofGenerada}}</td>
                                            <td>{{$o->FechaAlta}}</td>
                                            <td>{{$o->URL}}</td>
                                            <td>
                                                <a class="d-flex justify-content-center" href="{{ asset('storage/POF/' . $o->URL) }}">
                                                    <i class="fa fa-download"></i>
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
                "aaSorting": [[ 1, "asc" ]],
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
        @if (session('ConfirmarActualizarDivisiones')=='OK')
            <script>
            Swal.fire(
                'Registro guardado',
                'Se actualizó correctamente',
                'success'
                    )
            </script>
        @endif
    <script>

    $('.formularioDivisiones').submit(function(e){
        e.preventDefault();
        Swal.fire({
            title: '¿Está seguro de querer agregar una División a su Institución?',
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
    })

    $('.generarPOF').submit(function(e){
        e.preventDefault();
        Swal.fire({
            title: '¿Está seguro de confirmar su planta organica?',
            text: "Este dato será chequeado mensualmente por RRHH",
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
    })
    
</script>
 <script src="{{ asset('js/funcionesvarias.js') }}"></script>
        @if (session('ConfirmarEliminarDivision')=='OK')
            <script>
            Swal.fire(
                'Registro Eliminado Exitosamente',
                'Se desvinculó correctamente',
                'success'
                    )
            </script>
        @endif
        @if (session('ConfirmarEliminarDivisionFallida')=='OK')
        <script>
        Swal.fire(
            'Error al borrar Registro',
            'No se puede borrar, debido a que esta vinculado a docente/s',
            'error'
                )
        </script>
    @endif



@endsection