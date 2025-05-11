$(document).ready(function() {
    $('#buscar_dni_cue').on('submit', function(event) {
        event.preventDefault(); // Prevenir el envío normal del formulario

        var dni = $('input[name="dni"]').val(); // Obtener el DNI del campo de entrada
        var token = $('input[name="_token"]').val(); // Obtener el token CSRF

        // Validar que el campo de DNI no esté vacío
        if (!dni) {
            alert('Por favor, ingresa un DNI.'); // Mensaje de alerta
            return; // Detener la ejecución si el DNI está vacío
        }

        $.ajax({
            url: '/buscar_dni_ajax', // Usa la ruta de Laravel
            type: 'POST',
            data: {
                _token: token, // Incluir el token CSRF
                dni: dni // Incluir el DNI
            },
            success: function(response) {
                console.log(response); // Ver respuesta completa en consola
                $('#example-pof tbody').empty(); // Limpiar la tabla antes de mostrar los nuevos datos

                if (response.length === 0) {
                    $('#example-pof tbody').append('<tr><td colspan="8" style="text-align:center">No se encontraron usuarios</td></tr>');
                } else {
                    $.each(response, function(index, usuario) {
                        var row = `
                            <tr>
                                <td>${usuario.idLiquidacion}</td>
                                <td>${usuario.Documento}</td>
                                <td>${usuario.ApeNom}</td>
                                <td>${usuario.Escuela}</td>
                                <td>${usuario.Descuento_Escuela}</td>
                                <td>${usuario.Nivel}</td>
                                <td>${usuario.Descuento_Plan}</td>
                                <td>${usuario.Antiguedad}</td>
                                <td>${usuario.Hora}</td>
                                <td>${usuario.Agrupamiento}</td>
                                <td>
                                    <button class="agregar-btn" data-usuario='${JSON.stringify(usuario)}'>Agregar</button>
                                </td>
                            </tr>
                        `;
                        $('#example-pof tbody').append(row); // Agregar solo a la tabla específica
                    });
                }
            },
            error: function(xhr, status, error) {
                // Si es 404, mostramos un mensaje personalizado
                if (xhr.status === 404) {
                    $('#example-pof tbody').empty(); // Limpiar la tabla
                    $('#example-pof tbody').append('<tr><td colspan="8" style="text-align:center">No se encontraron usuarios</td></tr>');
                    alert('No se encuentra cargado ese Agente con ese DNI.');
                } else {
                    console.error(xhr.responseText); // Mostrar el error en la consola
                    alert('Error al procesar la solicitud. Inténtalo nuevamente.');
                }
            }
        });
    });
});

$(document).on('click', '.agregar-btn', function() {
    var usuario = $(this).data('usuario'); // Obtener los datos del usuario
    var cueCompleto = $('#valCUE').text(); // Obtener el CUE desde el h3

    $.ajax({
        url: '/insertar_usuario', // Usa la ruta de Laravel para insertar
        type: 'POST',
        data: {
            _token: $('input[name="_token"]').val(), // Incluir el token CSRF
            usuario: JSON.stringify(usuario), // Incluir los datos del usuario como JSON
            cueCompleto: cueCompleto // Incluir el CUE
        },
        success: function(response) {
            if (response.status === 'success') {
                alert(response.message); // Mensaje de éxito

                // Actualiza la tabla con los nuevos datos
                var tbody = $('#POFMH tbody');
                tbody.empty(); // Limpia la tabla antes de llenarla

                // Llena la tabla con los datos recibidos
                response.data.forEach(function(fila) {
                    tbody.append(`
                        <tr data-id="${fila.idPofmh}">
                            <td>${fila.idPofmh}</td>
                            <td><input type="text" name="dato1[]" value="${fila.orden}" class="orden-input" data-id="${fila.idPofmh}" disabled></td>
                            <td><input type="text" name="dato2[]" value="${fila.Agente}" class="dni-input" id="dni-input-${fila.idPofmh}" data-id="${fila.idPofmh}" disabled></td>
                            <td><input type="text" name="dato3[]" value="${fila.ApeNom}" class="apenom-input" id="apenom-input-${fila.idPofmh}" data-id="${fila.idPofmh}" disabled></td>
                            <td>
                                <select class="form-control origen-input" name="Origen" data-id="${fila.idPofmh}" id="Origen">
                                    <!-- Aquí deberías agregar las opciones correspondientes -->
                                </select>
                            </td>
                            <td>
                                <select class="form-control sitrev-input" name="SitRev" id="SitRev" data-id="${fila.idPofmh}">
                                    <!-- Aquí deberías agregar las opciones correspondientes -->
                                </select>
                            </td>
                            <td><input type="text" name="Horas" value="${fila.Horas}" class="horas-input" data-id="${fila.idPofmh}" disabled></td>
                            <td><input type="text" name="Antiguedad" value="${fila.Antiguedad}" class="antiguedad-input" data-id="${fila.idPofmh}" disabled></td>
                            <td>
                                <select class="form-control cargo-input" name="CargoSalarial" data-id="${fila.idPofmh}" id="CargoSalarial">
                                    <!-- Aquí deberías agregar las opciones correspondientes -->
                                </select>
                            </td>
                            <td>
                                <span class="add-row"><i class="fas fa-plus-circle"></i></span>
                                <span class="confirm-row"><i class="fas fa-check-circle"></i></span>
                                <span class="delete-row"><i class="fas fa-eraser"></i></span>
                            </td>
                        </tr>
                    `);
                });

            } else {
                alert(response.message); // Mensaje de error
            }
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
            alert('Error al procesar la solicitud. Inténtalo nuevamente.');
        }
    });
});
