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
                                                        {{ $detalle['nombre_cargo'] }} - {{$infoCargo->nombre_cargo_origen}}
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

                    <!-- Botón para exportar toda la tabla a Excel -->
                    <div class="row d-flex justify-content-center mt-3">
                        <button id="btn-exportar" class="btn btn-primary" data-nivel="{{ $NivelSeleccionado }}">
                            Exportar toda la tabla a Excel
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
    // Función para exportar toda la tabla de detalles a Excel
    document.getElementById('btn-exportar').addEventListener('click', function () {
        const nivelSeleccionado = this.getAttribute('data-nivel');
        const table = document.getElementById('detalles');
        if (table) {
            const workbook = XLSX.utils.table_to_book(table, { sheet: "Detalles" });
            const nombreArchivo = `detalles_pof_${nivelSeleccionado.replace(/ /g, '_')}.xlsx`;
            XLSX.writeFile(workbook, nombreArchivo);
        } else {
            alert('No se encontró la tabla para exportar');
        }
    });
</script>
@endsection
