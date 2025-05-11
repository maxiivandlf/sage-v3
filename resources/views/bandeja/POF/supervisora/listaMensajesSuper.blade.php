@extends('layout.app')

@section('Titulo', 'Sage2.0 - Nuevo Usuario ADMIN')
@section('LinkCSS')
<style>
    .btn-app {
        border-radius: 3px;
    background-color: #f8f9fa;
    border: 1px solid #ddd;
    color: #6c757d;
    font-size: 12px;
    height: 60px;
    margin: 0 0 10px 10px;
    min-width: 80px;
    padding: 15px 5px;
    position: relative;
    text-align: center;
    }
    .custom-switch.custom-switch-off-danger .custom-control-input:checked ~ .custom-control-label::before {
        background-color: #28a745; /* Verde para el estado "on" */
    }
    .custom-switch.custom-switch-off-danger .custom-control-input:not(:checked) ~ .custom-control-label::before {
        background-color: #dc3545; /* Rojo para el estado "off" */
    }
 
    .custom-control-input:checked ~ .custom-control-label::before {
        transform: scale(1.5);
    }

    .custom-control-label::before {
        transform: scale(1.5);
        width: 3rem;
        height: 1.75rem;
    }

    .custom-control-label {
        font-size: 1.2rem;
    }
    /* Establecer el color verde al estar activado */
.btn-app.btn-success {
    background-color: #28a745 !important; /* Verde */
    border-color: #28a745 !important;
}

/* Evitar que el foco cambie el color del botón */
.btn-app:focus, .btn-app:active, .btn-app:focus-visible {
    box-shadow: none !important; /* Eliminar cualquier sombra */
    background-color: #28a745 !important; /* Verde cuando se hace foco */
    border-color: #28a745 !important; /* Asegurarse de que el borde sea verde */
}

/* Mantener el color cuando el botón está en estado inactivo */
.btn-app.btn-danger {
    background-color: #dc3545 !important; /* Rojo */
    border-color: #dc3545 !important;
}

/* Evitar que el foco cambie el color cuando está inactivo */
.btn-app.btn-danger:focus, .btn-app.btn-danger:active {
    background-color: #dc3545 !important;
    border-color: #dc3545 !important;
    box-shadow: none !important; /* Eliminar cualquier sombra */
}

/* Aplicar transición suave para el cambio de color */
.btn-app {
    transition: background-color 0.3s ease, border-color 0.3s ease;
}
</style>


@endsection
@section('ContenidoPrincipal')

<section id="container">
    <section id="main-content">
        <section class="content-wrapper">
                <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- Buscador Agente -->
                    @php
                        $infoModoZona = DB::table('tb_zonasupervision')->where('idZonaSupervision',$valorModo)->first();
                    @endphp
                    <h4 class="text-center display-4">Panel de Escuelas en POFMH</h4>
                    
                    <!-- Agregar Nuevo Agente -->
                   
                    <div class="row d-flex justify-content-center">
                        <div class="col-md-10">
                            <div class="card card-default">
                              <div class="card-header">
                                <h3 class="card-title">
                                  <i class="fas fa-bullhorn"></i>
                                  Mensajes a Resolver y Resueltos
                                </h3>
                              </div>
                              <!-- /.card-header -->
                              <div class="card-body">
                                @if($AlertasMensajes && count($AlertasMensajes) > 0)
                                    @foreach($AlertasMensajes as $mensaje)
                                        @php
                                            $infoInstitucion = DB::table('tb_institucion_extension')
                                                ->where('CUECOMPLETO', $mensaje->CUECOMPLETO)
                                                ->first();
                                        @endphp
                                
                                        @if($infoInstitucion && $mensaje->Estado === "Pendiente")
                                            <div class="callout callout-info">
                                                <h5>{{ $infoInstitucion->CUECOMPLETO }} - {{ $infoInstitucion->Nombre_Institucion }}</h5>
                                                <p>Registro Creado: {{ \Carbon\Carbon::parse($mensaje->created_at)->format('d-m-Y') }}</p>
                                                <p>Estado del Registro: <b>Pendiente</b></p>
                                                <p>Detalle: {{ $mensaje->Observaciones }}</p>
                                                <a href="{{ route('agregarNovedadParticular', $mensaje->idInstitucionExtension) }}" class="nav-link">
                                                    <i class="fas fa-bell"></i> Novedades Generales
                                                </a>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            
                                                                
                              </div>
                              <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                          </div>
                    </div>    
            </section>
            <!-- /.content -->
        </section>
    </section>
</section>
@endsection

@section('Script')
    <script src="{{ asset('js/funcionesvarias.js') }}"></script>

@endsection
