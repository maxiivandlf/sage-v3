$(document).ready(function() {
    var table = $('#myTable').DataTable({
        responsive: true,
        dom: 'Bfrtip',
        order: [[0, 'desc']], // Ordenar por la primera columna, de forma ascendente
        buttons: [
            {
                extend: 'copy',
                text: 'Copiar',
                className: 'btn-custom' // Clase personalizada
            },
            {
                extend: 'csv',
                text: 'CSV',
                className: 'btn-custom' // Clase personalizada
            },
            {
                extend: 'excel',
                text: 'Excel',
                className: 'btn-custom' // Clase personalizada
            },
            {
                extend: 'pdf',
                text: 'PDF',
                orientation: 'landscape', // Horizontal
                pageSize: 'A4', // Tamaño de página
                customize: function (doc) {
                    doc.content[1].table.widths = ['*', '*', '*', '*', '*', '*', '*', '*', '*', '*', '*', '*'];
                    doc.styles.tableHeader = {
                        fontSize: 10,
                        bold: true,
                        alignment: 'center'
                    };
                    doc.styles.tableBodyOdd = {
                        fontSize: 9
                    };
                    doc.styles.tableBodyEven = {
                        fontSize: 9
                    };
                }
            },
            {
                extend: 'print',
                text: 'Imprimir',
                className: 'btn-custom' ,// Clase personalizada
                customize: function (win) {
                    $(win.document.body).css({
                        'font-size': '10pt',
                        'line-height': '1.5',
                        'margin': '0',
                        'padding': '0'
                    });
                    $(win.document.body).find('table')
                        .css('width', '100%')
                        .css('border-collapse', 'collapse')
                        .css('font-size', '10pt');
                    $(win.document.body).find('.dt-buttons').remove();
                }
            }
        ],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
        }
    });
    // Mover los botones a #buttons-container
    table.buttons().container().appendTo('#buttons-container');
     // Ajustar los estilos de los botones en móviles
     if ($(window).width() < 768) {
        $('#buttons-container').css({
            'flex-direction': 'column',
            'align-items': 'center'
        });
    }
});

$(document).ready(function() {
    var table = $('#myTable2').DataTable({
        responsive: true,
        dom: 'Bfrtip',
        order: [[0, 'desc']], // Ordenar por la primera columna, de forma ascendente
        buttons: [
            {
                extend: 'copy',
                text: 'Copiar',
                className: 'btn-custom' // Clase personalizada
            },
            {
                extend: 'csv',
                text: 'CSV',
                className: 'btn-custom' // Clase personalizada
            },
            {
                extend: 'excel',
                text: 'Excel',
                className: 'btn-custom' // Clase personalizada
            },
            {
                extend: 'pdf',
                text: 'PDF',
                orientation: 'landscape', // Horizontal
                pageSize: 'A4', // Tamaño de página
                customize: function (doc) {
                    doc.content[1].table.widths = ['*', '*', '*', '*', '*', '*', '*', '*', '*', '*', '*', '*'];
                    doc.styles.tableHeader = {
                        fontSize: 10,
                        bold: true,
                        alignment: 'center'
                    };
                    doc.styles.tableBodyOdd = {
                        fontSize: 9
                    };
                    doc.styles.tableBodyEven = {
                        fontSize: 9
                    };
                }
            },
            {
                extend: 'print',
                text: 'Imprimir',
                className: 'btn-custom' ,// Clase personalizada
                customize: function (win) {
                    $(win.document.body).css({
                        'font-size': '10pt',
                        'line-height': '1.5',
                        'margin': '0',
                        'padding': '0'
                    });
                    $(win.document.body).find('table')
                        .css('width', '100%')
                        .css('border-collapse', 'collapse')
                        .css('font-size', '10pt');
                    $(win.document.body).find('.dt-buttons').remove();
                }
            }
        ],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
        }
    });
    // Mover los botones a #buttons-container
    table.buttons().container().appendTo('#buttons-container');
     // Ajustar los estilos de los botones en móviles
     if ($(window).width() < 768) {
        $('#buttons-container').css({
            'flex-direction': 'column',
            'align-items': 'center'
        });
    }
});

$(document).ready(function() {
    var table = $('#myTable3').DataTable({
        responsive: true,
        dom: 'Bfrtip',
        order: [[0, 'desc']], // Ordenar por la primera columna, de forma ascendente
        buttons: [
            {
                extend: 'copy',
                text: 'Copiar',
                className: 'btn-custom' // Clase personalizada
            },
            {
                extend: 'csv',
                text: 'CSV',
                className: 'btn-custom' // Clase personalizada
            },
            {
                extend: 'excel',
                text: 'Excel',
                className: 'btn-custom' // Clase personalizada
            },
            {
                extend: 'pdf',
                text: 'PDF',
                orientation: 'landscape', // Horizontal
                pageSize: 'A4', // Tamaño de página
                customize: function (doc) {
                    doc.content[1].table.widths = ['*', '*', '*', '*', '*', '*', '*', '*', '*', '*', '*', '*'];
                    doc.styles.tableHeader = {
                        fontSize: 10,
                        bold: true,
                        alignment: 'center'
                    };
                    doc.styles.tableBodyOdd = {
                        fontSize: 9
                    };
                    doc.styles.tableBodyEven = {
                        fontSize: 9
                    };
                }
            },
            {
                extend: 'print',
                text: 'Imprimir',
                className: 'btn-custom' ,// Clase personalizada
                customize: function (win) {
                    $(win.document.body).css({
                        'font-size': '10pt',
                        'line-height': '1.5',
                        'margin': '0',
                        'padding': '0'
                    });
                    $(win.document.body).find('table')
                        .css('width', '100%')
                        .css('border-collapse', 'collapse')
                        .css('font-size', '10pt');
                    $(win.document.body).find('.dt-buttons').remove();
                }
            }
        ],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
        }
    });
    // Mover los botones a #buttons-container
    table.buttons().container().appendTo('#buttons-container');
     // Ajustar los estilos de los botones en móviles
     if ($(window).width() < 768) {
        $('#buttons-container').css({
            'flex-direction': 'column',
            'align-items': 'center'
        });
    }
});
function abrirModal(urlFormulario) {
    const iframe = document.getElementById('formularioIframe');
    const modal = document.getElementById('modalFormulario');

    // Mostrar el modal
    modal.style.display = 'flex';

    // Mostrar mensaje de carga (opcional)
    iframe.style.opacity = 0.3;
    iframe.src = ''; // Limpia antes de cargar uno nuevo

    // Verificamos si es un PDF
    if (urlFormulario.toLowerCase().endsWith('.pdf')) {
        iframe.src = urlFormulario;
    } else {
        // Supone que es un formulario
        iframe.src = urlFormulario + '?embedded=true';
    }

    // Espera a que cargue para mostrar bien
    iframe.onload = function () {
        iframe.style.opacity = 1;
    }
}

function cerrarModal() {
    const iframe = document.getElementById('formularioIframe');
    document.getElementById('modalFormulario').style.display = 'none';
    iframe.src = ''; // Limpia el iframe al cerrar
}

function cerrarModal() {
    document.getElementById('modalFormulario').style.display = 'none';
    document.getElementById('formularioIframe').src = ''; // limpia el iframe
}



function abrirImagen(src) {
    document.getElementById('modalImg').src = src;
    document.getElementById('modalImagen').style.display = 'flex';
}
function cerrarModalImg() {
    document.getElementById('modalImagen').style.display = 'none';
}



// Cambiar el estado de un llamado
$(document).ready(function() {
    $(document).on('change', '.cambiar-estado', function() {
        const select = $(this);
        const id = select.data('id');
        const nuevoEstado = select.val();
        $.ajax({
            url: '/llamado/cambiar-estado',
            method: 'POST',
            data: {
                idllamado: id,
                idtb_tipoestado: nuevoEstado,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                Swal.fire('Estado actualizado', '', 'success');
            },
            error: function(xhr) {
                Swal.fire('Error al actualizar', 'Revisá la consola', 'error');
                console.error(xhr.responseText);
            }
        });
    });
});




