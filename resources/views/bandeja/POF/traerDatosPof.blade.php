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
                    <h4 class="text-center display-4">Prueba Lista</h4>
                    <!-- Agregar Nuevo Agente -->
                   
                    <div class="row d-flex justify-content-center">
                        <!-- left column -->
                        <div class="col-md-10">
                            <div class="card card-lightblue">
                            <div class="card-header">
                                <h3 class="card-title">Lista POF</h3>
                                <button id="exportButton">Exportar a Excel</button>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>CUE</th>
                                            <th>INSTITUCIÓN</th>
                                            <th>NIVEL</th>
                                            <th>ZONA</th>
                                            <th>LOCALIDAD</th>

                                            <th>DNI</th>
                                            <th>CUIL</th>
                                            <th>Apellido y Nombre</th>
                                            <th>POF</th>
                                            <th>Sit.Rev</th>
                                            <th>Horas</th>
                                            <th>Antigüedad Docente</th>

                                            <th>Código Cargo</th>
                                            <th>Aula</th>
                                            <th>Division</th>
                                            <th>Turno</th>
                                            <th>Esp.Cur</th>
                                            <th>Matricula</th>
                                            <th>Posesión del Cargo</th>
                                            <th>Designado al cargo</th>
                                            <th>Condición</th>
                                            <th>¿En el Aula?</th>
                                            <th>Tipo-Motivo-Art.Licenica</th>
                                            <th>Otros Datos por Condición</th>
                                            <th>Desde</th>
                                            <th>Hasta</th>
                                            <th>DNI Suplente</th>
                                            <th>Asistencia</th>
                                            <th>Justificada</th>
                                            <th>Injustificada</th>
                                            <th>Observaciones</th>
                                            <th>Carrera</th>
                                            <th>Orientación</th>
                                            <th>Titulo</th>
                                        </tr>
                                        
                                    </thead>
                                    <tbody>
                                        @foreach ($Pofmh as $p)
                                            <tr>
                                                <td>{{$p['CUECOMPLETO']}}</td>
                                                <td>{{$p['Nombre_Institucion']}}</td>
                                                <td>{{$p['Nivel']}}</td>
                                                <td>{{$p['Zona']}}</td>
                                                <td>{{$p['Localidad']}}</td>
                                                <td>{{$p['Agente']}}</td>
                                                <td>{{$p['Cuil']}}</td>
                                                <td>{{$p['ApeNom']}}</td>
                                                <td>{{$p['Origen']}}</td>
                                                <td>{{$p['SitRev']}}</td>
                                                <td>{{$p['Horas']}}</td>
                                                <td>{{$p['Antiguedad']}}</td>
                                                <td>{{$p['CargoSalarial']}}-<b>({{$p['CodigoSalarial']}})</b></td>
                                                <td>{{$p['Aula']}}</td>
                                                <td>{{$p['Division']}}</td>
                                                <td>{{$p['Turno']}}</td>
                                                <td>{{$p['EspCur']}}</td>
                                                <td>{{$p['Matricula']}}</td>
                                                <td>{{$p['FechaAltaCargo']}}</td>
                                                <td>{{$p['FechaDesignado']}}</td>
                                                <td>{{$p['Condicion']}}</td>
                                                <td>{{$p['Activo']}}</td>
                                                <td>{{$p['Motivo']}}</td>
                                                <td>{{$p['DatosPorCondicion']}}</td>
                                                <td>{{$p['FechaDesde']}}</td>
                                                <td>{{$p['FechaHasta']}}</td>
                                                <td>{{$p['AgenteR']}}</td>
                                                <td>{{$p['Asistencia']}}</td>
                                                <td>{{$p['Justificada']}}</td>
                                                <td>{{$p['Injustificada']}}</td>
                                                <td>{{$p['Observaciones']}}</td>
                                                <td>{{$p['Carrera']}}</td>
                                                <td>{{$p['Orientacion']}}</td>
                                                <td>{{$p['Titulo']}}</td>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>

<script>
    document.getElementById('exportButton').addEventListener('click', function () {
        // Obtiene la tabla
        const table = document.getElementById('example1');
        const workbook = XLSX.utils.table_to_book(table, { sheet: "Hoja1" });
        XLSX.writeFile(workbook, 'datos_pof.xlsx');
    });
</script>
@endsection
