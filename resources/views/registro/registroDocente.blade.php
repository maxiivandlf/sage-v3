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
<div class="alert alert-warning alert-dismissible" style="width: 700px;margin-left: -100px; margin-bottom: 30px;">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
  <h5><i class="icon fas fa-exclamation-triangle"></i> Atención</h5>
  En esta primera etapa de registro, no se enviará un correo de confirmación al completar la inscripción.
Si necesitas recuperar información o corregir algún dato proporcionado, por favor, comunícate directamente con los agentes de SAGE para recibir asistencia.<br>
  <i class="fas fa-phone"></i> 3804555564 / 3804110354<br>
  <i class="fas fa-envelope"></i> sage@educacionlarioja.com <br>
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
          <form role="form" class="formRegDoc" method="POST" action="{{route('formRegDoc')}}">
            @csrf
            <div class="card">
                <div class="card-body register-card-body">
                  <p class="login-box-msg">Registro Multi-Cuenta</p>
                  <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Documento de Identidad" name="documento" id="documento" autocomplete="off" required>
                   
                    <div class="input-group-append">
                      
                      <div class="input-group-text">
                        <span class="fas fa-id-card"></span>
                      </div>
                    </div>
                    
                  </div>
                 <p class="feedback" style="display: block; font-size: 14px;margin-bottom:24px;height:20px"></p>
                    <div class="input-group mb-3">
                      <input type="text" class="form-control" placeholder="Apellido" name="apellido" autocomplete="off" required>
                      <div class="input-group-append">
                        <div class="input-group-text">
                          <span class="fas fa-user"></span>
                        </div>
                      </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Nombre" name="nombre"  autocomplete="off" required>
                        <div class="input-group-append">
                          <div class="input-group-text">
                            <span class="fas fa-user"></span>
                          </div>
                        </div>
                      </div>
                    <div class="input-group mb-3">
                      <input type="email" class="form-control" placeholder="Email" name="email" id="email" autocomplete="new-email" required>
                      <div class="input-group-append">
                        <div class="input-group-text">
                          <span class="fas fa-envelope"></span>
                        </div>
                      </div>
                      
                    </div>
                    <p class="feedback2" style="display: none"></p>
                    <div class="input-group mb-3">
                      <input type="password" class="form-control" placeholder="Password" name="password1" id="password1" autocomplete="new-password" required>
                      <div class="input-group-append">
                        <div class="input-group-text">
                          <span class="fas fa-eye-slash toggle-password" data-target="#password1"></span>
                        </div>
                      </div>
                    </div>
                    <div class="input-group mb-3">
                      <input type="password" class="form-control" placeholder="Retype password" name="password2" id="password2" autocomplete="new-password"  required>
                      <div class="input-group-append">
                        <div class="input-group-text">
                          <span class="fas fa-eye-slash toggle-password" data-target="#password2"></span>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block registerbtn" style="display:none">Registrarme al Sistema SAGE</button>
                        <button type="button"  class="btn btn-secondary btn-block bloqueado" disabled>Debe estar habilitado para Registrar</button>
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
        @if (session('DatosEncontrados')=='OK')
            <script>
            Swal.fire(
                'Alerta!!!',
                'El Correo o el DNI ya existe, consulte con los Agentes del Sistema para recuperar su cuenta',
                'error'
                    )
            </script>
        @endif
        @if (session('ConfirmadoRegistroMultiCuenta')=='OK')
            <script>
            Swal.fire(
                'Felicidades',
                'Su cuenta se registro con éxito',
                'success'
                    )
            </script>
        @endif
    <script>

$('.formRegDoc').submit(function(e){
      if($("#password1").val()!=$("#password2").val()){
         e.preventDefault();
          Swal.fire(
            'Error',
            'Las claves no son iguales',
            'error'
                )
      }else{
        e.preventDefault();
        Swal.fire({
            title: 'Esta seguro de querer registrar el Agente?',
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
   

  $(document).ready(function() {
            $('#email').on('input', function() {
                var email = $(this).val(); 
                $('.registerbtn').hide();
                        $('.bloqueado').show();
                $.ajax({
                    type: 'POST', // Método HTTP utilizado
                    url: '/buscar_usuario', 
                    data: { email: email}, 
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content")
                    },
                    dataType: 'json',
                    success: function(response) {
                      var $feedback2 = $('.feedback2');
                       // Revisa la respuesta completa del servidor
                      
                      // Mostrar el elemento <p> si estaba oculto
                        
                        if(response.msg==""){
                          $feedback2.hide();
                        }else if(response.msg == "Disponible") {
                          $feedback2.show();
                          $feedback2.css("color", "green");
                          $feedback2.text("Puedes usar el correo");
                          $('.registerbtn').show();
                          $('.bloqueado').hide();
                      } else {
                        $feedback2.show();
                        $feedback2.css("color", "red");
                        $feedback2.text("Este correo ya fue registrado");
                        $('.registerbtn').hide();
                        $('.bloqueado').show();
                      }
                    }
                });
            });
        });
</script>
<script>
  document.getElementById('documento').addEventListener('blur', function () {
    const documento = this.value.trim();

    if (documento.length > 0) {
      fetch('{{ route('validar.dni') }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ documento })
      })
        .then(response => response.json())
        .then(data => {
          const feedback = document.querySelector('.feedback');
          const registerBtn = document.querySelector('.registerbtn');
          const bloqueadoBtn = document.querySelector('.bloqueado');

          if (data.existe) {
            feedback.textContent = 'El DNI ya fue registrado, consulte con un Agente para recuperarlo';
            feedback.style.color = 'red';
            feedback.style.display = 'block';
            registerBtn.style.display = 'none';
            bloqueadoBtn.style.display = 'block';
          } else {
            feedback.textContent = 'El DNI está disponible, puede ser usado';
            feedback.style.color = 'green';
            feedback.style.display = 'block';
            registerBtn.style.display = 'block';
            bloqueadoBtn.style.display = 'none';
          }
        })
        .catch(error => console.error('Error:', error));
    }
  });
</script>
<script>
  document.querySelectorAll('.toggle-password').forEach(toggle => {
    toggle.addEventListener('click', function () {
      const target = document.querySelector(this.dataset.target);
      const type = target.getAttribute('type') === 'password' ? 'text' : 'password';
      target.setAttribute('type', type);

      // Cambiar el icono del ojo
      this.classList.toggle('fa-eye');
      this.classList.toggle('fa-eye-slash');
    });
  });
</script>
<script>
  document.getElementById('documento').addEventListener('input', function (e) {
      // Remover puntos del valor ingresado
      this.value = this.value.replace(/[^0-9]/g, '');
    });
</script>
@endsection