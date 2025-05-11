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
                    <h4 class="text-center display-4">Aproximado por {{$NivelSeleccionado}}</h4>
                    <!-- Agregar Nuevo Agente -->
                   
                    <div class="row d-flex justify-content-center">
                        <!-- left column -->
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
                                            @foreach ($infoDatos as $i)
                                            <tr>
                                                <td>{{ $i['nombre_origen'] }}</td>
                                                <td>{{ $i['cantidad'] }}</td>
                                                <td>-</td>
                                       
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-lightblue">
                                <div class="card-header">
                                    <h3 class="card-title">Cantidad Con Privada</h3>
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
                                            @foreach ($infoDatos2 as $i)
                                            <tr>
                                                <td>{{ $i['nombre_origen'] }}</td>
                                                <td>{{ $i['cantidad'] }}</td>
                                                <td>-</td>
                                       
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>
                    </div>  
                   <!-- Botón para exportar a Excel -->
                   <div class="row d-flex justify-content-center mt-3">
                    <button id="btn-exportar" class="btn btn-primary" data-nivel="{{$NivelSeleccionado}}">
                        Exportar a Excel
                    </button>
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
    // Función para la exportación de la tabla a Excel
    document.getElementById('btn-exportar').addEventListener('click', function () {
        // Obtiene el nivel seleccionado desde el atributo data del botón
        const nivelSeleccionado = this.getAttribute('data-nivel');
        const table = document.getElementById('porgrupos');

        if (table) {
            // Crea el archivo Excel
            const workbook = XLSX.utils.table_to_book(table, { sheet: "Hoja1" });
            
            // Genera el nombre del archivo basado en el nivel
            const nombreArchivo = 'datos_pof_' + nivelSeleccionado + '.xlsx';

            // Descarga el archivo Excel
            XLSX.writeFile(workbook, nombreArchivo);
        } else {
            alert('No se encontró la tabla para exportar');
        }
    });
</script>
@endsection
