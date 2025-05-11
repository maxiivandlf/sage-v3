<div class="container mt-4">
    <h2 class="text-center">Datos Importados</h2>
    <div class="alert alert-info">
        <strong>Nota:</strong> A continuación se muestran los datos importados desde el archivo Excel. Si los datos son
        correctos, puede proceder a la siguiente etapa.
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="thead-light">
                <tr>
                    @foreach ($columnas as $columna)
                        <th scope="col" class="text-center">
                            {{ $columnasTraducidas[$columna] ?? $columna }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($datosImportados as $dato)
                    <tr>
                        @foreach ($columnas as $columna)
                            <td class="text-center">
                                {{ $dato[$columna] }}
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $datosImportados->links('pagination::bootstrap-4') }}
    </div>
</div>
