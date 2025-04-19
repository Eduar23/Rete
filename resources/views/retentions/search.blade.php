@extends('layouts.app')

@section('content')
    <!-- Menú de navegación responsive -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route('menu') }}">Inicio</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('retention.create') }}">Generar Retención</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('retention.search') }}">Buscar Retenciones</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('retention.report') }}">Reportes</a>
                </li>
            </ul>

            @auth
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userMenu" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="px-3 py-1">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger w-100">
                                    Cerrar Sesión
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
            @endauth
        </div>
    </div>
</nav>

<div class="container">

    <h2>Buscar Retenciones</h2>
    <form method="GET" action="{{ route('retention.search') }}">
        <div class="form-group">
            <label>Nombre del Comercio</label>
            <input type="text" name="nombre_comercio" class="form-control" placeholder="Ingrese nombre del comercio" value="{{ request('nombre_comercio') }}">
        </div>
        <button type="submit" class="btn btn-primary">Buscar</button>
    </form>

    @if(request()->has('nombre_comercio') && !empty(request('nombre_comercio')))
        @if($retentions->count() > 0)
            <h3 class="mt-4">Resultados de la búsqueda</h3>
            <!-- Contenedor con scroll vertical -->
            <div style="max-height: 400px; overflow-y: auto;">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Número de Retención</th>
                            <th>Nombre del Comercio</th>
                            <th>Monto Retenido</th>
                            <th>Fecha de Retención</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($retentions as $retention)
                        <tr>
                            <td>{{ $retention->nro_comprobante }}</td>
                            <td>{{ $retention->nombre_comercio }}</td>
                            <td>
                                @php
                                    $iva = $retention->base_imponible * 0.16;
                                    $porcentaje = (float) $retention->retencion_porcentaje;
                                    $impuesto_retenido = $iva * ($porcentaje / 100);
                                @endphp
                                {{ number_format($impuesto_retenido, 2, ',', '.') }}
                            </td>
                            <td>{{ date('d/m/Y', strtotime($retention->fecha_emision)) }}</td>
                            <td>
                                <a href="{{ route('retention.pdf', $retention->id) }}" target="_blank" class="btn btn-info btn-sm">
                                    Reimprimir
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="mt-4">No se encontraron retenciones para el comercio ingresado.</p>
        @endif
    @endif
</div>
@endsection
