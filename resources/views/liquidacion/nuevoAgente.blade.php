@extends('layout.app')

@section('Titulo', 'Sage2.0 - Nuevo Agente')
@section('LinkCSS')
    <style>
        .modal-custom-width {
            width: 90%;
            max-width: none;
        }
    </style>
@endsection
@section('ContenidoPrincipal')
    <div class="container">
        <h2>Nuevo Agente</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('formNuevoAgenteLiquidacion') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="nombre">Nombre y Apellido</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>

            <div class="form-group">
                <label for="plaza_id">Plaza ID</label>
                <input type="number" class="form-control" id="plaza_id" name="plaza_id" required>
            </div>

            {{-- Puedes agregar más campos aquí según lo que pida tu tabla --}}

            <button type="submit" class="btn btn-primary mt-3">Guardar Agente</button>
        </form>
    </div>
@endsection
