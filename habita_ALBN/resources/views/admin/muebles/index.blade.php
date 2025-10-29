@extends('cabecera')

@section('titulo', 'Categorias')

@section('contenido')
    <h1>Categorias</h1>
    <p><a href="{{ route('muebles.create') }}">+ Nuevo Mueble</a></p>
    @if (empty($muebles))
        <p>No hay muebles.</p>
    @else
        <table border="1" cellpadding="6" cellspacing="0">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripcion</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($muebles as $mueble)
                    <tr>
                        <td>{{ $mueble['nombre'] }}</td>
                        <td>{{ $mueble['descripcion'] ?? '-' }}</td>
                        <td>
                            <a href="{{ route('muebles.show', $mueble['id']) }}">Ver</a> |
                            <a href="{{ route('muebles.edit', $mueble['id']) }}">Editar</a> |
                            <form method="POST" action="{{ route('categorias.destroy', $mueble['id']) }}" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Eliminar este mueble?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection

