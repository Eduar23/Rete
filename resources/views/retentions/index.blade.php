@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Listado de Comprobantes de Retención</h2>
    <form method="GET" action="{{ route('retention.index') }}" class="mb-4">
        <input type="text" name="buscar" placeholder="Buscar por comercio o factura" value="{{ request('buscar') }}">
        <button type="submit" class="btn btn-primary">Buscar</button>
    </form>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre del Comercio</th>
                <th>Factura</th>
                <th>Fecha Emisión</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($retentions as $retention)
            <tr>
                <td>{{ $retention->id }}</td>
                <td>{{ $retention->nombre_comercio }}</td>
                <td>{{ $retention->numero_factura }}</td>
                <td>{{ $retention->fecha_emision }}</td>
                <td>
                    <a href="{{ route('retention.pdf', $retention->id) }}" target="_blank" class="btn btn-info">Ver PDF</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $retentions->links() }}
</div>
@endsection
