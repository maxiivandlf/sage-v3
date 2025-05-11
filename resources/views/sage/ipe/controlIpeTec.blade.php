@extends('layout.app')

@section('Titulo', 'Sage2.0 - Nuevo Usuario ADMIN')
@section('LinkCSS')
<style>
.modal-custom-width {
    width: 90%;
    max-width: none;
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
                    {{--Conformidad--}}   
            <div class="row"> 
                        <div class="alert alert-danger">
                        <h4>CONFORMIDAD</h4>
                        <p>
                            *Advertencia:* La carga de datos en este sistema reviste el carácter de declaración jurada, realizada en cumplimiento de lo dispuesto por el Decreto N° 1556/24, las Resoluciones Ministeriales N° 983/24 y N° 328/25, y la Ley Provincial N° 9911. Asimismo, se recuerda que esta acción se encuentra sujeta a las disposiciones de la Ley Nacional N° 25.164 de Ética en la Función Pública, la Ley N° 25.326 de Protección de Datos Personales y la Ley N° 27.275 de Acceso a la Información Pública. El ingreso de información falsa o inexacta podrá acarrear sanciones administrativas y/o legales correspondientes.
                        </p>
                        </div>
                    </div>
                    <!-- Buscador Agente -->
                    <h4 class="text-center display-4">Lista de Agentes Eliminados </h4>
                    <!-- Agregar Nuevo Agente -->
                   
                    <div class="row d-flex justify-content-center">
                        <!-- left column -->
                        <div class="">
                            <div class="card card-lightblue">
                                <div class="card-header">
                                    <h3 class="">Control de IPE</h3>
                                    <h4>Escuela: {{$NombreInstitucion}}</h4>
                                    <h4>CUECOMPLETO: {{$CUECOMPLETO}}</h4>
                                    <h4>Unidades Relacionadas: {!! ($liqText ? '<span style="color:yellow">' . rtrim($liqText, ' / ') . '</span>' : '<span style="color:red">No se encontró unidad de liquidación</span>') !!}</h4>
                                    <h4>Mes Actual: {{$MesActual}}</h4>
                                    
                                </div>
                                {{--BUSCADOR--}}
                                <div class="container-fluid m-1">
                                    <form id="searchForm" class="row col-12">
                                    <div class="input-group">
                                        <input type="search" id="searchInput" class="form-control" placeholder="Buscar Apellido, Nombre o N° de DNI">
                                    </div>
                                    </form>
                                </div>
                              <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="tablacontrolIpe" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Registro</th>
                                                <th>DNI</th>
                                                <th>CUIL</th>
                                                <th>N&deg; de Trabajo</th>
                                                <th>Apellido y Nombre</th>
                                                <th>Unidad liquidación</th>
                                                <th>Situación Revista</th>
                                                <th>Código Salarial</th>
                                                <th>Area</th>
                                               
                                                {{-- <th> Pertenece a la Institución</th> --}}
                                                <th><i class="fas fa-cog"></i> Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $contador = 0; @endphp
                                            @foreach ($infoAgentes as $indice => $agente)
                                            <tr data-id="{{ $agente->idPofIpe }}">
                                                <td>{{ $contador }}</td>
                                                <td class="dni-input">{{ $agente->Documento }}</td>
                                                <td>{{ $agente->Cuil }}</td>
                                                <td class="text-center">{{ $agente->Trabajo }}</td>
                                                <td class="apenom-input">{{ $agente->ApeNom }}</td>
                                                <td class="text-center">{{ $agente->Escu }}</td>
                                                <td class="text-center">{{ $agente->Descripcion }}</td>
                                                <td class="text-center">{{ $agente->Codigo }}</td>
                                                <td class="text-center">{{ $agente->Area }}</td>
                                               
                                                <td class="text-center estado-validacion" id="estado_{{ $agente->idPofIpe }}" style="display:flex;justify-content: space-between;">
                                                    <span class="text-success check-validacion d-none" id="check_{{ $agente->idPofIpe }}">
                                                        <i class="fas fa-check-circle"></i>
                                                    </span>
                                                      |  
                                                    <button class="btn btn-warning btn-sm eliminar-agente_lista_negra"
                                                            data-idpof="{{ $agente->idPofIpe }}"
                                                            data-cue="{{ session('CUECOMPLETOBASE') }}">
                                                        <i class="fas fa-undo"></i>

                                                    </button>
                                                </td>
                                                
                                            </tr>
                                                @php $contador++; @endphp
                                            @endforeach
                                           
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>
                      
                    </div>  
                   <!-- Botón para exportar a Excel -->
                   <div class="row d-flex justify-content-center mt-3">
                    <button id="btn-exportar"
                            type="button"
                            class="btn btn-primary"
                            data-mes="{{ $MesActual }}"
                            data-liq="{{ rtrim($liqText, ' / ') }}"
                            data-cue="{{ $CUECOMPLETO }}">
                        Exportar a Excel el Control de IPE
                    </button>
                </div>  
            </section>
            <!-- /.content -->
        </section>
    </section>
</section>

<div class="modal fade" id="modalAgente">
    <div class="modal-dialog modal-custom-width">
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
                <button class="btn btn-sm btn-info form-control" type="button" id="traerAgentes" onclick="getAgentesIPE()">Buscar
                    <i class="fa fa-search ml-2"></i>
                </button>
              </div>
            </div>
              <!-- /.card-header -->
            <div class="card-body">
              <table id="examplex" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Registro</th>
                        <th>DNI</th>
                        <th>CUIL</th>
                        <th>N&deg; de Trabajo</th>
                        <th>Apellido y Nombre</th>
                        <th>SEXO</th>
                        <th>Zona</th>
                        <th>Unidad liquidación - Escuela Origen</th>
                        <th>Situación Revista</th>
                        <th>Antigüedad</th>
                        <th>Agrupación</th>
                        <th>Código Salarial</th>
                        <th>Area</th>
                        <th>Turno de Trabajo</th>
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
@endsection

@section('Script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
<script src="{{ asset('js/sage/controlipe.js') }}"></script>
<script>
    window.appData = {
        cue: '{{ $CUECOMPLETO }}',
        mes: '{{ $MesActual }}'
    };

    document.addEventListener("DOMContentLoaded", function () {
    document
        .querySelectorAll(".eliminar-agente_lista_negra")
        .forEach(function (btn) {
            btn.addEventListener("click", function () {
                const idPofIpe = this.dataset.idpof;
                const cue = this.dataset.cue;

                Swal.fire({
                    title: "¿Recuperar agente?",
                    text: "¿Deseás quitar al agente de la lista negra?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#28a745",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sí, recuperar",
                    cancelButtonText: "Cancelar",
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/agente-recuperar/${idPofIpe}/${cue}`, {
                            method: "DELETE",
                            headers: {
                                "X-CSRF-TOKEN": document
                                    .querySelector('meta[name="csrf-token"]')
                                    .getAttribute("content"),
                            },
                        })
                            .then((res) => res.json())
                            .then((data) => {
                                if (data.success) {
                                    Swal.fire(
                                        "Recuperado",
                                        data.message,
                                        "success"
                                    );
                                    document
                                        .querySelector(
                                            `tr[data-id="${idPofIpe}"]`
                                        )
                                        .remove();
                                } else {
                                    Swal.fire("Error", data.message, "error");
                                }
                            })
                            .catch(() => {
                                Swal.fire(
                                    "Error",
                                    "Ocurrió un error al recuperar el agente.",
                                    "error"
                                );
                            });
                    }
                });
            });
        });
});
</script>
@endsection
