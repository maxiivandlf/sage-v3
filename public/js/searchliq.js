$(document).ready(function () {
    $("#buscar_dni_cue").on("submit", function (event) {
        event.preventDefault();

        var dni = $('input[name="dni"]').val();
        var token = $('input[name="_token"]').val();

        if (!dni) {
            alert("Por favor, ingresa un DNI.");
            return;
        }

        $.ajax({
            url: "/buscar_dni_ajax_liq",
            type: "POST",
            data: {
                _token: token,
                dni: dni,
            },
            success: function (response) {
                console.log(response);

                $("#tabla-completa tbody").empty(); // Limpiar antes de cargar

                if (Object.keys(response).length === 0) {
                    alert("No se encontraron usuarios.");
                    return;
                }

                $.each(response, function (dni, datos) {
                    $.each(datos.pof, function (index, pof) {
                        // 🔵 Alineamos por índice
                        var institucional = datos.institucional[index] || {};
                        var aulica = datos.aulica[index] || {};

                        var row = `
                            <tr>
                                <!-- Información Personal -->
                                <td>${datos.personal?.ApeNom || "-"}</td>
                                <td>${datos.personal?.Documento || "-"}</td>
                                <td>${datos.personal?.Cuil || "-"}</td>
                                <td>${
                                    datos.personal?.Sexo === "M"
                                        ? "MASCULINO"
                                        : datos.personal?.Sexo === "F"
                                        ? "FEMENINO"
                                        : "-"
                                }</td>
            
                                <!-- Información POF -->
                                <td>${pof?.Situacion_Revista || "-"}</td>
                                <td>${pof?.Antiguedad || "-"}</td>
                                <td>${pof?.Hora || "-"}</td>
                                <td>${pof?.Cargo_Salarial || "-"}</td>
                                <td>${pof?.Codigo_Salarial || "-"}</td>
                                <td>${pof?.Posesion_Cargo || "-"}</td>
                                <td>${pof?.Designado_Cargo || "-"}</td>
                                <td>
                                    ${
                                        pof?.idPofmh
                                            ? `<button class="btn-delete btn btn-danger btn-sm" data-id="${pof.idPofmh}">Borrar</button>`
                                            : "-"
                                    }
                                </td>
            
                                <!-- Información Institucional -->
                                <td>${institucional?.CUE || "-"}</td>
                                <td>${institucional?.Codigo_Liq || "-"}</td>
                                <td>${institucional?.Area_Liq || "-"}</td>
                                <td>${
                                    institucional?.Nombre_Institucion || "-"
                                }</td>
                                <td>${institucional?.Nivel || "-"}</td>
                                <td>${institucional?.Zona || "-"}</td>
                                <td>${institucional?.ZonaSuper || "-"}</td>
                                <td>${institucional?.Localidad || "-"}</td>
            
                                <!-- Información Aúlica -->
                                <td>${aulica?.Nombre_Institucion || "-"}</td>
                                <td>${aulica?.Aula || "-"}</td>
                                <td>${aulica?.Division || "-"}</td>
                                <td>${aulica?.Turno || "-"}</td>
                                <td>${aulica?.EspCur || "-"}</td>
                                <td>${aulica?.Matricula || "-"}</td>
                                <td>${aulica?.Condicion || "-"}</td>
                                <td>${aulica?.EnFuncion || "-"}</td>
                                <td>${aulica?.observacion_cond || "-"}</td>
                                <td>${aulica?.AsisTotal || "-"}</td>
                                <td>${aulica?.AsistJust || "-"}</td>
                                <td>${aulica?.AsistInjust || "-"}</td>
                            </tr>
                        `;

                        $("#tabla-completa tbody").append(row);
                    });
                });
            },

            error: function (xhr, status, error) {
                if (xhr.status === 404) {
                    alert("No se encuentra cargado ese Agente con ese DNI.");
                } else {
                    console.error(xhr.responseText);
                    alert(
                        "Error al procesar la solicitud. Inténtalo nuevamente."
                    );
                }

                $("#tabla-completa tbody").empty();
            },
        });
    });
});

//TENICOS
$(document).ready(function () {
    $("#buscar_dni_cue_tec").on("submit", function (event) {
        event.preventDefault(); // Prevenir el envío normal del formulario

        var dni = $('input[name="dni"]').val(); // Obtener el DNI del campo de entrada
        var token = $('input[name="_token"]').val(); // Obtener el token CSRF

        // Validar que el campo de DNI no esté vacío
        if (!dni) {
            alert("Por favor, ingresa un DNI.");
            return;
        }

        $.ajax({
            url: "/buscar_dni_ajax_tec", // Usa la ruta de Laravel
            type: "POST",
            data: {
                _token: token, // Incluir el token CSRF
                dni: dni, // Incluir el DNI
            },
            success: function (response) {
                console.log(response); // Ver respuesta completa en consola

                // Limpiar las tablas antes de mostrar los nuevos datos
                $("#tabla-personal tbody").empty();
                $("#tabla-agente tbody").empty();

                // Verificar si hay datos
                if (Object.keys(response).length === 0) {
                    alert("No se encontraron usuarios.");
                    return;
                }

                // Primera tabla: Datos de libfeb2024
                if (response.libfeb2024 && response.libfeb2024.length > 0) {
                    $.each(response.libfeb2024, function (index, datos) {
                        var personalRow = `
                            <tr>
                                <td>${datos.idLiquidacion}</td>
                                <td>${datos.Documento}</td>
                                <td>${datos.Cuil}</td>
                                <td>${datos.ApeNom}</td>
                                <td>${datos.Nivel}</td>
                                <td>${datos.Escuela}</td>
                                <td>${datos.Descuento_Plan}</td>
                                <td>${datos.Codigo_Nomenclador}</td>
                                <td>${datos.Nomenclador}</td>
                                <td>${datos.Hora}</td>
                                <td>${datos.Antiguedad}</td>
                            </tr>
                        `;
                        $("#tabla-personal tbody").append(personalRow);
                    });
                }

                // Segunda tabla: Datos de tb_pofmh
                if (response.pofmh && response.pofmh.length > 0) {
                    $.each(response.pofmh, function (index, datos) {
                        let cargoSalarial = response.CargosSalariales.find(
                            (cargo) => cargo.idCargo === datos.Cargo
                        );
                        let nivelTexto = cargoSalarial
                            ? `${cargoSalarial.Codigo}`
                            : "--";
                        let cargoTexto = cargoSalarial
                            ? `${cargoSalarial.Cargo}`
                            : "--";

                        let turno = response.Turnos.find(
                            (t) => t.idTurnoUsuario === datos.Turno
                        );
                        let turnoText = turno ? `${turno.Descripcion}` : "--";

                        let institucion = response.Instituciones.find(
                            (i) =>
                                i.CUECOMPLETO === datos.CUECOMPLETO &&
                                i.idTurnoUsuario === datos.Turno
                        );
                        let instText = institucion
                            ? `${institucion.Nombre_Institucion}`
                            : "--";
                        let nivelText = institucion
                            ? `${institucion.Nivel}`
                            : "--";

                        let condicion = response.Condiciones.find(
                            (cond) => cond.idCondicion === datos.Condicion
                        );
                        let condText = condicion
                            ? `${condicion.Descripcion}`
                            : "--";

                        let activo = response.Activos.find(
                            (act) => act.idActivo === datos.Activo
                        );
                        let activoText = activo
                            ? `${activo.nombre_activo}`
                            : "--";

                        let motivo = response.Motivos.find(
                            (m) => m.idMotivo === datos.Motivo
                        );
                        let motivoText = motivo
                            ? `${motivo.Codigo}-${motivo.Nombre_Licencia}`
                            : "--";

                        var agenteRow = `
                            <tr>
                                <td>${datos.idPofmh}</td>
                                <td>${datos.Agente}</td>
                                <td>${datos.Cuil}</td>
                                <td>${datos.ApeNom}</td>
                                <td>${nivelText}</td>
                                <td>${datos.Unidad_Liquidacion}</td>
                                <td>${instText}</td>
                                <td>${turnoText}</td>
                                <td>${datos.CUECOMPLETO}</td>
                                <td>${nivelTexto}</td>
                                <td>${cargoTexto}</td>
                                <td>${datos.Horas}</td>
                                <td>${datos.Antiguedad}</td>
                                <td>${datos.EspCur}</td>
                                <td>${condText}</td>
                                <td>${activoText}</td>
                                <td>${datos.DatosPorCondicion}</td>
                                <td>${motivoText}</td>
                                <td>${datos.Observaciones}</td>
                                 <td>
                                    <button class="btn-delete" data-id="${datos.idPofmh}">Borrar</button>
                                </td>
                            </tr>
                        `;
                        $("#tabla-agente tbody").append(agenteRow);
                    });
                }
            },
            error: function (xhr, status, error) {
                if (xhr.status === 404) {
                    alert("No se encuentra cargado ese Agente con ese DNI.");
                } else {
                    console.error(xhr.responseText);
                }
            },
        });
    });
});

// Manejar el clic en los botones de eliminar
$(document).on("click", ".btn-delete", function () {
    var idPofmh = $(this).data("id");
    var token = $('input[name="_token"]').val(); // Obtener el token CSRF

    Swal.fire({
        title: "¿Estás seguro?",
        text: "¡No podrás revertir esto!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/eliminar_pof_agente/" + idPofmh, // Ruta de Laravel
                type: "DELETE",
                data: {
                    _token: token,
                },
                success: function (response) {
                    Swal.fire(
                        "¡Eliminado!",
                        "El registro ha sido eliminado.",
                        "success"
                    );
                    // Recargar la página después de eliminar
                    location.reload();
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                    Swal.fire(
                        "Error",
                        "Hubo un problema al eliminar el registro. Inténtalo nuevamente.",
                        "error"
                    );
                },
            });
        }
    });
});

$(document).ready(function () {
    $("#buscar_cue").on("submit", function (event) {
        event.preventDefault(); // Prevenir el envío normal del formulario

        var cue = $("#CUE").val(); // Obtener el CUE seleccionado
        var token = $('input[name="_token"]').val(); // Obtener el token CSRF

        if (!cue) {
            alert("Por favor, ingresa un CUE.");
            return;
        }

        $.ajax({
            url: "/buscar_cue_ajax_liq",
            type: "POST",
            data: {
                _token: token,
                cue: cue,
            },
            success: function (response) {
                console.log(response); // Ver respuesta completa en consola

                $("#tabla-completa tbody").empty();
                $("#botones-acciones").empty();

                if (Object.keys(response).length === 0) {
                    alert("No se encontraron usuarios.");
                    return;
                }

                // Obtener el idInstitucionExtension
                var idInstitucionExtension = response.idInstitucionExtension;

                if (!idInstitucionExtension) {
                    alert("No se pudo obtener el ID de la Institución.");
                    return;
                }

                // Construir los botones dinámicamente
                var botones = `
                    <div style="display: flex; flex-wrap: wrap; gap: 1rem; justify-content: space-between; margin-top: 10px;">
                        <a class="btn btn-app" href="/verPofMhidExtSuper/${idInstitucionExtension}" target="_blank">
                            <i class="fas fa-eye"></i> Pof Nominal
                        </a>
                        <a class="btn btn-app" href="/verCargosCreados/${idInstitucionExtension}" target="_blank">
                            <i class="fas fa-search"></i> Ver Cargos POF
                        </a>
                        <a class="btn btn-app" href="/verCargosPofvsNominal/${idInstitucionExtension}" target="_blank">
                            <i class="fas fa-list-ol"></i> Pof vs Nominal
                        </a>
                        <a class="btn btn-app" href="/ver_novedades/Todo/${idInstitucionExtension}" target="_blank">
                            <i class="fas fa-bell"></i> Novedades (Todas)
                        </a>
                        <a class="btn btn-app" href="/adjuntar_novedad/${idInstitucionExtension}" target="_blank">
                            <i class="fas fa-paperclip"></i> Adjuntar Novedades
                        </a>
                        <a class="btn btn-app" href="/agregarNovedadParticular/${idInstitucionExtension}" target="_blank">
                            <i class="fas fa-bell"></i> Novedades Generales
                        </a>
                        <a class="btn btn-app"  href="/controlDeIpeSuper/${idInstitucionExtension}" target="_blank">
                            <i class="fas fa-check-square"></i> Ver Control IPE
                        </a>
                    </div>
                `;

                $("#botones-acciones").html(botones);

                // Ahora iterar sobre cada grupo de datos devueltos
                $.each(response.datos, function (dni, datos) {
                    if (dni === "idInstitucionExtension") {
                        // saltar si la clave es el ID, no un agente
                        return;
                    }

                    var institucional = datos.institucional[0] || {};
                    var aulica = datos.aulica[0] || {};

                    $.each(datos.pof, function (index, pof) {
                        var row = `
                            <tr>
                                <!-- Información Personal -->
                                <td>${datos.personal?.ApeNom || "-"}</td>
                                <td>${datos.personal?.Documento || "-"}</td>
                                <td>${datos.personal?.Cuil || "-"}</td>
                                <td>${
                                    datos.personal?.Sexo === "M"
                                        ? "MASCULINO"
                                        : datos.personal?.Sexo === "F"
                                        ? "FEMENINO"
                                        : "-"
                                }</td>

                                <!-- Información POF -->
                                <td>${pof?.Agente || "-"}</td>
                                <td>${pof?.Situacion_Revista || "-"}</td>
                                <td>${pof?.Antiguedad || "-"}</td>
                                <td>${pof?.Hora || "-"}</td>
                                <td>${pof?.Cargo_Salarial || "-"}</td>
                                <td>${pof?.Codigo_Salarial || "-"}</td>
                                <td>${pof?.Posesion_Cargo || "-"}</td>
                                <td>${pof?.Designado_Cargo || "-"}</td>

                                <!-- Información Institucional -->
                                <td>${institucional?.CUE || "-"}</td>
                                <td>${institucional?.Codigo_Liq || "-"}</td>
                                <td>${institucional?.Area_Liq || "-"}</td>
                                <td>${
                                    institucional?.Nombre_Institucion || "-"
                                }</td>
                                <td>${institucional?.Nivel || "-"}</td>
                                <td>${institucional?.Zona || "-"}</td>
                                <td>${institucional?.Domicilio || "-"}</td>
                                <td>${institucional?.Localidad || "-"}</td>

                                <!-- Información Aúlica -->
                                <td>${aulica?.Nombre_Institucion || "-"}</td>
                                <td>${aulica?.Aula || "-"}</td>
                                <td>${aulica?.Division || "-"}</td>
                                <td>${aulica?.Turno || "-"}</td>
                                <td>${aulica?.EspCur || "-"}</td>
                                <td>${aulica?.Matricula || "-"}</td>
                                <td>${aulica?.Condicion || "-"}</td>
                                <td>${aulica?.EnFuncion || "-"}</td>
                                <td>${aulica?.observacion_cond || "-"}</td>
                                <td>${aulica?.AsisTotal || "-"}</td>
                                <td>${aulica?.AsistJust || "-"}</td>
                                <td>${aulica?.AsistInjust || "-"}</td>
                            </tr>
                        `;

                        $("#tabla-completa tbody").append(row);
                    });
                });
            },
            error: function (xhr, status, error) {
                if (xhr.status === 404) {
                    alert("No se encuentra cargado ese Agente con ese CUE.");
                } else {
                    console.error(xhr.responseText);
                    alert(
                        "Error al procesar la solicitud. Inténtalo nuevamente."
                    );
                }

                $("#tabla-completa tbody").empty();
            },
        });
    });
});
