// DropzoneJS Demo Code Start
Dropzone.autoDiscover = false;
var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Get the template HTML and remove it from the document
var previewNode = document.querySelector("#template2");
if (previewNode) {
    previewNode.id = "";

    var previewTemplate = previewNode.parentNode.innerHTML;
    previewNode.parentNode.removeChild(previewNode);

    // Función para inicializar Dropzone después de cargar completamente el DOM
    document.addEventListener("DOMContentLoaded", function() {
        var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
            url: "/uploadpofmhdecreto", // Usa una URL relativa
            thumbnailWidth: 80,
            maxFilesize: 10, // en megabytes
            dictFileTooBig: "El archivo es demasiado grande ({{filesize}}MB). El tamaño máximo permitido es {{maxFilesize}}MB.",
            // Elimina 'params' aquí
            thumbnailHeight: 80,
            parallelUploads: 20,
            previewTemplate: previewTemplate,
            autoQueue: false, // Make sure the files aren't queued until manually added
            previewsContainer: "#previews", // Define the container to display the previews
            clickable: ".fileinput-button" // Define el elemento que actúa como disparador para seleccionar archivos
        });

        // Agregar los parámetros antes de enviar el archivo
        myDropzone.on("sending", function(file, xhr, formData) {
            formData.append("_token", csrfToken);
            formData.append("CueX", document.getElementById("valCUE").value); // CueX desde 'novedad_cue'

            // Para depuración: verificar los valores que se están enviando
            console.log("Enviando archivo con CueX:", document.getElementById("valCUE").value);
        });

        myDropzone.on("addedfile", function(file) {
            // Hookup the start button
            var startButton = file.previewElement.querySelector(".start");
            if (startButton) {
                startButton.addEventListener("click", function() {
                    myDropzone.enqueueFile(file);
                });
            }
            console.log("Presionó start");
        });

        // Update the total progress bar
        myDropzone.on("totaluploadprogress", function(progress) {
            document.querySelector("#total-progress .progress-bar").style.width = progress + "%";
        });

        myDropzone.on("sending", function(file) {
            // Show the total progress bar when upload starts
            document.querySelector("#total-progress").style.opacity = "1";
            // And disable the start button
            var startButton = file.previewElement.querySelector(".start");
            if (startButton) {
                startButton.setAttribute("disabled", "disabled");
            }
        });

        // Hide the total progress bar when nothing's uploading anymore
        myDropzone.on("queuecomplete", function(progress) {
            document.querySelector("#total-progress").style.opacity = "0";
        });

        // Setup the buttons for all transfers
        var startButton = document.querySelector("#actions .start");
        if (startButton) {
            startButton.onclick = function() {
                myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED));
            };
        }

        var cancelButton = document.querySelector("#actions .cancel");
        if (cancelButton) {
            cancelButton.onclick = function() {
                myDropzone.removeAllFiles(true);
            };
        }

        // Manejar la respuesta JSON
        myDropzone.on("success", function(file, response) {
            if (response.success) {
                traerArchivos_origen();
                if (response.SubirDocExito) {
                    Swal.fire(
                        'Registro guardado',
                        'Archivo subido con éxito',
                        'success'
                    );
                }
                if (response.SubirDocFallo) {
                    Swal.fire(
                        'Registro guardado',
                        'No se encontró ningún archivo para subir',
                        'error'
                    );
                }
                if (response.SubirDocError) {
                    Swal.fire(
                        'Registro guardado',
                        'No puede Subir Documentos, consultar al Administrador',
                        'error'
                    );
                }
            } else {
                console.error("Error en la respuesta JSON:", response);
            }
        });

        // Manejar errores de carga
        myDropzone.on("error", function(file, errorMessage) {
            console.error("Error al subir el archivo:", errorMessage);
        });
    });
}

function traerArchivos_origen(){
        // Obtén el valor de los elementos HTML usando jQuery
        var cueX = $('#valCUE').val();
        
        // Genera el objeto de datos que contiene los parámetros
        var data = {
            _token: '{{ csrf_token() }}', // Obtén el token CSRF de Laravel
            CueX: cueX
        };
        
        // Realiza la solicitud AJAX
        $.ajax({
            url: '/traerArchivospofmhdecreto',
            type: 'GET',
            data: data, // Pasa los parámetros en el objeto de datos
            success: function(data) {
                // Actualiza el contenido del modal con los archivos recibidos en la respuesta
                $('#modalBody').html(data);
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
        //console.log("trayendo")
}

$(document).ready(function() {
    $('body').on('click', '.btn-delete-documento', function() {
        var documentoId = $(this).data('id');
        var row = $('#documento-' + documentoId);

        // Usar SweetAlert para la confirmación
        Swal.fire({
            title: '¿Estás seguro?',
            text: "No podrás revertir esto",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminarlo'
        }).then((result) => {
            if (result.isConfirmed) {
                // Si el usuario confirma, hacer la petición AJAX
                $.ajax({
                    url: '/borrarDocumentoAgentePof',
                    type: 'POST',
                    data: {
                        _token: csrfToken, // Usando el token CSRF obtenido
                        doc: documentoId
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            row.remove();
                            Swal.fire(
                                'Eliminado',
                                response.message,
                                'success'
                            );
                        } else {
                            Swal.fire(
                                'Error',
                                'No se pudo eliminar el documento.',
                                'error'
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        Swal.fire(
                            'Error',
                            'Ocurrió un problema al procesar la solicitud.',
                            'error'
                        );
                    }
                });
            }
        });
    });
});