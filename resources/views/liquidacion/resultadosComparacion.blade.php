@extends('layouts.app')
@section('Titulo', 'Liquidación- Comparacion Liquidación')
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
    </style>
@endsection
@section('ContenidoPrincipal')
    <div class="container">
        <h2>Resultado de Comparación</h2>

        @if (empty($inconsistencias))
            <div class="alert alert-success">No se encontraron inconsistencias.</div>
        @else
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Tipo de Inconsistencia</th>
                        <th>Datos del Archivo</th>
                        <th>Datos Oficiales</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($inconsistencias as $inconsistencia)
                        <tr>
                            <td>{{ $inconsistencia['tipo'] }}</td>
                            <td>
                                @if (isset($inconsistencia['dato']))
                                    Nombre: {{ $inconsistencia['dato']->nombre }}<br>
                                    DNI: {{ $inconsistencia['dato']->dni }}<br>
                                    Plaza: {{ $inconsistencia['dato']->plaza }}
                                @elseif(isset($inconsistencia['temp']))
                                    Nombre: {{ $inconsistencia['temp']->nombre }}<br>
                                    DNI: {{ $inconsistencia['temp']->dni }}<br>
                                    Plaza: {{ $inconsistencia['temp']->plaza }}
                                @endif
                            </td>
                            <td>
                                @if (isset($inconsistencia['oficial']))
                                    Nombre: {{ $inconsistencia['oficial']->nombre }}<br>
                                    DNI: {{ $inconsistencia['oficial']->dni }}<br>
                                    Plaza: {{ $inconsistencia['oficial']->plaza }}
                                @else
                                    No existe en oficial
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <a href="{{ route('exportarInconsistencias') }}" class="btn btn-success">Exportar Inconsistencias a Excel</a>
    </div>
@endsection
