@extends('layout.app')
@section('Titulo', 'Sage2.0 - Nivel Superior - CONVOCATORIA')
@section('ContenidoPrincipal')

@section('LinkCSS')
    {{-- para superior --}}
          <link rel="stylesheet" href="{{ asset('css/superior/tablallamado.css') }}">  
    <!--fin superior -->
@endsection
<section id="container" class="col-12">
    <section id="main-content">
        <section class="content-wrapper">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="alert alert-warning alert-dismissible text-danger">
                    <h4 class="text-center">
                        <i class="icon fas fa-exclamation-triangle"></i> AVISO!
                    </h4>
                     <h6 class="text-center">
                        Para Reclamos Según Disposición a Regirse en DGES N° 7/25, por favor comuníquese con la Comisión de Nivel Superior a través de:
                    </h6>
                    <p class="text-center">  
                      <i class="fas fa-envelope">  </i> comisionsuperior@educacionlarioja.com
                    </p>

                 </div>
            </div>
            
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
          
            <div class="table-wrapper">
                <div class="d-flex justify-content-between align-items-center mb-3 head">
                      <img src="{{asset('storage/superior/llamado/cabeceralom.png')}}" alt="Imagen" width="100%" height="auto" style="margin: 5px; border-radius: 5px; border: 1px solid #ccc; align: center;">
                </div>
                <table id="myTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">

                    <thead>
                        <tr>
                            <th>LOM</th>
                            <th>Zona</th>
                            <th>Institución</th>
                            <th>Carrera</th>
                            <th>Unidad / Cargo</th>
                                                               
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($llamados as $llamado)
                            <tr>
                                <td>
                                    @if($llamado->mes == null)     
                                        <a class="my-link" href="{{ asset('storage/superior/lom/'.$llamado->pdf) }}" onclick="abrirModal('{{ asset('storage/superior/lom/'.$llamado->pdf) }}'); return false;">
                                            VER LOM
                                        </a>                                                                            
                                    @else
                                        <a class="my-link" href="{{ asset('storage/superior/lom/'.$llamado->mes.'/'.$llamado->pdf) }}" onclick="abrirModal('{{ asset('storage/superior/lom/'.$llamado->mes.'/'.$llamado->pdf) }}'); return false;">
                                            VER LOM
                                        </a>
                                    @endif                                    
                                </td>
                            
                                <td>{{ $llamado->nombre_zona}}</td>
                                <td>{{ $llamado->nombre_instsup}}</td>
                                <td>{{ $llamado->nombre_carrera?$llamado->nombre_carrera:'Sin Información de Carrera'}}</td>
                                <td> 
                                    @if($llamado->mes == null)                                       
                                        <a href="#" onclick="abrirImagen('{{ asset('storage/superior/lom/'.$llamado->imglom) }}'); return false;">
                                            <img src="{{ asset('storage/superior/lom/'.$llamado->imglom) }}" alt="Imagen" style="width: 100px;">
                                        </a>  
                                    @else
                                        <a href="#" onclick="abrirImagen('{{ asset('storage/superior/lom/'.$llamado->mes.'/'.$llamado->imglom) }}'); return false;">
                                            <img src="{{ asset('storage/superior/lom/'.$llamado->mes.'/'.$llamado->imglom) }}" alt="Imagen" style="width: 100px;">
                                        </a>
                                    @endif    
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
                   <!-- Modal pdf -->
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
                <!-- Fin Modal pdf -->
                <!-- Modal imagen -->
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
@endsection