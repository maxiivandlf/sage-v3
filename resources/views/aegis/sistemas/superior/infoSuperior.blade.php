@extends('layout.app')

@section('Titulo', 'Sage2.0 - Legajo Docente y F2')

@section('ContenidoPrincipal')


@section('LinkCSS')
  <link rel="stylesheet" href="{{ asset('css/superior/infoSuperior.css') }}">
  <link rel="stylesheet" href="{{ asset('css/pofmh.css') }}">

    <!-- Bootstrap CSS -->
  <link href="{{ asset('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet') }}">
  <!-- DataTables CSS -->
  <link rel="stylesheet" href="{{ asset('https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css') }}">
  <link rel="stylesheet" href="{{ asset('https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css') }}">
  <link rel="stylesheet" href="{{ asset('https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css') }}">
@endsection
<section id="container" >
     <section id="main-content">
         <section class="content-wrapper">
            <!-- Mensaje ALERTA -->
            <div class="row bg-success" style="text-align: center; display: flex; justify-content: center;">
              
                <img src="{{asset('img/superior/imgllamado/cabecera.png')}}" alt="Photo 1" style="width: 70vw; height: 8vw;">

            </div>
            <div class="col-md-12">
                  @php                             
                  @endphp
                  @if ($Agente==null)                          
                      <div class="alert alert-warning alert-dismissible text-danger">
                         <h4><i class="icon fas fa-exclamation-triangle"></i> AVISO!</h4>
                          <h4 class="text-center">"No se encontró información del DNI en el sistema SAGE - Nivel Superior. Para habilitar su DNI, por favor comuníquese con la Comisión de Nivel Superior a través de los siguientes medios:
                           <br/>
                           <i class="fas fa-phone"></i> 3804555564 / 3804110354<br/>
                           <i class="fas fa-envelope"></i> comisionsuperior@educacionlarioja.com "</h4>
                      </div>
                  @else                
                    <section class="content">  
                      <div class="container mt-5">
                        <div class="card collapsed-card">
                          <div class="card-header">      
                            <h2>CONVOCATORIA DOCENTE</h2>      
                            <!-- Tabla de usuarios -->
                            <table id="myTable" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Imagen</th>
                                        <th>ZONA</th>
                                        <th>INSTITUCIÓN</th>
                                        <th>UNIDAD/ESPACIO CURRICULAR/CARGO</th>
                                        <th>HORAS CATEDRAS</th>
                                        <th>HORARIO</th>
                                        <th>PERFIL</th>
                                        <th>INICIO</th>
                                        <th>CIERRE</th>
                                        <th>INSCRIPTOS</th>
                                        <th>ESTADO</th>                                       
                                    </tr>
                                </thead>
                                <tbody>                                
                                       <tr>
                                          <td>                   
                                            <img src="{{asset('img/superior/imgllamado/3.png')}}" alt="Imagen" width="50">
                                          </td>
                                            <td>I</td>
                                            <td>ISFD-Famatina Profesorado de Educación Física</td>
                                            <td>Practicas Gimnasticas Y Su Enseñanza En El Nivel Inicial Y Primario </td>
                                            <td>10</td>
                                            <td>  1er Cuatrimestre
                                              Mier. 16 a 18hs
                                              Vier. 16 a 18hs
                                              2do Cuatrimestre
                                              Mier. 16 a 18hs
                                              Vier. 16 a 18hs
                                            </td>
                                            <td> Prof. Para Nivel Superior Y/O Lic. En Educación Física</td>
                                            <td> 14/02/2025 08:00hs</td>
                                            <td>  19/02/2025  12:00hs</td>
                                            <td>
                                              <div class="progress progress-sm">
                                                <div class="progress-bar bg-green" role="progressbar" aria-valuenow="57" aria-valuemin="0" aria-valuemax="100" style="width: 57%">
                                                </div>
                                              </div>
                                              <small> 10 Inscriptos</small>
                                            </td>
                                            <td> <span class="badge text-success">ABIERTO</span>                       
                                               <a  class="badge badge-info" href="{{asset('//forms.gle/3m3L4NL4D1Tm2YTbA')}}" target="_blank">                               
                                                    INSCRIBIRME
                                               </a>
                                            </td>                                            
                                        </tr>
                                        <tr>
                                          <td>                   
                                            <img src="{{asset('img/superior/imgllamado/3.png')}}" alt="Imagen" width="50">
                                          </td>
                                          <td>II</td>
                                          <td>ISFD-Famatina Profesorado de Educación Física </td>
                                          <td>Practicas Gimnasticas Y Su Enseñanza En El Nivel Inicial Y Primario </td>
                                          <td>10</td>
                                          <td>  1er Cuatrimestre
                                            Mier. 16 a 18hs
                                            Vier. 16 a 18hs
                                            2do Cuatrimestre
                                            Mier. 16 a 18hs
                                            Vier. 16 a 18hs
                                          </td>
                                          <td> Prof. Para Nivel Superior Y/O Lic. En Educación Física</td>
                                          <td> 14/02/2025 08:00hs </td>
                                          <td>  19/02/2025  12:00hs  </td>
                                          <td>
                                            <div class="progress progress-sm">
                                              <div class="progress-bar bg-green" role="progressbar" aria-valuenow="57" aria-valuemin="0" aria-valuemax="100" style="width: 57%">
                                              </div>
                                            </div>
                                            <small> 10 Inscriptos</small>
                                          </td>
                                        
                                          <td>
                                            <span class="badge text-success">ABIERTO</span>                       
                                        
                                            <a  class="badge badge-info" href="{{asset('//forms.gle/3m3L4NL4D1Tm2YTbA')}}" target="_blank">                               
                                                  INSCRIBIRME
                                            </a>
                                          </td>
                                        
                                      </tr>
                                </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </section>                
                    <!-- Inicio Selectores IMAGENES-->
                    <div class="row mt-4">
                      <div class="col-sm-4 img-container">
                        <div class="position-relative">
                          <img src="{{asset('img/superior/imgllamado/1.png')}}" alt="Photo 1" class="img-fluid">
                        
                          <div class="ribbon bg-success text-lg" style="text-align: center;">
                              <i class="fa fa-regular fa-link"></i>
                              <a href="{{asset('//forms.gle/3m3L4NL4D1Tm2YTbA')}}" target="_blank"> Más Información</a>
                          </div>
                        </div>
                        <hr/>
                    </div>   
                    <div class="col-sm-4">
                        <div class="position-relative">
                          <img src="{{asset('img/superior/imgllamado/foto-llamado.png')}}" alt="Photo 2" class="img-fluid">
                          <div class="ribbon-wrapper ribbon-lg">
                            <div class="ribbon bg-warning text-lg">
                              ZONA II
                            </div>
                          </div>
                          <div class="ribbon bg-success text-lg" style="text-align: center;">
                              <i class="fa fa-regular fa-link"></i>
                              <a href="{{asset('//forms.gle/3m3L4NL4D1Tm2YTbA')}}" target="_blank">Más Información</a>
                          </div>
                        </div>
                        <hr/>
                    </div>
                    <div class="col-sm-4">
                      <div class="position-relative" style="min-height: 180px;">
                        <img src="{{asset('img/superior/imgllamado/2.jpg')}}" alt="Photo 3" class="img-fluid">
                        <div class="ribbon-wrapper ribbon-lg">
                          <div class="ribbon bg-success text-lg">
                            ZONA I
                          </div>
                        </div>
                        <div class="ribbon bg-danger text-lg" style="text-align: center;">
                            INACTIVO
                        </div>
                      </div>
                        <hr/>
                    </div>
                    <div class="col-sm-4">
                        <div class="position-relative" style="min-height: 180px;">
                          <img src="{{asset('img/superior/imgllamado/3.png')}}" alt="Photo 3" class="img-fluid">
                          <div class="ribbon-wrapper ribbon-lg">
                            <div class="ribbon bg-pink text-lg">
                              ZONA III
                            </div>
                          </div>
                          <div class="ribbon bg-success text-lg" style="text-align: center; min-height: 10px;">
                              <i class="fa fa-regular fa-link"></i>
                              <a href="{{asset('//forms.gle/3m3L4NL4D1Tm2YTbA')}}" target="_blank">Más Información</a>
                          </div>
                        </div>
                        <hr/>
                    </div>
                    <div class="col-sm-4">
                        <div class="position-relative" style="min-height: 180px;">
                          <img src="{{asset('img/superior/imgllamado/1.png')}}" alt="Photo 3" class="img-fluid">
                          <div class="ribbon-wrapper ribbon-lg">
                            <div class="ribbon bg-blue text-lg">
                              ZONA IV
                            </div>
                          </div>
                          <div class="ribbon bg-success text-lg" style="text-align: center; min-height: 10px;">
                              <i class="fa fa-regular fa-link"></i>
                              <a href="{{asset('//forms.gle/3m3L4NL4D1Tm2YTbA')}}" target="_blank">Más Información</a>
                          </div>
                        </div>
                        <hr/>
                      </div>
                      <div class="col-sm-4">
                        <div class="position-relative" style="min-height: 180px;">
                          <img src="{{asset('img/superior/imgllamado/2.png')}}" alt="Photo 3" class="img-fluid">
                          <div class="ribbon-wrapper ribbon-lg">
                            <div class="ribbon bg-warning text-lg">
                              ZONA V
                            </div>
                          </div>
                          <div class="ribbon bg-success text-lg" style="text-align: center; min-height: 10px;">
                              <i class="fa fa-regular fa-link"></i>
                              <a href="{{asset('//forms.gle/3m3L4NL4D1Tm2YTbA')}}" target="_blank">Más Información</a>
                          </div>
                        </div>
                        <hr/>
                      </div>
                    </div>
                 @endif
             </div>           
        </section>
    </section>
 </section>

@endsection

@section('Script')
  <script>
    $("#toggleTable").click(function () {
        $("#tablaCursos").slideToggle();
    });
  </script>
    <script type="text/javascript" charset="utf-8">
        $(document).ready(function() {
            $('#titulosTab').dataTable( {
                "aaSorting": [[ 0, "desc" ]],
                "oLanguage": {
                    "sLengthMenu": "Ob _MENU_ por página",
                    "search": "Buscar:",
                    "oPaginate": {
                        "sPrevious": "Anterior",
                        "sNext": "Siguiente"
                    }
                }
            } );
        } );
        $(document).ready(function() {
            $('#certificadosTab').dataTable( {
                "aaSorting": [[ 0, "desc" ]],
                "oLanguage": {
                    "sLengthMenu": "Ob _MENU_ por página",
                    "search": "Buscar:",
                    "oPaginate": {
                        "sPrevious": "Anterior",
                        "sNext": "Siguiente"
                    }
                }
            } );
        } );
    </script>
    <script src="{{ asset('js/funcionesvarias.js') }}"></script>
            @if (session('ConfirmarAgregarTitCer')=='OK')
                <script>
                Swal.fire(
                    'Registro guardado',
                    'Se cargo correctamente un nuevo titulo/certificado',
                    'success'
                        )
                </script>
            @endif
        <script>

        $('.formularioTituloYCertificado').submit(function(e){
            if($("#DNI").val()=="" ||
            $("#Apellido").val()=="" ||
            $("#Nombre").val() == ""){
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
                title: 'Esta seguro de querer agregar un Titulo?',
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
    <script src="{{ asset('js/funcionesvarias.js') }}"></script>
        @if (session('ConfirmarEliminarDivision')=='OK')
            <script>
            Swal.fire(
                'Registro Eliminado Exitosamente',
                'Se desvinculo correctamente',
                'success'
                    )
            </script>
        @endif
        @if (session('ConfirmarEliminarDivisionFallida')=='OK')
          <script>
              Swal.fire(
              'Error al borrar Registro',
              'No se puede borrar, debido a que esta vinculado a docente/s',
              'error'
                  )
          </script>
       @endif
     <script>
        function validarFecha() {
            var fechaInput = document.getElementById('fecha').value;
            var regex = /^\d{4}-\d{2}-\d{2}$/;
            if (!regex.test(fechaInput)) {
                //alert('Formato de fecha inválido. Por favor, ingrese una fecha válida en el formato YYYY-MM-DD.');
                document.getElementById('fecha').focus();
                Swal.fire({
                  icon: "error",
                  title: "Oops...",
                  text: "Formato de fecha inválido. Por favor, ingrese una fecha válida en el formato Día-Mes-Año",
      
                });
                return false; // Retorna false si el formato de fecha es inválido
            }
      
            // Dividir la fecha en sus componentes
            var partesFecha = fechaInput.split("-");
            var año = parseInt(partesFecha[0]);
            var mes = parseInt(partesFecha[1]);
            var dia = parseInt(partesFecha[2]);
      
            // Verificar si el año es válido (entre 1000 y 9999)
            if (año < 1000 || año > 9999) {
                Swal.fire({
                  icon: "error",
                  title: "Oops...",
                  text: "Año inválido. Por favor, ingrese un año válido entre 1000 y 9999",
      
                });
                return false;
            }
      
            // Verificar si el mes es válido (entre 1 y 12)
            if (mes < 1 || mes > 12) {
                Swal.fire({
                  icon: "error",
                  title: "Oops...",
                  text: "Mes inválido. Por favor, ingrese un mes válido entre 01 y 12",
      
                });
                return false;
            }
      
            // Verificar si el día es válido
            var diasEnMes = new Date(año, mes, 0).getDate();
            if (dia < 1 || dia > diasEnMes) {
                Swal.fire({
                  icon: "error",
                  title: "Oops...",
                  text: "Día inválido para el mes y año especificados. Por favor, ingrese un día válido",
      
                });
                return false;
            }
      
            // Si pasa todas las validaciones, retorna true
            return true;
        }
        function validarFecha2() {
            var fechaInput = document.getElementById('fechaEgreso').value;
            var regex = /^\d{4}-\d{2}-\d{2}$/;
            if (!regex.test(fechaInput)) {
                //alert('Formato de fecha inválido. Por favor, ingrese una fecha válida en el formato YYYY-MM-DD.');
                document.getElementById('fechaEgreso').focus();
                Swal.fire({
                  icon: "error",
                  title: "Oops...",
                  text: "Formato de fecha inválido. Por favor, ingrese una fecha válida en el formato Día-Mes-Año",
      
                });
                return false; // Retorna false si el formato de fecha es inválido
            }
      
            // Dividir la fecha en sus componentes
            var partesFecha = fechaInput.split("-");
            var año = parseInt(partesFecha[0]);
            var mes = parseInt(partesFecha[1]);
            var dia = parseInt(partesFecha[2]);
      
            // Verificar si el año es válido (entre 1000 y 9999)
            if (año < 1000 || año > 9999) {
                Swal.fire({
                  icon: "error",
                  title: "Oops...",
                  text: "Año inválido. Por favor, ingrese un año válido entre 1000 y 9999",
      
                });
                return false;
            }
      
            // Verificar si el mes es válido (entre 1 y 12)
            if (mes < 1 || mes > 12) {
                Swal.fire({
                  icon: "error",
                  title: "Oops...",
                  text: "Mes inválido. Por favor, ingrese un mes válido entre 01 y 12",
      
                });
                return false;
            }
      
            // Verificar si el día es válido
            var diasEnMes = new Date(año, mes, 0).getDate();
            if (dia < 1 || dia > diasEnMes) {
                Swal.fire({
                  icon: "error",
                  title: "Oops...",
                  text: "Día inválido para el mes y año especificados. Por favor, ingrese un día válido",
      
                });
                return false;
            }
      
            // Si pasa todas las validaciones, retorna true
            return true;
        }
        document.getElementById('fecha').addEventListener('blur', validarFecha);
        document.getElementById('fechaEgreso').addEventListener('blur', validarFecha2);
      </script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.btnActualizar', function(e) {
                e.preventDefault(); 

                let form = $(this).closest('form');

                let formData = {
                    _token: form.find('input[name="_token"]').val(),
                    codliq: form.find('#codliq').val(),
                    descescuela: form.find('#descescuela').val(),
                    codtrabajo: form.find('#codtrabajo').val(),
                    codarea: form.find('#codarea').val(),
                    idPof: form.find('#idPof').val()
                };

                function padLeft(value, length) {
                    if (isNaN(value)) {
                        return value; // Si no es numérico, devuelve el valor sin cambios
                    }
                    return value.toString().padStart(length, '0');
                }

                $.ajax({
                    url: "{{ route('ActualizarPofmhRecibo') }}",
                    method: "POST",
                    data: formData,
                    success: function(response) {
                        Swal.fire(
                        'Registro Actualizado Exitosamente',
                        'Periodicamente controle estos datos, hasta que queden sincronizados. Gracias',
                        'success'
                            )
                        
                        let codliqValue = form.find('#codliq').val(); 
                        let codtrabajoValue = form.find('#codtrabajo').val(); 

                        let formattedCodliq = padLeft(codliqValue, 3); 
                        let formattedCodtrabajo = padLeft(codtrabajoValue, 3); 

                        
                        form.find('#codliq').val(formattedCodliq);
                        form.find('#codtrabajo').val(formattedCodtrabajo);
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            alert("Errores: " + Object.values(errors).join("\n"));
                        } else {
                            alert("Error al actualizar los datos.");
                        }
                    }
                });
            });
        });
    </script>
    <script>
      $(document).ready(function () {
          $('#myTable').DataTable({
              responsive: true,
              dom: 'Bfrtip', // Botones arriba de la tabla
              buttons: [
                  {
                      extend: 'copyHtml5',
                      text: 'Copiar',
                      className: 'btn btn-secondary'
                  },
                  {
                      extend: 'excelHtml5',
                      text: 'Exportar a Excel',
                      className: 'btn btn-success'
                  },
                  {
                      extend: 'pdfHtml5',
                      text: 'Exportar a PDF',
                      className: 'btn btn-danger'
                  },
                  {
                      extend: 'print',
                      text: 'Imprimir',
                      className: 'btn btn-info'
                  }
              ],
              language: {
                  url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' // Español
              }
          });
      });
    </script>

    <!-- jQuery y Bootstrap JS -->
    <script src="{{ asset('https://code.jquery.com/jquery-3.7.0.min.js')}}"></script>
    <script src="{{ asset('https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js')}}"></script>

    <!-- DataTables y extensiones -->
    <script src="{{ asset('https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{ asset('https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js')}}"></script>
    <script src="{{ asset('https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js')}}"></script>
    <script src="{{ asset('https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js')}}"></script>
    <script src="{{ asset('https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js')}}"></script>

    <!-- Para exportar a Excel, PDF -->
    <script src="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js')}}"></script>
    <script src="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js')}}"></script>
    <script src="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js')}}"></script>

    <!-- Traducción a español -->
    <script src="{{ asset('//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json')}}"></script>
@endsection