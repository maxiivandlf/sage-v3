@extends('layout.app')

@section('Titulo', 'Sage2.0 - Autenticacion')
@section('LinkCSS')
<script src="{{ asset('js/barraprogreso.js') }}"></script>
@endsection
@section('ContenidoPrincipal')
<body class="lock-screen">
  <div class="lock-wrapper" style="width: 100%;margin:0 auto">
    <div id="preloader">
      <!-- Imagen del preloader -->
      <div class="preloader-img">
          <img src="{{ asset('img/logo_gob_lr.png') }}" alt="SAGE2.0" height="60" width="60">
      </div>
      <!-- Barra de progreso -->
      <div class="progress">
          <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
      </div>
    </div>
    <div id="time" style="color:rgb(74, 71, 71)"></div>
    @php
      $activo = "NO";
    @endphp
 <div class="row" style="position: absolute;top:-30px; left:50px;z-index:0;">
  {{-- <marquee style="color:red;font-size:30px;">Mes de MAYO, de 12:00 a 16:00hs - Mantenimiento Preventivo y de Actualización - Disculpen las Molestias</marquee> --}}

</div>
    <div class="card text-center bg-op-1" style="width: 500px">
      <img src="{{ asset('img/seguridad.jpg');}}" alt="lock avatar"/>
      <div class="card-body">
        <h1><strong style="color:rgb(74, 71, 71)">Sistema <strong><strong style="color: rgb(75, 15, 15)">SAGE</strong></h1>
        <span class="locked">{{$mensajeError}}</span>
        {{-- prueba local --}}
        @if ($activo=="SI")
         
              <div class="row" style="height:150px; width:100%; margin: 0 auto; position:relative">
                <img src="{{ asset('img/mantenimiento.gif');}}" style="border: none; position: absolute; left:10px; top:10px">
              </div>
              <marquee style="color:red;font-size:24px;">Mantenimiento preventivo hasta el d&iacute;a Miércoles 07AM Aproximadamente
              </marquee>
          
        @else
          <form role="form" class="formRecDoc" method="POST" action="{{route('formRecDoc')}}">
            @csrf
            <div class="card">
                <div class="card-body register-card-body">
                  <p class="login-box-msg">Recuperar clave Multi-Cuenta</p>
                  <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Documento de Identidad" name="documento" id="documento" autocomplete="off" required>
                    <div class="input-group-append">
                      <div class="input-group-text">
                        <span class="fas fa-id-card"></span>
                      </div>
                    </div>
                  </div>
                    
                    <div class="input-group mb-3">
                      <input type="email" class="form-control" placeholder="Email" name="email" id="email" autocomplete="off" required>
                      <div class="input-group-append">
                        <div class="input-group-text">
                          <span class="fas fa-envelope"></span>
                        </div>
                      </div>
                      
                    </div>
                    <p class="feedback" style="display: none"></p>
                    
                    <div class="row">
                      <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block recuperarbtn">Recuperar clave Multicuenta</button>
                      </div>
                      <!-- /.col -->
                    </div>
                  </form>
                  <div class="row" style="margin-top: 15px;margin-left:30px">
                    Si ya tienes una cuenta, <a href="{{route('Autenticar')}}" class="text-center">has click para regresar</a>
                  </div>
                  
                </div>
                <!-- /.form-box -->
              </div><!-- /.card -->
                  
          </form>
         
        @endif
       
      </div>
      
    </div>
    <br>
      {{-- <div class="card text-center bg-op-1">
        <p>Si no tiene asignado un Usuario, haga <a href="{{ route('pedirUsuario') }}">Click AQUI</a> para solicitarlo</p>
      </div> --}}
<marquee style="color:rgb(83, 235, 45);font-size:30px;">Estado: ONLINE</marquee>

  </div>
    @php
      echo Carbon\Carbon::now();
    @endphp
</body>
@endsection

@section('Script')
<script type="text/javascript">
  $(window).on('load', function(){
    $(".loader").fadeOut("slow")
  })
</script>
@if (session('FinDeSession')=='OK')
            <script>
            Swal.fire(
                'Alerta',
                'Tu sesión ha expirado debido a inactividad.',
                'question'
                    )
            </script>
        @endif

    <script src="{{ asset('js/funcionesvarias.js') }}"></script>
        @if (session('SeAnuloRecuperoMulticuenta')=='OK')
            <script>
            Swal.fire(
                'Alerta!!!',
                'La Combinación de Correo y DNI no se encontró en nuestros Registros',
                'error'
                    )
            </script>
        @endif
        @if (session('SeAnuloRecuperoMulticuenta-red')=='OK')
            <script>
            Swal.fire(
                'Alerta!!!',
                'Error de Red, no se pudo enviar el correo, pruebe en unos minutos',
                'error'
                    )
            </script>
        @endif
        @if (session('ConfirmadoRecuperarMultiCuenta')=='OK')
            <script>
            Swal.fire(
                'Felicidades',
                'Se envió un correo con informa.ion de su cuenta',
                'success'
                    )
            </script>
        @endif
    <script>

/*$('.formRecDoc').submit(function(e){
        e.preventDefault();
        Swal.fire({
            title: 'Esta seguro de querer recuperar el correo?',
            text: "Esta acción será observada luego por RRHH",
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
    })*/
   
    $(document).ready(function() {
    $('.formRecDoc').submit(function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: '¿Está seguro de querer recuperar el correo?',
            text: "Esta acción será observada luego por RRHH",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, crear el registro!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Mostrar el preloader
                $('#preloader').show();

                // Enviar el formulario
                $.ajax({
                    type: 'POST',
                    url: $(this).attr('action'), // Usa la acción del formulario
                    data: $(this).serialize(), // Serializa el formulario
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content")
                    },
                    success: function(response) {
                        // Ocultar el preloader
                        $('#preloader').hide();
                        
                        // Manejar la respuesta del servidor aquí
                        Swal.fire(
                            'Éxito!',
                            'El registro ha sido creado.',
                            'success'
                        );
                        // Redirigir o actualizar la página según sea necesario
                    },
                    error: function() {
                        // Ocultar el preloader en caso de error
                        $('#preloader').hide();
                        Swal.fire(
                            'Error!',
                            'Ocurrió un error al procesar la solicitud.',
                            'error'
                        );
                    }
                });
            }
        });
    });
});

  $(document).ready(function() {
            $('#email').on('input', function() {
                var email = $(this).val(); 
                
                $.ajax({
                    type: 'POST', // Método HTTP utilizado
                    url: '/buscar_usuario', 
                    data: { email: email}, 
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content")
                    },
                    dataType: 'json',
                    success: function(response) {
                      var $feedback = $('.feedback');
                       // Revisa la respuesta completa del servidor
                      
                      // Mostrar el elemento <p> si estaba oculto
                        $('.recuperarbtn').hide();

                        if(response.msg==""){
                          $feedback.hide();
                        }else if(response.msg != "Disponible") {
                          $feedback.show();
                          $feedback.css("color", "green");
                          $feedback.text("Correo Disponible");
                          $('.recuperarbtn').show();
                      } else {
                        $feedback.show();
                        $feedback.css("color", "red");
                        $feedback.text("Este correo es erróneo o no fue registrado");
                        $('.recuperarbtn').hide();
                      }
                    }
                });
            });
        });
</script>

@endsection