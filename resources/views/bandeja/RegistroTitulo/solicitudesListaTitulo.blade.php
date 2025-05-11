@extends('layout.app')

@section('Titulo', 'Sage2.0 - Certificados')

@section('ContenidoPrincipal')
<section id="container" >
    <section id="main-content">
        <section class="content-wrapper">
            <!-- Mensaje ALERTA -->
            <div class="alert alert-warning alert-dismissible">
                <h4><i class="icon fas fa-exclamation-triangle"></i> AVISO!</h4>
                En esta pantalla se visualizaran todos los CERTIFICADOS / TITULOS que son generados<br>
            </div>
            <!-- Inicio Selectores -->
            <div class="row">
                <!-- Inicio Tabla-Card -->
                <div class="col-md-12">
                    <div class="card card-lightblue">
                        <div class="card-header">
                            <h3 class="card-title">Lista de Títulos Cargados</h3>
                        </div>
                        @php
                            use Illuminate\Support\Facades\DB;
                    
                            $Registro_Titulo = DB::connection('DB2')->table('tb_registro_de_titulos')
                            ->join('tb_agentes','tb_agentes.dni','tb_registro_de_titulos.dni')
                            ->orderby('tb_registro_de_titulos.created_at','desc')
                            ->get();
                        @endphp
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table id="titulosTab" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th colspan="6" style="text-align: center">DOCUMENTO SOLICITADO: <b>TITULO</b></th>
                                        <th colspan="7" style="text-align: center;">DATOS DEL AGENTE</th>
                                        <th colspan="2">Opciones</th>
                                    </tr>
                                    <tr>
                                        <th>Registro</th>
                                        <th>Descripción</th>
                                        <th>Otorgado</th>
                                        <th>Fecha Titulo</th>
                                        <th>Fecha Registro</th>
                                        <th>Fecha Egreso</th>
                                        <th>Agente</th>
                                        <th>Dni</th>
                                        <th>Nacionalidad</th>
                                        <th>Provincia</th>
                                        <th>Localidad</th>
                                        <th>N° Tel/Cel</th>
                                        <th>Correo</th>
                                        <th>-</th>
                                        <th>-</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($Registro_Titulo as $key => $o)
                                    <tr class="gradeX">
                                        <td>{{$o->idRegistroTitulo}}</td>
                                        <td>{{$o->nombre_titulo}}</td>
                                        <td>{{$o->otorgado_por}}</td>
                                        <td>{{ \Carbon\Carbon::parse($o->fecha_de_titulo)->format('d-m-Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($o->fecha_de_registro)->format('d-m-Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($o->fecha_de_egreso)->format('d-m-Yc') }}</td>
                                        <td>{{$o->apellido_nombre}}</td>
                                        <td>{{$o->dni}}</td>
                                        <td>{{$o->nacionalidad}}</td>
                                        <td>{{$o->provincia}}</td>
                                        <td>{{$o->localidad}}</td>
                                        <td>{{$o->numero_telefono}}</td>
                                        <td>{{$o->correo}}</td>
                                        <td>{{$o->localidad}}</td>
                                        <td style="display: flex">
                                            @if ($o->URL_doc != "")
                                                <a target="_blank" class="d-flex justify-content-center" href="{{ asset('storage/TITCERT/' . $o->URL_doc) }}" title="Download" data-id="{{$o->idRegistroTitulo}}">
                                                    <i class="fa fa-download" style="color: green"></i>
                                                </a>
                                            @endif
                                            @if ($o->URL_titulo_Online != "")
                                                |  <a target="_blank" class="d-flex justify-content-center" href="{{ $o->URL_titulo_Online }}" title="ver Titulo Digital">
                                                    <i class="fa fa-eye" style="color: green"></i>
                                                </a>
                                            @endif
                                           
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
            </div>
        </section>
    </section>
</section>

@endsection

@section('Script')
<script type="text/javascript">
    $(document).ready(function() {
        // Configuración para la tabla de títulos
        $("#titulosTab").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "order": [[0, 'desc']],
            "dom": 'Bfrtip', // Habilitar botones
            "buttons": [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });

        // Configuración para la tabla de certificados
        $("#certificadosTab").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "order": [[0, 'desc']],
            "dom": 'Bfrtip', // Habilitar botones
            "buttons": [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
    });
</script>
@endsection
