@extends('layout.app')

@section('Titulo', 'Sage2.0 - Autenticacion')

@section('LinkCSS')
    <style>
        .label-container {
            display: flex;
            margin-bottom: 1px;
            margin-left: 35px;
        }

        .label-container label {
            margin-right: 8px;
        }

        .circle {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 1px solid rgb(12, 12, 12);
            display: inline-block;
        }

        .green {
            background-color: chartreuse;
            border-color: rgb(134, 136, 134);
        }

        .red {
            background-color: rgb(255, 120, 120);
            border-color: rgb(134, 136, 134);
        }
    </style>
@endsection
@section('ContenidoPrincipal')

    <body class="lock-screen" onload="startTime()">
        <div class="lock-wrapper">

            <div id="time" style="color:rgb(74, 71, 71)"></div>
            @php
                $activo = 'SI';
            @endphp
            <div class="row" style="position: absolute;top:10px; left:150px;z-index:0;">
                <marquee style="color:red;font-size:24px;">Control de IPE Activado para Control . Gracias
                </marquee>

            </div>
            <div class="card text-center bg-op-1 margin-bottom" style="width: 420px">
                <img src="{{ asset('img/seguridad.jpg') }}" alt="lock avatar" />
                <div class="card-body">
                    <h1><strong style="color:rgb(74, 71, 71)">Sistema <strong><strong
                                    style="color: rgb(102, 78, 78)">SAGE</strong>HOlaaaaaaaaaaaaaaaa</h1>
                    <span class="locked">{{ $mensajeError }}</span>
                    {{-- prueba local --}}
                    @if ($activo == 'NO')
                        <div class="row" style="height:150px; width:100%; margin: 0 auto; position:relative">
                            <img src="{{ asset('img/mantenimiento.gif') }}"
                                style="border: none; position: absolute; left:10px; top:10px">
                        </div>
                        {{-- <marquee style="color:red;font-size:24px;">Hoy 23 de Agosto de 20hs a 05 hs - Mantenimiento Preventivo y de Actualización - Disculpen las Molestias

              </marquee> --}}
                    @else
                        <form role="form" class="form-group" method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group">
                                <input type="email" placeholder="Email" id="email" name="email"
                                    class="form-control mb-2">
                                <input type="password" placeholder="Password" id="password" name="clave"
                                    class="form-control">
                            </div>

                            <div class="form-group">
                                <button class="btn btn-block btn-success btn-lg" type="submit">
                                    Iniciar Sesión <i class="fa fa-arrow-right"></i>
                                </button>
                            </div>

                        </form>

                        <p class="mt-2" style="text-align: left;">
                            <label>No tiene un Usuario Multicuenta?</label> <a href="{{ route('preregistroDocente') }}"
                                class="text-center">CLICK para Registrarlo</a>
                        </p>
                        {{-- <p class="mt-2" style="text-align: left;">
            <label>No tiene un Usuario Multicuenta?</label> <a href="{{route('registrarDocente')}}" class="text-center">CLICK para Registrarlo</a>
          </p> --}}
                        {{-- 
          <p class="mb-1" style="text-align: left;">
            <label>Olvido su clave, </label> <a href="{{route('recuperarClaveMulticuenta')}}">CLICK para recuperarla</a>
          </p> --}}
                    @endif

                </div>

            </div>

            <div class="card text-center bg-op-1 p-2" style="width: 420px">
                <div class="label-container">
                    <label>Modalidad PC de Escritorio</label>
                    Disponible <span class="circle green"> <i class="fas fa-check-circle" style="color: green;"
                            title="Online"></i> </span>
                </div>
                <div class="label-container">
                    <label>Modalidad Movil</label>
                    No Disponible <span class="circle red"><i class="fas fa-times-circle" style="color: red;"
                            title="Offline"></i> </span>
                </div>
            </div>
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
    @if (session('FinDeSession') == 'OK')
        <script>
            Swal.fire(
                'Alerta',
                'Tu sesión ha expirado debido a inactividad.',
                'question'
            )
        </script>
    @endif
    @if (session('mensajeError'))
        <script type="text/javascript">
            Swal.fire({
                icon: 'error',
                title: '¡Oops!',
                text: '{{ session('mensajeError') }}',
                confirmButtonText: 'Aceptar'
            });
        </script>
    @endif
    <script>
        function startTime() {
            var today = new Date();
            var h = today.getHours();
            var m = today.getMinutes();
            var s = today.getSeconds();
            // add a zero in front of numbers<10
            m = checkTime(m);
            s = checkTime(s);
            document.getElementById('time').innerHTML = h + ":" + m + ":" + s;
            t = setTimeout(function() {
                startTime()
            }, 500);
        }

        function checkTime(i) {
            if (i < 10) {
                i = "0" + i;
            }
            return i;
        }
    </script>
@endsection
