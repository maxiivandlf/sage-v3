@extends('layout.app')

@section('Titulo', 'Sage2.0 - Nuevo Usuario ADMIN')

@section('LinkCSS')
 
@endsection

@section('ContenidoPrincipal')
<section id="container">
    <section id="main-content">
        <section class="content-wrapper">
                <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- Buscador Agente -->
                    <h4 class="text-center display-4">Panel de Usuarios y T&eacute;cnicos</h4>
                    <!-- Agregar Nuevo Agente -->
                   
                    <div class="row d-flex justify-content-center">
                        <!-- left column -->
                        <div class="col-md-10">
                            <div class="card card-lightblue">
                            <div class="card-header">
                                <h3 class="card-title">Lista de Usuarios en el Sistema(Admin y Técnicos)</h3>&nbsp; 
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>COD</th>
                                    <th>Institución</th>
                                    <th>Nivel</th>
                                    <th>CUE-BASE</th>
                                    <th>CUE-COMPLETO</th>
                                    <th>Domicilio</th>
                                    <th>Localidad</th>
                                    <th>Turno</th>
                                    <th>Editar Activo?</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ($Instituciones as $nag)
                                    <tr>
                                        <td>{{$nag->idInstitucionExtension}}</td>
                                        <td>{{$nag->Nombre_Institucion}}</td>
                                        <td>{{$nag->Nivel}}</td>
                                        <td>{{$nag->CUE}}</td>
                                        <td>{{$nag->CUECOMPLETO}}</td>
                                        
                                        <td>{{$nag->Domicilio_Institucion}}</td>
                                        <td class="text-center">{{$nag->Localidad}}</td>
                                        <td>{{$nag->TurnoEscuela}}</td>
                                       
                                       
                                        <td class="text-center">
                                            <div class="form-group">
                                                
                                                @if ($nag->PermiteEditarTodo ==1 )
                                                <div class="form-group clearfix">
                                                    <div class="icheck-danger d-inline">
                                                      <input type="radio" name="ed{{$nag->idInstitucionExtension}}"  id="radioed{{$nag->idInstitucionExtension}}">
                                                      <label for="radioed{{$nag->idInstitucionExtension}}">
                                                      </label>
                                                    </div>
                                                    <div class="icheck-success d-inline">
                                                      <input type="radio" name="ed{{$nag->idInstitucionExtension}}" checked="" id="radioede{{$nag->idInstitucionExtension}}">
                                                      <label for="radioede{{$nag->idInstitucionExtension}}">
                                                      </label>
                                                    </div>
                                                  </div>
                                                    
                                                @else
                                                    <div class="form-group clearfix">
                                                        <div class="icheck-danger d-inline">
                                                        <input type="radio" name="ed{{$nag->idInstitucionExtension}}"  checked=""id="radioed{{$nag->idInstitucionExtension}}">
                                                        <label for="radioed{{$nag->idInstitucionExtension}}">
                                                        </label>
                                                        </div>
                                                        <div class="icheck-success d-inline">
                                                        <input type="radio" name="ed{{$nag->idInstitucionExtension}}"  id="radioede{{$nag->idInstitucionExtension}}">
                                                        <label for="radioede{{$nag->idInstitucionExtension}}">
                                                        </label>
                                                        </div>
                                                    </div>
                                                @endif   
                                                        
                                                   
                                               
                                            </div>   
                                        </td>
                                        
                                    </tr> 
                                    @endforeach
                                
                                </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                            </div>
                        </div>
                    </div>    
            </section>
            <!-- /.content -->
        </section>
    </section>
</section>
@endsection

@section('Script')
    <script src="{{ asset('js/funcionesvarias.js') }}"></script>
        @if (session('ConfirmarNuevoUsuario')=='OK')
            <script>
            Swal.fire(
                'Registro guardado',
                'Se creo un nuevo registro de un Usuario',
                'success'
                    )
            </script>
        @endif
    <script>

    $('.formularioNuevoUsuario').submit(function(e){
        e.preventDefault();
        Swal.fire({
            title: '¿Está seguro de querer agregar el Usuario?',
            text: "Este cambio no puede ser borrado luego, y deberá ser validado por RRHH!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, crear el registro!'
          }).then((result) => {
            if (result.isConfirmed) {
              this.submit();
            }
          })
    })
    

    //control de reinicio
    /*
    $(document).ready(function() {
    // Manejar clic en botones de radio
    $('input[type="radio"]').on('change', function() {
        // Obtener el valor del radio seleccionado y el ID de la institución
        var valorSeleccionado = $(this).val();
        var idInstitucionExtension = $(this).attr('name').replace('r', '');
       console.log("se selecciono: "+ valorSeleccionado);
       console.log("inst:"+idInstitucionExtension);
        // Enviar la solicitud AJAX para actualizar el estado
            $.ajax({
                type: "POST",
                url: "/cambiarEstadoBorrado", // Ruta para actualizar el estado
                data: {
                    idInstitucionExtension: idInstitucionExtension,
                    valor: valorSeleccionado
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // Manejar la respuesta del servidor, por ejemplo, actualizar el estado en la UI
                    console.log('Estado actualizado con éxito:', response);
                },
                error: function(xhr, status, error) {
                    // Manejar errores
                    console.error('Error al actualizar el estado:', error);
                }
            });
        });
    });
*/
$(document).ready(function() {
    // Manejar clic en botones de radio
    $('input[type="radio"]').on('change', function() {
        // Obtener el valor del radio seleccionado y el ID de la institución
        var valorSeleccionado = $(this).val();
        var nameAttribute = $(this).attr('name');
        var idInstitucionExtension;
        //console.log("apretando el par: " + nameAttribute)
        // Diferenciar entre los radios de borrar y editar
        if (nameAttribute.startsWith('r')) {
            // Se seleccionó el par de borrar
            idInstitucionExtension = nameAttribute.replace('r', '');
            var url = "/cambiarEstadoBorrado";
            console.log("Se seleccionó el radio para borrar con ID:", idInstitucionExtension);
        } else if (nameAttribute.startsWith('ed')) {
            // Se seleccionó el par de editar
            idInstitucionExtension = nameAttribute.replace('ed', '');
            var url = "/cambiarEstadoEdicion";
            console.log("Se seleccionó el radio para editar con ID:", idInstitucionExtension);
        }

        // Enviar la solicitud AJAX para actualizar el estado
       $.ajax({
            type: "POST",
            url: url, // Ruta para actualizar el estado
            data: {
                idInstitucionExtension: idInstitucionExtension,
                valor: valorSeleccionado
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // Manejar la respuesta del servidor
                console.log('Estado actualizado con éxito:', response);
            },
            error: function(xhr, status, error) {
                // Manejar errores
                console.error('Error al actualizar el estado:', error);
            }
        });
    });
});



</script>

@endsection
