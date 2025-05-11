@extends('layout.app')

@section('Titulo', 'Sage2.0 - Altas')

@section('ContenidoPrincipal')
@php
    use Carbon\Carbon;
@endphp
<section id="container" >
    <section id="main-content">
        <section class="content-wrapper" >
            <!-- Inicio Selectores -->
            <div class="row"style="margin-left:10px">
                <div class="col-md-3">
      
                  <!-- Profile Image -->
                  <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                      <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle"
                             src="{{asset('img/'.$InfoUsuario->avatar)}}"
                             alt="User profile picture">
                      </div>
      
                      <h3 class="profile-username text-center">{{$InfoUsuario->Nombre}}</h3>
      
                      <p class="text-muted text-center">Usuario Multi-Cuenta</p>
      
                      <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                          <b>Registrado el dia </b> <a class="float-right">{{Carbon::parse($InfoUsuario->created_at)->format('d-m-Y H:i')}}</a>
                        </li>
                      </ul>
                    </div>
                    <!-- /.card-body -->
                  </div>
                  <!-- /.card -->
      
                  <!-- About Me Box -->
                  <div class="card card-primary">
                    <div class="card-header">
                      <h3 class="card-title">Acerca de Mi</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <strong><i class="fas fa-book mr-1"></i> Ecuación</strong>
      
                      <p class="text-muted">
                        No Disponible por ahora <span style="color:red">(Trabajando)</span>
                      </p>
      
                      <hr>
      
                      <strong><i class="fas fa-map-marker-alt mr-1"></i> Ubicación</strong>
      
                      <p class="text-muted">No Disponible por ahora <span style="color:red">(Trabajando)</span></p>
      
                      <hr>
      
                      <strong><i class="fas fa-pencil-alt mr-1"></i> Habilidades</strong>
      
                      <p class="text-muted">
                        <span class="tag tag-danger d-block">No Disponible por ahora <span style="color:red">(Trabajando)</span></span>
                      </p>
      
                      <hr>
      
                      <strong><i class="far fa-file-alt mr-1"></i> Información Extra</strong>
      
                      <p class="text-muted">No Disponible por ahora <span style="color:red">(Trabajando)</span></p>
                    </div>
                    <!-- /.card-body -->
                  </div>
                  <!-- /.card -->
                </div>
                <!-- /.col -->
                <div class="col-md-9">
                  <div class="card">
                    <div class="card-header p-2">
                      <ul class="nav nav-pills">
                        {{-- <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Activity</a></li>
                        <li class="nav-item"><a class="nav-link" href="#timeline" data-toggle="tab">Timeline</a></li> --}}
                        <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Mis Datos de Usuario MulitCuenta</a></li>
                      </ul>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                      <div class="tab-content">
                        <div class="tab-pane active" id="settings">
                            <form role="form" class="form-horizontal formPerfilDoc" method="POST" action="{{route('formPerfilDoc')}}" enctype="multipart/form-data">
                                @csrf
                            <div class="form-group row">
                              <label for="apellido" class="col-sm-2 col-form-label">Apellido</label>
                              <div class="col-sm-10">
                                <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Ingrese Apellido" value="{{$InfoUsuario->ape}}">
                              </div>
                            </div>
                            <div class="form-group row">
                                <label for="nombre" class="col-sm-2 col-form-label">Nombre</label>
                                <div class="col-sm-10">
                                  <input type="text" class="form-control" id="nombre" placeholder="Ingrese Nombre Completo" value="{{$InfoUsuario->nom}}" name="nombre">
                                </div>
                              </div>
                              
                            <div class="form-group row">
                              <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                              <div class="col-sm-10">
                                <input type="email" class="form-control" id="inputEmail" placeholder="Ingrese Correo Electrónico" name="email"  value="{{$InfoUsuario->email}}">
                              </div>
                            </div>
                            
                            <div class="form-group row">
                              <label for="clave" class="col-sm-2 col-form-label">Clave</label>
                              <div class="col-sm-10">
                                <input type="text" class="form-control" id="clave" placeholder="Ingrese Clave" name="clave"  value="{{$InfoUsuario->Clave}}">
                              </div>
                            </div>
                            
                            <div class="form-group row">
                              <div class="offset-sm-2 col-sm-10">
                                <input type="hidden" name="user" value="{{$InfoUsuario->idUsuario}}">
                                <button type="submit" class="btn btn-danger">Actualizar información</button>
                              </div>
                            </div>
                          </form>
                        </div>
                        <!-- /.tab-pane -->
                      </div>
                      <!-- /.tab-content -->
                    </div><!-- /.card-body -->
                  </div>
                  <!-- /.card -->
                </div>
                <!-- /.col -->
              </div>
            
        </section>
    </section>
</section>

@endsection

@section('Script')
<script type="text/javascript">
    $(window).on('load', function(){
      $(".loader").fadeOut("slow")
    })
</script>

<script src="{{ asset('js/funcionesvarias.js') }}"></script>
        @if (session('ConfirmarActualizarMiInfo')=='OK')
            <script>
            Swal.fire(
                'Registro guardado',
                'Se actualizó correctamente',
                'success'
                    )
            </script>
        @endif
        @if (session('ConfirmarActualizarMiInfoFail')=='OK')
            <script>
            Swal.fire(
                'Registro Error',
                'Controle la información, hubo datos faltantes',
                'error'
                    )
            </script>
        @endif
    <script>

    $('.formPerfilDoc').submit(function(e){
      if($('#apellido').val()==="" || $('#nombre').val()===""){
        e.preventDefault();
        Swal.fire(
                'Alerta',
                'Complete Apellido y Nombre para Continuar',
                'error'
                    )
      }else{
        e.preventDefault();
        Swal.fire({
            title: '¿Está seguro de querer actualizar la información personal?',
            text: "Recuerde colocar datos verdaderos",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, guardo el registro!'
          }).then((result) => {
            if (result.isConfirmed) {
              this.submit();
            }
          })
      }
        
    })
    
    
</script>

@endsection