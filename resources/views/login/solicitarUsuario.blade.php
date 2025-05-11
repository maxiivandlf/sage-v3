@extends('layout.app')

@section('Titulo', 'Sage2.0 - Autenticacion')

@section('ContenidoPrincipal')
<body class="lock-screen" onload="startTime()">

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper" style="margin-left: 0px;margin-top:20px;">
          <!-- Content Header (Page header) -->
          <div class="content-header">
            <div class="container">
                <div class="alert alert-info alert-dismissible">
                    <h4><i class="icon fas fa-exclamation-triangle"></i> AVISO!</h4>
                    Para solicitar un usuario, por favor use el CUE sin puntos<br>
                    Una vez localizada la escuela, haga click en el icono de lupa a la derecha y complete los campos requeridos para gestionar el usuario
                    <br>
                    Ejemplo: <b>CUE, 40XXXXX</b>
                </div>
              
            </div><!-- /.container-fluid -->
          </div>
          <!-- /.content-header -->
      
          <!-- Main content -->
          <div class="content">
            <?php 
                //$mostrarError==true
                if($mensajeError != "")
                  echo'
                    <div class="alert alert-warning alert-dismissible">
                     
                      <h5><i class="icon fas fa-exclamation-triangle"></i> Alerta!</h5>
                      '.$mensajeError.'
                    </div>
                  ';
                ?>
            <div class="container  col-lg-12">
              <div class="row col-lg-12">
                
                  
                  <div class="card card-info  col-lg-6">
                    <a  href="/" class=" btn-outline-info"  title="Volver a Login " >
                        <span class="material-symbols-outlined">
                            reply_all
                        </span> VOLVER al Home
                    </a><br>
                    <div class="card-header">
                      <h3 class="card-title">Busqueda por CUE Base</h3>
                    </div>
                    <form role="form" class="form-group" method="POST" action="{{ route('buscarCUE') }}">
                        @csrf
                        <div class="card-body  col-lg-12">
                        <div class="row  col-lg-12">
                            
                            <div class="col-6">
                                <input type="text" class="form-control" placeholder="CUE(Solo Numeros)" name="cue">
                            </div>
                            <div class="col-6">
                                <input type="submit" class="form-control btn-success" value="Consultar CUE" name="bc">
                            </div>
                            
                            
                        </div>
                    <form>
                    </div>
                    </form>
                    <!-- /.card-body -->
                  </div>
              </div>
              <div class="row col-lg-12">
              <div class="card  col-lg-12">
                    <div class="card-header">
                      <h3 class="card-title">Lista de CUE encontradas</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <table id="example111" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                          <th>CUE</th>
                          <th>Nombre</th>
                          <th>Domicilio</th>
                          <th>Localidad</th>
                          <th>Turnos Asociados</th>
                          <th>Zona</th>
                          
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($infoCue as $i )
                                <tr>
                                    <td>{{$i->CUE}}</td>
                                    <td>{{$i->Nombre_Institucion}}</td>
                                    <td>{{$i->Domicilio_Institucion}}</td>
                                    <td>{{$i->Localidad}}</td>
                                    <td>{{$i->Turno}}</td>
                                    <td>{{$i->Zona}}</td>
                                    
                                </tr>
                            @endforeach
                         
                        </tbody>
                        <tfoot>
                          <tr>
                            <td colspan="6" style="text-align: right">
                              @if(isset($infoCue) && count($infoCue) > 0)
                              <a class="btn btn-success" href="{{route('cargarInfoUsuario',$infoCue[0]->CUE)}}">
                                Continuar <i class="fa  fa-thumbs-up"></i>
                            </a>
                              @endif
                              
                            </td>
                          </tr>
                        </tfoot>
                      </table>
                    </div>
                    <!-- /.card-body -->
                  </div>
              </div>
            </div><!-- /.container-fluid -->
          </div>
          <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
      

@endsection

@section('Script')
<script>
  function startTime()
  {
      var today=new Date();
      var h=today.getHours();
      var m=today.getMinutes();
      var s=today.getSeconds();
      // add a zero in front of numbers<10
      m=checkTime(m);
      s=checkTime(s);
      document.getElementById('time').innerHTML=h+":"+m+":"+s;
      t=setTimeout(function(){startTime()},500);
  }

  function checkTime(i)
  {
      if (i<10)
      {
          i="0" + i;
      }
      return i;
  }
</script>
@endsection