@extends('cabecerauser')

@section('titulo', $mueble->getNombre())

@section('contenido')
    <h2>{{ $mueble->getNombre() }}</h2>
    <p><strong>Descripción:</strong> {{ $mueble->getDescripcion() ?? '-' }}</p>
    <p><strong>Precio:</strong> {{ $mueble->getPrecio() }} €</p>
    <p><strong>Stock:</strong> {{ $mueble->getStock() }}</p>
    <p><strong>Materiales:</strong> {{ $mueble->getMateriales() ?? '-' }}</p>
    <p><strong>Dimensiones:</strong> {{ $mueble->getDimensiones() ?? '-' }}</p>
    <p><strong>Color principal:</strong> {{ $mueble->getColorPrincipal() ?? '-' }}</p>
    <p><strong>Destacado:</strong> {{ $mueble->getDestacado() ? 'Sí' : 'No' }}</p>

    <br>
    <a href="{{ url()->previous() }}" class="btn-volver">Volver</a>
@endsection
