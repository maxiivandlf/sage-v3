

        @foreach ($institucionesPorNivel as $nivel => $instituciones)
            @foreach ($instituciones as $institucion)
                <table id="" class="table table-bordered table-striped tabla-escuelas" style="width: 4000px">
                    <thead>
                        <tr>
                            <th style="width: 75px;">VER</th>
                            <th style="width: 75px;">INSTITUCIÓN</th>
                            <th style="width: 50px;">CUE</th>
                            <th style="width: 50px;">TURNO</th>
                            <th style="width: 50px;">NIVEL</th>
                            <th style="width: 30px;">ZONA</th>
                            <th style="width: 50px;">LOCALIDAD</th>
                            <th style="width: 50%">--</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr data-cue="{{ $institucion->CUECOMPLETO }}">
                        <td>
                            <button class="btn-cargar-agentes" data-cue="{{ $institucion->CUECOMPLETO }}">Ver Agentes</button>
                            <button class="btn-cargar-pof" data-cue="{{$institucion->CUECOMPLETO}}" data-inst="{{ $institucion->Nombre_Institucion }}" data-idext="{{$institucion->idInstitucionExtension}}" data-toggle="modal" data-target="#modal-pof">Ver POF</button>
                        </td>
                        <td>{{ $institucion->Nombre_Institucion }}</td>
                        <td>{{ $institucion->CUECOMPLETO }}</td>
                        <td>{{ $institucion->Turno }}</td>
                        <td>{{ $institucion->Nivel }}</td>
                        <td>{{ $institucion->Zona }}</td>
                        <td>{{ $institucion->Localidad }}</td>
                        <td></td>
                    
                    </tr>
                    
                    </tbody>
                </table>
                <div id="fila-agentes-{{ $institucion->CUECOMPLETO }}" class="fila-agentes" style="display: none;">
                    <!-- Aquí se cargarán los agentes con AJAX -->
                    <div class="contenedor-agentes" id="contenedor-agentes-{{ $institucion->CUECOMPLETO }}"></div>
                </div>
            @endforeach
        @endforeach
        <div class="modal fade" id="modal-pof">
            <div class="modal-dialog" >
              <div class="modal-content" style="width:950px" >
                <div class="modal-header">
                  <h4 class="modal-title"></h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                    <table id="tablapofs" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 50px">ID</th>
                                <th style="width: 120px">Descripción</th>
                                <th>Cargo POF</th>
                                <th style="text-align: lef;width: 400px">Aulas Asociadas</th>
                            </tr>
                        </thead>
                        <tbody class="cuerpo-pof">
                       
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer justify-content-between">
                    <div class="botonera" style="display: flex; gap:1rem; justify-content:left">
                        <button type="button" id="btn-imprimir" class="btn btn-primary">Imprimir</button>
                        <!-- Botón para exportar a Excel -->
                        <button type="button" id="btn-exportar" class="btn btn-success" data-nivel="nivel-ejemplo">Exportar a Excel</button>
                    </div>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>

                </div>
                
              </div>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
          </div>


          
          
