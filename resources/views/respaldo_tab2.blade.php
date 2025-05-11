@foreach ($infoNodos as $key => $o)
                            <tr>
                              <td>
                                @if ($o->Nombres != "")
                                  <label id="DescripcionNombreAgente" class="mb-0">({{$o->idNodo}})<b class="text-success">{{$o->Nombres}}</b></label>
                                @else
                                  <label id="DescripcionNombreAgente" class="mb-0">({{$o->idNodo}})<b class="text-danger">VACANTE</b> </label>
                                @endif
                                <input type="hidden" name="idAgente" id="idAgente2" value="{{$o->idAgente}}">
                              </td>
                              <td>
                                <p class=""><label for="cargo" id="DescripcionCargo">{{$o->nomCargo}} - ({{$o->nomCodigo}})</p>
                                <input type="hidden" id="CargoSal2" name="CargoSal" value="{{$o->idCargo}}">
                              </td>
                              <td>
                                <p class="mb-0"><label for="CantidadHoras" id="CantidadHoras">{{$o->CantidadHoras}}</label></p>
                              </td>
                              <td>
                                <p class="mb-0"><label for="DescripcionEspCur" id="DescripcionEspCur">{{$o->nomAsignatura}}</label>
                                <input type="hidden" id="idEspCur2" name="idEspCur" value="{{$o->idAsignatura}}">
                              </td>
                              <td  class="bg-{{$o->nomSitRev}}" title="Detalle: {{$o->Observaciones}}">
                                <p class="mb-0">
                                  @foreach ($SituacionDeRevista as $sr)
                                    @if ($sr->idSituacionRevista == $o->idSituacionRevista)
                                      <label for="SituacionDeRevista" id="SituacionDeRevista">{{$sr->Descripcion}}</label>
                                    @endif
                                  @endforeach
                                </p>
                              </td>
                              <td>
                                <p class="mb-0"><label for="Fa" id="Fa">{{ \Carbon\Carbon::parse($o->FechaDeAlta)->format('d-m-Y')}}</label></p>
                              </td>
                              <td>
                                <p class="mb-0"> 
                                  @foreach($Divisiones as $key => $d)
                                    @if ($d->idDivision == $o->idDivision)
                                      <label for="idDivision" id="idDivision">{{$d->Descripcion}}<br>T: {{$d->DescripcionTurno}}</label>
                                    @endif 
                                  @endforeach
                                </p>
                              </td>
                              <td>
                                {{-- <a type="button" href="#" class="btn mx-1" data-toggle="tooltip" data-placement="top" title="Licencia">
                                  <span class="material-symbols-outlined pt-1">medical_services</span>
                                </a> --}}
                                <a  href="{{route('ActualizarNodoAgente',$o->idNodo)}}" class="btn mx-1 "  data-placement="top" title="Actualizar Docente"  >
                                  <span class="material-symbols-outlined pt-1" >edit_square</span>
                                </a>

                                <!--boton PRUEBA modal historial plaza-->
                                  {{-- @if ($o->PosicionSiguiente != "")
                                    <button type="button" data-toggle="modal" data-target="#modal-{{$o->PosicionSiguiente}}" class="btn mx-1 " >
                                      <span class="material-symbols-outlined pt-1" >history</span>
                                    </button>
                                  @else
                                                    
                                  @endif --}}

                                  {{-- @if ($o->PosicionSiguiente == "")
                                    <a href="{{route('agregaNodo',$o->idNodo)}}" class="btn mx-1 Vincular">
                                      <span class="material-symbols-outlined pt-1" data-toggle="tooltip" data-placement="top" title="Vincular">compare_arrows</span>
                                    </a>
                                  @endif --}}                    
                              </td>
                            </tr>
                            @endforeach


@while ($infoNodo->PosicionSiguiente != null || $infoNodo->PosicionSiguiente != "" )
        <!--primera Card-->
        <div class="ml-1">
            <div class="card shadow-lg bg-{{$SituacionRevista[0]->nomSitRev}}">
                <div class="card-title mt-4 d-flex justify-content-center">
                    {{-- $o->Nombres --}}
                    @if ($o != "")
                        <h5 id="DescripcionNombreAgente" class="mb-0">({{$infoNodo->idNodo}})Docente: NOMAGENTE </h5>
                    @else
                        <h5 id="DescripcionNombreAgente" class="mb-0">({{$infoNodo->idNodo}})Docente: <b>VACANTE</b> </h5>
                    @endif
                                            
                    <input type="hidden" name="idAgente" id="idAgente2" value="NOMIDADGETE">
                </div>
                <div class="card-body">
                    <p class="mb-0">Cargo/Función: <label for="cargo" id="DescripcionCargo">NOMCARGO - (NOMCODIGO)</label>
                        <input type="hidden" id="CargoSal2" name="CargoSal" value="NOMIDCARGO">
                    </p>
                    <p class="mb-0">Esp. Curricular: <label for="DescripcionEspCur" id="DescripcionEspCur">NOMASIG</label>
                        <input type="hidden" id="idEspCur2" name="idEspCur" value="NOMIDASIG">
                    </p>
                    <p class="mb-0">Sit.Rev: 
                    {{-- @foreach ($SituacionDeRevista as $sr)
                            @if ($sr->idSituacionRevista == $o->idSituacionRevista)
                                <label for="SituacionDeRevista" id="SituacionDeRevista">{{$sr->Descripcion}}</label>
                            @endif
                            @endforeach --}}
                    </p>
                    <p class="mb-0">Sala/Division/Año: 
                        {{-- @foreach($Divisiones as $key => $d)
                            @if ($d->idDivision == $o->idDivision)
                                <label for="idDivision" id="idDivision">{{$d->Descripcion}} - {{$d->DescripcionTurno}}</label>
                            @endif 
                        @endforeach --}}
                                                
                    </p>
                    <p class="mb-0">Horas: <label for="CantidadHoras" id="CantidadHoras">NOMCANTHORAS</label></p>
                    <p class="mb-0">Fecha de Alta(Res): <label for="Fa" id="Fa">{{ \Carbon\Carbon::parse($o->FechaDeAlta)->format('d-m-Y')}}</label></p>
                </div>
                
                <div class="card-footer">
                    {{-- <a type="button" href="#" class="btn mx-1" data-toggle="tooltip" data-placement="top" title="Licencia">
                        <span class="material-symbols-outlined pt-1">medical_services</span>
                        </a> --}}
                    <a  href="{{route('ActualizarNodoAgente',$infoNodo->idNodo)}}" class="btn mx-1 "  data-placement="top" title="Actualizar Docente"  >
                        <span class="material-symbols-outlined pt-1" >edit_square</span>
                    </a>
                    {{-- @if ($o->PosicionSiguiente == "")
                        <a href="{{route('agregaNodo',$o->idNodo)}}" class="btn mx-1 Vincular">
                            <span class="material-symbols-outlined pt-1" data-toggle="tooltip" data-placement="top" title="Vincular">compare_arrows</span>
                        </a>
                    @endif --}}
                </div>
            </div>
        </div>
        <!--Fin primera Card-->
  
@endwhile