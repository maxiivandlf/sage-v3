@extends('layout.liquidacion')
@section('Titulo', 'Liquidación- Cargar Excel')
@section('LinkCSS')
    <link rel="stylesheet" href="{{ asset('css/pofmh.css') }}">
    <style>
        #tabla-completa {
            font-size: 1rem;
        }

        #tabla-completa td,
        #tabla-completa th {
            font-size: inherit;
        }

        #tabla-completa {
            table-layout: auto;
        }

        /* Ocultar el loader por defecto */
        #loader {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
        }
    </style>
@endsection
@section('ContenidoPrincipal')
    <div class="container">
        <h2>Cargar archivo Excel</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Agregar el ID al formulario -->
        <form action="{{ route('importarExcelLiquidacion') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
            @csrf

            <div class="form-group">
                <label for="archivo">Seleccione el archivo Excel (.xlsx o .xls)</label>
                <input type="file" class="form-control" id="archivo" name="archivo" required accept=".xls,.xlsx">
            </div>

            <button type="submit" class="btn btn-primary mt-3">Subir y Procesar</button>
            <!-- Loader -->
            <div id="loader" class="">
                <div class="spinner-border text-primary" role="status">
                    <span class="">Cargando...</span>
                </div>
                <p class="text-center mt-2">Procesando archivo, por favor espere...</p>
            </div>
        </form>

        @livewire('tabla-visualizacion-datos-importados')
    </div>
@endsection

@section('Script')
    <script>
        // Mostrar el loader al enviar el formulario
        document.getElementById('uploadForm').addEventListener('submit', function() {
            document.getElementById('loader').style.display = 'block';
        });
    </script>
@endsection
