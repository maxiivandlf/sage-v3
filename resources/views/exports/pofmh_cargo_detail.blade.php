<table>
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
            <td>{{ $detalle['idPofmh'] }}</td>
            <td>{{ $detalle['CUECOMPLETO'] }}</td>
            <td>{{ $detalle['Nivel'] }} - {{ $detalle['Nombre_Institucion'] }}</td>
            <td>{{ $detalle['Cargo_Origen'] }}</td>
            <td>{{ $detalle['Cargo_Salarial'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
