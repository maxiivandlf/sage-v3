<!DOCTYPE html>
<html lang="en" translate="no">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('Titulo')</title>

    @livewireStyles

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css') }}">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
        href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- JQVMap -->
    {{-- <link rel="stylesheet" href="{{ asset('plugins/jqvmap/jqvmap.min.css') }}"> --}}
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">
    <!--Style dropzone subir doc-->
    <link rel="stylesheet" href="{{ asset('plugins/dropzone/min/dropzone.min.css') }}">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">

    <!--Style Bootstrap-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <!--Style MaterialGoogle-->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20,400,0,0" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">

    <!--CSS personalizados-->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style_vincular.css') }}">
    <link rel="stylesheet" href="{{ asset('css/reloj.css') }}">
    <link rel="stylesheet" href="{{ asset('css/barraprogreso.css') }}">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    @yield('LinkCSS')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- control para ancho de select --}}
    <style>
        .custom-select option {
            width: 723px;
            /* Ancho máximo inicial */
            max-width: 100%;
            /* Limita el ancho máximo al tamaño del contenedor */
            overflow-x: auto;
            /* Oculta el desbordamiento de contenido */
        }

        .nav-separator {
            height: 1px;
            /* Altura del separador */
            background-color: #ccc;
            /* Color del separador */
            margin: 5px 0;
            /* Espacio alrededor del separador */
            list-style: none;
            /* Elimina los estilos de viñeta de la lista */
        }

        #reloj {
            font-size: 1.5rem;
            font-family: Arial, sans-serif;
            color: #333;
            text-align: center;
            margin-top: 10px;
        }
    </style>

</head>
<!--BODY-->
@if (session('Validar') != '')

    <body class=" sidebar-mini layout-fixed ">
        {{-- <div class="loader"></div>  --}}

        <div class=""> <!-- Aquí era así <div class="wrapper"> -->

            <!-- Preloader con barra de progreso -->
            <div id="preloader">
                <!-- Imagen del preloader -->
                <div class="preloader-img mb-4">
                    <img src="{{ asset('img/logo_gob_lr.png') }}" alt="SAGE2.0" height="100">
                </div>
                <!-- Barra de progreso -->
                <div class="spinner text-center">
                    <!--<div class="progress-bar bg-primary progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>-->
                    <div class="spinner-grow gb-trabajo" role="status"></div>
                    <div class="spinner-grow gb-energia" role="status"></div>
                    <div class="spinner-grow gb-conectividad" role="status"></div>
                    <div class="spinner-grow gb-sostenibilidad" role="status"></div>
                </div>
            </div>


            <!--Hamburguesa-->
            <nav class="main-header navbar navbar-expand navbar-light">
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item"> <a href="#" class="nav-link" data-widget="pushmenu" role="button">
                            <i class="fas fa-bars"></i></a>
                    </li>
                    <li class="nav-item d-none d-sm-inline-block">
                        <marquee style="color:red;font-size:24px;">Durante la jornada, el control de Ipe sé activara en
                            modo observación . Gracias
                        </marquee>
                        <div style="display: flex; align-items: center;">
                            <div class="col-12">
                                <a href="#" class="nav-link h5" style="margin-right: 10px;">
                                    <?php
                                    
                                    //consulto su Unidad o Unidades de Liquidacion
                                    $infoUnidLiq = DB::connection('DB8')->table('instarealiq')->where('instarealiq.CUEA', session('CUECOMPLETOBASE'))->groupBy('instarealiq.escu')->select('instarealiq.escu')->get();
                                    
                                    $liqText = '';
                                    foreach ($infoUnidLiq as $unidliq) {
                                        // Validación más explícita para asegurarse de que 'escu' no sea vacío ni nulo
                                        $liqText .= !empty($unidliq->escu) ? $unidliq->escu : 'S/D';
                                        $liqText .= ' / '; // Añadir un separador solo si no es el último
                                    }
                                    //echo rtrim($liqText, ' / '); // Remueve el último " / "
                                    
                                    //echo "hab:".$EstadoHabilitado."--";
                                    
                                    if (session('Modo') == 4) {
                                        echo 'Sistema de Liquidaciones - ' . session('Usuario');
                                    } elseif (session('Modo') != 13) {
                                        if (session('Modo') == 44) {
                                            echo 'Sistema control SAGE - Gestión Privada';
                                        } elseif (session('Modo') == 45) {
                                            echo 'Sistema control SAGE - Gestión Municipal';
                                        } elseif (session('Nombre_Institucion')) {
                                            echo session('Nombre_Institucion') . ' - CUE: ' . session('CUECOMPLETO') . ' - Unidad de Liquidación: ' . ($liqText ? '<span style="color:green">' . rtrim($liqText, ' / ') . '<span>' : '<span style="color:red"> No se encontró unidad de liquidación</span>');
                                        } else {
                                            echo 'Sistema control SAGE';
                                        }
                                    } else {
                                        echo 'Cuenta Multiusuario';
                                    }
                                    
                                    ?>
                                </a>

                            </div>
                            {{-- Alertas segun MODO  --}}
                            <div class="col-6">
                                @if (session('Modo') == 4)
                                    <div class="alert alert-info alert-dismissible"
                                        style="margin-right: 10px;width:250px;">
                                        <h5><i class="icon fas fa-check"></i> Aviso</h5>
                                        No hay novedades <b>LIQUIDACION</b>
                                    </div>
                                @endif
                            </div>

                        </div>
        </div>
        </li>

        </ul>
        </nav>


        <div class="row main-header">
            <section class="content-header">
                <div class="container-fluid">
                    <ol id="breadcrumb" class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href={{ route('dashboardRedirect') }}>INICIO</a></li>

                        @if (session('Modo') == 4)
                            @php
                                $currentUrl = url()->current();
                                $routeName = Route::currentRouteName();
                                $breadcrumbName = strtoupper(str_replace('_', ' ', last(explode('.', $routeName))));
                                $breadcrumbName = str_replace(' LIQ', '', $breadcrumbName);

                                echo '
                                    <li class="breadcrumb-item active">
                                        <a href="' .
                                    $currentUrl .
                                    '">' .
                                    $breadcrumbName .
                                    '</a>
                                </li>
                            ';
                            @endphp
                        @else
                            @php
                                echo session('ruta');
                            @endphp
                        @endif

                    </ol>
                </div>
            </section>
        </div>

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="{{ route('dashboardRedirect') }}" class="brand-link">
                <img src="{{ asset('img/logo_larioja.png') }}" alt="SAGE"
                    class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">SAGE</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        @php
                            $InfoUsuario = session('InfoUsuario');
                        @endphp
                        <img src="{{ asset('img/' . $InfoUsuario->avatar) }}" class="img-circle elevation-2"
                            alt="User Image">
                    </div>
                    <div class="info">
                        <a href="{{ route('perfilMulticuenta') }}" class="d-block">{{ session('Usuario') }}</a><br>
                        @if (session('Modo') != 13)
                            <a href="#"
                                class="d-block">{{ session('NombreInstitucion') }}({{ session('TurnoDescripcion') }})</a>
                        @else
                            <a href="#" class="d-block">Usuario {{ session('NombreInstitucion') }}</a>
                        @endif
                        <div id="reloj" style="color:green"></div>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item">
                            <a href={{ route('dashboardRedirect') }} class="nav-link">
                                <i class="nav-icon fas fa-th"></i>
                                <p>
                                    Inicio
                                </p>
                            </a>
                        </li>

                        {{-- Liquidación --}}

                        <li class="nav-item menu-is-opening menu-open">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-search"></i>
                                <p>
                                    Consultas
                                    <i class="fas fa-angle-left right"></i>
                                    <span class="badge badge-info right"><!--aqui algo--></span>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('buscar_dni_liq') }}" class="nav-link">
                                        <i class="far fa-id-card nav-icon"></i>
                                        <p>Buscar por DNI</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('buscar_cue_liq') }}" class="nav-link">
                                        <i class="fas fa-school nav-icon"></i>
                                        <p>Buscar por CUE</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item menu-is-opening menu-open">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-university"></i>
                                <p>
                                    Instituciones Educativas
                                    <i class="fas fa-angle-left right"></i>
                                    <span class="badge badge-info right"><!--aqui algo--></span>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('listarInstarealiq') }}" class="nav-link">
                                        <i class="fas fa-info-circle nav-icon"></i>
                                        <p><span style="color:yellow">Informacón Instituciones</span></p>
                                    </a>
                                </li>

                            </ul>
                        </li>
                        <li class="nav-item menu-is-opening menu-open">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-calendar-alt"></i>
                                <p>
                                    Control Mensual
                                    <i class="fas fa-angle-left right"></i>
                                    <span class="badge badge-info right"><!--aqui algo--></span>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('cargarExcelLiquidacion') }}" class="nav-link">
                                        <i class="far fa-file-excel nav-icon"></i>
                                        <p>Carga Excel</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('compararDatosLiquidacion') }}" class="nav-link">
                                        <i class="fas fa-clipboard-check nav-icon"></i>
                                        <p>Control IPE</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('traerTodoAgenteLiq') }}" class="nav-link">
                                        <i class="fas fa-users nav-icon"></i>
                                        <p>Lista total de Agentes<span style="color:yellow">(Experimental)</span>
                                        </p>
                                    </a>
                                </li>
                            </ul>
                        </li>


                        <li class="nav-header">Opciones</li>
                        <li class="nav-item">
                            <a href="{{ route('Salir') }}" class="nav-link">
                                <i class="nav-icon fas fa-sign-out-alt"></i>
                                <p>
                                    Salir
                                    <span class="badge badge-info right"><!--aqui algo--></span>
                                </p>
                            </a>
                        </li>

                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>
    @else
@endif

<section class="content">
    <div class="container-fluid">
        @yield('ContenidoPrincipal')
    </div>
</section>




<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

@livewireScripts
<!-- jQuery -->
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- ChartJS -->
<script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset('plugins/jquery-knob/jquery.knob.min.js') }}"></script>
<!-- daterangepicker -->
<script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- Summernote -->
<script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- DataTables  & Plugins -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>

<script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.js') }}"></script>
<script src="{{ asset('js/arbol.js') }}"></script>
<script src="{{ asset('js/funcionesvarias.js') }}"></script>
{{-- <script src="{{ asset('js/reloj.js') }}"></script> --}}
<!-- SweetAlert2 -->
<script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<!-- Toastr -->
<script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
<!--subir doc-->
<script src="{{ asset('plugins/dropzone/min/dropzone.min.js') }}"></script>
<script src="{{ asset('js/subirDoc.js') }}"></script>
<script src="{{ asset('js/barraprogreso.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
@yield('Script')
<script type="text/javascript">
    $(window).on('load', function() {
        $(".loader").fadeOut("slow")
    })
</script>
<!-- Page specific script -->
{{-- <script>
    $(function() {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "order": [
                [0, 'desc']
            ]
            //"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)')

        $("#example3").DataTable({
            "dom": 'lBfrtip', // Muestra el control de longitud, el botón, el campo de búsqueda, la tabla, la paginación
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "order": [
                [0, 'desc']
            ]
        });
        $("#examplelogs").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "order": [
                [0, 'desc']
            ]
            //"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)')
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        })
        $('#tablalogs').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "order": [
                [8, 'asc']
            ] // Ordenar por la columna 9 (índice 8) en orden ascendente

        })
        $('#tecnicoSage').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "order": [
                [8, 'asc']
            ] // Ordenar por la columna 9 (índice 8) en orden ascendente

        })
        $("#example4").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "order": [
                [0, 'desc']
            ]
            //"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)')

        $("#detalles99999").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "order": [
                [0, 'desc']
            ]
            //"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)')
    })


    // Agrega un evento click al enlace de borrado
    $('#borrarCarga').click(function(e) {
        e.preventDefault(); // Evita que se siga el enlace automáticamente

        // Muestra la ventana de alerta
        Swal.fire({
            title: '¿Está seguro de querer borrar toda la carga?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, borrar toda la carga'
        }).then((result) => {
            if (result.isConfirmed) {
                // Si el usuario confirma, redirige a la URL deseada
                window.location.href = "{{ route('limpiar_carga') }}";
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Delegación para la exportación de la tabla a Excel
        document.addEventListener('click', function(event) {
            if (event.target && event.target.id === 'btn-exportar') {
                const nivelSeleccionado = event.target.getAttribute('data-nivel');
                const table = document.getElementById('tablapofs');

                if (table) {
                    const workbook = XLSX.utils.table_to_book(table, {
                        sheet: "Hoja1"
                    });
                    const nombreArchivo = 'datos_pof_' + nivelSeleccionado + '.xlsx';
                    XLSX.writeFile(workbook, nombreArchivo);
                } else {
                    alert('No se encontró la tabla para exportar');
                }
            }
        });

        // Delegación para la impresión del modal sin recargar
        document.addEventListener('click', function(event) {
            if (event.target && event.target.id === 'btn-imprimir') {
                // Guarda el contenido original del body
                const originalContents = document.body.innerHTML;

                // Obtiene el contenido del modal para imprimir
                const printContents = document.querySelector('#modal-pof .modal-body').innerHTML;

                // Cambia el contenido del body al del modal
                document.body.innerHTML = printContents;

                // Ejecuta la impresión
                window.print();

                // Restaura el contenido original del body
                document.body.innerHTML = originalContents;

                // Restaura el modal y reabre el modal si se cerró
                $('#modal-pof').modal('show');
                // Si el overlay persiste, se elimina al cerrar
                document.querySelector('.modal-backdrop').remove();
            }
        });
    });
</script> --}}
{{-- <script>
    @if (session('ActivarSplashInicial') == 'OK')
        /*Swal.fire(
          'Registro guardado',
          'Se actualizó correctamente',
          'success'
              )*/
    @endif


    document.addEventListener('DOMContentLoaded', function() {
        // Obtener la URL actual
        const currentUrl = window.location.pathname;

        // Verificar si la URL contiene "verPofMhidExt"
        if (currentUrl.includes('verPofMhidExt')) {
            // Seleccionar el elemento <body>
            const bodyElement = document.body;

            // Agregar la clase "sidebar-collapse"
            bodyElement.classList.add('sidebar-collapse');
        }
    });

    $(document).ready(function() {
        var rutaListaSupervisoraMensajes = @json(route('listaSupervisoraMensajes'));

        // Función para actualizar el reloj cada segundo
        function actualizarReloj() {
            var fecha = new Date(); // Obtenemos la fecha y hora actual
            var horas = fecha.getHours().toString().padStart(2, "0");
            var minutos = fecha.getMinutes().toString().padStart(2, "0");
            var segundos = fecha.getSeconds().toString().padStart(2, "0");

            // Mostramos la hora en el div con id "reloj"
            $("#reloj").text(horas + ":" + minutos + ":" + segundos);
        }

        // Llamamos a la función de actualización cada segundo
        setInterval(actualizarReloj, 1000);

        function ejecutarConsultaPeriodica() {
            // Aquí va la lógica de la consulta
            //console.log("Ejecutando consulta a la base de datos...");

            $.ajax({
                url: "/consultaPruebaNovedad",
                type: "GET",
                success: function(response) {
                    /* console.log(
                         "Se encontraron: " +
                             response.Cantidad +
                             " de novedades sin procesar"
                     );*/
                    // Seleccionamos el li donde se agregará la alerta
                    var alertaMensajesLi = $("#AlertaMensajes");
                    // Verificar si hay alertas
                    if (response.Cantidad > 0) {
                        // Si hay alertas, agregar el contenido al li
                        var contenidoAlertas = `
                        <a href="${rutaListaSupervisoraMensajes}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Hay Mensajes <i class="far fa-envelope" style="color:yellow"></i><span class="badge badge-info right">${response.Cantidad}</span></p>
                            </a>
                        
                    `;

                        // Colocar el contenido dentro del li
                        alertaMensajesLi.html(contenidoAlertas);
                    } else {
                        // Si no hay alertas, dejar el li vacío
                        alertaMensajesLi.html("");
                    }
                },
                error: function() {
                    console.log("Error al ejecutar la consulta");
                },
            });
        }

        setInterval(ejecutarConsultaPeriodica, 3000);
    });
</script> --}}

@livewireScripts

</body>

</html>
