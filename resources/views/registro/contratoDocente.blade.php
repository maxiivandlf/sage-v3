@extends('layout.app')

@section('Titulo', 'Sage2.0 - Autenticacion')

@section('LinkCSS')
<script src="{{ asset('js/barraprogreso.js') }}"></script>
<style>
  .contrato p{
    text-align: left;
    font-size: 14px;
    font-weight: normal;
  }
  #redirectLink {
    display: none; /* Ocultar el enlace inicialmente */
    margin-top: 15px;
  }
  .checkbox-container {
    margin-top: 15px;
    font-size: 22px;
  }
 
  .checkbox-container input[type="checkbox"] {
    width: 22px; 
    height: 22px; 
    margin-right: 10px;
  }

  .checkbox-container label {
    vertical-align: middle; 
  }

</style>
@endsection

@section('ContenidoPrincipal')
<body class="lock-screen">
  <div class="" style="width: 100%;margin:0 auto">
    <div id="preloader">
      <!-- Imagen del preloader -->
      <div class="preloader-img" >
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
    <div class="card text-center bg-op-1" style="width: 70vw;margin:0 auto">
      <img src="{{ asset('img/seguridad.jpg');}}" alt="lock avatar" style="width: 70px;margin:0 auto"/>
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
        <div class="contrato">
          <h4>Política de Seguridad de Datos Personales</h4>
            <p>
              En el marco del uso de la página web del sistema administrativo de gestión educativa SAGE, 
              se establece la siguiente POLÍTICA DE SEGURIDAD DE DATOS PERSONALES, en cumplimiento de 
              la normativa vigente en la República Argentina.
            </p>
            <h4>Finalidad del Tratamiento de Datos Personales</h4>
            <p>
              Se recolectarán, almacenarán y tratarán datos personales de los usuarios, con el propósito 
              de crear y gestionar cuentas de usuario en la página web de SAGE. Estos datos se utilizarán 
              exclusivamente para fines de autenticación, seguridad y gestión administrativa.
            </p>
            <h4>Protección de los Datos Personales</h4>
            <p>
              Se garantizará la protección de los datos personales conforme a la normativa vigente, 
              implementando medidas de seguridad adecuadas para prevenir accesos no autorizados, pérdidas, 
              alteraciones o divulgaciones no consentidas.
            </p>
            <h4>Marco Legal Aplicable</h4>
            <p>
              El tratamiento de los datos personales se realizará en conformidad con la Ley Nº 25.326 de 
              Protección de los Datos Personales de la República Argentina y su normativa complementaria, 
              la Disposición Nº 11/2006 de la Dirección Nacional de Protección de Datos Personales y la Ley Nº 26.388 sobre delitos informáticos.
            </p>
            <h4>Derechos de los Titulares de los Datos</h4>
            <p>
              Los usuarios tienen derecho a acceder, rectificar, actualizar y suprimir sus datos personales, 
              conforme a lo dispuesto por la legislación vigente. Estos derechos podrán ejercerse ante el 
              responsable del tratamiento de datos de la página web de SAGE.
            </p>
            <h4>Información al Usuario</h4>
            <p>
              Se garantiza que los usuarios recibirán información suficiente sobre los propósitos del 
              tratamiento de sus datos, las medidas de seguridad implementadas y sus derechos en relación 
              con la protección de datos personales.
            </p>
            <h4>Responsable del Tratamiento</h4>
            <p>
              El responsable del tratamiento de los datos personales soporte técnico SAGE- Ministerio de Educación, 
              quien velará por el cumplimiento de esta política y de la normativa aplicable. Esta Política de 
              Seguridad de Datos Personales entra en vigencia a partir de la aceptación de creación de usuario en 
              sistema web y podrá ser actualizada conforme a cambios en la normativa o en los procedimientos internos de SAGE.
            </p>
            <div class="checkbox-container">
              <input type="checkbox" id="acceptPolicy" onchange="toggleLink()">
              <label for="acceptPolicy">He leído y acepto la Política de Seguridad de Datos Personales</label>
            </div>
            <a id="redirectLink" href="{{route('registrarDocente')}}" target="_SELF" class="btn btn-success btn-large">Redirigir a pagina de Registro</a>
        </div>
        <script>
          function toggleLink() {
            const checkbox = document.getElementById('acceptPolicy');
            const link = document.getElementById('redirectLink');
            link.style.display = checkbox.checked ? 'inline-block' : 'none';
          }
        </script>
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
        @if (session('EmailEnontrado')=='OK')
            <script>
            Swal.fire(
                'Alerta!!!',
                'El Correo electrónico ya existe, use otro',
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
                      var $feedback = $('.feedback');
                       // Revisa la respuesta completa del servidor
                      
                      // Mostrar el elemento <p> si estaba oculto
                        
                        if(response.msg==""){
                          $feedback.hide();
                        }else if(response.msg == "Disponible") {
                          $feedback.show();
                          $feedback.css("color", "green");
                          $feedback.text("Puedes usar el correo");
                          $('.registerbtn').show();
                          $('.bloqueado').hide();
                      } else {
                        $feedback.show();
                        $feedback.css("color", "red");
                        $feedback.text("Este correo ya fue registrado");
                        $('.registerbtn').hide();
                        $('.bloqueado').show();
                      }
                    }
                });
            });
        });
</script>

@endsection