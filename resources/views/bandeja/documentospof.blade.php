@php
    $infoUsuario = session('InfoUsuario');
@endphp
@foreach ($documentos as $documento)
<tr id="documento-{{ $documento->idDocumento }}">
    <td>{{ $documento->URL }}</td>
    <td>{{ $documento->FechaAlta }}</td>
    <td style="display: flex">
        <a href="{{ asset('storage/DOCUMENTOS/' . $documento->CUECOMPLETO . '/' . $documento->Agente . '/' . $documento->URL) }}" target="_blank" title="Observar Documento">
            <i class="fa fa-eye"></i>
        </a>

        @if($infoUsuario->Modo==2)
            <button type="button" class="btn-delete-documento" data-id="{{ $documento->idDocumento }}" style="padding: 0; margin: 0px; border: none;" title="Borrar Documento">
                <i class="fa fa-eraser borrarDoc" style="color:red; margin-left: 10px;"></i>
            </button>
        @endif
        
    </td>
</tr>
@endforeach