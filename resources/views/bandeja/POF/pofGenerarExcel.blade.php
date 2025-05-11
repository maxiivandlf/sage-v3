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
                    <h4 class="text-center display-4">Aproximado</h4>
                    <!-- Agregar Nuevo Agente -->
                   
                    <div class="row d-flex justify-content-center">
                        <!-- left column -->
                        <div class="col-md-10">
                            <div class="card card-lightblue">
                                <div class="card-header">
                                    <h3 class="card-title">Cantidad</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                               
                                                <th>CUE</th>
                                                <th>Cargo</th>
                                                <th>Aula</th>
                                                <th>Division</th>
                                                <th>Turno</th>
        
                                         
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($infoDatos as $i)
                                            <tr>
                                                <td>{{ $i->CUECOMPLETO }}</td>
                                                <td>
                                                    @php
                                                        $infoCargo = DB::connection('DB7')->table('tb_cargos_pof_origen')
                                                        ->join('tb_origenes_cargos','tb_origenes_cargos.nombre_origen','tb_cargos_pof_origen.idCargos_Pof_Origen')
                                                        ->where('idOrigenCargo',$i->idOrigenCargo)->first();
                                                        //dd($infoCargo);
                                                    @endphp
                                                    {{ $infoCargo?$infoCargo->nombre_cargo_origen:"No Encontrado"}}
                                                </td>
                                                <td>{{ $i->nombre_aula }}</td>
                                                <td>{{ $i->nombre_division }}</td>
                                                <td>{{ $i->nombre_turno }}</td>
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
        // Simula una tabla que quieres exportar, puede ser cualquier tabla que tengas en el HTML
        const table = document.getElementById('example1');  // Asegúrate de que 'example1' sea el ID de tu tabla

        if (table) {
            const workbook = XLSX.utils.table_to_book(table, { sheet: "Hoja1" });
            XLSX.writeFile(workbook, 'datos_pof.xlsx');
        } else {
            alert('No se encontró la tabla para exportar');
        }
    });

    $(document).ready(function() {
        $('#btn-exportar').on('click', function() {
            $('#progreso').show(); // Muestra la barra de progreso
            $('#porcentaje').text('0%');
            $('#progreso-barra').css('width', '0%');

            // Inicia la exportación vía AJAX
            $.ajax({
                url: '/exportar_pof', // La ruta que manejará la exportación
                method: 'GET',
                success: function(data) {
                    // Comienza a monitorear el progreso
                    let intervalo = setInterval(function() {
                        $.get('/progreso_exportacion', function(data) {
                            $('#porcentaje').text(data.progreso + '%');
                            $('#progreso-barra').css('width', data.progreso + '%');

                            // Cuando el progreso llegue a 100%, detiene el intervalo y descarga el archivo
                            if (data.progreso >= 100) {
                                clearInterval(intervalo);

                                // Alerta de finalización
                                alert('Exportación completada. Iniciando descarga del archivo.');

                                // Redirige a la ruta de descarga del archivo
                                window.location.href = '/descargar_pof';
                            }
                        });
                    }, 1000); // Intervalo de 1 segundo entre cada consulta
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error en la exportación: ' + textStatus);
                }
            });
        });
    });
</script>

@endsection
