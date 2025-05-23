@extends('layout.app')
@section('Titulo', 'Sage2.0 - Nivel Superior - Crear LOM')
@section('ContenidoPrincipal')
@section('LinkCSS')
    {{-- para superior --}}
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">

    <link rel="stylesheet" href="{{ asset('css/superior/tablallamado.css') }}">  
    <!--fin superior -->
@endsection
    <section id="container" class="col-12">
        <section id="main-content">
            <section class="content-wrapper">
                <div class="row mb-3">
                 
                </div>
                <div class="form-wrapper mx-auto bg-light p-4 rounded shadow-sm">                   
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif                   
                    <div id="formularioLom">
                        <form action="{{ route('lom.agregarLom') }}" method="POST" id="formActualizarLom" enctype="multipart/form-data">
                            @csrf
                             <h5 class="text-dark text-center font-weight-bold mt-2 mb-2 border-bottom pb-1">Editar LOM: <strong id="idLomCrear">{{$lom->idtb_lom}}</strong></h5>
                            <div class="mb-3">
                                <label for="idtb_zona">Zona:</label>
                                <select name="idtb_zona" id="idtb_zona" class="form-control select2" required>
                                    <option value="">Seleccione una zona</option>
                                    @foreach($zonas as $zona)
                                          @if($zona->idtb_zona == $lom->idtb_zona)
                                                <option value="{{ $zona->idtb_zona }}" selected>{{ $zona->nombre_zona }}</option>
                                            @else
                                                <option value="{{ $zona->idtb_zona }}">{{ $zona->nombre_zona }}</option>
                                            @endif        
                                    @endforeach
                                </select>
                            </div>
                    
                            <div class="mb-3">
                                <label for="id_instituto_superior">Instituto:</label>
                                <select name="id_instituto_superior" id="id_instituto_superior" class="form-control select2" required>
                                    <option value="">Seleccione un instituto</option>
                                     @foreach($institutos as $instituto)
                                       <option value="{{ $instituto->id_instituto_superior }}" 
                                               {{ $instituto->id_instituto_superior == $lom->id_instituto_superior ? 'selected' : '' }}>
                                               {{ $instituto->nombre_instsup }}
                                        </option>
                                    @endforeach 
                                </select>
                            </div>                    
                            <div class="mb-3">
                                <label for="idCarrera">Carrera:</label>
                                <select name="idCarrera" id="idCarrera" class="form-control select2" required>
                                    <option value="">Seleccione una carrera</option>
                                    @foreach($carreras as $carrera)
                                        <option value="{{ $carrera->idCarrera }}" 
                                            {{ $carrera->idCarrera == $lom->idCarrera ? 'selected' : '' }}>
                                            {{ $carrera->nombre_carrera }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                    
                            <div class="mb-3">
                                <label for="idtipo_llamado">Tipo de llamado:</label>
                                <select name="idtipo_llamado" id="idtipo_llamado" class="form-control" required>
                                    <option value="">Seleccione un tipo</option>
                                    @foreach($tiposLlamado as $tipo)
                                       @if($tipo->idtipo_llamado == $lom->idtipo_llamado)
                                          <option value="{{ $tipo->idtipo_llamado }}" selected>{{ $tipo->nombre}}</option>
                                        @else
                                          <option value="{{ $tipo->idtipo_llamado }}">{{ $tipo->nombre}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>                      
                            <!-- imagen -->
                            <div class="form-group mb-3">                           
                                <label for="nombre_img" class="font-weight-bold mt-3">Imagen:</label>
                                  @if ($lom->imglom)
                                      <img src="{{ asset('storage/superior/lom/'.$lom->mes.'/'.$lom->imglom) }}" alt="Imagen" style="width: 200px;">
                                      <p><strong> Imagen actual: </strong>{{ $lom->imglom }}<strong>  <br/>Si desea cambiar la imagen, seleccione una nueva.</strong></p>    
                                      <input type="file" name="imagen" id="imagen" class="form-control">  
                                  @endif                                                         
                            </div>
                               <!-- pdf -->
                               <div class="form-group mb-3">                           
                                <label for="pdf" class="font-weight-bold mt-3">PDF:</label>
                                @if ($lom->imglom)
                                      <img src="{{ asset('storage/superior/lom/'.$lom->mes.'/'.$lom->pdf) }}" alt="Imagen" style="width: 200px;">
                                      <p><strong> Imagen actual: </strong>{{ $lom->pdf }}<strong><br/>Si desea cambiar El PDF, seleccione uno nuevo con extensión .pdf</strong></p>    
                                      <input type="file" name="pdf" id="pdf" class="form-control">  
                                  @endif 
                                                        
                            </div>
                           
                            
                            <input type="hidden" name="lom_id" id="lom_id" value="{{ $lom->idtb_lom }}">                        
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                        </form>
                       
                    </div>
                </div>
            </section>
        </section>
    </section>

    <section id="container" class="col-12">
            <section id="main-content">
               <section class="content-wrapper">                               
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif                   
                    <div class="row">
                        <h3 style="display: block">LOM Cargados</h3>
                        <table class="table table-striped table-bordered dt-responsive nowrap tablaLom" style="width:100%">
                            <tr>
                                <th>N°</th>
                                <th>LOM</th>
                                <th>Zona</th>
                                <th>Institución</th>
                                <th>Carrera</th>
                                <th>Unidad / Cargo</th>
                            </tr>
                            <tr>
                                <td colspan="8">Sin Información</td>
                            </tr>
                        </table>
                    </div>                  
                </section>               
           </section>
    </section>   
 
  
  
@endsection

@section('Script')     
  
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>

    <!-- Librerías necesarias para exportación -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
   

    <script>
          // Variables globales
          let modo = 'agregar'; 
          let filaActual = null;
          let idEspacioEditar = null; 
          let idCargoEditar = null; // Para editar el cargo
          let formularioEnviado = false;
    $(document).ready(function () {            

            // $('#btnCrearLom').on('click', function () {
            //     Swal.fire({
            //         title: '¿Estás seguro/a?',
            //         text: "¿Querés crear un nuevo LOM?",
            //         icon: 'question',
            //         showCancelButton: true,
            //         confirmButtonText: 'Sí, crear',
            //         cancelButtonText: 'Cancelar'
            //     }).then((result) => {
            //         if (result.isConfirmed) {
            //             $.ajax({
            //                 url: '{{ route("lom.agregarLom") }}',
            //                 type: 'POST',
            //                 headers: {
            //                     "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            //                 },
            //                 success: function (response) {
            //                     console.log(response);
            //                     $('#lom_id').val(response.id);
            //                     $('#idLomCrear').text(response.id);
            //                     $('#formularioLom').show();
            //                     $('#btnCrearLom').hide();

            //                     // Agregar el ID al data-id de los botones
            //                     $('#btnCargo').attr('data-id', response.id);
            //                     $('#btnEspacio').attr('data-id', response.id);

            //                     Swal.fire(
            //                         '¡LOM creado!',
            //                         'Se creó un nuevo LOM con ID: ' + response.id,
            //                         'success'
            //                     );
                              
            //                 },
            //                 error: function () {
            //                     Swal.fire(
            //                         'Error',
            //                         'Hubo un problema al crear el LOM.',
            //                         'error'
            //                     );
            //                 }
            //             });
            //         }
            //     });
            // });

            $('#formActualizarLom').on('submit', function (e) {
                e.preventDefault();

                Swal.fire({
                    title: '¿Actualizar LOM?',
                    text: "Se guardarán los cambios de este LOM.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, actualizar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const formData = new FormData(this); // 👈 Aca usamos FormData correctamente

                        $.ajax({
                            url: '{{ route("lom.obtenerLom") }}',
                            type: 'POST',
                            data: formData,
                            processData: false, // 👈 Importante para que jQuery NO convierta los datos
                            contentType: false, // 👈 Importante para que se envíe como multipart/form-data
                            success: function () {
                                 formularioEnviado = true;
                                Swal.fire(
                                    '¡Actualizado!',
                                    'Los datos del LOM se actualizaron correctamente.',
                                    'success'
                                );
                            },
                            error: function (xhr) {
                                console.log(xhr.responseText); // Para debug si da error
                                Swal.fire(
                                    'Error',
                                    'No se pudo actualizar el LOM.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });            
     });

      $(document).ready(function () {            

            llenarTablaEspacio($('#llamadoIdEspacio').val());
            llenarTablaCargo($('#llamadoIdCargo').val());

            const zonaId = '{{ $lom->idtb_zona }}';
            const institutoId = '{{ $lom->id_instituto_superior }}';
            const carreraId = '{{ $lom->idCarrera }}';

            if (zonaId) {
               $('#idtb_zona').val(zonaId);

            $.ajax({
                url: '{{ route("llamado.obtenerInstitutos") }}',
                type: 'POST',
                data: { zona_id: zonaId, _token: '{{ csrf_token() }}' },
                success: function (institutos) {
                    const institutoSelect = $('#id_instituto_superior');
                    institutoSelect.empty().append('<option value="">Seleccione un instituto</option>');
                    institutos.forEach(inst => {
                        institutoSelect.append(`<option value="${inst.id_instituto_superior}">${inst.nombre_instsup}</option>`);
                    });

                    institutoSelect.val(institutoId);

                    // 🧠 Reiniciar Select2 correctamente
                    if (institutoSelect.hasClass('select2-hidden-accessible')) {
                        institutoSelect.select2('destroy');
                    }
                    institutoSelect.select2({
                        width: '100%',
                        placeholder: 'Seleccione un instituto',
                        allowClear: true
                    });

            // Cargar carreras automáticamente luego
            $.ajax({
                    url: '{{ route("llamado.obtenerCarreras") }}',
                    type: 'POST',
                    data: { instituto_id: institutoId, _token: '{{ csrf_token() }}' },
                    success: function (carreras) {
                        const carreraSelect = $('#idCarrera');
                        carreraSelect.empty().append('<option value="">Seleccione una carrera</option>');
                        carreras.forEach(carrera => {
                            carreraSelect.append(`<option value="${carrera.idCarrera}">${carrera.nombre_carrera}</option>`);
                        });

                        carreraSelect.val(carreraId);

                        // 🧠 Reiniciar Select2 para carreras también
                        if (carreraSelect.hasClass('select2-hidden-accessible')) {
                            carreraSelect.select2('destroy');
                        }
                            carreraSelect.select2({
                                width: '100%',
                                placeholder: 'Seleccione una carrera',
                                allowClear: true
                            });
                        }
                    });
                }
            });
        }
        // Cargar carreras al cambiar el instituto
        $('#id_instituto_superior').on('change', function () {
            const institutoId = $(this).val();
                $.ajax({
                    url: '{{ route("llamado.obtenerCarreras") }}',
                    type: 'POST',
                    data: { instituto_id: institutoId, _token: '{{ csrf_token() }}' },
                    success: function (carreras) {
                        const carreraSelect = $('#idCarrera');
                        carreraSelect.empty().append('<option value="">Seleccione una carrera</option>');
                        carreras.forEach(carrera => {
                            carreraSelect.append(`<option value="${carrera.idCarrera}">${carrera.nombre_carrera}</option>`);
                        });
                    }
                });
            });
        });

        
            window.addEventListener("beforeunload", function (e) {
            if (!formularioEnviado) {
                // ⚠️ Este mensaje no siempre se puede personalizar por seguridad del navegador
                const mensaje = "Tenés cambios sin guardar. ¿Seguro que querés salir?";
                e.preventDefault(); // Necesario para algunos navegadores
                e.returnValue = mensaje;
                return mensaje;
            }
        });    

    </script>
     <script>
        window.routes = {
            obtenerInstitutos: "{{ route('llamado.obtenerInstitutos') }}",
            obtenerCarreras: "{{ route('llamado.obtenerCarreras') }}",
            csrf: "{{ csrf_token() }}"
        };
    </script>   
    <script src="{{ asset('js/superior/tablaLlamado.js') }}"></script>  
    <script src="{{ asset('js/superior/tipoLlamado.js') }}"></script>
@endsection
