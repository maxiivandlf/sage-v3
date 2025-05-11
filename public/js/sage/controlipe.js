$(document).ready(function () {
    // Evento para checkbox SI
    $(".checkbox-ipe-si").on("change", function () {
        const id = $(this).data("id");

        // Marcar SI, desmarcar NO
        $(`#ipe_no_${id}`).prop("checked", false);

        const checked = $(this).is(":checked") ? "SI" : "";

        if (checked === "SI") {
            actualizarIPE(id, "SI");
        }
    });

    // Evento para checkbox NO
    $(".checkbox-ipe-no").on("change", function () {
        const id = $(this).data("id");

        // Marcar NO, desmarcar SI
        $(`#ipe_si_${id}`).prop("checked", false);

        const checked = $(this).is(":checked") ? "NO" : "";

        if (checked === "NO") {
            actualizarIPE(id, "NO");

            //si es no, actualizar la hora de la fila a cero
            $(`#hora_${id}`).val(0).trigger("input");
        }
    });

    // Función AJAX común
    function actualizarIPE(id, valor) {
        $.ajax({
            url: "/actualizar_ipe",
            method: "POST",
            data: {
                id: id,
                IPE: valor,
                _token: $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                // console.log("IPE actualizado:", response);
            },
            error: function (xhr) {
                console.error("Error actualizando IPE:", xhr.responseText);
            },
        });
    }

    // Evento para checkbox SI relacionado
    $(".checkbox-ipe-si-r1").on("change", function () {
        const id = $(this).data("id");
        const idr1 = $(this).data("idr1");

        if ($(this).is(":checked")) {
            // Desmarcar NO
            $(`#ipe_no_r1_${id}`).prop("checked", false);
            actualizarIPER1(id, "SI", idr1);
        } else {
            // Si se desmarca manualmente, quitamos ambos
            actualizarIPER1(id, null, idr1);
        }
    });

    // Evento para checkbox NO relacionado
    $(".checkbox-ipe-no-r1").on("change", function () {
        const id = $(this).data("id");
        const idr1 = $(this).data("idr1");

        if ($(this).is(":checked")) {
            // Desmarcar SI
            $(`#ipe_si_r1_${id}`).prop("checked", false);
            actualizarIPER1(id, "NO", idr1);
            $(`#hora_r1_${id}`).val(0).trigger("input");
        } else {
            // Si se desmarca manualmente, quitamos ambos
            actualizarIPER1(id, null, idr1);
        }
    });

    // Función AJAX común relacionado
    function actualizarIPER1(id, valor, idr1) {
        console.log("Actualizar IPE R1", id, valor, idr1);
        $.ajax({
            url: "/actualizar_ipe_r1",
            method: "POST",
            data: {
                id: id,
                idr1: idr1,
                IPER1: valor,
                _token: $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                // console.log("IPE actualizado:", response);
            },
            error: function (xhr) {
                console.error("Error actualizando IPE:", xhr.responseText);
            },
        });
    }

    // Evento para Pertenece
    $(".checkbox-pertenece-normal").on("change", function () {
        const id = $(this).data("id");
        const checked = $(this).is(":checked") ? "SI" : "NO";

        // Actualiza el texto visual
        $(this).closest(".form-check").find(".estado-pertenece").text(checked);

        // Enviar AJAX (acá deberías tener otro endpoint)
        $.ajax({
            url: "/actualizar_pertenece",
            method: "POST",
            data: {
                id: id,
                Pertenece: checked,
                _token: $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                //console.log("Pertenece actualizado:", response);
            },
            error: function (xhr) {
                console.error(
                    "Error actualizando Pertenece:",
                    xhr.responseText
                );
            },
        });
    });

    // Evento para Pertenece Relacionado
    $(".checkbox-pertenece-relacionado").on("change", function () {
        const id = $(this).data("id");
        const checked = $(this).is(":checked") ? "SI" : "NO";

        // Actualiza el texto visual
        $(this)
            .closest(".form-check")
            .find(".estado-pertenece-r1")
            .text(checked);

        // Enviar AJAX (acá deberías tener otro endpoint)
        $.ajax({
            url: "/actualizar_pertenece_r1",
            method: "POST",
            data: {
                id: id,
                Pertenece_R1: checked,
                _token: $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                //console.log("Pertenece actualizado:", response);
            },
            error: function (xhr) {
                console.error(
                    "Error actualizando Pertenece:",
                    xhr.responseText
                );
            },
        });
    });

    // Turno normal
    $(".turno-normal").on("change", function () {
        const id = $(this).data("id");
        const turno = $(this).val();

        $.post("/actualizar_turno", {
            id: id,
            Turno: turno,
            _token: $('meta[name="csrf-token"]').attr("content"),
        })
            .done(function (response) {
                console.log("Turno normal actualizado:", response);
            })
            .fail(function (xhr) {
                console.error(
                    "Error actualizando turno normal:",
                    xhr.responseText
                );
            });
    });
    // hora normal en input
    $(".hora-normal").on("input", function () {
        const id = $(this).data("id");
        const hora = $(this).val();

        console.log("hora normal:", hora);
        console.log("id normal:", id);

        $.post("/actualizar_hora", {
            id: id,
            Hora: hora,
            _token: $('meta[name="csrf-token"]').attr("content"),
        })
            .done(function (response) {
                console.log("Horas actualizado:", response);
            })
            .fail(function (xhr) {
                console.error(
                    "Error actualizando horas normal:",
                    xhr.responseText
                );
            });
    });

    // Turno relacionado
    $(".turno-relacionado").on("change", function () {
        const idr1 = $(this).data("idr1");
        const turno = $(this).val();

        $.post("/actualizar_turno_relacionado", {
            idr1: idr1,
            TurnoR1: turno,
            _token: $('meta[name="csrf-token"]').attr("content"),
        })
            .done(function (response) {
                console.log("Turno relacionado actualizado:", response);
            })
            .fail(function (xhr) {
                console.error(
                    "Error actualizando turno relacionado:",
                    xhr.responseText
                );
            });
    });

    // hora relacionada
    $(".hora-relacionado").on("input", function () {
        const idpof = $(this).data("id");
        const idr1 = $(this).data("idr1");
        const hora = $(this).val();

        console.log("hora normal:", hora);
        console.log("id normal:", idpof); // ✅ corregido

        $.post("/actualizar_hora_relacionado", {
            idr1: idr1,
            idpof: idpof,
            HoraR1: hora,
            _token: $('meta[name="csrf-token"]').attr("content"),
        })
            .done(function (response) {
                console.log("Horas actualizado:", response);
            })
            .fail(function (xhr) {
                console.error(
                    "Error actualizando horas normal:",
                    xhr.responseText
                );
            });
    });
    // Evento para generar el excel de ipe
    // document
    //     .getElementById("btn-exportar")
    //     .addEventListener("click", function (e) {
    //         e.preventDefault();
    //         e.stopPropagation();
    //         const table = document.getElementById("tablacontrolIpe");

    //         if (table) {
    //             // Tomamos los datos desde los atributos del botón
    //             const mes = this.getAttribute("data-mes")?.replace(/\s/g, "");
    //             const unidad = this.getAttribute("data-liq")?.replace(
    //                 /\s/g,
    //                 ""
    //             );
    //             const cue = this.getAttribute("data-cue");

    //             // Armamos el nombre del archivo
    //             const nombreArchivo = `controlIPE-${mes}-Escu-${unidad}-CUE-${cue}.xlsx`;

    //             const workbook = XLSX.utils.table_to_book(table, {
    //                 sheet: "IPE",
    //             });
    //             XLSX.writeFile(workbook, nombreArchivo);
    //         } else {
    //             alert("No se encontró la tabla para exportar");
    //         }
    //     });
});

//Servicios Generales - AGentes
function getAgentesIPE() {
    if ($("#buscarAgente").val() != "") {
        $.ajax({
            type: "get",
            url: "/getAgentesIPE/" + $("#buscarAgente").val(),
            success: function (response) {
                document.getElementById("contenidoAgentes").innerHTML =
                    response.msg;
            },
        });
    }
}

function seleccionarAgentes(idPofmh) {
    var Agente = document.getElementById("dniAgenteModal" + idPofmh).value;
    var nomAgente = document.getElementById("nomAgenteModal" + idPofmh).value;
    console.log(Agente);
    document.getElementById("DNI").value = Agente;
    document.getElementById("ApeNom").value = nomAgente;
    document.getElementById("idpof").value = idPofmh;
    $("#modalAgente").modal("hide");
}

$("#modalAgente").on("shown.bs.modal", function () {
    $("#buscarAgente").focus();
});

//para agregar persona al control de ipe, en caso de no existir pero sin perder la relacion de la original
$(document).on("click", ".btn-agregar-agente", function () {
    const idPofIpe = $(this).data("id");
    const dni = $(this).data("dni");
    const cue = window.appData?.cue;
    const mes = window.appData?.mes;

    Swal.fire({
        title: "¿Agregar Agente?",
        text: `¿Querés agregar al agente con DNI ${dni}?`,
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Sí, agregar",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/agregar_agente_ipe",
                method: "POST",
                data: {
                    idPofIpe: idPofIpe,
                    CUECOMPLETO: cue,
                    Mes: mes,
                    _token: $('meta[name="csrf-token"]').attr("content"),
                },
                success: function (response) {
                    Swal.fire("Agregado!", response.message, "success").then(
                        () => {
                            location.reload(); // recarga toda la página
                        }
                    );
                },
                error: function (xhr) {
                    Swal.fire(
                        "Error",
                        xhr.responseJSON?.message || "No se pudo agregar.",
                        "error"
                    );
                },
            });
        }
    });
});

//control para eliminar u ocultar la base
$(document).on("click", ".eliminar-agente_base", function () {
    const idPofIpe = $(this).data("idpof");
    const idRel = $(this).data("idrel");

    Swal.fire({
        title: "¿Estás seguro?",
        text: "Esta acción eliminará el agente de su lista, la recuperación sera controlara por Referentes.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/eliminar_agente_base",
                method: "POST",
                data: {
                    idPofIpe: idPofIpe,
                    idRel: idRel,
                    _token: $('meta[name="csrf-token"]').attr("content"),
                },
                success: function (response) {
                    Swal.fire("Eliminado", response.message, "success").then(
                        () => {
                            // oculta la fila del agente base
                            $('tr[data-id="' + idPofIpe + '"]').fadeOut(
                                300,
                                function () {
                                    $(this).remove();
                                }
                            );
                        }
                    );
                },
                error: function (xhr) {
                    Swal.fire(
                        "Error",
                        "No se pudo eliminar el agente.",
                        "error"
                    );
                    console.error("Error:", xhr.responseText);
                },
            });
        }
    });
});
//control para eliminar la fila relacionada
$(document).on("click", ".eliminar-agente", function () {
    const idPofIpe = $(this).data("idpof");
    const idRel = $(this).data("idrel");

    Swal.fire({
        title: "¿Estás seguro?",
        text: "Esta acción eliminará la relación y limpiará los datos relacionados del agente.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/eliminar_agente_relacionado",
                method: "POST",
                data: {
                    idPofIpe: idPofIpe,
                    idRel: idRel,
                    _token: $('meta[name="csrf-token"]').attr("content"),
                },
                success: function (response) {
                    Swal.fire("Eliminado", response.message, "success").then(
                        () => {
                            // oculta la fila del agente base
                            $('tr[data-id="' + idPofIpe + '"]').fadeOut(
                                300,
                                function () {
                                    $(this).remove();
                                }
                            );
                        }
                    );
                },
                error: function (xhr) {
                    Swal.fire(
                        "Error",
                        "No se pudo eliminar el agente.",
                        "error"
                    );
                    console.error("Error:", xhr.responseText);
                },
            });
        }
    });
});

//bsucador
$(document).ready(function () {
    $("#searchInput").on("keyup", function () {
        const value = $(this).val().toLowerCase().trim();

        $("#tablacontrolIpe tbody tr").each(function () {
            const dni = $(this).find(".dni-input").text().toLowerCase().trim();
            const name = $(this)
                .find(".apenom-input")
                .text()
                .toLowerCase()
                .trim();
            const area = $(this)
                .find(".area-input")
                .text()
                .toLowerCase()
                .trim();

            const matches =
                dni.includes(value) ||
                name.includes(value) ||
                area.includes(value);
            $(this).toggle(matches);
        });
    });
});

//validando para ver el check
$(document).ready(function () {
    function validarCampos(id) {
        const turno = $(`#turno_${id}`).val();
        const ipe_si = $(`#ipe_si_${id}`).is(":checked");
        const horas = parseFloat($(`#hora_${id}`).val());

        const esValido = turno && ipe_si && horas > 0;

        const $check = $(`#estado_${id} .check-validacion`);
        if (esValido) {
            $check.removeClass("d-none");
        } else {
            $check.addClass("d-none");
        }
    }

    // Eventos que disparan la validación
    $(".turno-normal").change(function () {
        const id = $(this).data("id");
        validarCampos(id);
    });

    $(".checkbox-ipe-si, .checkbox-ipe-no").change(function () {
        const id = $(this).data("id");

        // Asegura que sólo uno esté activo (si querés forzar que se deseleccione el otro)
        if ($(this).hasClass("checkbox-ipe-si")) {
            $(`#ipe_no_${id}`).prop("checked", false);
        } else {
            $(`#ipe_si_${id}`).prop("checked", false);
        }

        validarCampos(id);
    });

    $(".hora-normal").on("input", function () {
        const id = $(this).data("id");
        validarCampos(id);
    });

    // Ejecutar una vez al cargar por si hay datos precargados
    $(".turno-normal").each(function () {
        const id = $(this).data("id");
        validarCampos(id);
    });
});

//control check para el relacional
$(document).ready(function () {
    function validarCamposRelacionado(idRel) {
        const $turno = $(`.turno-relacionado[data-idr1="${idRel}"]`);
        const $ipe_si = $(`.checkbox-ipe-si-r1[data-idr1="${idRel}"]`);
        const $hora = $(`.hora-relacionado[data-idr1="${idRel}"]`);

        const turnoVal = $turno.val();
        const ipe_si_checked = $ipe_si.is(":checked");
        const horasVal = parseFloat($hora.val());

        // Capturar el idPofIpe que está en data-id
        const idPof = $ipe_si.data("id");

        const $check = $(`#check2_${idPof}`);
        const $estado = $(`#estado2_${idPof}`);

        const esValido = turnoVal && ipe_si_checked && horasVal > 0;

        if ($check.length === 0) {
            console.warn(`❌ No se encontró #check2_${idPof}`);
        }

        if (esValido) {
            $check.removeClass("d-none");
        } else {
            $check.addClass("d-none");
        }
    }

    // Turno relacionado
    $(document).on("change", ".turno-relacionado", function () {
        const idRel = $(this).data("idr1");
        validarCamposRelacionado(idRel);
    });

    // Checkbox IPE SI/NO relacionados
    $(document).on(
        "change",
        ".checkbox-ipe-si-r1, .checkbox-ipe-no-r1",
        function () {
            const idRel = $(this).data("idr1");

            if ($(this).hasClass("checkbox-ipe-si-r1")) {
                $(`.checkbox-ipe-no-r1[data-idr1="${idRel}"]`).prop(
                    "checked",
                    false
                );
            } else {
                $(`.checkbox-ipe-si-r1[data-idr1="${idRel}"]`).prop(
                    "checked",
                    false
                );
            }

            validarCamposRelacionado(idRel);
        }
    );

    // Horas relacionadas
    $(document).on("input", ".hora-relacionado", function () {
        const idRel = $(this).data("idr1");
        validarCamposRelacionado(idRel);
    });

    // Validar todos al cargar
    $(".turno-relacionado").each(function () {
        const idRel = $(this).data("idr1");
        validarCamposRelacionado(idRel);
    });
});
