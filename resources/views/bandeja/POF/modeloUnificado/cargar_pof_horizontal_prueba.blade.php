@extends('layout.app')

@section('Titulo', 'Sage2.1 - POF MH')

@section('LinkCSS')
  <link rel="stylesheet" href="{{ asset('css/pofmh.css') }}">
  <style>

  </style>
@endsection
@section('ContenidoPrincipal')

<section id="container" >
    <section id="main-content">
        <section class="content-wrapper">
          <div class="container-fluid"> 

            {{--Conformidad--}}   
            <div class="row"> 
              <div class="alert alert-danger">
                <h4>CONFORMIDAD</h4>
                <p>
                  Esta declaración se realiza en cumplimiento de las normativas vigentes, 
                  incluyendo la Ley N° 25.164 de Ética en la Función Pública, la Ley N° 25.326 de Protección de 
                  Datos Personales y en conformidad con la Ley N° 27.275 de Acceso a la Información Pública. 
                  Autorizo ​​al Director/a de <b>AQUI ESCUELA</b> a registrar y 
                  digitalizar los datos consignados en esta declaración, los cuales serán tratados exclusivamente 
                  en el marco de lo establecido por las normativas antes mencionadas.
                </p>
              </div>
            </div>

            {{--Datos Institucional--}}
            <div class="card alert-warning row">
              <div class="alert alert-dismissible" id="mensajeprincipalescuela">
                @php
                    // Obtener la fecha actuals
                    \Carbon\Carbon::setLocale('es');
                    $fechaActual = \Carbon\Carbon::now();
                    $fechaFormateada = $fechaActual->format('d/m/Y');
                    $mesDeControl = $fechaActual->translatedFormat('F'); // Formato de mes en español
                @endphp

              </div>
            </div>


            {{--Botones--}}
            <form id="excelForm row">
                @csrf <!-- Agregar el token CSRF de Laravel -->
            
                <!-- Botón para agregar la primera fila -->
                <div class="row align-items-center container-fluid">
                  <div class="botonera col-md-8 col-sm-12">
                    <div class="botonera-izquierda">
                      <button type="button" class="add-first-row-btn" id="addFirstRowBtn">
                        <i class="fas fa-plus"></i> Crear primera fila
                      </button>
                      <button type="button" class="add-ultimo-row-btn" id="addLastRow">
                          <i class="fas fa-plus-circle"></i> Crear Fila al Último
                      </button>
                      <button type="button" class="ir-al-ultimo-btn" id="irAlUltimoBtn">
                        <i class="fas fa-arrow-down"></i> Ir al Último
                      </button>
                    </div>
                  </div>

                </div>

                {{--BUSCADOR--}}
                <div class="container-fluid m-1">
                  <form id="searchForm" class="row col-12">
                    <div class="input-group">
                      <input type="search" id="searchInput" class="form-control" placeholder="Buscar Apellido, Nombre o N° de DNI">
                    </div>
                  </form>
                </div>

                {{--POFMH--}}
                <div class="content m-0">
                  <div class="container-fluid">
                      <div class="card table-responsive" id="cardPOFMH">
                          <table id="POFMH" class="table table-bordered table-striped table-hover">
                              <thead class="card-header">
                                  <tr >
                                      <th class="custom-5rem" id="tablaarriba">#ID</th>
                                      <th class="custom-5rem">Orden</th>
                                      <th class="custom-8rem">DNI</th>
                                      <th class="custom-8rem">CUIL</th>
                                      <th class="custom-8rem">Trabajo</th>
                                      <th class="custom-20rem">Apellido y Nombre</th>
                                      <th class="custom-5rem">Sexo</th>

                                      <th class="custom-20rem">Cargo de Origen en la Institución</th>
                                      
                                      <th class="custom-15rem">Sit.Rev</th>
                                      <th class="custom-5rem">Horas</th>
                                      <th class="custom-13rem">Antigüedad Docente</th>
                                      <th class="custom-8rem">Código</th>
                                      <th class="custom-33rem">Cargo</th>
                                      <th class="custom-20rem">Area</th>
                                      <th class="custom-20rem">IPE</th>


                                  </tr>
                              </thead>
                              <tbody class="card-body direct-chat-messages">
                                @if ($AgentesLista)
                                    @foreach ($AgentesLista as $Agente)
                                    @php
                                        //dd($Agente);
                                    @endphp
                                        <tr>
                                            <td class="text-center">{{ $Agente->idPofIpe }}</td>
                                            <td class="text-center">0</td>
                                            <td class="text-center">
                                                <input type="text" class="form-control dni-input" value="{{ $Agente->Documento }}" readonly>
                                            </td>

                                            <td class="text-center">
                                                <input type="text" class="form-control cuil-input" value="{{ $Agente->Cuil }}" readonly>
                                            </td>

                                            <td class="text-center">
                                                <input type="text" class="form-control trabajo-input" value="{{ $Agente->Trabajo }}" readonly>
                                            </td>

                                            <td class="text-center">
                                                <input type="text" class="form-control apenom-input" value="{{ $Agente->ApeNom }}" readonly>
                                            </td>

                                            <td class="text-center">
                                                <input type="text" class="form-control sexo-input" value="{{ $Agente->Sexo }}" readonly>
                                            </td>

                                            <td class="text-center">
                                                <input type="text" class="form-control origen-input" value="" readonly>
                                            </td>

                                            <td class="text-center">
                                                <input type="text" class="form-control sitrev-input" value="{{ $Agente->Descripcion }}" readonly>
                                            </td>

                                            <td class="text-center">
                                                <input type="number" class="form-control hora-input" value="{{ $Agente->Hora }}" readonly>
                                            </td>

                                            <td class="text-center">
                                                <input type="number" class="form-control antiguedad-docente-input" value="{{ $Agente->Antiguedad }}" readonly>
                                            </td>

                                            <td class="text-center">
                                                <input type="text" class="form-control codigo-docente-input" value="{{$Agente->Codigo}}" readonly>
                                            </td>

                                            <td class="text-center">
                                                <input type="text" class="form-control cargo-docente-input" value="{{$Agente->Cargo}}" readonly>
                                            </td>

                                            <td class="text-center">
                                                <input type="text" class="form-control area-docente-input" value="{{ $Agente->Area }}" readonly>
                                            </td>

                                            <td class="text-center">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input checkbox-ipe-si" type="checkbox"
                                                        id="ipe_si_{{ $Agente->idPofIpe }}"
                                                        name="ipe_{{ $Agente->idPofIpe }}"
                                                        data-id="{{ $Agente->idPofIpe }}"
                                                        {{ $Agente->IPE == 'SI' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="ipe_si_{{ $Agente->idPofIpe }}">SI</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input checkbox-ipe-no" type="checkbox"
                                                        id="ipe_no_{{ $Agente->idPofIpe }}"
                                                        name="ipe_{{ $Agente->idPofIpe }}"
                                                        data-id="{{ $Agente->idPofIpe }}"
                                                        {{ $Agente->IPE == 'NO' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="ipe_no_{{ $Agente->idPofIpe }}">NO</label>
                                                </div>
                                            </td>


                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="30" class="text-center">No hay datos disponibles</td>
                                    </tr>
                                @endif
                              </tbody>
                          </table>
                      </div>
                  </div>
                </div>
                
            </form>
          </div>
        </section>
    </section> 
</section>




@endsection

@section('Script')

<script src="{{ asset('js/pofmhMod2.js') }}"></script>

<!-- Incluye la librería SheetJS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
    $(document).ready(function () {
        // Evento para capturar entrada en el campo de búsqueda
        $('#searchInput').on('keyup', function () {
            const value = $(this).val().toLowerCase().trim(); // Convertir a minúsculas y eliminar espacios extra

            // Recorrer las filas del tbody de la tabla
            $('#POFMH tbody tr').each(function () {
                const dni = $(this).find('.dni-input').val().toLowerCase().trim(); // Tomar el valor del input de DNI
                const name = $(this).find('.apenom-input').val().toLowerCase().trim(); // Cambiar al índice real del Apellido y Nombre
                const espcur = $(this).find('.espcur-input').val().toLowerCase().trim();

                // Mostrar u ocultar fila si coincide el valor
                const matches = dni.includes(value) || name.includes(value) || espcur.includes(value);
                $(this).toggle(matches);
            });
        });
    });
</script>

@endsection
