@extends('layout.app')
@section('Titulo', 'Sage2.0 - Nivel Superior - Crear Llamado')
@section('ContenidoPrincipal')
@section('LinkCSS')
    {{-- para superior --}}
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">

    <link rel="stylesheet" href="{{ asset('css/superior/tablallamado.css') }}">  
    <!--fin superior -->
@endsection

@section('content')
<div class="container">
    <h2>Llamado: ID #{{ $llamado->idllamado }}</h2>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <h2>Llamado: ID #{{ $llamado->idllamado }}</h2>

    

    <p><strong>Zona:</strong> {{ $llamado->idtb_zona }}</p>
    <p><strong>Instituto:</strong> {{ $llamado->id_instituto_superior }}</p>
    <p><strong>Carrera:</strong> {{ $llamado->idCarrera }}</p>
    p><strong>Tipo de llamado:</strong> {{ $llamado->idtipo_llamado }}</p>
    <p><strong>Fecha Inicio:</strong> {{ $llamado->fecha_ini }}</p>
    <p><strong>Fecha Fin:</strong> {{ $llamado->fecha_fin }}</p>
    <p><strong>Descripción:</strong> {{ $llamado->descripcion }}</p>
    <hr>

    {{-- Espacio para agregar cargos o espacios --}}
    <h4>Agregar Cargos o Espacios Curriculares</h4>
    <p>Aquí podés agregar bloques dinámicos para cargar información.</p>

    {{-- Por ejemplo, podrías poner botones para abrir modales o forms --}}
    <a href="#" class="btn btn-primary">Agregar Cargo</a>
    <a href="#" class="btn btn-secondary">Agregar Espacio Curricular</a>

</div>
@endsection
