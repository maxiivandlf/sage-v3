@if ($documentos->isEmpty())
    <p>No hay documentos disponibles para esta novedad.</p>
@else
    <ul class="list-group">
        @foreach ($documentos as $documento)
            <li class="list-group-item">
                <a href="{{ asset('storage/documentos/' . $documento->ruta) }}" target="_blank">
                    {{ $documento->nombre }}
                </a>
            </li>
        @endforeach
    </ul>
@endif
