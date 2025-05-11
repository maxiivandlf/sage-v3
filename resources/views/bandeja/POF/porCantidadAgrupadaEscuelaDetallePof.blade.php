@extends('layout.app')

@section('Titulo', 'Sage2.0 - Detalle Agrupado POF')

@section('ContenidoPrincipal')

<section id="container">
    <section id="main-content">
        <section class="content-wrapper">
            <section class="content">
                <div class="container-fluid">
                    <h4 class="text-center display-4">Detalles por {{ $NivelSeleccionado }}</h4>
                   
                    <div class="row d-flex justify-content-center">
                        <!-- Tabla de agrupación -->
                        <div class="col-md-6">
                            <div class="card card-lightblue">
                                <div class="card-header">
                                    <h3 class="card-title">Cantidad por Origen</h3>
                                </div>
                                <div class="card-body">
                                    <table id="agrupados" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Origen</th>
                                                <th>Cantidad Cargos</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($infoDatos as $i)
                                            <tr>
                                                <td>{{ $i['nombre_origen'] }}</td>
                                                <td>{{ $i['cantidad'] }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Tabla de detalles -->
                        <div class="col-md-6">
                            <div class="card card-lightblue">
                                <div class="card-header">
                                    <h3 class="card-title">Detalle por Institución</h3>
                                </div>
                                <div class="card-body">
                                    <table id="detalles" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Nombre del Cargo</th>
                                                <th>Nombre de la Institución</th>
                                                <th>CUECOMPLETO</th>
                                                <th>Nivel</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($detallesInstituciones as $origen => $detalles)
                                                @foreach ($detalles as $detalle)
                                                <tr>
                                                    <td>
                                                        @php
                                                            $infoCargo = DB::connection('DB7')->table('tb_cargos_pof_origen')
                                                            ->where('idCargos_Pof_Origen',$detalle['nombre_cargo'])->first();
                                                        @endphp
                                                        Cod({{ $detalle['nombre_cargo'] }}) - {{$infoCargo->nombre_cargo_origen}}
                                                    </td>
                                                    <td>{{ $detalle['nombre_institucion'] }}</td>
                                                    <td>{{ $detalle['cuecompleto'] }}</td>
                                                    <td>{{ $detalle['nivel'] }}</td>
                                                    
                                                </tr>
                                                @endforeach
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones para exportar -->
                    <div class="row d-flex justify-content-center mt-3">
                        <button id="btn-exportar-grupos" class="btn btn-primary m-2" data-nivel="{{$NivelSeleccionado}}">
                            Exportar Agrupados
                        </button>
                        <button id="btn-exportar-detallados" class="btn btn-secondary m-2" data-nivel="{{$NivelSeleccionado}}">
                            Exportar Detallados
                        </button>
                    </div>
                </div>
            </section>
        </section>
    </section>
</section>

@endsection

@section('Script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>

<script>
    // Función para exportar la tabla a Excel
    function exportarTabla(tableId, nivelSeleccionado, nombreArchivoBase) {
        const table = document.getElementById(tableId);
        if (table) {
            // Crear el archivo Excel
            const workbook = XLSX.utils.table_to_book(table, { sheet: "Hoja1" });
            const nombreArchivo = nombreArchivoBase + '_' + nivelSeleccionado + '.xlsx';
            // Descargar el archivo
            XLSX.writeFile(workbook, nombreArchivo);
        } else {
            alert('No se encontró la tabla para exportar');
        }
    }

    // Exportar agrupados
    document.getElementById('btn-exportar-grupos').addEventListener('click', function () {
        const nivelSeleccionado = this.getAttribute('data-nivel');
        exportarTabla('agrupados', nivelSeleccionado, 'datos_pof_agrupados');
    });

    // Exportar detallados
    document.getElementById('btn-exportar-detallados').addEventListener('click', function () {
        const nivelSeleccionado = this.getAttribute('data-nivel');
        exportarTabla('detalles', nivelSeleccionado, 'datos_pof_detallados');
    });
</script>
@endsection