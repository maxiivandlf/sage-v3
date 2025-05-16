$(document).on("click", "#btnVerDocumentosNovedades", function (event) {
    event.preventDefault();

    // Obtener el ID de la novedad desde el atributo data-idnovedad
    var idNovedad = $(this).data("idnovedad");

    // URL para obtener los documentos de la novedad
    var url = "/liquidacion/verDocumentosNovedades/";

    // Realizar la solicitud
    $.ajax({
        url: url,
        method: "GET",
        data: {
            idNovedad: idNovedad,
        },

        success: function (documentosNovedades) {
            // Limpiar el contenedor de documentos
            $("#documentos-lista tbody").empty();

            // Recorrer los documentos y agregarlos a la tabla
            $.each(documentosNovedades, function (index, documento) {
                var row = `
                            <tr class="gradeX ${
                                index % 2 === 0
                                    ? "table-primary"
                                    : "table-secondary"
                            }" data-id="${documento.idDocumento}">
                                <td class="text-center">${
                                    documento.Agente || "Sin datos"
                                }</td>
                                <td class="text-center">${
                                    documento.idDocumento || "Sin datos"
                                }</td>
                                <td class="text-center">
                                    
                                        ${
                                            documento.URL || "Sin datos"
                                        }                                   
                                </td>
                                <td class="text-center">   
                                    <a href="/storage/DOCUMENTOS/${
                                        documento.CUECOMPLETO
                                    }/${documento.Agente}/${
                    documento.URL
                }" target="_blank" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> Ver
                                    </a>                    
                                </td>
                            </tr>`;
                $("#documentos-lista tbody").append(row);
            });

            // Mostrar el modal
            $("#modalVerDocumentosNovedades").modal("show");
        },
        error: function (xhr, status, error) {
            console.error(
                "Error al cargar los documentos de novedades:",
                error
            );
            alert(
                "No se pudieron cargar los documentos. Inténtalo nuevamente."
            );
        },
    });
});
