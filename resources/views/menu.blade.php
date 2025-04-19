@extends('layouts.app')

@section('content')
<style>
    .menu-card {
        transition: transform 0.3s ease;
        border-radius: 15px;
    }
    .menu-card:hover {
        transform: scale(1.02);
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }
    .navbar-brand {
        font-weight: bold;
        font-size: 1.25rem;
    }
</style>

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

<!-- Cuerpo principal -->
<div class="container mt-4">
    <div class="text-center mb-5">
        <h2 class="font-weight-bold">SERVICIOS TÉCNICOS HORIZONTE, C.A.</h2>
        <p class="lead">Sistema para gestionar de manera fácil las retenciones directamente desde cualquier dispositivo.</p>
    </div>

    <div class="row">
        <!-- Opción 1 -->
        <div class="col-md-4 mb-4">
            <div class="card menu-card shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title font-weight-bold">Generar Retención</h5>
                    <p class="card-text flex-grow-1">
                        Gestiona una nueva retención tras una compra con factura emitida. Se genera un comprobante en PDF.
                    </p>
                    <a href="{{ route('retention.create') }}" class="btn btn-primary mt-auto">Ingresar</a>
                </div>
            </div>
        </div>

        <!-- Opción 2 -->
        <div class="col-md-4 mb-4">
            <div class="card menu-card shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title font-weight-bold">Buscar Retenciones</h5>
                    <p class="card-text flex-grow-1">
                        Consulta las retenciones generadas por nombre del comercio y reimprímelas si es necesario.
                    </p>
                    <a href="{{ route('retention.search') }}" class="btn btn-info mt-auto">Buscar</a>
                </div>
            </div>
        </div>

        <!-- Opción 3 -->
        <div class="col-md-4 mb-4">
            <div class="card menu-card shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title font-weight-bold">Reportes</h5>
                    <p class="card-text flex-grow-1">
                        Genera reportes detallados de retenciones por rangos quincenales o mensuales, con totales acumulados.
                    </p>
                    <a href="{{ route('retention.report') }}" class="btn btn-success mt-auto">Generar Reporte</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

