 @foreach ($documentos as $documento)
    <tr>
        <td>{{ $documento->URL }}</td>
        <td>{{ $documento->FechaAlta }}</td>
        <td style="display: flex">
            <a href="{{ asset('storage/DOCUMENTOS/' . $documento->CUECOMPLETO . '/' . $documento->Agente . '/' . $documento->URL) }}" target="_blank" title="Observar Documento">
                <i class="fa fa-eye"></i>
            </a>
            {{-- <a href="{{route('borrarDocumentoAgente',$documento->idDocumento) }}" class="confirmDelete" data-id="{{ $documento->idDocumento }}">
                <i class="fa fa-eraser borrarDoc" style="color:red;margin-left:5px"></i>
            </a> --}}
            <form method="POST" action="{{ route('borrarDocumentoAgente') }}" class="confirmDelete">
                @csrf
                <input type="hidden" name="doc" value="{{ $documento->idDocumento }}">
                <button type="submit" name="btnDel" style="padding: 0;margin:0px;border:none" title="Borrar Documento">
                    <i class="fa fa-eraser borrarDoc" style="color:red;margin-left:10px"></i>
                </button>
            </form>
        </td>
    </tr>
@endforeach

