<div class="table-responsive">
    <table class="table table-bordered tabla-agentes">
        <thead class="table-light">
            <tr>
                <th>Documento</th>
                <th>Nombre</th>
                <th>Validación</th>
                <th>Unidades</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($lista as $item)
                <tr>
                    <td>{{ $item['docu'] ?? '-' }}</td>
                    <td>{{ $item['agente']['nomb'] ?? '-' }}</td>
                    <td>
                        @if (isset($item['validacion']))
                            IPE: {{ $item['validacion']['ipe'] ?? 'N/A' }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if (isset($item['validacion']['unidadesValidadas']))
                            <ul class="mb-0 d-flex">
                                @foreach ($item['validacion']['unidadesValidadas'] as $unidad)
                                    <li>{{ $unidad['UnidadDePago'] ?? 'S/U' }}</li>
                                    <li> -
                                        <strong class="{{ $unidad['coincide'] ? 'text-success' : 'text-danger' }}">
                                            {{ $unidad['coincide'] ? '✔ Coincide' : '✘ No coincide' }}
                                        </strong>

                                    </li>
                                    <li>
                                        {{ $unidad['UnidadDeclarada1'] ?? 'S/U' }}
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-muted">No valida</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
