@extends('layout.app')

@section('Titulo', 'Sage2.0 - Novedades Particulares')
@section('LinkCSS')
<style>
.text-success {
    color: green;
}

.text-danger {
    color: red;
}
.form-row2{
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}


</style>
@endsection
@section('ContenidoPrincipal')
<section id="container" >
    <section id="main-content">
        <section class="content-wrapper">
            <!-- Mensaje ALERTA -->
            <div class="alert alert-warning alert-dismissible">
                <h4><i class="icon fas fa-exclamation-triangle"></i> AVISO!</h4>
                En esta sección se darán de alta novedades individuales, se irán agregando según la necesidad</b>
                <input type="hidden" id="valCUE" value="{{$InstitucionExtension->CUECOMPLETO}}">
            </div>
            <!-- Inicio Selectores -->
            @if(session('Modo')<=14)
                <div class="row">
                    <div class="col-12">
                        <div class="card card-lightblue">
                            <div class="card-header">
                                <h3 class="card-title">
                                <i class="fas fa-book"></i>
                                Panel de Carga de Novedades  - Inasistencias - Otros<br>
                                CUE: <b>{{session('CUECOMPLETO')}}</b><br>
                                @php
                                    //traigo todas las unidades encontradas para el cue
                                     //buscar los unid liq
                                $infoUnidLiq = DB::connection('DB8')->table('instarealiq')
                                ->where('instarealiq.CUEA',$InstitucionExtension->CUECOMPLETO)
                                ->groupBy('instarealiq.escu')
                                ->select('instarealiq.escu')
                                ->get();

                                
                                //dd($infoUnidLiq);
                                @endphp
                                Nombre: <b>{{$InstitucionExtension->Nombre_Institucion}} 
                                    <br>
                                    (<span style="color: yellow">Unidad de Liquidaciones Relacionadas al CUE: 
                                        @php
                                        if($infoUnidLiq->isEmpty()){
                                            echo 'S/D';
                                        }else{
                                         $liqText = '';
                                            foreach($infoUnidLiq as $unidliq){
                                                // Validación más explícita para asegurarse de que 'escu' no sea vacío ni nulo
                                                $liqText .= !empty($unidliq->escu) ? $unidliq->escu : 'S/D';
                                                $liqText .= " / ";  // Añadir un separador solo si no es el último
                                            }
                                            echo rtrim($liqText, ' / '); // Remueve el último " / "
                                        }
                                        @endphp
                                        </span>)</b>
                                    <br>
                                Mes: <b>{{ \Carbon\Carbon::now()->locale('es')->isoFormat('MMMM') }}</b><br>
                                Año: <b>{{ \Carbon\Carbon::now()->year }}</b><br>
                               
                                </h3>
                                
                                
                                
                            </div>
                            <!-- /.card-header -->
                            <form method="POST" action="{{ route('formularioNovedadParticular') }}" class="formularioNovedadParticular">
                                @csrf
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="FechaInicio">Fecha Desde</label>
                                            <input type="date" class="form-control" id="FechaInicio" name="FechaInicio" placeholder="Fecha Inicio" value="" required>
                                        </div>
                                        <div class="form-group" style="margin-left: 20px">
                                            <label for="FechaHasta">Fecha Hasta</label>
                                            <input type="date" class="form-control" id="FechaHasta" name="FechaHasta" placeholder="Fecha Hasta" value="" required>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="DNI">DNI del Agente"</label>
                                            <input type="text" class="form-control" id="DNI" name="DNI" placeholder="Ingrese DNI del Agente" value="">
                                            <input type="hidden" class="form-control" id="idpof" name="idpof" value="">
                                            <input type="hidden" class="form-control" id="datoCue" name="datoCUE" value="{{$InstitucionExtension->CUECOMPLETO}}">
                                            <input type="hidden" class="form-control" id="datoTurno" name="datoTurno" value="{{$InstitucionExtension->idTurnoUsuario}}">

                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="ApeNom" style="margin-right: 10px;">Apellido y Nombre</label>
                                            <div  style="margin-left: 20px; display: flex; align-items: center;">
                                                <input type="text" class="form-control" id="ApeNom" name="ApeNom" placeholder="Agente" value="" readonly>
                                                {{-- <a href="#modalAgente" class="btn btn-success" data-toggle="modal" title="Agregar Docente" data-target="#modalAgente" style="margin-left: 10px;" id="agenteBtn">
                                                    <i class="fas fa-search"></i>
                                                </a> --}}
                                            </div>
                                        </div>
                                        
                                        
                                        <div class="form-group" style="margin-left: 20px">
                                            <label for="TL">Tipo de Novedad </label>
                                            <select name="TipoNovedad" class="form-control custom-select" id="fTipoNovedad">
                                                @foreach($NovedadesExtras as $key => $o)
                                                    <option value="{{$o->idNovedadExtra}}">({{$o->tipo_novedad}})</option>
                                                @endforeach 
                                            </select>
                                        </div>
                                        
                                        <div class="form-group" style="margin-left: 20px">
                                            <label for="TL">Tipo de Condición></label>
                                            <select name="TipoCondicion" class="form-control custom-select" id="fTipoCondicion">
                                                @foreach($Condiciones as $key => $o)
                                                    <option value="{{$o->idCondicion}}">({{$o->Descripcion}})</option>
                                                @endforeach 
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="TL">Tipo de Motivo </label>
                                            <select name="TipoMotivo" class="form-control custom-select">
                                                @foreach($Motivos as $key => $o)
                                                <option value="{{$o->idMotivo}}" 
                                                    @if($o->idMotivo == 65) selected @endif>
                                                    ({{$o->Codigo}})-{{$o->Nombre_Licencia}}
                                                </option>                                                @endforeach 
                                            </select>
                                        </div>  
                                        <div class="form-group"  style="margin-left: 20px">
                                            <label for="obligaciones">obligaciones"</label>
                                            <input type="text" class="form-control" id="Obligaciones" name="Obligaciones" placeholder="Obligaciones" value="0" >
                                        </div>
                                        <div class="form-group col-3"  style="margin-left: 20px">
                                            <label for="CUPOF">CUPOF"</label>
                                            <input type="text" class="form-control" id="CUPOF" name="CUPOF" placeholder="CUPOF" value="" >
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Observacion">Observación</label><br>
                                        <textarea class="form-control" name="Observaciones" rows="5" cols="100%"></textarea>
                                    </div>
                                    
                                    
                                
                                </div>
                                <div class="card-footer bg-transparent">
                                    <button type="submit" class="btn btn-primary">Agregar</button>
                                </div>
                                
                            </form>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    
                </div>
            @endif 
            
            {{-- <div class="container-fluid">
                <div class="row">
                    <div class="">
                      <!-- Custom Tabs -->
                      <div class="card">
                        <div class="card-header d-flex p-0">
                            <h3 class="card-title p-3"><i class="fas fa-book"></i>
                                Panel de Control - Novedades Particulares - Mes Actual: {{\Carbon\Carbon::now()->locale('es')->isoFormat('MMMM') }}
                            </h3>
                          <ul class="nav nav-pills ml-auto p-2">
                            <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Tab 1</a></li>
                            <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab">Tab 2</a></li>
                            <li class="nav-item"><a class="nav-link" href="#tab_3" data-toggle="tab">Tab 3</a></li>
                            <li class="nav-item dropdown">
                              <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">
                                Dropdown <span class="caret"></span>
                              </a>
                              <div class="dropdown-menu">
                                <a class="dropdown-item" tabindex="-1" href="#">Action</a>
                                <a class="dropdown-item" tabindex="-1" href="#">Another action</a>
                                <a class="dropdown-item" tabindex="-1" href="#">Something else here</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" tabindex="-1" href="#">Separated link</a>
                              </div>
                            </li>
                          </ul>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                          <div class="tab-content">
                            <div class="tab-pane active" id="tab_1">
                              A wonderful serenity has taken possession of my entire soul,
                              like these sweet mornings of spring which I enjoy with my whole heart.
                              I am alone, and feel the charm of existence in this spot,
                              which was created for the bliss of souls like mine. I am so happy,
                              my dear friend, so absorbed in the exquisite sense of mere tranquil existence,
                              that I neglect my talents. I should be incapable of drawing a single stroke
                              at the present moment; and yet I feel that I never was a greater artist than now.
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="tab_2">
                              The European languages are members of the same family. Their separate existence is a myth.
                              For science, music, sport, etc, Europe uses the same vocabulary. The languages only differ
                              in their grammar, their pronunciation and their most common words. Everyone realizes why a
                              new common language would be desirable: one could refuse to pay expensive translators. To
                              achieve this, it would be necessary to have uniform grammar, pronunciation and more common
                              words. If several languages coalesce, the grammar of the resulting language is more simple
                              and regular than that of the individual languages.
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="tab_3">
                              Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                              Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
                              when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                              It has survived not only five centuries, but also the leap into electronic typesetting,
                              remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset
                              sheets containing Lorem Ipsum passages, and more recently with desktop publishing software
                              like Aldus PageMaker including versions of Lorem Ipsum.
                            </div>
                            <!-- /.tab-pane -->
                          </div>
                          <!-- /.tab-content -->
                        </div><!-- /.card-body -->
                      </div>
                      <!-- ./card -->
                    </div>
                    <!-- /.col -->
                  </div>
            </div> --}}
            <div class="container-fluid">
                <div class="row">
                    <div class="">
                        <div class="card card-lightblue">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-book"></i>
                                    Panel de Control - Novedades Generadas - Mes Actual: {{\Carbon\Carbon::now()->locale('es')->isoFormat('MMMM') }} 
                                    <br>CUE: <b>{{$InstitucionExtension->CUECOMPLETO}}</b><br>
                                @php
                                    //traigo todas las unidades encontradas para el cue
                                     //buscar los unid liq
                                $infoUnidLiq = DB::connection('DB8')->table('instarealiq')
                                ->where('instarealiq.CUEA',$InstitucionExtension->CUECOMPLETO)
                                ->groupBy('instarealiq.escu')
                                ->select('instarealiq.escu')
                                ->get();

                                
                                //dd($infoUnidLiq);
                                @endphp
                                Nombre: <b>{{$InstitucionExtension->Nombre_Institucion}} 
                                    <br>
                                    (<span style="color: yellow">Unidad de Liquidaciones Relacionadas al CUE: 
                                        @php
                                        if($infoUnidLiq->isEmpty()){
                                            echo 'S/D';
                                        }else{
                                         $liqText = '';
                                            foreach($infoUnidLiq as $unidliq){
                                                // Validación más explícita para asegurarse de que 'escu' no sea vacío ni nulo
                                                $liqText .= !empty($unidliq->escu) ? $unidliq->escu : 'S/D';
                                                $liqText .= " / ";  // Añadir un separador solo si no es el último
                                            }
                                            echo rtrim($liqText, ' / '); // Remueve el último " / "
                                        }
                                        @endphp
                                        </span>)</b>
                                    <br>
                                Mes: <b>{{ \Carbon\Carbon::now()->locale('es')->isoFormat('MMMM') }}</b><br>
                                Año: <b>{{ \Carbon\Carbon::now()->year }}</b><br>
                               
                                </h3>
                                
                            </div>
                            <div class="card-body">
                                @php
                                    $conteoTipos = [];
                                    $totalNovedades = 0;

                                    foreach ($Novedades as $n) {
                                        $tipo = $n->idNovedadExtra;
                                        if (!isset($conteoTipos[$tipo])) {
                                            $conteoTipos[$tipo] = 0;
                                        }
                                        $conteoTipos[$tipo]++;
                                        $totalNovedades++;
                                    }
                                @endphp
                                <div class="mb-3 d-flex flex-wrap align-items-center" style="gap: 10px 15px; margin-left: 20px;">
                                    <button class="btn btn-primary filtro-novedad" data-tipo="TODO">
                                        TODO <span class="badge badge-info right">({{$totalNovedades}})</span>
                                    </button>
                                    @foreach($NovedadesExtras as $key => $o)
                                        <button class="btn btn-secondary filtro-novedad" data-tipo="{{$o->idNovedadExtra}}">
                                            ({{$o->tipo_novedad}}) <span class="badge badge-info right">({{ $conteoTipos[$o->idNovedadExtra] ?? 0 }})</span>
                                        </button>
                                    @endforeach
                                </div>
                                <table id="example_novedad" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" style="text-align:center">Acciones</th>
                                            <th rowspan="2" style="text-align:center">Apellido y Nombres</th>
                                            <th rowspan="2" style="text-align:center">DNI</th>
                                            <th rowspan="2" style="text-align:center">Código Unico de Identificación de P.O.F. (CUPOF)</th>
                                            <th colspan="4" style="text-align:center">Fecha Novedad</th>
                                            <th rowspan="2" style="text-align:center">Obligaciones</th>
                                            <th rowspan="2" style="text-align:center">Ley/Dec/Art</th>
                                            <th rowspan="2" style="text-align:center">Condición</th>
                                            <th rowspan="2" style="text-align:center">Observaciones</th>
                                            <th rowspan="2" style="text-align:center">Adjuntos</th>
                                            <th rowspan="2" style="text-align:center">Supervisores</th>
                                            <th rowspan="2" style="text-align:center">Liquidación</th>
                                        </tr>
                                        <tr>
                                            <th style="text-align:center">Fecha Reg</th>
                                            <th style="text-align:center">Fecha Desde</th>
                                            <th style="text-align:center">Fecha Hasta</th>
                                            <th style="text-align:center">Total Días</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($Novedades as $key => $n)
                                            <tr class="{{$n->Estado}}" data-tipo-novedad="{{$n->idNovedadExtra}}">
                                                @php
                                                    $infoDocu = DB::table('tb_agentes')
                                                        ->where('Documento', $n->Agente)
                                                        ->first();
                                                    if($infoDocu){
                                                        $ApeNom = $infoDocu->ApeNom;
                                                        $Documento = $infoDocu->Documento;
                                                    }else{
                                                       
                                                        $ApeNom = 'Sin datos';
                                                        $Documento = 0;
                                                    }
                                                @endphp

                                                <td class="text-center">
                                                    {{-- <br>{{$n->idNovedad}} --}}
                                                    @if ($n->Supervisores !=1 )
                                                        <a href="javascript:void(0)" class="editar-novedad" data-id="{{ $n->idNovedad }}" data-dni="{{ $Documento }}" data-nombre="{{ $ApeNom }}" data-cuopf="{{ $n->CUPOF }}" data-fechainicio="{{ \Carbon\Carbon::parse($n->FechaDesde)->format('d-m-Y') }}" data-fechahasta="{{ \Carbon\Carbon::parse($n->FechaHasta)->format('d-m-Y') }}" data-obs="{{ $n->Observaciones }}" data-obligaciones="{{ $n->Obligaciones }}" data-tipocondicion="{{ $n->Condicion }}" data-tiponovedad="{{ $n->idNovedadExtra }}" data-tipomotivo="{{ $n->Motivo }}">
                                                            <i class="fas fa-edit"></i>
                                                        </a> | 
                                                        <a href="{{ route('eliminarNovedadParticular', $n->idNovedad) }}" style="color:red" class="btn-delete">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    @endif
                                                </td>

                                                @if ($infoDocu)
                                                    <td>{{$infoDocu->ApeNom}}</td>
                                                    <td>{{$infoDocu->Documento}}
                                                    </td>
                                                @else
                                                    <td>Sin datos</td>
                                                    <td>Sin datos</td>
                                                @endif
            
                                                <td class="text-center">{{($n->CUPOF)?$n->CUPOF:'Sin Datos'}}</td>
                                                <td class="text-center">{{ \Carbon\Carbon::parse($n->created_at)->format('d-m-Y')}}</td>
                                                <td class="text-center">{{ \Carbon\Carbon::parse($n->FechaDesde)->format('d-m-Y')}}</td>
                                                <td class="text-center">{{ \Carbon\Carbon::parse($n->FechaHasta)->format('d-m-Y')}}</td>
                                                <td class="text-center">{{($n->TotalDiasLicencia)?$n->TotalDiasLicencia:'1'}}</td>
                                                <td class="text-center">{{($n->Obligaciones)?$n->Obligaciones:'0'}}</td>
                                                @php
                                                    $infoNovedadExtra = DB::table('tb_novedades_extras')
                                                    ->where('tb_novedades_extras.idNovedadExtra', $n->idNovedadExtra)
                                                    ->first();

                                                    //consulto su motivo
                                                    $infoMotivo = DB::table('tb_motivos')
                                                    ->where('tb_motivos.idMotivo', $n->Motivo)
                                                    ->first();

                                                    $infoCondicion = DB::connection('DB7')->table('tb_condiciones')
                                                    ->where('idCondicion', $n->Condicion)
                                                    ->first();
                                                @endphp
                                               
                                                <td class="text-center">{{$infoMotivo->Codigo}}-{{$infoMotivo->Nombre_Licencia}}</td>
                                                <td class="text-center">{{$infoCondicion->Descripcion?$infoCondicion->Descripcion:"Sin Datos"}}</td>
                                                <td>{{$infoNovedadExtra->tipo_novedad}}: {{$n->Observaciones}}</td>
                                                <td>
                                                    <button type="button" class="btn btn-default view-novedades" data-toggle="modal" data-target="#modal-novedades" data-id="{{ $n->idNovedad }}" data-agente={{$n->Agente}}>
                                                      <i class="fas fa-newspaper"></i>
                                                    </button>
                                                    @php
                                                       // data-id="{{ $n->idPofmh }}"
                                                    @endphp
                                                  </td>
                                                <td class="text-center" style="display: flex; flex-direction: column; align-items: center; gap: 5px;">
                                                    @if($n->Supervisores > 0 || $n->Supervisores != null)
                                                        <i class="fas fa-check-circle text-success" id="supervisor-icon-{{$n->idNovedad}}"></i>
                                                    @else
                                                        <i class="fas fa-times-circle text-danger" id="supervisor-icon-{{$n->idNovedad}}"></i>
                                                    @endif
                                                    @if (!empty($n->ObservacionesSuper))
                                                        <label>Mensaje:</label>
                                                        @if ($n->Supervisores==1)
                                                            <p class="mensaje-super" style="color:green">{{ $n->ObservacionesSuper }}</p>
                                                        @else
                                                            <p class="mensaje-super" style="color:red">{{ $n->ObservacionesSuper }}</p>
                                                        @endif
                                                    @endif
                                                    @php
                                                        $primerosCuatro = substr(session('CUECOMPLETO'), 0, 4);
                                                    @endphp
                                                    
                                                    @if(session('Modo') >= 14 || $primerosCuatro == '8000')
                                                        <div class="acciones-super">
                                                            <textarea class="form-control" name="nota_super" rows="5" cols="100%">{{ $n->ObservacionesSuper ?? '' }}</textarea>
                                                            
                                                            <button data-id="{{ $n->idNovedad }}" class="btn btn-success confirmar w-100 mt-2">
                                                                Confirmar
                                                            </button>
                                                            
                                                            <button data-id="{{ $n->idNovedad }}" class="btn btn-warning rechazar w-100 mt-2">
                                                                Pendiente
                                                            </button>

                                                            
                                                        </div>
                                                    @endif
                                                    {{-- Mostrar fecha solo si existe, pero reservar el espacio --}}
                                                    <div class="mt-2" style="min-height: 24px;">
                                                        @if (!empty($n->FechaObservacionSuper))
                                                            <small>Control: <span style="color:green">{{ \Carbon\Carbon::parse($n->FechaObservacionSuper)->format('d-m-Y') }}</span></small>
                                                        @else
                                                            {{-- Esto mantiene el espacio aunque no haya fecha --}}
                                                            <small style="visibility: hidden;">Control: 00-00-0000</small>
                                                        @endif
                                                    </div>
                                                    
                                                </td>
                                                <td class="text-center">
                                                    @if($n->Liquidacion > 0 || $n->Liquidacion != null)
                                                        <i class="fas fa-check-circle text-success"></i>
                                                    @else
                                                        <i class="fas fa-times-circle text-danger"></i>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
         
            
        </section>
    </section>
</section>
<div class="modal fade" id="modal-novedades">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Panel de Novedades</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="col-12">
            <div class="card">
              <div class="card-header d-flex p-0">
                <h3 class="card-title p-3">Panel de Adjuntos</h3>
                <ul class="nav nav-pills ml-auto p-2">
                  <li class="nav-item">
                    <a class="nav-link" href="#tab_3" data-toggle="tab">
                        <i class="fas fa-upload"></i> Subir Documentación
                    </a>
                  </li>
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
 
                  <!-- /.tab-pane -->
                  <div class="tab-pane active" id="tab_3">
                    <div class="container_archivos"  style="display: flex; gap:1rem;">
                          <!-- INICIO SUBIR DOC -->
                        <div class="card card-secondary col-6">
                          <div class="card-header">
                              <h3 class="card-title">Subir Documentos <small><em></em></small></h3>
                          </div>
                          <div class="card-body" >
                              <div id="actions" class="row">
                                  <div class="">
                                      <div class="btn-group w-100" >
                                          <span class="btn btn-success fileinput-button">
                                              <i class="fas fa-plus"></i>
                                              Agregar
                                          </span>                        
                                      </div>
                                  </div>
                                  <div class="col-lg-6 d-flex align-items-center">
                                      <div class="fileupload-process w-100">
                                          <div id="total-progress" class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                                              <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              <div class="table table-striped files" id="previews">
                                  <div id="template2" class="row mt-2">
                                      <div class="col-auto">
                                          <span class="preview"><img src="data:," alt="" data-dz-thumbnail /></span>
                                      </div>
                                      <div class="col d-flex align-items-center">
                                          <p class="mb-0">
                                              <span class="lead" data-dz-name></span>
                                              (<span data-dz-size></span>)
                                          </p>
                                          <strong class="error text-danger" data-dz-errormessage></strong>
                                      </div>
                                      <div class="col-4 d-flex align-items-center">
                                          <div class="progress progress-striped active w-100" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                                              <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                                              
                                          </div>
                                      </div>
                                      <div class="col-auto d-flex align-items-center">
                                          <div class="btn-group">
                                              <button class="btn btn-primary start" title="Enviar Archivo">
                                                  <i class="fas fa-upload"></i>
                                              </button>
                                              <button data-dz-remove class="btn btn-warning cancel"  title="Cancelar Subida">
                                                  <i class="fas fa-times-circle"></i>
                                              </button>
                                              <button data-dz-remove class="btn btn-danger delete"  title="Borrar Envio">
                                                  <i class="fas fa-trash"></i>
                                              </button>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                          <!-- /.card-body -->
                          <div class="card-footer" id="upload-status">                                  
                              <!-- Aquí se mostrarán los mensajes de estado o errores de la carga de archivos -->
                          </div>
                      </div>
                      <!-- /.card -->
                        <table id="example3" class="table table-bordered table-striped">
                          <thead>
                              <tr>
                                  <th style="text-align:center">Archivo</th>
                                  <th style="text-align:center">Fecha Alta</th>
                                  <th style="text-align:center">Acciones</th>
                              </tr>
                          </thead>
                          <tbody id="modalBody">
                                                            
                          </tbody>
                        </table>
                    </div>
                    
                  </div>
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            
            <!-- /.card -->
        </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar Panel</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

  <div class="modal fade" id="modalAgente">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <div class="modal-title">
            <h4 class="modal-title">Buscar Agente</h4>
            <h6 class="">CUE:<b>{{ session('CUECOMPLETO') }}</b></h6>
          </div>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <div class="card card-olive">
            <div class="card-header">
              <div class="form-inline">
                <label class="col-auto col-form-label">Lista de Agentes: </label>
                <input type="text" autocomplete="off" class="form-control form-control-sm col-5" id="buscarAgente" placeholder="Ingrese DNI sin Puntos" value="">
                <button class="btn btn-sm btn-info form-control" type="button" id="traerAgentes" onclick="getAgentes()">Buscar
                    <i class="fa fa-search ml-2"></i>
                </button>
              </div>
            </div>
              <!-- /.card-header -->
            <div class="card-body">
              <table id="examplex" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>RegID</th>
                        <th>APELLIDO Y NOMBRE</th>
                        <th>DNI</th>
                        <th>Horas</th>
                        <th>SitRev</th>
                        <th>Cargo</th>
                        <th>Esp.Cur</th>
                        <th>Condición</th>
                        <th>Activo</th>
                        <th>OPCIONES</th>
                    </tr>
                </thead>
                <tbody id="contenidoAgentes">
                
                </tbody>
              </table>
            </div>
              <!-- /.card-body -->
          </div>
        </div>
        <div class="modal-footer justify-content-end">
            <button type="button" class="btn bg-olive"  data-dismiss="modal">Salir</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
      <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->


  <div class="modal fade" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarLabel">Editar Novedad</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('editarNovedadParticular') }}" class="formulario-editar">
                    @csrf
                    <div class="form-row2">
                        <div class="form-group">
                            <label for="dni">DNI del Agente</label>
                            <input type="text" class="form-control dni" name="DNI" disabled>
                        </div>
                        <div class="form-group">
                            <label for="nombre">Apellido y Nombre</label>
                            <input type="text" class="form-control nombre" name="ApeNom" disabled>
                        </div>
                        <div class="form-group">
                            <label for="cuopf">CUPOF</label>
                            <input type="text" class="form-control cuopf" name="CUPOF">
                        </div>
                    
                        <div class="form-group">
                            <label for="fechaInicio">Fecha Desde</label>
                            <input type="date" class="form-control fechaInicio" name="FechaInicio">
                        </div>
                        <div class="form-group">
                            <label for="fechaFin">Fecha Hasta</label>
                            <input type="date" class="form-control fechahasta" name="FechaHasta">
                        </div>
                    
                    </div>    
                    <div class="form-group">
                        <label for="TL">Tipo de Novedad </label>
                        <select name="modTipoNovedad" class="form-control custom-select modTipoNovedad" id="modTipoNovedad">
                            @foreach($NovedadesExtras as $key => $o)
                                <option value="{{$o->idNovedadExtra}}">({{$o->tipo_novedad}})</option>
                            @endforeach 
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="TL">Tipo de Condición></label>
                        <select name="modTipoCondicion" class="form-control custom-select modTipoCondicion" id="modTipoCondicion">
                            @foreach($Condiciones as $key => $o)
                                <option value="{{$o->idCondicion}}">({{$o->Descripcion}})</option>
                            @endforeach 
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="TL">Tipo de Motivo </label>
                        <select name="modTipoMotivo" class="form-control custom-select modTipoMotivo">
                            @foreach($Motivos as $key => $o)
                                <option value="{{$o->idMotivo}}">({{$o->Codigo}})-{{$o->Nombre_Licencia}}</option>
                            @endforeach 
                        </select>
                    </div> 
                    
                    <div class="form-group">
                        <label for="modObligaciones">Obligaciones</label>
                        <input type="number" min="0" class="form-control modObligaciones" name="modObligaciones">
                    </div>
                    <div class="form-group">
                        <label for="modobservaciones">Observaciones</label>
                        <textarea class="form-control modobservaciones" name="Observaciones" rows="3"></textarea>
                    </div>
                    <input type="hidden" name="modidNovedad" class="modidNovedad">
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('Script')

<script src="{{ asset('js/pofmh_novedad.js') }}"></script>
    <script type="text/javascript" charset="utf-8">
        $(document).ready(function() {
            $('#example_novedad').dataTable( {
                "aaSorting": [[ 13, "asc" ]],
                "columnDefs": [
                    { "targets": 13, "orderable": true } 
                ],
                "oLanguage": {
                    "sLengthMenu": "Escuelas _MENU_ por pagina",
                    "search": "Buscar:",
                    "oPaginate": {
                        "sPrevious": "Anterior",
                        "sNext": "Siguiente"
                    }
                },
            "responsive": true, 
            "lengthChange": true, 
            "autoWidth": false,
            "dom": 'lBfrtip',
                "buttons": [
            {
                extend: "copy",
                text: "Copiar"
            },
            {
                extend: "csv",
                text: "CSV"
            },
            {
                extend: "excel",
                text: "Excel"
            },
            {
                extend: "pdf",
                text: "PDF"
            },
            {
                extend: "print",
                text: "Imprimir"
            },
            {
                extend: "colvis",
                text: "Visibilidad de Columnas"
            }
        ]
            } );
        } );
  </script>


<script src="{{ asset('js/funcionesvarias.js') }}"></script>
        @if (session('ConfirmarEditarNovedadParticular')=='OK')
            <script>
            Swal.fire(
                'Registro guardado',
                'Se actualizó correctamente',
                'success'
                    )
            </script>
        @endif
    <script>

    
    
$('.formularioNovedadParticular').submit(function(e){
        e.preventDefault();
        var dniInput = $('#DNI');
        var dni = dniInput.val(); 
        var isDniRequired = dniInput.prop('required');  
        var isDniDisabled = dniInput.prop('disabled'); 
        
        if (!isDniDisabled && isDniRequired && !dni) {
            // Muestra un mensaje de error si el input está vacío y es requerido
            Swal.fire({
                title: 'Error',
                text: 'El campo DNI debe estar completo.',
                icon: 'error'
            });
            return; // Detiene el proceso de envío del formulario
        }
        var tipoNovedad = parseInt($('#fTipoNovedad').val());
        var tipoCondicion = $('#fTipoCondicion').val();
        // Validar que si el TipoNovedad es 1, 2, 5, 6 o 15, se haya seleccionado una condición
        var tiposQueRequierenCondicion = [1, 2, 5, 6, 15];
        if (tiposQueRequierenCondicion.includes(tipoNovedad) && (!tipoCondicion || tipoCondicion === '')) {
            Swal.fire({
                title: 'Error',
                text: 'Debe seleccionar una condición para este tipo de novedad.',
                icon: 'error'
            });
            return;
        }
        Swal.fire({
            title: '¿Está seguro de querer agregar una novedad para el Agente?',
            text: "Recuerde colocar datos verdaderos",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, guardo el registro!'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });
</script>
 <script src="{{ asset('js/funcionesvarias.js') }}"></script>
        @if (session('ConfirmarEliminarDivision')=='OK')
            <script>
            Swal.fire(
                'Registro Eliminado Exitosamente',
                'Se desvinculó correctamente',
                'success'
                    )
            </script>
        @endif
        
         @if (session('ConfirmarNuevaNovedadParticular')=='OK')
            <script>
            Swal.fire(
                'Novedades',
                'Se agrego una novedad con éxito',
                'success'
                    )
            </script>
         @endif
         @if (session('errorMensaje')=='OK')
         <script>
         Swal.fire(
             'Alerta',
             'El DNI no existe en la base de datos',
             'error'
                 )
         </script>
      @endif

    <script>
 $(document).ready(function () {
    $('#DNI').on('input', function () {
        var dni = $(this).val().trim();

        if (dni.length >= 7 && /^\d+$/.test(dni)) {
            $.ajax({
                type: 'POST',
                url: '/buscar_agente',
                data: { dni: dni },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content")
                },
                success: function (response) {
                    // Esto solo funcionará si la respuesta es JSON puro
                    console.log("✅ Respuesta exitosa:", response);
                    $('#ApeNom').val(response.msg ?? "--");
                },
                error: function (xhr) {
                    try {
                        const texto = xhr.responseText;
                        const jsonInicio = texto.indexOf('{');
                        const jsonTexto = texto.substring(jsonInicio);
                        const response = JSON.parse(jsonTexto);

                        console.warn("⚠️ Se reparó respuesta con HTML inyectado.");
                        $('#ApeNom').val(response.msg ?? "--");
                    } catch (e) {
                        // console.error("❌ Error en AJAX:");
                        // console.error("Código de estado HTTP:", xhr.status);
                        // console.error("Respuesta completa:", xhr.responseText);
                        // $('#ApeNom').val("Error al consultar");
                    }
                }
            });
        } else {
            $('#ApeNom').val("DNI inválido");
        }
    });
});



        document.getElementById('DNI').addEventListener('input', function(event) {
            // Remover puntos y comas en tiempo real
            this.value = this.value.replace(/[.,]/g, '');
        });
    </script>

<script>
function validarFecha() {
        var fechaInput = document.getElementById('FechaInicio').value;
        var regex = /^\d{4}-\d{2}-\d{2}$/;
        if (!regex.test(fechaInput)) {
            //alert('Formato de fecha inválido. Por favor, ingrese una fecha válida en el formato YYYY-MM-DD.');
            document.getElementById('FechaInicio').focus();
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "Formato de fecha inválido. Por favor, ingrese una fecha válida en el formato Día-Mes-Año",
  
            });
            return false; // Retorna false si el formato de fecha es inválido
        }
  
        // Dividir la fecha en sus componentes
        var partesFecha = fechaInput.split("-");
        var año = parseInt(partesFecha[0]);
        var mes = parseInt(partesFecha[1]);
        var dia = parseInt(partesFecha[2]);
  
        // Verificar si el año es válido (entre 1000 y 9999)
        if (año < 1000 || año > 9999) {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "Año inválido. Por favor, ingrese un año válido entre 1000 y 9999",
  
            });
            return false;
        }
  
        // Verificar si el mes es válido (entre 1 y 12)
        if (mes < 1 || mes > 12) {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "Mes inválido. Por favor, ingrese un mes válido entre 01 y 12",
  
            });
            return false;
        }
  
        // Verificar si el día es válido
        var diasEnMes = new Date(año, mes, 0).getDate();
        if (dia < 1 || dia > diasEnMes) {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "Día inválido para el mes y año especificados. Por favor, ingrese un día válido",
  
            });
            return false;
        }
  
        // Si pasa todas las validaciones, retorna true
        return true;
    }
    function validarFecha2() {
        var fechaInput = document.getElementById('FechaHasta').value;
        var regex = /^\d{4}-\d{2}-\d{2}$/;
        if (!regex.test(fechaInput)) {
            //alert('Formato de fecha inválido. Por favor, ingrese una fecha válida en el formato YYYY-MM-DD.');
            document.getElementById('FechaHasta').focus();
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "Formato de fecha inválido. Por favor, ingrese una fecha válida en el formato Día-Mes-Año",
  
            });
            return false; // Retorna false si el formato de fecha es inválido
        }
  
        // Dividir la fecha en sus componentes
        var partesFecha = fechaInput.split("-");
        var año = parseInt(partesFecha[0]);
        var mes = parseInt(partesFecha[1]);
        var dia = parseInt(partesFecha[2]);
  
        // Verificar si el año es válido (entre 1000 y 9999)
        if (año < 1000 || año > 9999) {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "Año inválido. Por favor, ingrese un año válido entre 1000 y 9999",
  
            });
            return false;
        }
  
        // Verificar si el mes es válido (entre 1 y 12)
        if (mes < 1 || mes > 12) {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "Mes inválido. Por favor, ingrese un mes válido entre 01 y 12",
  
            });
            return false;
        }
  
        // Verificar si el día es válido
        var diasEnMes = new Date(año, mes, 0).getDate();
        if (dia < 1 || dia > diasEnMes) {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "Día inválido para el mes y año especificados. Por favor, ingrese un día válido",
  
            });
            return false;
        }
  
        // Si pasa todas las validaciones, retorna true
        return true;
    }
    document.getElementById('FechaInicio').addEventListener('blur', validarFecha);
    document.getElementById('FechaHasta').addEventListener('blur', validarFecha2);
</script>
<script>
$(document).ready(function() {
    $('#example_novedad').on('click', '.confirmar', function() {
        const $btn = $(this);
        const idNovedad = $btn.data('id');
        const $row = $btn.closest('tr');
        const $textarea = $row.find('textarea[name="nota_super"]');
        const observacion = $textarea.val();

        $.ajax({
            url: '/novedad_cambiar_estado_confirmar',
            method: 'POST',
            data: {
                idNovedad: idNovedad,
                Observacion: observacion,
                _token: '{{ csrf_token() }}',
            },
            success: function(response) {
                if (response.success) {
                    $('#supervisor-icon-' + idNovedad)
                        .removeClass('fa-times-circle text-danger')
                        .addClass('fa-check-circle text-success');

                    const $td = $btn.closest('td');
                    let $mensaje = $td.find('p.mensaje-super');
                    if ($mensaje.length > 0) {
                        $mensaje.text(observacion);
                    } else {
                        $td.find('label:contains("Mensaje:")').remove();
                        $td.prepend(`<label>Mensaje:</label><p class="mensaje-super" style="color:red">${observacion}</p>`);
                    }

                    Swal.fire({
                        icon: "success",
                        title: "Genial",
                        text: "Se marcó como Aprobado",
                    });
                } else {
                    alert('Hubo un error al cambiar el estado.');
                }
            },
            error: function() {
                alert('Hubo un error al intentar actualizar el estado.');
            }
        });
    });
});

/*
$(document).ready(function() {
    $('.rechazar').click(function() {
        var idNovedad = $(this).data('id'); // Obtiene el id de la novedad desde el botón
        var notaSuper = $('textarea[name="nota_super"]').val().trim(); // Obtiene el valor del textarea y elimina espacios extras

        // Validar si el textarea está vacío
        if (notaSuper === '') {
            Swal.fire({
                    icon: "error",
                    title: "Controlar?",
                    text: "Debe ingresar datos para poder ser enviados",
        
                    });
            return; // Evita que se realice la solicitud AJAX si el campo está vacío
        }else{

            console.log("rechazar " + idNovedad);
            // Hacer la solicitud AJAX al controlador
            $.ajax({
                url: '/novedad_cambiar_estado_rechazar', // Ruta a la que enviamos la solicitud
                method: 'POST',
                data: {
                    idNovedad: idNovedad,
                    _token: '{{ csrf_token() }}', // Asegura el CSRF Token
                },
                success: function(response) {
                    if (response.success) {
                        // Si la actualización fue exitosa, actualizamos el ícono y el estado
                        
                        $('#supervisor-icon-' + idNovedad)
                                    .removeClass('fa-times-circle text-success')
                                    .addClass('fa-check-circle text-danger');

                    } else {
                        alert('Hubo un error al cambiar el estado.');
                    }
                },
                error: function() {
                    alert('Hubo un error al intentar actualizar el estado.');
                }
            });
        }
        });
    
});
*/
$(document).ready(function() {
           // Función para cargar las novedades al cambiar el select
           function cargarNovedadesModal(){
            console.log("cambiando")
            // Cuando cambie el valor del primer select (Tipo de Novedad)
            $('#modTipoNovedad').change(function() {
                var tipoNovedadId = $(this).val();  // Obtener el valor seleccionado
    
                // Verificar que se haya seleccionado un valor válido
                if (tipoNovedadId) {
                    // Realizar la solicitud AJAX para obtener las condiciones
                    $.ajax({
                        url: '/condiciones/' + tipoNovedadId,  // Llamar a la ruta que hemos definido en Laravel
                        type: 'GET',
                        success: function(data) {
                            // Limpiar el segundo select antes de agregar nuevas opciones
                            $('#modTipoCondicion').empty();
    
                            // Verificar si hay condiciones
                            if (data.length > 0) {
                                // Agregar un "Seleccione" predeterminado
                                $('#modTipoCondicion').append('<option value="">Seleccione Condición</option>');
    
                                // Llenar el select con las nuevas opciones
                                $.each(data, function(key, value) {
                                    $('#modTipoCondicion').append('<option value="' + value.idCondicion + '">' + value.Descripcion + '</option>');
                                });
                            } else {
                                // Si no hay condiciones, mostrar un mensaje
                                $('#modTipoCondicion').append('<option value="">No hay condiciones disponibles</option>');
                            }
                        }
                    });
                } else {
                    // Si no se ha seleccionado un tipo de novedad, limpiar el select
                    $('#modTipoCondicion').empty();
                    $('#modTipoCondicion').append('<option value="">Seleccione Condición</option>');
                }
            });
        }
    

        $(document).on('click', '.editar-novedad', function() {
        // Obtener los datos del botón
        var id = $(this).data('id');
       
        var dni = $(this).data('dni');
        var nombre = $(this).data('nombre');
        var cuopf = $(this).data('cuopf');
        var fechaIni = $(this).data('fechainicio');
        var fechaFin = $(this).data('fechahasta');
        var obs = $(this).data('obs');
        var obligaciones = $(this).data('obligaciones');
        var tipocondicion = $(this).data('tipocondicion');
        var tiponovedad = $(this).data('tiponovedad');
        var tipomotivo = $(this).data('tipomotivo');

        // Convertir las fechas de d-m-Y a Y-m-d
        var fechaIniFormatted = formatDate(fechaIni);
        var fechaFinFormatted = formatDate(fechaFin);

        // Confirmar con SweetAlert
        Swal.fire({
            title: '¿Estás seguro de editar esta novedad?',
            text: 'Recuerda que puedes cambiar los datos.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, editar',
        }).then((result) => {
            if (result.isConfirmed) {
                // Rellenar el modal con los datos
                $('#modalEditar .dni').val(dni);
                $('#modalEditar .nombre').val(nombre);
                $('#modalEditar .cuopf').val(cuopf);
                $('#modalEditar .fechaInicio').val(fechaIniFormatted);
                $('#modalEditar .fechahasta').val(fechaFinFormatted);
                $('#modalEditar .modobservaciones').val(obs);
                $('#modalEditar .modObligaciones').val(obligaciones);
                $('#modalEditar .modTipoCondicion').val(tipocondicion);
               
                // Establecer el valor seleccionado para los selects
                 $('#modalEditar .modTipoNovedad').val(tiponovedad);  
                $('#modalEditar .modTipoCondicion').val(tipocondicion);  
                $('#modalEditar .modTipoMotivo').val(tipomotivo);  
                $('#modalEditar .modidNovedad').val(id);
                
                cargarNovedadesModal();
                // Mostrar el modal
                $('#modalEditar').modal('show');
                        // Ejecutar la función al cargar la página
        
                $('#modTipoNovedad').trigger('change');
            }
        });
    });
});
// Función para convertir la fecha de d-m-Y a Y-m-d
function formatDate(date) {
    var parts = date.split('-'); // Divide la fecha por el guion
    return parts[2] + '-' + parts[1] + '-' + parts[0]; // Devuelve en formato Y-m-d
}

$(document).ready(function() {
    $('.formulario-editar').submit(function(e) {
        e.preventDefault(); // Prevenir el envío del formulario

        // Mostrar SweetAlert de confirmación
        Swal.fire({
            title: '¿Estás seguro de que deseas guardar los cambios?',
            text: 'Una vez guardados, no podrás deshacer estos cambios.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, guardar',
        }).then((result) => {
            if (result.isConfirmed) {
                // Si el usuario confirma, enviamos el formulario
                this.submit(); // Enviar el formulario
            }
        });
    });
});

</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Ahora podemos estar seguros de que el DOM está completamente cargado
    const tipoNovedad = document.getElementById('fTipoNovedad');
    //const agenteBtn = document.getElementById('agenteBtn');

    /*tipoNovedad.addEventListener('change', function() {
        const tipoNovedadValue = this.value;
        if (tipoNovedadValue == "1") {
            agenteBtn.style.display = 'none';
        } else {
            agenteBtn.style.display = 'inline-block';
        }
    });*/

    // Verificar al cargar la página
    /*const tipoNovedadValue = tipoNovedad.value;
    if (tipoNovedadValue == "1") {
        agenteBtn.style.display = 'none';
    } else {
        agenteBtn.style.display = 'inline-block';
    }*/
});
</script>
<script>
    // Usamos jQuery para capturar el clic en el enlace
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();  // Evita que el enlace se siga automáticamente

        // Mostrar la alerta de confirmación
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡Esta acción no se puede deshacer!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Si el usuario confirma, redirigir a la ruta de eliminación
                window.location.href = $(this).attr('href');
            }
        });
    });
</script>

<script>
$(document).ready(function() {
    // Función para cargar las novedades al cambiar el select
    function cargarNovedades(){
        // Cuando cambie el valor del primer select (Tipo de Novedad)
        $('#fTipoNovedad').change(function() {
            var tipoNovedadId = $(this).val();  // Obtener el valor seleccionado

            // Verificar que se haya seleccionado un valor válido
            if (tipoNovedadId) {
                // Realizar la solicitud AJAX para obtener las condiciones
                $.ajax({
                    url: '/condiciones/' + tipoNovedadId,  // Llamar a la ruta que hemos definido en Laravel
                    type: 'GET',
                    success: function(data) {
                        // Limpiar el segundo select antes de agregar nuevas opciones
                        $('#fTipoCondicion').empty();

                        // Verificar si hay condiciones
                        if (data.length > 0) {
                            // Agregar un "Seleccione" predeterminado
                            $('#fTipoCondicion').append('<option value="">Seleccione Condición</option>');

                            // Llenar el select con las nuevas opciones
                            $.each(data, function(key, value) {
                                $('#fTipoCondicion').append('<option value="' + value.idCondicion + '">' + value.Descripcion + '</option>');
                            });
                        } else {
                            // Si no hay condiciones, mostrar un mensaje
                            $('#fTipoCondicion').append('<option value="">No hay condiciones disponibles</option>');
                        }
                    }
                });
            } else {
                // Si no se ha seleccionado un tipo de novedad, limpiar el select
                $('#fTipoCondicion').empty();
                $('#fTipoCondicion').append('<option value="">Seleccione Condición</option>');
            }
        });
    }

    // Ejecutar la función al cargar la página
    cargarNovedades();

    // También ejecuta la función para cargar las novedades si ya hay un valor seleccionado en "TipoNovedad"
    // Esto puede ser útil si el valor de "TipoNovedad" ya está preseleccionado al cargar la página
    $('#fTipoNovedad').trigger('change');
});

</script>
<script>
    
    $(document).ready(function () {
    // Delegación del evento para botones .rechazar dentro de la tabla
    $('#example_novedad').on('click', '.rechazar', function () {
        const $btn = $(this);
        const idNovedad = $btn.data('id');
        const $row = $btn.closest('tr');
        const $textarea = $row.find('textarea[name="nota_super"]');
        const observacion = $textarea.val();

        if (observacion.trim() === '') {
            Swal.fire({
                icon: "warning",
                title: "Oops...",
                text: "Por favor ingrese una observación",
            });
            return;
        }

        $.ajax({
            url: '/novedad_cambiar_estado_rechazar',
            type: 'POST',
            data: {
                idNovedad: idNovedad,
                Observacion: observacion,
                nota_super: observacion,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        icon: "success",
                        title: "Genial",
                        text: "Novedad marcada como pendiente",
                    });

                    // Refrescar elementos visuales en la fila
                    const $td = $btn.closest('td');
                    $textarea
                        .val(observacion)
                        .addClass('bg-warning text-dark');

                    $row.find('.estado').text('Pendiente'); // si usás esta clase

                    let $mensaje = $td.find('p.mensaje-super');
                    if ($mensaje.length > 0) {
                        $mensaje.text(observacion);
                    } else {
                        $td.find('label:contains("Mensaje:")').remove(); // evita duplicados
                        $td.prepend(`<label>Mensaje:</label><p class="mensaje-super" style="color:red">${observacion}</p>`);
                    }

                    // Cambiar ícono visual del estado del supervisor
                    $('#supervisor-icon-' + idNovedad)
                        .removeClass('fa-times-circle text-success')
                        .removeClass('fa-check-circle text-success')
                        .addClass('fa-check-circle text-danger');
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Hubo un error al actualizar la novedad",
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Error en la solicitud",
                });
            }
        });
    });
});



</script>
<<script>
    document.getElementById('fTipoNovedad').addEventListener('change', function() {
        var dniInput = document.getElementById('DNI');
        var apeNomInput = document.getElementById('ApeNom');
        var selectedValue = this.value;

        if (selectedValue == 11 || selectedValue == 12 || selectedValue == 16) {
            dniInput.disabled = true;  
            apeNomInput.disabled = true;
            dniInput.removeAttribute('required');  // Quita el atributo required de DNI
            apeNomInput.removeAttribute('required');  
        } else {
            dniInput.disabled = false;  
            apeNomInput.disabled = false;  
            dniInput.setAttribute('required', 'required');  // Añade el atributo required al DNI
            apeNomInput.setAttribute('required', 'required');
        }
    });

    $(document).ready(function() {
    var table = $('#example_novedad').DataTable();
    var tipoNovedadSeleccionado = 'TODO';

    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
        var row = table.row(dataIndex).node();
        var tipoFila = $(row).data('tipo-novedad');

        if (tipoNovedadSeleccionado === 'TODO') {
            return true;
        }

        return tipoFila == tipoNovedadSeleccionado;
    });

    $('.filtro-novedad').on('click', function() {
        tipoNovedadSeleccionado = $(this).data('tipo');
        table.draw();
    });
});
</script>
@endsection