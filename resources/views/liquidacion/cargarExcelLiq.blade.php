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
            z-index: 9999;
        }

        #archivo {
            height: 50px;
        }

        #archivo::-webkit-file-upload-button {
            padding: 10px 20px;
            background-color: #2a9995;
            text-align: center;
            font-weight: 600;
            color: white;
            font-size: .8rem;
            border: none;
            border-radius: 5px;

        }
    </style>
@endsection
@section('ContenidoPrincipal')
    <div class="container">
        <h2>Cargar Excel </h2>

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
            <div style="display: flex; flex-direction: row; height: fit-content;align-items: center;gap: 10px;">
                <button type="submit" class="btn btn-primary ">Subir y Procesar</button>
                <!-- Loader -->
                <div id="loader" style="flex-direction: row;gap: 10px; align-items: center">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="m-0">Procesando archivo, por favor espere...</p>
                </div>
            </div>
            <div class="progress mt-3" style="display: none;" id="progressContainer">
                <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                    style="height: 20px;width: 0%">0%</div>
            </div>
        </form>

        @livewire('tabla-visualizacion-datos-importados')
    </div>
@endsection

@section('Script')
    {{-- <script>
        // Mostrar el loader al enviar el formulario
        document.getElementById('uploadForm').addEventListener('submit', function() {
            document.getElementById('loader').style.display = 'flex';
        });
    </script> --}}

    <script>
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            e.preventDefault();

            var form = this;
            var fileInput = document.getElementById('archivo');
            if (!fileInput.files.length) return;

            var formData = new FormData(form);

            var xhr = new XMLHttpRequest();

            xhr.open('POST', form.action, true);
            xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

            // Mostrar barra de progreso y loader
            document.getElementById('progressContainer').style.display = 'block';
            document.getElementById('loader').style.display = 'flex';

            xhr.upload.addEventListener('progress', function(e) {
                console.log(e);
                if (e.lengthComputable) {
                    var percent = Math.round((e.loaded / e.total) * 100);
                    var progressBar = document.getElementById('progressBar');
                    progressBar.style.width = percent + '%';
                    progressBar.textContent = percent + '%';
                    if (percent === 100) {
                        setTimeout(function() {
                            document.getElementById('progressContainer').style.display = 'none';
                            document.querySelector('#loader p').textContent =
                                'Archivo subido! Procesando archivo, por favor espere...';
                        }, 500);
                    }
                }
            });

            xhr.onload = function() {
                if (xhr.status === 200) {
                    window.location.reload();
                } else {
                    alert('Error al subir el archivo');
                    document.getElementById('loader').style.display = 'none';
                    document.getElementById('progressContainer').style.display = 'none';
                }
            };

            xhr.send(formData);
        });
    </script>
@endsection
