@extends('layout.app')

@section('Titulo', 'Sage2.0 - Información')

@section('ContenidoPrincipal')

<section id="container" >
    <section id="main-content">
        <section class="content-wrapper">
            <!-- Mensaje ALERTA -->
            <div class="alert alert-warning alert-dismissible">
                <input type="hidden" id="valCUE" name="valCUE" value="{{$infoInstitucion[0]->CUECOMPLETO}}">
                <h4><i class="icon fas fa-exclamation-triangle"></i> AVISO!</h4>
                <h5>
                Aquí se suben los adjuntos de la escuela<br>
                Nombre: <b>{{$infoInstitucion[0]->Nombre_Institucion}}</b> -
                CUE: <b>{{$infoInstitucion[0]->CUECOMPLETO}}</b>
                </h5>
            </div>

            <!-- /.fin row -->
            <div class="row">
                <!-- datos logo -->
                <div class="col-md-6">
                    <div class="card card-lightblue">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-book"></i>
                                Panel de Control - Subir Archivos de Novedades
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <!-- /.card-tools -->
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="container_archivos">
                                <!-- INICIO SUBIR DOC -->
                              <div class="card card-secondary">
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
                          </div>
                        </div>
                    </div>
                </div>
                <!-- /.fin m6-->

                <div class="col-md-6">
                    <div class="card card-lightblue">
                        <div class="card-header">
                            <h3 class="card-title">
                            <i class="fas fa-book"></i>
                            Panel de Control - Archivos Subidos</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <!-- /.card-tools -->
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
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
                <!-- /.fin m6-->                
            </div> 
            <!-- /.fin row -->
        </section>
    </section>
</section>


@endsection

@section('Script')
<script src="{{ asset('js/infolegajo.js') }}"></script>
<script>
    traerArchivos_origen();
</script>
<script src="{{ asset('js/funcionesvarias.js') }}"></script>

<script type="text/javascript" charset="utf-8">
    $(document).ready(function() {
        $('#example').dataTable( {
            "aaSorting": [[ 1, "asc" ]],
            "oLanguage": {
                "sLengthMenu": "Escuelas _MENU_ por pagina",
                "search": "Buscar:",
                "oPaginate": {
                    "sPrevious": "Anterior",
                    "sNext": "Siguiente"
                }
            }
        } );
    } );
</script>

 
@endsection