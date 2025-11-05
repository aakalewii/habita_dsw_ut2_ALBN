@extends('cabecera')

@section('titulo', 'Mis Preferencias')

@section('contenido')
    <div class="container">
        <h2>Configuración de Preferencias</h2>

        @if(session('mensaje'))
            <div class="alert alert-success">
                {{ session('mensaje') }}
            </div>
        @endif

        <form action="{{ route('preferencias.update') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="moneda" class="form-label">Moneda</label>
                <select name="moneda" id="moneda" class="form-select">
                    <option value="€" {{ $moneda == '€' ? 'selected' : '' }}>Euro (€)</option>
                    <option value="$" {{ $moneda == '$' ? 'selected' : '' }}>Dólar ($)</option>
                    <option value="£" {{ $moneda == '£' ? 'selected' : '' }}>Libra (£)</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="paginacion" class="form-label">Elementos por página (funcionalidad futura)</label>
                <select name="paginacion" id="paginacion" class="form-select">
                    <option value="6" {{ $paginacion == '6' ? 'selected' : '' }}>6 elementos</option>
                    <option value="12" {{ $paginacion == '12' ? 'selected' : '' }}>12 elementos</option>
                    <option value="24" {{ $paginacion == '24' ? 'selected' : '' }}>24 elementos</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="tema" class="form-label">Tema de la Interfaz</label>
                <select name="tema" id="tema" class="form-select">
                    <option value="claro" {{ $tema == 'claro' ? 'selected' : '' }}>Claro</option>
                    <option value="oscuro" {{ $tema == 'oscuro' ? 'selected' : '' }}>Oscuro</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Guardar Preferencias</button>
        </form>
    </div>
@endsection
