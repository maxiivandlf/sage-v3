$(document).ready(function () {
    $("#buscar_dni_cue").on("submit", function (event) {
        event.preventDefault();

        var dni = $('input[name="dni"]').val();
        var token = $('meta[name="csrf-token"]').attr("content");

        if (!dni) {
            alert("Por favor, ingresa un DNI.");
            return;
        }

        // Mostrar loaders y ocultar contenido
        $("#loader-circle").css("display", "inline-block");
        $("#icon-search").css("display", "none");
        $(".tab-content .table").hide();
        $("#feedback-inicial").hide();
        $("#skeleton-loader").show();
        $("#text-skeleton").show();
        $("#skeleton-row").show();

        $.ajax({
            url: "/buscar_dni_ajax_liq",
            type: "POST",
            data: {
                _token: token,
                dni: dni,
            },
            success: function (response) {
                $("#tabla-personal tbody").empty();
                $("#tabla-pof tbody").empty();
                $("#tabla-institucional tbody").empty();
                $("#tabla-aulica tbody").empty();

                if (Object.keys(response).length === 0) {
                    alert("No se encontraron usuarios.");
                    return;
                }

                Object.entries(response).forEach(([key, value]) => {
                    if (value.personal) {
                        var personalRow = `
                            <tr>
                                <td>${value.personal.ApeNom || "-"}</td>
                                <td>${value.personal.Documento || "-"}</td>
                                <td>${value.personal.Cuil || "-"}</td>
                                <td>${
                                    value.personal.Sexo === "M"
                                        ? "MASCULINO"
                                        : value.personal.Sexo === "F"
                                        ? "FEMENINO"
                                        : "-"
                                }</td>
                            </tr>
                        `;
                        $("#tabla-personal tbody").append(personalRow);
                    }

                    if (value.pof) {
                        Object.entries(value.pof).forEach(([key, pofItem]) => {
                            var pofRow = `
                                    <tr>
                                        <td>${
                                            pofItem.Situacion_Revista || "-"
                                        }</td>
                                        <td>${pofItem.Antiguedad || "-"}</td>
                                        <td>${pofItem.Hora || "-"}</td>
                                        <td>${
                                            pofItem.Cargo_Salarial || "-"
                                        }</td>
                                        <td>${
                                            pofItem.Codigo_Salarial || "-"
                                        }</td>
                                        <td>${
                                            pofItem.Posesion_Cargo || "-"
                                        }</td>
                                        <td>${
                                            pofItem.Designado_Cargo || "-"
                                        }</td>
                                    </tr>
                                `;
                            $("#tabla-pof tbody").append(pofRow);
                        });
                    }

                    if (value.institucional) {
                        Object.entries(value.institucional).forEach(
                            ([instKey, institucionalItem]) => {
                                var institucionalRow = `
                                    <tr>
                                    <td>${institucionalItem.CUE || "-"}</td>
                                    <td>${
                                        institucionalItem.Codigo_Liq +
                                            "-" +
                                            institucionalItem.Area_Liq || "S/U"
                                    }</td>
                                    <td>${
                                        institucionalItem.Nombre_Institucion ||
                                        "-"
                                    }</td>
                                    <td>${institucionalItem.Nivel || "-"}</td>
                                    <td>${institucionalItem.Zona || "-"}</td>
                                    <td>${
                                        institucionalItem.Domicilio || "-"
                                    }</td>
                                    <td>${
                                        institucionalItem.Localidad || "-"
                                    }</td>
                                </tr>
                                    `;
                                $("#tabla-institucional tbody").append(
                                    institucionalRow
                                );
                            }
                        );
                    }
                    if (value.aulica) {
                        Object.entries(value.aulica).forEach(
                            ([key, aulicaItem]) => {
                                var aulicaRow = `
                                <tr>
                                    <td>${
                                        aulicaItem.Nombre_Institucion || "-"
                                    }</td>
                                    <td>${aulicaItem.Aula || "-"}</td>
                                    <td>${aulicaItem.Division || "-"}</td>
                                    <td>${aulicaItem.Turno || "-"}</td>
                                    <td>${aulicaItem.EspCur || "-"}</td>
                                    <td>${aulicaItem.Matricula || "-"}</td>
                                    <td>${aulicaItem.Condicion || "-"}</td>
                                    <td>${aulicaItem.EnFuncion || "-"}</td>
                                    <td>${
                                        aulicaItem.observacion_cond || "-"
                                    }</td>
                                    <td>${aulicaItem.AsisTotal || "-"}</td>
                                    <td>${aulicaItem.AsistJust || "-"}</td>
                                    <td>${aulicaItem.AsistInjust || "-"}</td>
                                </tr>
                            `;
                                $("#tabla-aulica tbody").append(aulicaRow);
                            }
                        );
                    }
                });
            },

            error: function (xhr, status, error) {
                $("#feedback-inicial").show();
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

        $.ajax({
            url: "/liquidacion/verNovedadesAgente",
            method: "POST",
            data: {
                _token: token,
                dni: dni,
            },

            success: function (novedadesAgente) {
                $("#novedadesAgente tbody").empty();

                $.each(novedadesAgente, function (key, novedad) {
                    var row = `
                        <tr class="gradeX" data-id="${novedad.idNovedad}">
                          <td>${novedad.Agente || "Sin datos"}</td>
                          <td class="text-center">${new Date(
                              novedad.FechaDesde
                          ).toLocaleDateString("es-ES")}</td>
                          <td class="text-center">${new Date(
                              novedad.FechaHasta
                          ).toLocaleDateString("es-ES")}</td>
                          <td class="text-center">${
                              novedad.TotalDiasLicencia || "1"
                          }</td>
                          <td class="text-center">${
                              novedad.tipo_novedad || "Sin novedad"
                          }</td>
                          <td class="text-center">${novedad.Motivo}</td>
                          <td>${
                              novedad.Observaciones || "Sin observaciones"
                          }</td>
                            <td>
                                <buttom type="button" id="btnVerDocumentosNovedades" class="btn btn-default view-novedades" data-toggle="modal" data-target="#modal-novedades" 
                                    data-idnovedad="${novedad.Agente}">
                                    <i class="fas fa-paperclip"></i>
                                </buttom>
                                
                            </td>
                      </tr>`;
                    $("#novedadesAgente tbody").append(row);
                });
            },
            complete: function () {
                // Ocultar loaders y mostrar contenido
                $("#loader-circle").css("display", "none");
                $("#icon-search").css("display", "inline-block");
                $(".tab-content .table").show();
                $("#skeleton-loader").hide();
                $("#text-skeleton").hide();
                $("#skeleton-row").hide();
            },
            error: function (xhr) {
                console.error("Error al cargar las novedades:", xhr);
            },
        });
    });
});

// $(document).ready(function () {
//     // Función para cargar datos en la tabla de novedades
//     function cargarNovedades() {
//         console.log("dentro de carga");
//         var dni = $("#DNI").val();
//         var cue = $("#valCUE").val();
//         console.log(dni, cue);
//         $.ajax({
//             url: "/pofmhNovedades/" + dni + "/" + cue, // Ruta definida en web.php
//             method: "GET",
//             dataType: "json",
//             success: function (data) {
//                 // Limpiar la tabla antes de llenarla
//                 $("#novedadesAgente tbody").empty();

//                 // Iterar sobre los datos y llenar la tabla
//                 $.each(data.novedades, function (key, n) {
//                     // Aquí usamos data.novedades
//                     let motivo = data.Motivos.find(
//                         (m) => m.idMotivo === n.Motivo
//                     ) || { Codigo: "N/A", Nombre_Licencia: "N/A" };

//                     var row = `<tr class="gradeX" data-id="${n.idNovedad}">
//                       <td>${n.Agente || "Sin datos"}</td>
//                       <td class="text-center">${new Date(
//                           n.FechaDesde
//                       ).toLocaleDateString("es-ES")}</td>
//                       <td class="text-center">${new Date(
//                           n.FechaHasta
//                       ).toLocaleDateString("es-ES")}</td>
//                       <td class="text-center">${n.TotalDiasLicencia || "1"}</td>
//                       <td class="text-center">${
//                           n.tipo_novedad || "Sin novedad"
//                       }</td>
//                       <td class="text-center">${motivo.Codigo}-${
//                         motivo.Nombre_Licencia
//                     }</td>
//                       <td>${n.Observaciones || "Sin observaciones"}</td>
//                       <td>
//                           Sin Acciones
//                       </td>
//                   </tr>`;
//                     $("#novedadesAgente tbody").append(row);
//                 });
//             },
//             error: function (xhr) {
//                 console.error("Error al cargar las novedades:", xhr);
//             },
//         });
//     }
//     // Cargar datos al abrir el modal
//     $("#modal-novedades").on("show.bs.modal", function () {
//         cargarNovedades(); // Llama a la función para cargar las novedades
//     });

//     $(".pofmhformularioNovedadParticular").submit(function (e) {
//         e.preventDefault();

//         var dni = $("#DNI").val();
//         var fi = $("#FechaInicio").val();
//         var fh = $("#FechaHasta").val();
//         var ob = $("#novedad_observacion").val();

//         if (!dni || !fi || !fh || !ob) {
//             Swal.fire({
//                 title: "Error",
//                 text: "Debe completar todos los campos solicitados.",
//                 icon: "error",
//             });
//             return;
//         }

//         Swal.fire({
//             title: "¿Está seguro de querer agregar una novedad para el Agente?",
//             text: "Recuerde colocar datos verdaderos",
//             icon: "warning",
//             showCancelButton: true,
//             confirmButtonColor: "#3085d6",
//             cancelButtonColor: "#d33",
//             confirmButtonText: "Si, guardo el registro!",
//         }).then((result) => {
//             if (result.isConfirmed) {
//                 var formData = new FormData(this);

//                 $.ajax({
//                     url: $(this).attr("action"), // URL del formulario
//                     method: "POST",
//                     data: formData,
//                     processData: false,
//                     contentType: false,
//                     headers: {
//                         "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
//                             "content"
//                         ),
//                     },
//                     success: function (response) {
//                         console.log(response);
//                         Swal.fire(
//                             "Éxito",
//                             "Novedad agregada correctamente.",
//                             "success"
//                         );
//                         cargarNovedades(); // Actualiza la tabla de novedades después de agregar
//                     },
//                     error: function (xhr, status, error) {
//                         console.error(xhr.responseText);
//                         Swal.fire(
//                             "Error",
//                             "No se pudo agregar la novedad.",
//                             "error"
//                         );
//                     },
//                 });
//             }
//         });
//     });
// });
