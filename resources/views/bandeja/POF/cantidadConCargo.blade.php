@extends('layout.app')

@section('Titulo', 'Sage2.0 - Detalle Agrupado POF')

@section('ContenidoPrincipal')

<section id="container">
    <section id="main-content">
        <section class="content-wrapper">
            <section class="content">
                <div class="container-fluid">
                    <h4 class="text-center display-4">Detalles del POF - Institución</h4>
                   
                    <div class="row d-flex justify-content-center">
                        <!-- Tabla de detalles -->
                        <div class="col-md-12">
                            <div class="card card-lightblue">
                                <div class="card-header">
                                    <h3 class="card-title">Detalle por Institución</h3>
                                </div>
                                <div class="card-body">
                                    <table id="detalles" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>CUECOMPLETO</th>
                                                <th>Nivel / Nombre de la Institución</th>
                                                <th>Cargo Origen</th>
                                                <th>Nombre del Cargo Salarial</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($infoPofMH as $detalle)
                                            <tr>
                                                <td>{{ $detalle->idPofmh }}</td>
                                                <td>{{ $detalle->CUECOMPLETO }}</td>
                                                <td>
                                                    @php
                                                        $inst = DB::table('tb_institucion_extension')->where('CUECOMPLETO', $detalle->CUECOMPLETO)
                                                        ->select('Nivel', 'Nombre_Institucion')
                                                        ->first(); 
                                                    @endphp
                                                    {{ $inst->Nivel?$inst->Nivel: "Sin Definir" }} - {{ $inst->Nombre_Institucion }}
                                                </td>
                                                <td>
                                                    @if($detalle->Origen != null && $detalle->Origen != "")
                                                        @php
                                                            $CargosCreados = DB::connection('DB7')
                                                                ->table('tb_origenes_cargos')
                                                                ->where('idOrigenCargo', $detalle->Origen)
                                                                ->join('tb_cargos_pof_origen', 'tb_cargos_pof_origen.idCargos_Pof_Origen', '=', 'tb_origenes_cargos.nombre_origen')
                                                                ->select('tb_origenes_cargos.idOrigenCargo', 'tb_cargos_pof_origen.nombre_cargo_origen')
                                                                ->first();
                                                        @endphp

                                                        @if($CargosCreados)
                                                            {{ $CargosCreados->nombre_cargo_origen }}
                                                        @else
                                                            Origen no encontrado
                                                        @endif
                                                    @else
                                                        Sin Definir
                                                    @endif
                                                    
                                                </td>
                                                <td>
                                                    @php
                                                        $c = DB::table('tb_cargossalariales')->where('idCargo', $detalle->Cargo)->first();
                                                    @endphp
                                                
                                                    @if($c)
                                                        {{ $c->Cargo }} - ({{ $c->Codigo }})
                                                    @else
                                                        Sin Definir
                                                    @endif
                                                </td>
                                                
                                              
                                               
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones para exportar -->
                    <div class="row d-flex justify-content-center mt-3">
                        <button id="btn-exportar-detallados" class="btn btn-secondary m-2" data-nivel="{{}}">
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

    // Exportar detallados
    document.getElementById('btn-exportar-detallados').addEventListener('click', function () {
        const nivelSeleccionado = this.getAttribute('data-nivel');
        exportarTabla('detalles', nivelSeleccionado, 'datos_pof_detallados');
    });
</script>
@endsection
