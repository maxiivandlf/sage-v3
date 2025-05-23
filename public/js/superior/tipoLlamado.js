$(document).ready(function () {
    // Mostrar/ocultar selects según tipo de llamado
    $('#idtipo_llamado_modal').change(function () {
        const tipoLlamado = $(this).val();
        $('#cargoSelect_modal').toggle(tipoLlamado == "1");
        $('#espacioSelect_modal').toggle(tipoLlamado == "2");
    }).trigger('change');

    // Manejamos el cambio del select por bloque
    $('#bloques-container').on('change', '.tipoLlamadoSelect', function () {
        const bloque = $(this).closest('.bloque-espacio');
        const tipo = $(this).val();
        bloque.find('.divCargo').toggle(tipo == "1");
        bloque.find('.divUnidad').toggle(tipo == "2");

        if (tipo == "1") cargarCargos(bloque.find('.cargoSelect'));
        if (tipo == "2") cargarEspaciosCurriculares(bloque.find('.espacioSelect'));
    });

    $('.tipoLlamadoSelect').trigger('change');

    // Select2 en modales
    const modales = ['#modalCargo', '#modalEditarCargo', '#modalEspacio', '#modalEditarEspacio'];
    modales.forEach(modalId => {
        $(modalId).on('shown.bs.modal', function () {
            $(this).find('select.selectPerfil, select.espacioSelect, select.selectCarrera, select.selectInstituto').each(function () {
                const $select = $(this);
                if ($select.hasClass('select2-hidden-accessible')) $select.select2('destroy');
                $select.select2({
                    dropdownParent: $(modalId),
                    width: '100%',
                    placeholder: 'Seleccione una opción',
                    allowClear: true
                });
            });
        });
    });

    // Select2 en formulario principal (editar o crear)
    $('select.selectPerfil, select.espacioSelect, select.selectCarrera, select.selectInstituto').each(function () {
        const $select = $(this);
        if (!$select.hasClass("select2-hidden-accessible")) {
            $select.select2({
                width: '100%',
                placeholder: 'Seleccione una opción',
                allowClear: true
            });
        }
    });

    // Agregar bloque dinámico
    $('#agregarEspacioCargo').click(function () {
        const tipoLlamado = $("#idtipo_llamado_modal").val();
        const tipoText = $("#idtipo_llamado_modal option:selected").text();
        const cargo = $("#cargoSelect_modal").val();
        const cargoText = $("#cargoSelect_modal option:selected").text();
        const espacio = $("#espacioSelect_modal").val();
        const espacioText = $("#espacioSelect_modal option:selected").text();
        const turno = $("#idTurno_modal").val();
        const turnoText = $("#idTurno_modal option:selected").text();
        const horas = $("#horacat_modal").val();
        const situacion = $("#idtb_situacion_revista_modal").val();
        const situacionText = $("#idtb_situacion_revista_modal option:selected").text();
        const periodo = $("#idtb_periodo_cursado_modal").val();
        const periodoText = $("#idtb_periodo_cursado_modal option:selected").text();
        const horario = $("#horario_modal").val();
        const perfil = $("#idtb_perfil_modal").val();
        const perfilText = $("#idtb_perfil_modal option:selected").text();

        if (!tipoLlamado || (tipoLlamado == "1" && !cargo) || (tipoLlamado == "2" && !espacio) || !turno || !horas || !situacion || !periodo || !horario || !perfil) {
            alert("Por favor, complete todos los campos obligatorios.");
            return;
        }

        let repetido = false;
        if (tipoLlamado == "1") {
            $('#bloques-container select[name="idtb_cargos[]"]').each(function () {
                if ($(this).val() == cargo) repetido = true;
            });
        } else if (tipoLlamado == "2") {
            $('#bloques-container select[name="idEspacioCurricular[]"]').each(function () {
                if ($(this).val() == espacio) repetido = true;
            });
        }

        if (repetido) {
            alert("Este elemento ya fue agregado.");
            return;
        }

        const nuevoBloque = $(`
        <div class="bloque-espacio border p-3 rounded mb-3 bg-white">
            <div class="row">
                <div class="col-md-6">
                    <label>Tipo de llamado:</label>
                    <select name="idtipo_llamado[]" class="form-control tipoLlamadoSelect" readonly>
                        <option value="${tipoLlamado}">${tipoText}</option>
                    </select>
                </div>
                <div class="col-md-6 divCargo" style="${tipoLlamado == "1" ? '' : 'display:none;'}">
                    <label>Cargo:</label>
                    <select name="idtb_cargos[]" class="form-control cargoSelect">
                        <option value="${cargo}">${cargoText}</option>
                    </select>
                </div>
                <div class="col-md-6 divUnidad" style="${tipoLlamado == "2" ? '' : 'display:none;'}">
                    <label>Unidad Curricular:</label>
                    <select name="idEspacioCurricular[]" class="form-control espacioSelect">
                        <option value="${espacio}">${espacioText}</option>
                    </select>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-3">
                    <label>Turno:</label>
                    <select name="idTurno[]" class="form-control"><option value="${turno}">${turnoText}</option></select>
                </div>
                <div class="col-md-3">
                    <label>Horas Cátedra:</label>
                    <input type="text" name="horacat_espacio[]" class="form-control" value="${horas}">
                </div>
                <div class="col-md-3">
                    <label>Situación de Revista:</label>
                    <select name="idtb_situacion_revista[]" class="form-control"><option value="${situacion}">${situacionText}</option></select>
                </div>
                <div class="col-md-3">
                    <label>Período de Cursado:</label>
                    <select name="idtb_periodo_cursado[]" class="form-control"><option value="${periodo}">${periodoText}</option></select>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-6">
                    <label>Horario:</label>
                    <textarea name="horario_espacio[]" class="form-control">${horario}</textarea>
                </div>
                <div class="col-md-6">
                    <label>Perfil:</label>
                    <select name="idtb_perfil[]" class="form-control selectPerfil"><option value="${perfil}">${perfilText}</option></select>
                </div>
            </div>
            <button type="button" class="btn btn-danger btn-sm mt-3 btn-eliminar-bloque">Eliminar bloque</button>
        </div>`);

        $("#bloques-container").append(nuevoBloque);

        // Activar Select2 en nuevos campos
        nuevoBloque.find('select.selectPerfil, select.espacioSelect, select.selectCarrera, select.selectInstituto').each(function () {
            const $select = $(this);
            if ($select.hasClass('select2-hidden-accessible')) $select.select2('destroy');
            $select.select2({
                width: '100%',
                placeholder: 'Seleccione una opción',
                allowClear: true
            });
        });

        nuevoBloque.find('.tipoLlamadoSelect').trigger('change');
        $('#modalAgregarEspacioCargo').modal('hide');
    });

    // Eliminar bloque dinámico
    document.addEventListener("click", function (e) {
        if (e.target.classList.contains("btn-eliminar-bloque")) {
            e.target.closest(".bloque-espacio").remove();
        }
    });
});

// AJAX para cargos
function cargarCargos(select) {
    $.ajax({
        url: '/ajax/cargos',
        method: 'GET',
        success: function (data) {
            select.empty().append('<option value="">Seleccione un cargo</option>');
            data.forEach(cargo => {
                select.append(`<option value="${cargo.idtb_cargos}">${cargo.nombre_cargo}</option>`);
            });
        },
        error: () => alert('Error al cargar los cargos.')
    });
}

// AJAX para espacios curriculares
function cargarEspaciosCurriculares(select) {
    $.ajax({
        url: '/ajax/espacios',
        method: 'GET',
        success: function (data) {
            select.empty().append('<option value="">Seleccione un espacio</option>');
            data.forEach(espacio => {
                select.append(`<option value="${espacio.idEspacioCurricular}">${espacio.nombre_espacio}</option>`);
            });
        },
        error: () => alert('Error al cargar los espacios curriculares.')
    });
}
// filtrado zona->instituto->carrera
// ⚠️ Esto reemplaza el comportamiento por defecto para reinicializar Select2 luego del cambio dinámico

$('#idtb_zona').on('change', function () {
    const zonaId = $(this).val();
    const institutoSelect = $('#id_instituto_superior');
    const carreraSelect = $('#idCarrera');

    institutoSelect.empty().append('<option value="">Cargando institutos...</option>');
    carreraSelect.empty().append('<option value="">Seleccione una carrera</option>');

    if (zonaId) {
            $.ajax({
                url: window.routes.obtenerInstitutos,
                type: 'POST',
                data: { zona_id: zonaId, _token: window.routes.csrf },
                success: function (institutos) {
                    institutoSelect.empty().append('<option value="">Seleccione un instituto</option>');
                    institutos.forEach(inst => {
                        institutoSelect.append(`<option value="${inst.id_instituto_superior}">${inst.nombre_instsup}</option>`);
                    });
        
                    // 🔁 Reaplica Select2
                    if (institutoSelect.hasClass('select2-hidden-accessible')) institutoSelect.select2('destroy');
                    institutoSelect.select2({
                        width: '100%',
                        placeholder: 'Seleccione un instituto',
                        allowClear: true
                    });
                },
                error: function () {
                    institutoSelect.empty().append('<option value="">Error al cargar institutos</option>');
                }
            });
        } else {
            institutoSelect.empty().append('<option value="">Seleccione una zona</option>');
        }
        
    });

$('#id_instituto_superior').on('change', function () {
    const institutoId = $(this).val();
    const carreraSelect = $('#idCarrera');

    carreraSelect.empty().append('<option value="">Cargando carreras...</option>');

    if (institutoId) {
        $.ajax({
            url: window.routes.obtenerCarreras,
            type: 'POST',
            data: { instituto_id: institutoId, _token: window.routes.csrf },
            success: function (carreras) {
                carreraSelect.empty().append('<option value="">Seleccione una carrera</option>');
                carreras.forEach(carrera => {
                    carreraSelect.append(`<option value="${carrera.idCarrera}">${carrera.nombre_carrera}</option>`);
                });

                // 🔁 Reaplica Select2
                if (carreraSelect.hasClass('select2-hidden-accessible')) carreraSelect.select2('destroy');
                carreraSelect.select2({
                    width: '100%',
                    placeholder: 'Seleccione una carrera',
                    allowClear: true
                });
            },
            error: function () {
                carreraSelect.empty().append('<option value="">Error al cargar carreras</option>');
            }
        });
    } else {
        carreraSelect.empty().append('<option value="">Seleccione un instituto</option>');
    }
});

//tabla perfil
// ✅ Configuración global de CSRF para Laravel
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Guardamos de dónde vino el click
$(document).on('click', '.btn-abrir-modal-perfil', function () {
    const opcion = $(this).data('opcion');
    $('#modalPerfiles').data('opcion', opcion);
    $('#modalPerfiles').modal('show');
     // Mostrar u ocultar los botones "Seleccionar" en TODAS las páginas
    const tabla = $('#tablaPerfiles').DataTable();
    tabla.rows().every(function () {
        const row = $(this.node());
        const botonSeleccionar = row.find('.btn-seleccionar-perfil');

        if (!opcion) {
            botonSeleccionar.hide();
        } else {
            botonSeleccionar.show();
        }
    });
});

// Al seleccionar un perfil
$(document).on('click', '.btn-seleccionar-perfil', function () {
    const id = $(this).data('id');
    const nombre = $(this).data('nombre');
    const opcion = $('#modalPerfiles').data('opcion');
    console.log(opcion);
    switch (opcion) {
        case 'cargo':
            $('#idPerfilCargo').val(id);
            $('#nombrePerfilCargo').val(nombre);
            break;
        case 'cargoEditar':
            $('#idPerfilCargoEditar').val(id);
            $('#nombrePerfilCargoEditar').val(nombre);
            break;
        case 'espacio':
            $('#idPerfilEspacio').val(id);
            $('#nombrePerfilEspacio').val(nombre);
            break;
        case 'espacioEditar':
            $('#idPerfilEspacioEditar').val(id);
            $('#nombrePerfilEspacioEditar').val(nombre);
            break;
    }

    $('#modalPerfiles').modal('hide');
});
// buscador de perfiles
$('#tablaPerfiles').DataTable({
    autoWidth: false,
    columnDefs: [
        { width: "25px", targets: 0 },  // ID
        { width: "90px", targets: 2 }  // Acción
    ],
    language: {
        search: "Buscar:",
        lengthMenu: "Mostrar _MENU_ registros por página",
        zeroRecords: "No se encontraron resultados",
        info: "Mostrando página _PAGE_ de _PAGES_",
        infoEmpty: "No hay perfiles disponibles",
        infoFiltered: "(filtrado de _MAX_ perfiles en total)"
    }
});

// ✅ Al hacer clic en "Agregar Perfil"
$('#btnAgregarPerfil').on('click', function () {
    $('#modalLabelPerfil').text('Agregar Perfil');
    $('#idPerfilForm').val('');
    $('#nombrePerfilForm').val('');
    $('#modalAgregarEditarPerfil').modal('show');
});

// ✅ Al hacer clic en "Editar" en alguna fila
$(document).on('click', '.btn-editar-perfil', function () {
    const id = $(this).data('id');
    const nombre = $(this).data('nombre');

    $('#modalLabelPerfil').text('Editar Perfil');
    $('#idPerfilForm').val(id);
    $('#nombrePerfilForm').val(nombre);
    $('#modalAgregarEditarPerfil').modal('show');
});

// ✅ Guardar perfil (crear o editar)
$('#formPerfil').on('submit', function (e) {
    e.preventDefault();

    const id = $('#idPerfilForm').val();
    const nombre = $('#nombrePerfilForm').val();

    $.ajax({
        url: id ? '/perfil/' + id : '/perfil',
        type: id ? 'PUT' : 'POST',
        data: { nombre_perfil: nombre },
        success: function (response) {
            $('#modalAgregarEditarPerfil').modal('hide');
              recargarPerfiles();
              Swal.fire('Éxito', 'Perfil guardado correctamente.', 'success');
        },
        error: function (xhr) {
            console.error(xhr.responseText);
            alert('Error al guardar perfil.');
        }
    });
});

//agregar y editar espacio curricular


    $(document).ready(function () {
        let tablaEspacios;

        // Botón principal: abre el modal con la tabla
        $('#btnVerEspacioCurricular').on('click', function () {
            $('#modalEspacios').modal('show');
            cargarEspacios();
        });

        // Función que carga los espacios curriculares en la tabla
        function cargarEspacios() {
            $.get('/espacios/listar', function (data) {
                if ($.fn.DataTable.isDataTable('#tablaEspacios')) {
                    tablaEspacios.clear().destroy();
                }

                $('#tablaEspacios tbody').empty();

                data.forEach((espacio, i) => {
                    $('#tablaEspacios tbody').append(`
                        <tr>
                            <td>${i + 1}</td>
                            <td>${espacio.nombre_espacio}</td>
                            <td>
                                <button class="btn btn-sm btn-info btnEditarEspacio"
                                    data-id="${espacio.idEspacioCurricular}"
                                    data-nombre="${espacio.nombre_espacio}">
                                    Editar
                                </button>
                            </td>
                        </tr>
                    `);
                });

                tablaEspacios = $('#tablaEspacios').DataTable({
                    responsive: true,
                    destroy: true,
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
                    }
                });
            });
        }

        // Abrir modal para agregar
        $('#btnAgregarEspacio').on('click', function () {
            $('#idEspacioForm').val('');
            $('#nombreEspacioForm').val('');
            $('#modalLabelFormEspacio').text('Agregar Espacio Curricular');
            $('#modalFormularioEspacio').modal('show');
        });

        // Abrir modal para editar (desde botón en tabla)
        $(document).on('click', '.btnEditarEspacio', function () {
            const id = $(this).data('id');
            const nombre = $(this).data('nombre');

            $('#idEspacioForm').val(id);
            $('#nombreEspacioForm').val(nombre);
            $('#modalLabelFormEspacio').text('Editar Espacio Curricular');
            $('#modalFormularioEspacio').modal('show');
        });

        // Guardar (nuevo o editado)
        $('#formEspacioCurricular').on('submit', function (e) {
            e.preventDefault();

            const id = $('#idEspacioForm').val();
            const nombre = $('#nombreEspacioForm').val().trim();

            if (!nombre) {
                Swal.fire('Error', 'El nombre del espacio es obligatorio.', 'warning');
                return;
            }

            $.ajax({
                url: id ? `/espacio/editar/${id}` : '/espacio/nuevo',
                method: id ? 'PUT' : 'POST',
                data: {
                    nombre_espacio: nombre,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    $('#modalFormularioEspacio').modal('hide');
                    cargarEspacios();
                    recargarEspacios();
                    Swal.fire('Éxito', res.mensaje, 'success');
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    Swal.fire('Error', 'No se pudo guardar el espacio', 'error');
                }
            });
        });
    });

    function recargarPerfiles() {
        $.get('/perfil/listar', function (perfiles) {
            const selectPerfil = $('#idPerfilCargo');
            selectPerfil.empty();
            selectPerfil.append('<option value="">Seleccione un perfil</option>');
            perfiles.forEach(p => {
                selectPerfil.append(`<option value="${p.idtb_perfil}">${p.nombre_perfil}</option>`);
            });
        });
    }
    function recargarEspacios() {
        $.get('/espacios/listar', function (espacios) {
            const selectEspacio = $('#idEspacioCurricular'); // ajustalo a tu selector real
            selectEspacio.empty();
            selectEspacio.append('<option value="">Seleccione un espacio</option>');
            espacios.forEach(e => {
                selectEspacio.append(`<option value="${e.idEspacioCurricular}">${e.nombre_espacio}</option>`);
            });
        });
    }
