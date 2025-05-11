@foreach($institucionesPorNivel as $nivel => $instituciones)
  <h5 style="display: block;background-color:cadetblue;padding: 5px">Nivel: {{ $nivel }}</h5>
  <table id="titulosTab-{{ $nivel }}" class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>CInst</th>
        <th>Inst</th>
        <th>CUE</th>
        <th>T</th>
        <th>N</th>
        <th>CA</th>
        @foreach ($sitRev as $st)
          <th>{{ $st->Mnemo }}</th>
        @endforeach
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      @foreach($instituciones as $o)
        <tr class="gradeX" id="fila-{{ $o->idInstitucionExtension }}">
          <td>{{ $o->idInstitucionExtension }}</td>
          <td>{{ $o->Nombre_Institucion }}</td>
          <td>{{ $o->CUECOMPLETO }}</td>
          <td>{{ $o->Turno }}</td>
          <td>{{ $o->Nivel }}</td>
          <td>
            {{ DB::table('tb_nodos')
                ->where('CUECOMPLETO', $o->CUECOMPLETO)
                ->where('idTurnoUsuario', $o->idTurnoUsuario)
                ->count() }}
          </td>
          @foreach ($sitRev as $st)
            <td>
              {{ DB::table('tb_nodos')
                  ->where('CUECOMPLETO', $o->CUECOMPLETO)
                  ->where('idTurnoUsuario', $o->idTurnoUsuario)
                  ->where('SitRev', $st->idSituacionRevista)
                  ->count() }}
            </td>
          @endforeach
          <td>
            <button type="button" class="btn btn-default view-details" data-toggle="modal" data-target="#modal-default" data-id="{{ $o->idInstitucionExtension }}">
                <i class="fas fa-chart-bar"></i>
            </button>
            <button type="button" class="btn btn-default view-users" data-toggle="modal" data-target="#modal-user" data-id="{{ $o->idInstitucionExtension }}">
              <i class="fas fa-user"></i>
            </button>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endforeach
