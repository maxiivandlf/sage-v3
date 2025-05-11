document.addEventListener("DOMContentLoaded", function () {
    // Selecciona todos los inputs de orden
    const ordenInputs = document.querySelectorAll(".orden-input");
    const dniInputs = document.querySelectorAll(".dni-input");
    const apenomInputs = document.querySelectorAll(".apenom-input");
    const cargoInputs = document.querySelectorAll(".cargo-input");

    const aulaInputs = document.querySelectorAll(".aula-input");
    const divisionInputs = document.querySelectorAll(".division-input");

    const espcurInputs = document.querySelectorAll(".espcur-input");
    const turnoInputs = document.querySelectorAll(".turno-input");
    const horasInputs = document.querySelectorAll(".horas-input");
    const origenInputs = document.querySelectorAll(".origen-input");
    const sitrevInputs = document.querySelectorAll(".sitrev-input");
    const fechaaltacargoInputs = document.querySelectorAll(
        ".fechaaltacargo-input"
    );
    const fechadesignadoInputs = document.querySelectorAll(
        ".fechadesignado-input"
    );
    const condicionInputs = document.querySelectorAll(".condicion-input");
    const fechadesddeInputs = document.querySelectorAll(".fechadesde-input");
    const fechahastaInputs = document.querySelectorAll(".fechahasta-input");
    const motivosInputs = document.querySelectorAll(".motivos-input");
    const activoInputs = document.querySelectorAll(".activo-input");
    const datosporcondicionInputs = document.querySelectorAll(
        ".datosporcondicion-input"
    );
    const antiguedadInputs = document.querySelectorAll(".antiguedad-input");
    const agenteRInputs = document.querySelectorAll(".agenter-input");
    const novedadesInputs = document.querySelectorAll(".novedades-input");
    //asistencias
    const asistenciaInputs = document.querySelectorAll(".asistencia-input");
    const asistenciajusInputs = document.querySelectorAll(
        ".asistenciajus-input"
    );
    const asistenciainInputs = document.querySelectorAll(".asistenciain-input");
    const observacionesInputs = document.querySelectorAll(
        ".observaciones-input"
    );

    const carreraInputs = document.querySelectorAll(".carrera-input");
    const orientacionInputs = document.querySelectorAll(".orientacion-input");
    const tituloInputs = document.querySelectorAll(".titulo-input");
    const matriculaInputs = document.querySelectorAll(".matricula-input");

    //para supervision
    const zonasuperInputs = document.querySelectorAll(".zonasupervision-input");

    // Función para actualizar los índices de las filas
    function updateTableIndices() {
        $("#POFMH tbody tr").each(function (index) {
            // Solo actualiza los índices en las filas no confirmadas
            if (!$(this).find("td:first").hasClass("confirmed")) {
                $(this)
                    .find("td:first")
                    .text(index + 1); // Usa el índice como número temporal
            }
        });
    }

    function createNewRow(orden = "1", dni = "Nuevo DNI", currentRow) {
        var newRow = `
            <tr data-id=""  class="fila" data-bg-color="default">
                <td data-id="" >
                    <input type="checkbox" data-id="" >
                </td>
                <td><input type="text" name="dato1[]" value="${orden}" class="orden-input" data-id=""></td>
                <td><input type="text" name="dato2[]" value="${dni}" class="dni-input" data-id="" id=""></td>
                <td><input type="text" name="dato3[]" value="" class="apenom-input" data-id="" id=""></td>
                <td>
                    <select class="form-control origen-input" name="Origen" data-id="">
                        <option value="-1" disabled selected>SELECCIONE UNA OPCIÓN</option>
                        ${generarOpcionesOrigenCargo([])}
                    </select>
                </td>
                 <td>
                    <select class="form-control sitrev-input" name="SitRev" data-id="">
                        <option value="-1" disabled selected>SELECCIONE UNA OPCIÓN</option>
                        ${generarOpcionesSitRev([])}
                    </select>
                </td>
                <td><input type="text" name="Horas" value="" class="horas-input" data-id=""></td>
                <td><input type="text" name="Antiguedad" value="" class="antiguedad-input" data-id=""></td>
                <td>
                    <select class="form-control cargo-input" name="CargoSalarial" data-id="">
                        <option value="-1" disabled selected>SELECCIONE UNA OPCIÓN</option>
                        ${generarOpcionesCargos([])}
                    </select>
                </td>
                
                <td>
                    <select class="form-control aula-input" name="Aula" data-id="">
                        <option value="-1" disabled selected>SELECCIONE UNA OPCIÓN</option>
                        ${generarOpcionesAulas([])}
                    </select>
                </td>
                <td>
                    <select class="form-control division-input" name="Division" data-id="">
                        <option value="-1" disabled selected>SELECCIONE UNA OPCIÓN</option>
                        ${generarOpcionesDivisiones([])}
                    </select>
                </td>
                <td>
                    <select class="form-control turno-input" name="Turno" data-id="">
                        <option value="-1" disabled selected>SELECCIONE UNA OPCIÓN</option>
                        ${generarOpcionesTurnos([])}
                    </select>
                </td>
                <td><input type="text" name="EspCur" value="" class="espcur-input" data-id=""></td>
                <td><input type="text" name="Matricula" value="" class="matricula-input" data-id=""></td>
                <td><input type="date" name="AltaCargo" value="" class="fechaaltacargo-input" data-id=""></td>
                <td><input type="date" name="Designado" value="" class="fechadesignado-input" data-id=""></td>
                <td>
                    <select class="form-control condicion-input" name="Condicion" data-id="">
                        ${generarOpcionesCondicion([])}
                    </select>
                </td>                
                <td>
                    <select class="form-control activo-input" name="Activo" data-id="">
                        ${generarOpcionesActivo([])}
                    </select>
                </td> 
                 <td>
                    <select class="form-control motivos-input" name="Motivos" data-id="">
                        ${generarOpcionesMotivos([])}
                    </select>
                </td>
                <td><textarea name="DatosPorCondicion" class="datosporcondicion-input" data-id=""></textarea></td>               
                <td><input type="date" name="Desde" value="" class="fechadesde-input" data-id=""></td>
                <td><input type="date" name="Hasta" value="" class="fechahasta-input" data-id=""></td>               
                <td><input type="text" name="AgenteR" id="AgenteR" value="" class="agenter-input" data-id=""></td>
                <td>
                  <button type="button" class="btn btn-default view-novedades" data-toggle="modal" data-target="#modal-novedades" data-id="">
                            <i class="fas fa-newspaper"></i>
                    </button>
                </td>
                
                <td>
                    <textarea name="Observaciones" class="observaciones-input" data-id=""></textarea>
                </td>
                <td>
                    <input type="text" name="Carrera" value="" class="carrera-input" data-id="">
                </td>
                <td>
                    <input type="text" name="Orientacion" value="" class="orientacion-input" data-id="">
                </td>
                <td>
                    <input type="text" name="Titulo" value="" class="titulo-input" data-id="">
                </td>
                <td>
                    <span class="add-row">
                        <i class="fas fa-plus-circle"></i>
                    </span>
                     <span class="ir-al-izquierda-btn" id="irAlIzquierdaBtn">
                        <i class="fas fa-arrow-circle-left"></i> 
                    </span>
                    <span class="confirmarFilaCompleta" id="confirmarFilaCompleta" data-id="">
                        <i class="fas fa-check"></i> 
                    </span>
                    <span style="margin-right: 2rem">
                        |
                    </span>
                    <span class="delete-row">
                        <i class="fas fa-eraser"></i>
                    </span>
                </td>
                 <td>
                    <textarea name="zonasupervision" id="zonasupervision"   class="zonasupervision-input" data-id=""></textarea>
                </td>
            </tr>`;

        /*
            
            */
        // Añadir la nueva fila después de la fila actual
        if (currentRow) {
            currentRow.after(newRow);
        } else {
            $("#POFMH tbody").append(newRow);
        }

        // Obtener la última fila agregada
        var row = $("#POFMH tbody").find("tr:last");

        // Recoger todos los datos necesarios
        var csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content");
        var cue = document.querySelector("#valCUE").value;
        var turno = document.querySelector("#valTurno").value;

        // Hacer la solicitud al servidor
        fetch("/crearRegistro", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
            },
            body: JSON.stringify({
                nuevo: true,
                cue: cue,
                turno: turno,
                // Agregar otros campos necesarios aquí
            }),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    row.attr("data-id", data.id);
                    row.find("td:first").attr("data-id", data.id);
                    row.find("td:first").append(`<span>${data.id}</span>`);
                    //row.find('td:first').attr('data-id', data.id).text(data.id);
                    row.find("input, select, textarea").attr(
                        "data-id",
                        data.id
                    );
                    row.find(".dni-input").attr("id", `dni-input-${data.id}`);
                    row.find(".apenom-input").attr(
                        "id",
                        `apenom-input-${data.id}`
                    );
                    row.find(".confirmarFilaCompleta").attr(
                        "id",
                        `confirmarFilaCompleta-${data.id}`
                    );
                    row.find(".confirmarFilaCompleta").attr("data-id", data.id);
                    // Llama a la función para llenar los combos en la nueva fila
                    llenarCombosFila(row);
                    setUpDniValidation();
                } else {
                    alert("Error al confirmar el DNI: " + data.message);
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                alert("Hubo un error al crear el registro.");
            });
    }

    async function createIntermediateRow(
        orden = "1",
        dni = "Nuevo DNI",
        currentRow
    ) {
        var newRow = `
            <tr data-id="" class="fila" data-bg-color="default">
                <td data-id="" >
                    <input type="checkbox" data-id="">
                </td>
                <td><input type="text" name="dato1[]" value="${orden}" class="orden-input" data-id=""></td>
                <td><input type="text" name="dato2[]" value="${dni}" class="dni-input" data-id="" id=""></td>
                <td><input type="text" name="dato3[]" value="" class="apenom-input" data-id="" id=""></td>
                <td>
                    <select class="form-control origen-input" name="Origen" data-id="">
                        ${generarOpcionesOrigenCargo([])}
                    </select>
                </td>
                 <td>
                    <select class="form-control sitrev-input" name="SitRev" data-id="">
                        ${generarOpcionesSitRev([])}
                    </select>
                </td>
                <td><input type="text" name="Horas" value="" class="horas-input" data-id=""></td>
                <td><input type="text" name="Antiguedad" value="" class="antiguedad-input" data-id=""></td>
                <td>
                    <select class="form-control cargo-input" name="CargoSalarial" data-id="">
                        ${generarOpcionesCargos([])}
                    </select>
                </td>

                <td>
                    <select class="form-control aula-input" name="Aula" data-id="">
                        ${generarOpcionesAulas([])}
                    </select>
                </td>
                <td>
                    <select class="form-control division-input" name="Division" data-id="">
                        ${generarOpcionesDivisiones([])}
                    </select>
                </td>
                <td>
                    <select class="form-control turno-input" name="Turno" data-id="">
                        ${generarOpcionesTurnos([])}
                    </select>
                </td>
                <td><input type="text" name="EspCur" value="" class="espcur-input" data-id=""></td>
                <td><input type="text" name="Matricula" value="" class="matricula-input" data-id=""></td>
                <td><input type="date" name="AltaCargo" value="" class="fechaaltacargo-input" data-id=""></td>
                <td><input type="date" name="Designado" value="" class="fechadesignado-input" data-id=""></td>
                <td>
                    <select class="form-control condicion-input" name="Condicion" data-id="">
                        ${generarOpcionesCondicion([])}
                    </select>
                </td>                
                <td>
                    <select class="form-control activo-input" name="Activo" data-id="">
                        ${generarOpcionesActivo([])}
                    </select>
                </td> 
                 <td>
                    <select class="form-control motivos-input" name="Motivos" data-id="">
                        ${generarOpcionesMotivos([])}
                    </select>
                </td>
                <td><textarea name="DatosPorCondicion" class="datosporcondicion-input" data-id=""></textarea></td>               
                <td><input type="date" name="Desde" value="" class="fechadesde-input" data-id=""></td>
                <td><input type="date" name="Hasta" value="" class="fechahasta-input" data-id=""></td>               
                <td><input type="text" name="AgenteR" id="AgenteR"  value="" class="agenter-input" data-id=""></td>
                <td>
                  <button type="button" class="btn btn-default view-novedades" data-toggle="modal" data-target="#modal-novedades" data-id="">
                            <i class="fas fa-newspaper"></i>
                    </button>
                </td>
                
                <td>
                    <textarea name="Observaciones" class="observaciones-input" data-id=""></textarea>
                </td>
                <td>
                    <input type="text" name="Carrera" value="" class="carrera-input" data-id="">
                </td>
                <td>
                    <input type="text" name="Orientacion" value="" class="orientacion-input" data-id="">
                </td>
                <td>
                    <input type="text" name="Titulo" value="" class="titulo-input" data-id="">
                </td>
                <td>
                    <span class="add-row">
                        <i class="fas fa-plus-circle"></i>
                    </span>
                     <span class="ir-al-izquierda-btn" id="irAlIzquierdaBtn">
                        <i class="fas fa-arrow-circle-left"></i> 
                    </span>
                    <span class="confirmarFilaCompleta" id="confirmarFilaCompleta"  data-id="">
                        <i class="fas fa-check"></i> 
                    </span>
                    <span style="margin-right: 2rem">
                        |
                    </span>
                    <span class="delete-row">
                        <i class="fas fa-eraser"></i>
                    </span>
                </td>
                 <td>
                    <textarea name="zonasupervision" id="zonasupervision"  class="zonasupervision-input" data-id=""></textarea>
                </td>
            </tr>`;

        /*
             
            */
        // Añadir la nueva fila después de la fila actual
        currentRow.after(newRow);

        // Obtener la última fila agregada
        var row = currentRow.next();

        // Recoger todos los datos necesarios
        var csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content");
        var cue = document.querySelector("#valCUE").value;
        var turno = document.querySelector("#valTurno").value;

        // Hacer la solicitud al servidor
        try {
            const response = await fetch("/crearRegistro", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify({
                    nuevo: true,
                    cue: cue,
                    turno: turno,
                    // Agregar otros campos necesarios aquí
                }),
            });

            const data = await response.json();

            if (data.success) {
                // Establecer el ID en la nueva fila
                row.attr("data-id", data.id);
                row.find("td:first").attr("data-id", data.id);
                row.find("td:first").append(`<span>${data.id}</span>`);
                //row.find('td:first').attr('data-id', data.id).text(data.id);
                row.find("input, select, textarea").attr("data-id", data.id);
                row.find(".dni-input").attr("id", `dni-input-${data.id}`);
                row.find(".apenom-input").attr("id", `apenom-input-${data.id}`);
                row.find(".confirmarFilaCompleta").attr(
                    "id",
                    `confirmarFilaCompleta-${data.id}`
                );
                row.find(".confirmarFilaCompleta").attr("data-id", data.id);
                // Actualizar los data-id de las filas siguientes
                updateDataIds(row.nextAll(), data.id);

                // Llama a la función para llenar los combos en la nueva fila
                llenarCombosFila(row);
                setUpDniValidation();
                const lastRow = $("#POFMH tbody tr:last-child")[0]; // Obtener la última fila creada
                if (lastRow) {
                    // Calcular la posición de la fila respecto al lado izquierdo
                    const rowPositionLeft =
                        lastRow.getBoundingClientRect().left +
                        (window.scrollX - 500);

                    window.scrollTo({
                        left: rowPositionLeft, // Desplazar hacia la posición horizontal calculada
                        behavior: "smooth", // Desplazamiento suave
                    });
                }
            } else {
                Swal.fire(
                    "Fila creada",
                    `Error al confirmar el DNI: ${data.message}.`,
                    "error"
                );
            }
        } catch (error) {
            console.error("Error:", error);
            Swal.fire(
                "Fila creada",
                `Hubo un error al crear el registro.`,
                "error"
            );
        }
    }

    // Función para actualizar los data-id de las filas siguientes
    function updateDataIds(rows, startId) {
        rows.each(function (index) {
            const newId = parseInt(startId) + index + 1; // Incrementar el ID por fila
            $(this).find("td:first").attr("data-id", newId).text(newId);
            $(this).find("input, select, textarea").attr("data-id", newId);
        });
    }

    // Evento para agregar la primera fila
    $("#addFirstRowBtn").click(function () {
        if ($("#POFMH tbody tr").length === 0) {
            createNewRow("1", "");
        } else {
            Swal.fire(
                "Fila creada!",
                'Ya existe al menos una fila. Puedes agregar más filas usando el botón "+" en cada fila.',
                "warning"
            );
        }
    });

    //boton de fila abajo fuera de la tabla
    $("#addLastRow").click(function () {
        Swal.fire({
            title: "¿Estás seguro?",
            text: "Esta acción creará una nueva fila.",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí, crear fila",
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                createNewRow("1", "");
                Swal.fire(
                    "Fila creada!",
                    "La nueva fila ha sido agregada.",
                    "success"
                );
            }
        });
    });

    //boton para ir al ultimo
    $("#irAlUltimoBtn").click(function () {
        // Obtener la última fila de la tabla
        var lastRow = $("#POFMH tbody tr:last");

        // Verificar si hay filas en la tabla
        if (lastRow.length) {
            // Desplazar el scroll hasta la última fila
            $("#cardPOFMH, #POFMH").animate(
                {
                    scrollTop: lastRow.offset().top,
                },
                500
            ); // 500 es la duración de la animación en milisegundos
            $("html, body").animate(
                {
                    scrollTop: $(document).height(), // Desplazar hasta el final del documento
                },
                500
            );
        } else {
            Swal.fire(
                "Fallo Crear Fila!",
                "No existe fila al último para poder desplazarse",
                "error"
            );
        }
    });

    //Actualización ir a izquierda
    $(document).on("click", ".ir-al-izquierda-btn", function () {
        // Seleccionar el contenedor principal que tiene el scroll horizontal
        const scrollContainer = $(".card"); // selector .card
        if (scrollContainer.length) {
            scrollContainer.animate(
                {
                    scrollLeft: 0,
                },
                500
            ); // Duración de la animación
        }
    });

    // Evento para agregar una nueva fila intermedia
    $("#POFMH").on("click", ".add-row", async function () {
        const currentRow = $(this).closest("tr"); // Encontrar la fila que contiene el botón
        const dataId = currentRow.data("id"); // Obtener el data-id de la fila correctamente desde currentRow
        const dniValue = currentRow.find("#AgenteR").val(); // Obtener el valor del DNI
        var filaId = $(this).data("id");

        // Usar el data-id para obtener los valores de DNI y ApeNom de la fila correspondiente
        var dni = $("#AgenteR-" + dataId).val();
        console.log(dni);

        // SweetAlert2 para confirmar la acción
        Swal.fire({
            title: "¿Estás seguro?",
            text: `Vas a agregar una fila intermedia con el DNI: ${dni}.`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí, agregar fila",
            cancelButtonText: "Cancelar",
        }).then(async (result) => {
            if (result.isConfirmed) {
                // Si el usuario confirma, llamamos a la función
                await createIntermediateRow("1.1", dni, currentRow); // Llama a la función y espera a que se llene la fila

                // Mensaje de éxito tras la confirmación
                Swal.fire(
                    "Fila creada",
                    `La fila intermedia con el DNI: ${dni} ha sido agregada.`,
                    "success"
                );
            }
        });
    });

    // Evento para eliminar una fila
    $("#POFMH").on("click", ".delete-row", function () {
        const row = $(this).closest("tr"); // Guardar la fila a eliminar
        const dataId = row.data("id"); // Obtener el data-id de la fila

        // Mostrar la alerta de confirmación
        Swal.fire({
            title: "Esta seguro de borrar este registro?",
            text: "El borrado no tiene reversa!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, acepto borrar!",
        }).then((result) => {
            if (result.isConfirmed) {
                const csrfToken = document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content");
                // Realizar la solicitud fetch para eliminar la fila en el servidor
                fetch(`/borrarFilaPofmh`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken, // Asegúrate de que este token se envía
                    },
                    body: JSON.stringify({ id: dataId }), // Enviar el id como JSON
                }).then((response) => {
                    return response.json().then((data) => {
                        row.remove(); // Eliminar la fila
                        // updateTableIndices(); // Actualizar los índices al eliminar
                        Swal.fire({
                            title: "Borrado!",
                            text: data.msg, // Mensaje del servidor
                            icon: "success",
                        });
                    });
                });
            }
        });
    });

    // Agrega el evento de clic
    // Suponiendo que cada fila tiene la clase "fila" y el botón "confirmarFilaCompleta" está dentro de esa fila
    const confirmarFilaCompletaBtn = document.getElementById(
        "confirmarFilaCompleta"
    );

    const tabla = document.querySelector("#POFMH tbody");

    // Asigna el evento click a la tabla
    tabla.addEventListener("click", function (event) {
        // Verifica si el elemento que disparó el evento tiene la clase 'confirmarFilaCompleta'
        const button = event.target.closest(".confirmarFilaCompleta");
        const idPofmh = event.target.getAttribute("data-id");
        //console.log(idPofmh)
        if (button) {
            // Si es un botón dentro de la fila
            const fila = $(button.closest(".fila")); // Encuentra la fila contenedora del botón

            // Para depuración: muestra el contenido de la fila
            console.log("Fila encontrada:", fila);

            const datosFila = {}; // Objeto para almacenar los datos

            // Obtén el ID de la fila usando jQuery
            const id = fila.data("id"); // Obtiene el ID desde el atributo data-id
            console.log("ID de la fila:", id); // Verifica el ID capturado

            // Verifica si el ID es undefined o null
            if (id === undefined || id === null) {
                console.error("No se pudo encontrar el ID de la fila.");
                return; // Sale si no hay ID
            }

            datosFila.id = id; // Añade el ID al objeto de datos

            // Captura los valores de los inputs y selects en la fila
            const inputs = [
                { class: "orden-input", key: "orden" },
                { class: "dni-input", key: "dni" },
                { class: "apenom-input", key: "apenom" },
                { class: "cargo-input", key: "cargo" },
                { class: "aula-input", key: "aula" },
                { class: "division-input", key: "division" },
                { class: "espcur-input", key: "espcur" },
                { class: "turno-input", key: "turno" },
                { class: "horas-input", key: "horas" },
                { class: "origen-input", key: "origen" },
                { class: "sitrev-input", key: "sitrev" },
                { class: "fechaaltacargo-input", key: "fechaAltaCargo" },
                { class: "fechadesignado-input", key: "fechaDesignado" },
                { class: "condicion-input", key: "condicion" },
                { class: "fechadesde-input", key: "fechaDesde" },
                { class: "fechahasta-input", key: "fechaHasta" },
                { class: "motivos-input", key: "motivos" },
                { class: "activo-input", key: "activo" },
                { class: "datosporcondicion-input", key: "datosPorCondicion" },
                { class: "antiguedad-input", key: "antiguedad" },
                { class: "agenter-input", key: "agenteR" },
                { class: "novedades-input", key: "novedades" },
                { class: "asistencia-input", key: "asistencia" },
                { class: "asistenciajus-input", key: "asistenciaJustificada" },
                { class: "asistenciain-input", key: "asistenciaInjustificada" },
                { class: "observaciones-input", key: "observaciones" },
                { class: "carrera-input", key: "carrera" },
                { class: "orientacion-input", key: "orientacion" },
                { class: "titulo-input", key: "titulo" },
                { class: "matricula-input", key: "matricula" },
            ];

            // Recorre cada clase y captura el valor correspondiente
            inputs.forEach((inputInfo) => {
                const inputElement = fila.find(`.${inputInfo.class}`);
                if (inputElement.length) {
                    const valor = inputElement.val(); // Obtiene el valor
                    if (valor) {
                        // Solo agregar si hay un valor
                        datosFila[inputInfo.key] = valor;
                    }
                }
            });
            // Muestra el SweetAlert de confirmación
            Swal.fire({
                title: "¿Estás seguro?",
                text: "Esta acción no se puede deshacer.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Sí, continuar",
                cancelButtonText: "No, cancelar",
            }).then((result) => {
                if (result.isConfirmed) {
                    fila.find('td:first input[type="checkbox"]').prop(
                        "checked",
                        false
                    );

                    // Quitar el borde rojo de los inputs
                    const inputsEditable = fila.find(
                        'input[type="text"], input[type="number"], textarea'
                    );
                    inputsEditable.each(function () {
                        $(this).removeClass("input-editable"); // Quita la clase para el borde
                    });
                    //console.log('Datos a enviar:', datosFila); // Verifica los datos a enviar
                    // Aquí puedes llamar a tu función para enviar los datos
                    enviarDatos(datosFila); // Asegúrate de que esta función esté definida
                }
            });
        }
    });

    // Función para enviar los datos a las rutas
    function enviarDatos(datos) {
        // Aquí se puede realizar el envío de los datos a la ruta correspondiente
        fetch("/actualizarDatos", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
            },
            body: JSON.stringify(datos), // Envía todos los datos capturados
        })
            .then((response) => response.json())
            .then((data) => {
                console.log(data.datos);
                console.log(data.info);
                if (data.success) {
                    console.log("Datos actualizados con éxito:", data);
                    Swal.fire({
                        title: "Éxito",
                        text: "Los datos han sido actualizados.",
                        icon: "success",
                    });
                } else {
                    console.error(
                        "Error al actualizar los datos:",
                        data.message
                    );
                    Swal.fire({
                        title: "Error",
                        text: "No se pudieron actualizar los datos.",
                        icon: "error",
                    });
                }
            })
            .catch((error) => console.error("Error en la solicitud:", error));
    }

    const tableBody = document.querySelector("#POFMH tbody");

    tableBody.addEventListener(
        "blur",
        function (event) {
            if (event.target.matches(".orden-input")) {
                const datoACambiar = event.target.value; // Captura el nuevo valor
                const idPofmh = event.target.getAttribute("data-id"); // Captura el ID del registro
                const checkbox = event.target
                    .closest("tr")
                    .querySelector('td:first-child input[type="checkbox"]');
                if (!checkbox.checked) {
                    event.target.setAttribute("disabled", "disabled"); // Deshabilita el input solo si el checkbox no está activo
                }
                // Realiza la solicitud al servidor
                fetch("/actualizarOrden", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    body: JSON.stringify({
                        idPofmh: idPofmh,
                        datoACambiar: datoACambiar,
                    }),
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            console.log("Dato actualizado con éxito:", data);
                        } else {
                            console.error(
                                "Error al actualizar el dato:",
                                data.message
                            );
                        }
                    })
                    .catch((error) =>
                        console.error("Error en la solicitud:", error)
                    );

                // Manejo del input de DNI
            }

            //dni
            if (event.target.matches(".dni-input")) {
                const dniInput = event.target;
                const dni = event.target.value; // Captura el nuevo valor (DNI)
                const idPofmh = event.target.getAttribute("data-id"); // Captura el ID del registro
                const checkbox = event.target
                    .closest("tr")
                    .querySelector('td:first-child input[type="checkbox"]');
                if (!checkbox.checked) {
                    event.target.setAttribute("disabled", "disabled"); // Deshabilita el input solo si el checkbox no está activo
                }

                // Realiza la solicitud al servidor para actualizar el DNI
                fetch("/actualizarDNI", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken, // Asegúrate de tener el token CSRF
                    },
                    body: JSON.stringify({
                        idPofmh: idPofmh,
                        datoACambiar: dni,
                    }), // Envía el ID y el nuevo valor
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            console.log("Dato actualizado con éxito:", data);

                            // Ahora realiza la verificación del DNI
                            return fetch("/verificarDNI", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": csrfToken,
                                },
                                body: JSON.stringify({ dni: dni }), // Envía el DNI para la verificación
                            });
                        } else {
                            console.error(
                                "Error al actualizar el dato:",
                                data.message
                            );
                        }
                    })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            console.log("DNI encontrado:", data);
                            // Cambia el color del input a verde si el DNI existe
                            event.target.style.backgroundColor = "lightgreen";
                            const apenomInput = document.querySelector(
                                `#apenom-input-${idPofmh}`
                            );
                            // Actualiza el valor con el nombre y apellido
                            apenomInput.value = `${data.ApeNom}`;
                            // Habilita el input del apellido y escucha el evento blur
                            apenomInput.removeAttribute("disabled");
                            apenomInput.addEventListener("blur", function () {
                                const datoACambiar = this.value.toUpperCase(); // Captura el nuevo valor
                                this.value = datoACambiar;
                                const idPofmh = this.getAttribute("data-id"); // Captura el ID del registro
                                this.setAttribute("disabled", "disabled");

                                // Realiza la solicitud al servidor para actualizar el apellido
                                fetch("/actualizarApeNom", {
                                    method: "POST",
                                    headers: {
                                        "Content-Type": "application/json",
                                        "X-CSRF-TOKEN": csrfToken, // Asegúrate de tener el token CSRF
                                    },
                                    body: JSON.stringify({
                                        idPofmh: idPofmh,
                                        datoACambiar: datoACambiar,
                                    }), // Envía el ID y el nuevo valor
                                })
                                    .then((response) => response.json())
                                    .then((data) => {
                                        if (data.success) {
                                            console.log(
                                                "Apellido actualizado con éxito:",
                                                data
                                            );
                                        } else {
                                            console.error(
                                                "Error al actualizar el apellido:",
                                                data.message
                                            );
                                        }
                                    })
                                    .catch((error) =>
                                        console.error(
                                            "Error en la solicitud:",
                                            error
                                        )
                                    );
                            });
                        } else {
                            console.log("DNI no encontrado:", data.message);
                            // Cambia el color del input a rosado claro si el DNI no existe
                            event.target.style.backgroundColor = "lightpink";
                        }
                    })
                    .catch((error) =>
                        console.error("Error en la solicitud:", error)
                    );
            }

            //apellido
            if (event.target.matches(".apenom-input")) {
                const datoACambiar = event.target.value.toUpperCase(); // Captura el nuevo valor
                event.target.value = datoACambiar;
                const idPofmh = event.target.getAttribute("data-id"); // Captura el ID del registro
                const checkbox = event.target
                    .closest("tr")
                    .querySelector('td:first-child input[type="checkbox"]');
                if (!checkbox.checked) {
                    event.target.setAttribute("disabled", "disabled"); // Deshabilita el input solo si el checkbox no está activo
                }
                // Realiza la solicitud al servidor
                fetch("/actualizarApeNom", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken, // Asegúrate de tener el token CSRF
                    },
                    body: JSON.stringify({
                        idPofmh: idPofmh,
                        datoACambiar: datoACambiar,
                    }), // Envía el ID y el nuevo valor
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            console.log("Dato actualizado con éxito:", data);
                        } else {
                            console.error(
                                "Error al actualizar el dato:",
                                data.message
                            );
                        }
                    })
                    .catch((error) =>
                        console.error("Error en la solicitud:", error)
                    );
            }

            //cargos salariales
            if (event.target.matches(".cargo-input")) {
                const datoACambiar = event.target.value.toUpperCase(); // Captura el nuevo valor y lo convierte a mayúsculas
                event.target.value = datoACambiar; // Actualiza el valor en el select
                const idPofmh = event.target.getAttribute("data-id"); // Captura el ID del registro
                // Realiza la solicitud al servidor
                fetch("/actualizarCargoSalarial", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken, // Asegúrate de tener el token CSRF
                    },
                    body: JSON.stringify({
                        idPofmh: idPofmh,
                        datoACambiar: datoACambiar,
                    }), // Envía el ID y el nuevo valor
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            console.log(
                                "Cargo salarial actualizado con éxito:",
                                data
                            );
                        } else {
                            console.error(
                                "Error al actualizar el cargo salarial:",
                                data.message
                            );
                        }
                    })
                    .catch((error) =>
                        console.error("Error en la solicitud:", error)
                    );
            }

            if (event.target.matches(".aula-input")) {
                const datoACambiar = event.target.value.toUpperCase(); // Captura el nuevo valor y lo convierte a mayúsculas
                event.target.value = datoACambiar; // Actualiza el valor en el select
                const idPofmh = event.target.getAttribute("data-id"); // Captura el ID del registro
                // Realiza la solicitud al servidor
                fetch("/actualizarAula", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken, // Asegúrate de tener el token CSRF
                    },
                    body: JSON.stringify({
                        idPofmh: idPofmh,
                        datoACambiar: datoACambiar,
                    }), // Envía el ID y el nuevo valor
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            console.log(
                                "Cargo salarial actualizado con éxito:",
                                data
                            );
                        } else {
                            console.error(
                                "Error al actualizar el cargo salarial:",
                                data.message
                            );
                        }
                    })
                    .catch((error) =>
                        console.error("Error en la solicitud:", error)
                    );
            }
            //divisiones
            if (event.target.matches(".division-input")) {
                const datoACambiar = event.target.value.toUpperCase(); // Captura el nuevo valor y lo convierte a mayúsculas
                event.target.value = datoACambiar; // Actualiza el valor en el select
                const idPofmh = event.target.getAttribute("data-id"); // Captura el ID del registro
                // Realiza la solicitud al servidor
                fetch("/actualizarDivision", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken, // Asegúrate de tener el token CSRF
                    },
                    body: JSON.stringify({
                        idPofmh: idPofmh,
                        datoACambiar: datoACambiar,
                    }), // Envía el ID y el nuevo valor
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            console.log(
                                "Cargo salarial actualizado con éxito:",
                                data
                            );
                        } else {
                            console.error(
                                "Error al actualizar el cargo salarial:",
                                data.message
                            );
                        }
                    })
                    .catch((error) =>
                        console.error("Error en la solicitud:", error)
                    );
            }

            if (event.target.matches(".espcur-input")) {
                const datoACambiar = event.target.value.toUpperCase(); // Captura el nuevo valor y lo convierte a mayúsculas
                event.target.value = datoACambiar; // Actualiza el valor en el select
                const idPofmh = event.target.getAttribute("data-id"); // Captura el ID del registro
                const checkbox = event.target
                    .closest("tr")
                    .querySelector('td:first-child input[type="checkbox"]');
                if (!checkbox.checked) {
                    event.target.setAttribute("disabled", "disabled"); // Deshabilita el input solo si el checkbox no está activo
                }
                // Realiza la solicitud al servidor
                fetch("/actualizarEspCur", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken, // Asegúrate de tener el token CSRF
                    },
                    body: JSON.stringify({
                        idPofmh: idPofmh,
                        datoACambiar: datoACambiar,
                    }), // Envía el ID y el nuevo valor
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            console.log(
                                "Cargo salarial actualizado con éxito:",
                                data
                            );
                        } else {
                            console.error(
                                "Error al actualizar el cargo salarial:",
                                data.message
                            );
                        }
                    })
                    .catch((error) =>
                        console.error("Error en la solicitud:", error)
                    );
            }

            if (event.target.matches(".matricula-input")) {
                const datoACambiar = event.target.value.toUpperCase(); // Captura el nuevo valor y lo convierte a mayúsculas
                event.target.value = datoACambiar; // Actualiza el valor en el select
                const idPofmh = event.target.getAttribute("data-id"); // Captura el ID del registro
                const checkbox = event.target
                    .closest("tr")
                    .querySelector('td:first-child input[type="checkbox"]');
                if (!checkbox.checked) {
                    event.target.setAttribute("disabled", "disabled"); // Deshabilita el input solo si el checkbox no está activo
                }
                // Realiza la solicitud al servidor
                fetch("/actualizarMatricula", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken, // Asegúrate de tener el token CSRF
                    },
                    body: JSON.stringify({
                        idPofmh: idPofmh,
                        datoACambiar: datoACambiar,
                    }), // Envía el ID y el nuevo valor
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            console.log(
                                "Cargo salarial actualizado con éxito:",
                                data
                            );
                        } else {
                            console.error(
                                "Error al actualizar el cargo salarial:",
                                data.message
                            );
                        }
                    })
                    .catch((error) =>
                        console.error("Error en la solicitud:", error)
                    );
            }

            if (event.target.matches(".turno-input")) {
                const datoACambiar = event.target.value.toUpperCase(); // Captura el nuevo valor y lo convierte a mayúsculas
                event.target.value = datoACambiar; // Actualiza el valor en el select
                const idPofmh = event.target.getAttribute("data-id"); // Captura el ID del registro
                // Realiza la solicitud al servidor
                fetch("/actualizarTurno", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken, // Asegúrate de tener el token CSRF
                    },
                    body: JSON.stringify({
                        idPofmh: idPofmh,
                        datoACambiar: datoACambiar,
                    }), // Envía el ID y el nuevo valor
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            console.log(
                                "Cargo salarial actualizado con éxito:",
                                data
                            );
                        } else {
                            console.error(
                                "Error al actualizar el cargo salarial:",
                                data.message
                            );
                        }
                    })
                    .catch((error) =>
                        console.error("Error en la solicitud:", error)
                    );
            }

            if (event.target.matches(".horas-input")) {
                const datoACambiar = event.target.value.toUpperCase(); // Captura el nuevo valor y lo convierte a mayúsculas
                event.target.value = datoACambiar; // Actualiza el valor en el select
                const idPofmh = event.target.getAttribute("data-id"); // Captura el ID del registro
                const checkbox = event.target
                    .closest("tr")
                    .querySelector('td:first-child input[type="checkbox"]');
                if (!checkbox.checked) {
                    event.target.setAttribute("disabled", "disabled"); // Deshabilita el input solo si el checkbox no está activo
                }
                // Realiza la solicitud al servidor
                fetch("/actualizarHoras", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken, // Asegúrate de tener el token CSRF
                    },
                    body: JSON.stringify({
                        idPofmh: idPofmh,
                        datoACambiar: datoACambiar,
                    }), // Envía el ID y el nuevo valor
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            console.log(
                                "Cargo salarial actualizado con éxito:",
                                data
                            );
                        } else {
                            console.error(
                                "Error al actualizar el cargo salarial:",
                                data.message
                            );
                        }
                    })
                    .catch((error) =>
                        console.error("Error en la solicitud:", error)
                    );
            }

            if (event.target.matches(".origen-input")) {
                const datoACambiar = event.target.value.toUpperCase(); // Captura el nuevo valor y lo convierte a mayúsculas
                event.target.value = datoACambiar; // Actualiza el valor en el select
                const idPofmh = event.target.getAttribute("data-id"); // Captura el ID del registro

                // Realiza la solicitud al servidor
                fetch("/actualizarOrigen", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken, // Asegúrate de tener el token CSRF
                    },
                    body: JSON.stringify({
                        idPofmh: idPofmh,
                        datoACambiar: datoACambiar,
                    }), // Envía el ID y el nuevo valor
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            console.log(
                                "Cargo salarial actualizado con éxito:",
                                data
                            );
                        } else {
                            console.error(
                                "Error al actualizar el cargo salarial:",
                                data.message
                            );
                        }
                    })
                    .catch((error) =>
                        console.error("Error en la solicitud:", error)
                    );
            }

            if (event.target.matches(".sitrev-input")) {
                const datoACambiar = event.target.value.toUpperCase(); // Captura el nuevo valor y lo convierte a mayúsculas
                event.target.value = datoACambiar; // Actualiza el valor en el select
                const idPofmh = event.target.getAttribute("data-id"); // Captura el ID del registro

                // Realiza la solicitud al servidor
                fetch("/actualizarSitRev", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken, // Asegúrate de tener el token CSRF
                    },
                    body: JSON.stringify({
                        idPofmh: idPofmh,
                        datoACambiar: datoACambiar,
                    }), // Envía el ID y el nuevo valor
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            console.log(
                                "Cargo salarial actualizado con éxito:",
                                data
                            );
                        } else {
                            console.error(
                                "Error al actualizar el cargo salarial:",
                                data.message
                            );
                        }
                    })
                    .catch((error) =>
                        console.error("Error en la solicitud:", error)
                    );
            }

            if (event.target.matches(".fechaaltacargo-input")) {
                const datoACambiar = event.target.value.toUpperCase(); // Captura el nuevo valor y lo convierte a mayúsculas
                event.target.value = datoACambiar; // Actualiza el valor en el select
                const idPofmh = event.target.getAttribute("data-id"); // Captura el ID del registro

                // Realiza la solicitud al servidor
                fetch("/actualizarFechaAltaCargo", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken, // Asegúrate de tener el token CSRF
                    },
                    body: JSON.stringify({
                        idPofmh: idPofmh,
                        datoACambiar: datoACambiar,
                    }), // Envía el ID y el nuevo valor
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            console.log(
                                "Cargo salarial actualizado con éxito:",
                                data
                            );
                        } else {
                            console.error(
                                "Error al actualizar el cargo salarial:",
                                data.message
                            );
                        }
                    })
                    .catch((error) =>
                        console.error("Error en la solicitud:", error)
                    );
            }

            if (event.target.matches(".fechadesignado-input")) {
                const datoACambiar = event.target.value.toUpperCase(); // Captura el nuevo valor y lo convierte a mayúsculas
                event.target.value = datoACambiar; // Actualiza el valor en el select
                const idPofmh = event.target.getAttribute("data-id"); // Captura el ID del registro

                // Realiza la solicitud al servidor
                fetch("/actualizarFechaDesignado", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken, // Asegúrate de tener el token CSRF
                    },
                    body: JSON.stringify({
                        idPofmh: idPofmh,
                        datoACambiar: datoACambiar,
                    }), // Envía el ID y el nuevo valor
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            console.log(
                                "Cargo salarial actualizado con éxito:",
                                data
                            );
                        } else {
                            console.error(
                                "Error al actualizar el cargo salarial:",
                                data.message
                            );
                        }
                    })
                    .catch((error) =>
                        console.error("Error en la solicitud:", error)
                    );
            }

            if (event.target.matches(".condicion-input")) {
                const datoACambiar = event.target.value.toUpperCase(); // Captura el nuevo valor y lo convierte a mayúsculas
                event.target.value = datoACambiar; // Actualiza el valor en el select
                const idPofmh = event.target.getAttribute("data-id"); // Captura el ID del registro

                // Realiza la solicitud al servidor
                fetch("/actualizarCondicion", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken, // Asegúrate de tener el token CSRF
                    },
                    body: JSON.stringify({
                        idPofmh: idPofmh,
                        datoACambiar: datoACambiar,
                    }), // Envía el ID y el nuevo valor
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            console.log(
                                "Cargo salarial actualizado con éxito:",
                                data
                            );
                        } else {
                            console.error(
                                "Error al actualizar el cargo salarial:",
                                data.message
                            );
                        }
                    })
                    .catch((error) =>
                        console.error("Error en la solicitud:", error)
                    );
            }

            if (event.target.matches(".activo-input")) {
                const datoACambiar = event.target.value.toUpperCase(); // Captura el nuevo valor y lo convierte a mayúsculas
                event.target.value = datoACambiar; // Actualiza el valor en el select
                const idPofmh = event.target.getAttribute("data-id"); // Captura el ID del registro

                // Realiza la solicitud al servidor
                fetch("/actualizarActivo", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken, // Asegúrate de tener el token CSRF
                    },
                    body: JSON.stringify({
                        idPofmh: idPofmh,
                        datoACambiar: datoACambiar,
                    }), // Envía el ID y el nuevo valor
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            console.log(
                                "Cargo salarial actualizado con éxito:",
                                data
                            );
                        } else {
                            console.error(
                                "Error al actualizar el cargo salarial:",
                                data.message
                            );
                        }
                    })
                    .catch((error) =>
                        console.error("Error en la solicitud:", error)
                    );
            }
            if (event.target.matches(".fechadesde-input")) {
                const datoACambiar = event.target.value.toUpperCase(); // Captura el nuevo valor y lo convierte a mayúsculas
                event.target.value = datoACambiar; // Actualiza el valor en el select
                const idPofmh = event.target.getAttribute("data-id"); // Captura el ID del registro

                // Realiza la solicitud al servidor
                fetch("/actualizarFechaDesde", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken, // Asegúrate de tener el token CSRF
                    },
                    body: JSON.stringify({
                        idPofmh: idPofmh,
                        datoACambiar: datoACambiar,
                    }), // Envía el ID y el nuevo valor
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            console.log(
                                "Cargo salarial actualizado con éxito:",
                                data
                            );
                        } else {
                            console.error(
                                "Error al actualizar el cargo salarial:",
                                data.message
                            );
                        }
                    })
                    .catch((error) =>
                        console.error("Error en la solicitud:", error)
                    );
            }

            if (event.target.matches(".fechahasta-input")) {
                const datoACambiar = event.target.value.toUpperCase(); // Captura el nuevo valor y lo convierte a mayúsculas
                event.target.value = datoACambiar; // Actualiza el valor en el select
                const idPofmh = event.target.getAttribute("data-id"); // Captura el ID del registro

                // Realiza la solicitud al servidor
                fetch("/actualizarFechaHasta", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken, // Asegúrate de tener el token CSRF
                    },
                    body: JSON.stringify({
                        idPofmh: idPofmh,
                        datoACambiar: datoACambiar,
                    }), // Envía el ID y el nuevo valor
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            console.log(
                                "Cargo salarial actualizado con éxito:",
                                data
                            );
                        } else {
                            console.error(
                                "Error al actualizar el cargo salarial:",
                                data.message
                            );
                        }
                    })
                    .catch((error) =>
                        console.error("Error en la solicitud:", error)
                    );
            }

            if (event.target.matches(".motivos-input")) {
                const datoACambiar = event.target.value.toUpperCase(); // Captura el nuevo valor y lo convierte a mayúsculas
                event.target.value = datoACambiar; // Actualiza el valor en el select
                const idPofmh = event.target.getAttribute("data-id"); // Captura el ID del registro

                // Realiza la solicitud al servidor
                fetch("/actualizarMotivo", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken, // Asegúrate de tener el token CSRF
                    },
                    body: JSON.stringify({
                        idPofmh: idPofmh,
                        datoACambiar: datoACambiar,
                    }), // Envía el ID y el nuevo valor
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            console.log(
                                "Cargo salarial actualizado con éxito:",
                                data
                            );
                        } else {
                            console.error(
                                "Error al actualizar el cargo salarial:",
                                data.message
                            );
                        }
                    })
                    .catch((error) =>
                        console.error("Error en la solicitud:", error)
                    );
            }

            if (event.target.matches(".datosporcondicion-input")) {
                const datoACambiar = event.target.value.toUpperCase(); // Captura el nuevo valor y lo convierte a mayúsculas
                event.target.value = datoACambiar; // Actualiza el valor en el select
                const idPofmh = event.target.getAttribute("data-id"); // Captura el ID del registro

                // Realiza la solicitud al servidor
                fetch("/actualizarDatosPorCondicion", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken, // Asegúrate de tener el token CSRF
                    },
                    body: JSON.stringify({
                        idPofmh: idPofmh,
                        datoACambiar: datoACambiar,
                    }), // Envía el ID y el nuevo valor
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            console.log(
                                "Cargo salarial actualizado con éxito:",
                                data
                            );
                        } else {
                            console.error(
                                "Error al actualizar el cargo salarial:",
                                data.message
                            );
                        }
                    })
                    .catch((error) =>
                        console.error("Error en la solicitud:", error)
                    );
            }

            if (event.target.matches(".antiguedad-input")) {
                const datoACambiar = event.target.value.toUpperCase(); // Captura el nuevo valor y lo convierte a mayúsculas
                event.target.value = datoACambiar; // Actualiza el valor en el select
                const idPofmh = event.target.getAttribute("data-id"); // Captura el ID del registro
                const checkbox = event.target
                    .closest("tr")
                    .querySelector('td:first-child input[type="checkbox"]');
                if (!checkbox.checked) {
                    event.target.setAttribute("disabled", "disabled"); // Deshabilita el input solo si el checkbox no está activo
                }
                // Realiza la solicitud al servidor
                fetch("/actualizarAntiguedad", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken, // Asegúrate de tener el token CSRF
                    },
                    body: JSON.stringify({
                        idPofmh: idPofmh,
                        datoACambiar: datoACambiar,
                    }), // Envía el ID y el nuevo valor
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            console.log(
                                "Cargo salarial actualizado con éxito:",
                                data
                            );
                        } else {
                            console.error(
                                "Error al actualizar el cargo salarial:",
                                data.message
                            );
                        }
                    })
                    .catch((error) =>
                        console.error("Error en la solicitud:", error)
                    );
            }

            if (event.target.matches(".agenter-input")) {
                const datoACambiar = event.target.value.toUpperCase(); // Captura el nuevo valor y lo convierte a mayúsculas
                event.target.value = datoACambiar; // Actualiza el valor en el select
                const idPofmh = event.target.getAttribute("data-id"); // Captura el ID del registro
                const checkbox = event.target
                    .closest("tr")
                    .querySelector('td:first-child input[type="checkbox"]');
                if (!checkbox.checked) {
                    event.target.setAttribute("disabled", "disabled"); // Deshabilita el input solo si el checkbox no está activo
                }
                // Realiza la solicitud al servidor
                fetch("/actualizarAgenteR", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken, // Asegúrate de tener el token CSRF
                    },
                    body: JSON.stringify({
                        idPofmh: idPofmh,
                        datoACambiar: datoACambiar,
                    }), // Envía el ID y el nuevo valor
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            console.log(
                                "Cargo salarial actualizado con éxito:",
                                data
                            );
                        } else {
                            console.error(
                                "Error al actualizar el cargo salarial:",
                                data.message
                            );
                        }
                    })
                    .catch((error) =>
                        console.error("Error en la solicitud:", error)
                    );
            }

            if (event.target.matches(".novedades-input")) {
                const datoACambiar = event.target.value.toUpperCase(); // Captura el nuevo valor y lo convierte a mayúsculas
                event.target.value = datoACambiar; // Actualiza el valor en el select
                const idPofmh = event.target.getAttribute("data-id"); // Captura el ID del registro

                // Realiza la solicitud al servidor
                fetch("/actualizarNovedades", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken, // Asegúrate de tener el token CSRF
                    },
                    body: JSON.stringify({
                        idPofmh: idPofmh,
                        datoACambiar: datoACambiar,
                    }), // Envía el ID y el nuevo valor
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            console.log(
                                "Cargo salarial actualizado con éxito:",
                                data
                            );
                        } else {
                            console.error(
                                "Error al actualizar el cargo salarial:",
                                data.message
                            );
                        }
                    })
                    .catch((error) =>
                        console.error("Error en la solicitud:", error)
                    );
            }

            if (event.target.matches(".asistencia-input")) {
                const datoACambiar = event.target.value.toUpperCase(); // Captura el nuevo valor y lo convierte a mayúsculas
                event.target.value = datoACambiar; // Actualiza el valor en el select
                const idPofmh = event.target.getAttribute("data-id"); // Captura el ID del registro
                const checkbox = event.target
                    .closest("tr")
                    .querySelector('td:first-child input[type="checkbox"]');
                if (!checkbox.checked) {
                    event.target.setAttribute("disabled", "disabled"); // Deshabilita el input solo si el checkbox no está activo
                }
                // Realiza la solicitud al servidor
                fetch("/actualizarAsistencia", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken, // Asegúrate de tener el token CSRF
                    },
                    body: JSON.stringify({
                        idPofmh: idPofmh,
                        datoACambiar: datoACambiar,
                    }), // Envía el ID y el nuevo valor
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            console.log(
                                "Cargo salarial actualizado con éxito:",
                                data
                            );
                        } else {
                            console.error(
                                "Error al actualizar el cargo salarial:",
                                data.message
                            );
                        }
                    })
                    .catch((error) =>
                        console.error("Error en la solicitud:", error)
                    );
            }

            if (event.target.matches(".asistenciajus-input")) {
                const datoACambiar = event.target.value.toUpperCase(); // Captura el nuevo valor y lo convierte a mayúsculas
                event.target.value = datoACambiar; // Actualiza el valor en el select
                const idPofmh = event.target.getAttribute("data-id"); // Captura el ID del registro
                const checkbox = event.target
                    .closest("tr")
                    .querySelector('td:first-child input[type="checkbox"]');
                if (!checkbox.checked) {
                    event.target.setAttribute("disabled", "disabled"); // Deshabilita el input solo si el checkbox no está activo
                }
                // Realiza la solicitud al servidor
                fetch("/actualizarAsistenciaJustificada", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken, // Asegúrate de tener el token CSRF
                    },
                    body: JSON.stringify({
                        idPofmh: idPofmh,
                        datoACambiar: datoACambiar,
                    }), // Envía el ID y el nuevo valor
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            console.log(
                                "Cargo salarial actualizado con éxito:",
                                data
                            );
                        } else {
                            console.error(
                                "Error al actualizar el cargo salarial:",
                                data.message
                            );
                        }
                    })
                    .catch((error) =>
                        console.error("Error en la solicitud:", error)
                    );
            }

            if (event.target.matches(".asistenciain-input")) {
                const datoACambiar = event.target.value.toUpperCase(); // Captura el nuevo valor y lo convierte a mayúsculas
                event.target.value = datoACambiar; // Actualiza el valor en el select
                const idPofmh = event.target.getAttribute("data-id"); // Captura el ID del registro
                const checkbox = event.target
                    .closest("tr")
                    .querySelector('td:first-child input[type="checkbox"]');
                if (!checkbox.checked) {
                    event.target.setAttribute("disabled", "disabled"); // Deshabilita el input solo si el checkbox no está activo
                }
                // Realiza la solicitud al servidor
                fetch("/actualizarAsistenciaInjustificada", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken, // Asegúrate de tener el token CSRF
                    },
                    body: JSON.stringify({
                        idPofmh: idPofmh,
                        datoACambiar: datoACambiar,
                    }), // Envía el ID y el nuevo valor
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            console.log(
                                "Cargo salarial actualizado con éxito:",
                                data
                            );
                        } else {
                            console.error(
                                "Error al actualizar el cargo salarial:",
                                data.message
                            );
                        }
                    })
                    .catch((error) =>
                        console.error("Error en la solicitud:", error)
                    );
            }

            if (event.target.matches(".observaciones-input")) {
                const datoACambiar = event.target.value.toUpperCase(); // Captura el nuevo valor y lo convierte a mayúsculas
                event.target.value = datoACambiar; // Actualiza el valor en el select
                const idPofmh = event.target.getAttribute("data-id"); // Captura el ID del registro
                // Realiza la solicitud al servidor
                fetch("/actualizarObservaciones", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken, // Asegúrate de tener el token CSRF
                    },
                    body: JSON.stringify({
                        idPofmh: idPofmh,
                        datoACambiar: datoACambiar,
                    }), // Envía el ID y el nuevo valor
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            console.log(
                                "Cargo salarial actualizado con éxito:",
                                data
                            );
                        } else {
                            console.error(
                                "Error al actualizar el cargo salarial:",
                                data.message
                            );
                        }
                    })
                    .catch((error) =>
                        console.error("Error en la solicitud:", error)
                    );
            }

            if (event.target.matches(".carrera-input")) {
                const datoACambiar = event.target.value.toUpperCase(); // Captura el nuevo valor y lo convierte a mayúsculas
                event.target.value = datoACambiar; // Actualiza el valor en el select
                const idPofmh = event.target.getAttribute("data-id"); // Captura el ID del registro
                const checkbox = event.target
                    .closest("tr")
                    .querySelector('td:first-child input[type="checkbox"]');
                if (!checkbox.checked) {
                    event.target.setAttribute("disabled", "disabled"); // Deshabilita el input solo si el checkbox no está activo
                }
                // Realiza la solicitud al servidor
                fetch("/actualizarCarrera", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken, // Asegúrate de tener el token CSRF
                    },
                    body: JSON.stringify({
                        idPofmh: idPofmh,
                        datoACambiar: datoACambiar,
                    }), // Envía el ID y el nuevo valor
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            console.log(
                                "Cargo salarial actualizado con éxito:",
                                data
                            );
                        } else {
                            console.error(
                                "Error al actualizar el cargo salarial:",
                                data.message
                            );
                        }
                    })
                    .catch((error) =>
                        console.error("Error en la solicitud:", error)
                    );
            }

            if (event.target.matches(".orientacion-input")) {
                const datoACambiar = event.target.value.toUpperCase(); // Captura el nuevo valor y lo convierte a mayúsculas
                event.target.value = datoACambiar; // Actualiza el valor en el select
                const idPofmh = event.target.getAttribute("data-id"); // Captura el ID del registro
                const checkbox = event.target
                    .closest("tr")
                    .querySelector('td:first-child input[type="checkbox"]');
                if (!checkbox.checked) {
                    event.target.setAttribute("disabled", "disabled"); // Deshabilita el input solo si el checkbox no está activo
                }
                // Realiza la solicitud al servidor
                fetch("/actualizarOrientacion", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken, // Asegúrate de tener el token CSRF
                    },
                    body: JSON.stringify({
                        idPofmh: idPofmh,
                        datoACambiar: datoACambiar,
                    }), // Envía el ID y el nuevo valor
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            console.log(
                                "Cargo salarial actualizado con éxito:",
                                data
                            );
                        } else {
                            console.error(
                                "Error al actualizar el cargo salarial:",
                                data.message
                            );
                        }
                    })
                    .catch((error) =>
                        console.error("Error en la solicitud:", error)
                    );
            }

            if (event.target.matches(".titulo-input")) {
                const datoACambiar = event.target.value.toUpperCase(); // Captura el nuevo valor y lo convierte a mayúsculas
                event.target.value = datoACambiar; // Actualiza el valor en el select
                const idPofmh = event.target.getAttribute("data-id"); // Captura el ID del registro
                const checkbox = event.target
                    .closest("tr")
                    .querySelector('td:first-child input[type="checkbox"]');
                if (!checkbox.checked) {
                    event.target.setAttribute("disabled", "disabled"); // Deshabilita el input solo si el checkbox no está activo
                }
                // Realiza la solicitud al servidor
                fetch("/actualizarTitulo", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken, // Asegúrate de tener el token CSRF
                    },
                    body: JSON.stringify({
                        idPofmh: idPofmh,
                        datoACambiar: datoACambiar,
                    }), // Envía el ID y el nuevo valor
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            console.log(
                                "Cargo salarial actualizado con éxito:",
                                data
                            );
                        } else {
                            console.error(
                                "Error al actualizar el cargo salarial:",
                                data.message
                            );
                        }
                    })
                    .catch((error) =>
                        console.error("Error en la solicitud:", error)
                    );
            }

            if (event.target.matches(".zonasupervision-input")) {
                const datoACambiar = event.target.value.toUpperCase(); // Captura el nuevo valor y lo convierte a mayúsculas
                event.target.value = datoACambiar; // Actualiza el valor en el select
                const idPofmh = event.target.getAttribute("data-id"); // Captura el ID del registro
                //event.target.setAttribute('disabled', 'disabled');
                // Realiza la solicitud al servidor
                fetch("/actualizarZonaSupervision", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken, // Asegúrate de tener el token CSRF
                    },
                    body: JSON.stringify({
                        idPofmh: idPofmh,
                        datoACambiar: datoACambiar,
                    }), // Envía el ID y el nuevo valor
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            console.log(
                                "Cargo salarial actualizado con éxito:",
                                data
                            );
                        } else {
                            console.error(
                                "Error al actualizar el cargo salarial:",
                                data.message
                            );
                        }
                    })
                    .catch((error) =>
                        console.error("Error en la solicitud:", error)
                    );
            }
        },
        true
    );

    // Función para obtener los cargos salariales desde el servidor
    async function obtenerCargosSalariales() {
        try {
            const response = await fetch("/obtener_cargosSalariales");
            if (!response.ok) {
                throw new Error("Error al obtener los cargos salariales");
            }
            const data = await response.json();
            return data.success ? data.data : []; // Devuelve solo los datos si la respuesta es exitosa
        } catch (error) {
            console.error(error);
            return []; // Devuelve un array vacío en caso de error
        }
    }

    // Función para obtener las Aulas desde el servidor

    async function obtenerAulas() {
        try {
            const response = await fetch("/obtener_aulas");
            if (!response.ok) {
                throw new Error("Error al obtener las aulas");
            }
            return await response.json();
        } catch (error) {
            console.error(error);
        }
    }
    // Función para obtener las divisiones desde el servidor
    async function obtenerDivisiones() {
        try {
            const response = await fetch("/obtener_division");
            if (!response.ok) {
                throw new Error("Error al obtener las divisiones");
            }
            return await response.json();
        } catch (error) {
            console.error(error);
        }
    }
    // Capturar el cambio en el select "Origen"
    // Selecciona todos los elementos con la clase 'origen-input'
    // Agrega un listener al contenedor de la tabla para delegación de eventos
    document
        .getElementById("POFMH")
        .addEventListener("change", function (event) {
            // Verificar si el evento proviene de un elemento con la clase `origen-input`
            if (event.target.classList.contains("origen-input")) {
                const idOrigenCargo = event.target.value;
                console.log("Origen seleccionado:", idOrigenCargo);

                // Encuentra la fila actual usando el selector `tr` más cercano
                const filaActual = event.target.closest("tr");

                // Encuentra los selectores de Aula, Division y Turno en la misma fila
                const aulaSelect = filaActual.querySelector(".aula-input");
                const divisionSelect =
                    filaActual.querySelector(".division-input");
                const turnoSelect = filaActual.querySelector(".turno-input");

                // Obtener el valor seleccionado del turno
                const turnoSeleccionado = turnoSelect
                    ? turnoSelect.value
                    : null;
                console.log("Turno seleccionado:", turnoSeleccionado);

                // Vaciar los selectores de Aula y Division en la fila actual
                aulaSelect.innerHTML =
                    '<option value="-1" disabled selected>SELECCIONE UNA OPCIÓN</option>';
                divisionSelect.innerHTML =
                    '<option value="-1" disabled selected>SELECCIONE UNA OPCIÓN</option>';

                // Realizar la solicitud para obtener las Aulas y Divisiones relacionadas
                setTimeout(async () => {
                    try {
                        // Incluir el Turno en la solicitud si es necesario
                        const response = await fetch(
                            `/obtener_aula_y_division_por_origen?idOrigenCargo=${idOrigenCargo}&turno=${turnoSeleccionado}`
                        );
                        const data = await response.json();
                        console.log(data);

                        if (data.success) {
                            // Llenar el select de Aula con los datos recibidos
                            data.aulas.forEach((aula) => {
                                const option = document.createElement("option");
                                option.value = aula.idAula;
                                option.textContent = aula.nombre_aula;
                                aulaSelect.appendChild(option);
                            });

                            // Llenar el select de Division con los datos recibidos
                            data.divisiones.forEach((division) => {
                                const option = document.createElement("option");
                                option.value = division.idDivision;
                                option.textContent = division.nombre_division;
                                divisionSelect.appendChild(option);
                            });
                        } else {
                            console.error(data.message);
                        }
                    } catch (error) {
                        console.error(
                            "Error al obtener las aulas y divisiones:",
                            error
                        );
                    }
                }, 100); // Retraso de 100 ms
            }
        });

    /*
document.querySelectorAll('.aula-input').forEach(aulaSelect => {
    aulaSelect.addEventListener('focus', function() {
        // Encuentra la fila actual
        const filaActual = this.closest('tr');

        // Obtén el idOrigenCargo de la selección actual en "Origen"
        const origenSelect = filaActual.querySelector('.origen-input');
        const idOrigenCargo = origenSelect.value;
        
        if (!idOrigenCargo || idOrigenCargo === "-1") {
            console.error("Seleccione primero una opción en el campo Origen");
            return;
        }

        console.log("Origen seleccionado:", idOrigenCargo);

        // Vaciar el select de Aula solo si no tiene opciones cargadas
        if (aulaSelect.options.length <= 1) {  // Evita recargar si ya tiene opciones
            aulaSelect.innerHTML = '<option value="-1" disabled selected>SELECCIONE UNA OPCIÓN</option>';

            // Realizar la solicitud para obtener las Aulas relacionadas
            setTimeout(async () => {
                try {
                    const response = await fetch(`/obtener_aulas_por_origen?idOrigenCargo=${idOrigenCargo}`);
                    const data = await response.json();
                    console.log(data);

                    if (data.success) {
                        // Llenar el select de Aula con los datos recibidos
                        data.aulas.forEach(aula => {
                            const option = document.createElement('option');
                            option.value = aula.idAula;
                            option.textContent = aula.nombre_aula;
                            aulaSelect.appendChild(option);
                        });
                    } else {
                        console.error(data.message);
                    }
                } catch (error) {
                    console.error('Error al obtener las aulas:', error);
                }
            }, 100); // Retraso de 100 ms
        }
    });
});
*/

    // Función para obtener los turnos desde el servidor
    /*
async function obtenerTurnos() {
    try {
        const response = await fetch('/obtener_turnos');
        if (!response.ok) {
            throw new Error('Error al obtener los turnos');
        }
        return await response.json();
    } catch (error) {
        console.error(error);
    }
}
*/
    async function obtenerTurnos() {
        try {
            // Obtener el valor del campo oculto
            const valCUE = document.getElementById("valCUE").value;

            // Hacer la solicitud POST enviando el valor de valCUEInd
            const response = await fetch("/obtener_turnos", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"), // Incluir el token CSRF si usas Laravel
                },
                body: JSON.stringify({
                    valCUE: valCUE, // Enviar el valor del campo como parte de los datos
                }),
            });

            if (!response.ok) {
                throw new Error("Error al obtener los turnos");
            }

            const data = await response.json();
            return data; // Procesar la respuesta
        } catch (error) {
            console.error(error);
        }
    }
    // Función para obtener situaciones de revista (SitRev) desde el servidor
    async function obtenerSitRev() {
        try {
            const response = await fetch("/obtener_sitrev");
            if (!response.ok) {
                throw new Error("Error al obtener situaciones de revista");
            }
            return await response.json();
        } catch (error) {
            console.error(error);
        }
    }

    // Función para obtener motivos desde el servidor
    async function obtenerMotivos() {
        try {
            const response = await fetch("/obtener_motivos");
            if (!response.ok) {
                throw new Error("Error al obtener motivos");
            }
            return await response.json();
        } catch (error) {
            console.error(error);
        }
    }

    // Función para obtener motivos desde el servidor
    async function obtenerCondicion() {
        try {
            const response = await fetch("/obtener_condiciones");
            if (!response.ok) {
                throw new Error("Error al obtener condiciones");
            }
            return await response.json();
        } catch (error) {
            console.error(error);
        }
    }

    // Función para obtener activos opciones desde el servidor
    async function obtenerActivos() {
        try {
            const response = await fetch("/obtener_activos");
            if (!response.ok) {
                throw new Error("Error al obtener activos");
            }
            return await response.json();
        } catch (error) {
            console.error(error);
        }
    }

    // Función para obtener activos opciones desde el servidor
    async function obtenerOrigenes() {
        valIdExt = $("#valIdExt").val();
        console.log(valIdExt);
        try {
            const response = await fetch(`/obtener_origenes?idExt=${valIdExt}`);
            if (!response.ok) {
                throw new Error("Error al obtener activos");
            }
            return await response.json();
        } catch (error) {
            console.error(error);
        }
    }
    async function llenarCombosFila(row) {
        const cargos = await obtenerCargosSalariales();
        const aulasResponse = await obtenerAulas();
        const aulas = aulasResponse.data;
        const divisionesResponse = await obtenerDivisiones();
        const divisiones = divisionesResponse.data;
        const turnosResponse = await obtenerTurnos();
        const turnos = turnosResponse.data;
        const sitrevResponse = await obtenerSitRev();
        const sitrev = sitrevResponse.data;
        const motivosResponse = await obtenerMotivos();
        const motivos = motivosResponse.data;
        const condicionResponse = await obtenerCondicion();
        const condicion = condicionResponse.data;
        const activoResponse = await obtenerActivos();
        const activo = activoResponse.data;
        const origenesResponse = await obtenerOrigenes();
        const origenes = origenesResponse.data;

        // Obtener selectores para la nueva fila
        const selectAulas = row.find(".aula-input");
        const selectDivisiones = row.find(".division-input");
        const selectCargos = row.find(".cargo-input");
        const selectTurnos = row.find(".turno-input");
        const selectSitRev = row.find(".sitrev-input");
        const selectMotivos = row.find(".motivos-input");
        const selectCondicion = row.find(".condicion-input");
        const selectActivo = row.find(".activo-input");
        const selectOrigen = row.find(".origen-input");

        // Llenar solo los select de la fila nueva
        //selectAulas.html(generarOpcionesAulas(aulas));
        //selectDivisiones.html(generarOpcionesDivisiones(divisiones));
        selectCargos.html(generarOpcionesCargos(cargos));
        selectTurnos.html(generarOpcionesTurnos(turnos));
        selectSitRev.html(generarOpcionesSitRev(sitrev));
        selectMotivos.html(generarOpcionesMotivos(motivos));
        selectCondicion.html(generarOpcionesCondicion(condicion));
        selectActivo.html(generarOpcionesActivo(activo));
        selectOrigen.html(generarOpcionesOrigenCargo(origenes));
    }

    // Función para llenar los combos con los datos obtenidos
    async function llenarCombos() {
        // Obtener datos de cada categoría
        const cargos = await obtenerCargosSalariales();
        const aulasResponse = await obtenerAulas();
        const aulas = aulasResponse.data;
        const divisionesResponse = await obtenerDivisiones();
        const divisiones = divisionesResponse.data; // Acceder a los datos
        const turnosResponse = await obtenerTurnos();
        const turnos = turnosResponse.data;
        const sitrevResponse = await obtenerSitRev();
        const sitrev = sitrevResponse.data;
        const motivosResponse = await obtenerMotivos();
        const motivos = motivosResponse.data;
        const condicionResponse = await obtenerCondicion();
        const condicion = condicionResponse.data;
        const activoResponse = await obtenerActivos();
        const activo = activoResponse.data;
        const origenesResponse = await obtenerOrigenes();
        const origenes = origenesResponse.data;

        // Llenar cada combo con los datos
        const selectAulas = document.querySelectorAll(".aula-input");
        const selectCargos = document.querySelectorAll(".cargo-input");
        const selectTurnos = document.querySelectorAll(".turno-input");
        const selectSitRev = document.querySelectorAll(".sitrev-input");
        const selectMotivos = document.querySelectorAll(".motivos-input");
        const selectDivisiones = document.querySelectorAll(".division-input");
        const selectCondicion = row.find(".condicion-input");
        const selectActivo = row.find(".activo-input");
        const selectOrigen = row.find(".origen-input");

        selectAulas.forEach((select) => {
            const valorSeleccionado = select.getAttribute("data-id");
            select.innerHTML = generarOpcionesAulas(aulas, valorSeleccionado);
        });

        selectDivisiones.forEach((select) => {
            const valorSeleccionado = select.getAttribute("data-id");
            select.innerHTML = generarOpcionesDivisiones(
                divisiones,
                valorSeleccionado
            );
        });

        selectCargos.forEach((select) => {
            const valorSeleccionado = select.getAttribute("data-id");
            // Llama a generarOpcionesCargos y asegúrate de que recibe la estructura correcta
            select.innerHTML = generarOpcionesCargos(cargos, valorSeleccionado);
        });

        selectTurnos.forEach((select) => {
            const valorSeleccionado = select.getAttribute("data-id");
            select.innerHTML = generarOpcionesTurnos(turnos, valorSeleccionado);
        });

        selectSitRev.forEach((select) => {
            const valorSeleccionado = select.getAttribute("data-id");
            select.innerHTML = generarOpcionesSitRev(sitrev, valorSeleccionado);
        });

        selectMotivos.forEach((select) => {
            const valorSeleccionado = select.getAttribute("data-id");
            select.innerHTML = generarOpcionesMotivos(
                motivos,
                valorSeleccionado
            );
        });

        selectCondicion.forEach((select) => {
            const valorSeleccionado = select.getAttribute("data-id");
            select.innerHTML = generarOpcionesCondicion(
                condicion,
                valorSeleccionado
            );
        });

        selectActivo.forEach((select) => {
            const valorSeleccionado = select.getAttribute("data-id");
            select.innerHTML = generarOpcionesActivo(activo, valorSeleccionado);
        });

        selectOrigen.forEach((select) => {
            const valorSeleccionado = select.getAttribute("data-id");
            select.innerHTML = generarOpcionesOrigenCargo(
                origenes,
                valorSeleccionado
            );
        });
    }

    // Llamar a la función para llenar los combos cuando el DOM esté completamente cargado
    document.addEventListener("DOMContentLoaded", () => {
        console.log("llamando a combo cargar");
    });

    // Funciones para generar las opciones de cada combo
    function generarOpcionesCargos(cargos, valorSeleccionado) {
        // Agregamos la opción por defecto
        let opciones = `<option value="-1" disabled selected>SELECCIONE UNA OPCIÓN</option>`;

        // Mapear las opciones de Cargos
        opciones += cargos
            .map((cargo) => {
                // Verifica si el id del cargo actual es el valor seleccionado
                const selected =
                    cargo.idCargo == valorSeleccionado ? "selected" : "";
                // Devuelve una cadena HTML para cada opción
                return `<option value="${cargo.idCargo}" ${selected}>${cargo.Cargo} ${cargo.Codigo}</option>`;
            })
            .join("");

        return opciones; // Devuelve todas las opciones generadas
    }

    function generarOpcionesAulas(aulas, valorSeleccionado) {
        // Verificamos que 'aulas' sea un array
        if (!Array.isArray(aulas)) {
            console.error("Aulas no es un array:", aulas);
            return "";
        }

        // Agregamos la opción por defecto
        let opciones = `<option value="-1" disabled selected>SELECCIONE UNA OPCIÓN</option>`;

        // Mapear las opciones de aulas
        opciones += aulas
            .map((aula) => {
                const selected =
                    aula.idAula == valorSeleccionado ? "selected" : "";
                return `<option value="${aula.idAula}" ${selected}>${aula.nombre_aula}</option>`;
            })
            .join("");

        return opciones;
    }
    function generarOpcionesDivisiones(divisiones, valorSeleccionado) {
        // Verificamos que 'divisiones' sea un array
        if (!Array.isArray(divisiones)) {
            console.error("Divisiones no es un array:", divisiones);
            return "";
        }

        return divisiones
            .map((division) => {
                const selected =
                    division.idDivision == valorSeleccionado ? "selected" : "";
                return `<option value="${division.idDivision}" ${selected}>${division.nombre_division}</option>`;
            })
            .join("");
    }

    function generarOpcionesTurnos(turnos, valorSeleccionado) {
        // Agregamos la opción por defecto

        // Mapear las opciones de turnos
        return turnos
            .map((turno) => {
                const selected =
                    turno.idTurno == valorSeleccionado ? "selected" : "";
                return `<option value="${turno.idTurno}" ${selected}>${turno.nombre_turno}</option>`;
            })
            .join("");
    }

    function generarOpcionesSitRev(sitrev, valorSeleccionado) {
        // Agregamos la opción por defecto para que el usuario seleccione
        let opciones = `<option value="-1" disabled selected>SELECCIONE UNA OPCIÓN</option>`;

        // Mapear las opciones de Situación de Revista
        opciones += sitrev
            .map((sit) => {
                // Verifica si el id de la situación actual es el valor seleccionado
                const selected =
                    sit.idSituacionRevista == valorSeleccionado
                        ? "selected"
                        : "";
                // Devuelve una cadena HTML para cada opción
                return `<option value="${sit.idSituacionRevista}" ${selected}>${sit.Descripcion}</option>`;
            })
            .join("");

        return opciones; // Devuelve todas las opciones generadas
    }

    function generarOpcionesMotivos(motivos, valorSeleccionado) {
        let opciones = `<option value="-1" disabled selected>SELECCIONE UNA OPCIÓN</option>`;
        opciones += motivos
            .map((motivo) => {
                const selected =
                    motivo.idMotivo == valorSeleccionado ? "selected" : "";
                return `<option value="${motivo.idMotivo}" ${selected}>${motivo.Codigo}-${motivo.Nombre_Licencia}</option>`;
            })
            .join("");
        return opciones;
    }

    function generarOpcionesCondicion(condiciones, valorSeleccionado) {
        // Agregamos la opción por defecto
        let opciones = `<option value="-1" disabled selected>SELECCIONE UNA OPCIÓN</option>`;
        opciones += condiciones
            .map((condicion) => {
                const selected =
                    condicion.idCondicion == valorSeleccionado
                        ? "selected"
                        : "";
                return `<option value="${condicion.idCondicion}" ${selected}>${condicion.Descripcion}</option>`;
            })
            .join("");
        return opciones;
    }

    function generarOpcionesActivo(Activos, valorSeleccionado) {
        // Agregamos la opción por defecto
        let opciones = `<option value="-1" disabled selected>SELECCIONE UNA OPCIÓN</option>`;

        opciones += Activos.map((activo) => {
            const selected =
                activo.idActivo == valorSeleccionado ? "selected" : "";
            return `<option value="${activo.idActivo}" ${selected}>${activo.nombre_activo}</option>`;
        }).join("");

        return opciones;
    }

    function generarOpcionesOrigenCargo(Origenes, valorSeleccionado) {
        // Agregamos la opción por defecto
        let opciones = `<option value="-1" disabled selected>SELECCIONE UNA OPCIÓN</option>`;

        // Mapear las opciones de Orígenes de Cargo
        opciones += Origenes.map((origen) => {
            // Verifica si el id del origen actual es el valor seleccionado
            const selected =
                origen.idOrigenCargo == valorSeleccionado ? "selected" : "";
            // Devuelve una cadena HTML para cada opción
            return `<option value="${origen.idOrigenCargo}" ${selected}>${origen.nombre_origen}</option>`;
        }).join("");

        return opciones; // Devuelve todas las opciones generadas
    }
});
//manejo de teclado
/*
$(document).ready(function() {
    let currentRow = 0;
    let currentCol = 0;

    // Resalta la primera celda y enfoca el primer campo al cargar la página
    highlightCell(currentRow, currentCol);

    // Manejar el evento de teclado
    $(document).keydown(function(e) {
        const inputs = $('#POFMH tbody tr').eq(currentRow).find('input, select, textarea');

        switch (e.key) {
            case "ArrowDown":
                e.preventDefault();
                if (currentRow < $('#POFMH tbody tr').length - 1) {
                    currentRow++;
                }
                currentCol = Math.min(currentCol, $('#POFMH tbody tr').eq(currentRow).find('input, select, textarea').length - 1);
                highlightCell(currentRow, currentCol);
                break;

            case "ArrowUp":
                e.preventDefault();
                if (currentRow > 0) {
                    currentRow--;
                }
                currentCol = Math.min(currentCol, $('#POFMH tbody tr').eq(currentRow).find('input, select, textarea').length - 1);
                highlightCell(currentRow, currentCol);
                break;

            case "ArrowRight":
                e.preventDefault();
                const currentInputs = $('#POFMH tbody tr').eq(currentRow).find('input, select, textarea');
                if (currentCol < currentInputs.length - 1) {
                    currentCol++;
                } else {
                    currentCol = 0; // Resetea al primer campo de la próxima fila
                    if (currentRow < $('#POFMH tbody tr').length - 1) {
                        currentRow++;
                    }
                }
                highlightCell(currentRow, currentCol);
                break;

            case "ArrowLeft":
                e.preventDefault();
                const currentInputsLeft = $('#POFMH tbody tr').eq(currentRow).find('input, select, textarea');
                if (currentCol > 0) {
                    currentCol--;
                } else {
                    if (currentRow > 0) {
                        currentRow--;
                        currentCol = $('#POFMH tbody tr').eq(currentRow).find('input, select, textarea').length - 1; // Ve al último campo de la fila anterior
                    }
                }
                highlightCell(currentRow, currentCol);
                break;
        }
    });

    function highlightCell(rowIndex, colIndex) {
        // Eliminar resalte de todas las filas
        $('#POFMH tbody tr').removeClass('active');
        
        // Resaltar la fila seleccionada
        $('#POFMH tbody tr').eq(rowIndex).addClass('active');
        
        // Enfocar el campo actual
        const inputs = $('#POFMH tbody tr').eq(rowIndex).find('input, select, textarea');
        if (inputs.length > 0) {
            inputs.eq(colIndex).focus();
        }
    }
});*/
$(document).ready(function () {
    let currentRow = 0;
    let currentCol = 0;

    // Resalta la primera celda y enfoca el primer campo al cargar la página
    highlightCell(currentRow, currentCol);

    // Manejar el evento de teclado
    $(document).keydown(function (e) {
        const totalRows = $("#POFMH tbody tr").length;
        const currentInputs = $("#POFMH tbody tr")
            .eq(currentRow)
            .find("input, select, textarea");
        const totalInputs = currentInputs.length;

        switch (e.key) {
            case "ArrowDown":
                e.preventDefault();
                if (currentRow < totalRows - 1) {
                    currentRow++;
                }
                currentCol = Math.min(
                    currentCol,
                    $("#POFMH tbody tr")
                        .eq(currentRow)
                        .find("input, select, textarea").length - 1
                );
                highlightCell(currentRow, currentCol);
                break;

            case "ArrowUp":
                e.preventDefault();
                if (currentRow > 0) {
                    currentRow--;
                }
                currentCol = Math.min(
                    currentCol,
                    $("#POFMH tbody tr")
                        .eq(currentRow)
                        .find("input, select, textarea").length - 1
                );
                highlightCell(currentRow, currentCol);
                break;

            case "ArrowRight":
                e.preventDefault();
                if (currentCol < totalInputs - 1) {
                    currentCol++;
                } else {
                    currentCol = 0; // Resetea al primer campo de la próxima fila
                    if (currentRow < totalRows - 1) {
                        currentRow++;
                    }
                }
                highlightCell(currentRow, currentCol);
                break;

            case "ArrowLeft":
                e.preventDefault();
                if (currentCol > 0) {
                    currentCol--;
                } else {
                    if (currentRow > 0) {
                        currentRow--;
                        currentCol =
                            $("#POFMH tbody tr")
                                .eq(currentRow)
                                .find("input, select, textarea").length - 1; // Ve al último campo de la fila anterior
                    }
                }
                highlightCell(currentRow, currentCol);
                break;
        }
    });

    // Escuchar eventos de clic en cada celda de entrada
    /*
    $('#POFMH tbody').on('click', 'input, select, textarea', function(e) {
        e.preventDefault();
        const cell = $(this).closest('td'); // Encuentra la celda
        currentRow = cell.parent().index(); // Obtiene el índice de la fila
        currentCol = cell.index(); // Obtiene el índice de la columna
        highlightCell(currentRow, currentCol); // Resaltar y enfocar la celda seleccionada
    });*/

    function highlightCell(rowIndex, colIndex) {
        // Eliminar resalte de todas las filas
        $("#POFMH tbody tr").removeClass("active");

        // Resaltar la fila seleccionada
        $("#POFMH tbody tr").eq(rowIndex).addClass("active");

        // Enfocar el campo actual
        const inputs = $("#POFMH tbody tr")
            .eq(rowIndex)
            .find("input, select, textarea");
        if (inputs.length > 0) {
            inputs.eq(colIndex).focus().select(); // Enfocar y seleccionar el contenido
        }
    }
});

// Función para manejar el evento de clic en el botón .view-novedades en filas dinámicas
// Función para manejar el evento de clic en el botón .view-novedades en filas dinámicas
$("#POFMH").on("click", ".view-novedades", async function (event) {
    event.preventDefault(); // Evita comportamientos automáticos

    const currentRow = $(this).closest("tr"); // Encontrar la fila que contiene el botón
    const dataId = currentRow.data("id"); // Obtener el data-id de la fila

    if (!dataId) {
        console.error("No se encontró el ID de la fila.");
        return;
    }

    // Obtener valores específicos de la fila usando el data-id
    const dni = $("#dni-input-" + dataId).val(); // Obtener el valor del DNI
    const apenom = $("#apenom-input-" + dataId).val(); // Obtener el valor de apenom
    const valCue = $("#valCUE").val(); // Asumimos que valCue es único
    const valTurno = $("#Turno").val(); // Asumimos que valTurno es único

    console.log("ID de la fila seleccionada:", dataId); // Verificar que se obtiene el ID correctamente
    console.log("DNI:", dni);
    console.log("ApeNom:", apenom);

    // Asignar valores al modal solo después de confirmar
    $("#DNI").val(dni);
    $("#novedad_dni").val(dni);
    $("#ApeNom").val(apenom);
    $("#novedad_apenom").val(apenom);
    $("#novedad_cue").val(valCue);
    $("#novedad_turno").val(valTurno);

    traerArchivos2(); // Llama a la función para traer archivos asociados
    $("#modal-novedades").modal("show"); // Muestra el modal solo después de la confirmación
});

/*
$('.view-novedades').on('click', function() {
    $("#pofmhformularioNovedadParticular")[0].reset();
    // Obtener el data-id de la fila clickeada
    var filaId = $(this).data('id');

    // Usar el data-id para obtener los valores de DNI y ApeNom de la fila correspondiente
    var dni = $('#dni-input-' + filaId).val();
    var apenom = $('#apenom-input-' + filaId).val();
    var valCue = $('#valCUE').val();
    var valTurno = $('#Turno').val();

    // Asignar los valores de DNI y ApeNom a los campos correspondientes en el modal
    $('#DNI').val(dni);
    $('#novedad_dni').val(dni);
    $('#ApeNom').val(apenom);
    $('#novedad_apenom').val(apenom);
    $('#novedad_cue').val(valCue);
    $('#novedad_turno').val(valTurno);
    // Si es n$('#ApeNom').val(apenom);ecesario, puedes ajustar cualquier otro campo en el modal aquí
    traerArchivos2();
    // Mostrar el modal (opcional si no está siendo gestionado por data-toggle)
    $('#modal-novedades').modal('show');
});
*/
$(document).ready(function () {
    // Manejar el clic en el botón de eliminar
    $(".btn-delete-documento").on("click", function () {
        var documentoId = $(this).data("id");
        var row = $("#documento-" + documentoId); // Para eliminar la fila después de la eliminación

        // Confirmación antes de eliminar
        if (confirm("¿Estás seguro de que deseas eliminar este documento?")) {
            $.ajax({
                url: "/borrarDocumentoAgentePof", // La ruta a tu método de eliminación
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}", // Token CSRF para seguridad
                    doc: documentoId,
                },
                success: function (response) {
                    if (response.status === "success") {
                        // Eliminar la fila de la tabla si la eliminación fue exitosa
                        row.remove();

                        // Mostrar un mensaje de éxito
                        alert(response.message);
                    } else {
                        // Mostrar un mensaje de error
                        alert("Error: " + response.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                    alert(
                        "Error al procesar la solicitud. Inténtalo nuevamente."
                    );
                },
            });
        }
    });
});

//funciones de menu contextual
/*
document.addEventListener('DOMContentLoaded', () => {
    const menu = document.createElement('div');
    menu.id = 'menuContextual';
    menu.innerHTML = `
        <ul>
            <li id="ver">Ver</li>
        </ul>
    `;
    document.body.appendChild(menu);

    let selectedInput = null; // Almacena el input seleccionado

    document.querySelectorAll('#POFMH td').forEach(td => {
        td.addEventListener('contextmenu', (e) => {
            e.preventDefault();
            
            const input = td.querySelector('input');
            if (input) {
                selectedInput = input;
                
                const { pageX: x, pageY: y } = e;
                console.log("Click derecho detectado. Posición: ", x, y);
                console.log("Input seleccionado: ", selectedInput);

                menu.style.left = `${x}px`;
                menu.style.top = `${y}px`;
                menu.style.display = 'block';
            }
        });
    });

    // Evento para ocultar el menú contextual
    document.addEventListener('click', () => {
        menu.style.display = 'none';
    });

    // Evento para habilitar el input cuando se selecciona "Ver"
    document.getElementById('ver').addEventListener('click', () => {
        if (selectedInput) {
            console.log("Habilitando input: ", selectedInput);
            selectedInput.removeAttribute('disabled');
        }
    });
});
*/
//primer proceso para cargar el menu con boton derecho
/*
document.addEventListener('DOMContentLoaded', () => {
    const menu = document.createElement('div');
    menu.id = 'menuContextual';
    menu.innerHTML = `
        <ul>
            <li id="ver">Ver</li>
            <li id="desactivar">Desactivar</li> <!-- Nueva opción para desactivar -->
        </ul>
    `;
    document.body.appendChild(menu);

    let selectedInput = null; // Almacena el input seleccionado

    document.querySelectorAll('#POFMH td').forEach(td => {
        td.addEventListener('contextmenu', (e) => {
            e.preventDefault();
            
            const input = td.querySelector('input');
            if (input) {
                selectedInput = input;
                
                const { pageX: x, pageY: y } = e;
                console.log("Click derecho detectado. Posición: ", x, y);
                console.log("Input seleccionado: ", selectedInput);

                menu.style.left = `${x}px`;
                menu.style.top = `${y}px`;
                menu.style.display = 'block';
            }
        });
    });

    // Evento para ocultar el menú contextual
    document.addEventListener('click', () => {
        menu.style.display = 'none';
    });

    // Evento para habilitar el input cuando se selecciona "Ver"
    document.getElementById('ver').addEventListener('click', () => {
        if (selectedInput) {
            console.log("Habilitando input: ", selectedInput);
            selectedInput.removeAttribute('disabled');
        }
    });

    // Evento para desactivar el input cuando se selecciona "Desactivar"
    document.getElementById('desactivar').addEventListener('click', () => {
        if (selectedInput) {
            console.log("Desactivando input: ", selectedInput);
            const idPofmh = selectedInput.getAttribute('data-id'); // Captura el ID del registro
            const datoACambiar = ''; // Valor a enviar al servidor (vacío)
    
            let endpoint; // Determinamos el endpoint en función de la clase del input
    
            if (selectedInput.matches('.fechaaltacargo-input')) {
                endpoint = '/actualizarFechaAltaCargo';
            } else if (selectedInput.matches('.fechadesignado-input')) {
                endpoint = '/actualizarFechaDesignado';
            } else if (selectedInput.matches('.fechadesde-input')) {
                endpoint = '/actualizarFechaDesde';
            } else if (selectedInput.matches('.fechahasta-input')) {
                endpoint = '/actualizarFechaHasta';
            }
    
            // Realiza la solicitud al servidor
            fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken // Asegúrate de tener el token CSRF
                },
                body: JSON.stringify({ idPofmh: idPofmh, datoACambiar: datoACambiar }) // Envía el ID y el nuevo valor (vacío)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Fecha desactivada con éxito:', data);
                    selectedInput.value = ''; // Establece el valor a null (vacío)
                } else {
                    console.error('Error al desactivar la fecha:', data.message);
                }
            })
            .catch(error => console.error('Error en la solicitud:', error));
        }
    });
    
});*/
//la usamos para colocar el menu dinamico
//probando proceso mejorado
/*
document.addEventListener('DOMContentLoaded', () => {
    const menu = document.createElement('div');
    menu.id = 'menuContextual';
    menu.innerHTML = `
        <ul>
            <li id="ver">Ver</li>
        </ul>
    `;
    document.body.appendChild(menu);

    let selectedInput = null; // Almacena el input seleccionado

    // Asigna el evento a la tabla en lugar de a cada celda
    const table = document.getElementById('POFMH'); // Asegúrate de que este sea el ID correcto de la tabla

    table.addEventListener('contextmenu', (e) => {
        const td = e.target.closest('td'); // Encuentra el td más cercano al elemento que disparó el evento
        if (td) {
            e.preventDefault();
            const input = td.querySelector('input');
            if (input) {
                selectedInput = input;

                const { pageX: x, pageY: y } = e;
                // console.log("Click derecho detectado. Posición: ", x, y);
                // console.log("Input seleccionado: ", selectedInput);

                menu.style.left = `${x}px`;
                menu.style.top = `${y}px`;
                menu.style.display = 'block';
            }
        }
    });

    // Evento para ocultar el menú contextual
    document.addEventListener('click', () => {
        menu.style.display = 'none';
    });

    // Evento para habilitar el input cuando se selecciona "Ver"
    document.getElementById('ver').addEventListener('click', () => {
        if (selectedInput) {
            // console.log("Habilitando input: ", selectedInput);
            selectedInput.removeAttribute('disabled');
        }
    });

    // Evento para desactivar el input cuando se selecciona "Desactivar"
    // document.getElementById('desactivar').addEventListener('click', () => {
    //     if (selectedInput) {
    //         console.log("Desactivando input: ", selectedInput);
    //         const idPofmh = selectedInput.getAttribute('data-id'); // Captura el ID del registro
    //         const datoACambiar = ''; // Valor a enviar al servidor (vacío)

    //         let endpoint; // Determinamos el endpoint en función de la clase del input

    //         if (selectedInput.matches('.fechaaltacargo-input')) {
    //             endpoint = '/actualizarFechaAltaCargo';
    //         } else if (selectedInput.matches('.fechadesignado-input')) {
    //             endpoint = '/actualizarFechaDesignado';
    //         } else if (selectedInput.matches('.fechadesde-input')) {
    //             endpoint = '/actualizarFechaDesde';
    //         } else if (selectedInput.matches('.fechahasta-input')) {
    //             endpoint = '/actualizarFechaHasta';
    //         }

    //         // Realiza la solicitud al servidor
    //         fetch(endpoint, {
    //             method: 'POST',
    //             headers: {
    //                 'Content-Type': 'application/json',
    //                 'X-CSRF-TOKEN': csrfToken // Asegúrate de tener el token CSRF
    //             },
    //             body: JSON.stringify({ idPofmh: idPofmh, datoACambiar: datoACambiar }) // Envía el ID y el nuevo valor (vacío)
    //         })
    //         .then(response => response.json())
    //         .then(data => {
    //             if (data.success) {
    //                 console.log('Fecha desactivada con éxito:', data);
    //                 selectedInput.value = ''; // Establece el valor a null (vacío)
    //             } else {
    //                 console.error('Error al desactivar la fecha:', data.message);
    //             }
    //         })
    //         .catch(error => console.error('Error en la solicitud:', error));
    //     }
    // });
});
*/
/*
$(document).ready(function() {
    // Detectar clic en cualquier 'td'
    $('td').on('click', function(event) {
      const input = $(this).find('input:disabled, textarea:disabled'); // Buscar inputs deshabilitados dentro del td
      
      if (input.length > 0) { // Verificar si hay algún input o textarea deshabilitado
        console.log("quitando disabled"); // Mensaje para consola
        input.prop('disabled', false); // Habilitar el input o textarea
      }
    });
  });*/

$(document).ready(function () {
    // Delegar el evento click a todos los 'td', incluyendo los agregados dinámicamente
    $(document).on("click", "td", function (event) {
        const input = $(this).find("input:disabled, textarea:disabled"); // Buscar inputs deshabilitados dentro del td
        // Remover borde rojo de cualquier td previamente seleccionado
        $("td").removeClass("highlight");

        // Agregar borde rojo al td actual
        $(this).addClass("highlight");
        if (input.length > 0) {
            // Verificar si hay algún input o textarea deshabilitado
            console.log("quitando disabled"); // Mensaje para consola
            input.prop("disabled", false); // Habilitar el input o textarea
        }
    });
});

$(document).ready(function () {
    $(".orden-input").on("input", function () {
        // Remover caracteres que no son dígitos o punto
        $(this).val(
            $(this)
                .val()
                .replace(/[^0-9.]/g, "")
        );

        // Validar que solo se permita un punto
        if ($(this).val().split(".").length > 2) {
            $(this).val($(this).val().slice(0, -1)); // Remover el último carácter
        }
    });
});

/*aqui cargo todo para subir */

// DropzoneJS Demo Code Start
Dropzone.autoDiscover = false;
var csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute("content");

// Get the template HTML and remove it from the document
var previewNode = document.querySelector("#template2");
if (previewNode) {
    previewNode.id = "";

    var previewTemplate = previewNode.parentNode.innerHTML;
    previewNode.parentNode.removeChild(previewNode);

    // Función para inicializar Dropzone después de cargar completamente el DOM
    document.addEventListener("DOMContentLoaded", function () {
        var myDropzone = new Dropzone(document.body, {
            // Make the whole body a dropzone
            url: "/uploadpofmh", // Usa una URL relativa
            thumbnailWidth: 80,
            maxFilesize: 10, // en megabytes
            dictFileTooBig:
                "El archivo es demasiado grande ({{filesize}}MB). El tamaño máximo permitido es {{maxFilesize}}MB.",
            // Elimina 'params' aquí
            thumbnailHeight: 80,
            parallelUploads: 20,
            previewTemplate: previewTemplate,
            autoQueue: false, // Make sure the files aren't queued until manually added
            previewsContainer: "#previews", // Define the container to display the previews
            clickable: ".fileinput-button", // Define el elemento que actúa como disparador para seleccionar archivos
        });

        // Agregar los parámetros antes de enviar el archivo
        myDropzone.on("sending", function (file, xhr, formData) {
            formData.append("_token", csrfToken);
            formData.append("Agente", document.getElementById("DNI").value); // Agente (DNI)
            formData.append(
                "CueX",
                document.getElementById("novedad_cue").value
            ); // CueX desde 'novedad_cue'

            // Para depuración: verificar los valores que se están enviando
            console.log(
                "Enviando archivo con Agente:",
                document.getElementById("DNI").value
            );
            console.log(
                "Enviando archivo con CueX:",
                document.getElementById("novedad_cue").value
            );
        });

        myDropzone.on("addedfile", function (file) {
            // Hookup the start button
            var startButton = file.previewElement.querySelector(".start");
            if (startButton) {
                startButton.addEventListener("click", function () {
                    myDropzone.enqueueFile(file);
                });
            }
            console.log("Presionó start");
        });

        // Update the total progress bar
        myDropzone.on("totaluploadprogress", function (progress) {
            document.querySelector(
                "#total-progress .progress-bar"
            ).style.width = progress + "%";
        });

        myDropzone.on("sending", function (file) {
            // Show the total progress bar when upload starts
            document.querySelector("#total-progress").style.opacity = "1";
            // And disable the start button
            var startButton = file.previewElement.querySelector(".start");
            if (startButton) {
                startButton.setAttribute("disabled", "disabled");
            }
        });

        // Hide the total progress bar when nothing's uploading anymore
        myDropzone.on("queuecomplete", function (progress) {
            document.querySelector("#total-progress").style.opacity = "0";
        });

        // Setup the buttons for all transfers
        var startButton = document.querySelector("#actions .start");
        if (startButton) {
            startButton.onclick = function () {
                myDropzone.enqueueFiles(
                    myDropzone.getFilesWithStatus(Dropzone.ADDED)
                );
            };
        }

        var cancelButton = document.querySelector("#actions .cancel");
        if (cancelButton) {
            cancelButton.onclick = function () {
                myDropzone.removeAllFiles(true);
            };
        }

        // Manejar la respuesta JSON
        myDropzone.on("success", function (file, response) {
            if (response.success) {
                traerArchivos2();
                if (response.SubirDocExito) {
                    Swal.fire(
                        "Registro guardado",
                        "Archivo subido con éxito",
                        "success"
                    );
                }
                if (response.SubirDocFallo) {
                    Swal.fire(
                        "Registro guardado",
                        "No se encontró ningún archivo para subir",
                        "error"
                    );
                }
                if (response.SubirDocError) {
                    Swal.fire(
                        "Registro guardado",
                        "Vacante o sin Agente, no puede Subir Documentos",
                        "error"
                    );
                }
            } else {
                console.error("Error en la respuesta JSON:", response);
            }
        });

        // Manejar errores de carga
        myDropzone.on("error", function (file, errorMessage) {
            console.error("Error al subir el archivo:", errorMessage);
        });
    });
}

function traerArchivos2() {
    // Obtén el valor de los elementos HTML usando jQuery
    var agente = $("#DNI").val();
    var cueX = $("#novedad_cue").val();

    // Genera el objeto de datos que contiene los parámetros
    var data = {
        _token: "{{ csrf_token() }}", // Obtén el token CSRF de Laravel
        Agente: agente,
        CueX: cueX,
    };

    // Realiza la solicitud AJAX
    $.ajax({
        url: "/traerArchivospofmh",
        type: "GET",
        data: data, // Pasa los parámetros en el objeto de datos
        success: function (data) {
            // Actualiza el contenido del modal con los archivos recibidos en la respuesta
            $("#modalBody").html(data);
        },
        error: function (xhr, status, error) {
            console.error(xhr.responseText);
        },
    });
    //console.log("trayendo")
}

$(document).ready(function () {
    $("body").on("click", ".btn-delete-documento", function () {
        var documentoId = $(this).data("id");
        var row = $("#documento-" + documentoId);

        // Usar SweetAlert para la confirmación
        Swal.fire({
            title: "¿Estás seguro?",
            text: "No podrás revertir esto",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí, eliminarlo",
        }).then((result) => {
            if (result.isConfirmed) {
                // Si el usuario confirma, hacer la petición AJAX
                $.ajax({
                    url: "/borrarDocumentoAgentePof",
                    type: "POST",
                    data: {
                        _token: csrfToken, // Usando el token CSRF obtenido
                        doc: documentoId,
                    },
                    success: function (response) {
                        if (response.status === "success") {
                            row.remove();
                            Swal.fire("Eliminado", response.message, "success");
                        } else {
                            Swal.fire(
                                "Error",
                                "No se pudo eliminar el documento.",
                                "error"
                            );
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error(xhr.responseText);
                        Swal.fire(
                            "Error",
                            "Ocurrió un problema al procesar la solicitud.",
                            "error"
                        );
                    },
                });
            }
        });
    });
});

//SCRIPT ACTUALIZADO
document.addEventListener("DOMContentLoaded", function () {
    // Selecciona todos los selects con la clase "condicion-input"
    const selectElements = document.querySelectorAll(".condicion-input");

    selectElements.forEach((select) => {
        // Función para actualizar el color de la fila y la cuarta celda según el índice seleccionado
        const updateRowColor = () => {
            const selectedIndex = select.selectedIndex; // Obtener el índice seleccionado
            console.log("seleccion de condicion ", selectedIndex);
            const trElement = select.closest("tr"); // Obtener el tr más cercano al select
            // Determinar el color según el índice seleccionado
            let bgColor = "default"; // Valor por defecto
            if (selectedIndex === 2) {
                bgColor = "rosado-claro"; // Color rosado claro
            }

            if (
                selectedIndex === 1 ||
                selectedIndex === 3 ||
                selectedIndex === 4 ||
                selectedIndex === 5
            ) {
                bgColor = "azul-claro"; // Color azul claro
            } else {
                bgColor = "rosado-claro"; // Color rosado claro
            }
            // Actualizar el atributo "data-bg-color" del <tr>
            trElement.setAttribute("data-bg-color", bgColor);
        };
        // Agregar el evento "change" para cada select
        select.addEventListener("change", updateRowColor);
        // Aplicar el color al cargar la página por primera vez
        updateRowColor();
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const table = document.getElementById("POFMH");
    const tbody = table.querySelector("tbody");

    tbody.addEventListener("click", function (event) {
        // Evita que el clic en botones o inputs afecte la selección de la fila
        const target = event.target;
        if (
            target.tagName.toLowerCase() === "button" ||
            target.tagName.toLowerCase() === "input" ||
            target.tagName.toLowerCase() === "select" ||
            target.tagName.toLowerCase() === "textarea"
        ) {
            return;
        }

        // Encuentra la fila más cercana al elemento clicado
        let tr = target.closest("tr");
        if (tr) {
            // Remueve la clase 'active' de todas las filas
            tbody
                .querySelectorAll("tr")
                .forEach((row) => row.classList.remove("active"));
            // Añade la clase 'active' a la fila clicada
            tr.classList.add("active");
        }
    });

    // Opcional: También puedes activar la fila al hacer clic en cualquier elemento dentro de la fila
    // Si deseas que los clics en inputs, botones, etc., también activen la fila, elimina el bloque 'if' anterior
});

//control de puntos y comas
// Definición de la función para asociar eventos a los inputs
const setUpDniValidation = () => {
    document.querySelectorAll(".dni-input").forEach((input) => {
        // Validación en tiempo real durante la escritura
        input.addEventListener("input", (event) => {
            // Eliminar cualquier carácter que no sea un dígito
            event.target.value = event.target.value.replace(/[^0-9]/g, "");
        });

        // Validación para eliminar puntos y comas mientras se escribe
        input.addEventListener("input", (event) => {
            event.target.value = event.target.value.replace(/[.,]/g, "");
        });

        // Validación cuando se pierde el foco (blur)
        input.addEventListener("blur", (event) => {
            // Eliminar puntos y comas si pegaron contenido
            event.target.value = event.target.value.replace(/[.,]/g, "");
        });
    });
};

document.addEventListener("DOMContentLoaded", () => {
    // Llamar a la función inicialmente al cargar el DOM
    setUpDniValidation();
});

// Función para llenar la tabla y llamar a la validación
function fillTable(data) {
    data.forEach((item) => {
        createNewRow(item.orden, item.dni);
    });

    // Llama a la función para configurar la validación en todos los inputs de la tabla
    setUpDniValidation();
}

function verificarDNI(dniInput) {
    const dni = dniInput.value; // Captura el valor del DNI
    const idPofmh = dniInput.getAttribute("data-id"); // Captura el ID del registro

    // Realiza la verificación del DNI
    fetch("/verificarDNI", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
        },
        body: JSON.stringify({ dni: dni }), // Envía el DNI para la verificación
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                // Cambia el color del input a verde si el DNI existe
                dniInput.style.backgroundColor = "lightgreen";
                const apenomInput = document.querySelector(
                    `#apenom-input-${idPofmh}`
                );
                // Actualiza el valor con el nombre y apellido
                apenomInput.value = `${data.ApeNom}`;

                // Habilita el input del apellido
                //apenomInput.removeAttribute('disabled');
                apenomInput.addEventListener("blur", function () {
                    const datoACambiar = this.value.toUpperCase(); // Captura el nuevo valor
                    this.value = datoACambiar;
                    const idPofmh = this.getAttribute("data-id"); // Captura el ID del registro
                    //this.setAttribute('disabled', 'disabled');

                    // Realiza la solicitud al servidor para actualizar el apellido
                    fetch("/actualizarApeNom", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": csrfToken, // Asegúrate de tener el token CSRF
                        },
                        body: JSON.stringify({
                            idPofmh: idPofmh,
                            datoACambiar: datoACambiar,
                        }), // Envía el ID y el nuevo valor
                    })
                        .then((response) => response.json())
                        .then((data) => {
                            if (data.success) {
                                console.log("Apellido actualizado con éxito:");
                            } else {
                                console.error(
                                    "Error al actualizar el apellido:",
                                    data.message
                                );
                            }
                        })
                        .catch((error) =>
                            console.error("Error en la solicitud:", error)
                        );
                });
            } else {
                // Cambia el color del input a rosado claro si el DNI no existe
                dniInput.style.backgroundColor = "lightpink";
            }
        })
        .catch((error) => console.error("Error en la solicitud:", error));
}

document.addEventListener("DOMContentLoaded", function () {
    const dniInputs = document.querySelectorAll(".dni-input"); // Selecciona todos los inputs de DNI
    dniInputs.forEach((input) => {
        if (input.value.trim() !== "") {
            // Verifica si el input no está vacío
            verificarDNI(input); // Verifica solo si hay un DNI válido
        } else {
            input.style.backgroundColor = "lightslategray"; // Restablece el color si el campo está vacío
        }
    });
});

//manejo de color en fila si hay mensaje de la super
$(document).ready(function () {
    $("textarea.zonasupervision").each(function () {
        // Revisamos si el valor es distinto de vacío (espacios no cuentan)
        if ($.trim($(this).val()) !== "") {
            // Agregamos la clase de borde rojo al <tr> más cercano
            $(this).closest("tr").addClass("tr-border-red");
        }
    });
});

//manejo del check
const tabla = document.querySelector("#POFMH tbody");

tabla.addEventListener("change", function (event) {
    // Verifica si el elemento que disparó el evento es un checkbox
    const checkbox = event.target.closest('input[type="checkbox"]'); // Corrige el selector a 'input[type="checkbox"]'
    if (checkbox) {
        const fila = checkbox.closest("tr"); // Encuentra la fila contenedora del checkbox

        // Selecciona todos los inputs y textareas en la fila excepto el checkbox
        const inputs = fila.querySelectorAll(
            'input[type="text"], input[type="number"], textarea:not(#super)'
        );

        // Si el checkbox está marcado, quita el disabled de los inputs y añade la clase
        if (checkbox.checked) {
            inputs.forEach((input) => {
                input.removeAttribute("disabled");
                input.classList.add("input-editable"); // Añade la clase para el borde rojo
            });
        } else {
            // Si el checkbox no está marcado, vuelve a poner disabled y quita la clase
            inputs.forEach((input) => {
                input.setAttribute("disabled", "disabled");
                input.classList.remove("input-editable"); // Quita la clase para el borde
            });
        }
    }
});
