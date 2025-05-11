$(document).ready(function() {
    
    // Mostrar/ocultar selects según el tipo de llamado
    $('#idtipo_llamado_modal').change(function () {
        const tipoLlamado = $(this).val();

        if (tipoLlamado == "1") { // Cargo
            $('#cargoSelect_modal').show();
            $('#espacioSelect_modal').hide();
        } else if (tipoLlamado == "2") { // Espacio curricular
            $('#cargoSelect_modal').hide();
            $('#espacioSelect_modal').show();
        } else {
            $('#cargoSelect_modal').hide();
            $('#espacioSelect_modal').hide();
        }
    });

    // Ejecutar al cargar
    $('#idtipo_llamado_modal').trigger('change');

    // También podés agregar aquí el click para "Agregar Espacio/Cargo"
    $('#agregarEspacioCargo').click(function () {
        // Validaciones y lógica...
    });


    // Manejamos el cambio del select de tipo de llamado por cada bloque
    $('#bloques-container').on('change', '.tipoLlamadoSelect', function() {
        const bloque = $(this).closest('.bloque-espacio');
        const selected = $(this).val();

        const divCargo = bloque.find('.divCargo');
        const divUnidad = bloque.find('.divUnidad');
        const cargoSelect = bloque.find('.cargoSelect');
        const espacioSelect = bloque.find('.espacioSelect');

        if (selected == "1") {
            divCargo.show();
            divUnidad.hide();
            cargarCargos(cargoSelect);
        } else if (selected == "2") {
            divCargo.hide();
            divUnidad.show();
            cargarEspaciosCurriculares(espacioSelect);
        } else {
            divCargo.hide();
            divUnidad.hide();
        }
    });

    // Dispara el change para los selects ya cargados
    $('.tipoLlamadoSelect').trigger('change');
});

// Función para cargar los cargos
function cargarCargos(select) {
    $.ajax({
        url: '/ajax/cargos',
        method: 'GET',
        success: function(data) {
            select.empty();
            if (data && data.length > 0) {
                select.append('<option value="">Seleccione un cargo</option>');
                data.forEach(function(cargo) {
                    select.append('<option value="' + cargo.idtb_cargos + '">' + cargo.nombre_cargo + '</option>');
                });
            } else {
                select.append('<option>No hay cargos disponibles</option>');
            }
        },
        error: function() {
            alert('Error al cargar los cargos.');
        }
    });
}

// Función para cargar los espacios curriculares
function cargarEspaciosCurriculares(select) {
    $.ajax({
        url: '/ajax/espacios',
        method: 'GET',
        success: function(data) {
            console.log("Datos recibidos:", data);
            select.empty();
            select.append('<option value="">Seleccione un espacio curricular</option>'); // Opción por defecto
            if (data && data.length > 0) {
                select.append('<option value="">Seleccione un espacio</option>');
                data.forEach(function(espacio) {
                    select.append('<option value="' + espacio.idEspacioCurricular + '">' + espacio.nombre_espacio + '</option>');
                });
            } else {
                select.append('<option>No hay espacios curriculares disponibles</option>');
            }
        },
        error: function() {
            alert('Error al cargar los espacios curriculares.');
        }
    });
}

// Botón "Agregar" en el modal
document.getElementById("agregarEspacioCargo").addEventListener("click", function() {
    const tipoLlamado = $("#idtipo_llamado_modal").val();
    const tipoLlamadoText = $("#idtipo_llamado_modal option:selected").text();
    const cargo = $("#cargoSelect_modal").val();
    const cargoText = $("#cargoSelect_modal option:selected").text();
    const espacio = $("#espacioSelect_modal").val();
    const espacioText = $("#espacioSelect_modal option:selected").text();
    const turno = $("#idTurno_modal").val();
    const turnoText = $("#idTurno_modal option:selected").text();
    const horasCat = $("#horacat_modal").val();
    const situacionRevista = $("#idtb_situacion_revista_modal").val();
    const situacionRevistaText = $("#idtb_situacion_revista_modal option:selected").text();
    const periodoCursado = $("#idtb_periodo_cursado_modal").val();
    const periodoCursadoText = $("#idtb_periodo_cursado_modal option:selected").text();
    const horario = $("#horario_modal").val();
    const perfil = $("#idtb_perfil_modal").val();
    const perfilText = $("#idtb_perfil_modal option:selected").text();

    // Depuración
    console.log("Tipo Llamado: ", tipoLlamado);
    console.log("Cargo: ", cargo);
    console.log("Espacio: ", espacio);
    console.log("Turno: ", turno);
    console.log("Horas Cátedra: ", horasCat);

    if (!tipoLlamado || (tipoLlamado == "1" && !cargo) || (tipoLlamado == "2" && !espacio) || !turno || !horasCat || !situacionRevista || !periodoCursado || !horario || !perfil) {
        alert("Por favor, complete todos los campos obligatorios.");
        return;
    }
    
    let repetido = false;
    if (tipoLlamado == "1") {
        $('#bloques-container select[name="idtb_cargos[]"]').each(function() {
            if ($(this).val() == cargo && tipoLlamado == $(this).closest('.bloque-espacio').find('select[name="idtipo_llamado[]"]').val()) {
                repetido = true;
            }
        });
    } else if (tipoLlamado == "2") {
        $('#bloques-container select[name="idEspacioCurricular[]"]').each(function() {
            if ($(this).val() == espacio && tipoLlamado == $(this).closest('.bloque-espacio').find('select[name="idtipo_llamado[]"]').val()) {
                repetido = true;
            }
        });
    }

    if (repetido) {
        alert("Este elemento ya fue agregado.");
        return;
    }

    const nuevoBloque = `
        <div class="bloque-espacio border p-3 rounded mb-3 bg-white">
            <div class="row">
                <div class="col-md-6">
                    <label class="font-weight-bold">Tipo de llamado:</label>
                    <select name="idtipo_llamado[]" class="form-control tipoLlamadoSelect" readonly>
                        <option value="${tipoLlamado}">${tipoLlamadoText}</option>
                    </select>
                </div>
                <div class="col-md-6 divCargo" style="${tipoLlamado == "1" ? '' : 'display: none;'}">
                    <label class="font-weight-bold">Cargo:</label>
                    <select name="idtb_cargos[]" class="form-control cargoSelect">
                        <option value="${cargo}">${cargoText}</option>
                    </select>
                </div>
                <div class="col-md-6 divUnidad" style="${tipoLlamado == "2" ? '' : 'display: none;'}">
                    <label class="font-weight-bold">Unidad Curricular:</label>
                    <select name="idEspacioCurricular[]" class="form-control espacioSelect">
                        <option value="${espacio}">${espacioText}</option>
                    </select>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-3">
                    <label class="font-weight-bold">Turno:</label>
                    <select name="idTurno[]" class="form-control">
                        <option value="${turno}">${turnoText}</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="font-weight-bold">Horas Cátedra:</label>
                    <input type="text" name="horacat_espacio[]" class="form-control" value="${horasCat}">
                </div>
                <div class="col-md-3">
                    <label class="font-weight-bold">Situación de Revista:</label>
                    <select name="idtb_situacion_revista[]" class="form-control">
                        <option value="${situacionRevista}">${situacionRevistaText}</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="font-weight-bold">Período de Cursado:</label>
                    <select name="idtb_periodo_cursado[]" class="form-control">
                        <option value="${periodoCursado}">${periodoCursadoText}</option>
                    </select>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-6">
                    <label class="font-weight-bold">Horario:</label>
                    <textarea name="horario_espacio[]" class="form-control">${horario}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="font-weight-bold">Perfil:</label>
                    <select name="idtb_perfil[]" class="form-control">
                        <option value="${perfil}">${perfilText}</option>
                    </select>
                </div>
            </div>
            <button type="button" class="btn btn-danger btn-sm mt-3 btn-eliminar-bloque">Eliminar bloque</button>
        </div>
    `;

    const $nuevoBloque = $(nuevoBloque);
    $("#bloques-container").append($nuevoBloque);
    $nuevoBloque.find('.tipoLlamadoSelect').trigger('change');
    $('#modalAgregarEspacioCargo').modal('hide');
    
});

// Eliminar bloque dinámico
document.addEventListener("click", function(event) {
    if (event.target && event.target.classList.contains("btn-eliminar-bloque")) {
        event.target.closest(".bloque-espacio").remove();
    }
});




