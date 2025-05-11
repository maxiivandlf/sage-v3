@if($personas->isEmpty())
    <p>No hay personas registradas en esta institución.</p>
@else
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>DNI</th>
                <th>Agente</th>
                <th>Situacion de Revista</th>
                <th>Cargo Salarial</th>
                <th>En el Aula???</th>
            </tr>
        </thead>
        <tbody>
            @foreach($personas as $persona)
                <tr>
                    <td>{{ $persona->Agente }}</td>
                    <td>{{ $persona->ApeNom }}</td>
                    <td>{{ $persona->Descripcion }}</td>
                    <td>{{ $persona->Cargo }} ({{ $persona->Codigo }})</td>
                    <td>{{ $persona->LicenciaActiva === 'SI' ? 'NO, con Licencia' : 'SI, Activo' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
