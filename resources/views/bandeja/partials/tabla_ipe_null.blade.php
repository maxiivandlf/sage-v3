<table class="table table-bordered table-striped " id="tablaIPE">
    <thead>
        <tr>
            <th>CUEA</th>
            <th>ESCU</th>
            <th>Área</th>
            <th>Nombre de la Institución</th>
            <th>Cantidad sin IPE</th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalGeneral = 0;
        @endphp
        @foreach ($registros as $item)
            @php $totalGeneral += $item['cantidad']; @endphp
            <tr>
                <td>{{ $item['CUEA'] }}</td>
                <td>{{ $item['Escu'] }}</td>
                <td>{{ $item['Area'] }}</td>
                <td>{{ $item['NombreInstitucion'] }}</td>
                <td>{{ $item['cantidad'] }}</td>
            </tr>
        @endforeach
        <tr class="font-weight-bold bg-light">
            <td colspan="4" class="text-right">Total general:</td>
            <td>{{ $totalGeneral }}</td>
        </tr>
    </tbody>
</table>
<div class="text-right mt-3">
    <button class="btn btn-success" onclick="exportarExcelIPE()">
        <i class="fas fa-file-excel"></i> Exportar a Excel
    </button>
</div>
