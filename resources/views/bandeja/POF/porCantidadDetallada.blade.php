@extends('layout.app')

@section('Titulo', 'Sage2.0 - Nuevo Usuario ADMIN')

@section('ContenidoPrincipal')
<section id="container">
    <section id="main-content">
        <section class="content-wrapper">
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- Título -->
                    <h4 class="text-center display-4">Aproximado por {{$NivelSeleccionado}}</h4>
                   
                    <div class="row d-flex justify-content-center">
                        <!-- Tabla agrupada -->
                        <div class="col-md-6">
                            <div class="card card-lightblue">
                                <div class="card-header">
                                    <h3 class="card-title">Cantidad Con Estatal</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="porgrupos" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Origen</th>
                                                <th>Cantidad Cargos</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($infoDatos2 as $i)
                                                <tr>
                                                    <td>{{ $i['nombre_origen'] ?? 'Sin datos' }}</td>
                                                    <td>{{ $i['cantidad'] ?? 0 }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="2">No se encontraron datos agrupados</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>
                        
                        <!-- Tabla detallada -->
                        <div class="col-md-6">
                            <div class="card card-lightblue">
                                <div class="card-header">
                                    <h3 class="card-title">Cantidad Con Privada</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="detallados" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Nombre del Cargo</th>
                                                <th>Nombre de la Institución</th>
                                                <th>CUECOMPLETO</th>
                                                <th>Nivel</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($infoDatos as $detalle)
                                                <tr>
                                                    <td>{{ $detalle['nombre_cargo'] ?? 'Sin datos' }}</td>
                                                    <td>{{ $detalle['nombre_institucion'] ?? 'Sin datos' }}</td>
                                                    <td>{{ $detalle['cuecompleto'] ?? 'Sin datos' }}</td>
                                                    <td>{{ $detalle['nivel'] ?? 'Sin datos' }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4">No se encontraron datos detallados</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.card-body -->
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
            <!-- /.content -->
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
        exportarTabla('porgrupos', nivelSeleccionado, 'datos_pof_agrupados');
    });

    // Exportar detallados
    document.getElementById('btn-exportar-detallados').addEventListener('click', function () {
        const nivelSeleccionado = this.getAttribute('data-nivel');
        exportarTabla('detallados', nivelSeleccionado, 'datos_pof_detallados');
    });
</script>
@endsection
