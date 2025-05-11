@extends('layout.app')

@section('Titulo', 'Sage2.0 - Movimientos')
@section('ContenidoPrincipal')
  
  <section id="container">
    <section id="main-content">
      <section class="content-wrapper">
        <div class="row">
          <div class="col-12">
            <!-- Custom Tabs -->
            <div class="card card-lightblue">
              <div class="card-header d-flex p-0">
                <h3 class="card-title p-3">Panel de Control POF - CUE / CUE COMPLETO: {{session('CUE')}} / {{session('CUECOMPLETO')}}</h3>
              </div><!-- /.card-header -->

              <div class="card-body">
                <div class="">
                  <div class="tab-pane active" id="tab_1">
                    <h3>Configurar Nuevo Agente / Docente:</h3>
                    <div class="container mt-3 d-block">
                      <form method="POST" action="{{ route('agregarAgenteEscuela') }}" class="formularioNuevoAgenteNodo" id="formularioNuevoAgenteNodoxxx">
                          @csrf
                        <div class="row">
                          <!--primera Card-->
                          <div class="ml-1">
                            <div class="card bg-Suplente">
                              <div class="card-title mt-4 d-flex justify-content-center">
                                <h6 style="font-weight: bold" id="DescripcionNombreAgente" class="mb-0">Docente: </h6>
                                <input type="hidden" name="idAgenteNuevoNodo" id="idAgenteNuevoNodo" value="">
                              </div>
                              <div class="card-body">
                                <p class="mb-0">Cargo/Función: <label for="cargo" id="DescripcionCargo"> Sin Selección </label>
                                <input type="hidden" id="CargoSal" name="CargoSal" value="">
                                </p>
                                
                                <p class="mb-0 mr-1">Sit.Rev:
                                <select class="form-control-sm border-0 mb-1" name="SituacionDeRevista" id="SituacionDeRevista">
                                  @foreach ($SituacionDeRevista as $sr)
                                    <option value='{{$sr->idSituacionRevista}}'>{{$sr->Descripcion}}</option>
                                  @endforeach
                                  </select>
                                </p>
                                
                                <p class="mb-0">Sala/División/Año: 
                                    <select class="form-control-sm border-0" name="idDivision" id="idDivision">
                                    @foreach($Divisiones as $key => $o)
                                        <option value="{{$o->idDivision}}">{{$o->DescripcionDivi}} - {{$o->DescripcionCurso}} - "{{$o->DescripcionDivision}}" - {{$o->DescripcionTurno}}</option>
                                    @endforeach
                                    </select>
                                    {{-- <i class="fa fa-plus-circle"> </i> --}}
                                </p>
                                <p class="mb-0" style="padding: 5px">Horas Ocupadas: <input type="number" id="cant_horas" class="form-control-sm border-0" name="cant_horas" style="width:50px" value=""></p>
                                <p class="mb-0"><label for="FechaAltaN1">Fecha de presentaci&oacute;n a la Instituci&oacute;n:</label> 
                                  <input type="date" id="FechaAltaN1" class="form-control-sm border-0" name="FechaAltaN1" style="width:125px" value="">
                                </p>
                                <p class="mb-0"><label for="FechaAltaN2">Fecha de alta en Juetaeno:</label> 
                                  <input type="date" id="FechaAltaN2" class="form-control-sm border-0" name="FechaAltaN2" style="width:125px" value="">
                                </p>

                                <div class="form-group">
                                  <label for="Observacion">Observación</label><br>
                                  <textarea class="form-control" name="Observaciones" rows="5" cols="100%"></textarea>
                                </div>
                              </div>
                              
                              <div class="card-footer d-flex justify-content-center">
                                {{-- <a type="button" href="#" class="btn mx-1" data-toggle="tooltip" data-placement="top" title="Licencia">
                                        <span class="material-symbols-outlined pt-1">medical_services</span>
                                      </a> --}}
                                      <a  href="#modalAgente" class="btn mx-1 " data-toggle="modal" data-placement="top" title="Agregar Docente"  data-target="#modalAgente">
                                        <span class="material-symbols-outlined pt-1" >group_add</span>
                                      </a>
                                      <a  href="#modalCargoFuncion" class="btn mx-1 " data-toggle="modal" data-placement="top" title="Cargo/Funcion"  data-target="#modalCargoFuncion">
                                        <span class="material-symbols-outlined pt-1" >library_add</span>
                                      </a>
                                     
                              
                                {{-- <a href="#" class="btn mx-1">
                                        <span class="material-symbols-outlined pt-1" data-toggle="modal" data-placement="top" title="Traslado/Afectación">transfer_within_a_station</span>
                                      </a> --}}
                                      <button type="submit" name="btnAgregarAgenteNuevo" class="btn mx-1">
                                        <span class="material-symbols-outlined pt-1" data-toggle="tooltip" data-placement="top" title="Confirmar">done</span>
                                      </button>
                                      
                                      {{-- <button type="submit" name="btnAgregarAgenteNuevo2"  class="btn mx-1">
                                        <span class="material-symbols-outlined pt-1" data-toggle="tooltip" data-placement="top" title="Confirmar">done</span>probar
                                      </button> --}}
                          
                                      {{-- <a href="{{route('agregaNodo',1)}}" class="btn mx-1">
                                        <span class="material-symbols-outlined pt-1" data-toggle="tooltip" data-placement="top" title="Vincular">compare_arrows</span>
                                      </a> --}}
                              </div>
                            </div>
                          </div>
                          <!--Fin primera Card-->
                        </div>
                      </form>
                    </div>

                   

                    <!-- /.modal -->
                    <div class="modal fade" id="modalCargoFuncion">
                      <div class="modal-dialog modal-lg">
                      <div class="modal-content">
                          <div class="modal-header">
                            <h4 class="modal-title">Buscar Cargo/Función</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <div class="card card-olive">
                              <div class="card-header">
                                <h3 class="card-title">Buscar Cargos / Funciones: </h3>
                                <input type="text" class="form-control" id="btCargos" onkeyup="getCargosFunciones()" placeholder="Ingrese Cargo/Funcion o Código Salarial" autocomplete="off">
                              </div>
                              <!-- /.card-header -->
                              <div class="card-body">
                                <table id="examplex" class="table table-bordered table-striped">
                                  <thead>
                                      <tr>
                                          <th>ID</th>
                                          <th>CÓDIGO</th>
                                          <th>DESCRIPCIÓN</th>
                                          <th>OPCIONES</th>
                                      </tr>
                                  </thead>
                                  <tbody id="contenidoCargosFunciones">
                                  
                                  </tbody>
                                </table>
                              </div>
                              <!-- /.card-body -->
                            </div>
                          </div>
                          <div class="modal-footer justify-content-end">
                              <button type="button" class="btn bg-olive"  data-dismiss="modal">Salir</button>
                          </div>
                      </div>
                      <!-- /.modal-content -->
                      </div>
                      <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->

                    <div class="modal fade" id="modalAgente">
                      <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                          <div class="modal-header">
                            <div class="modal-title">
                              <h4 class="modal-title">Buscar Agente</h4>
                              <h6 class="">CUE:<b>{{ session('CUECOMPLETO') }}</b></h6>
                            </div>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                          </div>

                          <div class="modal-body">
                            <div class="card card-olive">
                              <div class="card-header">
                                <div class="form-inline">
                                  <label class="col-auto col-form-label">Lista de Agentes: </label>
                                  <input type="text" autocomplete="off" class="form-control form-control-sm col-5" id="buscarAgente" placeholder="Ingrese DNI sin Puntos" value="">
                                  <button class="btn btn-sm btn-info form-control" type="button" id="traerAgentes" onclick="getAgentes()">Buscar
                                      <i class="fa fa-search ml-2"></i>
                                  </button>
                                </div>
                              </div>
                                <!-- /.card-header -->
                              <div class="card-body">
                                <table id="examplex" class="table table-bordered table-striped">
                                  <thead>
                                      <tr>
                                          <th>ID</th>
                                          <th>APELLIDO Y NOMBRE</th>
                                          <th>DNI</th>
                                          {{-- <th>INSTITUCIÓN</th>
                                          <th>CARGO</th> --}}
                                          <th>OPCIONES</th>
                                      </tr>
                                  </thead>
                                  <tbody id="contenidoAgentes">
                                  
                                  </tbody>
                                </table>
                              </div>
                                <!-- /.card-body -->
                            </div>
                          </div>
                          <div class="modal-footer justify-content-end">
                              <button type="button" class="btn bg-olive"  data-dismiss="modal">Salir</button>
                          </div>
                        </div>
                        <!-- /.modal-content -->
                      </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->
                  </div>
                  <!-- /.tab-pane -->

                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- ./card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </section>
    </section>
  </section>
@endsection

 
@section('Script')
    <script src="{{ asset('js/funcionesvarias.js') }}"></script>
   

     @if (session('ConfirmarNuevoAgenteNodo')=='OK')
        <script>
        Swal.fire(
            'Registro guardado',
            'Se creo un nuevo registro de un Agente',
            'success'
                )
        </script>
    @endif
    @if (session('ConfirmarNuevoNodo')=='OK')
        <script>
        Swal.fire(
            'Nodo Agregado',
            'Se creo un registro en Blanco, puede agregar los datos del Agente',
            'success'
                )
        </script>
    @endif
    @if (session('ConfirmarBorradoNodo')=='OK')
        <script>
        Swal.fire(
            'Nodo Borrado',
            'Se borro el nodo, no se puede recuperar',
            'success'
                )
        </script>
    @endif
    @if (session('ConfirmarBorradoNodoAnulado')=='OK')
        <script>
        Swal.fire(
            'Se cancelo la desvinculación, ese nodo esta relacionado con otro Agente',
            'Se cancelo el proceso',
            'error'
                )
        </script>
    @endif
<script>

    $('.formularioNuevoAgenteNodo').submit(function(e){
      if($("#idAgente").val()=="" ||
        $("#CargoSal").val()=="" ||
        $("#idDivision").val() == "" || $("#idDivision").val() == null ||
        $("#cant_horas").val()==""){
        console.log("error")
         e.preventDefault();
          Swal.fire(
            'Error',
            'No se pudo agregar, hay datos incompletos',
            'error'
                )
      }else{
        e.preventDefault();
        Swal.fire({
            title: 'Esta seguro de querer agregar el Agente?',
            text: "Esta acción será validada luego por RRHH",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, crear el registro!'
          }).then((result) => {
            if (result.isConfirmed) {
              this.submit();
              //prueba();
            }
          })
      }
    })
    

    $('.ConfirmarAgregarAgenteANodo').submit(function(e){
        e.preventDefault();
        Swal.fire({
            title: 'Esta seguro de querer agregar el Agente?',
            text: "Esta acción será validada luego por RRHH",
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
</script>

<script>
  function validarFecha(event) {
      var fechaInput = document.getElementById('FechaAltaN1').value;
      var regex = /^\d{4}-\d{2}-\d{2}$/;
      var valid = true;
      var elemento = document.getElementById('FechaAltaN1');

      if (!regex.test(fechaInput)) {
          valid = false;
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Formato de fecha inválido. Por favor, ingrese una fecha válida en el formato YYYY-MM-DD",
          }).then(function() {
              elemento.focus();
          });
          return false;
      }

      // Dividir la fecha en sus componentes
      var partesFecha = fechaInput.split("-");
      var año = parseInt(partesFecha[0]);
      var mes = parseInt(partesFecha[1]);
      var dia = parseInt(partesFecha[2]);

      // Verificar si el año es válido (entre 1000 y 9999)
      if (año < 1000 || año > 9999) {
          valid = false;
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Año inválido. Por favor, ingrese un año válido entre 1000 y 9999",
          }).then(function() {
              elemento.focus();
          });
          return false;
      }

      // Verificar si el mes es válido (entre 1 y 12)
      if (mes < 1 || mes > 12) {
          valid = false;
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Mes inválido. Por favor, ingrese un mes válido entre 01 y 12",
          }).then(function() {
              elemento.focus();
          });
          return false;
      }

      // Verificar si el día es válido
      var diasEnMes = new Date(año, mes, 0).getDate();
      if (dia < 1 || dia > diasEnMes) {
          valid = false;
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Día inválido para el mes y año especificados. Por favor, ingrese un día válido",
          }).then(function() {
              elemento.focus();
          });
          return false;
      }

      // Si pasa todas las validaciones, retorna true
      return true;
  }

  function validarFecha2(event) {
      var fechaInput = document.getElementById('FechaAltaN2').value;
      var regex = /^\d{4}-\d{2}-\d{2}$/;
      var valid = true;
      var elemento = document.getElementById('FechaAltaN2');

      if (!regex.test(fechaInput)) {
          valid = false;
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Formato de fecha inválido. Por favor, ingrese una fecha válida en el formato YYYY-MM-DD",
          }).then(function() {
              elemento.focus();
          });
          return false;
      }

      // Dividir la fecha en sus componentes
      var partesFecha = fechaInput.split("-");
      var año = parseInt(partesFecha[0]);
      var mes = parseInt(partesFecha[1]);
      var dia = parseInt(partesFecha[2]);

      // Verificar si el año es válido (entre 1000 y 9999)
      if (año < 1000 || año > 9999) {
          valid = false;
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Año inválido. Por favor, ingrese un año válido entre 1000 y 9999",
          }).then(function() {
              elemento.focus();
          });
          return false;
      }

      // Verificar si el mes es válido (entre 1 y 12)
      if (mes < 1 || mes > 12) {
          valid = false;
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Mes inválido. Por favor, ingrese un mes válido entre 01 y 12",
          }).then(function() {
              elemento.focus();
          });
          return false;
      }

      // Verificar si el día es válido
      var diasEnMes = new Date(año, mes, 0).getDate();
      if (dia < 1 || dia > diasEnMes) {
          valid = false;
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Día inválido para el mes y año especificados. Por favor, ingrese un día válido",
          }).then(function() {
              elemento.focus();
          });
          return false;
      }

      // Si pasa todas las validaciones, retorna true
      return true;
  }

  document.getElementById('FechaAltaN1').addEventListener('blur', validarFecha);
  document.getElementById('FechaAltaN2').addEventListener('blur', validarFecha2);
</script>


@endsection