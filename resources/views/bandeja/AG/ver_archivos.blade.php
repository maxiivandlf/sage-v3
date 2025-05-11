@extends('layout.app')

@section('Titulo', $mensajeNAV)

@section('ContenidoPrincipal')
<section id="container" >
    <section id="main-content">
        <section class="content-wrapper">
            <!-- Inicio Selectores -->
            <div class="row">
                <div class="col-md-12">
                    <!-- Inicio Tabla-Card -->
                    <div class="alert alert-warning alert-dismissible">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Importante!</h5>
                        Aquí aparecen todos los documentos generados para el cue {{$CUE}} y se incluyen todos los archivos bajo el CUE declarado, sin importar
                    los turnos.</b>
                    </div>
                    <div class="card card-lightblue">
                        <div class="card-header ">
                            
                            <h3 class="card-title">Listas de Archivos Subidos - CUE: {{$CUE}} - Todos los TURNOS</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>CUE</th>
                                        <th>Agente</th>
                                        <th>DNI</th>
                                        <th>Fecha de Alta</th>
                                        <th>URL</th>
                                        <th>Acción</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($Documentos && $Documentos->count() > 0) <!-- Cambiado de ! a solo $Documentos -->
                                    @foreach($Documentos as $key => $n)
                                        <tr class="gradeX">
                                            <td>{{$n->CUECOMPLETO}}</td>
                                            <td>{{$n->ApeNom}}</td>
                                            <td>{{$n->Agente}}</td>
                                            <td>{{ \Carbon\Carbon::parse($n->FechaAlta)->format('d-m-Y (H:i:s)') }}</td>
                                            <td style="text-align: center">{{$n->URL}}</td>
                                            <td style="text-align: center">
                                                <a href="{{ asset('storage/DOCUMENTOS/' . $n->CUECOMPLETO . '/' . $n->Agente . '/' . $n->URL) }}" target="_BLANK">
                                                    <i class="fas fa-eye" style="color:green"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4"><h3>No se encontraron archivos</h3></td> <!-- Mejorar presentación si no hay documentos -->
                                    </tr>
                                @endif
                                 
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


@endsection