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
                    <h4 class="text-center display-4">Bajas Definitiva y Bajas Retorno</h4>
                    <!-- Agregar Nuevo Agente -->
                   
                    <div class="row d-flex justify-content-center">
                        <!-- left column -->
                        <div>
                            <div class="card card-lightblue">
                                <div class="card-header">
                                    <h3 class="card-title">Cantidad Con Estatal</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="porgrupos" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                               
                                                <th>ID</th>
                                                <th>Agente</th>
                                                <th>DNI</th>
                                                <th>CUIL</th>
                                                <th>Escu</th>
                                                <th>CUE</th>
                                                <th>CUE</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($infoPofmh != null)
                                                 @foreach ($infoPofmh as $i)
                                            <tr>
                                                <td>{{ $i->idPofmh }}</td>
                                                <td>{{ $i->Agente }}</td>
                                                <td>-</td>
                                       
                                            </tr>
                                            @endforeach
                                            @endif
                                           
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>
                      
                    </div>  
                   <!-- Botón para exportar a Excel -->
                   <div class="row d-flex justify-content-center mt-3">
                    <button id="btn-exportar" class="btn btn-primary" data-nivel="">
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
