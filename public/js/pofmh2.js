function createNewRow(orden = '1', dni = 'Nuevo DNI') {
    var newRow = `
        <tr>
            <td></td> <!-- Aquí irá el ID devuelto por el servidor -->
            <td><input type="text" name="dato1[]" value="${orden}" class="orden-input"></td>
            <td><input type="text" name="dato2[]" value="${dni}" class="dni-input"></td>
            <td><input type="text" name="dato3[]" value="" class="apenom-input"></td>
            <td>
                <select class="form-control cargo-input" name="CargoSalarial">
                    ${generarOpcionesCargos([])} <!-- Puedes pasar un array de cargos inicial -->
                </select>
            </td>
            <td>
                <select class="form-control division-input" name="Division">
                    ${generarOpcionesDivisiones([])} <!-- Puedes pasar un array de divisiones inicial -->
                </select>
            </td>
            <td><input type="text" name="EspCur" value="" class="espcur-input"></td>
            <td>
                <select class="form-control turno-input" name="Turno">
                    ${generarOpcionesTurnos([])} <!-- Puedes pasar un array de turnos inicial -->
                </select>
            </td>
            <td><input type="text" name="Horas" value="" class="horas-input"></td>
            <td><input type="text" name="Origen" value="" class="origen-input"></td>
            <td>
                <select class="form-control sitrev-input" name="SitRev">
                    ${generarOpcionesSitRev([])} <!-- Puedes pasar un array de situaciones de revista -->
                </select>
            </td>
            <td><input type="date" name="AltaCargo" value="" class="fechaaltacargo-input"></td>
            <td><input type="date" name="Designado" value="" class="fechadesignado-input"></td>
            <td><input type="date" name="Condicion" value="" class="fechacondicion-input"></td>
            <td><input type="date" name="Desde" value="" class="fechadesde-input"></td>
            <td><input type="date" name="Hasta" value="" class="fechahasta-input"></td>
            <td>
                <select class="form-control motivos-input" name="Motivos">
                    ${generarOpcionesMotivos([])} <!-- Puedes pasar un array de motivos -->
                </select>
            </td>
            <td><textarea name="DatosPorCondicion" class="datosporcondicion-input"></textarea></td>
            <td><input type="text" name="Antiguedad" value="" class="antiguedad-input"></td>
            <td><input type="text" name="AgenteR" value="" class="agenter-input"></td>
            <td><textarea name="Novedades" class="novedades-input"></textarea></td>
            <td><input type="text" name="Asistencia" value="" class="asistencia-input"></td>
            <td><input type="text" name="AsistenciaJustificada" value="" class="asistenciajus-input"></td>
            <td><input type="text" name="AsistenciaInjustificada" value="" class="asistenciain-input"></td>
            <td><textarea name="Observaciones" class="observaciones-input"></textarea></td>
            <td>
                <span class="add-row"><i class="fas fa-plus-circle"></i></span>
                <span class="confirm-row"><i class="fas fa-check-circle"></i></span>
                <span class="delete-row"><i class="fas fa-eraser"></i></span>
            </td>
        </tr>`;

    // Añadir la nueva fila a la tabla y obtener referencia a la última fila creada
    var row = $('#POFMH tbody').append(newRow).find('tr:last');
    
    // Obtener el token CSRF
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Obtener los valores de valCUE y valTurno
    var cue = document.querySelector("#valCUE").value;
    var turno = document.querySelector("#valTurno").value;

    // Hacer la solicitud al servidor
    fetch('/crearRegistro', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ 
            nuevo: true,
            cue: cue,      // Valor de valCUE
            turno: turno   // Valor de valTurno
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Establecer el ID devuelto en la primera columna (#ID)
            row.find('td:first').text(data.id);
            
            // Actualizar el atributo data-id de los inputs
            row.find('input, select, textarea').attr('data-id', data.id);
        } else {
            alert('Error al confirmar el DNI: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Hubo un error al crear el registro.');
    });
}


// Función para generar las opciones de los cargos salariales
function generarOpcionesCargos(cargos, valorSeleccionado) {
    return cargos.map(cargo => `
        <option value="${cargo.idCargo}" ${cargo.idCargo === valorSeleccionado ? 'selected' : ''}>
            ${cargo.Cargo} <b>(${cargo.Codigo})</b>
        </option>
    `).join('');
}

// Función para generar las opciones de divisiones
function generarOpcionesDivisiones(divisiones, valorSeleccionado) {
    return divisiones.map(division => `
        <option value="${division.idDivision}" ${division.idDivision === valorSeleccionado ? 'selected' : ''}>
            ${division.Descripcion} - ${division.DescripcionCurso} - "${division.DescripcionDivision}" - ${division.DescripcionTurno}
        </option>
    `).join('');
}

// Función para generar las opciones de turnos
function generarOpcionesTurnos(turnos, valorSeleccionado) {
    return turnos.map(turno => `
        <option value="${turno.idTurnoUsuario}" ${turno.idTurnoUsuario === valorSeleccionado ? 'selected' : ''}>
            ${turno.Descripcion}
        </option>
    `).join('');
}

// Función para generar las opciones de situaciones de revista (SitRev)
function generarOpcionesSitRev(sitrev, valorSeleccionado) {
    return sitrev.map(sit => `
        <option value="${sit.idSituacionRevista}" ${sit.idSituacionRevista === valorSeleccionado ? 'selected' : ''}>
            ${sit.Descripcion}
        </option>
    `).join('');
}

// Función para generar las opciones de motivos
function generarOpcionesMotivos(motivos, valorSeleccionado) {
    return motivos.map(motivo => `
        <option value="${motivo.idMotivo}" ${motivo.idMotivo === valorSeleccionado ? 'selected' : ''}>
            <b>(${motivo.Codigo})</b>${motivo.Nombre_Licencia}
        </option>
    `).join('');
}
