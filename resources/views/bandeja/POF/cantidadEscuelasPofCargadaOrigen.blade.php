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
                    <h4 class="text-center display-4">Aproximado en Niveles Registrados</h4>
                    <!-- Agregar Nuevo Agente -->
                   
                    <div class="row d-flex justify-content-center">
                        <!-- left column -->
                        <div class="col-md-10">
                            <div class="card card-lightblue">
                                <div class="card-header">
                                    <h3 class="card-title">Calculo</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="imprimir" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                               
                                                <th>Nivel</th>
                                                <th>Estatal Confirmado</th>
                                                <th>Estatal Sin Confirmar</th>
                                                <th>Total Estatal</th>
                                                <th>Privado Confirmado</th>
                                                <th>Privado Sin Confirmado</th>
                                                <th>Total Acumulado Niveles</th>
                                                <th>Sin Determinar Si es Privada</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($Niveles as $n)
                                            <tr>
                                                <td>{{$n->NivelEnsenanza}}</td>
                                                <td>
                                                    @php
                                                        $totalCon1 = DB::table('tb_institucion_extension')
                                                        ->select('CUECOMPLETO', DB::raw('COUNT(*) as total'))
                                                        ->where('Nivel', 'like', '%'.$n->NivelEnsenanza.'%')
                                                        ->where('EsPrivada', 'N')
                                                        //->where('Nivel', 'not like', 'Priv%') // Excluir los que comienzan con "Priv"
                                                        ->where('CUECOMPLETO', 'not like', '9500%') // Excluir los que comienzan con "9500"
                                                        ->where('CUECOMPLETO', 'not like', '9999%') // Excluir los que comienzan con "9500"
                                                        ->where('PermiteEditarTodo', 1)
                                                        ->groupBy('CUECOMPLETO')
                                                        ->get();
                                                       
                                                    @endphp
                                                    {{$totalCon1->count()}}
                                                </td>
                                                <td>
                                                    @php
                                                        $totalCon1 = DB::table('tb_institucion_extension')
                                                        ->select('CUECOMPLETO', DB::raw('COUNT(*) as total'))
                                                        ->where('Nivel', 'like', '%'.$n->NivelEnsenanza.'%')
                                                        ->where('EsPrivada', 'N')
                                                        //->where('Nivel', 'not like', 'Priv%') // Excluir los que comienzan con "Priv"
                                                        ->where('CUECOMPLETO', 'not like', '9500%') // Excluir los que comienzan con "9500"
                                                        ->where('CUECOMPLETO', 'not like', '9999%') // Excluir los que comienzan con "9500"
                                                        ->where('PermiteEditarTodo', 0)
                                                        ->groupBy('CUECOMPLETO')
                                                        ->get();
                                                       
                                                    @endphp
                                                    {{$totalCon1->count()}}
                                                </td>
                                                <td>
                                                    @php
                                                        
                                                        $total = DB::table('tb_institucion_extension')
                                                        ->select('CUECOMPLETO', DB::raw('COUNT(*) as total'))
                                                        ->where('Nivel', 'like', '%'.$n->NivelEnsenanza.'%')
                                                        ->where('EsPrivada', 'N')                   //Excluir a las Privadas
                                                        //->where('Nivel', 'not like', 'Priv%')       // Excluir los que comienzan con "Privado"
                                                        ->where('CUECOMPLETO', 'not like', '9500%') // Excluir los que comienzan con "9500????" los que no ubico
                                                        ->where('CUECOMPLETO', 'not like', '9999%') // Excluir los que comienzan con "9500????" los de prueba
                                                        ->groupBy('CUECOMPLETO')
                                                        ->get();
                                                    @endphp
                                                     {{$total->count()}}
                                                </td>
                                                <td>
                                                    @php
                                                        $totalCon1 = DB::table('tb_institucion_extension')
                                                        ->select('CUECOMPLETO', DB::raw('COUNT(*) as total'))
                                                        ->where('Nivel', 'like', '%'.$n->NivelEnsenanza.'%')
                                                        ->where('EsPrivada', 'S')
                                                        //->where('Nivel', 'not like', 'Priv%') // Excluir los que comienzan con "Priv"
                                                        ->where('CUECOMPLETO', 'not like', '9500%') // Excluir los que comienzan con "9500"
                                                        ->where('CUECOMPLETO', 'not like', '9999%') // Excluir los que comienzan con "9500"
                                                        ->where('PermiteEditarTodo', 1)
                                                        ->groupBy('CUECOMPLETO')
                                                        ->get();
                                                        
                                                    @endphp
                                                    {{$totalCon1->count()}}
                                                </td>
                                                
                                                <td>
                                                    @php
                                                        $totalCon1 = DB::table('tb_institucion_extension')
                                                        ->select('CUECOMPLETO', DB::raw('COUNT(*) as total'))
                                                        ->where('Nivel', 'like', '%'.$n->NivelEnsenanza.'%')
                                                        ->where('EsPrivada', 'S')
                                                        //->where('Nivel', 'not like', 'Priv%') // Excluir los que comienzan con "Priv"
                                                        ->where('CUECOMPLETO', 'not like', '9500%') // Excluir los que comienzan con "9500"
                                                        ->where('CUECOMPLETO', 'not like', '9999%') // Excluir los que comienzan con "9500"
                                                        ->where('PermiteEditarTodo', 0)
                                                        ->groupBy('CUECOMPLETO')
                                                        ->get();
                                                        
                                                    @endphp
                                                    {{$totalCon1->count()}}
                                                </td>
                                                
                                                <td>
                                                    @php
                                                        
                                                        $total = DB::table('tb_institucion_extension')
                                                        ->select('CUECOMPLETO', DB::raw('COUNT(*) as total'))
                                                        ->where('Nivel', 'like', '%'.$n->NivelEnsenanza.'%')
                                                        //->where('EsPrivada', 'N')                   //Excluir a las Privadas
                                                        //->where('Nivel', 'not like', 'Priv%')       // Excluir los que comienzan con "Privado"
                                                        ->where('CUECOMPLETO', 'not like', '9500%') // Excluir los que comienzan con "9500????" los que no ubico
                                                        ->where('CUECOMPLETO', 'not like', '9999%') // Excluir los que comienzan con "9500????" los de prueba
                                                        ->groupBy('CUECOMPLETO')
                                                        ->get();
                                                    @endphp
                                                    {{$total->count()}}
                                                </td>
                                                <td>
                                                    @php
                                                        
                                                        $total = DB::table('tb_institucion_extension')
                                                        ->select('CUECOMPLETO', DB::raw('COUNT(*) as total'))
                                                        ->where('Nivel', 'like', '%'.$n->NivelEnsenanza.'%')
                                                        ->whereNull('EsPrivada')                   //Excluir a las Privadas
                                                        //->where('Nivel', 'not like', 'Priv%')       // Excluir los que comienzan con "Privado"
                                                        ->where('CUECOMPLETO', 'not like', '9500%') // Excluir los que comienzan con "9500????" los que no ubico
                                                        ->where('CUECOMPLETO', 'not like', '9999%') // Excluir los que comienzan con "9500????" los de prueba
                                                        ->groupBy('CUECOMPLETO')
                                                        ->get();
                                                    @endphp
                                                    {{$total->count()}}
                                                </td>
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
                        <button id="btn-exportar" class="btn btn-primary">
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
        // Asegúrate de que la tabla tiene el id correcto 'example1'
        const table = document.getElementById('imprimir');

        if (table) {
            const workbook = XLSX.utils.table_to_book(table, { sheet: "Hoja1" });
            XLSX.writeFile(workbook, 'datos_pof_niveles.xlsx'); // Nombre del archivo a exportar
        } else {
            alert('No se encontró la tabla para exportar');
        }
    });
</script>
@endsection
