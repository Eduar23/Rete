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
    <h2>Reportes de Retenciones</h2>
    <form method="GET" action="{{ route('retention.report') }}">
        <div class="form-group">
            <label>Fecha Inicio</label>
            <input type="date" name="fecha_inicio" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Fecha Fin</label>
            <input type="date" name="fecha_fin" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Tipo de Reporte</label>
            <select name="tipo_reporte" class="form-control" required>
                <option value="quincenal">Quincenal</option>
                <option value="mensual">Mensual</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Generar Reporte</button>
    </form>

    @if(isset($retentions))
        <h3 class="mt-4">Reporte</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Número de Retención</th>
                    <th>Nombre del Comercio</th>
                    <th>Monto Retenido</th>
                    <th>Fecha de Retención</th>
                </tr>
            </thead>
            <tbody>
                @php $totalSum = 0; @endphp
                @foreach($retentions as $retention)
                @php
                    $iva = $retention->base_imponible * 0.16;
                    $impuesto_retenido = $iva * ($retention->retencion_porcentaje / 100);
                    $totalSum += $impuesto_retenido;
                @endphp
                <tr>
                    <td>{{ $retention->nro_comprobante }}</td>
                    <td>{{ $retention->nombre_comercio }}</td>
                    <td>{{ number_format($impuesto_retenido, 2, ',', '.') }}</td>
                    <td>{{ date('d/m/Y', strtotime($retention->fecha_emision)) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <h4>Total Retenido: {{ number_format($totalSum, 2, ',', '.') }}</h4>
    @endif
</div>
@endsection
