@extends('layout.app')

@section('Titulo', 'Sage2.0 - ChatBlog')
@section('ContenidoPrincipal')
  
  <section id="container">
    <section id="main-content">
      <section class="content-wrapper">
        <div class="row">
           <!-- Direct Chat -->
        <h4 class="col-md-12">Chat Blog Institucional - Estado : <small class="badge badge-danger"><i class="far fa-clock"></i> Fuera de Linea</small></h4> 
        
          <div class="col-md-12">
            <!-- DIRECT CHAT PRIMARY -->
            <div class="card card-primary card-outline direct-chat direct-chat-primary" style="height: 800px;">
              <div class="card-header">
                <h3 class="card-title">Bandeja de Mensajes</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body" >
                <!-- Conversations are loaded here -->
                <div class="direct-chat-messages" style="height: 95%;">
                  <!-- Message. Default to the left -->
                  <div class="direct-chat-msg">
                    <div class="direct-chat-infos clearfix">
                      <span class="direct-chat-name float-left">Administrador</span>
                      <span class="direct-chat-timestamp float-right">21 de Marzo - 11:50 PM</span>
                    </div>
                    <!-- /.direct-chat-infos -->
                    <img class="direct-chat-img" src="../dist/img/user1-128x128.jpg" alt="Admin">
                    <!-- /.direct-chat-img -->
                    <div class="direct-chat-text">
                        <div class="container">
                            <div class="text-center my-4">
                              <h3 class="mb-4">🌟 ¡Bienvenidos! - Zona A/B/C 🌟</h3>
                              <p>Es un placer darles la bienvenida a este chatBlog. Espero que este espacio sea un lugar donde podamos compartir ideas, conversar y apoyarnos mutuamente en el uso del sistemas SAGE.</p>
                              <p>¿Cuál es tu duda o consulta?</p>
                              <p>Recuerden ser respetuosos/as y considerados/as entre sí. Estamos aquí para construir una comunidad positiva y enriquecedora.</p>
                              <p>¡Bienvenidos/as nuevamente!</p>
                            </div>
                            <div class="text-right">
                              <p class="font-italic">Saludos cordiales,</p>
                              <p class="font-italic">Administrador / Referentess AQUÍ</p>
                            </div>
                          </div>
                    </div>
                    <!-- /.direct-chat-text -->
                  </div>
                  <!-- /.direct-chat-msg -->

                  <!-- Message to the right -->
                  <div class="direct-chat-msg right">
                    <div class="direct-chat-infos clearfix">
                      <span class="direct-chat-name float-right">Jardin N&deg;75</span>
                      <span class="direct-chat-timestamp float-left">22 de Marzo - 11:30AM</span>
                    </div>
                    <!-- /.direct-chat-infos -->
                    <img class="direct-chat-img" src="../dist/img/user3-128x128.jpg" alt="Institucion">
                    <!-- /.direct-chat-img -->
                    <div class="direct-chat-text">
                        <p>Querido equipo,</p>
                        <p>Estoy emocionada de implementar el sistema SAGE en nuestra escuela para mejorar nuestra eficiencia y administración. Sin embargo, tengo algunas dudas sobre su uso.</p>

                        <ol>
                            <li>¿Cómo podemos asegurarnos de que todo el personal esté debidamente capacitado para utilizar todas las funciones del sistema SAGE de manera efectiva?</li>
                            <li>¿Cuáles son las medidas de seguridad implementadas en el sistema SAGE para proteger la privacidad y seguridad de los datos de nuestros estudiantes y personal?</li>
                            <li>¿Cómo podemos garantizar que el sistema SAGE se integre sin problemas con nuestros sistemas y procesos existentes en la escuela?</li>
                        </ol>

                        <p>Agradezco cualquier orientación adicional sobre cómo podemos maximizar el potencial del sistema SAGE para beneficiar a nuestra comunidad escolar.</p>

                        <p>Saludos cordiales,<br>Directora Roxana, Diaz</p>
                    </div>
                    <!-- /.direct-chat-text -->
                  </div>
                  <!-- /.direct-chat-msg -->
                </div>
                <!--/.direct-chat-messages-->

                <!-- Contacts are loaded here -->
                <div class="direct-chat-contacts">
                  <ul class="contacts-list">
                    <li>
                      <a href="#">
                        <img class="contacts-list-img" src="../dist/img/user1-128x128.jpg" alt="User Avatar">

                        <div class="contacts-list-info">
                          <span class="contacts-list-name">
                            Count Dracula
                            <small class="contacts-list-date float-right">2/28/2015</small>
                          </span>
                          <span class="contacts-list-msg">How have you been? I was...</span>
                        </div>
                        <!-- /.contacts-list-info -->
                      </a>
                    </li>
                    <!-- End Contact Item -->
                  </ul>
                  <!-- /.contatcts-list -->
                </div>
                <!-- /.direct-chat-pane -->
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <form action="#" method="post">
                  <div class="input-group">
                    <input type="text" name="message" placeholder="Escriba su mensaje" class="form-control">
                    <span class="input-group-append">
                      <button type="submit" class="btn btn-primary">Enviar</button>
                    </span>
                  </div>
                </form>
              </div>
              <!-- /.card-footer-->
            </div>
            <!--/.direct-chat -->
          </div>
          <!-- /.col -->
   
        <!-- /.row -->
        </div>
        <!-- /.row -->
      </section>
    </section>
  </section>
@endsection

 


@section('Script')
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
    @if (session('ConfirmarNuevoNodoDerechoFallo')=='OK')
        <script>
        Swal.fire(
            'Nodo Agregado',
            'No se permite crear un registro en blanco entre dos agentes',
            'error'
                )
        </script>
    @endif
    @if (session('ConfirmarBorradoNodo')=='OK')
        <script>
        Swal.fire(
            'Nodo Borrado',
            'Se borró el nodo, no se puede recuperar',
            'success'
                )
        </script>
    @endif
    @if (session('ConfirmarBorradoNodoAnulado')=='OK')
        <script>
        Swal.fire(
            'Se canceló la desvinculacion, ese nodo esta relacionado con otro Agente',
            'Se canceló el proceso',
            'error'
                )
        </script>
    @endif
    @if (session('ConfirmarLimpieza')=='OK')
        <script>
        Swal.fire(
            'Aviso',
            'Se Eliminó todo el contenido de Bloques y Novedades',
            'success'
                )
        </script>
    @endif
    @if (session('ConfirmarLimpiezaError')=='OK')
        <script>
        Swal.fire(
            'Aviso',
            'Esta función solo esta disponible para CUE de Prueba',
            'error'
                )
        </script>
    @endif
<script>

    $('.formularioNuevoAgenteNodo').submit(function(e){
      if($("#idAgente").val()=="" ||
        $("#CargoSal").val()=="" ||
        //$("#idEspCur").val()=="" ||
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
            title: '¿Está seguro de querer agregar el Agente?',
            text: "Prueba por ahora",
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
            title: '¿Está seguro de querer agregar el Agente?',
            text: "Prueba por ahora",
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
@endsection


