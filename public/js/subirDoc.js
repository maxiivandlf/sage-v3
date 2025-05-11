// DropzoneJS Demo Code Start
Dropzone.autoDiscover = false;
var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Get the template HTML and remove it from the document
var previewNode = document.querySelector("#template");
if (previewNode) {
    previewNode.id = "";

    var previewTemplate = previewNode.parentNode.innerHTML;
    previewNode.parentNode.removeChild(previewNode);

    // Función para inicializar Dropzone después de cargar completamente el DOM
    document.addEventListener("DOMContentLoaded", function() {
        var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
            //url: "http://127.0.0.1:8000/upload", // la envio como absoluta de donde llamo local
            //url: "http://128.201.239.26/upload", // la envio como absoluta de donde llamo
            url: "http://sage.larioja.edu.ar/upload", // la envio como absoluta de donde llamo
            thumbnailWidth: 80,
            maxFilesize: 10, // en megabytes
            dictFileTooBig: "El archivo es demasiado grande ({{filesize}}MB). El tamaño máximo permitido es {{maxFilesize}}MB.",
            params: {
                _token: csrfToken,
                Agente: document.getElementById("idAgente").value,
                CueX: document.getElementById("CueX").value,
            },
            thumbnailHeight: 80,
            parallelUploads: 20,
            previewTemplate: previewTemplate,
            autoQueue: false, // Make sure the files aren't queued until manually added
            previewsContainer: "#previews", // Define the container to display the previews
            clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
        });

        myDropzone.on("addedfile", function(file) {
            // Hookup the start button
            var startButton = file.previewElement.querySelector(".start");
            if (startButton) {
                startButton.addEventListener("click", function() {
                    myDropzone.enqueueFile(file);
                });
            }
            console.log("presione start");
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
        // The "add files" button doesn't need to be setup because the config
        // `clickable` has already been specified.
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
                traerArchivos();
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
                        'Vacante o sin Agente, no puede Subir Documentos',
                        'error'
                    );
                }
            } else {
                console.error("Error en la respuesta JSON:", response);
            }
        });
    });
}
function traerArchivos(){
        // Obtén el valor de los elementos HTML usando jQuery
        var agente = $('#idAgente').val();
        var cueX = $('#CueX').val();
        
        // Genera el objeto de datos que contiene los parámetros
        var data = {
            _token: '{{ csrf_token() }}', // Obtén el token CSRF de Laravel
            Agente: agente,
            CueX: cueX
        };
        
        // Realiza la solicitud AJAX
        $.ajax({
            url: '/traerArchivos',
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
