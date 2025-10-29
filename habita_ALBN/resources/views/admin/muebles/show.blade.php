@extends('cabecera')

@section('titulo', 'Detalle mueble')

@section('contenido')
    <h2>Detalle de mueble</h2>
    <p><strong>Nombre:</strong> {{ $mueble['nombre'] }}</p>
    <p><strong>Descripcion:</strong> {{ $mueble['precio'] ?? '-' }}</p>
    <br>
    <a href="{{ route('categorias.index') }}">Volver al listado</a>
@endsection