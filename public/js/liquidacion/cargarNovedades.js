$(document).ready(function () {
    function cargarNovedades() {
        var dni = $("#DNI").val();
        console.log(dni, cue);
        $.ajax({
            url: "/pofmhNovedades/" + dni + "/" + cue, // Ruta definida en web.php
            method: "GET",
            dataType: "json",
            success: function (data) {
                $("#tab_2 tbody").empty();

                // Iterar sobre los datos y llenar la tabla
                $.each(data.novedades, function (key, n) {
                    // Aquí usamos data.novedades
                    let motivo = data.Motivos.find(
                        (m) => m.idMotivo === n.Motivo
                    ) || {
                        Codigo: "N/A",
                        Nombre_Licencia: "N/A",
                    };

                    var row = `<tr class="gradeX" data-id="${n.idNovedad}">
                 <td>${n.Agente || "Sin datos"}</td>
                 <td class="text-center">${new Date(
                     n.FechaDesde
                 ).toLocaleDateString("es-ES")}</td>
                 <td class="text-center">${new Date(
                     n.FechaHasta
                 ).toLocaleDateString("es-ES")}</td>
                 <td class="text-center">${n.TotalDiasLicencia || "1"}</td>
                 <td class="text-center">${n.tipo_novedad || "Sin novedad"}</td>
                 <td class="text-center">${motivo.Codigo}-${
                        motivo.Nombre_Licencia
                    }</td>
                 <td>${n.Observaciones || "Sin observaciones"}</td>
                 <td>
                     Sin Acciones
                 </td>
             </tr>`;
                    $("#tab_2 tbody").append(row);
                });
            },
            error: function (xhr) {
                console.error("Error al cargar las novedades:", xhr);
            },
        });
    }

    // Cargar datos al abrir el modal
    $("#modal-novedades").on("show.bs.modal", function () {
        cargarNovedades(); // Llama a la función para cargar las novedades
    });

    $(".pofmhformularioNovedadParticular").submit(function (e) {
        e.preventDefault();

        var dni = $("#DNI").val();
        var fi = $("#FechaInicio").val();
        var fh = $("#FechaHasta").val();
        var ob = $("#novedad_observacion").val();

        if (!dni || !fi || !fh || !ob) {
            Swal.fire({
                title: "Error",
                text: "Debe completar todos los campos solicitados.",
                icon: "error",
            });
            return;
        }

        Swal.fire({
            title: "¿Está seguro de querer agregar una novedad para el Agente?",
            text: "Recuerde colocar datos verdaderos",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, guardo el registro!",
        }).then((result) => {
            if (result.isConfirmed) {
                var formData = new FormData(this);

                $.ajax({
                    url: $(this).attr("action"), // URL del formulario
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    success: function (response) {
                        console.log(response);
                        Swal.fire(
                            "Éxito",
                            "Novedad agregada correctamente.",
                            "success"
                        );
                        cargarNovedades(); // Actualiza la tabla de novedades después de agregar
                    },
                    error: function (xhr, status, error) {
                        console.error(xhr.responseText);
                        Swal.fire(
                            "Error",
                            "No se pudo agregar la novedad.",
                            "error"
                        );
                    },
                });
            }
        });
    });
});
