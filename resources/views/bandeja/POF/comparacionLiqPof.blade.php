@extends('layout.app')

@section('Titulo', 'Sage2.0 - Nuevo Usuario ADMIN')

@section('ContenidoPrincipal')
<style>
    table {
        width: 100%; /* Asegúrate de que la tabla ocupe el ancho completo */
        border-collapse: collapse; /* Colapsa los bordes de las celdas */
    }

    thead {
        background-color: #f2f2f2; /* Cambia el color de fondo si lo deseas */
    }

    th {
        position: sticky; /* Mantiene la posición de los encabezados */
        top: 0; /* Fija la cabecera en la parte superior */
        z-index: 10; /* Asegúrate de que esté encima de otros elementos */
        padding: 10px; /* Espaciado interno para las celdas */
        border: 1px solid #ccc; /* Bordes de las celdas */
    }
</style>
<section id="container">
    <section id="main-content">
        <section class="content-wrapper">
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- Buscador Agente -->
                    <h4 class="text-center display-4">Aproximado</h4>
                    <!-- Agregar Nuevo Agente -->
                    <div class="row d-flex justify-content-center">
                        <div class="col-md-6">
                            <div class="card card-lightblue">
                                <div class="card-header">
                                    <h3 class="card-title">LIQ SEP 2024</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="LIQ2024" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>COD</th>
                                                <th>DESC</th>
                                                <th>T</th>
                                                <th>I</th>
                                                <th>T+I</th>
                                                <th>S</th>
                                                <th>V</th>
                                                <th>PE</th>
                                                <th>Vinc</th>
                                                <th>TOTAL</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($cargosLiq as $cargo)
                                            <tr data-id="{{$cargo['codigo']}}">
                                                <td data-codigo="{{ $cargo['codigo'] }}">
                                                    {{ $cargo['codigo'] }} 
                                                    <span class="indicator"></span>
                                                </td>
                                                <td data-descripcion="{{ $cargo['descripcion'] }}">{{ $cargo['descripcion'] }} <span class="indicator"></span></td>
                                                <td data-titular="{{ $cargo['titular'] }}">{{ $cargo['titular'] }} <span class="indicator"></span></td>
                                                <td data-interino="{{ $cargo['interino'] }}">{{ $cargo['interino'] }} <span class="indicator"></span></td>
                                                <td style="background-color:salmon"  data-titinter="{{ $cargo['titinter'] }}">{{ $cargo['titinter'] }} <span class="indicator"></span></td>
                                                <td data-suplente="{{ $cargo['suplente'] }}">{{ $cargo['suplente'] }} <span class="indicator"></span></td>
                                                <td data-volante="{{ $cargo['volante'] }}">{{ $cargo['volante'] }} <span class="indicator"></span></td>
                                                <td data-planta_permanente="{{ $cargo['planta_permanente'] }}">{{ $cargo['planta_permanente'] }} <span class="indicator"></span></td>
                                                <td data-vinculado="{{ $cargo['vinculado'] }}">{{ $cargo['vinculado'] }} <span class="indicator"></span></td>
                                                <td data-total="{{ $cargo['total'] }}">{{ $cargo['total'] }} <span class="indicator"></span></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>
                        <!-- left column -->
                        <div class="col-md-6">
                            <div class="card card-lightblue">
                                <div class="card-header">
                                    <h3 class="card-title">SAGE</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="POFMH" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>COD</th>
                                                <th>DESC</th>
                                                <th>T</th>
                                                <th>I</th>
                                                <th>T+I</th>
                                                <th>S</th>
                                                <th>V</th>
                                                <th>PE</th>
                                                <th>Vinc</th>
                                                <th>TOTAL</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($cargos as $cargo)
                                            <tr data-id="{{$cargo['codigo']}}">
                                                <td data-codigo="{{ $cargo['codigo'] }}">
                                                    {{ $cargo['codigo'] }} 
                                                    <span class="indicator"></span>
                                                </td>
                                                <td data-descripcion="{{ $cargo['descripcion'] }}">{{ $cargo['descripcion'] }} <span class="indicator"></span></td>
                                                <td data-titular="{{ $cargo['titular'] }}">{{ $cargo['titular'] }} <span class="indicator"></span></td>
                                                <td data-interino="{{ $cargo['interino'] }}">{{ $cargo['interino'] }} <span class="indicator"></span></td>
                                                <td style="background-color:salmon" data-titinter="{{ $cargo['titinter'] }}">{{ $cargo['titinter'] }} <span class="indicator"></span></td>
                                                <td data-suplente="{{ $cargo['suplente'] }}">{{ $cargo['suplente'] }} <span class="indicator"></span></td>
                                                <td data-volante="{{ $cargo['volante'] }}">{{ $cargo['volante'] }} <span class="indicator"></span></td>
                                                <td data-planta_permanente="{{ $cargo['planta_permanente'] }}">{{ $cargo['planta_permanente'] }} <span class="indicator"></span></td>
                                                <td data-vinculado="{{ $cargo['vinculado'] }}">{{ $cargo['vinculado'] }} <span class="indicator"></span></td>
                                                <td data-total="{{ $cargo['total'] }}">{{ $cargo['total'] }} <span class="indicator"></span></td>
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
    // Función para la exportación de la tabla a Excel
    document.getElementById('btn-exportar').addEventListener('click', function () {
        const table = document.getElementById('example1');  // Asegúrate de que 'example1' sea el ID de tu tabla

        if (table) {
            const workbook = XLSX.utils.table_to_book(table, { sheet: "Hoja1" });
            XLSX.writeFile(workbook, 'datos_pof.xlsx');
        } else {
            alert('No se encontró la tabla para exportar');
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const liqTable = document.querySelector('#LIQ2024 tbody');
        const sageTable = document.querySelector('#POFMH tbody');
    
        const liqRows = liqTable.querySelectorAll('tr');
        const sageRows = sageTable.querySelectorAll('tr');
    
        liqRows.forEach(liqRow => {
            const liqCod = liqRow.querySelector('td[data-codigo]').innerText;
    
            sageRows.forEach(sageRow => {
                const sageCod = sageRow.querySelector('td[data-codigo]').innerText;
    
                // Cotejar los códigos
                if (liqCod === sageCod) {
                    // Comparar todos los campos excepto código y descripción
                    const fields = ['titular', 'interino', 'titinter', 'suplente', 'volante', 'planta_permanente', 'vinculado', 'total'];
                    
                    fields.forEach(field => {
                        const liqValue = liqRow.querySelector(`td[data-${field}]`).innerText;
                        const sageValue = sageRow.querySelector(`td[data-${field}]`).innerText;
                        const liqIndicator = liqRow.querySelector(`td[data-${field}] .indicator`);
                        const sageIndicator = sageRow.querySelector(`td[data-${field}] .indicator`);

                        // Comparar los datos
                        if (liqValue > sageValue) {
                            // Mostrar flecha verde hacia arriba
                            liqIndicator.innerHTML = '<i class="fas fa-arrow-up" style="color: green;"></i>';
                            sageIndicator.innerHTML = '<i class="fas fa-arrow-down" style="color: red;"></i>';
                        } else if (liqValue < sageValue) {
                            // Mostrar flecha roja hacia abajo
                            liqIndicator.innerHTML = '<i class="fas fa-arrow-down" style="color: red;"></i>';
                            sageIndicator.innerHTML = '<i class="fas fa-arrow-up" style="color: green;"></i>';
                        } else {
                            // Mostrar flecha gris hacia los lados
                            liqIndicator.innerHTML = '<i class="fas fa-arrows-alt-h" style="color: gray;"></i>';
                            sageIndicator.innerHTML = '<i class="fas fa-arrows-alt-h" style="color: gray;"></i>';
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
