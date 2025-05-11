@extends('layout.app')
@section('Titulo', 'Sage2.0 - Nivel Superior - Convocatoria Docente')
@section('ContenidoPrincipal')

@section('LinkCSS')
    {{-- para superior --}}
    <!-- DataTables CSS -->
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css"> --}}

    <link rel="stylesheet" href="{{ asset('css/superior/tablallamado.css') }}">  
    <!--fin superior -->
@endsection
<section id="container" class="col-12">
    <section id="main-content">
        <section class="content-wrapper">
            <div class="d-flex justify-content-between align-items-center mb-3 head">                 
                <img src="{{asset('storage/superior/llamado/cabecllamado.jpg')}}" alt="Imagen" width="100%" height="auto" style="margin: 5px; border-radius: 5px; border: 1px solid #ccc; align: center;">
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="alert alert-warning alert-dismissible text-danger">
                    <h4 class="text-center">
                        <i class="icon fas fa-exclamation-triangle"></i> AVISO!
                    </h4>
                    <h6 class="text-center">
                        Para mayor información, por favor comuníquese con la Comisión de Nivel Superior a través de los siguientes medios:
                    </h6>
                    <p class="text-center">
                        <i class="fas fa-phone"></i> 3804453790/3 interno 5160<br>
                        <i class="fas fa-envelope"></i> comisionsuperior@educacionlarioja.com
                    </p>
                </div>                
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1>Convocatoria Docente Nivel Superior</h1>
                <div id="buttons-container"></div> <!-- Aquí aparecerán los botones de exportación -->
            </div>
            
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <div class="table-wrapper">
                <table id="myTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th style="width: 130px; min-width:130px;">N°</th>
                            <th>Inscripción</th>
                            <th>Zona</th>
                            <th>Institución</th>
                            <th>Carrera</th>
                            <th>Espacios Curriculares / Cargos</th>
                            <th>Perfil</th>
                            <th>Inicio</th>
                            <th>Cierre</th>
                            <th>Descripción</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        @foreach($llamados as $llamado)

                            @if ($llamado->idtb_tipoestado == 9 or $llamado->idtb_tipoestado == 8)                               
                                <tr>
                                    <td>
                                        {{ $llamado->idllamado }}                                      
                                    
                                      

                                    </td>

                                    <td>                                    
                                        @if($llamado->idtb_tipoestado == 9)                               
                                            <small class="badge badge-danger">{{ $llamado->nombre_tipoestado }}</small><br>                        
                                        @else                                   
                                            <small class="badge badge-success">{{ $llamado->nombre_tipoestado }}</small><br>
                                            <a class="my-link" href="{{ $llamado->url_form }}" onclick="abrirModal('{{ $llamado->url_form }}'); return false;">Inscribirme</a>
                                        @endif

                                        @if(session('Modo') == 12)                                         
                                        <select name="idtb_tipoestado" class="form-control tipoestado-select" data-id="{{ $llamado->idllamado }}">
                                            <option value="">Seleccione</option>
 
                                             @foreach($tipoestado as $tipo)
                                                 <option value="{{ $tipo->idtb_tipoestado }}">
                                                     {{ $tipo->nombre_tipoestado }}
                                                 </option>
                                             @endforeach
                                         </select>                                        
                                         @endif
                                    </td>
                        
                                    <td>{{ $llamado->nombre_zona }}</td>
                                    <td><h6><strong>{{ $llamado->nombre_instsup }}</strong></h6></td>
                                    <td><strong>{{ $llamado->nombre_carrera }}</strong></td>
                        
                                    <td class="espacio-columna">
                                        @if($llamado->espacios->isnotEmpty())
                                            @foreach($llamado->espacios as $espacio)
                                                <div class="espacio-row border-bottom py-2">
                                                    <div class="espacio-contenedor" style="max-width: 100%; overflow: hidden;">
                                                        <h6 class="espacio-cargo">
                                                            <strong>
                                                                {{$espacio['nombre_espacio'] }}                                                    
                                                            </strong>
                                                        </h6>
                                                        <span class="badge badge-verde-claro">
                                                            Horas Cátedra: 
                                                            {{$espacio['horacat_espacio'] . ' hs - ' . $espacio['nombre_situacion_revista'] 
                                                            }}                                                        
                                                        </span>
                                                        <span class="badge badge-turno">Turno: {{ $espacio['nombre_turno'] }}</span><br>
                                                        <span class="badge badge-horario">Horario: <br>
                                                            {{$espacio['horario_espacio'] }}                                                            
                                                        </span>
                                                        <span class="badge badge-periodo"> Periodo:
                                                            {{ ($espacio['nombre_periodo']?$espacio['nombre_periodo']:"Sin Información") }}
                                                        </span><br>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else                               
                                        @endif

                                        @if($llamado->cargos->isnotEmpty())
                                                @foreach($llamado->cargos as $espacio)
                                            
                                                    <div class="espacio-row border-bottom py-2">
                                                        <div class="espacio-contenedor" style="max-width: 100%; overflow: hidden;">
                                                            <h6 class="espacio-cargo">
                                                                <strong>
                                                                {{$espacio['nombre_cargo_secundario'] }}
                                                                </strong>
                                                            </h6>
                                                            <span class="badge badge-verde-claro">
                                                                Horas Cátedra: 
                                                                {{ ($espacio['horacat_cargo']?$espacio['horacat_cargo']:"sin  h") . ' hs - ' . $espacio['situacion_revista_cargo'] 
                                                                }}
                                                            </span><br>
                                                            <span class="badge badge-turno">Turno: 
                                                                {{ $espacio['turno_cargo'] }}
                                                            </span><br>
                                                            <span class="badge badge-horario">Horario: <br>
                                                                {{ ($espacio['horario_cargo']?$espacio['horario_cargo']:"sin horario") }}
                                                            </span><br>
                                                            <span class="badge badge-periodo"> Periodo:
                                                                {{ ($espacio['nombre_periodo']?$espacio['nombre_periodo']:"Sin Información") }}
                                                            </span><br>
                                                        </div>
                                                    </div>    
                                                @endforeach                                 
                                        @endif                                 
                                        
                                    </td>
                        
                                    <td class="perfil-columna">
                                        @if($llamado->cargos->isnotEmpty())
                                                @foreach($llamado->cargos as $espacio)
                                                    {{ $espacio['nombre_perfil'] }}<br> <hr>
                                                @endforeach   
                                                @else   
                                                @foreach($llamado->espacios as $espacio)
                                                    {{ $espacio['nombre_perfil'] }}<br> <hr>
                                                @endforeach                              
                                        @endif     
                                    </td>
                                
                                    <td class="fech">{{ \Carbon\Carbon::parse($llamado->fecha_ini)->format('d-m-Y H:i') }}</td>
                                    <td class="fech"><strong>{{ \Carbon\Carbon::parse($llamado->fecha_fin)->format('d-m-Y H:i') }}</strong></td>
                        
                                    <td>
                                        @if ($llamado->mes == null)
                                            <a href="#" onclick="abrirImagen('{{ asset('storage/superior/llamado/'.$llamado->nombre_img) }}'); return false;">
                                                <img src="{{ asset('storage/superior/llamado/'.$llamado->nombre_img) }}" alt="Imagen" style="width: 100px;">
                                            </a><br>
                                        @else
                                        <a href="#" onclick="abrirImagen('{{ asset('storage/superior/llamado/'.$llamado->mes.'/'.$llamado->nombre_img) }}'); return false;">
                                            <img src="{{ asset('storage/superior/llamado/'.$llamado->mes.'/'.$llamado->nombre_img) }}" alt="Imagen" style="width: 100px;">
                                        </a><br>
                                        @endif
                                    
                                        {{ $llamado->descripcion }}
                                    </td>
                                </tr>
                                    
                            @else
                            @if ($llamado->idtb_tipoestado !=2 or session('Modo') == 12)

                                <tr>
                                    <td>{{ $llamado->idllamado }}                                         
                                     
                                    </td>
                                    <td>
                                    
                                        @if($llamado->idtb_tipoestado == 9)                               
                                            <small class="badge badge-danger">{{ $llamado->nombre_tipoestado }}</small><br>                        
                                        @else                                   
                                            <small class="badge badge-success">{{ $llamado->nombre_tipoestado }}</small><br>
                                            <a class="my-link" href="{{ $llamado->url_form }}" onclick="abrirModal('{{ $llamado->url_form }}'); return false;">Inscribirme</a>
                                        @endif

                                        @if(session('Modo') == 12)  
                                        {{-- //crear un select con los tipos estados --}}                                         
                                        <select name="idtb_tipoestado" class="form-control tipoestado-select" data-id="{{ $llamado->idllamado }}">
                                            <option value="">Seleccione</option>

                                            @foreach($tipoestado as $tipo)
                                                <option value="{{ $tipo->idtb_tipoestado }}">
                                                    {{ $tipo->nombre_tipoestado }}
                                                </option>
                                            @endforeach
                                        </select>                                                                             
                                    @endif 
                                    </td>
                        
                                    <td>{{ $llamado->nombre_zona }}</td>
                                    <td><h6><strong>{{ $llamado->nombre_instsup }}</strong></h6></td>
                                    <td><strong>{{ $llamado->nombre_carrera }}</strong></td>
                        
                                    <td class="espacio-columna">
                                        @if($llamado->espacios->isnotEmpty())
                                            @foreach($llamado->espacios as $espacio)
                                                <div class="espacio-row border-bottom py-2">
                                                    <div class="espacio-contenedor" style="max-width: 100%; overflow: hidden;">
                                                        <h6 class="espacio-cargo">
                                                            <strong>
                                                                {{$espacio['nombre_espacio'] }}                                                                                                                 
                                                            </strong>
                                                        </h6>
                                                        <span class="badge badge-verde-claro">
                                                            Horas Cátedra: 
                                                            {{$espacio['horacat_espacio'] . ' hs - ' . $espacio['nombre_situacion_revista'] 
                                                            }}                                                        
                                                        </span>
                                                        <span class="badge badge-turno">Turno: {{ $espacio['nombre_turno'] }}</span><br>
                                                        <span class="badge badge-horario">Horario: <br>
                                                            {{$espacio['horario_espacio'] }}
                                                         
                                                        </span>
                                                        <span class="badge badge-periodo"> Periodo:
                                                            {{ ($espacio['nombre_periodo']?$espacio['nombre_periodo']:"Sin Información") }}
                                                        </span><br>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else                               
                                        @endif

                                        @if($llamado->cargos->isnotEmpty())
                                                @foreach($llamado->cargos as $espacio)
                                            
                                                    <div class="espacio-row border-bottom py-2">
                                                        <div class="espacio-contenedor" style="max-width: 100%; overflow: hidden;">
                                                            <h6 class="espacio-cargo">
                                                                <strong>
                                                                {{$espacio['nombre_cargo_secundario'] }}
                                                                </strong>
                                                            </h6>
                                                            <span class="badge badge-verde-claro">
                                                                Horas Cátedra: 
                                                                {{ ($espacio['horacat_cargo']?$espacio['horacat_cargo']:"sin  h") . ' hs - ' . $espacio['situacion_revista_cargo'] 
                                                                }}
                                                            </span><br>
                                                            <span class="badge badge-turno">Turno: 
                                                                {{ $espacio['turno_cargo'] }}
                                                            </span><br>
                                                            <span class="badge badge-horario">Horario: <br>
                                                                {{ ($espacio['horario_cargo']?$espacio['horario_cargo']:"sin horario") }}
                                                            </span><br>
                                                            <span class="badge badge-periodo"> Periodo:
                                                                {{ ($espacio['nombre_periodo']?$espacio['nombre_periodo']:"Sin Información") }}
                                                            </span><br>
                                                        </div>
                                                    </div>    
                                                @endforeach                                 
                                        @endif                                 
                                        
                                    </td>
                        
                                    <td class="perfil-columna">
                                        @if($llamado->cargos->isnotEmpty())
                                                @foreach($llamado->cargos as $espacio)
                                                    {{ $espacio['nombre_perfil'] }}<br> <hr>
                                                @endforeach   
                                                @else   
                                                @foreach($llamado->espacios as $espacio)
                                                    {{ $espacio['nombre_perfil'] }}<br> <hr>
                                                @endforeach                              
                                        @endif     
                                    </td>                                
                                    <td class="fech">{{ \Carbon\Carbon::parse($llamado->fecha_ini)->format('d-m-Y H:i') }}</td>
                                    <td class="fech"><strong>{{ \Carbon\Carbon::parse($llamado->fecha_fin)->format('d-m-Y H:i') }}</strong></td>                                                            
                                    <td>
                                        @if ($llamado->mes == null)
                                            <a href="#" onclick="abrirImagen('{{ asset('storage/superior/llamado/'.$llamado->nombre_img) }}'); return false;">
                                                <img src="{{ asset('storage/superior/llamado/'.$llamado->nombre_img) }}" alt="Imagen" style="width: 100px;">
                                            </a><br>
                                        @else
                                        <a href="#" onclick="abrirImagen('{{ asset('storage/superior/llamado/'.$llamado->mes.'/'.$llamado->nombre_img) }}'); return false;">
                                            <img src="{{ asset('storage/superior/llamado/'.$llamado->mes.'/'.$llamado->nombre_img) }}" alt="Imagen" style="width: 100px;">
                                        </a><br>
                                        @endif
                                    
                                        {{ $llamado->descripcion }}
                                    </td>
                                </tr>
                            @endif 
                            @endif 
                        @endforeach                       
                      
                    </tbody>
                </table>

                <!-- Modal formulario -->
                <div id="modalFormulario" style="display: none;" class="modal-overlay">
                    <div class="modal-content">
                        <button onclick="cerrarModal()" class="modal-close">✖</button>
                        <div class="iframe-container">
                            <iframe id="formularioIframe"
                                src=""
                                frameborder="0"
                                allowfullscreen>
                            </iframe>
                        </div>
                    </div>
                </div>

                <div id="modalImagen" style="display: none;" class="modal-overlay">
                    <div class="modal-content">
                        <button onclick="cerrarModalImg()" class="modal-close">✖</button>
                        <div class="image-container">
                            <img id="modalImg" src="" alt="Imagen del llamado" width="100%">
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </section>
</section>
@endsection
@section('Script')   
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script> --}}

    <!-- Librerías necesarias para exportación -->
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script> --}}
    <script src="{{ asset('js/superior/tablaLlamado.js') }}"></script>  
    <script>
        $(document).ready(function () {
            $('.tipoestado-select').on('change', function () {
                const idLlamado = $(this).data('id');
                const nuevoEstado = $(this).val();
        
                if (nuevoEstado === '') {
                    Swal.fire('Debes seleccionar un estado válido.', '', 'warning');
                    return;
                }
        
                $.ajax({
                    url: '/llamado/estado',
                    type: 'POST',
                    data: {
                        idllamado: idLlamado,
                        idtb_tipoestado: nuevoEstado,
                    },
                    headers: {
                                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                            },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire('Estado actualizado correctamente', '', 'success');
                        } else {
                            Swal.fire('No se pudo actualizar el estado', '', 'error');
                        }
                    },
                    error: function (xhr) {
                        console.error(xhr.responseText);
                        Swal.fire('Error en el servidor', '', 'error');
                    }
                });
            });
        });
        </script>
        
        
        
@endsection