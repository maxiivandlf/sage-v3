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

    </style>

</head>
<!--BODY-->

<body class=" sidebar-mini layout-fixed ">
    @if (session('Validar') != '')

        <div class="" style="border-color: aqua; border-width: 2px">

            <div id="preloader">
                <!-- Imagen del preloader -->
                <div class="preloader-img mb-4">
                    <img src="{{ asset('img/logo_gob_lr.png') }}" alt="SAGE2.0" height="100">
                </div>
                <!-- Barra de progreso -->
                <div class="spinner text-center">
                    {{-- <div class="progress-bar bg-primary progress-bar-striped" role="progressbar" aria-valuenow="0"
                        aria-valuemin="0" aria-valuemax="100"></div> --}}
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
                        <div style="display: flex; align-items: center;">
                            <div class="col-12">
                                <a href="#" class="nav-link h5" style="margin-right: 10px;">
                                    <?php
                                    echo 'Sistema de Liquidaciones - ' . session('Usuario');
                                    ?>
                                </a>
                            </div>
                            {{-- Alertas --}}
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
                            <a href="{{ route('perfilMulticuenta') }}" class="d-block">{{ session('Usuario') }}</a>
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
                                        <a href="{{ route('informacionInstituciones') }}" class="nav-link">
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
                                        <a href="{{ route('controlIpe') }}" class="nav-link">
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
        </div>
    @else
    @endif

    <section class="content">
        <div class="container-fluid">
            <section class="content-wrapper">
                @yield('ContenidoPrincipal')
            </section>
        </div>
    </section>




    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->

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



</body>

</html>
